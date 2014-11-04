<?php

$game_id = $post->ID;

// An array for our JSON output.
$output = array(
            'nodes' => array(),
            'links' => array()
            );

// An empty index, which we'll use later for links.
$index = array();


// WP Query to get moves on the current game.
$args = array (
    'post_type' => 'ivanhoe_move',
    'post_parent' => $game_id,
    'posts_per_page' => -1,
    'order' => 'ASC',
    'orderby' => 'ID'
);

$moves = new WP_Query( $args );

// Start a counter for our index.
$i = 0;

if ($moves->have_posts()) : while ($moves->have_posts()) : $moves->the_post();

// 
$output['nodes'][] = array(
    'id' => get_the_ID(),
    'parent' => get_post_meta(get_the_ID(), 'Ivanhoe Move Source', true)
);

// Set the index to the current post ID.
$index[get_the_ID()] = $i;

// Increment our counter.
$i++;

endwhile; endif;

// Create the links array after our loop, now that we have an index of all our moves.
for ($i = 0; $i < count($output['nodes']); $i++) {

    $node = $output['nodes'][$i];

    // Only add a link if the move has a source.
    if ($node['parent']) {
        $output['links'][] = array(
            'source' => $index[$node['id']],
            'target' => $index[$node['parent']]
        );
    }

} 

wp_reset_query();

header("Content-type: application/json");
die(json_encode($output, JSON_PRETTY_PRINT));
