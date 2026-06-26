# AutoIQ Blog Editorial Playbook

This file defines how AutoIQ production blog batches are researched, written, shipped, and verified.

## Batch Contract

Each batch adds exactly five production-ready articles:

- one `Poređenje modela` article,
- two `Kupovina polovnjaka` articles,
- one `Provera vozila` article,
- one `Analiza tržišta` article.

Before writing, check production first:

```bash
ssh -p 9988 deploy@164.68.121.125
cd /www/wwwroot/autoiq.rs
php artisan tinker --execute='echo "total=".App\Models\BlogPost::count().PHP_EOL; App\Models\BlogPost::selectRaw("category, count(*) as total")->groupBy("category")->orderBy("category")->get()->each(fn($row) => print($row->category.":".$row->total.PHP_EOL)); App\Models\BlogPost::orderByDesc("id")->limit(90)->get(["id", "title", "slug", "category"])->each(fn($post) => print($post->id." | ".$post->category." | ".$post->slug." | ".$post->title.PHP_EOL));'
```

Also search the local seeder and test with `rg` for model names, countries, symptoms, and likely slug fragments. Do not add an article if the model, comparison, country, or inspection angle is already substantially covered.

## Editorial Standard

Write in Serbian Latin script, for Serbian used-car buyers. The tone is practical, calm, and specific. Every article must tell a small buying story rather than read like a generic checklist.

Required shape:

- title with a concrete buyer dilemma,
- unique slug,
- short excerpt,
- 4 story-led paragraphs in `content`,
- 3 actionable `highlights`,
- relevant `tags`,
- `meta_title` and `meta_description`,
- `is_featured => false`,
- `published_at => now()`,
- palette matching the existing batch style.

Avoid:

- duplicate angles already present in production,
- generic "best cars" filler,
- unsupported claims about exact prices,
- brand worship,
- SVG-only production covers,
- articles that only list parts without explaining the buyer decision.

Every article should make the buyer understand when to proceed, when to negotiate, and when to walk away.

## File Changes

For each batch:

1. Bump `VERSION`.
2. Add a dated `CHANGELOG.md` entry.
3. Append the five articles to `database/seeders/TrendBlogPostSeeder.php` before the end of `posts()`.
4. Update `tests/Feature/TrendBlogPostSeederTest.php`:
   - total article count,
   - `Poređenje modela` count when one comparison is added,
   - five new slug assertions.
5. Run:

```bash
php -l database/seeders/TrendBlogPostSeeder.php
php -l tests/Feature/TrendBlogPostSeederTest.php
php artisan test --filter=TrendBlogPostSeederTest
php artisan test
npm run build
git diff --check
```

## Cover Images

Production articles need real generated bitmap covers, not SVG placeholders.

Generate one image per new article. Use photorealistic, editorial automotive prompts with:

- 16:9 composition,
- no visible brand logos,
- no readable plates,
- no text or watermark,
- realistic used-car inspection context,
- subject and scene aligned with the article angle.

Copy generated PNGs into:

```bash
storage/app/public/blog/generated/<slug>.webp
```

Then run:

```bash
php artisan blog:optimize-covers --slug=<slug-1> --slug=<slug-2> --slug=<slug-3> --slug=<slug-4> --slug=<slug-5>
```

Visually inspect the final WebP files before deployment.

## Release And Deploy

Commit only source files:

- `CHANGELOG.md`,
- `VERSION`,
- `database/seeders/TrendBlogPostSeeder.php`,
- `tests/Feature/TrendBlogPostSeederTest.php`,
- this editorial playbook when changed.

Do not commit generated cover files under `storage/app/public`.

After commit and tag, deploy with the standard runbook in `docs/DEPLOYMENT.md`. Upload the five optimized WebP files to production storage, update each post's `cover_image_path`, run `blog:optimize-covers`, rebuild caches, and smoke test:

- production `VERSION`,
- production blog count,
- all five article URLs return `200`,
- all five cover URLs return `200 image/webp`.

Known production caveat: the server currently has a persistent local `package-lock.json` modification. Do not reset or overwrite it unless explicitly asked.
