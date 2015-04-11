<?php

/**
 * Move response form URL builder - passes post metadata to form.
 *
 * @param WP_Post
 */
function ivanhoe_response_form_url($post=null)
{
    $post    = (is_null($post)) ? get_post() : $post;
    $url     = "";
    $role    = ivanhoe_user_has_role($post->post_parent);
    if ($role === FALSE) {
        throw new Exception("Invalid POST (missing role).");
    }
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
 * Role form URL builder
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
function ivanhoe_user_has_role($game_id, $user_id=null)
{
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
        return FALSE;
    }
    return FALSE;
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
            get_pagenum_link( $big, false )
        );
        $html = '<div id="pagination">'
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

    return $html;

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
    $role = FALSE;
    if ($post_type == 'ivanhoe_game') {
        $role = ivanhoe_user_has_role( $post->ID );
    } elseif ($post_type == 'ivanhoe_move') {
        $role = ivanhoe_user_has_role( $post->post_parent );
    }

    if ( $role !== FALSE ) {

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
 * Returns the title of a post by ID.
 *
 * @return string HTML.
 */
function ivanhoe_get_title_by_id($post_id=null)
{
    if (is_null($post_id)) {
        $the_post = get_post();
        $post_id  = $the_post->ID;
    } else {
        $the_post = get_post($post_id);
    }

    $the_title = $the_post->post_title;
    return $the_title;
}

/**
 * Helper to set featured images.
 *
 * @return int The attachment ID.
 */
function ivanhoe_add_image( $file_handler, $parent_post_id)
{

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
 * @return WP_Query object - list of rationales
 */
function ivanhoe_get_rationales($post=null)
{
    $post = (is_null($post)) ? get_post() : $post;

    $args = array(
        'post_type'     => 'ivanhoe_role_journal',
        'post_per_page' => -1,
        'author'        => $post->post_author,
        'meta_key'      => 'Ivanhoe Game Source',
        'meta_value'    => $post->post_parent
    );

    $journal_entries = get_posts( $args );
    return $journal_entries;
}

/**
 * Display list of journal entries for a post
 *
 * @param WP_Post the Ivanhoe Move post object.
 * @return string html
 */
 function ivanhoe_display_rationales($post=null)
  {
    $html = '';

    $journal_entries = ivanhoe_get_rationales($post);

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

    return $html;
}
