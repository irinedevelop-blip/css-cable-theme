<?php
/**
 * Template Name: Elementor — на всю ширину (с шапкой и подвалом)
 * Template Post Type: page, post
 */
defined( 'ABSPATH' ) || exit;
get_header();
while ( have_posts() ) { the_post(); the_content(); }
get_footer();
