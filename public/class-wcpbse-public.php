<?php

defined( 'ABSPATH' ) || exit;

final class WCPBSE_Public {

	public static function init(): void {
		add_shortcode( 'wcpbsc-search-engine', [ self::class, 'render' ] );
		add_action( 'wp_enqueue_scripts', [ self::class, 'assets' ] );
	}

	public static function render(): string {
		wp_enqueue_script( 'wcpbse-public' );
		wp_enqueue_style( 'wcpbse-public' );

		ob_start();
		include WCPBSE_PATH . 'public/partials/wcpbse-search.php';
		return (string) ob_get_clean();
	}

	public static function assets(): void {
		wp_register_script(
			'wcpbse-public',
			WCPBSE_URL . 'public/js/wcpbse-public.js',
			[ 'jquery' ],
			WCPBSE_VERSION,
			true
		);
		wp_register_style(
			'wcpbse-public',
			WCPBSE_URL . 'public/css/wcpbse-public.css',
			[],
			WCPBSE_VERSION
		);

		wp_localize_script(
			'wcpbse-public',
			'WCPBSC',
			[
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'wcpbse_search' ),
				'i18n'    => [
					'noResults' => __( 'No results found.', 'woocommerce-better-search' ),
				],
			]
		);
	}
}
