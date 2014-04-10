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
        <p><?php printf( __('Playing since: %s', 'ivanhoe' ), get_the_time('F j, Y') ); ?></p>
        <?php

        if ( is_user_logged_in() ) :

            if ( $role ) :

                $url = add_query_arg(
                        array(
                            'parent_post' => $ivanhoe_game_id,
                            'ivanhoe_role_id' => $role->ID
                            ),
                    get_permalink(get_option('ivanhoe_move_page'))
                );
            ?>
            <a href="<?php echo $url; ?>" class="button" id="make-a-move"><?php _e( 'Make a move', 'ivanhoe' ); ?></a>

            <?php else : ?>

            <a href="<?php echo ivanhoe_role_form_url( $post ); ?>" class="button"><?php _e( 'Make a Role!', 'ivanhoe' ); ?></a>

            <?php endif; ?>

        <?php endif; ?>

    </header>

    <div id="game-data">
        <?php if($role): ?>

        <h3><?php _e( 'Your Current Role', 'ivanhoe' ); ?></h3>
        <article class="role">
            <a href="<?php echo get_permalink( $role->ID); ?>" class="image-container"><?php echo get_the_post_thumbnail($role->ID, 'medium'); ?></a>
            <a href="<?php echo get_permalink( $role->ID ); ?>"><?php echo $role->post_title; ?></a>
        </article>

        <?php endif; ?>



            <?php
                $args = array(
                        'post_type' => 'ivanhoe_role',
                         'post_parent' => $ivanhoe_game_id
                    );
                $characters = new WP_Query ( $args );
                if ( $characters->have_posts() ) : while ( $characters->have_posts() ) : $characters->the_post();
                ?>
                <?php if ($post->ID !== $role->ID): ?>
                    <article>
                        <h3>Other Characters</h3>
                        <ul>
                            <li>
                                 <a href="<?php echo get_permalink( $post->ID); ?>" class="image-container"><?php echo get_the_post_thumbnail($post->ID, 'medium'); ?></a>
                                 <a href="<?php echo get_permalink( $post->ID ); ?>"><?php echo $post->post_title; ?></a>
                            </li>
                        </ul>
                    </article>
                <?php endif ?>

            <?php
                endwhile; endif;
                wp_reset_postdata();
            ?>

        <h3><?php _e( 'Game Description', 'ivanhoe' ); ?></h3>

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
                <p><span class="byline"><?php the_author_posts_link(); ?></span>
            · <time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('F j, Y'); ?></time></p>
                <?php echo ivanhoe_move_link( $post ); ?>
            </header>

            <div class="excerpt">

                <?php
                $move_image_source = catch_that_properly_nested_html_media_tag_tree();

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

    <p><?php _e( 'There are no moves for this game.', 'ivanhoe' ); ?></p>

    <?php endif; ?>

<?php $wp_query = $original_query; ?>

</article>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
