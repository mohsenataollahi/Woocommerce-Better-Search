<?php

defined( 'ABSPATH' ) || exit;

class WCPBSE_Shortcode {

	public static function init() {
		add_shortcode( 'wcpbsc-search-engine', [ self::class, 'render' ] );
		add_action( 'wp_enqueue_scripts', [ self::class, 'assets' ] );
	}

	public static function render() {
		wp_enqueue_script( 'wcpbsc-search' );
		wp_enqueue_style( 'wcpbsc-search' );

		ob_start();
		?>
		<div class="wcpbsc-search">
			<div class="wcpbsc-search-bar">
				<div class="wcpbsc-search-input-box">
					<input type="search" class="wcpbsc-search-input" placeholder="<?php echo esc_attr__( '...جستجوی محصولات', 'woocommerce-better-search' ); ?>" autocomplete="off">
					<span class="wcpbsc-search-cross">&#10005;</span>
				</div>
				<span class="wcpbsc-search-loader"></span>
				<span class="wcpbsc-search-magnifier">&#128269;</span>
			</div>
			<div class="wcpbsc-search-result"></div>
		</div>
		<?php
		return ob_get_clean();
	}

	public static function assets() {
		wp_register_script(
			'wcpbsc-search',
			WCPBSE_URL . 'assets/main.js',
			[ 'jquery' ],
			WCPBSE_VERSION,
			true
		);
		wp_register_style(
			'wcpbsc-search',
			WCPBSE_URL . 'assets/style.css',
			[],
			WCPBSE_VERSION
		);

		wp_localize_script(
			'wcpbsc-search',
			'WCPBSC',
			[
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'wcpbse_search' ),
			]
		);
	}
}
