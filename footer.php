<?php defined( 'ABSPATH' ) || exit;
$phone = cssc_opt( 'cssc_phone' );
$email = cssc_opt( 'cssc_email' );
$wa    = preg_replace( '/\D+/', '', cssc_opt( 'cssc_whatsapp' ) );
?>
</main>
<footer class="site-footer">
	<div class="contact-bar">
		<div class="container contact-bar__in">
			<div class="contact-bar__logo"><?php echo cssc_logo(); // phpcs:ignore ?></div>
			<div class="contact-bar__items">
				<?php if ( $phone ) : ?><a class="contact-bar__item" href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $phone ) ); ?>"><span class="round-ico"><?php echo cssc_icon( 'phone' ); // phpcs:ignore ?></span><?php echo esc_html( $phone ); ?></a><?php endif; ?>
				<?php if ( $email ) : ?><a class="contact-bar__item" href="mailto:<?php echo esc_attr( $email ); ?>"><span class="round-ico"><?php echo cssc_icon( 'mail' ); // phpcs:ignore ?></span><?php echo esc_html( $email ); ?></a><?php endif; ?>
			</div>
		</div>
		<?php if ( $wa ) : ?><a class="contact-bar__wa" href="https://wa.me/<?php echo esc_attr( $wa ); ?>" target="_blank" rel="noopener" aria-label="WhatsApp"><?php echo cssc_icon( 'whatsapp' ); // phpcs:ignore ?></a><?php endif; ?>
	</div>
	<div class="footer-main">
		<div class="container footer-main__grid">
			<div class="footer-col footer-col--tagline"><?php echo wp_kses_post( cssc_opt( 'cssc_tagline' ) ); ?></div>
			<div class="footer-col">
				<h4>Продукция</h4>
				<?php
				if ( has_nav_menu( 'footer_products' ) ) {
					wp_nav_menu( array( 'theme_location' => 'footer_products', 'container' => false, 'depth' => 1 ) );
				} else {
					echo '<ul class="menu"><li><a href="' . esc_url( cssc_cat_url( 'silovoj-kabel' ) ) . '">Силовой кабель</a></li><li><a href="' . esc_url( cssc_cat_url( 'kontrolnyj-kabel' ) ) . '">Контрольный кабель</a></li></ul>';
				}
				?>
			</div>
			<div class="footer-col">
				<h4>Компания</h4>
				<?php
				if ( has_nav_menu( 'footer_company' ) ) {
					wp_nav_menu( array( 'theme_location' => 'footer_company', 'container' => false, 'depth' => 1 ) );
				} else {
					echo '<ul class="menu"><li><a href="' . esc_url( home_url( '/o-kompanii/' ) ) . '">О компании</a></li><li><a href="' . esc_url( home_url( '/novosti/' ) ) . '">Новости</a></li><li><a href="' . esc_url( home_url( '/kontakty/' ) ) . '">Контакты</a></li></ul>';
				}
				?>
			</div>
			<div class="footer-col footer-col--addr">
				<div class="addr"><span class="round-ico"><?php echo cssc_icon( 'pin' ); // phpcs:ignore ?></span><div><b>Отдел продаж:</b><span><?php echo esc_html( cssc_opt( 'cssc_sales_addr' ) ); ?></span></div></div>
				<div class="addr"><span class="round-ico"><?php echo cssc_icon( 'factory' ); // phpcs:ignore ?></span><div><b>Производственная площадка:</b><span><?php echo esc_html( cssc_opt( 'cssc_prod_addr' ) ); ?></span></div></div>
			</div>
		</div>
		<?php if ( cssc_opt( 'cssc_credit' ) ) : ?><div class="container footer-credit"><?php echo wp_kses_post( cssc_opt( 'cssc_credit' ) ); ?></div><?php endif; ?>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
