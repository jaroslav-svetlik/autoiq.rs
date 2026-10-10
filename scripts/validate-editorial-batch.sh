#!/usr/bin/env bash
set -euo pipefail

# Mandatory local gate for an AutoIQ daily batch. No production writes:
# a failed gate must stop before a release tag, deploy or publication.
batch_date="${1:-$(TZ=Europe/Belgrade date +%F)}"
export EDITORIAL_BATCH_DATE="$batch_date"
php -l "database/seeders/data/$batch_date.php"
php -l app/Support/ReviewedEditorialBatch.php
php -l app/Services/EditorialBatchPublisher.php
php -l app/Console/Commands/PublishEditorialBatchCommand.php
php -l scripts/verify-editorial-batch.php
if [[ ! -f tests/Feature/Local/EditorialBatchPublicationTest.php ]]; then
  echo "Missing ignored local editorial regression suite. Restore it before publication." >&2
  exit 1
fi
php artisan test tests/Feature/Local/EditorialBatchPublicationTest.php
if git diff --cached --name-only | rg -q '(^tests/|Test\.php$)'; then
  echo "Tests must never be staged, committed or pushed." >&2
  exit 1
fi
php -l database/seeders/TrendBlogPostSeeder.php
php -l database/seeders/data/october-sixth-2026.php
php -l app/Support/EditorialBatchValidator.php
php -l database/seeders/OctoberSixthEditorialRepairSeeder.php
php -l tests/Feature/TrendBlogPostSeederTest.php
php artisan test --filter=TrendBlogPostSeederTest
php artisan test
npm run build
git diff --check
