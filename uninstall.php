<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

wp_clear_scheduled_hook( 'wcpbse_index_products_event' );
delete_option( 'wcpbse_index_offset' );

global $wpdb;

$table = $wpdb->prefix . 'wc_products_search';
// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- table name from $wpdb->prefix only.
$wpdb->query( "DROP TABLE IF EXISTS {$table}" );
