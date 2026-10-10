# Daily editorial publication — effective 2026-10-10

This execution supplement follows AGENTS.md, the editorial playbook and current
server/migration policy. It replaces legacy directions to append daily posts to
the historical seeder, commit tests or make full local backups.

## Prepare and resume

Work directly in the scheduler-created chat in
`/Users/jaroslavsvetlik/Laravel/autoiq.rs`. Read automation memory and private dated
manifests. Determine the actual Europe/Belgrade date, inspect both catalogs and
production first through `ssh prod-web-01`. Confirm hostname and resolve
`/srv/sites/autoiq/current` with sudo when the ops account cannot traverse it.
Five posts is a daily ceiling. Missing source files/covers are not failures: the
run must create them. Do not stop after a manifest, draft, tag or code deploy.
Do not infer unpublished database articles from a list of proposed topics.

Keep an ignored source/intent/exact-version manifest under
`storage/app/editorial/private`. Research original sources; produce one comparison,
two used-car guides, one inspection and one market article. The first three need
700–1100 substantive body words, the last two 500–800, with 4–6 tailored sections
and blank lines between every heading and paragraph. Do not fabricate tests,
prices, defect rates or equipment availability. Save final five arrays separately
in `database/seeders/data/YYYY-MM-DD.php`, using the October 10 data structure.
`ReviewedEditorialBatch::load()` validates the five and proves that the legacy
voice transformer is an exact no-op. The old seeder stays frozen at its historical
655 rows; its existing tests are not weakened to accommodate daily publication.

## Local gate and assets

Extend the ignored suite `tests/Feature/Local/EditorialBatchPublicationTest.php`
for the new date/scopes. It must verify every rendered content block, no-op retry,
partial resume, conflicting rows/dates, malformed images and journal safety.
Restore/recreate this ignored suite if the workspace lacks it; never commit or
push test files. Generate five genuine editorial cover images with the image
tool, optimize to 1280×720 WebP, visually inspect all five, and store approved
files at `storage/app/public/blog/generated/SLUG.webp`. Model representations
must match the exact generation; reject/regenerate inaccurate ones. These are
AI editorial illustrations, not evidence of an actual road test.

Run `bash scripts/validate-editorial-batch.sh YYYY-MM-DD`. Fix failures and repeat.
The gate performs lint, the ignored batch suite, historical regressions, full
tests, frontend build and diff/staged-test checks. Review all final content and
all changed source, bump VERSION/CHANGELOG, commit only reviewed source on main,
create an annotated version tag and push main/tag. Check the staged paths and
outgoing commits for tests; preserve unrelated dirty files.

## Release and create-only publication

Transfer only reviewed source to
`/Users/jaroslavsvetlik/Documents/ChatGPT/autoiq.rs`; do not replace production
dependency manifests/locks, migration hardening or existing dirty documentation.
Commit/push the reviewed production branch. From that production copy use only
`python3 scripts/deploy-prod.py --status` then `--ref COMMIT` for deployment.
Confirm active commit and release before database operations.

Transfer only the five approved WebPs through `prod-web-01` and install them with
site ownership/Nginx-readable permissions in persistent
`/srv/sites/autoiq/var/storage/app/public/blog/generated`. If an intended file
already exists, verify it before replacing anything; retries preserve approved
media. No full database/site/media backup or historical seeder may be run.

Use `ssh prod-web-01 'sudo -n autoiq-artisan editorial:publish YYYY-MM-DD --check'`
to inspect reviewed slugs and daily count. Then run the same command without
`--check`. Its production guards verify hostname, release, persistent media and
timezone. The publisher revalidates all five, decodes each WebP, takes a shared
publication lock and creates only missing exact rows in one transaction. It
refuses other daily batches, changed existing copy or backdating. Exact retries
are no-ops. A small mode-0600 affected-row journal is fsynced before writes under
`/srv/sites/autoiq/var/storage/app/private/editorial/rollback`; journal failure
rolls the transaction back. No other article is updated.

## Mandatory public acceptance

Run scoped cover optimization for these five slugs (`blog:optimize-covers`, see
command help), then `sudo -n autoiq-artisan optimize` and `queue:restart`. Inspect
the production database: exactly five current-day rows, exact reviewed content,
slugs/media and expected identities. Open all five public routes and visually
review the live render. Run locally
`php scripts/verify-editorial-batch.php YYYY-MM-DD`: it fetches all five routes,
asserts the exact h1, canonical, every article paragraph/heading, excerpt and
highlights, decodes all five public 1280×720 WebPs and requires each route exactly
once in sitemap. HTTP 200 alone is not acceptance.

Only then record success, IDs, URLs, source/tag/deployed commits, release, asset
hashes, gate results and public verification in the private dated manifest and
automation memory. If a genuine external failure remains after safe recovery,
retain the last verified state and name the precise blocker, not generic missing
preparation. Do not claim that future scheduler availability is guaranteed.
