# Elementor custom query filter

Official API: [developers.elementor.com — Custom Query Filter](https://developers.elementor.com/docs/hooks/custom-query-filter/)

Unofficial hook tables: peixotorms/odinlayer-skills `elementor-hooks`. Loop Grid example: guramzhgamadze `elementor-patterns.md`.

## Setup

1. In Elementor Pro widget (Posts, Portfolio, or Loop Grid), set **Query ID** to a unique slug.
2. Hook `elementor/query/{that_slug}`.
3. Mutate the passed `\WP_Query` with `$query->set()`.

This plugin already has four IDs. Prefer extending those filters or adding a sibling `wp_actualized_query_*` — do not invent a second query helper unless `wp_actualized_set_listing_query` cannot express the filter.

## Official-style examples (adapt names)

Post type:

```php
function wp_actualized_query_example($query): void
{
    $query->set('post_type', [ACT_POST_TYPE]);
}
```

Post meta (this plugin's shape):

```php
wp_actualized_set_listing_query($query, ACT_POST_TYPE, [
    [
        'key' => ACT_META_STATUS,
        'value' => 'sold',
        'compare' => '=',
    ],
]);
```

`$query->set` also accepts normal `WP_Query` args: `post_status`, `orderby`, `order`, `posts_per_page`, `tax_query`, `date_query`. Match nearby code: listings use `post_status` `publish` and `meta_query` via the helper.

`no_found_rows => true` skips `COUNT(*)` when the widget has no pagination. Do not add it here unless pagination is unused; the existing helper does not set it.

## Do not

- Nested `WP_Query` / `get_posts()` inside the callback (infinite `pre_get_posts` loop).
- `query_posts()`.
- Rename an existing Query ID without updating every Loop Grid that uses it.
- Copy community widget-addon folders (`includes/plugin.php` singleton, `Widget_Base`) into this repo for a query change.

## Other Elementor hooks (only if asked)

This plugin does not use these. Open official docs, do not scaffold them unprompted:

| Need | Hook |
| --- | --- |
| Custom widget | `elementor/widgets/register` |
| Inject control into existing widget | `elementor/element/{widget}/{section}/before_section_end` |
| Frontend after widget ready | `elementorFrontend.hooks` → `frontend/element_ready/{widget.skin}` |
| Form submit (Pro) | `elementor_pro/forms/new_record` |
