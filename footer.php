<?php
defined( 'ABSPATH' ) || exit;

$phone   = cssc_opt( 'cssc_phone' );
$email   = cssc_opt( 'cssc_email' );
$address = cssc_opt( 'cssc_header_addr' );
$wa      = preg_replace( '/\D+/', '', cssc_opt( 'cssc_whatsapp' ) );
?>

</main>

<div
	class="floating-bar"
	role="region"
	aria-label="<?php esc_attr_e( 'Навигация и контакты', 'css-cable' ); ?>"
>
	<div class="floating-bar__inner">

		<div class="floating-bar__logo">
			<?php echo cssc_logo(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>

		<nav
			class="floating-bar__navigation"
			aria-label="<?php esc_attr_e( 'Нижнее меню', 'css-cable' ); ?>"
		>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'fallback_cb'    => 'cssc_menu_fallback',
					'depth'          => 1,
					'menu_class'     => 'floating-bar__menu',
				)
			);
			?>
		</nav>

		<div class="floating-bar__contacts">

			<?php if ( $phone ) : ?>
				<a
					class="floating-bar__contact"
					href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $phone ) ); ?>"
				>
					<span class="floating-bar__icon">
						<?php echo cssc_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>

					<span class="floating-bar__text">
						<?php echo esc_html( $phone ); ?>
					</span>
				</a>
			<?php endif; ?>

			<?php if ( $email ) : ?>
				<a
					class="floating-bar__contact"
					href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>"
				>
					<span class="floating-bar__icon">
						<?php echo cssc_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>

					<span class="floating-bar__text">
						<?php echo esc_html( antispambot( $email ) ); ?>
					</span>
				</a>
			<?php endif; ?>

			<?php if ( $address ) : ?>
				<a
					class="floating-bar__contact floating-bar__contact--address"
					href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>"
				>
					<span class="floating-bar__icon">
						<?php echo cssc_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>

					<span class="floating-bar__text">
						<?php echo esc_html( $address ); ?>
					</span>
				</a>
			<?php endif; ?>

		</div>

		<?php if ( $wa ) : ?>
			<a
				class="floating-bar__whatsapp"
				href="<?php echo esc_url( 'https://wa.me/' . $wa ); ?>"
				target="_blank"
				rel="noopener noreferrer"
				aria-label="<?php esc_attr_e( 'Написать в WhatsApp', 'css-cable' ); ?>"
			>
				<?php echo cssc_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
		<?php endif; ?>

	</div>
</div>

<footer class="site-footer">
	<div class="footer-main">
		<div class="container footer-main__grid">

			<div class="footer-col footer-col--tagline">
				<?php echo wp_kses_post( cssc_opt( 'cssc_tagline' ) ); ?>
			</div>

			<div class="footer-col">
				<h4><?php esc_html_e( 'Продукция', 'css-cable' ); ?></h4>

				<?php
				if ( has_nav_menu( 'footer_products' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'footer_products',
							'container'      => false,
							'depth'          => 1,
						)
					);
				} else {
					echo '<ul class="menu">';
					echo '<li><a href="' . esc_url( cssc_cat_url( 'silovoj-kabel' ) ) . '">Силовой кабель</a></li>';
					echo '<li><a href="' . esc_url( cssc_cat_url( 'kontrolnyj-kabel' ) ) . '">Контрольный кабель</a></li>';
					echo '</ul>';
				}
				?>
			</div>

			<div class="footer-col">
				<h4><?php esc_html_e( 'Компания', 'css-cable' ); ?></h4>

				<?php
				if ( has_nav_menu( 'footer_company' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'footer_company',
							'container'      => false,
							'depth'          => 1,
						)
					);
				} else {
					echo '<ul class="menu">';
					echo '<li><a href="' . esc_url( home_url( '/o-kompanii/' ) ) . '">О компании</a></li>';
					echo '<li><a href="' . esc_url( home_url( '/novosti/' ) ) . '">Новости</a></li>';
					echo '<li><a href="' . esc_url( home_url( '/kontakty/' ) ) . '">Контакты</a></li>';
					echo '</ul>';
				}
				?>
			</div>

			<div class="footer-col footer-col--addr">

				<div class="addr">
					<span class="round-ico">
						<?php echo cssc_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>

					<div>
						<b><?php esc_html_e( 'Отдел продаж:', 'css-cable' ); ?></b>
						<span><?php echo esc_html( cssc_opt( 'cssc_sales_addr' ) ); ?></span>
					</div>
				</div>

				<div class="addr">
					<span class="round-ico">
						<?php echo cssc_icon( 'factory' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>

					<div>
						<b><?php esc_html_e( 'Производственная площадка:', 'css-cable' ); ?></b>
						<span><?php echo esc_html( cssc_opt( 'cssc_prod_addr' ) ); ?></span>
					</div>
				</div>

			</div>
		</div>

		<?php if ( cssc_opt( 'cssc_credit' ) ) : ?>
			<div class="container footer-credit">
				<?php echo wp_kses_post( cssc_opt( 'cssc_credit' ) ); ?>
			</div>
		<?php endif; ?>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
