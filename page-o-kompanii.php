<?php
/**
 * Страница «О компании» (slug o-kompanii). Если страница собрана в Elementor — выводится Elementor,
 * иначе — все секции по умолчанию.
 */
defined( 'ABSPATH' ) || exit;
get_header();
while ( have_posts() ) {
	the_post();
	$is_el = did_action( 'elementor/loaded' ) && \Elementor\Plugin::$instance->documents->get( get_the_ID() ) && \Elementor\Plugin::$instance->documents->get( get_the_ID() )->is_built_with_elementor();
	if ( $is_el ) { the_content(); } else { cssc_about_render_all(); }
}
get_footer();
