<?php get_header(); ?>

<?php 

$original_query = $wp_query;
$ivanhoe_game_id = $post->ID;
$ivanhoe_parent_permalink = get_permalink( $post->ID );
$role = ivanhoe_user_has_role( $post->ID );

?>

<?php if (have_posts()) : while(have_posts()) : the_post(); ?>
<article class="game">
    <header>
        <h1><?php the_title(); ?></h1>
        <p><span class="citation">Playing since</span>: <span class="italic"><?php the_date(); ?></span></p> 
        <?php

        if ( is_user_logged_in() ) :

            if ( $role ) :

                $url = add_query_arg(
                    "parent_post",
                    $ivanhoe_game_id,
                    get_permalink(get_option('ivanhoe_move_page'))
                );
            ?>
            <a href="<?php echo $url; ?>" class="button" id="make-a-move">Make a move</a>

            <?php else : ?>

            <a href="<?php echo ivanhoe_role_form_url( $post ); ?>" class="button">Make a Role!</a>

            <?php endif; ?>

        <?php endif; ?>
        
    </header>   

    <div id="game-data">
        <?php if($role): ?>

        <h3>Your Current Role</h3>
        <article class="role">
        <?php echo get_the_post_thumbnail($role->ID, 'thumbnail'); ?>
        <?php echo $role->post_title; ?>

    </article>

        <?php endif; ?>

        <h3>Game Description</h3>

        <?php the_content(); ?>

    </div>

    <?php

    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $args = array (
        'post_type' => 'ivanhoe_move',
        'post_parent' => $post->ID,
        'paged' => $paged,
        'posts_per_page' => 10);
    $wp_query = new WP_Query( $args );


    if ( $wp_query->have_posts()) : ?>

    <div id="moves">
        <div class="pagination">
            <?php ivanhoe_paginate_links($wp_query);?>
        </div>

        <?php
        while($wp_query->have_posts()) : $wp_query->the_post(); ?>
        <article class="move">
            <header>
                <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                <p><span class="citation">By:</span><span class="author-date"><?php the_author_posts_link(); ?></span>
                · <time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('F j, Y'); ?></time></p>
                <?php echo ivanhoe_move_link( $post ); ?>
            </header>

            <div class="excerpt">

                <?php
                $move_image_source = catch_that_image();

                echo $move_image_source;
                the_excerpt();
                ?>

            </div>
            
            <div class="game-discussion-source">
                <?php ivanhoe_get_move_source( $post ); ?>
            </div>
            
            <div class="game-discussion-response">
                <?php ivanhoe_get_move_responses( $post ); ?>  
            </div>

        </article>

        <?php endwhile; ?>
        
        <div class="pagination">
            <?php ivanhoe_paginate_links($wp_query);?>
        </div>
    </div>

    

    <?php else : ?>

    <p>No one has made a move yet in this game.  Make the first move!</p>

    <?php endif; ?>

<?php $wp_query = $original_query; ?>

</article>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
