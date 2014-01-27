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
}
