<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */
function hello_elementor_child_enqueue_scripts() {
	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		'1.0.0'
	);
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts' );




function my_function( $post_id ){
    if ( ! wp_is_post_revision( $post_id ) ){
     
        // unhook this function so it doesn't loop infinitely
        remove_action('save_post', 'my_function');
     
        // update the post, which calls save_post again
        wp_update_post( $my_args );
 
        // re-hook this function
		add_action('save_post', 'my_function');
	} else{
		var_dump(get_the_title($post_id));
		die();
	}
	
	
}
add_action('save_post', 'my_function');



function add_organizer_from_chapter($post_id) {
	
	if ( ! wp_is_post_revision( $post_id ) ){
     
        // unhook this function so it doesn't loop infinitely
        remove_action('save_post', 'add_organizer_from_chapter');
     
/*
        // update the post, which calls save_post again
        wp_update_post( $my_args );
*/
 
        // re-hook this function
		add_action('save_post', 'add_organizer_from_chapter');
	} else {
		
		var_dump($post_id);
		die();
		
		$chapter_ids = array();

	    $query = new WP_Query(array(
	    	'post_type' => 'tribe_organizer',
	    	'post_status' => 'publish', 
			'posts_per_page' => -1
		));
		
		while ($query->have_posts()) {
			$query->the_post();
			array_push($chapter_ids, get_post_meta(get_the_ID(),'chapter_id',true));
		}
		
		if (!in_array($post_id, $chapter_ids)) {			
			$my_post = array(
				'post_type' => 'tribe_organizer',
			    'post_title'    => get_the_title($post_id),
			    'post_status'   => 'publish',
			    'meta_input' => array(
				    'chapter_id' => $post_id,
				)
			);
			// Insert the post into the database.
			$new_organizer = wp_insert_post( $my_post );
		}
			
		update_field('organizer_id', $new_organizer, $post);	
	}
}

add_action( 'save_post_chapter', 'add_organizer_from_chapter');
