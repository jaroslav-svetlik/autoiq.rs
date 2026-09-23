# AutoIQ production deployment

Migration target: `ssh prod-web-01` over Tailscale. The new instance is private staging until the final SQLite/media synchronization and DNS cutover. Public autoiq.rs continues on the old VPS during preparation. Google OAuth and OpenAI credentials from the compromised host must be replaced before cutover; new Resend is already configured privately.

Site ID `autoiq`, PHP 8.4, runtime `web_autoiq`, builder `deploy_autoiq`. Root: `/srv/sites/autoiq`; immutable releases under `releases`, active symlink `current`. Private environment: `private/autoiq.env`. Persistent SQLite: `var/data/database.sqlite`. Media and Laravel writable storage: `var/storage`. Candidate HTTP listener: localhost:2825. Never copy the old vendor, node_modules, build, sessions, cache or executable uploads.

Run `python3 scripts/deploy-prod.py` from this repository to deploy a committed revision. `--status` displays the active release and `--rollback` returns to the previous verified code release without changing data. The root-owned server coordinator lives at `/usr/local/sbin/autoiq-release`; updates to its checked-in implementation require a separate privileged installation. It validates the source archive, installs clean dependencies as the deployment user, runs audits/build/PHPUnit against an in-memory test database with fake mail, then links production data only after tests pass. Pending schema changes stop promotion and require a reviewed migration plan. Build limits: 2 CPU cores and 3 GB memory.

Operational CLI: `sudo autoiq-artisan COMMAND`. No production seeders, bulk imports, email tests or OpenAI image generation are part of deployment. Avoid running database seeders against production: the editorial seeder updates existing articles. Preserve content IDs, slugs, publication times and media paths.

Fresh APP_KEY invalidates prior encrypted cookies. Existing users and Google account associations remain, while sessions and password reset tokens are removed during migration. The database must be backed up through SQLite's backup API (including live WAL data), not by copying only the main database file. Restore requires the private environment, SQLite snapshot, media, exact source commit and clean dependencies.

Migration evidence and final status are maintained in `/Users/jaroslavsvetlik/Documents/Serveri/output/autoiq-migration-2026-09-23` and the server migration report. Secrets are excluded from Git and normal tool output. No public cutover is implied by a successful private deployment.
