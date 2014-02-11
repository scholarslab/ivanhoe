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
            'rewrite' => array('slug' => 'moves')
            )
        );

    add_post_type_support(
        'ivanhoe_move',
        'custom-fields'
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
 * Enables ivanhoe moves to support custom fields.
*/


/**
 * Generate HTML for Ivanhoe Move metabox.
 *
 * @return string The HTML for the form element.
 */
function ivanhoe_move_meta_box($post)
{
    $html = '<p><label for="post_parent">'.__('Game').'</label></p>'
          . '<p><input type="text" name="post_parent" value="'. $post->post_parent.'">';
    
    return $html;
}

/**
 * Wrapper function for adding meta boxes for our custom post types
 */
// function ivanhoe_add_meta_boxes()
// {
//     add_meta_box(
//         'ivanhoe_move_metadata',
//         __('Ivanhoe Move Metadata'),
//         'ivanhoe_move_meta_box',
//         'ivanhoe_move'
//     );
// }


// function ivanhoe_source_meta_box($post)
// {
//      $html = '<p><label for="ivanhoe_move_source">'.__('Ivanhoe Move Source').'</label></p>'
//            . '<p><input type="text" name="ivanhoe_move_source" value="'. $post->.'">';
    
//     return $html;
// }

// function ivanhoe_response_meta_box($post)
// {    
//     $html = '<p><label for="post_parent">'.__('Game').'</label></p>'
//           . '<p><input type="text" name="post_parent" value="'. $post->post_parent.'">';
    
//     return $html;
// }

/**
 * Function for getting the metadata for the post(s) which respond to the current move
 */

function ivanhoe_move_source()
{
    add_meta_box(
        'ivanhoe_move_source',
        __('Source for:'),
        'ivanhoe_source_meta_box',
        'ivanhoe_move'
    );
}

/**
 * Function for getting the metadata for the post(s) to which the current move responds
 */

function ivanhoe_move_response()
{
    add_meta_box(
        'ivanhoe_move_response',
        __('Responds to:'),
        'ivanhoe_response_meta_box',
        'ivanhoe_move'
    );
}

add_action('add_meta_boxes', 'ivanhoe_move_source', 'ivanhoe_move_response');

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

function ivanhoe_register_nav_menus() {
    register_nav_menu('ivanhoe_default',__( 'Ivanhoe Default' ));
}

add_action( 'init', 'ivanhoe_register_nav_menus' );

add_action( 'admin_init', 'ivanhoe_create_move_form_page');

function ivanhoe_create_move_form_page()
{
    if (! get_option('ivanhoe_installed')) {
        $args = array(
            'post_title' => 'Make a Move',
            'post_type' => 'page',
            'post_author' => '1',
            'post_status' => 'publish',
            );
        $ivanhoe_page = wp_insert_post($args);

        if ($ivanhoe_page && !is_wp_error($ivanhoe_page)) {
            update_post_meta( $ivanhoe_page, '_wp_page_template', 'new_ivanhoe_move.php' );
            update_option( 'ivanhoe_move_page', $ivanhoe_page );
        }
        update_option( 'ivanhoe_installed', true );

    }
}