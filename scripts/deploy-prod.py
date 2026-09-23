#!/usr/bin/env python3
"""Deploy a committed Git revision through Tailscale SSH to prod-web-01."""
import argparse
from datetime import datetime, timezone
import hashlib
from pathlib import Path
import subprocess

parser = argparse.ArgumentParser(description=__doc__)
parser.add_argument('--ref', default='HEAD', help='Committed Git revision (default: HEAD)')
parser.add_argument('--host', default='prod-web-01', help='SSH alias from the production access guide')
parser.add_argument('--rollback', action='store_true', help='Switch to the previous verified application release; does not touch the application database')
parser.add_argument('--status', action='store_true')
args = parser.parse_args()
repo = Path(__file__).resolve().parents[1]
ssh = ['ssh', '-o', 'BatchMode=yes', '-o', 'ConnectTimeout=15', args.host]
if args.rollback or args.status:
    subprocess.run(ssh + ['sudo -n autoiq-release ' + ('rollback' if args.rollback else 'status')], check=True)
else:
    commit = subprocess.check_output(['git', '-C', str(repo), 'rev-parse', '--verify', args.ref + '^{commit}'], text=True).strip()
    dirty = subprocess.check_output(['git', '-C', str(repo), 'status', '--short'], text=True)
    if dirty:
        print('Only committed files from ' + commit + ' will be deployed; working-tree changes are excluded.', flush=True)
    archive = subprocess.check_output(['git', '-C', str(repo), 'archive', '--format=tar', commit])
    digest = hashlib.sha256(archive).hexdigest()
    release = datetime.now(timezone.utc).strftime('%Y%m%d-%H%M%S') + '-' + commit[:8]
    print('Deploying commit ' + commit + ' as release ' + release, flush=True)
    subprocess.run(ssh + [f'sudo -n autoiq-release deploy {release} {commit} {digest}'], input=archive, check=True)
