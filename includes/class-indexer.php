<?php

defined( 'ABSPATH' ) || exit;

class WCPBSE_Index {

	private const BATCH_SIZE = 50;

	public static function init() {
		add_action( 'save_post_product', [ self::class, 'index' ] );
		add_action( 'woocommerce_update_product', [ self::class, 'index' ] );
		add_action( 'woocommerce_product_set_stock', function ( $product ) {
			if ( $product instanceof WC_Product ) {
				self::index( $product->get_id() );
			}
		} );
		add_action( 'wcpbse_index_products_event', [ self::class, 'process_batch' ] );
	}

	public static function index( int $product_id ) {

		if ( wp_is_post_revision( $product_id ) ) {
			return;
		}

		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			return;
		}

		if ( ! self::is_product_searchable( $product ) ) {
			self::delete( $product_id );

			return;
		}

		$data = [
			'product_id'       => $product_id,
			'title'            => $product->get_name(),
			'normalized_title' => self::normalizer( $product->get_name() ),
			'search_text'      => self::search_text_modifier( $product ),
			'updated_at'       => current_time( 'mysql' ),
		];

		global $wpdb;

		$table = WCPBSE_Database::tableConfig()['name'];

		$wpdb->replace(
			$table,
			$data,
			[ '%d', '%s', '%s', '%s', '%s' ]
		);

	}

	public static function process_batch() {
		$offset = (int) get_option( 'wcpbse_index_offset', 0 );

		$query = new WP_Query(
			[
				'post_type'              => 'product',
				'post_status'            => 'publish',
				'posts_per_page'         => self::BATCH_SIZE,
				'offset'                 => $offset,
				'fields'                 => 'ids',
				'orderby'                => 'ID',
				'order'                  => 'ASC',
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			]
		);

		$ids = $query->posts;

		if ( empty( $ids ) ) {
			update_option( 'wcpbse_index_offset', 0, false );

			return;
		}

		foreach ( $ids as $product_id ) {
			self::index( (int) $product_id );
		}

		$next_offset = count( $ids ) < self::BATCH_SIZE
			? 0
			: $offset + self::BATCH_SIZE;

		update_option( 'wcpbse_index_offset', $next_offset, false );
	}

	private static function search_text_modifier( $product ): string {

		$title             = $product->get_name();
		$short_description = $product->get_short_description();
		$description       = $product->get_description();

		$search = implode( ',', array_filter( [
			$title,
			$short_description,
			$description,
		] ) );

		return self::normalizer( $search );

	}

	public static function normalizer( string $text ): string {

		return wp_strip_all_tags( $text );

	}

	public static function is_product_searchable( WC_Product $product ): bool {
		if ( $product->get_status() !== 'publish' ) {
			return false;
		}

		$visibility = $product->get_catalog_visibility();

		if ( ! in_array( $visibility, [ 'visible', 'search' ], true ) ) {
			return false;
		}

		$post = get_post( $product->get_id() );

		return ! ( $post instanceof WP_Post && $post->post_password !== '' );
	}

	public static function delete( int $product_id ) {
		global $wpdb;

		$wpdb->delete(
			WCPBSE_Database::tableConfig()['name'],
			[ 'product_id' => $product_id ],
			[ '%d' ]
		);
	}
}
