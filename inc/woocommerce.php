<?php
/**
 * Интеграция WooCommerce + заявки + калькулятор веса
 */
defined( 'ABSPATH' ) || exit;

/* ================= ЗАЯВКА (модальное окно, работает и без WooCommerce) ================= */
add_action( 'wp_footer', function () {
	$sent = isset( $_GET['cssc_sent'] ); // phpcs:ignore
	?>
	<div class="modal<?php echo $sent ? ' is-open' : ''; ?>" id="cssc-modal" data-modal aria-hidden="true">
		<div class="modal__box" role="dialog" aria-modal="true">
			<button class="modal__close" type="button" data-modal-close aria-label="Закрыть"><?php echo cssc_icon( 'close' ); // phpcs:ignore ?></button>
			<?php if ( $sent ) : ?>
				<h3>Спасибо!</h3><p>Заявка отправлена. Менеджер свяжется с вами в ближайшее время.</p>
			<?php else : ?>
				<h3>Оставить заявку</h3>
				<p>Укажите контакты и необходимую продукцию — мы подготовим коммерческое предложение.</p>
				<form
	method="post"
	action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
	class="req-form req-form--product"
>
	<input type="hidden" name="action" value="cssc_request">

	<?php wp_nonce_field( 'cssc_request', 'cssc_nonce' ); ?>

	<input
		type="text"
		name="website"
		class="hp"
		tabindex="-1"
		autocomplete="off"
		aria-hidden="true"
	>

	<label class="req-form__field req-form__field--product">
		<span>Выбранный кабель</span>

		<input
			type="text"
			name="product"
			value=""
			data-req-product
			readonly
			required
		>
	</label>

	<div class="req-form__row">
		<label class="req-form__field">
			<span>Ваше имя*</span>

			<input
				type="text"
				name="name"
				autocomplete="name"
				required
			>
		</label>

		<label class="req-form__field">
			<span>Телефон*</span>

			<input
				type="tel"
				name="phone"
				inputmode="tel"
				autocomplete="tel"
				placeholder="+7 (___) ___-__-__"
				required
			>
		</label>
	</div>

	<label class="req-form__field">
		<span>Метраж*</span>

		<div class="req-form__quantity">
			<input
				type="number"
				name="quantity"
				min="1"
				step="1"
				value="1000"
				inputmode="numeric"
				required
			>

			<span class="req-form__unit">м</span>
		</div>
	</label>

	<label class="req-form__agree">
		<input
			type="checkbox"
			name="agreement"
			value="1"
			required
		>

		<span>
			Я согласен на обработку персональных данных
		</span>
	</label>

	<button class="req-form__submit" type="submit">
		<span>Отправить заявку</span>

		<?php echo cssc_arrow_btn_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</button>
</form>

			<?php endif; ?>
		</div>
	</div>
	<?php
} );

function cssc_handle_request() {
	if ( ! isset( $_POST['cssc_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cssc_nonce'] ) ), 'cssc_request' ) ) {
		wp_die( 'Ошибка проверки формы' );
	}
	if ( ! empty( $_POST['website'] ) ) { // honeypot
		wp_safe_redirect( home_url( '/' ) );
		exit;
	}
	$name = sanitize_text_field(
	wp_unslash( $_POST['name'] ?? '' )
);

$phone = sanitize_text_field(
	wp_unslash( $_POST['phone'] ?? '' )
);

$product = sanitize_text_field(
	wp_unslash( $_POST['product'] ?? '' )
);

$quantity = absint(
	$_POST['quantity'] ?? 0
);

$agreement = ! empty( $_POST['agreement'] );

if (
	'' === $name ||
	'' === $phone ||
	'' === $product ||
	$quantity < 1 ||
	! $agreement
) {
	wp_die(
		esc_html__( 'Заполните все обязательные поля.', 'css-cable' ),
		esc_html__( 'Ошибка отправки', 'css-cable' ),
		array( 'response' => 400 )
	);
}

	$to      = cssc_opt( 'cssc_email' ) ? cssc_opt( 'cssc_email' ) : get_option( 'admin_email' );
	$page_url = wp_get_referer() ?: home_url( '/' );

$body  = "Новая заявка с сайта\n\n";
$body .= "Кабель: {$product}\n";
$body .= "Метраж: {$quantity} м\n";
$body .= "Имя: {$name}\n";
$body .= "Телефон: {$phone}\n";
$body .= "Страница товара: {$page_url}\n";

	wp_mail(
	$to,
	'Заявка на кабель: ' . $product,
	$body,
	array( 'Content-Type: text/plain; charset=UTF-8' )
);
	$ref = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	wp_safe_redirect( add_query_arg( 'cssc_sent', 1, remove_query_arg( 'cssc_sent', $ref ) ) );
	exit;
}
add_action( 'admin_post_cssc_request', 'cssc_handle_request' );
add_action( 'admin_post_nopriv_cssc_request', 'cssc_handle_request' );

/* ================= КАЛЬКУЛЯТОР ВЕСА: шорткод [cssc_weight_calc] ================= */
add_shortcode( 'cssc_weight_calc', function () {
	$items = array();
	if ( class_exists( 'WooCommerce' ) ) {
		$ids = get_posts( array( 'post_type' => 'product', 'numberposts' => -1, 'fields' => 'ids', 'meta_key' => '_cssc_weight_km', 'meta_compare' => '!=', 'meta_value' => '' ) ); // phpcs:ignore
		foreach ( $ids as $id ) {
			$items[] = array( 'n' => get_the_title( $id ), 'w' => (float) str_replace( ',', '.', get_post_meta( $id, '_cssc_weight_km', true ) ) );
		}
	}
	ob_start(); ?>
	<div class="weight-calc" data-weight-calc='<?php echo esc_attr( wp_json_encode( $items ) ); ?>'>
		<label><span>Марка кабеля</span>
			<select data-wc-product>
				<option value="">— введите вес вручную —</option>
				<?php foreach ( $items as $i => $it ) : ?><option value="<?php echo esc_attr( $it['w'] ); ?>"><?php echo esc_html( $it['n'] ); ?></option><?php endforeach; ?>
			</select>
		</label>
		<label><span>Вес 1 км, кг</span><input type="number" step="0.01" min="0" data-wc-kg></label>
		<label><span>Длина, м</span><input type="number" step="1" min="0" value="1000" data-wc-len></label>
		<div class="weight-calc__res">Итого: <b data-wc-res>0</b> кг</div>
	</div>
	<?php
	return ob_get_clean();
} );

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

/* ---- Обёртки ---- */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

add_action( 'woocommerce_before_main_content', function () {
	echo '<div class="page-wrap page-wrap--light shop-wrap"><div class="container">';
	if ( ( is_shop() || is_product_taxonomy() ) && is_active_sidebar( 'shop-sidebar' ) ) {
		echo '<div class="shop-layout"><aside class="shop-sidebar">';
		dynamic_sidebar( 'shop-sidebar' );
		echo '</aside><div class="shop-content">';
	} else {
		echo '<div class="shop-content shop-content--full">';
	}
}, 10 );
add_action( 'woocommerce_after_main_content', function () {
	if ( ( is_shop() || is_product_taxonomy() ) && is_active_sidebar( 'shop-sidebar' ) ) {
		echo '</div></div>';
	} else {
		echo '</div>';
	}
	echo '</div></div>';
}, 10 );

add_filter( 'woocommerce_show_page_title', '__return_true' );
add_filter( 'woocommerce_breadcrumb_defaults', function ( $d ) {
	$d['delimiter'] = '<span class="sep">/</span>';
	$d['home']      = 'Главная';
	return $d;
} );

/* ---- Карточки товара ---- */
add_filter( 'loop_shop_columns', function () { return 3; } );
add_filter( 'woocommerce_output_related_products_args', function ( $a ) {
	$a['posts_per_page'] = 3;
	$a['columns']        = 3;
	return $a;
} );

/* Без цены — «Цена по запросу» + кнопка заявки вместо «В корзину» */
add_filter( 'woocommerce_empty_price_html', function () {
	return '<span class="price-request">Цена по запросу</span>';
} );
add_filter( 'woocommerce_loop_add_to_cart_link', function ( $html, $product ) {
	if ( '' === $product->get_price() ) {
		return '<a href="#zayavka" class="button btn btn--accent" data-req="' . esc_attr( $product->get_name() ) . '"><span>Оставить заявку</span>' . cssc_arrow_btn_icon() . '</a>';
	}
	return $html;
}, 10, 2 );
add_action( 'woocommerce_single_product_summary', function () {
	global $product;
	if ( $product && '' === $product->get_price() ) {
		echo '<a href="#zayavka" class="btn btn--accent btn--lg" data-req="' . esc_attr( $product->get_name() ) . '"><span>Оставить заявку</span>' . cssc_arrow_btn_icon() . '</a>'; // phpcs:ignore
	}
}, 31 );

/* ---- Доп. поле товара: вес 1 км ---- */
add_action( 'woocommerce_product_options_general_product_data', function () {
	woocommerce_wp_text_input( array(
		'id'          => '_cssc_weight_km',
		'label'       => 'Вес 1 км, кг',
		'description' => 'Используется в калькуляторе веса [cssc_weight_calc]',
		'desc_tip'    => true,
		'type'        => 'text',
	) );
} );
add_action( 'woocommerce_process_product_meta', function ( $id ) {
	if ( isset( $_POST['_cssc_weight_km'] ) ) { // phpcs:ignore
		update_post_meta( $id, '_cssc_weight_km', sanitize_text_field( wp_unslash( $_POST['_cssc_weight_km'] ) ) ); // phpcs:ignore
	}
} );
add_filter( 'woocommerce_product_tabs', function ( $tabs ) {
	global $product;
	$w = $product ? get_post_meta( $product->get_id(), '_cssc_weight_km', true ) : '';
	if ( $w ) {
		$tabs['cssc_weight'] = array(
			'title'    => 'Вес',
			'priority' => 25,
			'callback' => function () use ( $w ) { echo '<p>Вес 1 км: <b>' . esc_html( $w ) . ' кг</b></p>'; },
		);
	}
	return $tabs;
} );

/* ---- Счётчик корзины в меню (AJAX fragments) ---- */
add_filter( 'woocommerce_add_to_cart_fragments', function ( $f ) {
	$f['span.cart-count'] = '<span class="cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
	return $f;
} );
