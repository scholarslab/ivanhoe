<?php get_header(); ?>

<?php

if ( have_posts()) : while(have_posts()) : the_post(); ?>
<article>
    <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
    <?php the_content(); ?>
</article>

<?php endwhile; else : ?>
<p>OMG NO POSTS!!!!!</p>
<?php endif; ?>

</article>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
