<?php get_header(); ?>

<!-- Role, list of journals, other roles, return to game -->
 
 
 
 
</div>

<?php if (have_posts()) : while(have_posts()) : the_post(); ?>
<article class = "journal">
    <h1><?php the_title(); ?></h1>
    <?php the_content(); ?>
    
    <!-- add return to role page link: Return to X's Journal-->
    
</article>

<?php endwhile; endif; ?>


<?php get_footer();
