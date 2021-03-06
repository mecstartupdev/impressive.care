<?php

get_header();

$show_default_title = get_post_meta( get_the_ID(), '_et_pb_show_title', true );

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

$offer_sections = get_field('multioffers');

?>

<div id="main-content">
	<?php
		if ( et_builder_is_product_tour_enabled() ):
			// load fullwidth page in Product Tour mode
			while ( have_posts() ): the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
					<div class="entry-content">
					<?php
						the_content();
					?>
					</div> <!-- .entry-content -->

				</article> <!-- .et_pb_post -->

		<?php endwhile;
		else:
	?>
	<div class="container">
		<div id="content-area" class="clearfix">
			<?php // top banner
				if(!$offer_sections){
					$banner = get_field('article_top_banner', 'option');
					if($banner != '') {
						if(get_field('article_top_banner_publish', 'option') || current_user_can('administrator')) {
							echo '<div class="top-banner">'.$banner.'</div>';
						}
					}
				}
			?>
			<div id="left-area"<?php if($offer_sections)echo ' class="is-mop"'; ?>>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php if (et_get_option('divi_integration_single_top') <> '' && et_get_option('divi_integrate_singletop_enable') == 'on') echo(et_get_option('divi_integration_single_top')); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
					<?php if ( ( 'off' !== $show_default_title && $is_page_builder_used ) || ! $is_page_builder_used ) { ?>
						<div class="et_post_meta_wrapper">
							<div class="post-category"><?php custom_list_categories(); ?></div>
							<h1 class="entry-title"><?php the_title(); ?></h1>

						<?php
							if ( ! post_password_required() ) :

								et_divi_post_meta();

								/*$thumb = '';

								$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

								$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
								$classtext = 'et_featured_image';
								$titletext = get_the_title();
								$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
								$thumb = $thumbnail["thumb"];

								$post_format = et_pb_post_format();

								if ( 'video' === $post_format && false !== ( $first_video = et_get_first_video() ) ) {
									printf(
										'<div class="et_main_video_container">
											%1$s
										</div>',
										$first_video
									);
								} else if ( ! in_array( $post_format, array( 'gallery', 'link', 'quote' ) ) && 'on' === et_get_option( 'divi_thumbnails', 'on' ) && '' !== $thumb ) {
									print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height );
								} else if ( 'gallery' === $post_format ) {
									et_pb_gallery_images();
								}*/
								?>
								<div class="single-featured-image">
									<?php the_post_thumbnail( '845x321', array('class' => 'et_featured_image') ); ?>
								</div>
							<?php
								$text_color_class = et_divi_get_post_text_color();

								$inline_style = et_divi_get_post_bg_inline_style();

								switch ( $post_format ) {
									case 'audio' :
										$audio_player = et_pb_get_audio_player();

										if ( $audio_player ) {
											printf(
												'<div class="et_audio_content%1$s"%2$s>
													%3$s
												</div>',
												esc_attr( $text_color_class ),
												$inline_style,
												$audio_player
											);
										}

										break;
									case 'quote' :
										printf(
											'<div class="et_quote_content%2$s"%3$s>
												%1$s
											</div> <!-- .et_quote_content -->',
											et_get_blockquote_in_content(),
											esc_attr( $text_color_class ),
											$inline_style
										);

										break;
									case 'link' :
										printf(
											'<div class="et_link_content%3$s"%4$s>
												<a href="%1$s" class="et_link_main_url">%2$s</a>
											</div> <!-- .et_link_content -->',
											esc_url( et_get_link_url() ),
											esc_html( et_get_link_url() ),
											esc_attr( $text_color_class ),
											$inline_style
										);

										break;
								}

							endif;
						?>
					</div> <!-- .et_post_meta_wrapper -->
				<?php  } ?>

					<div class="entry-content">
					<?php
						do_action( 'et_before_content' );

						the_content();

						wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
					?>
					</div> <!-- .entry-content -->
					<div class="et_post_meta_wrapper">
					<?php
					if ( et_get_option('divi_468_enable') == 'on' ){
						echo '<div class="et-single-post-ad">';
						if ( et_get_option('divi_468_adsense') <> '' ) echo( et_get_option('divi_468_adsense') );
						else { ?>
							<a href="<?php echo esc_url(et_get_option('divi_468_url')); ?>"><img src="<?php echo esc_attr(et_get_option('divi_468_image')); ?>" alt="468" class="foursixeight" /></a>
				<?php 	}
						echo '</div> <!-- .et-single-post-ad -->';
					}
				?>

					<?php if (et_get_option('divi_integration_single_bottom') <> '' && et_get_option('divi_integrate_singlebottom_enable') == 'on') echo(et_get_option('divi_integration_single_bottom')); ?>

					<?php
						if ( ( comments_open() || get_comments_number() ) && 'on' == et_get_option( 'divi_show_postcomments', 'on' ) ) {
							comments_template( '', true );
						}
					?>
					</div> <!-- .et_post_meta_wrapper -->
					<?php if($slideshow_link = get_field('slideshow_link')) echo '<div class="slide-next"><a class="cs-btn" href="'.$slideshow_link.'">START SLIDESHOW</a></div>'; ?>
				</article> <!-- .et_pb_post -->
				<?php
					if($offer_sections) {
						foreach($offer_sections as $section) {
						echo '<section>';
						if( !empty($section['section_title']) ) echo '<h2 class="entry-title section-title">'.$section['section_title'].'</h2>';
						foreach($section['offers'] as $offer) {
							$call_to_action_link = get_field('call_to_action_link', $offer->ID);
							if( get_field('amazon_in_stock', $offer->ID) == 'no' ) continue;
							$titletext = get_the_title($offer->ID);
				?>
							<article id="offer-<?php echo $offer->ID; ?>" <?php post_class( array('et_pb_post', 'offer-post') ); ?>>
								<h2 class="entry-title offer-title"><a target="_blank" href="<?php echo $call_to_action_link; ?>"><?php echo /*$i .'. '.*/ $titletext; ?></a></h2>
								<?php
										//$thumb = '';
										/*$classtext = 'et_featured_image';
										$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, false, $offer );
										$thumb = $thumbnail["thumb"];
										if($thumb) print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height );*/
									?>
								<div class="single-featured-image">
									<a target="_blank" href="<?php echo $call_to_action_link; ?>">
										<?php echo get_the_post_thumbnail( $offer->ID, '9999x350', array('class' => 'et_featured_image') ); ?>
									</a>
								</div>
								<div class="entry-content">
								<?php
									echo apply_filters('the_content', $offer->post_content);
								?>
								</div> <!-- .entry-content -->
							</article> <!-- .et_pb_post -->
							<?php if($call_to_action_link) { ?>
								<div class="slide-next offer-cta"><a class="cs-btn" target="_blank" href="<?php echo $call_to_action_link; ?>"><?php echo get_field('call_to_action_title', $offer->ID); ?> >></a></div>
							<?php } ?>
						<?php } ?>
						</section>
						<?php }	} ?>
			<?php endwhile; ?>
			<?php if($post->post_type == 'offer' && ($call_to_action_title = get_field('call_to_action_title'))) { ?>
				<div class="slide-next"><a class="cs-btn" href="<?php echo get_field('call_to_action_link'); ?>"><?php echo $call_to_action_title ?> >></a></div>
			<?php } ?>
			</div> <!-- #left-area -->

			<?php //get_sidebar(); ?>
			<?php if(!$offer_sections){ ?>
			<div class="right-banner">
			<?php 
				$right_banner = get_field('article_right_banner', 'option');
				if($right_banner != '') {
					if(get_field('article_right_banner_publish', 'option') || current_user_can('administrator')) {
						echo $right_banner;
						$right_banner_show = true;
					}
				}
				$right_banner_2 = get_field('article_right_2_banner', 'option');
				if($right_banner_2 != '') {
					if(get_field('article_right_2_banner_publish', 'option') || current_user_can('administrator')) {
						echo $right_banner_2;
						$right_banner2_show = true;
					}
				}
				if(!$right_banner_show && !$right_banner2_show) {
					echo '&nbsp;';
				}
			?>
			</div>
			<?php } ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
	<?php endif; ?>
</div> <!-- #main-content -->

<?php

get_footer();
