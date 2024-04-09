<?php

/**
 * Plugin Name: Movies
 * Plugin URI: http://example.com/my-plugin
 * Description: This is for movies
 * Version: 1.0
 * Author: Sandeep
 * Author URI: http://example.com
 * License: GPL-2.0+
 */         


add_action('init', 'wporg_custom_post_type');
add_shortcode( 'movies', 'sada_shortcode' );
add_action('wp_enqueue_scripts', 'enqueue_custom');


function enqueue_custom() {
    $style_attribute = get_option('movies_shortcode_style');
    
    if (!empty($style_attribute)) {
        $css_file_url = plugin_dir_url(__FILE__) . 'assets/css/' . $style_attribute . '.css';
        wp_enqueue_style('movies-custom-style', $css_file_url);
    }
}



 function wporg_custom_post_type() {
	register_post_type('wporg_product',
		array(
			'labels'      => array(
				'name'          => __('Movies', 'textdomain'),
				'singular_name' => __('Product', 'textdomain'),
			),
				'public'      => true,
				'has_archive' => true,
                'supports' => array('title', 'editor', 'comments', 'revisions', 'trackbacks', 'author', 'excerpt', 'page-attributes', 'thumbnail', 'custom-fields', 'post-formats'),
                'taxonomies' => array('category'),          
		)
	);
}

function sada_shortcode($atts) {
    $wporg_atts = shortcode_atts(
        array(
            'post_type' => 'wporg_product',
            'posts_per_page' => '5',
            'author_name'=>'',
            'category_name'=>'',
            'template'=>'template1',
        ), $atts
    );


update_option('movies_shortcode_style', $wporg_atts['template']);


    // $args = array(
    //     'post_type'  => 'wporg_product', 
    //     'posts_per_page' => $wporg_atts['post'],
    //     'author_name' => $wporg_atts['author'],
    //     's' => $wporg_atts['category'],
    // );


    $custom_query = new WP_Query($wporg_atts);

    echo '<div class="maincontaniner">';

            if ($custom_query->have_posts()) {
                while ($custom_query->have_posts()){
                    
                    $custom_query->the_post();
                    $post_id = get_the_ID();
                    include 'template.php';
       
                }
                wp_reset_postdata(); 
            }else{
                    echo '<p>No books found</p>';
            }

  echo '</div>'; 

}
?>