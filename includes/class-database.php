<?php

defined( 'ABSPATH' ) || exit;

class WCPBSE_Database {

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
   		id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
 		product_id BIGINT UNSIGNED NOT NULL,
        title TEXT NOT NULL ,
        normalized_title TEXT NOT NULL,
        search_text LONGTEXT NOT NULL,
        updated_at datetime NOT NULL ,
        
        PRIMARY KEY  (id),
        UNIQUE KEY product_id (product_id),
        FULLTEXT KEY ft_search (title, normalized_title, search_text)
		) {$config['charset']}";
		dbDelta( $sql );
	}

}
