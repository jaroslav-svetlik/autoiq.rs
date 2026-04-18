# AutoIQ.rs

AutoIQ.rs is a Laravel application for the Serbian used-car market. It combines listings, price context, dealer profiles, saved searches, alerts, and editorial blog content for smarter buying and selling decisions.

## Stack

- PHP 8.3+
- Laravel 13
- Livewire 4
- Tailwind CSS 4
- Vite 8
- SQLite for local development

## Local setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

For frontend development, run:

```bash
npm run dev
```

## Tests

```bash
php artisan test
```

## Versioning

The project follows Semantic Versioning.

- Current version is stored in `VERSION`.
- Release notes are maintained in `CHANGELOG.md`.
- Use annotated Git tags for releases, for example `v0.1.0`.

Release checklist:

1. Update `VERSION`.
2. Move changelog entries from `Unreleased` into a dated release section.
3. Run `php artisan test` and `npm run build`.
4. Commit the release changes.
5. Create an annotated tag, for example `git tag -a v0.1.0 -m "Release v0.1.0"`.
6. Push the branch and tags.

## Repository

GitHub: https://github.com/jaroslav-svetlik/autoiq.rs
