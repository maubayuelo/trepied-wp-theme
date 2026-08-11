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
- No inner page templates (only `front-page.php`)
- No build step (no npm, no webpack, no compiled CSS)
- No local ACF field registration (disabled — all in DB)
