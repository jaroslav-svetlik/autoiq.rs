#!/usr/bin/python3
"""Root-owned AutoIQ release coordinator; application code runs as site users."""
import base64
import fcntl
import hashlib
import json
import os
from pathlib import Path
import pwd
import re
import secrets
import shutil
import subprocess
import sys
import tarfile
import time
import urllib.request

BASE = Path('/srv/sites/autoiq')
STATE = Path('/etc/autoiq/active.json')
DEPLOY = pwd.getpwnam('deploy_autoiq')
WEB = pwd.getpwnam('web_autoiq')
PHP = str(BASE / 'bin/php')


def run(*args, **kwargs):
    return subprocess.run(list(args), check=True, **kwargs)


def link(path, target):
    temp = path.with_name(path.name + '.tmp')
    temp.unlink(missing_ok=True)
    temp.symlink_to(target)
    os.replace(temp, path)


def state():
    return json.loads(STATE.read_text()) if STATE.exists() else None


def artisan(release, *args):
    return subprocess.check_output(['runuser', '-u', 'deploy_autoiq', '--', PHP,
        str(release / 'artisan'), '--no-interaction', '--no-ansi', *args], cwd=release, text=True)


def candidate_check(release):
    link(BASE / 'candidate', release)
    for route in ['/up', '/', '/blog', '/blog?page=2', '/oglasi', '/nalog/prijava', '/kontakt', '/sitemap.xml', '/vendor/livewire/livewire.min.js']:
        request = urllib.request.Request('http://127.0.0.1:2825' + route,
            headers={'Host': 'autoiq.rs', 'User-Agent': 'AutoIQMigrationAudit/1.0'})
        with urllib.request.urlopen(request, timeout=30) as response:
            assert response.status == 200, route



def promote(release, previous):
    candidate_check(release)
    old_target = (BASE / 'current').resolve() if (BASE / 'current').exists() else None
    link(BASE / 'current', release)
    try:
        run('nginx', '-t')
        run('systemctl', 'reload', 'nginx')
        with urllib.request.urlopen('https://autoiq.rs/up', timeout=20) as response:
            assert response.status == 200
        # Restart only this application's queue; graceful timeout preserves in-flight jobs.
        if subprocess.run(['systemctl', 'is-active', '--quiet', 'autoiq-queue']).returncode == 0:
            run('systemctl', 'restart', 'autoiq-queue')
    except Exception:
        if old_target:
            link(BASE / 'current', old_target)
            run('systemctl', 'reload', 'nginx')
        else:
            (BASE / 'current').unlink(missing_ok=True)
        raise
    data = {'release': release.name, 'commit': json.loads((release / 'deployment.json').read_text())['commit'],
            'previous_release': previous['release'] if previous else None}
    STATE.parent.mkdir(mode=0o755, exist_ok=True)
    tmp = STATE.with_suffix('.tmp')
    tmp.write_text(json.dumps(data, indent=2) + '\n')
    tmp.chmod(0o644)
    os.replace(tmp, STATE)
    print(json.dumps(data), flush=True)


def deploy(release_id, commit, expected_hash):
    assert re.fullmatch(r'\d{8}-\d{6}-[a-f0-9]{7,12}', release_id)
    assert re.fullmatch(r'[a-f0-9]{40}', commit)
    assert re.fullmatch(r'[a-f0-9]{64}', expected_hash)
    release = BASE / 'releases' / release_id
    assert not release.exists()
    archive = Path('/var/tmp') / ('autoiq-' + release_id + '.tar')
    with archive.open('xb') as output:
        archive.chmod(0o600)
        size = 0
        while block := sys.stdin.buffer.read(1048576):
            size += len(block)
            assert size < 350 * 1024 * 1024
            output.write(block)
    assert hashlib.sha256(archive.read_bytes()).hexdigest() == expected_hash
    release.mkdir(mode=0o750)
    with tarfile.open(archive) as source:
        for member in source.getmembers():
            path = Path(member.name)
            assert member.isfile() or member.isdir()
            assert not path.is_absolute() and '..' not in path.parts
            assert not any(x in path.parts for x in ['.git', '.env', '.runtime', 'node_modules'])
            assert path.parts[0] != 'vendor'
        source.extractall(release, filter='data')
    archive.unlink()
    hashes = {}
    for path in [release, *release.rglob('*')]:
        os.chown(path, DEPLOY.pw_uid, WEB.pw_gid)
        path.chmod(0o750 if path.is_dir() or path.stat().st_mode & 0o111 else 0o640)
        if path.is_file():
            hashes[str(path.relative_to(release))] = hashlib.sha256(path.read_bytes()).hexdigest()
    for folder in ['storage/app/public', 'storage/app/private', 'storage/framework/cache/data', 'storage/framework/sessions', 'storage/framework/views', 'storage/logs', 'bootstrap/cache']:
        p=release/folder; p.mkdir(parents=True,exist_ok=True); os.chown(p,DEPLOY.pw_uid,WEB.pw_gid); p.chmod(0o770)
    run('chown','-R','deploy_autoiq:web_autoiq',str(release/'storage'))
    run('chmod','-R','u+rwX,g+rX,o-rwx',str(release/'storage'))
    # Tests use an isolated in-memory SQLite DB and fake mail, before linking production .env.
    test_key = 'base64:' + base64.b64encode(secrets.token_bytes(32)).decode()
    command = ('set -eu; php /usr/local/lib/composer.phar validate --no-check-publish; '
        'php /usr/local/lib/composer.phar audit --locked --no-dev; '
        'php /usr/local/lib/composer.phar install --no-interaction --prefer-dist --no-scripts; '
        'npm audit --package-lock-only --audit-level=moderate; npm ci --ignore-scripts --no-audit --no-fund; npm run build; '
        'mkdir -p tests/Unit; php artisan package:discover --no-interaction; php vendor/bin/phpunit; '
        'php /usr/local/lib/composer.phar install --no-dev --no-interaction --prefer-dist --no-scripts --optimize-autoloader')
    run('systemd-run', '--wait', '--pipe', '--collect', '--unit=autoiq-build-' + release_id,
        '--uid=deploy_autoiq', '--gid=web_autoiq', '--working-directory=' + str(release),
        '--property=CPUQuota=200%', '--property=MemoryMax=3G', '--property=Nice=10',
        '--property=TasksMax=256', '--property=NoNewPrivileges=true', '--property=PrivateTmp=true',
        '--setenv=PATH=' + str(BASE / 'bin') + ':/usr/local/bin:/usr/bin:/bin',
        '--setenv=HOME=' + str(BASE / 'var/build-home'),
        '--setenv=COMPOSER_CACHE_DIR=' + str(BASE / 'var/composer-cache'),
        '--setenv=npm_config_cache=' + str(BASE / 'var/npm-cache'),
        '--setenv=APP_URL=http://localhost', '--setenv=APP_NAME=AutoIQ', '--setenv=APP_LOCALE=sr', '--setenv=APP_ENV=testing', '--setenv=APP_KEY=' + test_key, '--setenv=DB_CONNECTION=sqlite',
        '--setenv=DB_DATABASE=:memory:', '--setenv=CACHE_STORE=array', '--setenv=SESSION_DRIVER=array',
        '--setenv=MAIL_MAILER=array', '--setenv=QUEUE_CONNECTION=sync', '/bin/sh', '-c', command)
    for name, digest in hashes.items():
        assert hashlib.sha256((release / name).read_bytes()).hexdigest() == digest, 'Build changed source: ' + name
    (release / 'storage').rename(release / '.test-storage')
    (release / 'storage').symlink_to(BASE / 'var/storage')
    (release / '.env').symlink_to(BASE / 'private/autoiq.env')
    run('runuser', '-u', 'deploy_autoiq', '--', PHP, '/usr/local/lib/composer.phar',
        'dump-autoload', '--no-dev', '--optimize', '--no-interaction', cwd=release)
    print(artisan(release, 'livewire:publish', '--assets'), flush=True)
    print(artisan(release, 'optimize'), flush=True)
    migration_status = artisan(release, 'migrate:status')
    assert 'Pending' not in migration_status, 'Pending database migration: review/apply it separately before promotion'
    for path in (release / 'public/build/assets').rglob('*'):
        dest = BASE / 'var/build-assets' / path.relative_to(release / 'public/build/assets')
        if path.is_dir():
            dest.mkdir(mode=0o755, parents=True, exist_ok=True)
        else:
            dest.parent.mkdir(mode=0o755, parents=True, exist_ok=True)
            if dest.exists():
                assert dest.read_bytes() == path.read_bytes(), 'Hashed asset collision'
            else:
                shutil.copyfile(path, dest)
                dest.chmod(0o644)
    (release / 'public/storage').symlink_to(BASE / 'var/storage/app/public')
    assert (release / 'public/vendor/livewire/livewire.min.js').is_file()
    # Source is immutable to the HTTP runtime; framework caches remain site-scoped.
    run('chmod', '-R', 'g-w,o-rwx', str(release / 'bootstrap'), str(release / 'public'), str(release / 'vendor'))
    run('chown', '-R', 'web_autoiq:web_autoiq', str(release / 'bootstrap/cache'))
    run('chmod', '-R', 'u+rwX,g+rwX', str(release / 'bootstrap/cache'))
    run('setfacl', '-m', 'u:www-data:--x', str(release))
    run('setfacl', '-R', '-m', 'u:www-data:r-X', str(release / 'public'))
    manifest = release / 'deployment.json'
    manifest.write_text(json.dumps({'release': release_id, 'commit': commit, 'archive_sha256': expected_hash, 'files': hashes}, indent=2))
    manifest.chmod(0o640)
    promote(release, state())
    public = BASE / 'public'
    if public.is_dir() and not public.is_symlink():
        public.rmdir()
    link(public, BASE / 'current/public')


if __name__ == '__main__':
    assert os.geteuid() == 0
    os.umask(0o027)
    with open('/run/autoiq-release.lock', 'w') as lock:
        fcntl.flock(lock, fcntl.LOCK_EX | fcntl.LOCK_NB)
        if sys.argv[1:] == ['status']:
            print(json.dumps(state(), indent=2))
        elif sys.argv[1:] == ['rollback']:
            previous = state()
            assert previous and previous.get('previous_release')
            promote(BASE / 'releases' / previous['previous_release'], previous)
        elif len(sys.argv) == 5 and sys.argv[1] == 'deploy':
            deploy(*sys.argv[2:])
        else:
            raise SystemExit('Usage: autoiq-release status | rollback | deploy RELEASE COMMIT SHA256 < source.tar')
