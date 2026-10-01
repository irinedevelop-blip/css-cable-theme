<?php defined( 'ABSPATH' ) || exit; get_header(); ?>
<div class="page-wrap page-wrap--light">
	<div class="container">
		<header class="page-head">
			<h1 class="h-display h-display--dark"><?php
				if ( is_home() ) { echo esc_html( get_the_title( get_option( 'page_for_posts' ) ) ?: 'Новости' ); }
				elseif ( is_search() ) { printf( 'Поиск: %s', esc_html( get_search_query() ) ); }
				else { the_archive_title(); }
			?></h1>
		</header>
		<?php if ( have_posts() ) : ?>
			<div class="cssc-news__grid">
				<?php while ( have_posts() ) : the_post();
					$ts = get_post_time( 'U' );
					$img = get_the_post_thumbnail_url( null, 'cssc-news' );
					cssc_news_card( get_permalink(), $img ? $img : cssc_img( 'news-1.jpg' ), date( 'j', $ts ), cssc_ru_month( $ts ), get_the_title(), get_the_excerpt() );
				endwhile; ?>
			</div>
			<div class="pagination"><?php the_posts_pagination( array( 'prev_text' => '←', 'next_text' => '→' ) ); ?></div>
		<?php else : ?>
			<p>Ничего не найдено.</p>
		<?php endif; ?>
	</div>
</div>
<?php get_footer();
