<?php

get_header();
$curauth = ( isset( $_GET['author_name'] ) ) ? get_user_by( 'slug', $author_name ) : get_userdata( intval( $author ) );
?>

</div>

<article>
    <h1><?php echo $curauth->display_name; ?></h1>

    <?php
        $description = $curauth->user_description;
        if ($description) : ?>
            <div class="description">
                <h2><?php _e( 'Description', 'ivanhoe' ); ?></h2>
                <?php echo wpautop($description); ?>
            </div>
    <?php endif; ?>

    <?php

    $args = array
    (
        'post_type' => 'ivanhoe_role',
        'author' => $curauth->ID
    );
    $author_role_query = new WP_Query($args);

    if ($author_role_query->have_posts()) : ?>

    <h2><?php _e( 'Roles', 'ivanhoe' ); ?></h2>

    <div class="roles">

        <?php while($author_role_query->have_posts()) : $author_role_query->the_post();	?>

            <article class="role">
                <?php the_post_thumbnail('thumbnail'); ?>
                <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                <p class="game"><strong><?php _e( 'Game:', 'ivanhoe' ); ?> </strong><a href="<?php echo get_permalink( $post->post_parent ); ?>"><?php echo get_the_title( $post->post_parent ); ?></a></p>

                <?php
                $args = array
                (
                  'post_type' => 'ivanhoe_move',
                  'post_parent' => $post->post_parent,
                  'author' => $curauth->ID,
                  'posts_per_page' => '-1'
                );

                $moves_per_role_query = new WP_Query( $args );

                if ($moves_per_role_query->have_posts()) : ?>

                <ul class="moves">
                    <?php while($moves_per_role_query->have_posts()) : $moves_per_role_query->the_post(); ?>

                        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>

                    <?php endwhile; ?>
                </ul>

            <?php endif; ?>

            <?php wp_reset_postdata(); ?>
        </article>

        <?php
        endwhile;
        endif;
        ?>
    </div>
</article>

<?php get_footer();
