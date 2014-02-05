<?php get_header(); ?>

<?php

$args = array ( 'post_type' => 'ivanhoe_game' );
$query = new WP_Query( $args );

if ( $query->have_posts()) : while($query->have_posts()) : $query->the_post(); ?>
<article class="game">
    <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
    <?php the_excerpt(); ?>
</article>

<?php endwhile; else : ?>
<p>OMG NO POSTS!!!!!</p>
<?php endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
