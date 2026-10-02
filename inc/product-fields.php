<?php
/**
 * Дополнительные поля и оформление страницы товара CSS Cable.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Подключение стилей только на странице товара.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		if ( function_exists( 'is_product' ) && is_product() ) {
			$css_file = CSSC_DIR . '/assets/css/single-product.css';

wp_enqueue_style(
	'cssc-single-product',
	CSSC_URI . '/assets/css/single-product.css',
	array( 'cssc-main', 'cssc-woo' ),
	file_exists( $css_file ) ? filemtime( $css_file ) : CSSC_VER
);
			$js_file = CSSC_DIR . '/assets/js/single-product.js';

wp_enqueue_script(
	'cssc-single-product',
	CSSC_URI . '/assets/js/single-product.js',
	array(),
	file_exists( $js_file ) ? filemtime( $js_file ) : CSSC_VER,
	true
);

		}
	},
	30
);

/**
 * Отдельная вкладка CSS Cable в редакторе товара.
 */
add_filter(
	'woocommerce_product_data_tabs',
	function ( $tabs ) {
		$tabs['cssc_product'] = array(
			'label'    => 'CSS Cable',
			'target'   => 'cssc_product_data',
			'class'    => array(),
			'priority' => 75,
		);

		return $tabs;
	}
);

/**
 * Поля вкладки CSS Cable.
 */
add_action(
	'woocommerce_product_data_panels',
	function () {
		global $post;

		$certificate_id = absint(
			get_post_meta( $post->ID, '_cssc_certificate_id', true )
		);

		$certificate_image = $certificate_id
			? wp_get_attachment_image_url( $certificate_id, 'medium' )
			: '';
		?>
		<div
			id="cssc_product_data"
			class="panel woocommerce_options_panel hidden"
		>
			<div class="options_group">

				<?php
				woocommerce_wp_text_input(
					array(
						'id'          => '_cssc_gost',
						'label'       => 'ГОСТ',
						'placeholder' => 'Например: ГОСТ 1508-78',
						'desc_tip'    => true,
						'description' => 'Стандарт, который выводится под названием товара.',
					)
				);

				woocommerce_wp_textarea_input(
					array(
						'id'          => '_cssc_marking',
						'label'       => 'Расшифровка маркировки',
						'placeholder' => "К — кабель контрольный.\nВ — изоляция из ПВХ-пластиката.",
						'desc_tip'    => true,
						'description' => 'Каждую строку можно начинать с обозначения буквы.',
						'rows'        => 8,
					)
				);

				woocommerce_wp_textarea_input(
					array(
						'id'          => '_cssc_application',
						'label'       => 'Применение',
						'placeholder' => 'Опишите область применения кабеля.',
						'desc_tip'    => true,
						'description' => 'Текст для блока «Применение».',
						'rows'        => 7,
					)
				);
				?>

			</div>

			<div class="options_group">
				<p class="form-field">
					<label for="_cssc_certificate_id">
						Сертификат
					</label>

					<input
						id="_cssc_certificate_id"
						type="hidden"
						name="_cssc_certificate_id"
						value="<?php echo esc_attr( $certificate_id ); ?>"
					>

					<span
						class="cssc-certificate-admin"
						style="display:inline-block;max-width:220px"
					>
						<span
							class="cssc-certificate-admin__preview"
							style="display:block;margin-bottom:10px"
						>
							<?php if ( $certificate_image ) : ?>
								<img
									src="<?php echo esc_url( $certificate_image ); ?>"
									alt=""
									style="display:block;max-width:180px;height:auto"
								>
							<?php endif; ?>
						</span>

						<button
							type="button"
							class="button cssc-certificate-select"
						>
							Выбрать сертификат
						</button>

						<button
							type="button"
							class="button cssc-certificate-remove"
							<?php echo $certificate_id ? '' : 'style="display:none"'; ?>
						>
							Удалить
						</button>
					</span>
				</p>
			</div>
		</div>
		<?php
	}
);

/**
 * Сохранение дополнительных полей.
 */
add_action(
	'woocommerce_admin_process_product_object',
	function ( $product ) {
		$text_fields = array(
			'_cssc_gost',
		);

		foreach ( $text_fields as $field ) {
			$value = isset( $_POST[ $field ] )
				? sanitize_text_field( wp_unslash( $_POST[ $field ] ) )
				: '';

			$product->update_meta_data( $field, $value );
		}

		$textarea_fields = array(
			'_cssc_marking',
			'_cssc_application',
		);

		foreach ( $textarea_fields as $field ) {
			$value = isset( $_POST[ $field ] )
				? sanitize_textarea_field( wp_unslash( $_POST[ $field ] ) )
				: '';

			$product->update_meta_data( $field, $value );
		}

		$certificate_id = isset( $_POST['_cssc_certificate_id'] )
			? absint( $_POST['_cssc_certificate_id'] )
			: 0;

		$product->update_meta_data(
			'_cssc_certificate_id',
			$certificate_id
		);
	}
);

/**
 * Media Library для выбора сертификата.
 */
add_action(
	'admin_enqueue_scripts',
	function ( $hook ) {
		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		$screen = get_current_screen();

		if ( ! $screen || 'product' !== $screen->post_type ) {
			return;
		}

		wp_enqueue_media();
	}
);

add_action(
	'admin_footer-post.php',
	'cssc_certificate_admin_script'
);

add_action(
	'admin_footer-post-new.php',
	'cssc_certificate_admin_script'
);

/**
 * Скрипт выбора сертификата в админке.
 */
function cssc_certificate_admin_script() {
	$screen = get_current_screen();

	if ( ! $screen || 'product' !== $screen->post_type ) {
		return;
	}
	?>
	<script>
	(function ($) {
		'use strict';

		var frame;

		$(document).on('click', '.cssc-certificate-select', function (event) {
			event.preventDefault();

			if (frame) {
				frame.open();
				return;
			}

			frame = wp.media({
				title: 'Выберите сертификат',
				button: {
					text: 'Использовать сертификат'
				},
				multiple: false
			});

			frame.on('select', function () {
				var attachment = frame
					.state()
					.get('selection')
					.first()
					.toJSON();

				var preview = attachment.url;

				if (
					attachment.sizes &&
					attachment.sizes.medium
				) {
					preview = attachment.sizes.medium.url;
				} else if (
					attachment.sizes &&
					attachment.sizes.thumbnail
				) {
					preview = attachment.sizes.thumbnail.url;
				}

				$('#_cssc_certificate_id').val(attachment.id);

				$('.cssc-certificate-admin__preview').html(
					'<img src="' +
					preview +
					'" alt="" style="display:block;max-width:180px;height:auto">'
				);

				$('.cssc-certificate-remove').show();
			});

			frame.open();
		});

		$(document).on('click', '.cssc-certificate-remove', function (event) {
			event.preventDefault();

			$('#_cssc_certificate_id').val('');
			$('.cssc-certificate-admin__preview').empty();
			$(this).hide();
		});
	})(jQuery);
	</script>
	<?php
}
/**
 * Убираем стандартные хлебные крошки WooCommerce.
 * В шаблоне товара уже есть собственные хлебные крошки.
 */
add_action(
	'wp',
	function () {
		if ( function_exists( 'is_product' ) && is_product() ) {
			remove_action(
				'woocommerce_before_main_content',
				'woocommerce_breadcrumb',
				20
			);
		}
	}
);
