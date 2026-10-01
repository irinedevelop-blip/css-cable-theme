<?php defined( 'ABSPATH' ) || exit; get_header(); ?>
<?php while ( have_posts() ) : the_post();
	$is_el = did_action( 'elementor/loaded' ) && \Elementor\Plugin::$instance->documents->get( get_the_ID() ) && \Elementor\Plugin::$instance->documents->get( get_the_ID() )->is_built_with_elementor();
	if ( $is_el ) { the_content(); continue; } ?>
	<div class="page-wrap page-wrap--light">
		<div class="container">
			<h1 class="h-display h-display--dark page-title"><?php the_title(); ?></h1>
			<div class="entry__content"><?php the_content(); ?></div>
		</div>
	</div>
<?php endwhile; ?>
<?php get_footer();
