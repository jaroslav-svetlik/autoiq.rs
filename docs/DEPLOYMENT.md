# Production Deployment Runbook

This document is the source of truth for AutoIQ.rs production deployments.

Follow it every time. If a needed command is not covered here and it can change
files, services, data, or permissions, stop and confirm the exact command first.

## Production Server

- Host: `164.68.121.125`
- SSH user: `deploy`
- SSH port: `9988`
- SSH authentication: SSH key
- Sudo: `deploy` is in the sudo group without a password prompt
- Application path: `/www/wwwroot/autoiq.rs`
- GitHub repository: `https://github.com/jaroslav-svetlik/autoiq.rs`
- Production branch: `main`

SSH command:

```bash
ssh -p 9988 deploy@164.68.121.125
```

Application directory:

```bash
cd /www/wwwroot/autoiq.rs
```

## Hard Safety Rules

Deployments must be conservative. Production must never receive local-only,
generated, secret, cache, dependency, or temporary files.

Never upload or commit these local artifacts:

- `.env`
- `.phpunit.result.cache`
- `database/database.sqlite`
- `node_modules/`
- `vendor/`
- `public/build/` from a local machine
- `public/storage`
- `storage/app/public/*`
- `storage/framework/*` runtime contents
- `storage/logs/*`

Never use broad or destructive shell commands during deploy:

- `rm -rf`
- `find ... -delete`
- `git reset --hard`
- `git clean -fd`
- `rsync --delete`
- `scp -r . ...`
- `cp -r . ...`
- `php artisan migrate:fresh`
- `php artisan db:wipe`
- any command that deletes files or database data unless the user explicitly approves the exact command

Safe deploys should use Git for source code and native Laravel/package-manager
commands for generated production state.

## Local Preflight

Every production change must be released through versioning first:

1. Bump `VERSION`.
2. Add a dated entry to `CHANGELOG.md`.
3. Run local checks.
4. Commit the release on `main`.
5. Create and push an annotated `vX.Y.Z` tag.
6. Deploy that pushed commit to production.

Run this locally before deploying:

```bash
git status --short --branch
php artisan test
npm run build
git diff --check
git push
```

Expected state before server deploy:

- local branch is `main`
- `main` is pushed to `origin/main`
- the release tag is pushed to `origin`
- tests pass
- frontend build passes locally
- no uncommitted local changes are required for production

Do not upload local `public/build`; it is ignored and should be generated on the
server from the committed source.

## Server Preflight

After SSH login:

```bash
cd /www/wwwroot/autoiq.rs
pwd
git status --short --branch
git remote -v
php artisan about
```

Confirm:

- `pwd` is `/www/wwwroot/autoiq.rs`
- remote points to `https://github.com/jaroslav-svetlik/autoiq.rs`
- branch tracks `origin/main`
- `.env` exists on the server and is not committed
- `APP_ENV=production`
- `APP_DEBUG=false`
- `MAIL_FROM_ADDRESS` uses a sender domain verified with the configured mail provider
- `AUTOIQ_CONTACT_EMAIL` points to the inbox that should receive contact form messages

If `git status --short` shows unexpected modified files, stop and inspect them.
Do not overwrite or delete them blindly.

## Standard Deployment

Run from `/www/wwwroot/autoiq.rs` on the production server:

```bash
php artisan down --retry=60
git fetch --tags origin
git pull --ff-only origin main
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
npm install --ignore-scripts
npm run build
php artisan migrate --force
php artisan storage:link
php artisan optimize:clear
php artisan optimize
php artisan queue:restart
php artisan up
```

Notes:

- `git pull --ff-only` prevents merge commits and avoids rewriting history.
- `composer install` uses `composer.lock` and installs production PHP dependencies.
- `npm install` uses the lockfile without a broad delete operation.
- `npm run build` creates production Vite assets on the server.
- `php artisan migrate --force` is the only migration command allowed in production by default.
- `php artisan optimize` is preferred over manually managing Laravel cache files.
- `php artisan queue:restart` tells existing workers to restart cleanly.
- Always run `php artisan up` after a deploy attempt, unless the site must stay down intentionally.

## First Server Checkout

Use this only if `/www/wwwroot/autoiq.rs` is empty or has been prepared for the
project. Do not delete existing contents to make it empty.

```bash
sudo mkdir -p /www/wwwroot/autoiq.rs
sudo chown deploy:deploy /www/wwwroot/autoiq.rs
cd /www/wwwroot/autoiq.rs
git clone https://github.com/jaroslav-svetlik/autoiq.rs.git .
cp .env.example .env
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
php artisan key:generate
npm install --ignore-scripts
npm run build
php artisan migrate --force
php artisan storage:link
php artisan optimize
```

After first checkout, edit the server `.env` manually for production values.
Never replace production `.env` with a local file.

## Post Deploy Checks

Run:

```bash
php artisan about
php artisan migrate:status
curl -I https://autoiq.rs
```

Expected:

- Laravel reports production environment.
- migrations are applied.
- HTTP response is successful.
- contact form mail uses a verified sender address, for example `noreply@autoiq.rs`.
- no production errors appear in app logs after a smoke test.

If PHP-FPM or the web server needs a reload, first identify the exact service:

```bash
systemctl list-units --type=service | grep -E 'php|nginx|apache'
```

Then reload only the confirmed service, for example:

```bash
sudo systemctl reload php8.3-fpm
```

Do not restart or reload unknown services by guesswork.

## Rollback Policy

Preferred rollback is a new corrective commit pushed to `main`, followed by the
standard deployment steps.

Avoid history rewrites and broad file resets on production. Do not use:

- `git reset --hard`
- `git clean -fd`
- manual deletion of generated directories

If an urgent rollback to a previous tag is required, stop and confirm the exact
tag and command before changing production.

## Production Tracking

Google Analytics gtag is guarded by Laravel `@production`. It should only render
when the server environment is production.

Before investigating analytics, confirm:

```bash
php artisan about
```

The environment must be `production`; local and test environments must not send
analytics traffic.

## Blog Cover Generation

Blog cover images are generated with the OpenAI Images API through an Artisan
command. Never commit or paste the API key into source files, docs, shell
history, or chat logs. Store it only in the server `.env`:

```bash
OPENAI_API_KEY=...
OPENAI_IMAGE_MODEL=gpt-image-1.5
OPENAI_IMAGE_SIZE=1536x1024
OPENAI_IMAGE_QUALITY=medium
OPENAI_IMAGE_FORMAT=webp
OPENAI_IMAGE_TIMEOUT=120
OPENAI_IMAGE_MAX_WIDTH=1280
OPENAI_IMAGE_MAX_HEIGHT=854
OPENAI_IMAGE_TARGET_MAX_KB=350
OPENAI_IMAGE_OPTIMIZATION_QUALITY=76
```

Preview the work without an API request:

```bash
php artisan blog:generate-covers --dry-run --limit=10
```

Generate missing or placeholder blog covers:

```bash
php artisan blog:generate-covers --limit=10
```

Generate one article by slug:

```bash
php artisan blog:generate-covers --slug=article-slug
```

Regenerate existing covers only when explicitly intended:

```bash
php artisan blog:generate-covers --slug=article-slug --force
```

Preview optimization of generated covers without changing files:

```bash
php artisan blog:optimize-covers --dry-run
```

Optimize existing generated covers in place. The command overwrites a file only
when the optimized image is smaller; it does not delete generated images:

```bash
php artisan blog:optimize-covers
```

Generated files are written to `storage/app/public/blog/generated` and served
through Laravel's public storage link. Do not delete existing generated images
during deploy; replace them only through the command when needed.
