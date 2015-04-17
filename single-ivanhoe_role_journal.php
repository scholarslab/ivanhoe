<?php get_header(); ?>

</div>

<?php if (have_posts()) : while(have_posts()) : the_post(); ?>
<article>
    <h1><?php the_title(); ?></h1>
    <?php the_content(); ?>
    <?php echo get_the_title($post->post_parent); ?>
</article>

<?php endwhile; endif; ?>


<?php get_footer();
