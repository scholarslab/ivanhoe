<?php 
add_action( 'init', 'create_post_type' );
function create_post_type() {
    register_post_type( 'ivanhoe_move',
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
}
?>