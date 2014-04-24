<?php get_header(); ?>
<?php if (have_posts()) : while(have_posts()) : the_post(); ?>

<article class="role">

    <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>

    <?php the_post_thumbnail('medium'); ?>
    
    <?php the_content(); ?>	

    <p class="return-button"><a href="<?php echo get_permalink( $post->post_parent ); ?>"><?php _e( 'Return to game', 'ivanhoe' ); ?></a></p>

    <div class="moves">

    <h2><?php _e( 'Moves', 'ivanhoe' ); ?></h2>

	<?php 
    $args = array
    (
        'post_type' => "ivanhoe_move",
        'post_parent' => $post->post_parent,
        'author' => $post->post_author
    );

    $move_query = new WP_Query( $args );

    if ($move_query->have_posts()) : ?>

    <ul>

    <?php while($move_query->have_posts()) : $move_query->the_post(); ?>

        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>

    <?php endwhile; ?>

    </ul>

    <?php endif; ?>

    <?php wp_reset_postdata(); ?>

    </div>

    <div class="rationales">

        <h2><?php _e( 'Rationales', 'ivanhoe' ); ?></h2>

        <?php ivanhoe_get_rationales( $post ); ?>

    </div>

</article>

<?php endwhile; ?>
<?php endif; ?>

<?php get_footer();
