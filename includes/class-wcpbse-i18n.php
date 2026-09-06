<?php

defined( 'ABSPATH' ) || exit;

final class WCPBSE_i18n {

	public static function load_textdomain(): void {
		load_plugin_textdomain(
			'woocommerce-better-search',
			false,
			dirname( WCPBSE_BASENAME ) . '/languages'
		);
	}
}
