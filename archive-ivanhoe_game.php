<?php get_header(); ?>

<header>
    <h1><?php _e( 'Games', 'ivanhoe' ); ?></h1>
</header>

<?php
if ( have_posts()) : ?>
	<div class="pagination">
        <?php ivanhoe_paginate_links($wp_query);?>
    </div> 
    <?php while(have_posts()) : the_post(); ?>
<article class="game">
    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <?php the_excerpt(); ?>
</article>

<?php endwhile; ?>
<div class="pagination">
	<?php ivanhoe_paginate_links();?>
</div>
<?php else : ?>
<p><?php _e( 'Apologies, but no results were found.', 'ivanhoe' ); ?></p>
<?php endif; ?>

<?php get_footer(); ?>
