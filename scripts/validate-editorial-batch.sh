#!/usr/bin/env bash
set -euo pipefail

# Mandatory local gate for an AutoIQ daily batch. This is intentionally
# read-only: a failed gate must stop before covers, a release tag, deploy, or DB write.
php -l database/seeders/TrendBlogPostSeeder.php
php -l database/seeders/data/october-sixth-2026.php
php -l app/Support/EditorialBatchValidator.php
php -l database/seeders/OctoberSixthEditorialRepairSeeder.php
php -l tests/Feature/TrendBlogPostSeederTest.php
php artisan test --filter=TrendBlogPostSeederTest
php artisan test
npm run build
git diff --check
