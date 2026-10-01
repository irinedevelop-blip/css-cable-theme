<?php
/**
 * Рендер секций главной. Используются и виджетами Elementor, и шаблоном front-page.php (без Elementor).
 */
defined( 'ABSPATH' ) || exit;

function cssc_shop_url() {
	return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
}

function cssc_cat_url( $slug ) {
	if ( taxonomy_exists( 'product_cat' ) ) {
		$t = get_term_by( 'slug', $slug, 'product_cat' );
		if ( $t && ! is_wp_error( $t ) ) {
			return get_term_link( $t );
		}
	}
	return cssc_shop_url();
}

/* ---------- Значения по умолчанию (как в макете) ---------- */
function cssc_section_defaults( $section ) {
	$d = array(
		'hero' => array(
			'title'      => 'Производство товара в строгом соответствии с ГОСТ',
			'subtitle'   => 'наличие товара на складе в г. Караганда',
			'buttons'    => array(
				array( 'text' => 'Оставить заявку', 'url' => '#zayavka' ),
				array( 'text' => 'Калькулятор веса', 'url' => '/kalkulyator-vesa/' ),
				array( 'text' => 'Наличие на складе', 'url' => '/shop/' ),
				array( 'text' => 'Сертификаты', 'url' => '/sertifikaty/' ),
			),
			'badge'      => cssc_img( 'badge-ondiris.png' ),
			'badge_text' => 'Подтвержденный статус отечественного производителя в Реестре казахстанских товаропроизводителей (Реестр КТП)',
		),
		'products' => array(
			'label'      => 'Продукция',
			'gost_text'  => 'Произведено<br>в соотвествии<br><b>с ГОСТ</b>',
			'warranty'   => '5',
			'warr_text'  => 'лет гарантии<br><b>на всю<br>продукцию</b>',
			'more_text'  => 'Перейти в каталог',
			'items' => array(
				array(
					'title' => 'Плоский кабель', 'image' => cssc_img( 'cable-flat.png' ), 'video' => '', 'url' => cssc_cat_url( 'ploskij-kabel' ),
					'marks' => 'ВВГ-Пнг(А)-LS, ВВГ-Пнг(А)-LSLTx, ВВГ-Пнг(А)-FRLS, ППВ',
					'text'  => 'Плоский кабель применяется для скрытой и открытой прокладки внутренней электропроводки в жилых и общественных зданиях. Удобен при монтаже в штукатурке, под гипсокартоном и в кабель-каналах, обеспечивает надёжное подключение розеточных и осветительных групп.',
				),
				array(
					'title' => 'Силовой кабель', 'image' => cssc_img( 'cable-power.png' ), 'video' => '', 'url' => cssc_cat_url( 'silovoj-kabel' ),
					'marks' => 'ВВГнг(А)-LS, ВВГнг(А)-LSLTx, ВВГнг(А)-FRLS, ВВГнг(А)-FRLSLTx',
					'text'  => 'Силовой кабель используется для прокладки электрических сетей в жилых, коммерческих и производственных помещениях. Он подходит для подключения и распределения электроэнергии, а также монтажа систем освещения в условиях сухой или повышенной влажности. Кабель предназначен для стационарной прокладки и может применяться при выполнении бытовых и промышленных электромонтажных работ.',
				),
				array(
					'title' => 'Контрольный кабель', 'image' => cssc_img( 'cable-control-poster.png' ), 'video' => cssc_img( 'cable-control.webm' ), 'url' => cssc_cat_url( 'kontrolnyj-kabel' ),
					'marks' => 'КВВГнг(А)-LS, КВВГнг(А)-LSLTx, КВВГЭнг(А)-LS, КВВГнг(А)-FRLS',
					'text'  => 'Контрольный кабель предназначен для передачи управляющих сигналов и подключения электрических приборов, аппаратов и сборок зажимов. Применяется в системах автоматики, релейной защиты и управления на промышленных, энергетических и инфраструктурных объектах при стационарной прокладке.',
				),
			),
		),
		'deliveries' => array(
			'title'        => 'Поставки',
			'text'         => 'Ознакомьтесь с реализованными проектами и оцените опыт компании<br>в области производства и поставки качественной продукции.',
			'counter'      => '200',
			'counter_suffix' => '+',
			'counter_label' => 'успешных поставок',
			'slides'       => array(
				array( 'image' => cssc_img( 'delivery-1.jpg' ), 'tags' => 'ВВГнг-FRLS, КВВГнг-LSLTx', 'category' => 'Реконструкция здания под бизнес-центр', 'title' => 'Grey Plaza Sozvezdie г. Караганда', 'url' => '#' ),
				array( 'image' => cssc_img( 'delivery-2.jpg' ), 'tags' => 'ВВГнг-FRLS, КВВГнг-LSLTx', 'category' => 'Строительство', 'title' => 'МЖК «Sanara» в г. Астана', 'url' => '#' ),
				array( 'image' => cssc_img( 'delivery-3.jpg' ), 'tags' => 'ВВГнг-FRLS', 'category' => 'Жилой комплекс', 'title' => 'ЖК в г. Караганда', 'url' => '#' ),
			),
		),
		'news' => array(
			'title'    => 'Новости',
			'text'     => 'Рассказываем о развитии производства, новых проектах, достижениях команды<br>и событиях, которые важны для нас и наших партнёров.',
			'count'    => 3,
			'category' => '',
		),
	);
	return isset( $d[ $section ] ) ? $d[ $section ] : array();
}

function cssc_arrow_btn_icon() {
	return '<span class="btn-ico">' . cssc_icon( 'arrow-up-right' ) . '</span>';
}

/* ---------- HERO ---------- */
function cssc_render_hero( $s = array() ) {
	$s = wp_parse_args( $s, cssc_section_defaults( 'hero' ) );
	?>
	<section class="cssc-hero">
		<div class="container cssc-hero__grid">
			<div class="cssc-hero__main">
				<h1 class="cssc-hero__title"><?php echo wp_kses_post( $s['title'] ); ?></h1>
				<?php if ( $s['subtitle'] ) : ?><p class="cssc-hero__sub"><?php echo wp_kses_post( $s['subtitle'] ); ?></p><?php endif; ?>
				<div class="cssc-hero__btns">
					<?php foreach ( (array) $s['buttons'] as $b ) :
						if ( empty( $b['text'] ) ) { continue; }
						$url = is_array( $b['url'] ) ? ( $b['url']['url'] ?? '#' ) : $b['url']; ?>
						<a class="btn btn--accent" href="<?php echo esc_url( $url ); ?>"><span><?php echo esc_html( $b['text'] ); ?></span><?php echo cssc_arrow_btn_icon(); // phpcs:ignore ?></a>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="cssc-hero__aside">
				<?php if ( $s['badge'] ) : ?><img class="cssc-hero__badge" src="<?php echo esc_url( $s['badge'] ); ?>" alt=""><?php endif; ?>
				<p><?php echo wp_kses_post( $s['badge_text'] ); ?></p>
			</div>
		</div>
	</section>
	<?php
}

/* ---------- ПРОДУКЦИЯ ---------- */
function cssc_cable_media( $it, $class, $autoplay = false ) {
	$img = $it['image'];
	if ( ! empty( $it['video'] ) ) {
		$type = preg_match( '/\.webm(\?|$)/i', $it['video'] ) ? 'video/webm' : 'video/mp4';
		printf(
			'<video class="%1$s" muted playsinline preload="%4$s" poster="%2$s" data-cable-video%5$s><source src="%3$s" type="%6$s"></video>',
			esc_attr( $class ), esc_url( $img ), esc_url( $it['video'] ), $autoplay ? 'auto' : 'metadata', $autoplay ? ' autoplay' : '', esc_attr( $type )
		);
	} elseif ( $img ) {
		printf( '<img class="%1$s" src="%2$s" alt="%3$s">', esc_attr( $class ), esc_url( $img ), esc_attr( $it['title'] ) );
	}
}

function cssc_icon_gost() {
	return '<svg viewBox="0 0 48 56" fill="none" aria-hidden="true"><path d="M24 2 4 9v16c0 13 8.5 23.6 20 29 11.5-5.4 20-16 20-29V9z" fill="#fff"/><circle cx="24" cy="23" r="10" fill="currentColor"/><path d="m19.5 23 3.2 3.2 6-6.4" stroke="#fff" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/><text x="24" y="46" text-anchor="middle" font-family="Arial,sans-serif" font-weight="700" font-size="8.5" fill="currentColor">EAC</text></svg>';
}
function cssc_icon_warranty( $n ) {
	return '<svg viewBox="0 0 56 56" fill="none" aria-hidden="true"><path d="M38 6H8a3 3 0 0 0-3 3v39a3 3 0 0 0 3 3h39a3 3 0 0 0 3-3V20" stroke="#fff" stroke-width="3.2" stroke-linecap="round"/><circle cx="46" cy="9" r="7" stroke="#fff" stroke-width="2.6"/><path d="m42.8 9 2.2 2.2 4-4.2" stroke="#fff" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/><text x="27.5" y="40" text-anchor="middle" font-family="Onest,Arial,sans-serif" font-weight="800" font-size="25" fill="#fff">' . esc_html( $n ) . '</text></svg>';
}

function cssc_render_products( $s = array() ) {
	$s = wp_parse_args( $s, cssc_section_defaults( 'products' ) );
	$uid = 'cp' . wp_rand( 1000, 99999 );
	?>
	<section class="cssc-products">
		<div class="container">
			<div class="cssc-products__card" data-cp>
				<?php if ( $s['label'] ) : ?><span class="pill pill--accent"><?php echo esc_html( $s['label'] ); ?></span><?php endif; ?>
				<div class="cssc-products__grid">
					<?php foreach ( array_values( (array) $s['items'] ) as $i => $it ) : ?>
						<button type="button" class="cssc-product" data-cp-open="<?php echo esc_attr( $uid . '-' . $i ); ?>" aria-controls="<?php echo esc_attr( $uid . '-' . $i ); ?>" aria-expanded="false">
							<span class="cssc-product__img"><?php cssc_cable_media( $it, 'cssc-product__media' ); ?></span>
							<span class="cssc-product__title"><?php echo esc_html( $it['title'] ); ?></span>
						</button>
					<?php endforeach; ?>
				</div>

				<?php foreach ( array_values( (array) $s['items'] ) as $i => $it ) :
					$url   = is_array( $it['url'] ) ? ( $it['url']['url'] ?? '' ) : $it['url'];
					$marks = array_filter( array_map( 'trim', explode( ',', (string) ( $it['marks'] ?? '' ) ) ) ); ?>
					<div class="cp-detail" id="<?php echo esc_attr( $uid . '-' . $i ); ?>" role="dialog" aria-label="<?php echo esc_attr( $it['title'] ); ?>" hidden>
						<button type="button" class="cp-detail__close" data-cp-close aria-label="Закрыть"><?php echo cssc_icon( 'close' ); // phpcs:ignore ?></button>
						<div class="cp-detail__body">
							<h3 class="cp-detail__title"><?php echo esc_html( $it['title'] ); ?></h3>
							<?php if ( $marks ) : ?><div class="cp-detail__marks"><?php foreach ( $marks as $m ) { echo '<span>' . esc_html( $m ) . '</span>'; } ?></div><?php endif; ?>
							<?php if ( ! empty( $it['text'] ) ) : ?><p class="cp-detail__text"><?php echo esc_html( $it['text'] ); ?></p><?php endif; ?>
							<div class="cp-detail__foot">
								<?php if ( $s['gost_text'] ) : ?><div class="cp-badge"><span class="cp-badge__ico cp-badge__ico--gost"><?php echo cssc_icon_gost(); // phpcs:ignore ?></span><span class="cp-badge__txt"><?php echo wp_kses_post( $s['gost_text'] ); ?></span></div><?php endif; ?>
								<?php if ( $s['warranty'] ) : ?><div class="cp-badge"><span class="cp-badge__ico"><?php echo cssc_icon_warranty( $s['warranty'] ); // phpcs:ignore ?></span><span class="cp-badge__txt"><?php echo wp_kses_post( $s['warr_text'] ); ?></span></div><?php endif; ?>
								<?php if ( $url && $s['more_text'] ) : ?><a class="btn btn--accent cp-detail__more" href="<?php echo esc_url( $url ); ?>"><span><?php echo esc_html( $s['more_text'] ); ?></span><?php echo cssc_arrow_btn_icon(); // phpcs:ignore ?></a><?php endif; ?>
							</div>
						</div>
						<div class="cp-detail__media"><?php cssc_cable_media( $it, 'cp-detail__cable' ); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/* ---------- ПОСТАВКИ ---------- */
function cssc_render_deliveries( $s = array() ) {
	$s = wp_parse_args( $s, cssc_section_defaults( 'deliveries' ) );
	?>
	<section class="cssc-deliveries">
		<div class="container">
			<div class="cssc-deliveries__head">
				<div>
					<h2 class="h-display"><?php echo esc_html( $s['title'] ); ?></h2>
					<p class="cssc-deliveries__text"><?php echo wp_kses_post( $s['text'] ); ?></p>
				</div>
				<?php if ( $s['counter'] ) : ?>
				<div class="cssc-counter">
					<div class="cssc-counter__num" data-count="<?php echo esc_attr( (int) $s['counter'] ); ?>"><?php echo esc_html( $s['counter'] ); ?><sup><?php echo esc_html( $s['counter_suffix'] ); ?></sup></div>
					<div class="cssc-counter__label"><?php echo esc_html( $s['counter_label'] ); ?></div>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="cssc-slider" data-cssc-slider>
			<div class="cssc-slider__nav">
				<button type="button" class="sq-btn" data-next aria-label="Вперёд"><?php echo cssc_icon( 'arrow-right' ); // phpcs:ignore ?></button>
				<button type="button" class="sq-btn" data-prev aria-label="Назад"><?php echo cssc_icon( 'arrow-left' ); // phpcs:ignore ?></button>
			</div>
			<div class="cssc-slider__viewport">
				<div class="cssc-slider__track">
					<?php foreach ( (array) $s['slides'] as $sl ) :
						$url  = is_array( $sl['url'] ) ? ( $sl['url']['url'] ?? '#' ) : $sl['url'];
						$tags = array_filter( array_map( 'trim', explode( ',', (string) $sl['tags'] ) ) ); ?>
						<article class="cssc-slide">
							<img class="cssc-slide__img" src="<?php echo esc_url( $sl['image'] ); ?>" alt="<?php echo esc_attr( $sl['title'] ); ?>">
							<div class="cssc-slide__cap">
								<?php if ( $tags ) : ?><div class="cssc-slide__tags"><?php foreach ( $tags as $t ) { echo '<span>' . esc_html( $t ) . '</span>'; } ?></div><?php endif; ?>
								<div class="cssc-slide__cat"><?php echo esc_html( $sl['category'] ); ?></div>
								<div class="cssc-slide__title"><?php echo esc_html( $sl['title'] ); ?></div>
								<a class="cssc-slide__link" href="<?php echo esc_url( $url ); ?>" aria-label="Подробнее"><?php echo cssc_icon( 'arrow-up-right' ); // phpcs:ignore ?></a>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/* ---------- НОВОСТИ ---------- */
function cssc_render_news( $s = array() ) {
	$s = wp_parse_args( $s, cssc_section_defaults( 'news' ) );
	$args = array( 'post_type' => 'post', 'posts_per_page' => max( 1, (int) $s['count'] ), 'ignore_sticky_posts' => true );
	if ( ! empty( $s['category'] ) ) {
		$args['category_name'] = $s['category'];
	}
	$q = new WP_Query( $args );
	?>
	<section class="cssc-news">
		<div class="container">
			<div class="section-head">
				<h2 class="h-display h-display--dark"><?php echo esc_html( $s['title'] ); ?></h2>
				<p><?php echo wp_kses_post( $s['text'] ); ?></p>
			</div>
			<div class="cssc-news__grid">
				<?php if ( $q->have_posts() ) :
					while ( $q->have_posts() ) : $q->the_post();
						$ts  = get_post_time( 'U' );
						$img = get_the_post_thumbnail_url( null, 'cssc-news' );
						cssc_news_card( get_permalink(), $img ? $img : cssc_img( 'news-1.jpg' ), date( 'j', $ts ), cssc_ru_month( $ts ), get_the_title(), get_the_excerpt() );
					endwhile;
					wp_reset_postdata();
				else :
					for ( $i = 1; $i <= 3; $i++ ) {
						cssc_news_card( '#', cssc_img( 'news-' . $i . '.jpg' ), '24', 'июля 2026', 'Первый совместный тимбилдинг: начало хорошей традиции', 'Впервые собрались вместе вне рабочих задач — чтобы пообщаться, узнать друг друга ближе, посмеяться и просто классно провести время...' );
					}
				endif; ?>
			</div>
		</div>
	</section>
	<?php
}

function cssc_news_card( $url, $img, $day, $month, $title, $excerpt ) {
	?>
	<article class="news-card">
		<a class="news-card__media" href="<?php echo esc_url( $url ); ?>">
			<img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
			<span class="date-badge"><b><?php echo esc_html( $day ); ?></b><small><?php echo esc_html( $month ); ?></small></span>
		</a>
		<h3 class="news-card__title"><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $title ); ?></a></h3>
		<p class="news-card__excerpt"><?php echo esc_html( wp_strip_all_tags( $excerpt ) ); ?></p>
	</article>
	<?php
}
