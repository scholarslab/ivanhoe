<?php

// Add theme support for WP features.
add_theme_support('menus', 'post_thumbnails' );

add_action( 'init', 'ivanhoe_create_post_types' );

add_action( 'admin_bar_menu', 'modify_admin_bar', 999 );

function modify_admin_bar( $wp_admin_bar ) {
  $wp_admin_bar->remove_node( 'new-ivanhoe_move' );
  $wp_admin_bar->remove_node( 'new-ivanhoe_game' );
  $wp_admin_bar->remove_node( 'new-ivanhoe_role' );
}

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

    add_rewrite_rule( 'games/([.*]+)/page/([0-9]+)/?$', 'index.php?ivanhoe_game=[1]&paged=$matches[2]', 'top' );

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
    $nav_menu = wp_get_nav_menu_object( $menu_name );

    if ($nav_menu) {
        wp_delete_nav_menu($nav_menu->term_id);
    }
    $menu_id = wp_create_nav_menu($menu_name);

    // Set up default menu items
    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' =>  __('Home'),
        'menu-item-classes' => 'home',
        'menu-item-url' => home_url( '/' ),
        'menu-item-status' => 'publish')
    );

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' =>  __('Games'),
        'menu-item-url' => get_post_type_archive_link('ivanhoe_game'),
        'menu-item-status' => 'publish')
    );

    $current_user = wp_get_current_user();
    if (is_user_logged_in()) {
        wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => __('Profile'),
        'menu-item-url' => get_author_posts_url($current_user->ID),
        'menu-item-status' => 'publish')
    );
    }
}

add_action('init', 'ivanhoe_make_menus');

function ivanhoe_register_nav_menus() {
    register_nav_menu('ivanhoe_default',__( 'Ivanhoe Default' ));
}

add_action( 'init', 'ivanhoe_register_nav_menus' );

add_action( 'switch_theme', 'ivanhoe_switch_themes');

function ivanhoe_switch_themes()
{
    wp_delete_post( get_option('ivanhoe_move_page'), true);
    wp_delete_post( get_option('ivanhoe_role_page'), true);
    delete_option( 'ivanhoe_installed' );
    delete_option( 'ivanhoe_move_page' );
    delete_option( 'ivanhoe_role_page' );
}

add_action ( 'after_switch_theme','ivanhoe_after_switch_theme' );

function ivanhoe_after_switch_theme()
{
    if (! get_option('ivanhoe_installed')) {
       $pages = array(
            'ivanhoe_move' => 'Make a Move',
            'ivanhoe_role' => 'Make a Role'
            );
        $args = array(
            'post_type' => 'page',
            'post_author' => '1',
            'post_status' => 'publish',
            );
        foreach ($pages as $page => $title) {
            $args['post_title'] = $title;
            $ivanhoe_page = wp_insert_post($args);

            if ($ivanhoe_page && !is_wp_error($ivanhoe_page)) {
                update_post_meta( $ivanhoe_page, '_wp_page_template', 'new_' . $page. '.php' );
                update_option( $page . '_page', $ivanhoe_page );
            }
        }
        update_option( 'ivanhoe_installed', true );
    }
}



/**
 * Overrides WP's auto redirect on pretty URLs (e.g. /games/game-title/page/2),
 * so we can use native pagination for moves on single Ivanhoe game pages.
 *
 * Checks to see if the current page is a single Ivanhoe game, and returns the
 * requested URL instead of the redirect URL.
 */
function ivanhoe_redirect_canonical( $redirect_url, $requested_url ){

    if ( is_singular( 'ivanhoe_game' ) ) {

        return $requested_url;

    }

    return $redirect_url;

}

add_filter( 'redirect_canonical', 'ivanhoe_redirect_canonical', 10, 2 );

/*
* Calls the source metadata for each move and displays it
*/

function ivanhoe_get_move_source( $post )
{
    // Set $html to an empty string.
    $html = '';

    // Get the Move Source ID from custom post metadata.
    $source_id = get_post_meta($post->ID, 'Ivanhoe Move Source', true);

    // Check if $source_id isn't 0 and if we can get another post with its value.
    if ( $source_id && $source = get_post($source_id) ) {

        // Set $html to a string with a link to source post.
        $html = '<p>Source</p>
        <ul><li><a href="'.get_permalink($source->ID).'">'.$source->post_title.'</a></li></ul>';

    }

    // Print out the value of $html.
    echo $html;
}

/*
* Gets responses for a move and displays them
*/

function ivanhoe_get_move_responses( $post )
{
    $args = array(
        'post_type' => 'ivanhoe_move',
        'post_per_page' => -1,
        'meta_key' => 'Ivanhoe Move Source',
        'meta_value' => $post->ID,
        'meta_value_compare' => '='
        );

    $source_query = new WP_Query( $args );

    if ($source_query->have_posts() ) : ?>
    <p>Responses</p>
    <ul>
    <?php while( $source_query->have_posts() ) : $source_query->the_post(); ?>

    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>

    <?php endwhile; ?>
    </ul>
    <?php else : ?>
    <p>There are no responses to this post.</p>
    <?php endif;
    wp_reset_postdata();
}

/*
* Respond to move helper function
*/

function ivanhoe_response_form_url( $post )
{
    $url = "";

    $ivanhoe_params = array(
    "parent_post" => $post->post_parent,
    "move_source" => $post->ID
    );

    $url = add_query_arg(
        $ivanhoe_params,
        get_permalink(get_option('ivanhoe_move_page'))
    );

    return $url;
}

function ivanhoe_role_form_url( $post )
{
    $url = "";

    $ivanhoe_params = array(
    "parent_post" => $post->ID,
    );

    $url = add_query_arg(
        $ivanhoe_params,
        get_permalink(get_option('ivanhoe_role_page'))
    );

    return $url;
}

/*
* Helper for checking if user has a role
*/

function ivanhoe_user_has_role($game_id, $user_id=null) {
    // WP Query to find role post type for game ID and user ID.
    $user_id = $user_id ? $user_id : get_current_user_id();
    if ($user_id) {
        $args = array(
            'post_parent' => $game_id,
            'author' => $user_id,
            'post_type' => 'ivanhoe_role'
            );
        $posts = get_posts($args);
        $role = reset($posts);
        if ($role) {
            return $role;
        }
        return false;
    }
    return false;
}

/*
* Displays role name
*/

function ivanhoe_display_role_name ( $link )
{
    global $authordata, $post;
    $args = array (
        'post_type' => 'ivanhoe_role',
        'author' => $authordata->ID,
        'post_parent' => $post->post_parent
        );
    //$query = new WP_Query($args);
    //$posts = $query->get_posts();
    $posts = get_posts($args);
    $role = reset($posts);
    if ( $role ){
        $link = sprintf(
            '<a href="%1$s" title="%2$s" rel="author">%3$s</a>',
            esc_url( get_permalink($role->ID) ),
            esc_attr( sprintf( __( 'Posts by %s' ), get_the_author() ) ),
            $role->post_title
            );
    }

    return $link;
}

add_filter( 'the_author_posts_link', 'ivanhoe_display_role_name', 10, 1);

/*
*Displays Pagination
*/
function ivanhoe_paginate_links ( $query = null )
{
    global $wp_query;

    $query = $query ? $query : $wp_query;

    $big = 999999999; // need an unlikely integer

    echo paginate_links( array(
        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'format' => '?paged=%#%',
        'current' => max( 1, get_query_var('paged') ),
        'total' => $wp_query->max_num_pages
    ) );

}


/*
 * Function to display the 'respond to this move' button only when user has role
 */

function ivanhoe_move_link ( $post )
{
    $html = '';
    $post_type = get_post_type($post );
    $role = false;
    if ($post_type == 'ivanhoe_game') {
        $role = ivanhoe_user_has_role( $post->ID );
    } elseif ($post_type == 'ivanhoe_move') {
        $role = ivanhoe_user_has_role( $post->post_parent );
    }

    if ( $role )
    {
        $html = '<a href="'.ivanhoe_response_form_url( $post ).'" class="button">Respond to this move</a>';
    }

    return $html;

}

function catch_that_image() {
    global $post, $posts;
    $first_image = '';
    ob_start();
    ob_end_clean();
    $output_videos = preg_match_all('/<(img|embed|iframe|video)[^>]*>/i', $post->post_content, $matches);

    if ( !empty( $matches [0] ) ) {
        $first_image = $matches [0] [0];
        if ( strpos( $first_image, 'iframe' ) !== FALSE ) {
            $first_image = $first_image . '</iframe>';
        }
    }

    return $first_image;
}

