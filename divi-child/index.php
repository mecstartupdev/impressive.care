<?php get_header(); ?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<?php // top banner
				$banner = get_field('top_banner', 'option');
				if($banner != '') {
					if(get_field('top_banner_publish', 'option') || current_user_can('administrator')) {
						echo '<div class="top-banner">'.$banner.'</div>';
					}
				}
			?>
			<div id="left-area">
		<?php
			if ( have_posts() ) :
				while ( have_posts() ) : the_post();
					$post_format = et_pb_post_format(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>

				<?php if ( ! in_array( $post_format, array( 'link', 'audio', 'quote' ) ) ) : ?>
					<?php if ( ! in_array( $post_format, array( 'link', 'audio' ) ) ) : ?>
						<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php endif; ?>
						<a class="entry-featured-image-url" href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail( '845x321' ); ?>
						</a>
					<?php
						et_divi_post_meta();

						if(has_excerpt()) {
							the_excerpt();
						} else {
							truncate_post( 150 );
						}
					?>
				<?php endif; ?>
				<p><a href="<?php the_permalink(); ?>" class="cs-btn cs-btn-border">READ ARTICLE</a></p>

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
			?>
			</div> <!-- #left-area -->

			<?php //get_sidebar(); ?>
			<div class="right-banner">
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
</div> <!-- #main-content -->

<?php

get_footer();
