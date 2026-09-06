<?php

defined( 'ABSPATH' ) || exit;

/**
 * Main plugin orchestrator.
 */
final class WCPBSE {

	public function run(): void {
		add_filter( 'cron_schedules', [ self::class, 'add_cron_schedule' ] );
		add_action( 'plugins_loaded', [ $this, 'load' ] );
	}

	/**
	 * @param array<string, array{interval:int, display:string}> $schedules Cron schedules.
	 * @return array<string, array{interval:int, display:string}>
	 */
	public static function add_cron_schedule( array $schedules ): array {
		$schedules['wcpbse_every_hours'] = [
			'interval' => HOUR_IN_SECONDS,
			'display'  => __( 'Every hour', 'woocommerce-better-search' ),
		];

		return $schedules;
	}

	public function load(): void {
		require_once WCPBSE_PATH . 'includes/class-wcpbse-i18n.php';
		WCPBSE_i18n::load_textdomain();

		if ( ! class_exists( 'WooCommerce' ) ) {
			add_action( 'admin_notices', [ $this, 'woocommerce_missing_notice' ] );

			return;
		}

		require_once WCPBSE_PATH . 'includes/class-wcpbse-indexer.php';
		require_once WCPBSE_PATH . 'includes/class-wcpbse-search.php';
		require_once WCPBSE_PATH . 'public/class-wcpbse-public.php';

		WCPBSE_Index::init();
		WCPBSE_Search::init();
		WCPBSE_Public::init();
	}

	public function woocommerce_missing_notice(): void {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		echo '<div class="notice notice-error"><p>';
		echo esc_html__(
			'WooCommerce Better Search requires WooCommerce to be installed and active.',
			'woocommerce-better-search'
		);
		echo '</p></div>';
	}
}
