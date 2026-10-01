<?php defined( 'ABSPATH' ) || exit; get_header(); ?>
<div class="page-wrap page-wrap--light">
	<div class="container container--narrow">
		<?php while ( have_posts() ) : the_post(); $ts = get_post_time( 'U' ); ?>
			<article <?php post_class( 'entry' ); ?>>
				<div class="entry__meta"><span class="date-badge date-badge--inline"><b><?php echo esc_html( date( 'j', $ts ) ); ?></b><small><?php echo esc_html( cssc_ru_month( $ts ) ); ?></small></span></div>
				<h1 class="entry__title"><?php the_title(); ?></h1>
				<?php if ( has_post_thumbnail() ) : ?><div class="entry__thumb"><?php the_post_thumbnail( 'large' ); ?></div><?php endif; ?>
				<div class="entry__content"><?php the_content(); ?></div>
			</article>
		<?php endwhile; ?>
	</div>
</div>
<?php get_footer();
