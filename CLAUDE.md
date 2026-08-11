# Trepied — WordPress Theme

Custom one-page landing site for **Trépied**, a video production company based in Montreal.
Author: Mauricio Bayuelo. Theme version: 1.0.0. Requires WP 6.0+, PHP 8.0+.

---

## What this site is

Single-page portfolio LP. No blog, no archive, no inner pages. Everything lives on `front-page.php`.
Sections in order: Hero → Services → Projects → About → Testimonials → CTA/Contact.

---

## Stack

- **WordPress** (local via LocalWP)
- **ACF Pro** — all content is managed via ACF field groups (stored in the DB, not in code)
- **Tailwind CSS** — loaded via CDN Play script, no build step
- **Lucide Icons** — loaded via CDN (`unpkg.com/lucide`)
- **Calendly** — popup widget, loaded on-demand (not on page load) for performance
- **WPML-ready** — `wpml-config.xml` present, `trepied_get_front_page_id()` handles translation

---

## File structure

```
front-page.php        — the entire page template
functions.php         — theme setup, enqueue, image sizes, SEO meta, hero image preload
inc/
  acf.php             — ACF helper layer (trepied_get_field, trepied_get_group)
  acf-fields.php      — disabled local field registration (reference only — DB fields are live)
  calendly.php        — Calendly URL builder + on-demand loader + button helper
assets/
  images/symbol.png   — brand symbol used in hero and mobile sections
style.css             — theme header only (no actual styles here)
```

---

## ACF field groups (stored in DB)

All field groups are registered in the WordPress database. `inc/acf-fields.php` contains a disabled PHP reference copy — **do not re-enable it**, it will conflict with the DB groups.

DB group IDs:
- `group_69a5f37e52db6` — Hero
- `group_69a77c72a2028` — Services
- `group_69a77f37aa666` — Projects
- `group_69a781358063e` — About
- `group_69a783c4508d5` — Testimonials
- `group_69a791b7076fe` — CTA Section

### Hero (`hero` group)
| Field name | ACF type | Notes |
|---|---|---|
| `copy` | Text | H1 headline |
| `subtitle` | Text | Paragraph below H1 |
| `video` | File → URL | Self-hosted MP4, desktop (1920×1080) |
| `video_mobile` | File → URL | Self-hosted MP4, mobile (960×540) |
| `image` | Image → Array | Fallback if no video; also used for SEO OG image |

**Hero video render logic** (`front-page.php:47–54`):
- If `video` is set → renders `<video>` tag with two `<source>` elements
- `video_mobile` is optional; if present it's served to viewports ≤1023px via `<source media="(max-width: 1023px)">`
- If no video → renders ACF `image` via `wp_get_attachment_image()`

**Video specs:**
- Desktop: H.264 MP4, 1920×1080, ~2000–4000 kbps, no audio, target 10–20MB
- Mobile: H.264 MP4, 960×540, ~800–1200 kbps, no audio, target 3–5MB
- FFmpeg desktop: `ffmpeg -i original.mp4 -vf scale=1920:1080 -c:v libx264 -crf 25 -preset slow -an hero-desktop.mp4`
- FFmpeg mobile: `ffmpeg -i original.mp4 -vf scale=960:540 -c:v libx264 -crf 29 -preset slow -an hero-mobile.mp4`

### Services (`services` group)
| Field | Type |
|---|---|
| `title` | Text |
| `description` | Textarea |
| `service` | Repeater |
| `service[].service_title` | Text |
| `service[].service_description` | WYSIWYG |
| `service[].service_image` | Image → Array |
| `service[].service_link_text` | Text (triggers Calendly popup) |

Alternating layout: odd items = image left, even items = image right.

### Projects (`projects` group)
| Field | Type |
|---|---|
| `title` | Text |
| `description` | Textarea |
| `project` | Repeater |
| `project[].project_title` | Text |
| `project[].project_image` | Image → Array |
| `project[].client` | Text |
| `project[].short_description` | Textarea |
| `project[].long_description` | WYSIWYG (shown in modal) |

Each project card has a "View project" button that opens a modal. Long description lives in a `<template>` tag and is pulled by JS into the modal.

### About (`about` group)
`title`, `subtitle`, `paragraph` (WYSIWYG), `image` (Image → Array)

### Testimonials (`testimonials` group)
Repeater: `client`, `role`, `testimonial` (quote text). Rendered as a slider with prev/next buttons and dot indicators.

### CTA Section (`cta_section` group)
`title` (Text), `paragraph` (Textarea). Contains Calendly button, Quote modal trigger, WhatsApp link.

---

## Key helpers

### `trepied_get_group(string $group_name): array`
Safe wrapper to read an ACF group field from the front page. Always returns an array. Use this instead of calling `get_field()` directly.

### `trepied_get_field(string $field_name, $default = null)`
Safe single-field reader for the front page. Handles missing ACF, missing front page ID, empty values.

### `trepied_calendly_button(string $text, string $class = '', string $url = ''): void`
Outputs the Calendly popup trigger button. Calendly assets load on-demand via `requestIdleCallback` — do not enqueue them on page load.

### `trepied_get_calendly_url(): string`
Returns the Calendly URL with UTM params forwarded from the current page URL. Base URL is `TREPIED_CALENDLY_URL` constant (can be set in `wp-config.php`), defaults to `https://calendly.com/maubayuelo/30min`.

---

## Image sizes registered

| Name | Dimensions | Crop | Used for |
|---|---|---|---|
| `trepied-hero` | 1400×800 | Yes | Hero, projects, about |
| `trepied-service` | 1080×720 | Yes | Services section |

---

## Performance decisions

- Calendly loaded on-demand (`requestIdleCallback`), not on page load
- Hero image preloaded via `<link rel="preload">` in `wp_head` (priority 2)
- Hero video replaces YouTube iframe — no third-party iframe on page load
- `<source media>` on `<video>` serves smaller file to mobile
- Lucide icons and Tailwind loaded via CDN (no build step)
- `dns-prefetch` added for `assets.calendly.com`

---

## Calendly

- Popup widget only (no inline embed)
- Trigger class: `calendly-popup-trigger`
- URL passed via `data-calendly-url` attribute on the button
- UTM params are forwarded automatically from the page URL

---

## What does NOT exist in this theme

- No blog, no custom post types, no archive templates
- Only one inner page template: `page-legal.php` (privacy policy pages)
- No build step (no npm, no webpack, no compiled CSS)
- No local ACF field registration (disabled — all in DB)

---

## Loi 25 consent (cookie banner) — added [current work]

Québec's Law 25 requires **opt-in** consent (nothing loads before an
explicit choice) and equal visual weight between "accept" and "refuse".
This is a from-scratch implementation — no GA4/Meta Pixel existed in
this theme before it (confirmed by grep before starting).

### File structure

```
inc/options.php          — ACF options page "Trépied — Config": GA4 ID,
                            Meta Pixel ID, privacy contact email, legal
                            entity name. trepied_get_option($key) is the
                            only safe reader. Empty ID → script never
                            loads, with or without consent.
inc/consent/
  consent.php             — enqueues consent.css/consent.js sitewide,
                            wp_localize_script's the translatable
                            strings + cookie config, resolves the
                            language-aware privacy policy URL
  consent.js              — cookie read/write, banner + preference
                            panel DOM, focus trap, window.magnetoConsent
  consent.css              — bottom-sheet banner + dialog panel styles
  analytics.php           — GA4 (Consent Mode v2) bootstrap + enqueues
                            analytics.js when GA4 or Pixel ID is set
  analytics.js             — listens for magneto:consent, updates gtag
                            consent, injects Meta Pixel once marketing
                            consent is granted
page-legal.php             — template for the 3 privacy policy pages
assets/css/tokens.css       — :root custom properties (see below)
assets/css/legal.css       — page-legal.php styles only
```

### Consent contract (do not rename — a React headless port will share it)

- Cookie: `magneto_consent`, 6 months, `SameSite=Lax`, `Secure` on HTTPS
- Value (JSON, URL-encoded): `{"v":1,"analytics":false,"marketing":false,"ts":<unix_seconds>}`
- Categories: `essential` (always true, never stored), `analytics` (GA4),
  `marketing` (Meta Pixel)
- If the cookie's `v` doesn't match `TREPIED_CONSENT_VERSION` in
  `consent.php`, treat as no consent and show the banner again
- Event: `window.dispatchEvent(new CustomEvent('magneto:consent', {detail:{analytics, marketing}}))`
  fires on every state change **and once on page load** (deferred via
  `setTimeout(0)` — see Common Issues below)
- Public API: `window.magnetoConsent.get() / .set({analytics, marketing}) / .openPanel()`
- GA4/Pixel gating code (`analytics.js`) only ever listens to the event
  or calls `.get()` — it must never parse the cookie itself

### CSS custom properties (`assets/css/tokens.css`)

No `:root` variables existed anywhere in the theme before this work —
colors/fonts were hardcoded Tailwind literals (`bg-cream`, `#1a1a1a`,
etc. in `functions.php`'s inline Tailwind config and scattered across
templates). `tokens.css` extracts those same values into custom
properties (`--trepied-color-*`, `--trepied-font-*`) so the consent
module and legal pages have one source of truth instead of repeating
hex literals. It is enqueued sitewide. It intentionally does **not**
touch the Tailwind-classed markup elsewhere in the theme — that's out
of scope for this work.

### i18n

All banner/panel/footer strings use `__()`/`_e()` with the `trepied`
text domain — same as the rest of the theme. **No `.po`/`.mo` files**:
this theme has dozens of pre-existing `__()` calls (header.php,
footer.php...) and an empty `/languages` folder, because translation
is handled entirely by the `wpml-string-translation` plugin's
DB-backed UI (WP Admin → WPML → String Translation), not compiled
gettext files. New consent strings need to be entered there for
`fr_CA`/`es` — see the deploy plan for the exact source strings.

The privacy policy link in the banner resolves via
`trepied_get_privacy_policy_url()` (`inc/consent/consent.php`): looks
up the English page by slug (`privacy-policy`), then
`icl_object_id()` to the active language's translation, falling back
to the English URL if no translation exists.

### Common Issues

| Symptom | Cause | Fix |
|---|---|---|
| Banner never appears, even in a fresh incognito window | A stale `magneto_consent` cookie from a previous dev iteration has a matching `v` | Delete the cookie manually, or bump `TREPIED_CONSENT_VERSION` in `consent.php` |
| GA4/Pixel gating script never reacts to consent | `trepied-consent-gating` (analytics.js) enqueued without depending on `trepied-consent`, so it loaded *before* `window.magnetoConsent` existed | Keep `['trepied-consent']` as its dependency in `trepied_enqueue_consent_gating()` |
| Consent event seems to "fire into nothing" on page load | Both scripts are `in_footer=true` with no `defer`/`async`; by the time footer scripts run, `DOMContentLoaded` has already fired, so `consent.js`'s init runs synchronously *before* a script enqueued after it can attach a listener | Already fixed: the initial broadcast in `consent.js` goes through `setTimeout(fn, 0)`. Don't remove that when editing. |
| `_ga`/`_fbp` cookies exist despite no consent | Someone added a GA4/Pixel snippet directly in `header.php`/`footer.php` instead of through `trepied_get_option()` + the gating scripts | Remove it. All analytics tags must go through `inc/consent/analytics.php` so gating actually applies |
| Privacy policy link 404s on `/es/...` | `icl_object_id()` returned no translation for that language and the fallback also failed — usually means the ES page's WPML translation link was broken/removed | Re-link the ES page as a translation of the EN privacy-policy page in WPML → check |
| New legal page content looks unstyled | Content wasn't saved on a page using the "Legal Page" template, or `page-legal.php`/`legal.css` weren't matched via `is_page_template()` | Page Attributes → Template → "Legal Page" in wp-admin; confirm the page's actual template slug matches `page-legal.php` |
