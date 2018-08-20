<?php
function divi_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
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
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields', 'excerpt', 'comments', 'page-attributes' )
	);

	register_post_type( 'slide', $args );
	
	$labels = array(
		'name'               => 'Offer',
		'singular_name'      => 'Offer',
		'menu_name'          => 'Offers',
		'name_admin_bar'     => 'Offers',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New Offer',
		'new_item'           => 'New Offer',
		'edit_item'          => 'Edit Offer',
		'view_item'          => 'View Offer',
		'all_items'          => 'All Offers',
		'search_items'       => 'Search Offers',
		'parent_item_colon'  => 'Parent Offer:',
		'not_found'          => 'No Offers found.',
		'not_found_in_trash' => 'No Offers found in Trash.',
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
	global $wp;
	$request = explode('/', $wp->request);
	if(count($request) > 1) {
		if ( get_page_by_path( $request[0], OBJECT, 'slide' ) ) {
			$query->set( 'post_type', 'slide' );
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

	if ( in_array( 'author', $postinfo ) )
		$postinfo_meta .= ' ' . esc_html__( 'By', 'et_builder' ) . ' <span class="author vcard">' . et_pb_get_the_author_posts_link() . '</span>';

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