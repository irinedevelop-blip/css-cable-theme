<?php
/**
 * Страница отдельного товара CSS Cable.
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product || ! $product->get_id() ) {
	return;
}

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

$product_id = $product->get_id();

$gost           = $product->get_meta( '_cssc_gost' );
$marking        = $product->get_meta( '_cssc_marking' );
$application    = $product->get_meta( '_cssc_application' );
$certificate_id = absint( $product->get_meta( '_cssc_certificate_id' ) );

$description       = $product->get_description();
$short_description = $product->get_short_description();
$image_id          = $product->get_image_id();
$sku               = $product->get_sku();

$categories = get_the_terms( $product_id, 'product_cat' );
$category   = '';

if ( $categories && ! is_wp_error( $categories ) ) {
	$category = $categories[0]->name;
}

$certificate_url      = '';
$certificate_image    = '';
$certificate_full     = '';
$certificate_mime     = '';
$certificate_is_pdf   = false;
$certificate_modal_id = 'cssc-certificate-' . $product_id;

if ( $certificate_id ) {
	$certificate_url  = wp_get_attachment_url( $certificate_id );
	$certificate_mime = get_post_mime_type( $certificate_id );

	$certificate_is_pdf = 'application/pdf' === $certificate_mime;

	if ( ! $certificate_is_pdf ) {
		$certificate_image = wp_get_attachment_image(
			$certificate_id,
			'medium_large',
			false,
			array(
				'class'   => 'cable-product__certificate-image',
				'loading' => 'lazy',
			)
		);

		$certificate_full = wp_get_attachment_image(
			$certificate_id,
			'full',
			false,
			array(
				'class' => 'certificate-modal__image',
			)
		);
	}
}


$related_ids = wc_get_related_products( $product_id, 4 );
?>

<article
	id="product-<?php the_ID(); ?>"
	<?php wc_product_class( 'cable-product', $product ); ?>
>

	<section class="cable-product__hero">
		<div class="container cable-product__hero-inner">

			<div class="cable-product__hero-content">
				<div class="cable-product__breadcrumbs">
					<?php
					woocommerce_breadcrumb(
						array(
							'delimiter'   => '<span>/</span>',
							'wrap_before' => '<nav aria-label="Хлебные крошки">',
							'wrap_after'  => '</nav>',
						)
					);
					?>
				</div>

				<?php if ( $category ) : ?>
					<span class="cable-product__category">
						<?php echo esc_html( $category ); ?>
					</span>
				<?php endif; ?>

				<h1 class="cable-product__title">
					<?php the_title(); ?>
				</h1>

				<?php if ( $gost ) : ?>
					<div class="cable-product__gost">
						<?php echo esc_html( $gost ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $short_description ) : ?>
					<div class="cable-product__lead">
						<?php echo wp_kses_post( wpautop( $short_description ) ); ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="cable-product__hero-media">
				<div class="cable-product__image-glow" aria-hidden="true"></div>

				<?php
				if ( $image_id ) {
					echo wp_get_attachment_image(
						$image_id,
						'full',
						false,
						array(
							'class'         => 'cable-product__image',
							'fetchpriority' => 'high',
						)
					);
				} else {
					echo wc_placeholder_img( 'woocommerce_single' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
			</div>

		</div>
	</section>

	<section class="cable-product__details">
		<div class="container">

			<div class="cable-product__content-grid">

				<section class="cable-product__section cable-product__section--marking">
					<span class="cable-product__section-number">01</span>

					<h2>Расшифровка маркировки</h2>

					<div class="cable-product__section-line"></div>

					<?php if ( $marking ) : ?>
						<div class="cable-product__marking">
							<?php
							$marking_lines = preg_split(
								'/\r\n|\r|\n/',
								$marking
							);

							foreach ( $marking_lines as $line ) {
								$line = trim( $line );

								if ( '' === $line ) {
									continue;
								}

								$parts = preg_split(
									'/\s*[—–-]\s*/u',
									$line,
									2
								);

								echo '<div class="cable-product__marking-row">';

								if ( count( $parts ) > 1 ) {
									echo '<strong>' . esc_html( $parts[0] ) . '</strong>';
									echo '<span>' . esc_html( $parts[1] ) . '</span>';
								} else {
									echo '<span>' . esc_html( $line ) . '</span>';
								}

								echo '</div>';
							}
							?>
						</div>
					<?php else : ?>
						<p class="cable-product__empty">
							Заполните поле «Расшифровка маркировки» в редакторе товара.
						</p>
					<?php endif; ?>
				</section>

<section class="cable-product__section cable-product__section--description">
	<span class="cable-product__section-number">02</span>

	<h2>Описание</h2>

	<div class="cable-product__section-line"></div>

	<div class="cable-product__text">
		<?php if ( ! empty( $description ) ) : ?>
			<?php echo wp_kses_post( wpautop( $description ) ); ?>
		<?php else : ?>
			<p class="cable-product__empty">
				Описание для этого кабеля пока не добавлено.
			</p>
		<?php endif; ?>
	</div>
</section>

<section class="cable-product__section cable-product__section--application">
	<div class="cable-product__application-head">
		<span class="cable-product__section-number">03</span>

		<div>
			<h2>Применение</h2>
			<div class="cable-product__section-line"></div>
		</div>
	</div>

	<div class="cable-product__text cable-product__application-text">
		<?php if ( ! empty( $application ) ) : ?>
			<?php echo wp_kses_post( wpautop( $application ) ); ?>
		<?php else : ?>
			<p class="cable-product__empty">
				Информация о применении пока не добавлена.
			</p>
		<?php endif; ?>
	</div>
</section>


		 <aside class="cable-product__certificate">
	<div class="cable-product__certificate-head">
		<div>
			<span class="cable-product__certificate-label">
				Документация
			</span>

			<h2>Сертификат</h2>
		</div>

		 
	</div>

	<?php if ( $certificate_url ) : ?>

		<button
			class="certificate-card"
			type="button"
			data-certificate-open
			aria-controls="<?php echo esc_attr( $certificate_modal_id ); ?>"
			aria-label="Посмотреть сертификат"
		>
			<span class="certificate-card__document">

				<?php if ( $certificate_is_pdf ) : ?>
					<span class="certificate-card__pdf">
						<span class="certificate-card__pdf-type">PDF</span>
						<span>Сертификат соответствия</span>
					</span>
				<?php elseif ( $certificate_image ) : ?>
					<?php
					echo $certificate_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				<?php endif; ?>

				<span class="certificate-card__zoom">
					<?php echo cssc_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
			</span>

			<span class="certificate-card__footer">
				<span>
					<strong>Сертификат соответствия</strong>
					<small>Нажмите для просмотра</small>
				</span>

				<span class="certificate-card__arrow">
					<?php echo cssc_arrow_btn_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
			</span>
		</button>

		 
<div class="cable-product__certificate-actions">

	<a
		class="cable-product__action cable-product__action--weight"
		href="<?php echo esc_url( home_url( '/kalkulyator-vesa/' ) ); ?>"
	>
		<span>Рассчитать вес</span>

		<?php echo cssc_arrow_btn_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</a>

<button
	class="cable-product__action cable-product__action--request"
	type="button"
	data-req="<?php echo esc_attr( $product->get_name() ); ?>"
>
	<span>Оформить заявку</span>

	<?php echo cssc_arrow_btn_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</button>


</div>

	<?php else : ?>

		<div class="certificate-card__empty">
			<span>Документ пока не загружен</span>
		</div>

	<?php endif; ?>
			 <?php if ( $certificate_url ) : ?>
	<div
		id="<?php echo esc_attr( $certificate_modal_id ); ?>"
		class="certificate-modal"
		data-certificate-modal
		aria-hidden="true"
		hidden
	>
		<button
			class="certificate-modal__backdrop"
			type="button"
			data-certificate-close
			aria-label="Закрыть сертификат"
		></button>

		<div
			class="certificate-modal__panel"
			role="dialog"
			aria-modal="true"
			aria-label="Просмотр сертификата"
		>
			<div class="certificate-modal__head">
				<div>
					<span>CSS Cable</span>
					<strong>Сертификат соответствия</strong>
				</div>

				<button
					class="certificate-modal__close"
					type="button"
					data-certificate-close
					aria-label="Закрыть"
				>
					<?php echo cssc_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</button>
			</div>

			<div class="certificate-modal__content">
				<?php if ( $certificate_is_pdf ) : ?>
					<iframe
						class="certificate-modal__pdf"
						src="<?php echo esc_url( $certificate_url ); ?>#toolbar=0"
						title="Сертификат соответствия"
						loading="lazy"
					></iframe>
				<?php else : ?>
					<?php
					echo $certificate_full; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				<?php endif; ?>
			</div>

			<div class="certificate-modal__footer">
				<span>Официальный документ CSS Cable</span>

				<a
					href="<?php echo esc_url( $certificate_url ); ?>"
					download
				>
					Скачать
				</a>
			</div>
		</div>
	</div>
<?php endif; ?>

</aside>


			</div>
		</div>
	</section>

	<?php if ( $related_ids ) : ?>
		<section class="cable-product__related">
			<div class="container">

				<div class="cable-product__related-head">
					<div>
						<span class="cable-product__related-eyebrow">
							Каталог
						</span>

						<h2>
							<?php
							echo $category
								? esc_html( $category )
								: esc_html__( 'Похожие товары', 'css-cable' );
							?>
						</h2>
					</div>

					<a
						class="cable-product__catalog-link"
						href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"
					>
						<span>Вся продукция</span>
						<?php echo cssc_arrow_btn_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
				</div>

				<div class="cable-product__related-grid">
					<?php foreach ( $related_ids as $related_id ) : ?>
						<?php
						$related_product = wc_get_product( $related_id );

						if ( ! $related_product || ! $related_product->is_visible() ) {
							continue;
						}
						?>

						<a
							class="cable-related-card"
							href="<?php echo esc_url( $related_product->get_permalink() ); ?>"
						>
							<span class="cable-related-card__image">
								<?php
								echo $related_product->get_image(
									'woocommerce_thumbnail',
									array(
										'loading' => 'lazy',
									)
								); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
							</span>

							<span class="cable-related-card__body">
								<strong>
									<?php echo esc_html( $related_product->get_name() ); ?>
								</strong>

								<?php if ( $related_product->get_short_description() ) : ?>
									<span>
										<?php
										echo esc_html(
											wp_trim_words(
												wp_strip_all_tags(
													$related_product->get_short_description()
												),
												16
											)
										);
										?>
									</span>
								<?php endif; ?>

								<span class="cable-related-card__more">
									Подробнее
									<?php echo cssc_arrow_btn_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</span>
							</span>
						</a>
					<?php endforeach; ?>
				</div>

			</div>
		</section>
	<?php endif; ?>

</article>

<?php do_action( 'woocommerce_after_single_product' ); ?>
