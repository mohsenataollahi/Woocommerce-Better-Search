<?php

defined( 'ABSPATH' ) || exit;

final class WCPBSE_Deactivator {

	public static function deactivate(): void {
		wp_clear_scheduled_hook( 'wcpbse_index_products_event' );
	}
}
