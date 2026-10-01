<?php
/**
 * Подключение виджетов Elementor
 */
defined( 'ABSPATH' ) || exit;

add_action( 'elementor/elements/categories_registered', function ( $m ) {
	$m->add_category( 'css-cable', array( 'title' => 'CSS Cable', 'icon' => 'fa fa-plug' ) );
	$m->add_category( 'css-cable-about', array( 'title' => 'CSS Cable: О компании', 'icon' => 'fa fa-plug' ) );
} );

add_action( 'elementor/widgets/register', function ( $wm ) {
	require_once __DIR__ . '/widgets/class-base.php';
	require_once __DIR__ . '/widgets/class-hero.php';
	require_once __DIR__ . '/widgets/class-products.php';
	require_once __DIR__ . '/widgets/class-deliveries.php';
	require_once __DIR__ . '/widgets/class-news.php';
	$wm->register( new \CSSC\Widgets\Hero() );
	$wm->register( new \CSSC\Widgets\Products() );
	$wm->register( new \CSSC\Widgets\Deliveries() );
	$wm->register( new \CSSC\Widgets\News() );
	require_once __DIR__ . '/widgets/class-about.php';
	foreach ( array( 'Hero', 'Intro', 'Timeline', 'Production', 'Numbers', 'Products', 'Advantages', 'Quality', 'Projects', 'Team', 'Final' ) as $c ) {
		$cls = '\\CSSC\\Widgets\\About_' . $c;
		$wm->register( new $cls() );
	}
} );

/* Шрифт Onest в редакторе и дефолтные цвета темы в глобальной палитре */
add_action( 'elementor/editor/after_enqueue_styles', function () {
	wp_enqueue_style( 'cssc-fonts', 'https://fonts.googleapis.com/css2?family=Onest:wght@300;400;500;600;700;800;900&display=swap', array(), null );
} );

/* Инициализация слайдера в режиме предпросмотра Elementor */
add_action( 'elementor/frontend/after_enqueue_scripts', function () {
	wp_add_inline_script( 'elementor-frontend', "window.addEventListener('elementor/frontend/init',function(){if(!window.elementorFrontend)return;['cssc-deliveries','cssc-hero','cssc-news','cssc-products'].forEach(function(n){elementorFrontend.hooks.addAction('frontend/element_ready/'+n+'.default',function(){if(window.CSSC&&window.CSSC.init)window.CSSC.init();});});});" );
} );
