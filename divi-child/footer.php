<?php
/**
 * Fires after the main content, before the footer is output.
 *
 * @since 3.10
 */
do_action( 'et_after_main_content' );

if ( 'on' == et_get_option( 'divi_back_to_top', 'false' ) ) : ?>

	<span class="et_pb_scroll_top et-pb-icon"></span>

<?php endif;

if ( ! is_page_template( 'page-template-blank.php' ) ) : ?>

			<footer id="main-footer">
				<?php get_sidebar( 'footer' ); ?>


		<?php
			if ( has_nav_menu( 'footer-menu' ) ) : ?>

				<div id="et-footer-nav">
					<div class="container">
						<span class="footer-name"><?php echo esc_attr( get_bloginfo( 'name' ) ); ?></span>
						<span class="footer-copy">Copyright <?php echo date('Y'); ?>.</span>
						<?php
							wp_nav_menu( array(
								'theme_location' => 'footer-menu',
								'menu_class'     => 'bottom-nav',
								'container'      => '',
								'before'          => '<span> | </span>',
								'after'           => '',
								'link_before'     => '',
								'link_after'      => '',
							) );
						?>
					</div>
				</div> <!-- #et-footer-nav -->

			<?php endif; ?>

				<div id="footer-bottom">
					<div class="container clearfix">
				<?php
					if ( false !== et_get_option( 'show_footer_social_icons', true ) ) {
						get_template_part( 'includes/social_icons', 'footer' );
					}

					echo et_get_footer_credits();
				?>
					</div>	<!-- .container -->
				</div>
			</footer> <!-- #main-footer -->
		</div> <!-- #et-main-area -->

<?php endif; // ! is_page_template( 'page-template-blank.php' ) ?>

	</div> <!-- #page-container -->
	<div class="subscribe-popup">
		<div class="subscribe-popup-content">
			<div class="subscribe-close">×</div>
			<div class="subscribe-logo">avima.</div>
			<div class="subscribe-pretext">Enter your email address to receive updates on new articles about beauty, wellness and elective surgery.</div>
			<?php es_subbox( $namefield = "NO", $desc = "", $group = "test" ); ?>
			<div class="subscribe-agree">By clicking "Subscribe", I agree to Avima's <a href="/terms-of-use" target="_blank">Terms and Conditions</a> and <a href="/privacy-policy" target="_blank">Privacy Policy</a>. I also agree to receive emails from Avima and I understand I can opt out of the subscription at any time.</div>
		</div>
	</div>
	<?php wp_footer(); ?>
<script type="text/javascript" src="https://s.skimresources.com/js/124035X1584843.skimlinks.js"></script>
</body>
</html>
