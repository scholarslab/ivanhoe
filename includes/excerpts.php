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
