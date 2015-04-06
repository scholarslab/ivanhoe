<?php

// Add theme support for WP features.
add_theme_support('menus');
add_theme_support('post-thumbnails', array('ivanhoe_role', 'ivanhoe_game'));
add_theme_support( 'automatic-feed-links' );

// Queue infinite scroll script
function add_infinite_scroll_script ()
{
    wp_enqueue_script(
        'jquery.infinitescroll.min', 
        get_template_directory_uri() . '/javascripts/infinite-scroll/jquery.infinitescroll.min.js',
        array('jquery'),
        null,
        true
        );
}

add_action('wp_enqueue_scripts', 'add_infinite_scroll_script');

/**
 * Modify the admin bar to remove nodes, preventing people from making
 * moves, roles, or role journal entires without proper metadata
 */
add_action( 'admin_bar_menu', 'ivanhoe_modify_admin_bar', 999 );

function ivanhoe_modify_admin_bar( $wp_admin_bar )
{
    $wp_admin_bar->remove_node( 'new-ivanhoe_move' );
    $wp_admin_bar->remove_node( 'new-ivanhoe_role' );
    $wp_admin_bar->remove_node( 'new-ivanhoe_role_journal' );

    if( ! current_user_can( 'manage_options' ) )
    {
        $wp_admin_bar->remove_node( 'dashboard' );
    }
}

function ivanhoe_page_menu( $args = array() )
{

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
function ivanhoe_nav_menu_items()
{
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

function ivanhoe_append_nav_menu_items( $items )
{
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
function ivanhoe_after_switch_theme()
{
    ivanhoe_flush_rewrite_rules();
}

add_action( 'after_switch_theme', 'ivanhoe_after_switch_theme' );

/**
 * Let WordPress know about our text domain.
 */
function ivanhoe_load_theme_textdomain()
{

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
function ivanhoe_redirect_canonical( $redirect_url, $requested_url )
{

    if ( is_singular( 'ivanhoe_game' ) ) {

        return $requested_url;

    }

    return $redirect_url;

}

add_filter( 'redirect_canonical', 'ivanhoe_redirect_canonical', 10, 2 );

/**
 * Registers our theme's javascripts.
 */
function ivanhoe_enqueue_scripts()
{

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

function ivanhoe_print_errors($errors)
{
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
function ivanhoe_flush_rewrite_rules()
{
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}

/**
 * Adds some rewrite rules for our forms.
 */
function ivanhoe_rewrite_rules_array($rules)
{
    $newrules = array();
    $newrules['ivanhoe/(.+)'] = 'index.php?ivanhoe=$matches[1]';
    return $newrules + $rules;
}

add_action( 'rewrite_rules_array', 'ivanhoe_rewrite_rules_array' );

/**
 * Adds query variables for our form pages.
 */
function ivanhoe_query_vars($vars)
{
    array_push($vars, 'ivanhoe');
    return $vars;
}

add_filter( 'query_vars', 'ivanhoe_query_vars' );


/**
 * Display the proper form template depending on the URL/query.
 */
function ivanhoe_public_template()
{

    global $wp_query;

    $ivanhoe = isset($wp_query->query_vars['ivanhoe']);

    if(!empty($ivanhoe)) {

        $template = dirname( dirname(__FILE__ )) . '/ivanhoe-post-form.php';
        include($template);
        exit;

    }
}

add_filter( 'template_redirect', 'ivanhoe_public_template' );


function ivanhoe_ajax_upload_attachment()
{
    error_log(print_r($_REQUEST, true));
}

add_action('add_attachment', 'ivanhoe_ajax_upload_attachment');
