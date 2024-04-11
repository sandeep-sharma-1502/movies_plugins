<?php

/**
 * Plugin Name: Movies
 * Plugin URI: http://example.com/my-plugin
 * Description: This is for movies
 * Version: 1.0
 * Author: Sandeep
 * Author URI: http://example.com
 * License: GPL-2.0+
 * 
 */


add_action('init', 'wporg_custom_post_type');
add_shortcode( 'movies', 'sada_shortcode' );
add_action('wp_enqueue_scripts', 'enqueue_custom');

function sada_shortcode($atts) {

    $post_type = get_option('myplugin_settings_input_field1');

    $posts_per_page = get_option('myplugin_settings_input_field2');

    $author_name = get_option('myplugin_settings_input_field3');

    $category_name = get_option('myplugin_settings_input_field4');

    // Set the posts_per_page attribute in $atts
    $atts['posts_per_page'] = $posts_per_page;

    if($post_type != '') {
        $atts['post_type'] = $post_type;
    }
    if($author_name != '') {
        $atts['author_name'] = $author_name;
    }

    if($category_name != '') {
        $atts['category_name'] = $category_name;
    }

    if($posts_per_page != '') {
        $atts['posts_per_page'] = $posts_per_page;
    }
    

    $atts = shortcode_atts(array(
        'post_type' => 'wporg_product',
        'posts_per_page' => '-1' ,
        'author_name' => '',
        'category_name' => '',  
    ), $atts);


    update_option('movies_shortcode_style', 'template2');

    $query = new WP_Query($atts);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            global $post;
            $post_id = $post->ID;
            include 'template.php';
        }
    } else {
        echo '<p>No posts found.</p>';
    }
    wp_reset_postdata();
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


function enqueue_custom() {
    $style_attribute = get_option('movies_shortcode_style');
    
    if (!empty($style_attribute)) {
        $css_file_url = plugin_dir_url(__FILE__) . 'assets/css/' . $style_attribute . '.css';
        wp_enqueue_style('movies-custom-style', $css_file_url);
    }
}


add_action('admin_menu', 'movies_plugin_settings_page');

function movies_plugin_settings_page() {
    add_menu_page(
        'Movies Settings', // Page title
        'Settings', // Menu title
        'manage_options', // Capability
        'movies-settings', // Menu slug
        'movies_settings_page_html', // Function to display the settings page
        null, // Icon URL
        99 // Position
    );
}

function movies_settings_page_html() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

        <form action="options.php" method="post">
            <?php 
                // security field
                settings_fields( 'myplugin-settings-page' );

                // output settings section here
                do_settings_sections('myplugin-settings-page');

                // save settings button
                submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php 
}

add_action('admin_init', 'movies_register_settings');

function movies_register_settings() {
    // Registration of settings not needed for this task

        // Setup settings section
        add_settings_section(
            'myplugin_settings_section',
            'Movies Settings Page',
            '',
            'myplugin-settings-page'
        );
    
        // Registe input field1
        register_setting(
            'myplugin-settings-page',
            'myplugin_settings_input_field1',
            array(
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default' => ''
            )
        );
    
        // Add text fields
        add_settings_field(
            'myplugin_settings_input_field1',
            __( 'Post Type', 'my-plugin' ),
            'myplugin_settings_input_field_callback1',
            'myplugin-settings-page',
            'myplugin_settings_section'
        );
        
        // Registe input field2
        register_setting(
            'myplugin-settings-page',
            'myplugin_settings_input_field2',
            array(
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default' => ''
            )
        );
    
        // Add text fields
        add_settings_field(
            'myplugin_settings_input_field2',
            __( 'Posts Per Page', 'my-plugin' ),
            'myplugin_settings_input_field_callback2',
            'myplugin-settings-page',
            'myplugin_settings_section'
        );
        
        // Registe input field3
        register_setting(
            'myplugin-settings-page',
            'myplugin_settings_input_field3',
            array(
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default' => ''
            )
        );
    
        // Add text fields
        add_settings_field(
            'myplugin_settings_input_field3',
            __( 'Author Name', 'my-plugin' ),
            'myplugin_settings_input_field_callback3',
            'myplugin-settings-page',
            'myplugin_settings_section'
        );

        register_setting(
            'myplugin-settings-page',
            'myplugin_settings_input_field4',
            array(
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default' => ''
            )
        );

        add_settings_field(
            'myplugin_settings_input_field4',
            __( 'Category Name', 'my-plugin' ),
            'myplugin_settings_input_field_callback4',
            'myplugin-settings-page',
            'myplugin_settings_section'
        );

      
}

function myplugin_settings_input_field_callback1() {
    $myplugin_input_field = get_option('myplugin_settings_input_field1');
    ?>
    <input type="text" name="myplugin_settings_input_field1" class="regular-text" value="<?php echo isset($myplugin_input_field) ? esc_attr( $myplugin_input_field ) : ''; ?>" />
    <?php 
}

function myplugin_settings_input_field_callback2() {
    $myplugin_input_field = get_option('myplugin_settings_input_field2');
    ?>
    <input type="text" name="myplugin_settings_input_field2" class="regular-text" value="<?php echo isset($myplugin_input_field) ? esc_attr( $myplugin_input_field ) : ''; ?>" />
    <?php 
}

function myplugin_settings_input_field_callback3() {
    $myplugin_input_field = get_option('myplugin_settings_input_field3');
    ?>
    <input type="text" name="myplugin_settings_input_field3" class="regular-text" value="<?php echo isset($myplugin_input_field) ? esc_attr( $myplugin_input_field ) : ''; ?>" />
    <?php 
}

function myplugin_settings_input_field_callback4() {
    $myplugin_input_field = get_option('myplugin_settings_input_field4');
    ?>
    <input type="text" name="myplugin_settings_input_field4" class="regular-text" value="<?php echo isset($myplugin_input_field) ? esc_attr( $myplugin_input_field ) : ''; ?>" />
    <?php 
}


?>
