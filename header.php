<?php defined( 'ABSPATH' ) || exit;
$has_video = cssc_has_video_header();
$vid_id    = (int) get_theme_mod( 'cssc_video_id', 0 );
$video     = $vid_id ? wp_get_attachment_url( $vid_id ) : cssc_opt( 'cssc_video' );
$poster    = cssc_opt( 'cssc_header_bg' );
$full      = cssc_opt( 'cssc_video_full' ) ? cssc_opt( 'cssc_video_full' ) : $video;
$phone     = cssc_opt( 'cssc_phone' );
$email     = cssc_opt( 'cssc_email' );
$addr      = cssc_opt( 'cssc_header_addr' );
$logo_l    = cssc_opt( 'cssc_logo_light' );
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class( $has_video ? 'has-video-header' : 'no-video-header' ); ?>>
<?php wp_body_open(); ?>
<header class="site-header<?php echo $has_video ? ' site-header--video' : ''; ?>">
	<div class="site-header__media" style="background-image:url('<?php echo esc_url( $poster ); ?>')">
		<?php if ( $has_video && $video ) : ?>
			<video class="site-header__video" autoplay muted loop playsinline preload="metadata" poster="<?php echo esc_url( $poster ); ?>">
				<source src="<?php echo esc_url( $video ); ?>" type="<?php echo preg_match( '/\.webm(\?|$)/i', $video ) ? 'video/webm' : 'video/mp4'; ?>">
			</video>
		<?php endif; ?>
		<span class="site-header__shade"></span>
	</div>

	<div class="topbar">
		<div class="topbar__in">
			<a class="topbar__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img src="<?php echo esc_url( $logo_l ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			</a>
			<div class="topbar__tagline"><?php echo wp_kses_post( cssc_opt( 'cssc_tagline' ) ); ?></div>
			<div class="topbar__contacts">
				<?php if ( $phone ) : ?><a class="topbar__item" href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $phone ) ); ?>"><span class="round-ico"><?php echo cssc_icon( 'phone' ); // phpcs:ignore ?></span><span><?php echo esc_html( $phone ); ?></span></a><?php endif; ?>
				<?php if ( $email ) : ?><a class="topbar__item" href="mailto:<?php echo esc_attr( $email ); ?>"><span class="round-ico"><?php echo cssc_icon( 'mail' ); // phpcs:ignore ?></span><span><?php echo esc_html( $email ); ?></span></a><?php endif; ?>
				<?php if ( $addr ) : ?><a class="topbar__item" href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>"><span class="round-ico"><?php echo cssc_icon( 'pin' ); // phpcs:ignore ?></span><span><?php echo esc_html( $addr ); ?></span></a><?php endif; ?>
			</div>
		</div>
		<button class="topbar__burger" type="button" aria-label="Меню" data-drawer-toggle><?php echo cssc_icon( 'burger' ); // phpcs:ignore ?></button>
	</div>

	<?php if ( $has_video && cssc_opt( 'cssc_video_play' ) && $full ) : $emb = cssc_video_embed( $full ); ?>
		<button class="video-play" type="button" aria-label="Смотреть видео" data-video-open data-type="<?php echo esc_attr( $emb['type'] ); ?>" data-src="<?php echo esc_url( $emb['src'] ); ?>">
			<span class="video-play__ring"></span><?php echo cssc_icon( 'play' ); // phpcs:ignore ?>
		</button>
	<?php endif; ?>

	<div class="site-header__bar">
		<nav class="main-nav" aria-label="<?php esc_attr_e( 'Главное меню', 'css-cable' ); ?>">
			<div class="main-nav__menu" data-menu>
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'fallback_cb' => 'cssc_menu_fallback', 'depth' => 2 ) ); ?>
			</div>
			<div class="main-nav__tools">
				<?php if ( cssc_opt( 'cssc_show_cart' ) && function_exists( 'WC' ) && WC()->cart ) : ?>
					<a class="main-nav__cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="Корзина"><?php echo cssc_icon( 'cart' ); // phpcs:ignore ?><span class="cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span></a>
				<?php endif; ?>
				<button class="main-nav__search" type="button" aria-label="Поиск" data-search-toggle><?php echo cssc_icon( 'search' ); // phpcs:ignore ?></button>
			</div>
		</nav>
	</div>
	<div class="search-overlay" data-search>
		<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<input type="search" name="s" placeholder="Поиск по сайту и каталогу…" value="<?php echo esc_attr( get_search_query() ); ?>">
			<?php if ( class_exists( 'WooCommerce' ) ) : ?><input type="hidden" name="post_type" value="product"><?php endif; ?>
			<button type="submit"><?php echo cssc_icon( 'search' ); // phpcs:ignore ?></button>
			<button type="button" class="search-overlay__close" data-search-toggle aria-label="Закрыть"><?php echo cssc_icon( 'close' ); // phpcs:ignore ?></button>
		</form>
	</div>
</header>

<aside class="drawer" data-drawer aria-hidden="true">
	<div class="drawer__box">
		<button class="drawer__close" type="button" data-drawer-toggle aria-label="Закрыть"><?php echo cssc_icon( 'close' ); // phpcs:ignore ?></button>
		<img class="drawer__logo" src="<?php echo esc_url( $logo_l ); ?>" alt="">
		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'fallback_cb' => 'cssc_menu_fallback', 'depth' => 2, 'menu_class' => 'menu drawer__menu' ) ); ?>
		<div class="drawer__contacts">
			<?php if ( $phone ) : ?><a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a><?php endif; ?>
			<?php if ( $email ) : ?><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a><?php endif; ?>
			<?php if ( $addr ) : ?><span><?php echo esc_html( $addr ); ?></span><?php endif; ?>
		</div>
		<a class="btn btn--accent btn--lg" href="#zayavka"><span>Оставить заявку</span><?php echo cssc_arrow_btn_icon(); // phpcs:ignore ?></a>
	</div>
</aside>
<div class="video-modal" data-video-modal aria-hidden="true">
	<button class="video-modal__close" type="button" data-video-close aria-label="Закрыть"><?php echo cssc_icon( 'close' ); // phpcs:ignore ?></button>
	<div class="video-modal__frame" data-video-frame></div>
</div>
<main id="content" class="site-main">
