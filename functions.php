<?php

// Add theme support for WP features.
add_theme_support('menus');
add_theme_support('post-thumbnails', array('ivanhoe_role', 'ivanhoe_game'));
add_theme_support( 'automatic-feed-links' );

/**
 * Custom backgrounds.
 */
$ivanhoe_background_defaults = array(
    'default-color' => '#fff',
    'default-image' => get_template_directory_uri() . '/images/tile.png'
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
                'name'          => __( 'Moves', 'ivanhoe' ),
                'singular_name' => __( 'Move', 'ivanhoe' ),
                'all_items'     => __( 'All Moves', 'ivanhoe' ),
                'add_new_item'  => __( 'Add New Move', 'ivanhoe' ),
                'view_item'     => __( 'View Move', 'ivanhoe' ),
                'edit_item'     => __( 'Edit Move', 'ivanhoe' )
            ),
            'public'      => true,
            'has_archive' => true,
            'rewrite'     => array('slug' => 'moves')
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
                'name'          => __( 'Games', 'ivanhoe' ),
                'singular_name' => __( 'Game', 'ivanhoe' ),
                'all_items'     => __( 'All Games', 'ivanhoe' ),
                'add_new_item'  => __( 'Add New Game', 'ivanhoe' ),
                'view_item'     => __( 'View Game', 'ivanhoe' ),
                'edit_item'     => __( 'Edit Game', 'ivanhoe' )
            ),
            'public'       => true,
            'has_archive'  => true,
            'rewrite'      => array('slug' => 'games'),
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

    add_post_type_support( 'ivanhoe_game', 'thumbnail' );

    register_post_type(
        'ivanhoe_role',
        array(
            'labels' => array(
                'name'          => __( 'Roles', 'ivanhoe' ),
                'singular_name' => __( 'Role', 'ivanhoe' ),
                'all_items'     => __( 'All Roles', 'ivanhoe' ),
                'add_new_item'  => __( 'Add New Role', 'ivanhoe' ),
                'view_item'     => __( 'View Role', 'ivanhoe'),
                'edit_item'     => __( 'Edit Role', 'ivanhoe' )
            ),
            'public'      => true,
            'has_archive' => true,
            'rewrite'     => array('slug' => 'roles'),
        )
    );

    add_post_type_support( 'ivanhoe_role', 'thumbnail' );

    add_rewrite_rule(
        'games/([.*]+)/page/([0-9]+)/?$',
        'index.php?ivanhoe_game=[1]&paged=$matches[2]',
        'top'
    );

    register_post_type(
        'ivanhoe_role_journal',
        array(
            'labels' => array(
                'name'          => __( 'Role Journal', 'ivanhoe' ),
                'singular_name' => __( 'Role Journal Entry', 'ivanhoe' ),
                'all_items'     => __( 'All Entries', 'ivanhoe' ),
                'add_new_item'  => __( 'Add New Entry', 'ivanhoe' ),
                'view_item'     => __( 'View Entry', 'ivanhoe' ),
                'edit_item'     => __( 'Edit Entry', 'ivanhoe' )
            ),
            'public'      => true,
            'has_archive' => true,
            'rewrite'     => array( 'slug' => 'rolejournal' )
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
function ivanhoe_move_meta_box($post=null)
{
    $post = (is_null($post)) ? get_post() : $post;
    $html = '<p><label for="post_parent">' . __('Game', 'ivanhoe' )
        . '</label></p>'
        . '<p><input type="text" name="post_parent" value="'
        . $post->post_parent . '">';

    return $html;
}

/**
 * Function for getting the metadata for the post(s) which respond to the
 * current move
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
function ivanhoe_move_source_meta_box($post=null) {
    $post = (is_null($post)) ? get_post() : $post;

    // Add an nonce field so we can check for it later.
    wp_nonce_field(
        'ivanhoe_move_source_meta_box',
        'ivanhoe_move_source_meta_box_nonce'
    );

    /*
     * Use get_post_meta() to retrieve an existing value
     * from the database and use the value for the form.
     */
    $value = get_post_meta( $post->ID, 'Ivanhoe Move Source', true );

    echo '<label for="ivanhoe_move_source">';
    __( 'Source for:', 'ivanhoe' );
    echo '</label> ';
    echo '<input type="text" id="ivanhoe_move_source" '
        . 'name="ivanhoe_move_source" value="' . esc_attr( $value ) .
        '" size="25" />';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function ivanhoe_move_source_save_meta_box_data($post_id=null) {
    $post_id = (is_null($post_id)) ? get_post()->ID : $post_id;

    /*
     * We need to verify this came from our screen and with proper
     * authorization, because the save_post action can be triggered at other
     * times.
     */

    // Check if our nonce is set.
    if ( ! isset( $_POST['ivanhoe_move_source_meta_box_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    $nonce = $_POST['ivanhoe_move_source_meta_box_nonce'];
    if ( ! wp_verify_nonce( $nonce, 'ivanhoe_move_source_meta_box' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't
    // want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) &&
         'ivanhoe_move' == $_POST['post_type'] ) {

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
 * Function for getting the metadata for the post(s) to which the current move
 * responds
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

add_action(
    'add_meta_boxes',
    'ivanhoe_move_source',
    'ivanhoe_move_response',
    'ivanhoe_role_for_journal'
);

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function ivanhoe_role_id_meta_box($post=null) {
    $post = (is_null($post)) ? get_post() : $post;

    // Add an nonce field so we can check for it later.
    wp_nonce_field(
        'ivanhoe_role_id_meta_box',
        'ivanhoe_role_id_meta_box_nonce'
    );

    /*
     * Use get_post_meta() to retrieve an existing value
     * from the database and use the value for the form.
     */
    $value = get_post_meta( $post->ID, 'Ivanhoe Role ID', true );

    echo '<label for="ivanhoe_role_for_journal">';
    __( 'Journal Entry For:', 'ivanhoe' );
    echo '</label> ';
    echo '<input type="text" id="ivanhoe_role_id" '
        . 'name="ivanhoe_role_id" value="' . esc_attr( $value )
        . '" size="25" />';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function ivanhoe_role_id_save_meta_box_data($post_id=null) {
    $post_id = (is_null($post_id)) ? get_post()->ID : $post_id;

    /*
     * We need to verify this came from our screen and with proper
     * authorization, because the save_post action can be triggered at other
     * times.
     */

    // Check if our nonce is set.
    if ( ! isset( $_POST['ivanhoe_role_id_meta_box_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    $nonce = $_POST['ivanhoe_role_id_meta_box_nonce'];
    if ( ! wp_verify_nonce( $nonce, 'ivanhoe_role_id_meta_box' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't
    // want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) &&
         'ivanhoe_role_journal' == $_POST['post_type'] ) {

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

function ivanhoe_page_menu( $args = array() ) {

    $defaults = array(
        'sort_column' => 'menu_order, post_title',
        'menu_class'  => 'menu',
        'echo'        => true,
        'link_before' => '',
        'link_after'  => ''
    );
    $args = wp_parse_args( $args, $defaults );

    /**
     * Filter the arguments used to generate a page-based menu.
     *
     * @since 2.7.0
     *
     * @see wp_page_menu()
     *
     * @param array $args An array of page menu arguments.
     */
    $args = apply_filters( 'wp_page_menu_args', $args );

    $menu = '';

    $list_args = $args;

    // Show Home in the menu
    if ( ! empty($args['show_home']) ) {
        if ( true === $args['show_home'] ||
            '1' === $args['show_home']   ||
            1 === $args['show_home'] ) {
            $text = __('Home', 'ivanhoe');
        } else {
            $text = $args['show_home'];
        }
        $class = '';
        if ( is_front_page() && !is_paged() )
            $class = 'class="current_page_item"';
        $menu .= '<li ' . $class . '><a href="' . home_url( '/' ) . '">'
            . $args['link_before'] . $text . $args['link_after'] . '</a></li>';
        // If the front page is a page, add it to the exclude list
        if (get_option('show_on_front') == 'page') {
            if ( !empty( $list_args['exclude'] ) ) {
                $list_args['exclude'] .= ',';
            } else {
                $list_args['exclude'] = '';
            }
            $list_args['exclude'] .= get_option('page_on_front');
        }
    }

    $list_args['echo'] = false;
    $list_args['title_li'] = '';
    $menu .= str_replace(
        array( "\r", "\n", "\t" ),
        '',
        wp_list_pages($list_args)
    );
    $menu .= ivanhoe_nav_menu_items();

    if ( $menu )
        $menu = '<ul>' . $menu . '</ul>';

    $menu = '<nav class="' . esc_attr($args['menu_class']) . '">' . $menu
        . "</nav>\n";

    /**
     * Filter the HTML output of a page-based menu.
     *
     * @since 2.7.0
     *
     * @see wp_page_menu()
     *
     * @param string $menu The HTML output.
     * @param array  $args An array of arguments.
     */
    $menu = apply_filters( 'wp_page_menu', $menu, $args );
    if ( $args['echo'] )
        echo $menu;
    else
        return $menu;
}

/**
 * Append links to the main nav menu.
 */
function ivanhoe_nav_menu_items() {
    global $wp;
    $items = '';

    $games_url = get_post_type_archive_link('ivanhoe_game');
    $games_label = __('Games', 'ivanhoe');

    $items .= "<li class='menu-item menu-item-type-custom "
        . "menu-item-object-custom menu'>"
        . "<a href='$games_url'>$games_label</a></li>";

    if (is_user_logged_in()) {
        $user        = wp_get_current_user();
        $profile_url = get_author_posts_url($user->ID);
        $profile_label = __('Profile', 'ivanhoe');
        $items .= "<li class='menu-item menu-item-type-custom "
            . "menu-item-object-custom menu'>"
            . "<a href='$profile_url'>$profile_label</a></li>";
    }

    $current_url = add_query_arg(
        $wp->query_string,
        '',
        home_url( $wp->request )
    );

    $items .= "<li class='menu-item menu-item-type-custom "
        . "menu-item-object-custom menu'>"
        . wp_loginout( $current_url, false )  . "</li>";

    if (!is_user_logged_in() && get_option('users_can_register')) {
        $registration_url = wp_registration_url();
        $registration_label = __('Register', 'ivanhoe');
        $items .= "<li class='menu-item menu-item-type-custom "
            . "menu-item-object-custom menu'>"
            . "<a href='$registration_url'>$registration_label</a></li>";
    }

    return $items;
}

function ivanhoe_append_nav_menu_items( $items ) {
    $items .= ivanhoe_nav_menu_items();

    return $items;
}

add_filter('wp_nav_menu_items', 'ivanhoe_append_nav_menu_items', 10, 2 );
register_nav_menu('header', 'header nav' );

/**
 * Things to run when users switch to a different theme.
 */
function ivanhoe_switch_themes()
{
    $menu_name = 'ivanhoe_default';

    wp_delete_post( get_option( 'ivanhoe_move_page' ), true );
    wp_delete_post( get_option( 'ivanhoe_role_page' ), true );
    wp_delete_post( get_option( 'ivanhoe_game_page' ), true );
    delete_option( 'ivanhoe_installed' );
    delete_option( 'ivanhoe_move_page' );
    delete_option( 'ivanhoe_role_page' );
    delete_option( 'ivanhoe_game_page' );

    $nav_menu = wp_get_nav_menu_object( $menu_name );

    if ($nav_menu) {
        wp_delete_nav_menu($nav_menu->term_id);
    }

    ivanhoe_flush_rewrite_rules();
}

add_action( 'switch_theme', 'ivanhoe_switch_themes');


/**
 * When switching to the Ivanhoe theme.
 */
function ivanhoe_after_switch_theme() {
    ivanhoe_flush_rewrite_rules();
}

add_action( 'after_switch_theme', 'ivanhoe_after_switch_theme' );

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
function ivanhoe_get_move_source($post=null)
{
    $post = (is_null($post)) ? get_post() : $post;
    $html = '';

    // Get the Move Source ID from custom post metadata.
    $source_id = get_post_meta($post->ID, 'Ivanhoe Move Source', true);

    // Check if $source_id isn't 0 and if we can get another post with its value.
    if ( $source_id && $source = get_post($source_id) ) {

        // Set $html to a string with a link to source post.
        $html = '<h3>' . __('Source', 'ivanhoe' ) . '</h3>'
            . '<ul><li>'
            . '<a href="' . get_permalink($source->ID) . '">'
            . $source->post_title . '</a>'
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
function ivanhoe_get_move_responses($post=null)
{
    $post = (is_null($post)) ? get_post() : $post;
    $html = '';

    $args = array(
        'post_type'          => 'ivanhoe_move',
        'post_per_page'      => -1,
        'meta_key'           => 'Ivanhoe Move Source',
        'meta_value'         => $post->ID,
        'meta_value_compare' => '='
    );

    $responses = get_posts( $args );

    if ($responses) {

        $html = '<h3>' . __('Responses', 'ivanhoe') . '</h3>'
            . '<ul>';

        foreach ( $responses as $response ) {
            $html .= '<li><a href="' . get_permalink($response->ID) . '">'
                . $response->post_title . '</a></li>';
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
function ivanhoe_response_form_url($post=null)
{
    $post    = (is_null($post)) ? get_post() : $post;
    $url     = "";
    $role    = ivanhoe_user_has_role($post->post_parent);
    $role_id = $role->ID;

    $ivanhoe_params = array(
        'ivanhoe'         => 'ivanhoe_move',
        "parent_post"     => $post->post_parent,
        "move_source"     => $post->ID,
        'ivanhoe_role_id' => $role_id
    );

    $url = add_query_arg(
        $ivanhoe_params,
        home_url()
    );

    return esc_url( $url );
}

/**
 * URL to new Ivanhoe role form.
 *
 * @param WP_Post
 */
function ivanhoe_role_form_url($post=null)
{
    $post = (is_null($post)) ? get_post() : $post;

    $args = array(
        'ivanhoe' => 'ivanhoe_role'
    );

    if ($post->post_type == 'ivanhoe_game'){
        $args['parent_post'] = $post->ID;
    } else {
        $args['parent_post'] = $post->post_parent;
    }

    $url = add_query_arg( $args, home_url() );

    return esc_url( $url );
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
            'author'      => $user_id,
            'post_type'   => 'ivanhoe_role'
        );
        $posts = get_posts($args);
        $role  = reset($posts);
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
        'post_type'   => 'ivanhoe_role',
        'author'      => $authordata->ID,
        'post_parent' => $post->post_parent
    );
    //$query = new WP_Query($args);
    //$posts = $query->get_posts();
    $posts = get_posts($args);
    $role = reset($posts);

    if ( $role && ($post->post_type !== 'post') ){
        $attr = esc_attr(
            sprintf( __('Posts by %s', 'ivanhoe'), get_the_author() )
        );
        $link = sprintf(
            '<a href="%1$s" title="%2$s" rel="author">%3$s</a>',
            esc_url( get_permalink($role->ID) ),
            $attr,
            $role->post_title
        );
    }

    return $link;
}

add_filter( 'the_author_posts_link', 'ivanhoe_display_role_name', 10, 1 );

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

    $html = '';

    $total_pages = $wp_query->max_num_pages;

    if ($total_pages > 1) {

        $base = str_replace(
            $big,
            '%#%',
            esc_url( get_pagenum_link( $big ) )
        );
        $html = '<div class="pagination">'
            . paginate_links( array(
                'base'      => $base,
                'format'    => '?paged=%#%',
                'current'   => max( 1, get_query_var('paged') ),
                'total'     => $wp_query->max_num_pages,
                'prev_text' => '<',
                'next_text' => '>'
            ) )
            . '</div>';
    }

    echo $html;

}

/**
 * Function to display the 'respond to this move' btn only when user has role.
 *
 * @param WP_Post.
 */
function ivanhoe_move_link ($post=null)
{
    $post = (is_null($post)) ? get_post() : $post;
    $html = '';
    $post_type = get_post_type($post );
    $role = false;
    if ($post_type == 'ivanhoe_game') {
        $role = ivanhoe_user_has_role( $post->ID );
    } elseif ($post_type == 'ivanhoe_move') {
        $role = ivanhoe_user_has_role( $post->post_parent );
    }

    if ( $role ) {

        $respond = __(
            'Respond <span class="visuallyhidden post-title">to %s</span>',
            'ivanhoe'
        );
        $link_string = sprintf( $respond, $post->post_title );
        $html = '<a href="' . ivanhoe_response_form_url( $post )
            . '" class="btn">'
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
    $output_videos = preg_match_all(
        '/<(img|embed|iframe|video|audio)[^>]*>(.*?<\\/\1>)?/si',
        $shortcoded,
        $matches
    );
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
function get_excerpt_by_id($post_id=null) {
    if (is_null($post_id)) {
        $the_post = get_post();
        $post_id  = $the_post->ID;
    } else {
        $the_post = get_post($post_id);
    }

    $the_excerpt    = $the_post->post_content;
    $excerpt_length = 55;
    $the_excerpt    = strip_tags(strip_shortcodes($the_excerpt));
    $words          = explode(' ', $the_excerpt, $excerpt_length + 1);

    if(count($words) > $excerpt_length) {
        array_pop($words);
        array_push($words, '&hellip;');
        $the_excerpt = implode(' ', $words);
    }

    $the_excerpt = '<p>' . $the_excerpt . '</p>';
    return $the_excerpt;
}

/**
 * Returns the title of a post by ID.
 *
 * @return string HTML.
 */
function get_title_by_id($post_id=null) {
    if (is_null($post_id)) {
        $the_post = get_post();
        $post_id  = $the_post->ID;
    } else {
        $the_post = get_post($post_id);
    }

    $the_title = $the_post->post_title;
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
function ivanhoe_get_rationales($post=null)
{
    $post = (is_null($post)) ? get_post() : $post;
    $html = '';

    $args = array(
        'post_type'     => 'ivanhoe_role_journal',
        'post_per_page' => -1,
        'author'        => $post->post_author,
        'meta_key'      => 'Ivanhoe Game Source',
        'meta_value'    => $post->post_parent
    );

    $journal_entries = get_posts( $args );
    if ($journal_entries) {

        $html = '<ul>';

        foreach ( $journal_entries as $journal_entry ) {
            $html .= '<li><a href="' . get_permalink($journal_entry->ID) . '">'
                . $journal_entry->post_title . '</a></li>';
        }

        $html .= '</ul>';

    } else {

        $html = __( 'There are no journal entries', 'ivanhoe' );

    }

    echo $html;
}

/**
 * Creates a "Read More" link to post at the end of the_excerpt.
 */

function new_excerpt_more( $more ) {
    return ' ... <a class="view-more" href="' . get_permalink( get_the_ID() )
        . '">' . __('View More', 'ivanhoe') . '</a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );

/**
 * Registers our theme's javascripts.
 */
function ivanhoe_enqueue_scripts() {

    wp_register_script(
        'ivanhoe_modernizr',
        get_stylesheet_directory_uri() . '/javascripts/modernizr.custom.min.js',
        array(),
        false,
        false
    );
    wp_register_script(
        'ivanhoe_respond',
        get_stylesheet_directory_uri() . '/javascripts/respond.min.js',
        array(),
        false,
        false
    );

    // enqueue the scripts for use in theme
    wp_enqueue_script (array('ivanhoe_modernizr', 'ivanhoe_respond'));

}

add_action('wp_enqueue_scripts', 'ivanhoe_enqueue_scripts');


/************
 * Error handling
 ************/

function print_errors($errors) {
    $html = <<<ERROR
      <div class="bs-callout bs-callout-danger">
        <h4>Errors</h4>
ERROR;
    foreach($errors as $message) {
      $html .= "<p>" . $message . "</p>";
    }

    $html .= "</div>";

    return $html;
}

/**
 * Function to flush WP's rewrite rules.
 */
function ivanhoe_flush_rewrite_rules() {
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}

/**
 * Adds some rewrite rules for our forms.
 */
function ivanhoe_rewrite_rules_array($rules) {
    $newrules = array();
    $newrules['ivanhoe/(.+)'] = 'index.php?ivanhoe=$matches[1]';
    return $newrules + $rules;
}

add_action( 'rewrite_rules_array', 'ivanhoe_rewrite_rules_array' );

/**
 * Adds query variables for our form pages.
 */
function ivanhoe_query_vars($vars) {
    array_push($vars, 'ivanhoe');
    return $vars;
}

add_filter( 'query_vars', 'ivanhoe_query_vars' );

/**
 * Display the proper form template depending on the URL/query.
 */
function ivanhoe_public_template() {

    global $wp_query;

    $ivanhoe = isset($wp_query->query_vars['ivanhoe']);

    if(!empty($ivanhoe)) {

        $template = dirname( __FILE__ ) . '/ivanhoe-post-form.php';
        include($template);
        exit;

    }
}

add_filter( 'template_redirect', 'ivanhoe_public_template' );

function ivanhoe_ajax_upload_attachment() {
    error_log(print_r($_REQUEST, true));
}

add_action('add_attachment', 'ivanhoe_ajax_upload_attachment');

function ivanhoe_add_feed() {
    $template = get_template_directory() . '/feed-ivanhoe.php';
    $post_type = get_query_var( 'post_type' );
    if( ($post_type == 'ivanhoe_game' || $post_type = 'ivanhoe_move') and file_exists( $template ) ) {
        load_template( $template );
    }
}

add_action('do_feed_ivanhoe', 'ivanhoe_add_feed', 10, 1);

/**
 * Enqueue d3js
 */
function ivanhoe_enqueue_d3js() {
    global $post_type;
    if ($post_type == 'ivanhoe_game') {
        wp_enqueue_script( 'd3js', get_template_directory_uri() . '/javascripts/d3.min.js', array(), null, false );
    }
}

add_action( 'wp_enqueue_scripts', 'ivanhoe_enqueue_d3js' );

function ivanhoe_echo_d3js_graph() {
    global $post, $post_type;

    $html = '';

    if ($post_type == 'ivanhoe_game') {
        $html = '
<script type="text/javascript">
var width = 500, height = 500;

var force = d3.layout.force()
    .charge(-120)
    .linkDistance(50)
    .size([width, height]);

var svg = d3.select("#game-data").append("svg")
    .attr("id", "playgraph")
    .attr("viewBox", "0 0 " + width + " " + height )
    .attr("preserveAspectRatio", "xMidYMid meet");

d3.json("'. add_query_arg('feed', 'ivanhoe', get_permalink($post->ID)) . '", function(error, graph) {
    force
      .nodes(graph.nodes)
      .links(graph.links)
      .start();

    var link = svg.selectAll(".link")
        .data(graph.links)
        .enter().append("line")
        .attr("class", "link");

    var node = svg.selectAll(".node")
        .data(graph.nodes)
        .enter().append("circle")
        .attr("class", "node")
        .attr("r", 10)
        .call(force.drag);

    force.on("tick", function() {
        link.attr("x1", function(d) { return d.source.x; })
            .attr("y1", function(d) { return d.source.y; })
            .attr("x2", function(d) { return d.target.x; })
            .attr("y2", function(d) { return d.target.y; });

        node.attr("cx", function(d) { return d.x; })
            .attr("cy", function(d) { return d.y; });
    });
});
</script>';


    }

    echo $html;
}

add_action('wp_footer', 'ivanhoe_echo_d3js_graph');

function ivanhoe_echo_d3js_styles() {
    global $post, $post_type;

    $html = 'foobar';

    if ($post_type == 'ivanhoe_game') {

        $html = '
<style type="text/css" media="all">
svg {
    background: white;
    border: 1px solid red;
    width: 100%;
    height: 300px;
}

.node {
    stroke: #fff;
  stroke-width: 2px;
  fill: rgba(255,200, 0, 1);
}

.link {
  stroke: rgba(0,0,0,0.25);
  stroke-width:1px;
}
</style>';

    }

    echo $html;
}

add_action('wp_head', 'ivanhoe_echo_d3js_styles');
