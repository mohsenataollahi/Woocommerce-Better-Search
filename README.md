# WooCommerce Better Search

Custom product search for WooCommerce stores. Indexes product titles and descriptions into a dedicated table and exposes a live AJAX search box via shortcode.

- Plugin: `Woocommerce Better Search` 1.0.1

## Features

- Custom DB table for product search text (`{prefix}wc_products_search`)
- Re-index on product save / stock update
- Hourly cron batch re-index (50 products per run)
- AJAX live search (debounced)
- Frontend shortcode with loader UI

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

## How it works

| Piece | Role |
| --- | --- |
| `WCPBSE_Database` | Creates `{prefix}wc_products_search` on activation |
| `WCPBSE_Index` | Writes / deletes index rows; cron `process_batch` |
| `WCPBSE_Search` | AJAX action `wcpbse_search` |
| `WCPBSE_Shortcode` | UI + assets (`assets/main.js`, `assets/style.css`) |

Indexed fields: `title`, `normalized_title`, `search_text` (title + short description + description).

## Development layout

```text
woocommerce-better-search/
├── woocommerce-better-search.php
├── includes/
│   ├── class-database.php
│   ├── class-indexer.php
│   ├── class-search.php
│   └── class-shortcode.php
├── assets/
│   ├── main.js
│   └── style.css
└── README.md
```

Code prefix: `WCPBSE_` / `wcpbse_` / `wcpbsc-`. Text domain: `woocommerce-better-search`.

## License

GPL v2 or later. See [GNU GPL v2](https://www.gnu.org/licenses/gpl-2.0.html).

## Author

Mohsen Ataollahi — https://github.com/mohsenataollahi
