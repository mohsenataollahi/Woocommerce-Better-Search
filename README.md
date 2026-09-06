# WooCommerce Better Search

Custom product search for WooCommerce stores. Indexes product titles and descriptions into a dedicated table and exposes a live AJAX search box via shortcode.

- Plugin: `Woocommerce Better Search` 1.1.0

## Features

- Custom DB table for product search text (`{prefix}wc_products_search`)
- Re-index on product save / stock update
- Hourly cron batch re-index (50 products per run)
- AJAX live search (debounced)
- Frontend shortcode with loader UI
- Bilingual UI (English source + Persian `fa_IR` translations)

## Requirements

- WordPress 5.2+
- PHP 7.2+
- [WooCommerce](https://wordpress.org/plugins/woocommerce/)

## Installation

1. Clone or download into `wp-content/plugins/woocommerce-better-search`
2. Activate **Woocommerce Better Search** under **Plugins**
3. Ensure WooCommerce is active
4. Place the shortcode where the search box should appear

```text
[wcpbsc-search-engine]
```

5. Deactivate/reactivate once after upgrade so `dbDelta` can repair the search table schema
6. Wait for hourly cron batches, or edit/save products to fill the index

## Usage

Add `[wcpbsc-search-engine]` to any page, post, or widget that supports shortcodes. Typing (2+ characters) searches the index and lists matching products with thumbnail, title, and link.

Site language `English` shows English strings. Site language `فارسی` (`fa_IR`) loads translations from `languages/`.

## How it works

| Piece | Role |
| --- | --- |
| `WCPBSE` | Orchestrator, cron schedule, WooCommerce guard |
| `WCPBSE_Database` | Creates `{prefix}wc_products_search` on activation |
| `WCPBSE_Index` | Writes / deletes index rows; cron `process_batch` |
| `WCPBSE_Search` | AJAX action `wcpbse_search` |
| `WCPBSE_Public` | Shortcode UI + public assets |
| `WCPBSE_i18n` | Loads text domain from `/languages` |

Indexed fields: `title`, `normalized_title`, `search_text` (title + short description + description).

## Development layout

```text
woocommerce-better-search/
├── woocommerce-better-search.php
├── uninstall.php
├── LICENSE
├── includes/
│   ├── class-wcpbse.php
│   ├── class-wcpbse-activator.php
│   ├── class-wcpbse-deactivator.php
│   ├── class-wcpbse-i18n.php
│   ├── class-wcpbse-database.php
│   ├── class-wcpbse-indexer.php
│   └── class-wcpbse-search.php
├── public/
│   ├── class-wcpbse-public.php
│   ├── css/wcpbse-public.css
│   ├── js/wcpbse-public.js
│   └── partials/wcpbse-search.php
├── languages/
│   ├── woocommerce-better-search.pot
│   ├── woocommerce-better-search-fa_IR.po
│   └── woocommerce-better-search-fa_IR.mo
└── README.md
```

Code prefix: `WCPBSE_` / `wcpbse_` / `wcpbsc-` (CSS). Text domain: `woocommerce-better-search`.

## License

GPL v2 or later. See [GNU GPL v2](https://www.gnu.org/licenses/gpl-2.0.html).

## Author

Mohsen Ataollahi — https://github.com/mohsenataollahi
