<?php
/**
 * AJAX-поиск товаров WooCommerce.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Выполняет AJAX-поиск товаров.
 */
function cssc_ajax_product_search() {
	check_ajax_referer( 'cssc_product_search', 'nonce' );

	if ( ! class_exists( 'WooCommerce' ) ) {
		wp_send_json_error(
			array(
				'message' => 'WooCommerce не активирован.',
			),
			503
		);
	}

	$term = isset( $_POST['query'] )
		? sanitize_text_field( wp_unslash( $_POST['query'] ) )
		: '';

	$term = trim( $term );

	if ( function_exists( 'mb_substr' ) ) {
		$term = mb_substr( $term, 0, 80 );
	} else {
		$term = substr( $term, 0, 80 );
	}

	$term_length = function_exists( 'mb_strlen' )
		? mb_strlen( $term )
		: strlen( $term );

	if ( $term_length < 2 ) {
		wp_send_json_success(
			array(
				'items'        => array(),
				'total'        => 0,
				'view_all_url' => '',
			)
		);
	}

	$product_ids = array();

	/*
	 * Основной поиск WordPress:
	 * название, описание и краткое описание товара.
	 */
	$text_query = new WP_Query(
		array(
			'post_type'              => 'product',
			'post_status'            => 'publish',
			's'                      => $term,
			'posts_per_page'         => 12,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'ignore_sticky_posts'    => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	if ( $text_query->posts ) {
		$product_ids = array_map( 'absint', $text_query->posts );
	}

	/*
	 * Дополнительный поиск по артикулу SKU.
	 */
	global $wpdb;

	$sku_like = '%' . $wpdb->esc_like( $term ) . '%';

	$sku_ids = $wpdb->get_col(
		$wpdb->prepare(
			"
			SELECT DISTINCT post_id
			FROM {$wpdb->postmeta}
			WHERE meta_key = '_sku'
			AND meta_value LIKE %s
			LIMIT 12
			",
			$sku_like
		)
	);

	if ( $sku_ids ) {
		$sku_ids = array_map( 'absint', $sku_ids );

		foreach ( $sku_ids as $sku_id ) {
			$post_type = get_post_type( $sku_id );

			if ( 'product_variation' === $post_type ) {
				$parent_id = wp_get_post_parent_id( $sku_id );

				if ( $parent_id ) {
					$product_ids[] = absint( $parent_id );
				}
			} elseif ( 'product' === $post_type ) {
				$product_ids[] = $sku_id;
			}
		}
	}

	$product_ids = array_values( array_unique( array_filter( $product_ids ) ) );
	$product_ids = array_slice( $product_ids, 0, 8 );

	$items = array();

	foreach ( $product_ids as $product_id ) {
		$product = wc_get_product( $product_id );

		if (
			! $product ||
			! $product->get_id() ||
			'publish' !== get_post_status( $product->get_id() ) ||
			! $product->is_visible()
		) {
			continue;
		}

		$image_id  = $product->get_image_id();
		$image_url = $image_id
			? wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' )
			: wc_placeholder_img_src( 'woocommerce_thumbnail' );

		$categories = get_the_terms( $product->get_id(), 'product_cat' );
		$category   = '';

		if ( $categories && ! is_wp_error( $categories ) ) {
			$category = $categories[0]->name;
		}

		$items[] = array(
			'id'         => $product->get_id(),
			'name'       => $product->get_name(),
			'url'        => $product->get_permalink(),
			'image'      => $image_url,
			'price_html' => wp_kses_post( $product->get_price_html() ),
			'sku'        => $product->get_sku(),
			'category'   => $category,
		);
	}

	$view_all_url = add_query_arg(
		array(
			's'         => $term,
			'post_type' => 'product',
		),
		home_url( '/' )
	);

	wp_send_json_success(
		array(
			'items'        => $items,
			'total'        => count( $items ),
			'view_all_url' => esc_url_raw( $view_all_url ),
		)
	);
}

add_action( 'wp_ajax_cssc_product_search', 'cssc_ajax_product_search' );
add_action( 'wp_ajax_nopriv_cssc_product_search', 'cssc_ajax_product_search' );
