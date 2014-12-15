<?php
    get_header();
    if (have_posts()) : while(have_posts()) : the_post();
    global $post;
    $game_id = $post->post_parent;
    $role = ivanhoe_user_has_role( $game_id );
    $parent_ID = $post->post_parent;
    $game_title = ivanhoe_get_title_by_id( $parent_ID );
    $game_excerpt = ivanhoe_get_excerpt_by_id( $parent_ID );
?>
<article class="single-move">

    <header>
        <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>

        <p>
            <span class="byline"><?php the_author_posts_link(); ?></span>
            &middot;
            <time datetime="<?php the_time('Y-m-d'); ?>">
                <?php the_time('F j, Y'); ?>
            </time>
        </p>
        <?php
            if ( is_user_logged_in() ) :
                if ( $role !== FALSE ) :
                    echo ivanhoe_move_link( $post );
                else : ?>

            <a href="<?php echo ivanhoe_role_form_url( $post ); ?>" class="btn">
            <?php _e( 'Make a Role!', 'ivanhoe' ); ?></a>

                <?php endif; ?>

            <?php endif; ?>

    </header>
    <div class="source-response-container">

        <div class="discussion-source">
            <?php echo ivanhoe_display_move_source( $post ); ?>
        </div>
        <div class="discussion-response">
            <?php echo ivanhoe_display_move_responses( $post ); ?>
        </div>
        <div class="game-description">
            <h2><?php _e( 'Game Description', 'ivanhoe' ); ?></h2>

            <?php
                echo('<h3>' . $game_title . '</h3>');
                echo $game_excerpt;
            ?>
        </div>
    </div>

    <div id="moves">

        <?php the_content(); ?>

        <p class="return-btn"><a href="<?php echo get_permalink( $post->post_parent ); ?>"><?php _e( 'Return to game', 'ivanhoe' ); ?></a></p>

    </div>

</article>

<?php endwhile; endif; ?>

<?php get_footer();
