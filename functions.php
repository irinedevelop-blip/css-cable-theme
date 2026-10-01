<?php
/**
 * CSS Cable — functions
 */
defined( 'ABSPATH' ) || exit;

define( 'CSSC_VER', '1.4.0' );
define( 'CSSC_DIR', get_template_directory() );
define( 'CSSC_URI', get_template_directory_uri() );

require CSSC_DIR . '/inc/helpers.php';
require CSSC_DIR . '/inc/customizer.php';
require CSSC_DIR . '/inc/sections.php';
require CSSC_DIR . '/inc/about.php';
require CSSC_DIR . '/inc/woocommerce.php';
require CSSC_DIR . '/inc/elementor/loader.php';
require CSSC_DIR . '/inc/generator.php';

add_action( 'after_setup_theme', function () {
	load_theme_textdomain( 'css-cable', CSSC_DIR . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 160, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 600,
		'single_image_width'    => 900,
		'product_grid'          => array( 'default_columns' => 3, 'default_rows' => 4 ),
	) );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	add_image_size( 'cssc-news', 740, 780, true );
	add_image_size( 'cssc-slide', 1100, 660, true );

	register_nav_menus( array(
		'primary'        => __( 'Главное меню', 'css-cable' ),
		'footer_products' => __( 'Подвал: Продукция', 'css-cable' ),
		'footer_company' => __( 'Подвал: Компания', 'css-cable' ),
	) );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'cssc-fonts', 'https://fonts.googleapis.com/css2?family=Onest:wght@300;400;500;600;700;800;900&display=swap', array(), null );
	wp_enqueue_style( 'cssc-main', CSSC_URI . '/assets/css/main.css', array(), CSSC_VER );
	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_style( 'cssc-woo', CSSC_URI . '/assets/css/woocommerce.css', array( 'cssc-main' ), CSSC_VER );
	}
	wp_enqueue_style( 'cssc-about', CSSC_URI . '/assets/css/about.css', array( 'cssc-main' ), CSSC_VER );
	wp_add_inline_style( 'cssc-main', cssc_css_vars() );
	wp_enqueue_script( 'cssc-main', CSSC_URI . '/assets/js/main.js', array(), CSSC_VER, true );

	/* Премиальная анимация */
	if ( get_theme_mod( 'cssc_motion', true ) ) {
		wp_enqueue_style( 'cssc-anim', CSSC_URI . '/assets/css/anim.css', array( 'cssc-main' ), CSSC_VER );
		$deps = array( 'cssc-main' );
		if ( get_theme_mod( 'cssc_smooth', true ) ) {
			wp_enqueue_script( 'lenis', CSSC_URI . '/assets/js/vendor/lenis.min.js', array(), '1.3.11', true );
			$deps[] = 'lenis';
		}
		wp_enqueue_script( 'cssc-motion', CSSC_URI . '/assets/js/motion.js', $deps, CSSC_VER, true );
		wp_add_inline_script( 'cssc-motion', 'window.CSSC_MOTION=' . wp_json_encode( array(
			'smooth' => (bool) get_theme_mod( 'cssc_smooth', true ),
			'cursor' => (bool) get_theme_mod( 'cssc_cursor', true ),
			'sticky' => (bool) get_theme_mod( 'cssc_sticky', true ),
		) ) . ';', 'before' );
	}
} );

add_action( 'widgets_init', function () {
	register_sidebar( array(
		'name'          => __( 'Боковая колонка', 'css-cable' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => __( 'Магазин: фильтры', 'css-cable' ),
		'id'            => 'shop-sidebar',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
} );

/* Класс анимации ставим максимально рано, чтобы не было «мигания» контента */
add_action( 'wp_head', function () {
	if ( ! get_theme_mod( 'cssc_motion', true ) || ( isset( $_GET['elementor-preview'] ) ) ) { return; } // phpcs:ignore
	echo "<script>(function(d){if(!window.matchMedia||!matchMedia('(prefers-reduced-motion: reduce)').matches){d.documentElement.classList.add('cssc-motion');setTimeout(function(){d.body&&d.body.classList.add('is-loaded')},4000);}})(document);</script>\n";
}, 1 );

add_filter( 'excerpt_length', function () { return 22; } );
add_filter( 'excerpt_more', function () { return '...'; } );

/* Класс body для страниц Elementor на всю ширину */
add_filter( 'body_class', function ( $c ) {
	if ( is_page_template( 'templates/elementor-full.php' ) || is_front_page() ) {
		$c[] = 'cssc-full';
	}
	return $c;
} );
