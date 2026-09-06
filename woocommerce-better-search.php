<?php
/*
 * Plugin Name:       Woocommerce Better Search
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Mohsen Ataollahi
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       WPBSE
 * Domain Path:       /languages
 * Requires Plugins:  woocommerce
 */


defined( 'ABSPATH' ) || exit;

const WCPBSE_VERSION = '1.0.0';
define( 'WCPBSE_PATH', plugin_dir_path( __FILE__ ) );
define( 'WCPBSE_URL', plugin_dir_url( __FILE__ ) );


require_once WCPBSE_PATH . 'includes/class-database.php';
require_once WCPBSE_PATH . 'includes/class-indexer.php';
require_once WCPBSE_PATH . 'includes/class-search.php';
require_once WCPBSE_PATH . 'includes/class-shortcode.php';


add_filter( 'cron_schedules', function ( $schedules ) {

	$schedules['wcpbse_every_hours'] = [
		'interval' => 3600,
		'display'  => 'Every Hour'
	];

} );

register_activation_hook( __FILE__, function () {
	WCPBSE_Database::createTable();

	if ( ! wp_next_scheduled( 'wcpbse_index_products_event' ) ) {

		wp_schedule_event(
			time() + 10,
			'wcpbse_every_hours',
			'wcpbse_index_products_event'
		);

	}


} );

register_deactivation_hook( __FILE__, function () {

	$timestamp = wp_next_scheduled( 'wcpbse_index_products_event' );

	if($timestamp){
		wp_unschedule_event($timestamp,'wcpbse_index_products_event');
	}

} );

add_action( 'plugin_loaded', function () {

	if ( ! class_exists( 'Woocommerce' ) ) {
		return;
	}

	WCPBSE_Index::init();
	WCPBSE_Search::init();
	WCPBSE_Shortcode::init();
} );




