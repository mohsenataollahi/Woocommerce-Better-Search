<?php
/**
 * Plugin Name:       Woocommerce Better Search
 * Plugin URI:        https://github.com/mohsenataollahi/Woocommerce-Better-Search
 * Description:       Better WooCommerce product AJAX search with a dedicated search index.
 * Version:           1.1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Mohsen Ataollahi
 * Author URI:        https://github.com/mohsenataollahi
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       woocommerce-better-search
 * Domain Path:       /languages
 * Requires Plugins:  woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WCPBSE_VERSION', '1.1.0' );
define( 'WCPBSE_FILE', __FILE__ );
define( 'WCPBSE_PATH', plugin_dir_path( __FILE__ ) );
define( 'WCPBSE_URL', plugin_dir_url( __FILE__ ) );
define( 'WCPBSE_BASENAME', plugin_basename( __FILE__ ) );

require_once WCPBSE_PATH . 'includes/class-wcpbse-database.php';
require_once WCPBSE_PATH . 'includes/class-wcpbse-activator.php';
require_once WCPBSE_PATH . 'includes/class-wcpbse-deactivator.php';
require_once WCPBSE_PATH . 'includes/class-wcpbse.php';

register_activation_hook( __FILE__, [ 'WCPBSE_Activator', 'activate' ] );
register_deactivation_hook( __FILE__, [ 'WCPBSE_Deactivator', 'deactivate' ] );

/**
 * Boot the plugin.
 *
 * @since 1.1.0
 */
function wcpbse_run(): void {
	$plugin = new WCPBSE();
	$plugin->run();
}

wcpbse_run();
