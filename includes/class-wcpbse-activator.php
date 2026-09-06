<?php

defined( 'ABSPATH' ) || exit;

final class WCPBSE_Activator {

	public static function activate(): void {
		WCPBSE_Database::create_table();

		if ( ! wp_next_scheduled( 'wcpbse_index_products_event' ) ) {
			wp_schedule_event(
				time() + 10,
				'wcpbse_every_hours',
				'wcpbse_index_products_event'
			);
		}
	}
}
