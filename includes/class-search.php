<?php

defined( 'ABSPATH' ) || exit;

class WCPBSE_Search {

	private const RESULT_LIMIT = 8;
	private const FETCH_LIMIT  = 40;

	public static function init() {
		add_action( 'wp_ajax_wcpbse_search', [ self::class, 'ajax_search' ] );
		add_action( 'wp_ajax_nopriv_wcpbse_search', [ self::class, 'ajax_search' ] );
	}

	public static function ajax_search() {
		check_ajax_referer( 'wcpbse_search', 'nonce' );

		$query = isset( $_POST['query'] ) ? sanitize_text_field( wp_unslash( $_POST['query'] ) ) : '';
		$query = trim( $query );

		if ( mb_strlen( $query ) < 2 ) {
			wp_send_json_success( [ 'result' => [] ] );
		}

		$result = self::search( $query );

		wp_send_json_success( [ 'result' => $result ] );
	}

	public static function search( $query ): array {
		$rows   = self::fetch( $query );
		$result = [];

		foreach ( $rows as $row ) {
			$product = wc_get_product( (int) $row->product_id );

			if ( ! $product || ! WCPBSE_Index::is_product_searchable( $product ) ) {
				continue;
			}

			$image = get_the_post_thumbnail_url( (int) $row->product_id, 'thumbnail' );

			$result[] = [
				'id'    => (int) $row->product_id,
				'title' => $row->title,
				'url'   => get_permalink( (int) $row->product_id ),
				'image' => $image ? $image : '',
			];

			if ( count( $result ) >= self::RESULT_LIMIT ) {
				break;
			}
		}

		return $result;
	}

	public static function fetch( $query ): array {
		global $wpdb;

		$table = WCPBSE_Database::tableConfig()['name'];
		$like  = '%' . $wpdb->esc_like( $query ) . '%';

		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT product_id, title FROM {$table}
				WHERE title LIKE %s
				OR normalized_title LIKE %s
				OR search_text LIKE %s
				LIMIT %d",
				$like,
				$like,
				$like,
				self::FETCH_LIMIT
			)
		);

		return is_array( $rows ) ? $rows : [];
	}
}
