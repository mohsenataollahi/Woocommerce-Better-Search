<?php

use JetBrains\PhpStorm\ArrayShape;

defined( 'ABSPATH' ) || exit;

class WCPBSE_Database {

	#[ArrayShape( [ 'name' => "string", 'charset' => "string" ] )]
	public static function tableConfig(): array {

		global $wpdb;

		return [
			'name'    => $wpdb->prefix . 'wc_products_search',
			'charset' => $wpdb->get_charset_collate(),
		];
	}

	public static function createTable() {

		global $wpdb;

		$config = self::tableConfig();

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$sql = "CREATE TABLE {$config['name']} (
   		id BIGINT UNSIGNED NOT NULL AUTOINCRIMENT,
 		product_id BIGINT UNSIGNED NOT NULL,
        title TEXT NOT NULL ,
        normalized_title TEXT NOT NULL,
        search_text LONGTEXT NOT NULL,
        updated_at datatime NOT NULL ,
        
        PRIMARY KEY (id),
        UNIQUE KEY (product_id),
        FULLTEXT KEY ftSearch(
            title,
            normalized_title,
        )
		){$config['charset']}";
		dbDelta( $sql );
	}

}
