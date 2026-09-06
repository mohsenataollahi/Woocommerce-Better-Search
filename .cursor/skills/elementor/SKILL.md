---
name: elementor
description: >
  Apply Elementor Pro custom query filters in this plugin. Use when writing,
  changing, or reviewing Elementor Query IDs, Loop Grid / Posts / Portfolio
  queries, elementor/query/* hooks, listing loops, or Elementor Pro query
  customization. Covers the official custom query filter API and this plugin's
  existing query IDs. Do not use community widget-addon scaffolds unless the
  user asks for a custom Elementor widget.
---

# Elementor (this plugin)

Elementor Inc does **not** ship an official Cursor skill. This skill is the official
[custom query filter](https://developers.elementor.com/docs/hooks/custom-query-filter/)
API plus the unofficial query-hook patterns from
[peixotorms/odinlayer-skills](https://github.com/peixotorms/odinlayer-skills)
(`elementor-hooks`) and
[guramzhgamadze/WordPress-Elementor-Skill](https://github.com/guramzhgamadze/WordPress-Elementor-Skill)
(`elementor-patterns.md` Loop Grid section), adapted to ActualizedCore.

This plugin is **not** an Elementor addon. It feeds Loop Grid / Posts widgets via Query IDs.
Do not add `Widget_Base`, custom controls, Dynamic Tags, Finder, or a singleton addon class
unless the user explicitly asks.

For WP security, hooks, and layout, still follow `wordpress` and `wordpress-best-practices`.

## This repo

Query hooks live in `includes/functions.php`. Register on `elementor/query/{id}`, then
`wp_actualized_set_listing_query( $query, $post_type, $meta_query )`.

| Query ID (editor field) | Callback | Post type | Meta filter |
| --- | --- | --- | --- |
| `actualized_transactions` | `wp_actualized_query_transactions` | `ACT_POST_TYPE` | `ACT_META_STATUS` = `sold` |
| `actualized_pending` | `wp_actualized_query_pending` | `ACT_POST_TYPE` | `ACT_META_STATUS` != `sold` |
| `actualized_all` | `wp_actualized_query_all` | `ACT_POST_TYPE` | `ACT_META_THUMBNAIL` not empty |
| `actualized_other` | `wp_actualized_query_other` | `ACT_POST_TYPE_OTHER` | `ACT_META_THUMBNAIL` not empty |

Editor: Posts / Loop Grid / Portfolio → Query → Query ID = one of those strings, exact match.

## Official hook

- **Type:** action
- **Name:** `elementor/query/{$query_id}`
- **Affects:** Posts, Portfolio, Loop Grid (Elementor Pro)
- **Callback:** receives `\WP_Query $query` (Elementor also passes the widget as a second arg; this plugin ignores it)

Modify with `$query->set()`, same as `pre_get_posts`. Do not replace the query object.

```php
add_action('elementor/query/actualized_transactions', 'wp_actualized_query_transactions');
```

New Query ID: named `wp_actualized_query_*` function + `add_action` next to the existing four.
Reuse `wp_actualized_set_listing_query` when the shape is post type + `meta_query`.

## Hard rules

- Query ID string in PHP must match the editor field. No prefix on the ID itself; hook prefix is `elementor/query/`.
- Use existing constants (`ACT_POST_TYPE`, `ACT_META_*`). Do not hardcode `'actualized'` / `'_status'` in new query code.
- Do **not** call `new WP_Query`, `get_posts()`, or `query_posts()` inside the callback. Elementor wires this hook through `pre_get_posts`; a nested query re-fires the same filter and can infinite-loop ([elementor#27513](https://github.com/elementor/elementor/issues/27513)).
- Do not unregister core widgets, inject editor controls, or enqueue Elementor editor JS for listing queries.
- `simple-code` wins: a new Query ID is a few lines in `includes/functions.php`, not a new class.

## When to open the reference

Custom query args, official examples, extra Elementor hooks → [references/query-filter.md](references/query-filter.md)

## Sources

- Official: [Custom Query Filter](https://developers.elementor.com/docs/hooks/custom-query-filter/)
- Unofficial: peixotorms `elementor-hooks` (query action table)
- Unofficial: guramzhgamadze `elementor-patterns.md` (Loop Grid query filter)
