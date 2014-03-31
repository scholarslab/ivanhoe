<?php get_header(); ?>

<?php
if ( have_posts()) : while(have_posts()) : the_post(); ?>
<article class="game">
    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <?php the_excerpt(); ?>
</article>

<?php endwhile; ?>
<div id="pagination">
	<?php ivanhoe_paginate_links();?>
</div>
<?php else : ?>
<p>No one has made a game yet.  Make the first game!</p>
<?php endif; ?>

<?php get_footer(); ?>
