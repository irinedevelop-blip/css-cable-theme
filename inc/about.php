<?php
/**
 * Страница «О компании»: схема секций (поля + значения по умолчанию) и рендер.
 * Одна схема используется виджетами Elementor, генератором и шаблоном без Elementor.
 */
defined( 'ABSPATH' ) || exit;

function cssc_about_img( $f ) { return cssc_img( 'about/' . $f ); }

function cssc_about_video_default() { return 'https://csscable.kz/sites/default/files/videos/original/new_1.mp4'; }

/* ================= СХЕМА ================= */
function cssc_about_schema() {
	static $s = null;
	if ( null !== $s ) { return $s; }
	$s = array(
		'hero' => array(
			'title' => 'О компании: Первый экран', 'icon' => 'eicon-header',
			'fields' => array(
				'eyebrow' => array( 'text', 'Надзаголовок', 'Характеристика ЦСС-КАБЕЛЬ' ),
				'title'   => array( 'text', 'Заголовок', 'О компании' ),
				'lead'    => array( 'textarea', 'Описание', 'ТОО «Кабельный завод ЦСС-КАБЕЛЬ» — современное отечественное предприятие по производству силового и контрольного кабеля, входящее в состав группы компаний CSS GROUP.' ),
				'bg'      => array( 'media', 'Фоновое фото', cssc_about_img( 'factory-building.jpg' ) ),
				'facts'   => array( 'repeater', 'Факты', array(
					array( 'value' => '2025', 'label' => 'В декабре завод был введён в эксплуатацию' ),
					array( 'value' => '38', 'label' => 'Марок силового и контрольного кабеля' ),
					array( 'value' => 'Караганда', 'label' => 'Место расположения предприятия' ),
				), array( 'value' => array( 'text', 'Значение' ), 'label' => array( 'text', 'Подпись' ) ), 'value' ),
			),
		),
		'intro' => array(
			'title' => 'О компании: О предприятии', 'icon' => 'eicon-info-box',
			'fields' => array(
				'eyebrow' => array( 'text', 'Надзаголовок', 'ЦСС-КАБЕЛЬ' ),
				'title'   => array( 'textarea', 'Заголовок', 'Современное отечественное предприятие' ),
				'label'   => array( 'text', 'Подпись с красной линией', 'ТОО «Кабельный завод ЦСС-КАБЕЛЬ»' ),
				'text'    => array( 'wysiwyg', 'Текст', '<p>Завод расположен в городе Караганда и является первым и единственным производителем медного силового и контрольного кабеля в Карагандинской области.</p><p>ТОО «Кабельный завод ЦСС-КАБЕЛЬ» входит в состав группы компаний CSS GROUP.</p>' ),
				'image'   => array( 'media', 'Фото', cssc_about_img( 'factory-building.jpg' ) ),
				'badge'   => array( 'text', 'Подпись на фото', '01 / ЗАВОД' ),
			),
		),
		'timeline' => array(
			'title' => 'О компании: История', 'icon' => 'eicon-time-line',
			'fields' => array(
				'eyebrow' => array( 'text', 'Надзаголовок', 'История предприятия' ),
				'title'   => array( 'textarea', 'Заголовок', 'От опыта на рынке к собственному производству' ),
				'items'   => array( 'repeater', 'Этапы', array(
					array( 'year' => '8+ лет', 'text' => 'ТОО «ЦентрСнабСтрой» успешно работает на рынке кабельно-проводниковой продукции Казахстана, обеспечивая строительные, промышленные и инфраструктурные объекты качественной продукцией ведущих производителей.' ),
					array( 'year' => '2023', 'text' => 'Руководством ТОО «ЦентрСнабСтрой» было принято стратегическое решение о создании собственного кабельного производства.' ),
					array( 'year' => '2025', 'text' => 'После приобретения оборудования, подготовки производственной площадки и формирования команды специалистов в декабре завод был введён в эксплуатацию и выпустил первую партию продукции.' ),
				), array( 'year' => array( 'text', 'Год / срок' ), 'text' => array( 'textarea', 'Текст' ) ), 'year' ),
				'text'    => array( 'wysiwyg', 'Текст под шкалой', '<p>Многолетний опыт работы на рынке позволил глубоко изучить потребности потребителей и отрасли в целом. Это стало основой для реализации масштабного инвестиционного проекта по созданию современного отечественного производства, ориентированного на выпуск кабельной продукции, соответствующей высоким требованиям качества и безопасности.</p>' ),
			),
		),
		'production' => array(
			'title' => 'О компании: Производство (видео)', 'icon' => 'eicon-youtube',
			'fields' => array(
				'video'     => array( 'text', 'Видео-превью (URL .mp4 / .webm, без звука)', cssc_about_video_default() ),
				'video_full' => array( 'text', 'Полное видео (YouTube, Vimeo или .mp4). Пусто — то же видео', '' ),
				'poster'    => array( 'media', 'Постер видео', cssc_about_img( 'production-machine.jpg' ) ),
				'btn'       => array( 'text', 'Текст кнопки', 'Смотреть полностью' ),
				'badge'     => array( 'text', 'Подпись на видео', '02 / ПРОИЗВОДСТВО' ),
				'eyebrow'   => array( 'text', 'Надзаголовок', 'Производственный комплекс' ),
				'title'     => array( 'textarea', 'Заголовок', 'Современная производственная база' ),
				'text'      => array( 'wysiwyg', 'Текст', '<p>Производственный комплекс предприятия занимает площадь 1368 квадратных метров и оснащён современным технологическим оборудованием.</p>' ),
				'list'      => array( 'repeater', 'Оборудование', array(
					array( 'text' => 'Линии малой и большой перемотки' ),
					array( 'text' => 'Экструзионные линии 70 и 90' ),
					array( 'text' => 'Линия скрутки' ),
					array( 'text' => 'Линия вертикальной обмотки' ),
					array( 'text' => 'Линия бухтовки' ),
					array( 'text' => 'Линия термоупаковки' ),
				), array( 'text' => array( 'text', 'Пункт' ) ), 'text' ),
			),
		),
		'numbers' => array(
			'title' => 'О компании: Цифры', 'icon' => 'eicon-counter',
			'fields' => array(
				'items' => array( 'repeater', 'Цифры', array(
					array( 'value' => '1368', 'label' => 'квадратных метров производственного комплекса' ),
					array( 'value' => '17–20', 'label' => 'километров готовой кабельной продукции в сутки' ),
					array( 'value' => '9–15', 'label' => 'тонн меди перерабатывается в среднем ежемесячно' ),
					array( 'value' => '38', 'label' => 'марок силового и контрольного кабеля' ),
				), array( 'value' => array( 'text', 'Число' ), 'label' => array( 'text', 'Подпись' ) ), 'value' ),
				'count' => array( 'switch', 'Анимировать счёт чисел', 'yes' ),
			),
		),
		'products' => array(
			'title' => 'О компании: Продукция', 'icon' => 'eicon-products',
			'fields' => array(
				'eyebrow' => array( 'text', 'Надзаголовок', 'Продукция завода' ),
				'title'   => array( 'textarea', 'Заголовок', 'Силовой и контрольный кабель' ),
				'lead'    => array( 'textarea', 'Описание', 'Сегодня предприятие выпускает 38 марок силового и контрольного кабеля, востребованных в гражданском, коммерческом и промышленном строительстве.' ),
				'cards'   => array( 'repeater', 'Большие карточки', array(
					array( 'index' => '01 / СИЛОВЫЕ КАБЕЛИ', 'title' => 'Силовые кабели', 'codes' => 'ВВГнг(А)-LS, ВВГнг(А)-LSLTx, ВВГнг(А)-FRLS', 'link' => '' ),
					array( 'index' => '02 / КОНТРОЛЬНЫЕ КАБЕЛИ', 'title' => 'Контрольные кабели', 'codes' => 'КВВГнг-LS, КВВГнг-LSLTx, Различные исполнения', 'link' => '' ),
				), array( 'index' => array( 'text', 'Надпись сверху' ), 'title' => array( 'text', 'Название' ), 'codes' => array( 'textarea', 'Марки (через запятую)' ), 'link' => array( 'text', 'Ссылка (необязательно)' ) ), 'title' ),
				'special' => array( 'repeater', 'Малые карточки', array(
					array( 'title' => 'LSLTx', 'text' => 'Кабели с пониженным дымо- и газовыделением и низкой токсичностью продуктов горения. Являются обязательным решением для объектов социальной инфраструктуры, включая детские сады, школы и медицинские учреждения.' ),
					array( 'title' => 'FRLS', 'text' => 'Огнестойкие кабели предназначены для систем пожарной автоматики, оповещения и эвакуации людей и обеспечивают сохранение работоспособности в условиях воздействия открытого огня.' ),
				), array( 'title' => array( 'text', 'Заголовок' ), 'text' => array( 'textarea', 'Текст' ) ), 'title' ),
			),
		),
		'advantages' => array(
			'title' => 'О компании: Преимущества', 'icon' => 'eicon-check-circle',
			'fields' => array(
				'eyebrow' => array( 'text', 'Надзаголовок', 'Преимущества предприятия' ),
				'title'   => array( 'textarea', 'Заголовок', 'Продукция непосредственно от производителя' ),
				'text'    => array( 'textarea', 'Текст', 'Отсутствие посредников позволяет предлагать потребителям конкурентоспособные цены, обеспечивать постоянное наличие востребованных позиций и оперативно выполнять заказы различного объёма.' ),
				'checks'  => array( 'repeater', 'Пункты', array(
					array( 'text' => 'Конкурентоспособные цены' ),
					array( 'text' => 'Постоянное наличие востребованных позиций' ),
					array( 'text' => 'Оперативное выполнение заказов различного объёма' ),
					array( 'text' => 'Контроль качества на всех этапах производства' ),
				), array( 'text' => array( 'text', 'Пункт' ) ), 'text' ),
				'image'   => array( 'media', 'Фото', cssc_about_img( 'worker-equipment.jpg' ) ),
				'badge'   => array( 'text', 'Подпись на фото', '03 / ОБОРУДОВАНИЕ' ),
			),
		),
		'quality' => array(
			'title' => 'О компании: Качество', 'icon' => 'eicon-rating',
			'fields' => array(
				'eyebrow' => array( 'text', 'Надзаголовок', 'Контроль качества' ),
				'title'   => array( 'textarea', 'Заголовок', 'Качество подтверждено' ),
				'text'    => array( 'textarea', 'Текст', 'Вся продукция производится из качественного сырья в строгом соответствии с требованиями государственных стандартов и проходит контроль качества на всех этапах технологического процесса.' ),
				'bg'      => array( 'media', 'Фоновое фото', cssc_about_img( 'copper-reels.jpg' ) ),
				'mark'    => array( 'text', 'Крупная надпись на фоне', 'СТ-KZ' ),
				'certs'   => array( 'repeater', 'Сертификаты', array(
					array( 'title' => 'Сертификат соответствия', 'text' => 'Зарегистрирован в реестре данных государственной системы технического регулирования Республики Казахстан', 'link' => '' ),
					array( 'title' => 'Сертификат CT-KZ', 'text' => 'Подтверждает статус отечественного производителя', 'link' => '' ),
					array( 'title' => 'Реестр товаропроизводителей', 'text' => 'Реестр отечественных товаропроизводителей Республики Казахстан', 'link' => '' ),
				), array( 'title' => array( 'text', 'Название' ), 'text' => array( 'textarea', 'Описание' ), 'link' => array( 'text', 'Ссылка на файл/страницу (необязательно)' ) ), 'title' ),
			),
		),
		'projects' => array(
			'title' => 'О компании: Объекты', 'icon' => 'eicon-bullet-list',
			'fields' => array(
				'eyebrow' => array( 'text', 'Надзаголовок', 'География применения' ),
				'title'   => array( 'textarea', 'Заголовок', 'Продукция на объектах Казахстана' ),
				'text'    => array( 'textarea', 'Текст слева', 'Продукция предприятия применяется на объектах промышленного, энергетического, социального и жилищного строительства в различных регионах Республики Казахстан.' ),
				'before'  => array( 'textarea', 'Текст над списком', 'В 2026 году кабельная продукция завода была поставлена для реализации следующих проектов:' ),
				'items'   => array( 'repeater', 'Объекты', array_map( function ( $t ) { return array( 'text' => $t ); }, array(
					'Подстанция ПС «Агадырь» 500 кВ АО «KEGOC»',
					'Областной центр физиопульмонологии города Сатпаев',
					'Общеобразовательная школа №1 имени Ыбырая Алтынсарина в городе Степногорск',
					'Общеобразовательная школа №13 в городе Астана',
					'Областная прокуратура города Караганда',
					'Казарма для курсантов МВД Республики Казахстан в городе Костанай',
					'Бизнес-центр Grey Plaza Sozvezdie в Караганде',
					'Производственная площадка на территории специальной экономической зоны «Сарыарка»',
					'Жилой комплекс «Береке» в городе Костанай',
					'Жилые комплексы «Дублин», «Шежіре» и Sanara в городе Астана',
					'Жилой комплекс «Бақыт Отбасы» в городе Караганда',
				) ), array( 'text' => array( 'text', 'Объект' ) ), 'text' ),
				'after'   => array( 'textarea', 'Текст под списком', 'Кроме того, продукция предприятия активно используется частными застройщиками при строительстве индивидуальных жилых домов на территории Казахстана.' ),
			),
		),
		'team' => array(
			'title' => 'О компании: Команда', 'icon' => 'eicon-person',
			'fields' => array(
				'image1'  => array( 'media', 'Фото 1 (большое)', cssc_about_img( 'worker-control-panel.jpg' ) ),
				'image2'  => array( 'media', 'Фото 2 (малое)', cssc_about_img( 'worker-css-cable.jpg' ) ),
				'eyebrow' => array( 'text', 'Надзаголовок', 'Команда' ),
				'title'   => array( 'textarea', 'Заголовок', 'Специалисты предприятия' ),
				'count'   => array( 'text', 'Число', '15' ),
				'count_label' => array( 'text', 'Подпись к числу', 'специалистов' ),
				'text'    => array( 'wysiwyg', 'Текст', '<p class="ab-text-lg">Завод активно развивает производственные мощности, расширяет ассортимент выпускаемой продукции и укрепляет партнёрские отношения с заказчиками по всей территории Казахстана.</p><p>Постоянными клиентами предприятия являются строительные, монтажные и торговые организации, заинтересованные в надёжной и качественной продукции отечественного производства.</p>' ),
			),
		),
		'final' => array(
			'title' => 'О компании: Заключение', 'icon' => 'eicon-call-to-action',
			'fields' => array(
				'eyebrow' => array( 'text', 'Надзаголовок', 'CSS GROUP' ),
				'title'   => array( 'textarea', 'Заголовок', 'Вклад в промышленный потенциал Казахстана' ),
				'text'    => array( 'wysiwyg', 'Текст', '<p>Являясь частью CSS GROUP, ТОО «Кабельный завод ЦСС-КАБЕЛЬ» вносит вклад в развитие промышленного потенциала Казахстана, укрепление политики импортозамещения и обеспечение строительной отрасли страны качественной кабельной продукцией собственного производства.</p><p>Сочетание многолетнего опыта, современных технологий и высоких стандартов качества позволяет предприятию уверенно занимать своё место среди перспективных производителей кабельно-проводниковой продукции Республики Казахстан.</p>' ),
				'bg'      => array( 'media', 'Фоновое фото', cssc_about_img( 'production-machine.jpg' ) ),
				'btn'     => array( 'text', 'Кнопка (пусто — без кнопки)', 'Оставить заявку' ),
				'btn_url' => array( 'text', 'Ссылка кнопки', '#zayavka' ),
			),
		),
	);
	return $s;
}

/** Значения по умолчанию секции в «плоском» виде */
function cssc_about_defaults( $slug ) {
	$sc = cssc_about_schema();
	$out = array();
	foreach ( $sc[ $slug ]['fields'] as $k => $f ) { $out[ $k ] = $f[2]; }
	return $out;
}

/* ================= РЕНДЕР ================= */
function cssc_ab_head( $s, $tag = 'h2' ) {
	if ( ! empty( $s['eyebrow'] ) ) { echo '<p class="ab-eyebrow">' . esc_html( $s['eyebrow'] ) . '</p>'; }
	if ( ! empty( $s['title'] ) ) { echo '<' . $tag . ' class="ab-h">' . nl2br( esc_html( $s['title'] ) ) . '</' . $tag . '>'; } // phpcs:ignore
}

function cssc_ab_media( $img, $badge = '', $alt = '', $extra = '' ) {
	if ( ! $img ) { return; }
	echo '<div class="ab-media ' . esc_attr( $extra ) . '"><img src="' . esc_url( $img ) . '" alt="' . esc_attr( $alt ) . '" decoding="async">';
	if ( $badge ) { echo '<span class="ab-media__num">' . esc_html( $badge ) . '</span>'; }
	echo '</div>';
}

function cssc_about_render( $slug, $s = array() ) {
	$s = wp_parse_args( $s, cssc_about_defaults( $slug ) );
	$fn = 'cssc_about_r_' . $slug;
	if ( function_exists( $fn ) ) { $fn( $s ); }
}

function cssc_about_r_hero( $s ) { ?>
	<section class="ab ab-dark ab-hero" style="--ab-bg:url('<?php echo esc_url( $s['bg'] ); ?>')">
		<div class="ab-wrap ab-hero__in">
			<?php cssc_ab_head( $s, 'h1' ); ?>
			<?php if ( $s['lead'] ) : ?><p class="ab-lead ab-hero__desc"><?php echo esc_html( $s['lead'] ); ?></p><?php endif; ?>
			<?php if ( $s['facts'] ) : ?>
			<div class="ab-hero__facts">
				<?php foreach ( $s['facts'] as $f ) : ?>
					<div class="ab-fact"><strong><?php echo esc_html( $f['value'] ); ?></strong><span><?php echo esc_html( $f['label'] ); ?></span></div>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</div>
	</section>
<?php }

function cssc_about_r_intro( $s ) { ?>
	<section class="ab ab-dark ab-sec">
		<div class="ab-wrap ab-intro">
			<div class="ab-intro__title">
				<?php cssc_ab_head( $s ); ?>
				<?php if ( $s['label'] ) : ?><div class="ab-label"><?php echo esc_html( $s['label'] ); ?></div><?php endif; ?>
			</div>
			<div class="ab-text ab-text-lg">
				<?php echo wp_kses_post( $s['text'] ); ?>
				<?php cssc_ab_media( $s['image'], $s['badge'], $s['label'], 'ab-media--mt' ); ?>
			</div>
		</div>
	</section>
<?php }

function cssc_about_r_timeline( $s ) { ?>
	<section class="ab ab-light ab-sec ab-timeline-sec">
		<div class="ab-wrap">
			<?php cssc_ab_head( $s ); ?>
			<div class="ab-timeline" style="--n:<?php echo max( 1, count( (array) $s['items'] ) ); ?>">
				<?php foreach ( (array) $s['items'] as $it ) : ?>
					<article class="ab-tl-item">
						<span class="ab-tl-item__dot"></span>
						<strong class="ab-tl-item__year"><?php echo esc_html( $it['year'] ); ?></strong>
						<p><?php echo esc_html( $it['text'] ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
			<?php if ( $s['text'] ) : ?><div class="ab-text ab-text-lg ab-timeline__after"><?php echo wp_kses_post( $s['text'] ); ?></div><?php endif; ?>
		</div>
	</section>
<?php }

function cssc_about_r_production( $s ) {
	$full = $s['video_full'] ? $s['video_full'] : $s['video'];
	$emb  = $full ? cssc_video_embed( $full ) : null;
	$type = preg_match( '/\.webm(\?|$)/i', (string) $s['video'] ) ? 'video/webm' : 'video/mp4';
	?>
	<section class="ab ab-dark ab-sec ab-production">
		<div class="ab-wrap">
			<div class="ab-production__grid">
				<div class="ab-media ab-media--video">
					<?php if ( $s['video'] ) : ?>
						<video class="ab-media__video" autoplay muted loop playsinline preload="metadata" poster="<?php echo esc_url( $s['poster'] ); ?>"><source src="<?php echo esc_url( $s['video'] ); ?>" type="<?php echo esc_attr( $type ); ?>"></video>
					<?php elseif ( $s['poster'] ) : ?>
						<img src="<?php echo esc_url( $s['poster'] ); ?>" alt="">
					<?php endif; ?>
					<?php if ( $emb && $s['btn'] ) : ?>
						<button class="ab-video-open" type="button" data-video-open data-type="<?php echo esc_attr( $emb['type'] ); ?>" data-src="<?php echo esc_url( $emb['src'] ); ?>"><span class="ab-video-open__ico" aria-hidden="true"></span><span><?php echo esc_html( $s['btn'] ); ?></span></button>
					<?php endif; ?>
					<?php if ( $s['badge'] ) : ?><span class="ab-media__num"><?php echo esc_html( $s['badge'] ); ?></span><?php endif; ?>
				</div>
				<div>
					<?php cssc_ab_head( $s ); ?>
					<div class="ab-text ab-text-lg"><?php echo wp_kses_post( $s['text'] ); ?></div>
				</div>
			</div>
			<?php if ( $s['list'] ) : ?>
			<ul class="ab-plist"><?php foreach ( $s['list'] as $li ) { echo '<li>' . esc_html( $li['text'] ) . '</li>'; } ?></ul>
			<?php endif; ?>
		</div>
	</section>
<?php }

function cssc_about_r_numbers( $s ) { ?>
	<section class="ab ab-numbers">
		<div class="ab-wrap ab-numbers__grid" style="--n:<?php echo max( 1, count( (array) $s['items'] ) ); ?>">
			<?php foreach ( (array) $s['items'] as $it ) : ?>
				<div class="ab-num"><strong<?php echo 'yes' === $s['count'] ? ' data-ab-count' : ''; ?>><?php echo esc_html( $it['value'] ); ?></strong><span><?php echo esc_html( $it['label'] ); ?></span></div>
			<?php endforeach; ?>
		</div>
	</section>
<?php }

function cssc_about_r_products( $s ) { ?>
	<section class="ab ab-light ab-sec ab-products">
		<div class="ab-wrap">
			<?php cssc_ab_head( $s ); ?>
			<?php if ( $s['lead'] ) : ?><p class="ab-lead"><?php echo esc_html( $s['lead'] ); ?></p><?php endif; ?>
			<div class="ab-pgrid">
				<?php foreach ( (array) $s['cards'] as $c ) :
					$tag = $c['link'] ? 'a' : 'article';
					$codes = array_filter( array_map( 'trim', explode( ',', (string) $c['codes'] ) ) ); ?>
					<<?php echo $tag; // phpcs:ignore ?> class="ab-pcard"<?php echo $c['link'] ? ' href="' . esc_url( $c['link'] ) . '"' : ''; ?>>
						<span class="ab-pcard__idx"><?php echo esc_html( $c['index'] ); ?></span>
						<h3><?php echo esc_html( $c['title'] ); ?></h3>
						<?php if ( $codes ) : ?><div class="ab-pcard__codes"><?php foreach ( $codes as $cd ) { echo '<span>' . esc_html( $cd ) . '</span>'; } ?></div><?php endif; ?>
					</<?php echo $tag; // phpcs:ignore ?>>
				<?php endforeach; ?>
			</div>
			<?php if ( $s['special'] ) : ?>
			<div class="ab-sgrid">
				<?php foreach ( $s['special'] as $c ) : ?>
					<article class="ab-scard"><strong><?php echo esc_html( $c['title'] ); ?></strong><p><?php echo esc_html( $c['text'] ); ?></p></article>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</div>
	</section>
<?php }

function cssc_about_r_advantages( $s ) { ?>
	<section class="ab ab-dark ab-sec">
		<div class="ab-wrap ab-adv">
			<div>
				<?php cssc_ab_head( $s ); ?>
				<?php if ( $s['text'] ) : ?><p class="ab-text-lg"><?php echo esc_html( $s['text'] ); ?></p><?php endif; ?>
				<div class="ab-checks"><?php foreach ( (array) $s['checks'] as $c ) { echo '<div class="ab-check">' . esc_html( $c['text'] ) . '</div>'; } ?></div>
			</div>
			<?php cssc_ab_media( $s['image'], $s['badge'], $s['title'] ); ?>
		</div>
	</section>
<?php }

function cssc_about_r_quality( $s ) { ?>
	<section class="ab ab-sec ab-quality" style="--ab-bg:url('<?php echo esc_url( $s['bg'] ); ?>')" data-mark="<?php echo esc_attr( $s['mark'] ); ?>">
		<div class="ab-wrap ab-quality__in">
			<?php cssc_ab_head( $s ); ?>
			<?php if ( $s['text'] ) : ?><p class="ab-quality__text"><?php echo esc_html( $s['text'] ); ?></p><?php endif; ?>
			<div class="ab-certs" style="--n:<?php echo max( 1, min( 4, count( (array) $s['certs'] ) ) ); ?>">
				<?php foreach ( (array) $s['certs'] as $c ) :
					$tag = $c['link'] ? 'a' : 'div'; ?>
					<<?php echo $tag; // phpcs:ignore ?> class="ab-cert"<?php echo $c['link'] ? ' href="' . esc_url( $c['link'] ) . '" target="_blank" rel="noopener"' : ''; ?>><strong><?php echo esc_html( $c['title'] ); ?></strong><span><?php echo esc_html( $c['text'] ); ?></span></<?php echo $tag; // phpcs:ignore ?>>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php }

function cssc_about_r_projects( $s ) { ?>
	<section class="ab ab-light ab-sec">
		<div class="ab-wrap ab-projects">
			<div class="ab-projects__head">
				<?php cssc_ab_head( $s ); ?>
				<?php if ( $s['text'] ) : ?><p class="ab-text-lg"><?php echo esc_html( $s['text'] ); ?></p><?php endif; ?>
			</div>
			<div>
				<?php if ( $s['before'] ) : ?><p class="ab-projects__before"><?php echo esc_html( $s['before'] ); ?></p><?php endif; ?>
				<ol class="ab-plist2"><?php foreach ( (array) $s['items'] as $k => $li ) { echo '<li><b>' . esc_html( str_pad( (string) ( $k + 1 ), 2, '0', STR_PAD_LEFT ) ) . '</b>' . esc_html( $li['text'] ) . '</li>'; } ?></ol>
				<?php if ( $s['after'] ) : ?><p class="ab-projects__after"><?php echo esc_html( $s['after'] ); ?></p><?php endif; ?>
			</div>
		</div>
	</section>
<?php }

function cssc_about_r_team( $s ) { ?>
	<section class="ab ab-dark ab-sec">
		<div class="ab-wrap ab-team">
			<div class="ab-team__imgs">
				<?php cssc_ab_media( $s['image1'], '', $s['title'], 'ab-team__i1' ); ?>
				<?php cssc_ab_media( $s['image2'], '', $s['title'], 'ab-team__i2' ); ?>
			</div>
			<div>
				<?php cssc_ab_head( $s ); ?>
				<?php if ( $s['count'] ) : ?><div class="ab-team__count"><strong data-ab-count><?php echo esc_html( $s['count'] ); ?></strong><span><?php echo esc_html( $s['count_label'] ); ?></span></div><?php endif; ?>
				<div class="ab-text"><?php echo wp_kses_post( $s['text'] ); ?></div>
			</div>
		</div>
	</section>
<?php }

function cssc_about_r_final( $s ) { ?>
	<section class="ab ab-final" style="--ab-bg:url('<?php echo esc_url( $s['bg'] ); ?>')">
		<div class="ab-wrap ab-final__in">
			<?php cssc_ab_head( $s ); ?>
			<div class="ab-text"><?php echo wp_kses_post( $s['text'] ); ?></div>
			<?php if ( $s['btn'] ) : ?><p class="ab-final__btn"><a class="btn btn--accent btn--lg" href="<?php echo esc_url( $s['btn_url'] ? $s['btn_url'] : '#zayavka' ); ?>"><span><?php echo esc_html( $s['btn'] ); ?></span><?php echo cssc_arrow_btn_icon(); // phpcs:ignore ?></a></p><?php endif; ?>
		</div>
	</section>
<?php }

/** Вся страница по умолчанию (если страница не собрана в Elementor) */
function cssc_about_render_all() {
	foreach ( array_keys( cssc_about_schema() ) as $slug ) { cssc_about_render( $slug ); }
}
