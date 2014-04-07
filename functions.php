<?php

// Add theme support for WP features.
add_theme_support('menus');
add_theme_support('post-thumbnails', array('ivanhoe_role'));

$ivanhoe_background_defaults = array(
	'default-color'          => '#fff',
	'default-image'          => get_template_directory_uri() . '/images/tile.png'
);

add_theme_support( 'custom-background', $ivanhoe_background_defaults );

add_action( 'init', 'ivanhoe_create_post_types' );

add_action( 'admin_bar_menu', 'modify_admin_bar', 999 );

function modify_admin_bar( $wp_admin_bar ) {
  $wp_admin_bar->remove_node( 'new-ivanhoe_move' );
  $wp_admin_bar->remove_node( 'new-ivanhoe_role' );
  $wp_admin_bar->remove_node( 'new-ivanhoe_role_journal' );

   if( ! current_user_can( 'manage_options' ) )
        {
            $wp_admin_bar->remove_node( 'dashboard' );
        }
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
                'add_new_item' => __( 'Add New Move' ),
                'view_item' => __( 'View Move' ),
                'edit_item' => __( 'Edit Move' )
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
                'singular_name' => __( 'Game' ),
                'all_items' => __( 'All Games' ),
                'add_new_item' => __( 'Add New Game' ),
                'view_item' => __( 'View Game' ),
                'edit_item' => __( 'Edit Game' )
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
                'add_new_item' => __( 'Add New Role' ),
                'view_item' => __( 'View Role' ),
                'edit_item' => __( 'Edit Role' )
                ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'roles'),
            )
        );


    add_post_type_support( 'ivanhoe_role', 'thumbnail' );

    add_rewrite_rule( 'games/([.*]+)/page/([0-9]+)/?$', 'index.php?ivanhoe_game=[1]&paged=$matches[2]', 'top' );

}

    register_post_type(
        'ivanhoe_role_journal',
        array(
            'labels' => array(
                'name'          => __( 'Role Journal' ),
                'singular_name' => __( 'Role Journal Entry' ),
                'all_items'     => __( 'All Entries' ),
                'add_new_item'  => __( 'Add New Entry' ),
                'view_item' => __( 'View Entry' ),
                'edit_item' => __( 'Edit Entry' )
            ),
            'public'        => true,
            'has_archive'   => true,
            'rewrite'       => array( 'slug' => 'rolejournal' )
        )
    );


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
}

function ivanhoe_register_nav_menus() {
    ivanhoe_make_menus();
    register_nav_menu('ivanhoe_default',__( 'Ivanhoe Default' ));
}

add_action( 'after_switch_theme', 'ivanhoe_register_nav_menus' );

function ivanhoe_append_profile_nav_menu($items) {
    global $wp;

    if (is_user_logged_in()) {
        $user   = wp_get_current_user();
        $url    = get_author_posts_url($user->ID);
        $items .= "<li class='menu-item menu-item-type-custom "
            . "menu-item-object-custom menu'>"
            . "<a href='$url'>Profile</a></li>";
    }

    $current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request) );

    $items .= "<li class='menu-item menu-item-type-custom "
            . "menu-item-object-custom menu'>"
            . wp_loginout( $current_url, false )  . "</li>";

    return $items;
}

add_filter('wp_nav_menu_items', 'ivanhoe_append_profile_nav_menu');

add_action( 'switch_theme', 'ivanhoe_switch_themes');

function ivanhoe_switch_themes()
{
    $menu_name = 'ivanhoe_default';

    wp_delete_post( get_option('ivanhoe_move_page'), true);
    wp_delete_post( get_option('ivanhoe_role_page'), true);
    delete_option( 'ivanhoe_installed' );
    delete_option( 'ivanhoe_move_page' );
    delete_option( 'ivanhoe_role_page' );

    $nav_menu = wp_get_nav_menu_object( $menu_name );

    if ($nav_menu) {
        wp_delete_nav_menu($nav_menu->term_id);
    }
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
        $html = '<h3>Source</h3>
        <ul><li><a href="'.get_permalink($source->ID).'">'.$source->post_title.'</a></li></ul>';
    }

    else {
        $html = '<h3>Source</h3>
        <p>There is no source for this post.</p>';
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

    <h3>Responses</h3>
    <ul>
    <?php while( $source_query->have_posts() ) : $source_query->the_post(); ?>

    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>

    <?php endwhile; ?>
    </ul>
    <?php else : ?>
    <h3>Responses</h3>
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
    $role = ivanhoe_user_has_role($post->post_parent);
    $role_id = $role->ID;

    $ivanhoe_params = array(
    "parent_post" => $post->post_parent,
    "move_source" => $post->ID,
    'ivanhoe_role_id' => $role_id
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

    if ('post_type' == 'ivanhoe_game'){
        $ivanhoe_params = array(
            "parent_post" => $post->ID
        );
    } else {
        $ivanhoe_params = array(
            'parent_post' => $post->post_parent
        );
    }

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

    if ( $role && ($post->post_type !== 'post') ){
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
        'total' => $wp_query->max_num_pages,
        'prev_text' => '<',
        'next_text' => '>'
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
        $html = '<a href="'.ivanhoe_response_form_url( $post ).'" class="button">Respond '
              . '<span class="visuallyhidden">to '.$post->post_title .'</span>'
              . '</a>';
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

function get_excerpt_by_id($post_id){
    $the_post = get_post($post_id); //Gets post ID
    $the_excerpt = $the_post->post_content; //Gets post_content to be used as a basis for the excerpt
    $excerpt_length = 55; //Sets excerpt length by word count
    $the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
    $words = explode(' ', $the_excerpt, $excerpt_length + 1);
    if(count($words) > $excerpt_length) :
        array_pop($words);
        array_push($words, '…');
    $the_excerpt = implode(' ', $words);
    endif;
    $the_excerpt = '<p>' . $the_excerpt . '</p>';
    return $the_excerpt;
}

function get_title_by_id($post_id){
    $the_post = get_post($post_id); //Gets post ID
    $the_title = $the_post->post_title; //Gets post_content to be used as a basis for the excerpt
    $the_title = '<h3>' . $the_title . '</h3>';
    return $the_title;
}

function ivanhoe_add_image( $file_handler, $parent_post_id) {

    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    $attach_id = media_handle_upload( $file_handler, $parent_post_id );

    set_post_thumbnail($parent_post_id, $attach_id);

    return $attach_id;
}

function ivanhoe_get_rationales( $post )
{
    // $role = ivanhoe_user_has_role($post->post_parent);
    // $role_id = $role->ID;
    $args = array(
        'post_type' => 'ivanhoe_role_journal',
        'post_per_page' => -1,
        'meta_key' => 'Ivanhoe Game Source',
        'meta_value' => $post->post_parent,
        'meta_value_compare' => '=',
        'meta_key' => 'Ivanhoe Role ID',
        'meta_value' => $post->ID,
        'meta_value_compare' => '='
        );

    $game_query = new WP_Query( $args );

    if ($game_query->have_posts() ) : ?>

    <ul>
    <?php while( $game_query->have_posts() ) : $game_query->the_post(); ?>

    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>

    <?php endwhile; ?>
    </ul>
    <?php else : ?>
    <p>There are no journal entries.</p>
    <?php endif;
    wp_reset_postdata();
}

//Closes off dashboard to non-admin users; redirects to homepage

function restrict_admin_with_redirect()
{
    if ( ! current_user_can( 'manage_options' ) && $_SERVER['PHP_SELF'] != '/wp-admin/admin-ajax.php' ) {
        wp_redirect( site_url() ); exit;
    }
}

add_action( 'admin_init', 'restrict_admin_with_redirect' );
