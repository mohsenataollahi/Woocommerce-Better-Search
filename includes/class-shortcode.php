<?php

class  WCPBSE_Shortcode {


	public static function init() {

		add_shortcode( 'wcpbsc-search-engine', [ self::class, 'render' ] );
		add_action( 'wp_enqueue_scripts', [ self::class, 'assets' ] );

	}

	public static function render() {

		ob_start();
		?>
        <div class="wcpbsc-search">
            <div class="wcpbsc-search-bar">
                <div class="wcpbsc-saerch-input-box">
                    <input type="search" class="wcpbsc-search-input" placeholder="...جستجوی محصولات" autocomplete="off">
                    <span class="wcpbsc-search-cross">>&#10005;</span>
                </div>
                <span class="wcpbsc-search-loader"></span>
                <span class="wcpbsc-search-magnifier" >&#128269</span>
            </div>
            <div class="wcpbsc-search-result"></div>
        </div>
		<?php
		return ob_get_clean();

	}

	public static function assets() {
		wp_enqueue_script(
			'wcpbsc-search',
			WCPBSE_URL . 'assets/main.js',
			[ 'jquery' ],
			WCPBSE_VERSION,
			true
		);
		wp_enqueue_style(
			'wcpbsc-search',
			WCPBSE_URL . 'assets/style.css',
			[ '' ],
			WCPBSE_VERSION,
		);

		wp_localize_script( 'wcpbsc-search', 'WCPBSC', [
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'wcpbsc-search' )
		] );

	}

}