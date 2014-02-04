<?php

// Add theme support for WP features.
add_theme_support('menus');

add_action( 'init', 'ivanhoe_create_post_types' );

function ivanhoe_create_post_types()
{
    register_post_type(
        'ivanhoe_move',
        array(
            'labels' => array(
                'name' => __( 'Moves' ),
                'singular_name' => __( 'Move'),
                'all_items' => __( 'All Moves' ),
                'add_new_item' => __( 'Add New Move' )
                ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'moves'),
            )
        );

    register_post_type(
        'ivanhoe_game',
        array(
            'labels' => array(
                'name' => __( 'Games' ),
                'singular_name' => __( 'Game'),
                'all_items' => __( 'All Games' ),
                'add_new_item' => __( 'Add New Game' )
                ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'games'),
            'capabilities' => array(
                'edit_post'          => 'update_core',
                'read_post'          => 'read',
                'delete_post'        => 'update_core',
                'edit_posts'         => 'update_core',
                'edit_others_posts'  => 'update_core',
                'publish_posts'      => 'update_core',
                'read_private_posts' => 'update_core'
                ),
            )
        );

    register_post_type(
        'ivanhoe_role',
        array(
            'labels' => array(
                'name' => __( 'Roles' ),
                'singular_name' => __( 'Role'),
                'all_items' => __( 'All Roles' ),
                'add_new_item' => __( 'Add New Role' )
                ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'roles'),
            )
        );
}

/**
 * Generate HTML for Ivanhoe Move metabox.
 *
 * @return string The HTML for the form element.
 */
function ivanhoe_move_meta_box($post)
{
    $html = '<p><label for="post_parent">'.__('Game').'</label></p>'
          . '<p><input type="text" name="post_parent" value="'. $post->post_parent.'">';
    
    echo $html;
}

/**
 * Wrapper function for adding meta boxes for our custom post types
 */
function ivanhoe_add_meta_boxes()
{
    add_meta_box(
        'ivanhoe_move_metadata',
        __('Ivanhoe Move Metadata'),
        'ivanhoe_move_meta_box',
        'ivanhoe_move'
    );
}

add_action('add_meta_boxes', 'ivanhoe_add_meta_boxes');

function ivanhoe_make_menus() {

    $menu_name = 'ivanhoe_default';

    // Check if the menu exists
    $menu_exists = wp_get_nav_menu_object( $menu_name );

    // If it doesn't exist, let's create it.
    if( !$menu_exists){
        $menu_id = wp_create_nav_menu($menu_name);

        // Set up default menu items
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' =>  __('Home'),
            'menu-item-classes' => 'home',
            'menu-item-url' => home_url( '/' ),
            'menu-item-status' => 'publish'));

        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' =>  __('Games'),
            'menu-item-url' => get_post_type_archive_link('ivanhoe_game'),
            'menu-item-status' => 'publish'));
    }
}

add_action('init', 'ivanhoe_make_menus');

/**
 * Load site scripts.
 *
 * @since  1.0.0
 *
 * @return void
 */
function ivanhoe_enqueue_scripts()
{
  $postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

	// Loads main stylesheet.
	wp_enqueue_style( 'ivanhoetheme', get_template_directory_uri() . "/assets/css/style.css", array(), null, 'all' );

	// Theme scripts.
  wp_enqueue_script( 'ivanhoetheme', get_template_directory_uri() . "/assets/js/build/main{$postfix}.js", array(), null, true );

}

add_action( 'wp_enqueue_scripts', 'ivanhoe_enqueue_scripts');

/**
 * Add humans.txt to the <head> element.
 */
function wptheme_header_meta()
{
  $humans = '<link type="text/plain" rel="author" href="' . get_template_directory_uri() . '/humans.txt" />';

  echo apply_filters( 'wptheme_humans', $humans );
}

add_action( 'wp_head', 'wptheme_header_meta' );

