<?php

/**
 * Returns the excerpt for a given post, shortened to 55 words.
 *
 * @return string HTML.
 */
function ivanhoe_get_excerpt_by_id($post_id=null)
{
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
 * Creates a "Read More" link to post at the end of the_excerpt.
 */

function ivanhoe_new_excerpt_more( $more )
{
    return ' ... <a class="view-more" href="' . get_permalink( get_the_ID() )
        . '">' . __('View More', 'ivanhoe') . '</a>';
}

add_filter( 'excerpt_more', 'ivanhoe_new_excerpt_more' );

/**
 * Returns the first image found in the post content.
 *
 * @return string HTML.
 */
function ivanhoe_catch_that_properly_nested_html_media_tag_tree()
{
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

/**
* Returns the first image found in post content
*
* @return media file
*/

function ivanhoe_display_first_media_file( $matches )
{
    $first_media_file = '';

    if ( !empty( $matches [0] ) ) {
        $first_media_file = $matches [0] [0];
    }

    return $first_media_file;
}

/*
* Generates the game excerpt
*
* @param WP_Post ivanhoe move post object
* @return string html
*/


function ivanhoe_game_excerpt ($post)
{
    $post = (is_null($post)) ? get_post() : $post;
    $game_id = $post->post_parent;
    $role = ivanhoe_user_has_role( $game_id );
    $game_title = ivanhoe_get_title_by_id( $game_id );
    $game_excerpt = ivanhoe_get_excerpt_by_id( $game_id );
    return $game_excerpt;
}

/*
* Returns either media file attached to post or media file and more
* depending upon whether there is textual content in the move
*
* @return media file
*/

function ivanhoe_media_excerpt ()
{
    $the_excerpt = get_the_excerpt();
    $move_image_source = '';
    $matches = ivanhoe_catch_that_properly_nested_html_media_tag_tree();

    if ( empty( $the_excerpt ) ) {
        $move_image_source = ivanhoe_display_first_media_file( $matches ) . ' ... <a class="view-more" href="'. get_permalink( get_the_ID() ) . '">' . __('View More', 'ivanhoe') . '</a>';
    } else {
        $move_image_source = ivanhoe_display_first_media_file( $matches );
    }

    return $move_image_source;
}


