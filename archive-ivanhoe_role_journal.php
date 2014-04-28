<?php get_header(); ?>

<header>
<h1>Roles</h1>
</header>

<?php
if ( have_posts()) : while(have_posts()) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
    <?php the_excerpt(); ?>
</article>

<?php endwhile; else : ?>
<p>OMG NO POSTS!!!!!</p>
<?php endif; ?>

<?php get_footer();
