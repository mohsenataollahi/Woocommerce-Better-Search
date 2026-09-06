# Hooks and assets

## Register on the right hook

| Work | Hook |
|------|------|
| Constants / wiring | top of `actCore.php` |
| Shortcodes | `includes/functions.php` (this repo: `add_shortcode` at load) |
| Frontend CSS/JS | `wp_enqueue_scripts` |
| Admin menus | `admin_menu` |
| REST routes | `rest_api_init` |
| Elementor Loop Grid / Posts query | `elementor/query/{query_id}` in `includes/functions.php` — see `.cursor/skills/elementor/` |
| Activation / deactivation | top-level in `actCore.php`, not inside another hook |

Hook callbacks stay thin: capability/context, then a method.

Remove behavior with `remove_action` / `remove_filter`, not by copying core files.

## Enqueue

- `wp_enqueue_style` / `wp_enqueue_script` with a prefixed handle and a version.
- Dependencies listed explicitly (`['jquery']` only if the file uses jQuery).
- Footer scripts: last arg `true` for frontend JS.
- Do not print `<script src>` / `<link>` for plugin assets in PHP templates.

## When to load assets

- Admin: only on this plugin's screens (`$hook` from `admin_enqueue_scripts`).
- Frontend: prefer enqueue when the shortcode actually runs (or a known page), not on every request.
- This plugin has no frontend CSS/JS enqueue. Do not add global assets. New assets: load only where needed.

## Third-party / CDN

- Prefer WordPress-bundled libraries.
- This plugin does not load CDNs. Do not add one for a few lines of CSS.
- WordPress.org directory forbids third-party CDNs for JS/CSS (fonts excepted). This is a private plugin; still avoid extra CDNs.

## Localize

Pass PHP data to JS with `wp_localize_script` or `wp_add_inline_script` + `wp_json_encode`. Never interpolate PHP into a JS string unsanitized.
