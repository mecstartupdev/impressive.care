<?php
function divi_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_script( 'parent-style', get_stylesheet_directory_uri() . '/js/theme.js' );
}
add_action( 'wp_enqueue_scripts', 'divi_enqueue_styles' );


add_action( 'init', 'cs_slideshow_init' );
/**
 * Register a slideshow post type.
 */
function cs_slideshow_init() {
	$labels = array(
		'name'               => 'Slide',
		'singular_name'      => 'Slide',
		'menu_name'          => 'Slides',
		'name_admin_bar'     => 'Slides',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New Slide',
		'new_item'           => 'New Slide',
		'edit_item'          => 'Edit Slide',
		'view_item'          => 'View Slide',
		'all_items'          => 'All Slides',
		'search_items'       => 'Search Slides',
		'parent_item_colon'  => 'Parent Slide:',
		'not_found'          => 'No Slides found.',
		'not_found_in_trash' => 'No Slides found in Trash.',
	);

	$args = array(
		'labels'             => $labels,
    'description'        => 'Description.',
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => false, 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => true,
		'menu_position'      => 5,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields', 'excerpt', 'comments', 'page-attributes' ),
		'taxonomies'         => array( 'category' ),
	);

	register_post_type( 'slide', $args );
	
	$labels = array(
		'name'               => 'Multioffers',
		'singular_name'      => 'Multioffers',
		'menu_name'          => 'Multioffers',
		'name_admin_bar'     => 'Multioffers',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New Multioffer',
		'new_item'           => 'New Multioffer',
		'edit_item'          => 'Edit Multioffer',
		'view_item'          => 'View Multioffer',
		'all_items'          => 'All Multioffers',
		'search_items'       => 'Search Multioffers',
		'parent_item_colon'  => 'Parent Multioffer:',
		'not_found'          => 'No Multioffers found.',
		'not_found_in_trash' => 'No Multioffers found in Trash.',
	);

	$args = array(
		'labels'             => $labels,
    'description'        => 'Description.',
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'offer' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => true,
		'menu_position'      => 5,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields', 'excerpt', 'comments', 'page-attributes' )
	);

	register_post_type( 'offer', $args );
}


//add_action( 'init', 'slideshow_taxonomy', 0 );
function slideshow_taxonomy() {
	$labels = array(
		'name'              => _x( 'Slideshows', 'taxonomy general name' ),
		'singular_name'     => _x( 'Slideshow', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Slideshows' ),
		'all_items'         => __( 'All Slideshows' ),
		'parent_item'       => __( 'Parent Slideshow' ),
		'parent_item_colon' => __( 'Parent Slideshow:' ),
		'edit_item'         => __( 'Edit Slideshow' ),
		'update_item'       => __( 'Update Slideshow' ),
		'add_new_item'      => __( 'Add New Slideshow' ),
		'new_item_name'     => __( 'New Slideshow Name' ),
		'menu_name'         => __( 'Slideshows' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'slideshow' ),
		//'rewrite' => array('slug' => '%slideshow%', 'with_front' => false)
	);

	register_taxonomy( 'slideshow', 'slide', $args );
}

add_filter( 'post_type_link', 'theme_remove_slug', 10, 3 );
function theme_remove_slug( $post_link, $post, $leavename ) {

	if ( $post->post_type == 'slide' ) {
		$post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );
	}
		
	return $post_link;
}

add_action( 'pre_get_posts', 'theme_parse_request' );
function theme_parse_request( $query ) {
	if(!$query->is_main_query()) return;
	if(is_category()) {
		$query->set('post_type', array('post', 'slide'));
	} else {
		global $wp;
		$request = explode('/', $wp->request);
		if(!empty($request[0])) {
			if ( get_page_by_path( $request[0], OBJECT, 'slide' ) ) {
				$query->set( 'post_type', 'slide' );
			}
		}
	}
}

// Add theme options
acf_add_options_sub_page( array(
	'title'  => 'Theme Fields',
	'parent' => 'themes.php',
) );

function et_pb_postinfo_meta( $postinfo, $date_format, $comment_zero, $comment_one, $comment_more ){
	$postinfo_meta = '';

	if(!$author_text = get_field('author')) {
		$author_text = et_pb_get_the_author_posts_link();
	}
	//if ( in_array( 'author', $postinfo ) )
		$postinfo_meta .= ' ' . esc_html__( 'By', 'et_builder' ) . ' <span class="author vcard">' . $author_text . '</span>';

	if ( in_array( 'date', $postinfo ) ) {
		if ( in_array( 'author', $postinfo ) ) $postinfo_meta .= ' | ';
		$postinfo_meta .= '<span class="published">' . esc_html( get_the_time( wp_unslash( 'F j, Y' ) ) ) . '</span>';
	}

	/*if ( in_array( 'categories', $postinfo ) ) {
		$categories_list = get_the_category_list(', ');

		// do not output anything if no categories retrieved
		if ( '' !== $categories_list ) {
			if ( in_array( 'author', $postinfo ) || in_array( 'date', $postinfo ) )	$postinfo_meta .= ' | ';

			$postinfo_meta .= $categories_list;
		}
	}*/

	if ( in_array( 'comments', $postinfo ) ){
		if ( in_array( 'author', $postinfo ) || in_array( 'date', $postinfo ) || in_array( 'categories', $postinfo ) ) $postinfo_meta .= ' | ';
		$postinfo_meta .= et_pb_get_comments_popup_link( $comment_zero, $comment_one, $comment_more );
	}

	return $postinfo_meta;
}

function et_divi_post_meta() {
	/*$postinfo = is_single() ? et_get_option( 'divi_postinfo2' ) : et_get_option( 'divi_postinfo1' );

	if ( $postinfo ) :
		echo '<p class="post-meta">';
		echo et_pb_postinfo_meta( $postinfo, et_get_option( 'divi_date_format', 'M j, Y' ), esc_html__( '0 comments', 'Divi' ), esc_html__( '1 comment', 'Divi' ), '% ' . esc_html__( 'comments', 'Divi' ) );
		echo '</p>';
	endif;*/
}

// add image sizes
/*add_filter('et_theme_image_sizes', 'theme_image_sizes');
function theme_image_sizes($et_theme_image_sizes) {
	$et_theme_image_sizes['845x321'] = '845x321';
	
	return $et_theme_image_sizes;
}*/
add_image_size( '845x321', 845, 321, true );
add_image_size( '9999x350', 9999, 350 );

// Post list_categories exclude Uncategorized
function custom_list_categories() {
	$out = array();
  $cats = wp_get_post_categories(get_the_ID());
	foreach ($cats as $c) {
		if($c == 1) continue; 
		$cat = get_category($c);
		$out[] = '<a href="' . get_category_link($cat) . '" rel="category tag">' . $cat->name . '</a>';
	}
	
	echo implode(', ', $out);
}

// Init
add_action('init', 'init_404');
function init_404() {
	
	// Old domain redirect
	if( $_SERVER['SERVER_NAME'] == 'impressive.care' ) {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		get_template_part( 404 );
		exit();
	}
	
	// Check amazon in stock - CRON
	if( isset($_GET['ex_cron']) && $_GET['ex_cron'] == 'amazon_in_stock' ) {
		// Testing //
		$old = get_post_field('post_content', 1306);
		 $my_post = array(
      'ID'           => 1306,
      'post_content' => $old.'ABC ',
			);
		wp_update_post( $my_post );
		$mop_args = array(
			'post_type' => 'post',
			'posts_status' => 'any',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'multioffers',
					'compare' => 'EXISTS'
				),
				array(
					'key' => 'multioffers',
					'compare' => '!=',
					'value' => ''
				)
			)
		);
		// Testing //
		$mop_query = new WP_Query( $mop_args );
		if($mop_query) {
			foreach($mop_query as $mop) {
				$offer_sections = get_field('multioffers', $mop->ID);
				if($offer_sections) {
					foreach($offer_sections as $section) {
						foreach($section['offers'] as $offer) {
							$call_to_action_link = get_field('call_to_action_link', $offer->ID);
							if( amazon_in_stock($call_to_action_link) ) {
								update_field('amazon_in_stock', 'yes', $offer->ID);
							} else {
								update_field('amazon_in_stock', 'no', $offer->ID);
							}
						}
					}
				}
			}
		}
	}
}

// Check amazon in stock
function amazon_in_stock($url) {
	$html = file_get_contents($url);
	if( $html && preg_match_all('/Add to Shopping Cart/', $html) ) {
		return true;
	} else {
		return false;
	}
}