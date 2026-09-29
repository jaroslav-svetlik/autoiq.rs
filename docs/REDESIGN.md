# AutoIQ light redesign

The home page is now the vehicle catalog. `HomePage` inherits the existing Livewire listing index; `/oglasi` and the model landing pages remain available. Search, equipment filters, favorites, saved searches, price analysis, authentication and moderation continue to use the existing application services.

The shared layout, reusable cards and page templates use a single light palette: navy text, blue actions, white surfaces and pale blue borders. The catalog supports nine results per page, grid/list display and collapsible mobile filters. Year and mileage ranges are also preserved in saved-search matching. The blog adds search across published article titles and excerpts.

Badges use actual data: active featured status, price deviation, publication date and dealer verification. The AutoIQ score remains visible. Features without an existing backend (newsletter subscriptions, app downloads, public seller star ratings and body-style filters) are not represented by nonfunctional controls.

## Hero asset

- Path: `public/images/alpine-drive.webp`
- Created with the built-in image generation tool; converted to WebP for delivery (approximately 149 KiB).
- This is a decorative brand image. Listing and article photos continue to come from their models.

Generation prompt:

> Use case: ads-marketing. Asset type: ultrawide automotive website hero photograph, 3:1 horizontal panorama. Photorealistic dark navy blue premium sport sedan (BMW 3-series style) driving on a winding asphalt road beside an alpine lake and dramatic rugged mountains under a clear pale blue sky. Car shown in front three-quarter view, entirely within right half of composition, centered at about 73% width, fully visible including wheels, occupying about 32% of width. Left half is open distant pale lake and misty mountains with very little detail, suitable for overlaying navy text. Natural morning daylight, crisp elegant European automotive photography, cool blue and soft white palette. Guard rail along winding road, subtle motion in road foreground, premium polished photo. No words, no lettering, no watermarks, no UI or graphics.

## Validation

Run `npm run build`, `php artisan test`, `vendor/bin/pint --dirty --test` and `php artisan view:cache`.

Browser checks cover the catalog at desktop and 390 px mobile widths, range filters, list display, pagination, blog search, listing details, authentication and contact screens. The local visual preview uses a separate SQLite copy with demonstration records because the configured local MySQL service was unavailable. The project's `.env` and original database are unchanged.

## Listing detail mockup

The listing page uses a large gallery and thumbnail strip on the left, with the title, price and seller contact on the right. Basic vehicle information, equipment, description and the existing price analysis sit below the gallery. Related listings and the catalog banner span the page. The shared header and footer are unchanged by this update.

The gallery keeps all photos available in a keyboard-accessible lightbox. Seller contacts use existing phone numbers and public dealer email: calling reveals all available phone numbers, and messaging opens the visitor's SMS or email application. Share copies the canonical URL with a selectable-link fallback. Report opens the existing contact form with the public listing context; it does not submit a message automatically.

Only stored data is presented. Dealer verification is conditional on `verified_at`; financing offers, ratings, mechanical specifications and service-history claims are not fabricated. Location links to the listed city on Google Maps, without implying an exact vehicle address. The mobile layout puts seller contact immediately after the gallery, followed by specifications and equipment.

## Shared dropdowns

All select fields use `x-select`, backed by `resources/js/select-field.js` and `resources/css/select-field.css`. The native control retains its existing Livewire directives and form value; the enhanced combobox provides a consistent light popup, selected state, keyboard navigation, disabled/error states and search for lists with ten or more options. Changes still dispatch the original input/change/blur events, including admin `wire:change` actions. Option lists and values stay synchronized after Livewire updates and filter resets.

Popups are teleported outside clipping containers, fit the viewport and open above the field when needed. Outside clicks, Escape and navigation close them. The original native select remains available if enhancement has not initialized. Account menus and quick-search range popovers use matching surfaces and dismissal behavior.

Browser checks cover contact-form selection/validation, linked make/model filters, filter resets, registration's conditional dealer fields, searchable city options, keyboard selection and mobile bounds.

Quick price and year range menus use `wire:ignore.self` to preserve the browser-owned `open` state during Livewire updates. Their children still update, keeping summary labels, inputs, sidebar filters and results synchronized. Outside clicks, Escape, switching menus and submitting the search still close the menu normally.

## Equipment and icons

Listing equipment is a separate panel immediately after basic information and before the description. It uses the existing selected equipment groups and only shows stored selections. Empty groups and the entire empty panel are omitted.

Basic information and equipment use a locally bundled subset of [Lucide](https://lucide.dev/guide/static) 1.48.0, imported from the official `lucide-static` npm package. `x-lucide-icon` renders the SVG paths directly in Blade; `x-equipment-icon` maps the existing equipment keys to icons, with a generic badge fallback for future keys. The original path data is preserved, with the stroke width set to 1.75 to match the site. The included ISC/MIT license is in `resources/licenses/lucide.txt`. No icon font, client-side initialization or external requests are required.

## Contextual blog guides

The guides inserted inside article content use a compact navigation list with one small heading, full linked titles and subtle separators. Repeated category labels, descriptions and nested cards have been removed from this block. The existing recommendation service, three-link limit, placement and destination URLs are preserved. Links wrap naturally on small screens and retain visible keyboard focus.

## Supplied brand logo

`x-brand` uses `public/images/autoiq-logo.webp` in the shared header, footer and error-page header. The user-supplied original artwork was cropped to `(122, 395, 1202, 264)` from the 1448 × 1086 source, resized to 728 × 160 and encoded as lossless WebP (about 67 KiB). Its lettering, gradients and brain symbol are preserved. The displayed width is 182 px in the desktop header and 164 px on mobile and in the footer, with an intrinsic aspect ratio to prevent layout shifts. Multiply blending integrates the original near-white background with the existing light surfaces. The image has descriptive alternative text; the existing home links are retained.

## My Listings management

Editable cards use `x-listing-owner-menu` with Lucide icons, status badges and actions in the upper-right corner. Active listings can be paused or marked as sold; paused listings can be sold or reactivated; sold listings can be reactivated. Only previously published listings can be reactivated, and drafts/rejected listings cannot bypass moderation. Every server action queries the authenticated owner's listings independently of the UI.

The new `paused` enum value uses the existing string status column without a database migration. Existing published scopes, public detail authorization, sitemap and search eligibility hide paused/sold listings. Favorites retain their stored associations but display only published listings. Reactivation preserves the original publication date and does not send another new-listing alert. Hidden listings do not send price-drop alerts.

Deletion keeps the existing soft-delete behavior and uses an in-page native dialog with explicit confirmation, cancellation, Escape and focus restoration. Menus preserve their open state through Livewire updates and close after actions, on outside clicks, Escape or navigation. Feature tests cover owner authorization, allowed transitions, hidden visibility, soft deletion, cancellation and notification behavior; UI checks use a separate local preview account and database.
