<?php
/**
 * Генератор главной страницы для Elementor.
 * Внешний вид → «Генератор главной»
 *  - создаёт (или пересоздаёт) страницу «Главная», собранную из виджетов CSS Cable, и делает её главной;
 *  - загружает изображения макета в Медиабиблиотеку (чтобы их можно было заменять в Elementor);
 *  - по желанию: категории/демо-товары WooCommerce, демо-новости, меню, страницы разделов;
 *  - экспорт шаблона Elementor в JSON (Шаблоны → Импорт).
 */
defined( 'ABSPATH' ) || exit;

class CSSC_Generator {

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'menu' ) );
		add_action( 'admin_post_cssc_generate', array( __CLASS__, 'handle' ) );
		add_action( 'admin_post_cssc_export', array( __CLASS__, 'export' ) );
		add_action( 'admin_post_cssc_generate_about', array( __CLASS__, 'handle_about' ) );
		add_action( 'admin_post_cssc_export_about', array( __CLASS__, 'export_about' ) );
		add_action( 'after_switch_theme', function () { set_transient( 'cssc_welcome', 1, 60 ); } );
		add_action( 'admin_notices', array( __CLASS__, 'notice' ) );
	}

	public static function menu() {
		add_theme_page( 'Генератор главной', 'Генератор главной', 'manage_options', 'cssc-generator', array( __CLASS__, 'page' ) );
	}

	public static function notice() {
		if ( get_transient( 'cssc_welcome' ) ) {
			delete_transient( 'cssc_welcome' );
			echo '<div class="notice notice-success is-dismissible"><p><b>CSS Cable активирована.</b> Соберите главную страницу для Elementor: <a href="' . esc_url( admin_url( 'themes.php?page=cssc-generator' ) ) . '">Внешний вид → Генератор главной</a>.</p></div>';
		}
	}

	private static function id() { return substr( md5( uniqid( '', true ) . wp_rand() ), 0, 7 ); }

	/* ---------- Медиа ---------- */
	private static function import_image( $file ) {
		$existing = get_posts( array( 'post_type' => 'attachment', 'meta_key' => '_cssc_src', 'meta_value' => $file, 'numberposts' => 1, 'fields' => 'ids' ) ); // phpcs:ignore
		if ( $existing ) {
			return array( 'id' => (int) $existing[0], 'url' => wp_get_attachment_url( $existing[0] ) );
		}
		$src = CSSC_DIR . '/assets/img/' . $file;
		if ( ! file_exists( $src ) ) {
			return array( 'id' => '', 'url' => cssc_img( $file ) );
		}
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$tmp = wp_tempnam( $file );
		copy( $src, $tmp );
		$id = media_handle_sideload( array( 'name' => $file, 'tmp_name' => $tmp ), 0 );
		if ( is_wp_error( $id ) ) {
			@unlink( $tmp ); // phpcs:ignore
			return array( 'id' => '', 'url' => cssc_img( $file ) );
		}
		update_post_meta( $id, '_cssc_src', $file );
		return array( 'id' => $id, 'url' => wp_get_attachment_url( $id ) );
	}

	/** Загрузка не-изображения (видео) в Медиабиблиотеку, возвращает URL */
	private static function import_file( $file ) {
		$r = self::import_image( $file );
		return $r['url'];
	}

	private static function img( $file, $import ) {
		return $import ? self::import_image( $file ) : array( 'id' => '', 'url' => cssc_img( $file ) );
	}

	/* ---------- Структура Elementor ---------- */
	private static function section( $widget, $settings, $sec = array() ) {
		return array(
			'id'       => self::id(),
			'elType'   => 'section',
			'settings' => array_merge( array(
				'layout'        => 'full_width',
				'gap'           => 'no',
				'stretch_section' => 'section-stretched',
				'padding'       => array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true ),
			), $sec ),
			'elements' => array(
				array(
					'id'       => self::id(),
					'elType'   => 'column',
					'settings' => array( '_column_size' => 100, 'padding' => array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true ) ),
					'elements' => array(
						array( 'id' => self::id(), 'elType' => 'widget', 'widgetType' => $widget, 'settings' => $settings, 'elements' => array() ),
					),
					'isInner'  => false,
				),
			),
			'isInner'  => false,
		);
	}

	public static function build_data( $import_media = true, $cat_ids = array() ) {
		$h = cssc_section_defaults( 'hero' );
		$p = cssc_section_defaults( 'products' );
		$d = cssc_section_defaults( 'deliveries' );
		$n = cssc_section_defaults( 'news' );

		$buttons = array();
		foreach ( $h['buttons'] as $b ) {
			$url = $b['url'];
			if ( '/shop/' === $url ) { $url = cssc_shop_url(); }
			elseif ( 0 === strpos( $url, '/' ) ) { $url = home_url( $url ); }
			$buttons[] = array( '_id' => self::id(), 'text' => $b['text'], 'url' => array( 'url' => $url, 'is_external' => '', 'nofollow' => '' ) );
		}

		$pimgs = array( 'cable-flat.png', 'cable-power.png', 'cable-control-poster.png' );
		$pitems = array();
		foreach ( $p['items'] as $i => $it ) {
			$slug = array( 'ploskij-kabel', 'silovoj-kabel', 'kontrolnyj-kabel' )[ $i ];
			$pitems[] = array(
				'_id'   => self::id(),
				'title' => $it['title'],
				'image' => self::img( $pimgs[ $i ], $import_media ),
				'cat'   => isset( $cat_ids[ $slug ] ) ? (string) $cat_ids[ $slug ] : '',
				'url'   => array( 'url' => $it['url'] ),
				'video' => $it['video'] ? ( $import_media ? self::import_file( basename( $it['video'] ) ) : $it['video'] ) : '',
				'marks' => $it['marks'],
				'text'  => $it['text'],
			);
		}

		$simgs = array( 'delivery-1.jpg', 'delivery-2.jpg', 'delivery-3.jpg' );
		$slides = array();
		foreach ( $d['slides'] as $i => $sl ) {
			$slides[] = array( '_id' => self::id(), 'image' => self::img( $simgs[ $i ], $import_media ), 'tags' => $sl['tags'], 'category' => $sl['category'], 'title' => $sl['title'], 'url' => array( 'url' => $sl['url'] ) );
		}

		return array(
			self::section( 'cssc-hero', array( 'title' => $h['title'], 'subtitle' => $h['subtitle'], 'buttons' => $buttons, 'badge' => self::img( 'badge-ondiris.png', $import_media ), 'badge_text' => $h['badge_text'] ) ),
			self::section( 'cssc-products', array( 'label' => $p['label'], 'items' => $pitems, 'gost_text' => $p['gost_text'], 'warranty' => $p['warranty'], 'warr_text' => $p['warr_text'], 'more_text' => $p['more_text'] ) ),
			self::section( 'cssc-deliveries', array( 'title' => $d['title'], 'text' => $d['text'], 'counter' => $d['counter'], 'counter_suffix' => $d['counter_suffix'], 'counter_label' => $d['counter_label'], 'slides' => $slides, 'autoplay' => 0 ) ),
			self::section( 'cssc-news', array( 'title' => $n['title'], 'text' => $n['text'], 'count' => 3, 'category' => '' ) ),
		);
	}

	/* ---------- Демо-контент ---------- */
	private static function ensure_page( $title, $slug, $content = '' ) {
		$pg = get_page_by_path( $slug );
		if ( $pg ) { return $pg->ID; }
		return wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => $title, 'post_name' => $slug, 'post_content' => $content ) );
	}

	private static function woo_demo( $with_products ) {
		$ids = array();
		if ( ! taxonomy_exists( 'product_cat' ) ) { return $ids; }
		$cats = array( 'ploskij-kabel' => 'Плоский кабель', 'silovoj-kabel' => 'Силовой кабель', 'kontrolnyj-kabel' => 'Контрольный кабель' );
		$imgs = array( 'ploskij-kabel' => 'cable-flat.png', 'silovoj-kabel' => 'cable-power.png', 'kontrolnyj-kabel' => 'cable-control.png' );
		foreach ( $cats as $slug => $name ) {
			$t = get_term_by( 'slug', $slug, 'product_cat' );
			if ( ! $t ) {
				$r = wp_insert_term( $name, 'product_cat', array( 'slug' => $slug ) );
				$tid = is_wp_error( $r ) ? 0 : $r['term_id'];
			} else {
				$tid = $t->term_id;
			}
			if ( $tid ) {
				$ids[ $slug ] = $tid;
				if ( ! get_term_meta( $tid, 'thumbnail_id', true ) ) {
					$im = self::import_image( $imgs[ $slug ] );
					if ( $im['id'] ) { update_term_meta( $tid, 'thumbnail_id', $im['id'] ); }
				}
			}
		}
		if ( $with_products && class_exists( 'WC_Product_Simple' ) ) {
			$demo = array(
				array( 'ППВ 3х1,5', 'ploskij-kabel', 62 ),
				array( 'ВВГ-Пнг(А)-LS 3х2,5', 'ploskij-kabel', 128 ),
				array( 'ВВГнг-FRLS 5х10', 'silovoj-kabel', 690 ),
				array( 'ВВГнг(А)-LS 4х16', 'silovoj-kabel', 820 ),
				array( 'КВВГнг-LSLTx 7х1,5', 'kontrolnyj-kabel', 225 ),
				array( 'КВВГЭнг(А)-LS 10х1,0', 'kontrolnyj-kabel', 260 ),
			);
			foreach ( $demo as $row ) {
				if ( get_page_by_title( $row[0], OBJECT, 'product' ) ) { continue; } // phpcs:ignore
				$pr = new WC_Product_Simple();
				$pr->set_name( $row[0] );
				$pr->set_status( 'publish' );
				$pr->set_catalog_visibility( 'visible' );
				$pr->set_short_description( 'Кабель произведён в соответствии с ГОСТ. Наличие на складе в г. Караганда. (демо-товар — замените описание)' );
				$pr->set_category_ids( array( $ids[ $row[1] ] ) );
				$im = self::import_image( $imgs[ $row[1] ] );
				if ( $im['id'] ) { $pr->set_image_id( $im['id'] ); }
				$pid = $pr->save();
				update_post_meta( $pid, '_cssc_weight_km', $row[2] ); // демо-значение
			}
		}
		return $ids;
	}

	private static function news_demo() {
		if ( get_posts( array( 'post_type' => 'post', 'meta_key' => '_cssc_demo', 'numberposts' => 1 ) ) ) { return; } // phpcs:ignore
		$im = self::import_image( 'news-1.jpg' );
		for ( $i = 0; $i < 3; $i++ ) {
			$id = wp_insert_post( array(
				'post_type'    => 'post',
				'post_status'  => 'publish',
				'post_title'   => 'Первый совместный тимбилдинг: начало хорошей традиции',
				'post_excerpt' => 'Впервые собрались вместе вне рабочих задач — чтобы пообщаться, узнать друг друга ближе, посмеяться и просто классно провести время...',
				'post_content' => '<p>Впервые собрались вместе вне рабочих задач — чтобы пообщаться, узнать друг друга ближе, посмеяться и просто классно провести время.</p><p>(Демо-запись — замените текст.)</p>',
				'post_date'    => '2026-07-24 10:0' . $i . ':00',
			) );
			if ( $id && $im['id'] ) { set_post_thumbnail( $id, $im['id'] ); }
			update_post_meta( $id, '_cssc_demo', 1 );
		}
	}

	private static function menus( $pages ) {
		$make = function ( $name, $loc, $items ) {
			$m = wp_get_nav_menu_object( $name );
			$mid = $m ? $m->term_id : wp_create_nav_menu( $name );
			if ( is_wp_error( $mid ) ) { return; }
			if ( ! $m ) {
				foreach ( $items as $t => $u ) {
					wp_update_nav_menu_item( $mid, 0, array( 'menu-item-title' => $t, 'menu-item-url' => $u, 'menu-item-status' => 'publish', 'menu-item-type' => 'custom' ) );
				}
			}
			$locs = get_theme_mod( 'nav_menu_locations', array() );
			$locs[ $loc ] = $mid;
			set_theme_mod( 'nav_menu_locations', $locs );
		};
		$make( 'Главное меню', 'primary', array(
			'Продукция'  => cssc_shop_url(),
			'О компании' => get_permalink( $pages['about'] ),
			'Новости'    => get_permalink( $pages['news'] ),
			'Контакты'   => get_permalink( $pages['contacts'] ),
		) );
		$make( 'Подвал: Продукция', 'footer_products', array(
			'Силовой кабель'     => cssc_cat_url( 'silovoj-kabel' ),
			'Контрольный кабель' => cssc_cat_url( 'kontrolnyj-kabel' ),
		) );
		$make( 'Подвал: Компания', 'footer_company', array(
			'О компании' => get_permalink( $pages['about'] ),
			'Новости'    => get_permalink( $pages['news'] ),
			'Контакты'   => get_permalink( $pages['contacts'] ),
		) );
	}

	/* ---------- Обработчик ---------- */
	public static function handle() {
		if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'Нет прав' ); }
		check_admin_referer( 'cssc_generate' );
		$o = function ( $k ) { return ! empty( $_POST[ $k ] ); }; // phpcs:ignore

		$cat_ids = array();
		if ( $o( 'woo' ) && class_exists( 'WooCommerce' ) ) {
			$cat_ids = self::woo_demo( $o( 'products' ) );
		}
		$pages = array(
			'about'    => self::ensure_page( 'О компании', 'o-kompanii', '<p>Текст о компании.</p>' ),
			'contacts' => self::ensure_page( 'Контакты', 'kontakty', '<p>Телефон: ' . esc_html( cssc_opt( 'cssc_phone' ) ) . '<br>E-mail: ' . esc_html( cssc_opt( 'cssc_email' ) ) . '</p><p>Отдел продаж: ' . esc_html( cssc_opt( 'cssc_sales_addr' ) ) . '</p><p>Производственная площадка: ' . esc_html( cssc_opt( 'cssc_prod_addr' ) ) . '</p><p>[cssc_request_button]</p>' ),
			'news'     => self::ensure_page( 'Новости', 'novosti' ),
			'calc'     => self::ensure_page( 'Калькулятор веса', 'kalkulyator-vesa', '[cssc_weight_calc]' ),
			'certs'    => self::ensure_page( 'Сертификаты', 'sertifikaty', '<p>Добавьте сертификаты (галерея или PDF).</p>' ),
		);
		if ( $o( 'news' ) ) { self::news_demo(); }

		$data = self::build_data( $o( 'media' ), $cat_ids );

		$front = (int) get_option( 'page_on_front' );
		if ( ! $front || $o( 'new' ) || 'page' !== get_option( 'show_on_front' ) ) {
			$front = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'Главная' ) );
		}
		update_post_meta( $front, '_elementor_edit_mode', 'builder' );
		update_post_meta( $front, '_elementor_template_type', 'wp-page' );
		update_post_meta( $front, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '3.20.0' );
		update_post_meta( $front, '_wp_page_template', 'templates/elementor-full.php' );
		update_post_meta( $front, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
		update_post_meta( $front, '_elementor_page_settings', array( 'hide_title' => 'yes' ) );
		delete_post_meta( $front, '_elementor_css' );

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front );
		update_option( 'page_for_posts', $pages['news'] );

		if ( $o( 'menus' ) ) { self::menus( $pages ); }

		if ( did_action( 'elementor/loaded' ) ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}
		wp_safe_redirect( admin_url( 'themes.php?page=cssc-generator&done=' . $front ) );
		exit;
	}

	/* ---------- О КОМПАНИИ ---------- */
	public static function build_about( $import_media = true ) {
		$files = array(); // url => filename
		foreach ( glob( CSSC_DIR . '/assets/img/about/*.jpg' ) as $f ) { $files[ cssc_about_img( basename( $f ) ) ] = 'about/' . basename( $f ); }
		$data = array();
		foreach ( cssc_about_schema() as $slug => $sec ) {
			$set = array();
			foreach ( $sec['fields'] as $k => $f ) {
				if ( 'media' === $f[0] ) {
					$set[ $k ] = ( $import_media && isset( $files[ $f[2] ] ) ) ? self::import_image( $files[ $f[2] ] ) : array( 'id' => '', 'url' => $f[2] );
				} elseif ( 'repeater' === $f[0] ) {
					$set[ $k ] = array_map( function ( $it ) { $it['_id'] = CSSC_Generator::rid(); return $it; }, $f[2] );
				} else {
					$set[ $k ] = $f[2];
				}
			}
			$data[] = self::section( 'cssc-about-' . $slug, $set );
		}
		return $data;
	}
	public static function rid() { return self::id(); }

	public static function handle_about() {
		if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'Нет прав' ); }
		check_admin_referer( 'cssc_generate_about' );
		$media = ! empty( $_POST['media'] ); // phpcs:ignore
		$pg = get_page_by_path( 'o-kompanii' );
		if ( $pg && empty( $_POST['new'] ) ) { // phpcs:ignore
			$id = $pg->ID;
		} else {
			$id = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'О компании', 'post_name' => $pg ? 'o-kompanii-' . gmdate( 'His' ) : 'o-kompanii' ) );
		}
		wp_update_post( array( 'ID' => $id, 'post_content' => '' ) );
		update_post_meta( $id, '_elementor_edit_mode', 'builder' );
		update_post_meta( $id, '_elementor_template_type', 'wp-page' );
		update_post_meta( $id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '3.20.0' );
		update_post_meta( $id, '_wp_page_template', 'templates/elementor-full.php' );
		update_post_meta( $id, '_elementor_data', wp_slash( wp_json_encode( self::build_about( $media ) ) ) );
		update_post_meta( $id, '_elementor_page_settings', array( 'hide_title' => 'yes' ) );
		delete_post_meta( $id, '_elementor_css' );
		if ( did_action( 'elementor/loaded' ) ) { \Elementor\Plugin::$instance->files_manager->clear_cache(); }
		wp_safe_redirect( admin_url( 'themes.php?page=cssc-generator&done_about=' . $id ) );
		exit;
	}

	public static function export_about() {
		if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'Нет прав' ); }
		check_admin_referer( 'cssc_export_about' );
		nocache_headers();
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=css-cable-about-elementor.json' );
		echo wp_json_encode( array( 'version' => '0.4', 'title' => 'CSS Cable — О компании', 'type' => 'page', 'content' => self::build_about( false ), 'page_settings' => array( 'hide_title' => 'yes', 'template' => 'templates/elementor-full.php' ) ), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ); // phpcs:ignore
		exit;
	}

	public static function export() {
		if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'Нет прав' ); }
		check_admin_referer( 'cssc_export' );
		$json = array(
			'version'  => '0.4',
			'title'    => 'CSS Cable — Главная',
			'type'     => 'page',
			'content'  => self::build_data( false ),
			'page_settings' => array( 'hide_title' => 'yes', 'template' => 'templates/elementor-full.php' ),
		);
		nocache_headers();
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=css-cable-home-elementor.json' );
		echo wp_json_encode( $json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ); // phpcs:ignore
		exit;
	}

	/* ---------- Страница в админке ---------- */
	public static function page() {
		$el  = did_action( 'elementor/loaded' );
		$woo = class_exists( 'WooCommerce' );
		?>
		<div class="wrap">
			<h1>Генератор главной страницы — CSS Cable</h1>
			<?php if ( isset( $_GET['done'] ) ) : $pid = (int) $_GET['done']; // phpcs:ignore ?>
				<div class="notice notice-success"><p><b>Готово!</b> Главная создана.
					<?php if ( $el ) : ?><a class="button button-primary" href="<?php echo esc_url( admin_url( 'post.php?post=' . $pid . '&action=elementor' ) ); ?>">Редактировать в Elementor</a><?php endif; ?>
					<a class="button" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank">Открыть сайт</a></p></div>
			<?php endif; ?>
			<p>Статус: Elementor — <b style="color:<?php echo $el ? 'green' : '#c04852'; ?>"><?php echo $el ? 'активен' : 'не установлен'; ?></b>,
				WooCommerce — <b style="color:<?php echo $woo ? 'green' : '#c04852'; ?>"><?php echo $woo ? 'активен' : 'не установлен'; ?></b>.</p>
			<?php if ( ! $el ) : ?><p>Без Elementor главная всё равно выводится (из шаблона темы), но для визуального редактирования установите бесплатный плагин Elementor.</p><?php endif; ?>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="background:#fff;padding:20px 24px;border:1px solid #dcdcde;max-width:720px">
				<input type="hidden" name="action" value="cssc_generate">
				<?php wp_nonce_field( 'cssc_generate' ); ?>
				<p><label><input type="checkbox" name="media" value="1" checked> Загрузить изображения макета в Медиабиблиотеку</label></p>
				<p><label><input type="checkbox" name="woo" value="1" <?php checked( $woo ); disabled( ! $woo ); ?>> Создать категории WooCommerce (Плоский / Силовой / Контрольный кабель)</label></p>
				<p><label><input type="checkbox" name="products" value="1" <?php disabled( ! $woo ); ?>> Добавить 6 демо-товаров</label></p>
				<p><label><input type="checkbox" name="news" value="1" checked> Добавить 3 демо-новости</label></p>
				<p><label><input type="checkbox" name="menus" value="1" checked> Создать меню (шапка и подвал)</label></p>
				<p><label><input type="checkbox" name="new" value="1"> Создать новую страницу, а не перезаписывать текущую главную</label></p>
				<p><button class="button button-primary button-hero">Сгенерировать главную</button></p>
				<p class="description">Внимание: без последней галочки содержимое текущей главной страницы в Elementor будет заменено.</p>
			</form>

			<h2 style="margin-top:36px">Страница «О компании»</h2>
			<?php if ( isset( $_GET['done_about'] ) ) : $aid = (int) $_GET['done_about']; // phpcs:ignore ?>
				<div class="notice notice-success inline"><p><b>Страница «О компании» создана.</b>
					<?php if ( $el ) : ?><a class="button button-primary" href="<?php echo esc_url( admin_url( 'post.php?post=' . $aid . '&action=elementor' ) ); ?>">Редактировать в Elementor</a><?php endif; ?>
					<a class="button" href="<?php echo esc_url( get_permalink( $aid ) ); ?>" target="_blank">Открыть</a></p></div>
			<?php endif; ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="background:#fff;padding:20px 24px;border:1px solid #dcdcde;max-width:720px">
				<input type="hidden" name="action" value="cssc_generate_about">
				<?php wp_nonce_field( 'cssc_generate_about' ); ?>
				<p>11 секций: первый экран, о предприятии, история, производство с видео, цифры, продукция, преимущества, качество, объекты, команда, заключение.</p>
				<p><label><input type="checkbox" name="media" value="1" checked> Загрузить фотографии в Медиабиблиотеку</label></p>
				<p><label><input type="checkbox" name="new" value="1"> Создать новую страницу, а не перезаписывать существующую «О компании» (/o-kompanii/)</label></p>
				<p><button class="button button-primary button-hero">Сгенерировать «О компании»</button></p>
			</form>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-top:10px">
				<input type="hidden" name="action" value="cssc_export_about">
				<?php wp_nonce_field( 'cssc_export_about' ); ?>
				<button class="button">Скачать JSON-шаблон «О компании»</button>
			</form>

			<h2 style="margin-top:30px">Шаблон главной для библиотеки Elementor</h2>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="cssc_export">
				<?php wp_nonce_field( 'cssc_export' ); ?>
				<button class="button">Скачать JSON-шаблон</button>
				<span class="description">Импорт: Шаблоны → Сохранённые шаблоны → Импорт.</span>
			</form>

			<h2 style="margin-top:30px">Где что править</h2>
			<ul style="list-style:disc;padding-left:20px">
				<li>Тексты, кнопки, картинки, слайды — в Elementor, виджеты категории «CSS Cable».</li>
				<li>Телефон, e-mail, WhatsApp, адреса, цвета, фон шапки — Внешний вид → Настроить → «CSS Cable: настройки темы».</li>
				<li>Логотип — Внешний вид → Настроить → Свойства сайта.</li>
				<li>Меню — Внешний вид → Меню.</li>
				<li>Вес 1 км для калькулятора — в карточке товара WooCommerce (вкладка «Основные»).</li>
			</ul>
		</div>
		<?php
	}
}
CSSC_Generator::init();

/* Шорткод кнопки заявки */
add_shortcode( 'cssc_request_button', function ( $a ) {
	$a = shortcode_atts( array( 'text' => 'Оставить заявку' ), $a );
	return '<a href="#zayavka" class="btn btn--accent btn--lg"><span>' . esc_html( $a['text'] ) . '</span>' . cssc_arrow_btn_icon() . '</a>';
} );
