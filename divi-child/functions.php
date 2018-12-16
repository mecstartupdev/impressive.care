<?php
function divi_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_script( 'hc-sticky', get_stylesheet_directory_uri() . '/js/hc-sticky.js' );
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
	'title'  => 'Ad banners',
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
add_action('init', 'init_301');
function init_301() {
	
	// Old domain redirect
	if( $_SERVER['SERVER_NAME'] == 'impressive.care' || $_SERVER['SERVER_NAME'] == 'www.impressive.care' ) {
		wp_redirect( 'http://avima.com' . $_SERVER['REQUEST_URI'], 301 );
		exit;
	}
	
	// Check amazon in stock - CRON
	if( isset($_GET['ex_cron']) && $_GET['ex_cron'] == 'amazon_in_stock' ) {
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
		$mop_query = new WP_Query( $mop_args );
		if($mop_query) {
			$outstock = 0;
			$offers_in = array();
			$offers_out = array();
			foreach($mop_query as $mop) {
				$offer_sections = get_field('multioffers', $mop->ID);
				if($offer_sections) {
					foreach($offer_sections as $section) {
						foreach($section['offers'] as $offer) {
							//$call_to_action_link = get_field('call_to_action_link', $offer->ID);
							$call_to_action_link = 'https://www.amazon.com/gp/product/B0798BBMLP';
							if( amazon_in_stock($call_to_action_link) ) {
								$offers_in[] = $offer->ID;
								echo 'IN --- <a target="_blank" href="'.$call_to_action_link.'">'.$call_to_action_link.'</a><br>';
							} else {
								$offers_out[] = $offer->ID;
								echo 'OUT --- <a target="_blank" href="'.$call_to_action_link.'">'.$call_to_action_link.'</a><br>';
								$outstock += 1;
							}
							exit;
						}
					}
				}
			}
			if($outstock < 3) {
				foreach($offers_in as $offer_id) {
					update_field('amazon_in_stock', 'yes', $offer_id);
				}
				foreach($offers_out as $offer_id) {
					update_field('amazon_in_stock', 'no', $offer_id);
				}
			} else {
				$message = '';
				wp_mail( 'samsonov.teamwork@gmail.com', 'Amazon 3+ items go out of stock', $message );
			}
			/*print_r($offers_in);
			print_r($offers_out);*/
		}
		
		// TEST***
		$old = get_post_field('post_content', 1306);
		$new = '';
		$i=1;
		foreach($offers_out as $offer_id) {
			$new .= $i.'. '. date('Y-m-d H:i') . ' ' . get_edit_post_link($offer_id) . "<br />";
			$i++;
		}
		$new .= '<br />';
		wp_update_post( array(
			'ID'           => 1306,
			'post_content' => $old.$new,
		));
		// ***TEST
		
		// EXIT
		exit;
	}
}

// Check amazon in stock
function amazon_in_stock($url) {
	$html = file_get_contents($url);
	//sleep(60);
	//var_dump($html);
	if( $html && preg_match_all('/Add to Shopping Cart/', $html) ) {
		return true;
	} else {
		return false;
	}
}

// Add Parent Slide to Quick Edit
add_filter( 'manage_slide_posts_columns', 'avima_manage_slide_posts_columns' );
function avima_manage_slide_posts_columns( $columns ) {
	$columns['parent_slide'] = 'Parent slide';
	return $columns;
}
add_action( 'manage_slide_posts_custom_column', 'avima_manage_slide_posts_custom_column', 10, 2 );
function avima_manage_slide_posts_custom_column( $column_name, $post_id ) {
	if ( 'parent_slide' == $column_name ) {
		$parent_slide = get_post_meta( $post_id, 'parent_slide', true );
		if ( $parent_slide ) {
			echo get_post_field('post_title', $parent_slide);
		} else {
			echo 'None';
		}
	}
}
add_action('quick_edit_custom_box',  'avima_quick_edit_custom_box', 10, 2);
function avima_quick_edit_custom_box($column_name, $post_type) {
	if ($column_name != 'parent_slide') return;
	?>
	<fieldset class="inline-edit-col-left">
	<div class="inline-edit-col">
			<span class="title">Parent slide</span>
			<input type="hidden" name="parent_slide_noncename" id="parent_slide_noncename" value="" />
			<?php
				$slides = get_posts( array( 
					'post_type' => 'slide',
					'numberposts' => -1,
					'post_status' => 'publish')
				);
			?>
			<select name="field_parent_slide" id="field_parent_slide">
				<option value="0">None</option>
				<?php 
					foreach ($slides as $slide) {
						echo "<option value='{$slide->ID}'>{$slide->post_title}</option>\n";
					}
				?>
			</select>
	</div>
	</fieldset>
	<?php
}
add_action('admin_footer', 'avima_admin_footer');
function avima_admin_footer() {
	global $current_screen;
	if (($current_screen->id != 'edit-slide') || ($current_screen->post_type != 'slide')) return; 
	?>
	<script type="text/javascript">
		jQuery( function( $ ) {
			var prnt = $( '.inline-edit-col #post_parent' );
			prnt.hide();
			prnt.siblings('span').hide();
		});
		function set_inline_widget_set(parent_slide, nonce) {
			// revert Quick Edit menu so that it refreshes properly
			inlineEditPost.revert();
			var widgetInput = document.getElementById('field_parent_slide');
			var nonceInput = document.getElementById('parent_slide_noncename');
			nonceInput.value = nonce;
			// check option manually
			for (i = 0; i < widgetInput.options.length; i++) {
				if (widgetInput.options[i].value == parent_slide) { 
					widgetInput.options[i].setAttribute("selected", "selected"); 
				} else { widgetInput.options[i].removeAttribute("selected"); }
			}
			inlineEditPost.revert();
		}
	</script>
	<?php
}
add_filter('page_row_actions', 'avima_page_row_actions', 10, 2);
function avima_page_row_actions($actions, $post) {
	global $current_screen;
	if (($current_screen->id != 'edit-slide') || ($current_screen->post_type != 'slide')) return $actions; 
	$nonce = wp_create_nonce( 'parent_slide_'.$post->ID);
	$parent_slide = get_post_meta( $post->ID, 'parent_slide', TRUE); 
	$actions['inline hide-if-no-js'] = '<a href="#" class="editinline" title="';
	$actions['inline hide-if-no-js'] .= esc_attr( __( 'Edit this item inline' ) ) . '" ';
	$actions['inline hide-if-no-js'] .= " onclick=\"set_inline_widget_set('{$parent_slide}', '{$nonce}')\">"; 
	$actions['inline hide-if-no-js'] .= __( 'Quick&nbsp;Edit' );
	$actions['inline hide-if-no-js'] .= '</a>';
	return $actions;    
}
add_action('save_post_slide', 'avima_save_post_slide');
function avima_save_post_slide($post_id) {
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return $post_id;    
	if ( 'slide' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) )
				return $post_id;
	} else {
		if ( !current_user_can( 'edit_post', $post_id ) )
		return $post_id;
	}
	$post = get_post($post_id);
	if (isset($_POST['field_parent_slide']) && ($post->post_type != 'revision')) {
		$field_parent_slide = esc_attr($_POST['field_parent_slide']);
		if ($field_parent_slide)
			update_post_meta( $post_id, 'parent_slide', $field_parent_slide);     
		else
			delete_post_meta( $post_id, 'parent_slide');     
	}
}