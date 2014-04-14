<?php

// Add theme support for WP features.
add_theme_support('menus');
add_theme_support('post-thumbnails', array('ivanhoe_role'));

/**
 * Custom backgrounds.
 */
$ivanhoe_background_defaults = array(
	'default-color'          => '#fff',
	'default-image'          => get_template_directory_uri() . '/images/tile.png'
);

add_theme_support( 'custom-background', $ivanhoe_background_defaults );


/**
 * Modify the admin bar.
 */
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


/**
 * Create custom post types.
 */
add_action( 'init', 'ivanhoe_create_post_types' );

function ivanhoe_create_post_types()
{
    register_post_type(
        'ivanhoe_move',
        array(
            'labels' => array(
                'name' => __( 'Moves', 'ivanhoe' ),
                'singular_name' => __( 'Move', 'ivanhoe' ),
                'all_items' => __( 'All Moves', 'ivanhoe' ),
                'add_new_item' => __( 'Add New Move', 'ivanhoe' ),
                'view_item' => __( 'View Move', 'ivanhoe' ),
                'edit_item' => __( 'Edit Move', 'ivanhoe' )
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
                'name' => __( 'Games', 'ivanhoe' ),
                'singular_name' => __( 'Game', 'ivanhoe' ),
                'all_items' => __( 'All Games', 'ivanhoe' ),
                'add_new_item' => __( 'Add New Game', 'ivanhoe' ),
                'view_item' => __( 'View Game', 'ivanhoe' ),
                'edit_item' => __( 'Edit Game', 'ivanhoe' )
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
                'name' => __( 'Roles', 'ivanhoe' ),
                'singular_name' => __( 'Role', 'ivanhoe' ),
                'all_items' => __( 'All Roles', 'ivanhoe' ),
                'add_new_item' => __( 'Add New Role', 'ivanhoe' ),
                'view_item' => __( 'View Role', 'ivanhoe'),
                'edit_item' => __( 'Edit Role', 'ivanhoe' )
                ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'roles'),
            )
        );


    add_post_type_support( 'ivanhoe_role', 'thumbnail' );

    add_rewrite_rule( 'games/([.*]+)/page/([0-9]+)/?$', 'index.php?ivanhoe_game=[1]&paged=$matches[2]', 'top' );

        register_post_type(
        'ivanhoe_role_journal',
        array(
            'labels' => array(
                'name'          => __( 'Role Journal', 'ivanhoe' ),
                'singular_name' => __( 'Role Journal Entry', 'ivanhoe' ),
                'all_items'     => __( 'All Entries', 'ivanhoe' ),
                'add_new_item'  => __( 'Add New Entry', 'ivanhoe' ),
                'view_item' => __( 'View Entry', 'ivanhoe' ),
                'edit_item' => __( 'Edit Entry', 'ivanhoe' )
            ),
            'public'        => true,
            'has_archive'   => true,
            'rewrite'       => array( 'slug' => 'rolejournal' )
        )
    );

    add_post_type_support(
        'ivanhoe_role_journal',
        'custom-fields'
        );

}

/**
 * Generate HTML for Ivanhoe Move metabox.
 *
 * @param WP_Post
 * @return string The HTML for the form element.
 */
function ivanhoe_move_meta_box($post)
{
    $html = '<p><label for="post_parent">'.__('Game', 'ivanhoe' ).'</label></p>'
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
        __('Source for:', 'ivanhoe' ),
        'ivanhoe_move_source_meta_box',
        'ivanhoe_move'
    );
}

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function ivanhoe_move_source_meta_box( $post ) {

    // Add an nonce field so we can check for it later.
    wp_nonce_field( 'ivanhoe_move_source_meta_box', 'ivanhoe_move_source_meta_box_nonce' );

    /*
     * Use get_post_meta() to retrieve an existing value
     * from the database and use the value for the form.
     */
    $value = get_post_meta( $post->ID, 'Ivanhoe Move Source', true );

    echo '<label for="ivanhoe_move_source">';
    __( 'Source for:', 'ivanhoe' );
    echo '</label> ';
    echo '<input type="text" id="ivanhoe_move_source" name="ivanhoe_move_source" value="' . esc_attr( $value ) . '" size="25" />';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function ivanhoe_move_source_save_meta_box_data( $post_id ) {

    /*
     * We need to verify this came from our screen and with proper authorization,
     * because the save_post action can be triggered at other times.
     */

    // Check if our nonce is set.
    if ( ! isset( $_POST['ivanhoe_move_source_meta_box_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['ivanhoe_move_source_meta_box_nonce'], 'ivanhoe_move_source_meta_box' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'ivanhoe_move' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

    } else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    /* OK, its safe for us to save the data now. */

    // Make sure that it is set.
    if ( ! isset( $_POST['ivanhoe_move_source'] ) ) {
        return;
    }

    // Sanitize user input.
    $my_data = sanitize_text_field( $_POST['ivanhoe_move_source'] );

    // Update the meta field in the database.
    update_post_meta( $post_id, 'Ivanhoe Role ID', $my_data );
}
add_action( 'save_post', 'ivanhoe_move_source_save_meta_box_data' );

/**
 * Function for getting the metadata for the post(s) to which the current move responds
 */
function ivanhoe_move_response()
{
    add_meta_box(
        'ivanhoe_move_response',
        __('Responds to:', 'ivanhoe' ),
        'ivanhoe_response_meta_box',
        'ivanhoe_move'
    );
}

/**
* Function to create metadata box for role associated with journal entries
*/
function ivanhoe_role_for_journal()
{
    add_meta_box(
        'ivanhoe_role_for_journal',
        __('Journal Entry For:', 'ivanhoe' ),
        'ivanhoe_role_id_meta_box',
        'ivanhoe_role_journal'
    );
}

add_action('add_meta_boxes', 'ivanhoe_move_source', 'ivanhoe_move_response', 'ivanhoe_role_for_journal');

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function ivanhoe_role_id_meta_box( $post ) {

    // Add an nonce field so we can check for it later.
    wp_nonce_field( 'ivanhoe_role_id_meta_box', 'ivanhoe_role_id_meta_box_nonce' );

    /*
     * Use get_post_meta() to retrieve an existing value
     * from the database and use the value for the form.
     */
    $value = get_post_meta( $post->ID, 'Ivanhoe Role ID', true );

    echo '<label for="ivanhoe_role_for_journal">';
    __( 'Journal Entry For:', 'ivanhoe' );
    echo '</label> ';
    echo '<input type="text" id="ivanhoe_role_id" name="ivanhoe_role_id" value="' . esc_attr( $value ) . '" size="25" />';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function ivanhoe_role_id_save_meta_box_data( $post_id ) {

    /*
     * We need to verify this came from our screen and with proper authorization,
     * because the save_post action can be triggered at other times.
     */

    // Check if our nonce is set.
    if ( ! isset( $_POST['ivanhoe_role_id_meta_box_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['ivanhoe_role_id_meta_box_nonce'], 'ivanhoe_role_id_meta_box' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'ivanhoe_role_journal' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

    } else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    /* OK, its safe for us to save the data now. */

    // Make sure that it is set.
    if ( ! isset( $_POST['ivanhoe_role_id'] ) ) {
        return;
    }

    // Sanitize user input.
    $my_data = sanitize_text_field( $_POST['ivanhoe_role_id'] );

    // Update the meta field in the database.
    update_post_meta( $post_id, 'Ivanhoe Role ID', $my_data );
}
add_action( 'save_post', 'ivanhoe_role_id_save_meta_box_data' );

/**
 * Create a custom nav menu for the theme.
 */
function ivanhoe_make_menus() {

    $menu_name = 'ivanhoe_default';
    $menu_id = wp_create_nav_menu($menu_name);

    // Set up default menu items
    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' =>  __('Home', 'ivanhoe' ),
        'menu-item-classes' => 'home',
        'menu-item-url' => home_url( '/' ),
        'menu-item-status' => 'publish')
    );

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' =>  __('Games', 'ivanhoe' ),
        'menu-item-url' => get_post_type_archive_link('ivanhoe_game'),
        'menu-item-status' => 'publish')
    );
}

function ivanhoe_register_nav_menus() {
    ivanhoe_make_menus();
    register_nav_menu('ivanhoe_default',__( 'Ivanhoe Default', 'ivanhoe'  ));
}

add_action( 'after_switch_theme', 'ivanhoe_register_nav_menus' );

/**
 * Append links to the main nav menu.
 */
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


/**
 * Things to run when users switch to a different theme.
 */
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

add_action( 'switch_theme', 'ivanhoe_switch_themes');


/**
 * Things to do when users switch to the Ivanhoe theme.
 */
function ivanhoe_after_switch_theme()
{
    if (! get_option('ivanhoe_installed')) {
       $pages = array(
            'ivanhoe_move' => __( 'Make a Move', 'ivanhoe' ),
            'ivanhoe_role' => __( 'Make a Role', 'ivanhoe' )
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

add_action ( 'after_switch_theme','ivanhoe_after_switch_theme' );

/**
 * Let WordPress know about our text domain.
 */
function ivanhoe_load_theme_textdomain() {

    load_theme_textdomain('ivanhoe', get_template_directory() . '/languages');

}

add_action('after_setup_theme', 'ivanhoe_load_theme_textdomain');

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


/**
 * Calls the source metadata for each move and displays it.
 *
 * @param WP_Post.
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
        $html = '<h3>' . __('Source', 'ivanhoe' ) . '</h3>'
              . '<ul><li>'
              . '<a href="'.get_permalink($source->ID).'">'.$source->post_title.'</a>'
              . '</li></ul>';
    }

   // Print out the value of $html.
    echo $html;
}

/**
 * Gets responses for a move and displays them.
 *
 * @param WP_Post
 */
function ivanhoe_get_move_responses( $post )
{
    $html = '';

    $args = array(
        'post_type' => 'ivanhoe_move',
        'post_per_page' => -1,
        'meta_key' => 'Ivanhoe Move Source',
        'meta_value' => $post->ID,
        'meta_value_compare' => '='
        );

    $responses = get_posts( $args );

    if ($responses) {

        $html = '<h3>' . __('Responses', 'ivanhoe') . '</h3>'
              . '<ul>';

        foreach ( $responses as $response ) {
            $html .= '<li><a href="' . get_permalink($response->ID) . '">' . $response->post_title . '</a></li>';
        }

        $html .= '</ul>';

    }

    echo $html;

}

/**
 * Respond to move helper function.
 *
 * @param WP_Post
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

/**
 * URL to new Ivanhoe role form.
 *
 * @param WP_Post
 */
function ivanhoe_role_form_url( $post )
{
    $url = "";

    if ($post->post_type == 'ivanhoe_game'){
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

/**
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

/**
 * Display role name on the author posts link.
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
            esc_attr( sprintf( __('Posts by %s', 'ivanhoe'), get_the_author() ) ),
            $role->post_title
            );
    }

    return $link;
}

add_filter( 'the_author_posts_link', 'ivanhoe_display_role_name', 10, 1);

/**
 * Displays custom pagination links.
 *
 * @param $query WP_Query.
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

/**
 * Function to display the 'respond to this move' button only when user has role.
 *
 * @param WP_Post.
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

    if ( $role ) {

        $link_string = sprintf( __( 'Respond <span class="visuallyhidden post-title">to %s</span>', 'ivanhoe' ), $post->post_title );
        $html = '<a href="'.ivanhoe_response_form_url( $post ).'" class="button">'
              . $link_string
              . '</a>';
    }

    return $html;

}

/**
 * Returns the first image found in the post content.
 *
 * @return string HTML.
 */
function catch_that_properly_nested_html_media_tag_tree() {
    global $post, $posts;
    ob_start();
    ob_end_clean();
    $shortcoded = do_shortcode($post->post_content);
    $output_videos = preg_match_all('/<(img|embed|iframe|video|audio)[^>]*>(.*?<\\/\1>)?/si', $shortcoded, $matches);
    return( $matches );
}

function display_first_media_file( $matches ) {
    $first_media_file = '';

    if ( !empty( $matches [0] ) ) {
        $first_media_file = $matches [0] [0];
    }

    return $first_media_file;
}

/**
 * Returns the excerpt for a given post, shortened to 55 words.
 *
 * @return string HTML.
 */
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

/**
 * Returns the title of a post by ID.
 *
 * @return string HTML.
 */
function get_title_by_id($post_id){
    $the_post = get_post($post_id); //Gets post ID
    $the_title = $the_post->post_title; //Gets post_content to be used as a basis for the excerpt
    $the_title = '<h3>' . $the_title . '</h3>';
    return $the_title;
}

/**
 * Helper to set featured images.
 *
 * @return int The attachment ID.
 */
function ivanhoe_add_image( $file_handler, $parent_post_id) {

    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    $attach_id = media_handle_upload( $file_handler, $parent_post_id );

    set_post_thumbnail($parent_post_id, $attach_id);

    return $attach_id;
}

/**
 * Retrieve ivanhoe_role_journal post for a given move.
 *
 * @param WP_Post the Ivanhoe Move post object.
 * @return string HTML.
 */
function ivanhoe_get_rationales( $post )
{

    $html = '';

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

    $journal_entries = get_posts( $args );

    if ($journal_entries) {

        $html = '<ul>';

        foreach ( $journal_entries as $journal_entry ) {
            $html .= '<li><a href="' . get_permalink($journal_entry->ID) . '">' . $journal_entry->post_title . '</a></li>';
        }

        $html .= '</ul>';

    } else {

        $html = __( 'There are no journal entries', 'ivanhoe' );

    }

    echo $html;
}

/**
 * Closes off dashboard to non-admin users; redirects to homepage.
 */
function restrict_admin_with_redirect()
{
    if ( ! current_user_can( 'manage_options' ) && $_SERVER['PHP_SELF'] != '/wp-admin/admin-ajax.php' ) {
        wp_redirect( site_url() ); exit;
    }
}

add_action( 'admin_init', 'restrict_admin_with_redirect' );

/**
 * Creates a "Read More" link to post at the end of the_excerpt.
 */

function new_excerpt_more( $more ) {
    return ' ... <a class="view-more" href="'. get_permalink( get_the_ID() ) . '">' . __('View More', 'your-text-domain') . '</a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );
