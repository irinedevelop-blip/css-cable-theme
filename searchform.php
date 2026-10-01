<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="search" class="search-field" placeholder="Поиск…" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	<button type="submit" class="btn btn--accent"><span>Найти</span></button>
</form>
