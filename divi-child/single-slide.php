<?php

get_header();

$show_default_title = get_post_meta( get_the_ID(), '_et_pb_show_title', true );

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

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
			<?php while ( have_posts() ) : the_post(); ?>
				<?php if (et_get_option('divi_integration_single_top') <> '' && et_get_option('divi_integrate_singletop_enable') == 'on') echo(et_get_option('divi_integration_single_top')); ?>
				<div class="title-content">
					<?php /* ?><div class="post-category"><?php echo get_the_title($post->post_parent); ?></div><?php */ ?>
					<h1 class="entry-title"><?php the_title(); ?></h1>
				</div>
				<div class="threecolumn-left">
					<?php 
						$banner = get_field('left_banner', 'option');
						if($banner != '') {
							if(get_field('left_banner_publish', 'option') || current_user_can('administrator')) {
								echo $banner;
								$banner_show = true;
							}
						}
						if(!$banner_show) echo '&nbsp;';
					?>
				</div>
				<div class="threecolumn-middle">
				<?php // top banner
					$banner = get_field('top_banner', 'option');
					if($banner != '') {
						if(get_field('top_banner_publish', 'option') || current_user_can('administrator')) {
							echo '<div class="top-banner">'.$banner.'</div>';
						}
					}
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
					<?php if ( ( 'off' !== $show_default_title && $is_page_builder_used ) || ! $is_page_builder_used ) { ?>
						<!--div class="et_post_meta_wrapper"-->
						<?php
							if ( ! post_password_required() ) :

								et_divi_post_meta();

								$thumb = '';

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
								}
							?>

							<?php
							endif;
						?>
					<!--/div> <!-- .et_post_meta_wrapper -->
				<?php  } ?>
					<?php // top content banner
						$banner = get_field('top_content_banner', 'option');
						if($banner != '') {
							if(get_field('top_content_banner_publish', 'option') || current_user_can('administrator')) {
								echo '<div class="content-top-banner">'.$banner.'</div>';
							}
						}
						$banner = get_field('mobile_banner', 'option');
						if($banner != '') {
							if(get_field('mobile_banner_publish', 'option') || current_user_can('administrator')) {
								echo '<div class="mobile-banner">'.$banner.'</div>';
							}
						}
					?>
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
					<?php 
						$content_banners = '';
						$content_left_banner = get_field('content_left_banner', 'option');
						if($content_left_banner != '') {
							if(get_field('content_left_banner_publish', 'option') || current_user_can('administrator')) {
								$content_banners .= '<div class="content-left-banner">'.$content_left_banner.'</div>';
							} 
						}
						$content_right_banner = get_field('content_right_banner', 'option');
						if($content_right_banner != '') {
							if(get_field('content_right_banner_publish', 'option') || current_user_can('administrator')) {
								$content_banners .= '<div class="content-right-banner">'.$content_right_banner.'</div>';
							} 
						}
						if($content_banners != '') echo '<div class="content-banners">'.$content_banners.'</div>';
					?>
				</article> <!-- .et_pb_post -->

			<?php endwhile; ?>
			<?php	
				if(!$post->post_parent) {
				$next_post = get_posts(array(
					'post_type' => 'slide',
					'post_parent' => $post->ID,
					'menu_order' => 1
				));
				if($next_post) {
			?>
				<div class="slide-next"><a class="cs-btn" href="<?php echo get_permalink($next_post[0]->ID); ?>">START SLIDESHOW</a></div>
			<?php	} } else {
				$next_post = get_posts(array(
					'post_type' => 'slide',
					'post_parent' => $post->post_parent,
					'menu_order' => $post->menu_order + 1
				));
				if($next_post) {
			?>
				<div class="slide-next"><a class="cs-btn" href="<?php echo get_permalink($next_post[0]->ID); ?>">NEXT SLIDE >></a></div>
			<?php } } ?>
			<?php //bottom banner
				$banner = get_field('bottom_banner', 'option');
				if($banner != '') {
					if(get_field('bottom_banner_publish', 'option') || current_user_can('administrator')) {
						echo '<div class="bottom-banner">'.$banner.'</div>';
					}
				}
			?>
			</div> <!-- threecolumn-middle -->
			<div class="threecolumn-right">
			<?php 
				$right_banner = get_field('right_banner', 'option');
				if($right_banner != '') {
					if(get_field('right_banner_publish', 'option') || current_user_can('administrator')) {
						echo $right_banner;
						$right_banner_show = true;
					}
				}
				$right_banner_2 = get_field('right_banner_2', 'option');
				if($right_banner_2 != '') {
					if(get_field('right_banner_2_publish', 'option') || current_user_can('administrator')) {
						echo $right_banner_2;
						$right_banner2_show = true;
					}
				}
				if(!$right_banner_show && !$right_banner2_show) {
					echo '&nbsp;';
				}
			?>
			</div>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
	<?php endif; ?>
</div> <!-- #main-content -->

<?php

get_footer();
