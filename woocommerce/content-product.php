<?php
/**
 * Карточка товара в каталоге (override WooCommerce 3.6.0)
 * @version 3.6.0
 */
defined( 'ABSPATH' ) || exit;
global $product;
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( 'cssc-pcard', $product ); ?>>
	<a class="cssc-pcard__media" href="<?php the_permalink(); ?>">
		<?php echo woocommerce_get_product_thumbnail( 'woocommerce_thumbnail' ); // phpcs:ignore ?>
	</a>
	<div class="cssc-pcard__body">
		<?php
		$cats = wc_get_product_category_list( $product->get_id(), ', ' );
		if ( $cats ) { echo '<div class="cssc-pcard__cat">' . wp_kses_post( $cats ) . '</div>'; }
		?>
		<h2 class="woocommerce-loop-product__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php woocommerce_template_loop_price(); ?>
		<div class="cssc-pcard__actions"><?php woocommerce_template_loop_add_to_cart(); ?></div>
	</div>
</li>
