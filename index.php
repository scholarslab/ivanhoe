<?php get_header(); ?>

</div>
<?php if (have_posts()) : ?>
<?php echo ivanhoe_paginate_links();?>
<?php while(have_posts()) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header>
            <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>

            <p><span class="byline"><?php the_author_posts_link(); ?></span>
            &middot; <time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('F j, Y'); ?></time></p>
    </header>

    <?php the_content(); ?>
    <?php comments_template(); ?>
</article>

<?php endwhile; ?>
<?php echo ivanhoe_paginate_links();?>
<?php else : ?>
<p><?php _e( 'Apologies, but no results were found.', 'ivanhoe' ); ?></p>
<?php endif; ?>

<?php get_footer();
