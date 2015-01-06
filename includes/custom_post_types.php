<?php
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
        'index.php?ivanhoe_game=$matches[1]&paged=$matches[2]',
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
