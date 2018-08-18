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
		'name'               => _x( 'Slide', 'post type general name', 'cs' ),
		'singular_name'      => _x( 'Slide', 'post type singular name', 'cs' ),
		'menu_name'          => _x( 'Slides', 'admin menu', 'cs' ),
		'name_admin_bar'     => _x( 'Slideshow', 'add new on admin bar', 'cs' ),
		'add_new'            => _x( 'Add New', 'book', 'cs' ),
		'add_new_item'       => __( 'Add New Slideshow', 'cs' ),
		'new_item'           => __( 'New Slideshow', 'cs' ),
		'edit_item'          => __( 'Edit Slideshow', 'cs' ),
		'view_item'          => __( 'View Slideshow', 'cs' ),
		'all_items'          => __( 'All Slides', 'cs' ),
		'search_items'       => __( 'Search Slides', 'cs' ),
		'parent_item_colon'  => __( 'Parent Slides:', 'cs' ),
		'not_found'          => __( 'No Slides found.', 'cs' ),
		'not_found_in_trash' => __( 'No Slides found in Trash.', 'cs' )
	);

	$args = array(
		'labels'             => $labels,
    'description'        => __( 'Description.', 'cs' ),
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