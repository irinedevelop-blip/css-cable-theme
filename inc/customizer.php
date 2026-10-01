<?php
defined( 'ABSPATH' ) || exit;

add_action( 'customize_register', function ( $wp ) {
	$d = cssc_defaults();
	$wp->add_panel( 'cssc', array( 'title' => 'CSS Cable: настройки темы', 'priority' => 30 ) );

	$wp->add_section( 'cssc_contacts', array( 'title' => 'Контакты', 'panel' => 'cssc' ) );
	$fields = array(
		'cssc_phone'      => 'Телефон',
		'cssc_email'      => 'E-mail',
		'cssc_whatsapp'   => 'WhatsApp (номер только цифрами)',
		'cssc_sales_addr' => 'Адрес отдела продаж',
		'cssc_prod_addr'  => 'Адрес производственной площадки',
	);
	foreach ( $fields as $k => $label ) {
		$wp->add_setting( $k, array( 'default' => $d[ $k ], 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh' ) );
		$wp->add_control( $k, array( 'label' => $label, 'section' => 'cssc_contacts', 'type' => 'text' ) );
	}

	$wp->add_section( 'cssc_footer', array( 'title' => 'Подвал и шапка', 'panel' => 'cssc' ) );
	$wp->add_setting( 'cssc_tagline', array( 'default' => $d['cssc_tagline'], 'sanitize_callback' => 'wp_kses_post' ) );
	$wp->add_control( 'cssc_tagline', array( 'label' => 'Слоган в подвале (можно &lt;br&gt;)', 'section' => 'cssc_footer', 'type' => 'textarea' ) );
	$wp->add_setting( 'cssc_credit', array( 'default' => '', 'sanitize_callback' => 'wp_kses_post' ) );
	$wp->add_control( 'cssc_credit', array( 'label' => 'Подпись разработчика (правый нижний угол)', 'section' => 'cssc_footer', 'type' => 'text' ) );
	$wp->add_setting( 'cssc_header_bg', array( 'default' => $d['cssc_header_bg'], 'sanitize_callback' => 'esc_url_raw' ) );
	$wp->add_control( new WP_Customize_Image_Control( $wp, 'cssc_header_bg', array( 'label' => 'Постер видео (фото, пока видео грузится / на внутренних страницах)', 'section' => 'cssc_footer' ) ) );
	$wp->add_setting( 'cssc_show_cart', array( 'default' => false, 'sanitize_callback' => 'wp_validate_boolean' ) );
	$wp->add_control( 'cssc_show_cart', array( 'label' => 'Показывать корзину WooCommerce в меню', 'section' => 'cssc_footer', 'type' => 'checkbox' ) );


	$wp->add_section( 'cssc_video', array( 'title' => 'Видео-шапка', 'panel' => 'cssc', 'description' => 'Фоновое видео: MP4 (H.264), без звука, 5–20 сек, до ~5 МБ. Кнопка ▶ открывает полное видео со звуком.' ) );
	$wp->add_setting( 'cssc_video', array( 'default' => $d['cssc_video'], 'sanitize_callback' => 'esc_url_raw' ) );
	$wp->add_control( 'cssc_video', array( 'label' => 'Фоновое видео (URL .mp4 из Медиабиблиотеки или внешний)', 'section' => 'cssc_video', 'type' => 'url' ) );
	$wp->add_setting( 'cssc_video_id', array( 'default' => '', 'sanitize_callback' => 'absint' ) );
	$wp->add_control( new WP_Customize_Media_Control( $wp, 'cssc_video_id', array( 'label' => '…или загрузите файл (имеет приоритет)', 'section' => 'cssc_video', 'mime_type' => 'video' ) ) );
	$wp->add_setting( 'cssc_video_full', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp->add_control( 'cssc_video_full', array( 'label' => 'Полное видео для кнопки ▶ (YouTube, Vimeo или .mp4). Пусто — фоновое видео со звуком', 'section' => 'cssc_video', 'type' => 'url' ) );
	$wp->add_setting( 'cssc_video_play', array( 'default' => true, 'sanitize_callback' => 'wp_validate_boolean' ) );
	$wp->add_control( 'cssc_video_play', array( 'label' => 'Показывать кнопку ▶', 'section' => 'cssc_video', 'type' => 'checkbox' ) );
	$wp->add_setting( 'cssc_video_scope', array( 'default' => 'front', 'sanitize_callback' => 'sanitize_key' ) );
	$wp->add_control( 'cssc_video_scope', array( 'label' => 'Где показывать видео-шапку', 'section' => 'cssc_video', 'type' => 'select', 'choices' => array( 'front' => 'Только на главной', 'all' => 'На всех страницах', 'none' => 'Нигде (только фото-постер)' ) ) );
	$wp->add_setting( 'cssc_logo_light', array( 'default' => $d['cssc_logo_light'], 'sanitize_callback' => 'esc_url_raw' ) );
	$wp->add_control( new WP_Customize_Image_Control( $wp, 'cssc_logo_light', array( 'label' => 'Белый логотип для шапки', 'section' => 'cssc_video' ) ) );
	$wp->add_setting( 'cssc_header_addr', array( 'default' => $d['cssc_header_addr'], 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp->add_control( 'cssc_header_addr', array( 'label' => 'Адрес в шапке (коротко)', 'section' => 'cssc_video', 'type' => 'text' ) );

	$wp->add_section( 'cssc_motion_sec', array( 'title' => 'Анимация', 'panel' => 'cssc' ) );
	$mo = array(
		'cssc_motion' => 'Премиальная анимация (появление блоков, интро, параллакс)',
		'cssc_smooth' => 'Плавный инерционный скролл',
		'cssc_sticky' => 'Липкое меню при прокрутке',
		'cssc_cursor' => 'Фирменный курсор-кольцо (только мышь)',
	);
	foreach ( $mo as $k => $label ) {
		$wp->add_setting( $k, array( 'default' => true, 'sanitize_callback' => 'wp_validate_boolean' ) );
		$wp->add_control( $k, array( 'label' => $label, 'section' => 'cssc_motion_sec', 'type' => 'checkbox' ) );
	}

	$wp->add_section( 'cssc_colors', array( 'title' => 'Цвета', 'panel' => 'cssc' ) );
	$colors = array(
		'cssc_accent'    => 'Акцентный (красный)',
		'cssc_dark'      => 'Тёмный фон',
		'cssc_navy'      => 'Тёмно-синий (меню, градиент)',
		'cssc_footer_bg' => 'Фон подвала',
	);
	foreach ( $colors as $k => $label ) {
		$wp->add_setting( $k, array( 'default' => $d[ $k ], 'sanitize_callback' => 'sanitize_hex_color' ) );
		$wp->add_control( new WP_Customize_Color_Control( $wp, $k, array( 'label' => $label, 'section' => 'cssc_colors' ) ) );
	}
} );
