<?php get_header(); ?>

<header>
    <h1><?php _e( 'Games', 'ivanhoe' ); ?></h1>
    <?php if ( is_user_logged_in() ) :
    	$url = get_permalink(get_option('ivanhoe_game_page')); ?>
    	<a href="<?php echo $url; ?>" class="button" id="make-a-game"><?php _e( 'Make a Game', 'ivanhoe' ); ?></a>
	<?php endif; ?>
</header>

<?php
if ( have_posts()) : ?>
    <?php ivanhoe_paginate_links($wp_query);?>
    <?php while(have_posts()) : the_post(); ?>
<article class="game">
    <?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?>
    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <?php the_excerpt(); ?>
</article>

<?php endwhile; ?>
<?php ivanhoe_paginate_links();?>
<?php else : ?>
<p><?php _e( 'Apologies, but no results were found.', 'ivanhoe' ); ?></p>
<?php endif; ?>

<?php get_footer(); ?>
