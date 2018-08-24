<?php /* Template Name: Home page */
get_header(); ?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
		<?php
			$home_args = array(
				'post_type' => 'any',
				'posts_status' => 'publish',
				'post__in' => get_field('home_posts'),
				'orderby' => 'post__in'
				);
			$home_query = new WP_Query( $home_args );

			if ( $home_query->have_posts() ) :
				while ( $home_query->have_posts() ) : $home_query->the_post();
					$post_format = et_pb_post_format(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>

				<?php
					$thumb = '';

					$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

					$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
					$classtext = 'et_pb_post_main_image';
					$titletext = get_the_title();
					$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
					$thumb = $thumbnail["thumb"];

					et_divi_post_format_content();

					if ( ! in_array( $post_format, array( 'link', 'audio', 'quote' ) ) ) {
						if ( 'video' === $post_format && false !== ( $first_video = et_get_first_video() ) ) :
							printf(
								'<div class="et_main_video_container">
									%1$s
								</div>',
								$first_video
							);
						elseif ( ! in_array( $post_format, array( 'gallery' ) ) && 'on' === et_get_option( 'divi_thumbnails_index', 'on' ) && '' !== $thumb ) : ?>
						<div class="cs-left">
							<a class="entry-featured-image-url" href="<?php the_permalink(); ?>">
								<?php //print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height ); ?>
								<?php the_post_thumbnail('full'); ?>
							</a>
						</div>
					<?php
						elseif ( 'gallery' === $post_format ) :
							et_pb_gallery_images();
						endif;
					} ?>
					<div class="cs-right">
					<div class="post-category"><?php echo get_the_category_list(', '); ?></div>
				<?php if ( ! in_array( $post_format, array( 'link', 'audio', 'quote' ) ) ) : ?>
					<?php if ( ! in_array( $post_format, array( 'link', 'audio' ) ) ) : ?>
						<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php endif; ?>

					<?php
						et_divi_post_meta();

						if (get_the_excerpt()) {
							the_excerpt();
						} elseif ( 'on' !== et_get_option( 'divi_blog_style', 'false' ) || ( is_search() && ( 'on' === get_post_meta( get_the_ID(), '_et_pb_use_builder', true ) ) ) ) {
							truncate_post( 270 );
						} else {
							the_content();
						}
					?>
				<?php endif; ?>
				<br>
							<p><a href="<?php the_permalink(); ?>" class="cs-btn cs-btn-border">READ ARTICLE</a></p>
						</div>
						<div class="clearfix cs-sep"></div>
					</article> <!-- .et_pb_post -->
			<?php
					endwhile;

					if ( function_exists( 'wp_pagenavi' ) )
						wp_pagenavi();
					else
						get_template_part( 'includes/navigation', 'index' );
				else :
					get_template_part( 'includes/no-results', 'index' );
				endif;
				wp_reset_postdata();
			?>
			</div> <!-- #left-area -->
			<?php 
				$banner = get_field('home_banner', 'option');
				if($banner != '') {
					if(get_field('home_banner_publish', 'option') || current_user_can('administrator')) {
						echo '<div class="home-banner">'.$banner.'</div>';
					}
				}
			?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php

get_footer();