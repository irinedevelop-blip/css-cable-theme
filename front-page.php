<?php
/**
 * Главная. Если страница собрана в Elementor — выводится контент Elementor,
 * иначе — секции по умолчанию (как в макете).
 */
defined( 'ABSPATH' ) || exit;
get_header();

$use_elementor = false;
if ( 'page' === get_option( 'show_on_front' ) && have_posts() ) {
	the_post();
	$id = get_the_ID();
	if ( did_action( 'elementor/loaded' ) && \Elementor\Plugin::$instance->documents->get( $id ) && \Elementor\Plugin::$instance->documents->get( $id )->is_built_with_elementor() ) {
		$use_elementor = true;
		the_content();
	}
}
if ( ! $use_elementor ) {
	cssc_render_hero();
	cssc_render_products();
	cssc_render_deliveries();
	cssc_render_news();
}
get_footer();
