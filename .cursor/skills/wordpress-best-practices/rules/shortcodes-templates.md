# Shortcodes and templates

This plugin's main surface: `[actualized_thumbnail]`, hidden post types, Elementor query IDs.

## Shortcodes

- Register with `add_shortcode`. Callback **returns** HTML (`ob_start` / `ob_get_clean` is fine).
- Parse attributes with `shortcode_atts` when the shortcode has attributes.
- Keep the shortcode callback thin: read meta → return markup.
- Escape at output (`esc_url`, `esc_html`, `esc_attr`).

## Markup

- Escape at echo/return: `esc_html`, `esc_attr`, `esc_url`.
- No HTTP, no `$wpdb` inside a shortcode beyond reading post meta.
- No `extract()`.
