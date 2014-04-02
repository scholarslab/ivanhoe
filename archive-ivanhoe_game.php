<?php get_header(); ?>

<header>
<h1>Games</h1>
</header>>

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
<p>There are no games yet.</p>
<?php endif; ?>

<?php get_footer(); ?>
