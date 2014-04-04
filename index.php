<?php get_header(); ?>
<?php if (have_posts()) : while(have_posts()) : the_post(); ?>
<article>
    <header>
            <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
            <p><span class="citation">By:</span><span class="author-date"><?php the_author_posts_link(); ?></span>
            · <time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('F j, Y'); ?></time></p>
    </header>

    <?php the_content(); ?>
    <?php comments_template(); ?>
</article>

<?php endwhile; ?>
<div id="pagination">
	<?php ivanhoe_paginate_links();?>
</div>
<?php else : ?>
<p>No one has made a move yet in this game.  Make the first move!</p>
<?php endif; ?>

<?php get_footer(); ?>
