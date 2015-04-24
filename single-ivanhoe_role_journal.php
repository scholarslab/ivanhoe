<?php get_header(); ?>

<!-- Role, list of journals, other roles, return to game -->
 
 
 
 
</div>

<?php if (have_posts()) : while(have_posts()) : the_post(); ?>
<article class = "journal">
    <h1><?php the_title(); ?></h1>
    <?php the_content(); ?>

    <?php
    	$role_id = get_post_meta( $post->ID, 'Ivanhoe Role ID', true);
    	$role_page_url = get_post_permalink($role_id); 
    ?>

    <p class="return-btn">
            <?php echo ivanhoe_a($role_page_url, 'Return to role page'); ?>
    </p>
    
</article>

<?php endwhile; endif; ?>


<?php get_footer();
