<?php
defined( 'ABSPATH' ) || exit;

function cssc_opt( $key, $default = '' ) {
	$d = cssc_defaults();
	return get_theme_mod( $key, isset( $d[ $key ] ) ? $d[ $key ] : $default );
}

function cssc_defaults() {
	return array(
		'cssc_phone'       => '+7 700 421 01 05',
		'cssc_email'       => 'sale@css-cable.kz',
		'cssc_whatsapp'    => '77004210105',
		'cssc_sales_addr'  => 'г. Караганда, Ул. Жанторе Абишева, участок 1/1',
		'cssc_prod_addr'   => 'г. Караганда, 152-й учетный квартал, ст870',
		'cssc_tagline'     => 'НАДЕЖНЫЙ ПРОИЗВОДИТЕЛЬ<br>КАБЕЛЬНОЙ ПРОДУКЦИИ',
		'cssc_credit'      => '',
		'cssc_accent'      => '#c04852',
		'cssc_dark'        => '#0e192b',
		'cssc_navy'        => '#0d203f',
		'cssc_footer_bg'   => '#19283f',
		'cssc_show_cart'   => false,
		'cssc_header_bg'   => CSSC_URI . '/assets/img/hero-bg.jpg',
		'cssc_video'       => CSSC_URI . '/assets/img/hero-video.mp4',
		'cssc_video_full'  => '',
		'cssc_video_scope' => 'front',
		'cssc_video_play'  => true,
		'cssc_header_addr' => 'Ул. Жанторе Абишева, участок 1/1',
		'cssc_logo_light'  => CSSC_URI . '/assets/img/logo-white.png',
	);
}

function cssc_img( $file ) {
	return CSSC_URI . '/assets/img/' . $file;
}

function cssc_css_vars() {
	return sprintf(
		':root{--c-accent:%s;--c-dark:%s;--c-navy:%s;--c-footer:%s;}',
		esc_attr( cssc_opt( 'cssc_accent' ) ),
		esc_attr( cssc_opt( 'cssc_dark' ) ),
		esc_attr( cssc_opt( 'cssc_navy' ) ),
		esc_attr( cssc_opt( 'cssc_footer_bg' ) )
	);
}

function cssc_icon( $name ) {
	$i = array(
		'arrow-up-right' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M8 7h9v9"/></svg>',
		'arrow-right'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>',
		'arrow-left'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>',
		'search'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><circle cx="11" cy="11" r="6.5"/><path d="m20 20-4.2-4.2"/></svg>',
		'phone'          => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"/><path d="M15 3h6v6M21 3l-6 6"/></svg>',
		'mail'           => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"><path d="M12 3 3 21l9-4 9 4z"/><path d="M12 10v4"/></svg>',
		'pin'            => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s7-6.1 7-12a7 7 0 1 0-14 0c0 5.9 7 12 7 12z"/><circle cx="12" cy="10" r="2.5"/></svg>',
		'factory'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 20s5-4.4 5-8.6a5 5 0 1 0-10 0C4 15.6 9 20 9 20z"/><circle cx="9" cy="11.5" r="1.8"/><circle cx="17.5" cy="6.5" r="3"/><path d="M17.5 2.5v1M17.5 9.5v1M21.5 6.5h-1M14.5 6.5h-1"/></svg>',
		'whatsapp'       => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.3-.4.2-.4.8-1.4.1-.2 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.1 5.1 0 0 0 1.1 2.7 11.7 11.7 0 0 0 4.5 4c1.7.7 2.3.8 3.2.6a2.7 2.7 0 0 0 1.8-1.2 2.2 2.2 0 0 0 .1-1.2c0-.1-.2-.2-.4-.3z"/></svg>',
		'cart'           => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="20" r="1.3"/><circle cx="18" cy="20" r="1.3"/><path d="M2 3h3l2.7 12.4a1.5 1.5 0 0 0 1.5 1.1h8.9a1.5 1.5 0 0 0 1.5-1.2L21 8H6"/></svg>',
		'play'           => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M6 3.5v17l15-8.5z"/></svg>',
		'burger'         => '<svg viewBox="0 0 44 32" fill="currentColor"><rect x="0" y="0" width="12" height="3.2"/><rect x="0" y="14.4" width="44" height="3.2"/><rect x="30" y="28.8" width="14" height="3.2"/></svg>',
		'menu'           => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 6h18M3 12h18M3 18h18"/></svg>',
		'close'          => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg>',
	);
	return isset( $i[ $name ] ) ? '<span class="ico ico-' . esc_attr( $name ) . '" aria-hidden="true">' . $i[ $name ] . '</span>' : '';
}

function cssc_logo() {
	if ( has_custom_logo() ) {
		return get_custom_logo();
	}
	return '<a class="custom-logo-link" href="' . esc_url( home_url( '/' ) ) . '"><img class="custom-logo" src="' . esc_url( cssc_img( 'logo.png' ) ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '"></a>';
}

/** Фолбэк меню, если меню не назначено */
function cssc_menu_fallback() {
	$shop = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
	$items = array(
		'Продукция'  => $shop,
		'О компании' => home_url( '/o-kompanii/' ),
		'Новости'    => home_url( '/novosti/' ),
		'Контакты'   => home_url( '/kontakty/' ),
	);
	echo '<ul class="menu">';
	foreach ( $items as $t => $u ) {
		echo '<li><a href="' . esc_url( $u ) . '">' . esc_html( $t ) . '</a></li>';
	}
	echo '</ul>';
}

function cssc_ru_month( $ts ) {
	$m = array( 1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря' );
	return $m[ (int) date( 'n', $ts ) ] . ' ' . date( 'Y', $ts );
}

/** Нужно ли выводить видео-шапку на текущей странице */
function cssc_has_video_header() {
	$scope = cssc_opt( 'cssc_video_scope' );
	if ( 'none' === $scope ) { return false; }
	if ( 'all' === $scope ) { return true; }
	return is_front_page();
}

/** Ссылка на полное видео -> URL для встраивания */
function cssc_video_embed( $url ) {
	if ( preg_match( '~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/))([\w-]{11})~', $url, $m ) ) {
		return array( 'type' => 'iframe', 'src' => 'https://www.youtube.com/embed/' . $m[1] . '?autoplay=1&rel=0' );
	}
	if ( preg_match( '~vimeo\.com/(\d+)~', $url, $m ) ) {
		return array( 'type' => 'iframe', 'src' => 'https://player.vimeo.com/video/' . $m[1] . '?autoplay=1' );
	}
	return array( 'type' => 'video', 'src' => $url );
}
