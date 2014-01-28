<?php 

add_action( 'init', 'create_post_type' );

function create_post_type() 
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
            'rewrite' => array('slug' => 'moves'),
            )
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
            )
        );
}

/**
 * Wrapper function for adding meta boxes for our custom post types
 */

function ivanhoe_move_meta_box($post)
{
    $html = '<p><label for="post_parent">'.__('Game').'</label></p>'
          . '<p><input type="text" name="post_parent" value="'. $post->post_parent.'">'
;    echo $html;
}

function ivanhoe_add_meta_boxes()
{
    add_meta_box(
        'ivanhoe_move_metadata',
        __('Ivanhoe Move Metadata'),
        'ivanhoe_move_meta_box',
        'ivanhoe_move')
;}

add_action('add_meta_boxes', 'ivanhoe_add_meta_boxes');

/**
 * Function to send the game id through the make_a_move html form
 */

function ivanhoe_get_game_id()
{
    $ivanhoe_game_id = $_POST["ivanhoe_game_id"];
}

