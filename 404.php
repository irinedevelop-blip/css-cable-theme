<?php defined( 'ABSPATH' ) || exit; get_header(); ?>
<div class="page-wrap page-wrap--light"><div class="container" style="text-align:center">
	<h1 class="h-display h-display--dark">404</h1><p>Страница не найдена.</p>
	<a class="btn btn--accent" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span>На главную</span><?php echo cssc_arrow_btn_icon(); // phpcs:ignore ?></a>
</div></div>
<?php get_footer();
