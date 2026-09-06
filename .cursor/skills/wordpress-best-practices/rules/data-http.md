# Data and HTTP

## Storage choice

| Need | Use |
|------|-----|
| Small config | `get_option` / `update_option` with `act_` prefix |
| Expensive listing payload | Transients (`get_transient` / `set_transient`) with a short TTL |
| Per-post data | post meta |
| Relational / large rows | custom table (`$wpdb->prefix`, dbDelta, schema version) |

Prefer options/meta/posts. Custom tables only when those cannot.

Direct `$wpdb` queries: always `$wpdb->prepare()`. See `rules/security.md`.

## Remote listing API

This plugin fetches residential listings with `wp_safe_remote_post`.

- Build URLs from constants or `site_url()`, not mixed hardcoded host + commented swap.
- Set `'timeout'` on every request.
- Check `is_wp_error( $request )` then HTTP status (`wp_remote_retrieve_response_code`).
- `json_decode` with `true`; validate the shape (`isset( $data['content'] )`) before use.
- On failure return an empty list or a `WP_Error` — do not return a raw English string that templates treat as listings.
- Do not log API keys. Do not commit secrets.

## Errors

- Fail as data at the edge: `WP_Error`, empty array, or a translated message in the shortcode return string.
- Do not `echo` + `die` from a helper.
- Empty `catch` is forbidden. Swallowing `WP_Error` by returning `'API request error'` as if it were listing rows is a bug — fix it when touching that path.

## Time

`current_time()` / `gmdate` per WP practice. Do not call `date_default_timezone_set`.

## Uninstall

If the plugin stores options/tables/meta, `uninstall.php` deletes **only** those keys/tables. Never other plugins' data.
