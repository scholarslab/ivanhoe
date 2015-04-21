<?php
    // Moves
    $args = array
    (
        'post_type' => "ivanhoe_move",
        'post_parent' => $post->post_parent,
        'author' => $post->post_author
    );
    $move_query = new WP_Query( $args );



    global $post;
    $game_id = $post->post_parent;
    $game_title = ivanhoe_get_title_by_id($game_id);
?>
<?php get_header(); ?>

<h3><?php _e( 'Game Description', 'ivanhoe' ); ?></h3>
<?php
    if ( has_post_thumbnail($post->post_parent) ) { echo get_the_post_thumbnail($post->post_parent, 'medium'); }
    echo('<h3>' . $game_title . '</h3>');
    echo ivanhoe_game_excerpt($post);
?>

</div>
<?php if (have_posts()) : while(have_posts()) : the_post(); ?>

<article class="role">

    <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>

    <?php the_post_thumbnail('large'); ?>

    <?php the_content(); ?>

    <p class="return-btn">
            <?php $url = get_permalink( $post->post_parent); ?>
            <?php echo ivanhoe_a($url, 'Return to game'); ?>
    </p>

    <div class="moves">

        <h2><?php _e( 'Moves', 'ivanhoe' ); ?></h2>

        <?php //removed move logic from here
            if ($move_query->have_posts()) : ?>

        <ul>

            <?php while($move_query->have_posts()) : $move_query->the_post(); ?>

            <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>

            <?php endwhile; else: ?>

            <?php
                $html = __( 'There are no moves', 'ivanhoe' );
                echo $html;
            ?>

        </ul>

        <?php endif; ?>

        <?php wp_reset_postdata(); ?>

    </div>

    <div class="rationales">

        <h2><?php _e( 'Rationales', 'ivanhoe' ); ?></h2>

        <?php echo ivanhoe_display_rationales($post); ?>

    </div>

</article>

<?php endwhile; ?>
<?php endif; ?>

<?php get_footer();
