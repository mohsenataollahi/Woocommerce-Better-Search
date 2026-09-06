<?php


class  WCPBSE_Search {

	public static function init() {
		add_action( 'wp_ajax_wcpbse_search', [ self::class, 'ajax_search' ] );
		add_action( 'wp_ajax_nopriv_wcpbse_search', [ self::class, 'ajax_search' ] );
	}

	public static function ajax_search() {

		check_ajax_referer( 'wcpbse_search', 'nonce' );

		$query = isset( $_POST['query'] ) ? sanitize_text_field( wp_unslash( $_POST['query'] ) ) : "";

		$query = trim( $query );

		if ( mb_strlen( $query < 2 ) ) {
			wp_send_json_success( [
				'result' => ""
			] );
		}
		$result = self::search( $query );

		wp_send_json_success( [ 'result' => $result ] );

	}

	public static function search( $query ): array {

		$rows = self::fetch( $query );

		$result = [];

		foreach ( $rows as $row ) {

			$product = wc_get_product( (int) $row->product_id );

			if ( ! $product ) {
				continue;
			}

			$result[] = [
				'id'    => (int) $row->product_id,
				'title' => $row->title,
				'url'   => get_permalink( $row->product_id ),
				'image' => get_the_post_thumbnail_url( $row->product_id, 'thumbnail' )
			];
		}

		return $result;
	}

	public static function fetch( $query ): ?array {

		global $wpdb;

		$table = WCPBSE_Database::tableConfig()['name'];

		$like = '%' . $wpdb->esc_like( $query ) . '%';

		return $wpdb->get_results(
			$wpdb->prepare(
				" SELECT product_id,title FROM {$table}
						WHERE title LIKE %s
						OR normalized_title LIKE %s
						OR search_text LIKE  %s, LIMIT 8 ", $like, $like, $like )
		);
	}
}