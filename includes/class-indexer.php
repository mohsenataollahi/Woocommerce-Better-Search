<?php


class  WCPBSE_Index {


	public static function init() {
		add_action( 'save_post_product', [ self::class, 'index' ] );
		add_action( 'woocommerce_update_product', [ self::class, 'index' ] );
		add_action( 'woocommerce_product_set_stock', function ( $product ) {
			if ( $product instanceof WC_Product ) {
				self::index( $product->get_id() );
			}
		} );
		add_action('wcpbse_index_products_event',[self::class ,'process_batch']);
	}

	public static function index( int $product_id ) {

		if ( wp_is_post_revision( $product_id ) ) {
			return;
		}

		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			return;
		}

		if ( $product->get_status() !== 'publish' ) {
			self::delete( $product_id );

			return;
		}

		$data = [
			'product_id'       => $product_id,
			'title'            => $product->get_name(),
			'normalized_title' => self::normalizer( $product->get_name() ),
			'search_text'      => self::search_text_modifier( $product ),
			'updated_at'       => current_datetime()
		];

		global $wpdb;

		$table = WCPBSE_Database::tableConfig()['name'];

		$wpdb->replace( $table, $data );

	}

	public static function process_batch(){

	}

	private static function search_text_modifier( $product ): string {

		$title             = $product->get_name();
		$short_description = $product->get_short_description();
		$description       = $product->get_description();

		$search = implode( ',', array_filter( [
			$title,
			$short_description,
			$description
		] ) );

		return self::normalizer( $search );

	}

	public static function normalizer( string $text ): string {

		return wp_strip_all_tags( $text );

	}

	public static function delete( int $product_id ) {
		global $wpdb;

		$wpdb->delete(
			WCPBSE_Database::tableConfig()['name'],
			[ 'product_id' => $product_id ]
			['%d']
		);
	}
}
