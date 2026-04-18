# Changelog

All notable changes to AutoIQ.rs are documented in this file.

This project follows [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.1.22] - 2026-04-18

### Changed

- Improved technical SEO for blog content with sitemap `lastmod` entries, robots sitemap discovery, absolute article image metadata, and blog breadcrumbs structured data.

## [0.1.21] - 2026-04-18

### Changed

- Made blog cover optimization idempotent by skipping images that are already within the target dimensions and file-size threshold.

## [0.1.20] - 2026-04-18

### Changed

- Added file-version cache busting to local blog cover URLs so optimized images bypass stale CDN/browser cache entries.

## [0.1.19] - 2026-04-18

### Added

- Added automatic optimization for OpenAI-generated blog cover images and a safe `blog:optimize-covers` command for existing generated covers.

## [0.1.18] - 2026-04-18

### Added

- Added reusable AutoIQ branded email templates for contact messages, email verification, and password reset emails.

## [0.1.17] - 2026-04-18

### Changed

- Reduced OAuth phishing-review surface by disabling unused Facebook OAuth routes and adding clearer AutoIQ.rs/Google-login safety copy.
- Added noindex response headers for account routes while Google Safe Browsing review is pending.

## [0.1.16] - 2026-04-18

### Added

- Added global browser security headers to reduce phishing and content-injection risk ahead of Google Safe Browsing review.

## [0.1.15] - 2026-04-18

### Fixed

- Fixed single blog article cover images so generated thumbnails are shown fully instead of being cropped.

## [0.1.14] - 2026-04-18

### Added

- Added five broader blog articles covering a single model, imported cars, diesel city-driving risks, mileage checks, and used electric cars.

## [0.1.13] - 2026-04-18

### Added

- Added an OpenAI-powered Artisan workflow for generating modern blog cover images without storing API keys in code.

## [0.1.12] - 2026-04-18

### Added

- Added five new idempotent comparison blog posts without duplicate slugs or titles.

## [0.1.11] - 2026-04-18

### Changed

- Removed the open-account button from the desktop and mobile header for signed-out visitors.

## [0.1.10] - 2026-04-18

### Changed

- Reorganized the header with a separate add-listing button, contact as the last primary navigation item, user account dropdown, and mobile menu.

## [0.1.9] - 2026-04-18

### Fixed

- Prevented contact form mail delivery failures from returning a 500 error and show a user-facing retry message instead.
- Documented the expected AutoIQ sender address for mail configuration.

## [0.1.8] - 2026-04-18

### Changed

- Reworded contact page safety copy to speak to end users without exposing form protection details.

## [0.1.7] - 2026-04-18

### Added

- Added a Livewire contact page with loading state, disabled submit button, honeypot field, minimum-submit-time check, link spam guard, and rate limiting.
- Added contact message email delivery, configurable contact recipient, navigation/footer links, and sitemap coverage.

## [0.1.6] - 2026-04-18

### Changed

- Removed the Facebook authentication button from login and registration pages, leaving Google as the only visible social sign-in option.

## [0.1.5] - 2026-04-18

### Changed

- Restyled Google and Facebook authentication buttons to match the AutoIQ dark glass interface.
- Documented the standard version bump, changelog, commit, tag, push, and deploy release procedure.

## [0.1.4] - 2026-04-18

### Added

- Added five trend-informed comparison blog articles with reusable database seeding.

## [0.1.3] - 2026-04-18

### Fixed

- Fixed `/sitemap.xml` so the footer sitemap link returns valid XML in production.

## [0.1.2] - 2026-04-18

### Added

- Added Google and Facebook login/register via Laravel Socialite.
- Added social account linking for existing users by email and OAuth-based account creation for new users.
- Added OAuth provider configuration placeholders and feature coverage for social authentication flows.

## [0.1.1] - 2026-04-18

### Added

- Initialized project versioning with `VERSION` and this changelog.
- Added production deployment runbook with server details and safety rules.

### Changed

- Refined visible UI, admin, and import copy so it speaks to end users instead of developers.
- Replaced internal terms such as score, lightbox, admin role, draft, and import flow with clearer Serbian user-facing wording.

## [0.1.0] - 2026-04-18

### Added

- Initial AutoIQ.rs Laravel application.
- Public pages for home, listings, dealers, blog, sitemap, authentication, and account flows.
- Blog index and article pages with category filtering, featured article layout, related posts, and SEO metadata.
- Listing import services and scrapers for Serbian car-market sources.
- Admin dashboard, role and permission setup, and account notification flows.
- Google Analytics gtag integration limited to production environments.
