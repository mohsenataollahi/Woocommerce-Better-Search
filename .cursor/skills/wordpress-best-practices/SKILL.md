---
name: wordpress-best-practices
description: >
  Apply WordPress best practices whenever writing, reviewing, or refactoring
  plugin PHP, JS, CSS, shortcodes, templates, REST, $wpdb, hooks, enqueue, or
  HTTP. Covers security, WPCS, data storage, remote requests, shortcode/template
  rendering, and performance. Use for WordPress code reviews and when aligning
  existing code to Plugin Handbook patterns.
---

# WordPress best practices

Best practices for WordPress plugins, prioritized by impact. Each rule teaches what to do and why.

## Consistency first

Before applying any rule, check what this plugin already does. WordPress offers multiple valid approaches — the best choice is the one the codebase already uses, even if another pattern would be theoretically better. Inconsistency is worse than a suboptimal pattern.

Check sibling files (`actCore.php`, `includes/functions.php`) for established patterns. If one exists, follow it — do not introduce a second way. These rules are defaults for when no pattern exists yet, not a license to rewrite the plugin.

## Quick reference

### 1. Security → `rules/security.md`

- Sanitize on input (`sanitize_text_field`, `absint`, `wp_kses_post`)
- Escape on output (`esc_html`, `esc_attr`, `esc_url`)
- `$wpdb->prepare()` for all custom SQL
- Nonce **and** `current_user_can` on mutating paths
- `wp_safe_remote_*` for HTTP; never raw `file_get_contents` / curl for WP URLs

### 2. Coding standards → `rules/coding-standards.md`

- Prefix global names (`wp_actualized_`, `ACT_`)
- `ABSPATH` guard on PHP entry files
- Typed new PHP; no `@` suppression; no `query_posts()`
- i18n for user-facing strings
- Match existing file naming in this repo (`actCore.php`, not WPPB unless asked)

### 3. Hooks and assets → `rules/hooks-assets.md`

- Register on the correct hook (`init`, `wp_enqueue_scripts`, `admin_menu`)
- Enqueue, never hardcode `<script>` / `<link>` in PHP for plugin assets
- Condition frontend assets when the shortcode actually renders
- Activation/deactivation at top-level in the main file

### 4. Data and HTTP → `rules/data-http.md`

- Options for small config; transients for expensive listing API
- Custom tables only when options/meta/posts cannot
- `WP_Error` at the edge; do not `echo` + `die` from helpers
- Remote listing API: timeout, error check, validate JSON shape

### 5. Shortcodes and templates → `rules/shortcodes-templates.md`

- Shortcode callbacks return a string, never echo (except when WP already expects echo)
- `shortcode_atts` with a named shortcode
- Escape at output
- Do not `extract()` in new code

## How to apply

1. Identify the file type (bootstrap, shortcode, view, asset, REST) and open the matching rule file.
2. Check siblings first — follow those patterns.
3. Security rules always apply, even when matching a messy sibling (fix the line you touch).
4. Do not restructure the plugin to look like Plugin Boilerplate unless the user asks.

## Conflict with quality skills

`simple-code` forbids a new architecture layer. These practices still require sanitize/escape/prepare on the lines you write.
