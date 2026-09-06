# Coding standards

WordPress Coding Standards (WPCS) plus this plugin's existing shape.

## Prefix and globals

- Prefix: `wp_actualized_` functions, `ACT_*` constants (`ACT_PLUGIN_DIR`, `ACT_PLUGIN_URL`).
- Cron hook: `wp_actualized_cron_hook`. Post types: `actualized`, `actualized_other`.
- Do not add unprefixed functions to the global space. Keep `wp_actualized_*` names that Elementor and cron already use.

## File headers

Every PHP file that can be requested directly:

```php
if (! defined('ABSPATH')) {
    exit;
}
```

Main plugin file keeps the WP plugin header (`Plugin Name`, `Requires PHP`, etc.).

## PHP style

- PHP version = header `Requires PHP` (7.4). Do not use 8.0+ syntax.
- Typed properties and return types on **new** methods.
- No `extract()` in new code. No variable-variables. No `@` error suppression.
- No `query_posts()`. Use `WP_Query` or REST/post APIs.
- `static` only for pure config or a single option wrapper — not a hidden cache of request state.
- Yoda only if the file already uses Yoda. This repo does not.

## Naming

- Functions/methods: verbs (`wp_actualized_import_properties`, `wp_actualized_register_hidden_post_type`).
- Booleans: `is` / `has` / `can`.
- One word per concept. Do not mix `get` / `fetch` / `load` for the same kind of read in one class.
- Match nearby names (`generateCardview` / `generateHerocard`) instead of renaming the plugin in passing.

## i18n

- User-facing strings: `__()` / `esc_html__()` / `esc_html_e()` with the plugin text domain.
- Do not concatenate translated fragments if word order will break.
- Keep translator context in the English source string.

## Comments

- No narration of the next line.
- Comment why: MLS/API quirk, template contract, workaround.
- Delete commented-out code. Git keeps history.

## Formatting

- Match the file: existing code is compact, braces on next line in some files. Do not reformat the whole file.
- If PHPCS/WPCS is added later, that tool wins over personal taste.
