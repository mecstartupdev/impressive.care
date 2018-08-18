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
		'name'               => _x( 'Slideshows', 'post type general name', 'cs' ),
		'singular_name'      => _x( 'Slideshow', 'post type singular name', 'cs' ),
		'menu_name'          => _x( 'Slideshows', 'admin menu', 'cs' ),
		'name_admin_bar'     => _x( 'Slideshow', 'add new on admin bar', 'cs' ),
		'add_new'            => _x( 'Add New', 'book', 'cs' ),
		'add_new_item'       => __( 'Add New Slideshow', 'cs' ),
		'new_item'           => __( 'New Slideshow', 'cs' ),
		'edit_item'          => __( 'Edit Slideshow', 'cs' ),
		'view_item'          => __( 'View Slideshow', 'cs' ),
		'all_items'          => __( 'All Slideshows', 'cs' ),
		'search_items'       => __( 'Search Slideshows', 'cs' ),
		'parent_item_colon'  => __( 'Parent Slideshows:', 'cs' ),
		'not_found'          => __( 'No slideshows found.', 'cs' ),
		'not_found_in_trash' => __( 'No slideshows found in Trash.', 'cs' )
	);

	$args = array(
		'labels'             => $labels,
                'description'        => __( 'Description.', 'cs' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'slideshow' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => true,
		'menu_position'      => 5,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields', 'excerpt', 'comments', 'page-attributes' )
	);

	register_post_type( 'slideshow', $args );
}


add_action( 'init', 'slideshow_taxonomy', 0 );
function slideshow_taxonomy() {
	$labels = array(
		'name'              => _x( 'Categories', 'taxonomy general name' ),
		'singular_name'     => _x( 'Category', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Categories' ),
		'all_items'         => __( 'All Categories' ),
		'parent_item'       => __( 'Parent Category' ),
		'parent_item_colon' => __( 'Parent Category:' ),
		'edit_item'         => __( 'Edit Category' ),
		'update_item'       => __( 'Update Category' ),
		'add_new_item'      => __( 'Add New Category' ),
		'new_item_name'     => __( 'New Category Name' ),
		'menu_name'         => __( 'Categories' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'slideshow-cat' ),
	);

	register_taxonomy( 'slideshow-cat', 'slideshow', $args );
}
