---
name: wordpress
description: >
  Write WordPress plugins the WP way: bootstrap, hooks, Settings API, shortcodes,
  enqueue, security (nonces, capabilities, sanitize-in escape-out, $wpdb->prepare),
  i18n, REST permission callbacks, no core hacks. Use when working in WordPress,
  PHP, wp-content, plugins, themes, shortcodes, $wpdb, add_action/add_filter,
  Gutenberg, WooCommerce, or WP_REST.
---

# WordPress plugin development

WP APIs and hooks. Never patch core. Never load WP from a random PHP script if the plugin bootstrap already exists.

This skill is the WordPress.org Plugin Handbook + WPCS path. For impact-ordered rules, also follow `wordpress-best-practices`.

## This repo (ActualizedCore)

Private site plugin. Match existing layout. Do **not** migrate to a full Plugin Boilerplate (`admin/` / `public/` class tree) unless the user asks.

```
actCore.php                 bootstrap, constants, activation hooks
includes/functions.php          CPT, cron, Elementor queries, API import, shortcode
assets/img/thumbnail.png    default listing thumbnail
```

- Prefix global WP space with `wp_actualized_` / `ACT_`.
- `actCore.php` wires only. Feature logic lives in `includes/functions.php`.
- Shortcodes return HTML.
- Text domain: match plugin slug (`actualizedcore` or existing `__()` domain). Do not invent a second domain.

## Before writing

Read the relevant reference only when needed:

- New structure / new class → [references/architecture.md](references/architecture.md)
- Input, output, SQL, nonces, caps, HTTP → [references/security.md](references/security.md)
- Public directory / GPL / no tracking → [references/wp-org-guidelines.md](references/wp-org-guidelines.md)
- Elementor Query IDs / Loop Grid → [../elementor/SKILL.md](../elementor/SKILL.md)

## Hard rules

- Bootstrap files: `if (! defined('ABSPATH')) { exit; }`
- Prefix everything that enters the global WP space: options, post meta, cron hooks, REST namespace, script handles, CSS handles.
- Input: sanitize (`sanitize_text_field`, `absint`, `wp_kses_post`, `rest_sanitize_*`). Output: escape (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`). SQL: `$wpdb->prepare`.
- Capability check on every mutating path. REST: `permission_callback` with `current_user_can`, not "user is logged in".
- Forms: nonce. REST: WP cookie + REST nonce; still check caps.
- User-facing strings: `__()` / `esc_html__()` with the plugin text domain.
- Enqueue with `wp_enqueue_script` / `wp_enqueue_style` on the screen that needs them. No assets on every admin page.
- Direct DB only when options/meta/posts APIs cannot. Custom tables: `$wpdb->prefix`, dbDelta on activate, version option, `uninstall.php` cleanup.
- HTTP: `wp_safe_remote_get` / `wp_safe_remote_post`. Check `is_wp_error`. Do not concatenate untrusted URLs.

## Architecture

- Main file: header + constants + hooks + loader. No feature HTML.
- Prefer existing `wp_actualized_*` functions in `includes/functions.php` over a new class unless the feature does not fit there.
- Keep admin-only code behind `admin_menu` / `is_admin()` so the frontend stays light.
- `final class` when adding new classes. One class per file. This repo is procedural (`actCore.php` + `includes/functions.php`) — do not invent Plugin Boilerplate class names.
- Autoload: keep the project's loader. Do not add Composer unless the repo already has it.

## Hooks and lifecycle

- Register on `plugins_loaded` / `init` / `rest_api_init` / `admin_menu` as WP expects.
- Activation/deactivation hooks at top-level in the main file, not inside other hooks.
- Flush rewrite rules only when needed and only after registering CPTs/rules.
- Uninstall: `uninstall.php` or `register_uninstall_hook`. Delete only this plugin's data.
- Remove behavior with `remove_action` / filters, not by copying core files.
- Hook callbacks stay thin: cap/context then a method call.

## REST

- Namespace like `actualized/v1`.
- Every route has `permission_callback`. `edit_posts` vs `manage_options` chosen by risk.
- Return `WP_REST_Response` or `WP_Error`.
- Validate params with `args` schema or explicit checks. Fail with a clear, translated message.

## PHP

- PHP version = plugin header `Requires PHP` (this plugin: 7.4).
- Typed properties and return types on new code.
- No `@` error suppression. No `query_posts()`. Use `WP_Query` or REST/post APIs.
- Avoid new `extract()`. Existing view includes already use it — do not spread it.
- Time: `current_time()` / `gmdate`. Do not assume `date_default_timezone_set`.

## JS / assets

- Localize with `wp_localize_script` or `wp_add_inline_script` + `wp_json_encode`.
- Use `apiFetch` / `wp.apiFetch` in WP admin. Send credentials. Handle `WP_Error` payloads.
- No new jQuery plugins unless the file already depends on jQuery (this plugin already enqueues jQuery).
- Prefer bundled WP libraries over a second copy of jQuery.

## i18n

- Text domain matches plugin slug.
- Keep translator context in the English source string. Do not concatenate translated fragments if word order will break.

## Security checklist (every change)

- [ ] Cap checked on mutating paths
- [ ] Input sanitized
- [ ] Output escaped
- [ ] SQL prepared
- [ ] Remote HTTP uses WP HTTP API + error check
- [ ] Secrets not in repo, not in `error_log`
- [ ] Uninstall does not delete other plugins' data

## Do not

- Invent a new top-level folder for one function.
- Bundle WordPress default libraries.
- Contact external servers for tracking without opt-in (listing API this plugin already calls is product data, not tracking).
- Patch `wp-includes` / `wp-admin`.
