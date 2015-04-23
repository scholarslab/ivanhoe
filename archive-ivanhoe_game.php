<?php get_header(); ?>


    <?php if ( is_user_logged_in() ) : ?>
    	<?php $url = add_query_arg(array('ivanhoe' => 'ivanhoe_game'), home_url()); ?>
            <?php echo ivanhoe_a($url, 'Make a Game', 'class="btn" id="make-a-game"'); ?>
    <?php endif; ?>

</div>

<header class= "form-title">
    <h1><?php _e( 'Games', 'ivanhoe' ); ?></h1>
</header>

<div id = "box-container">
<?php
if ( have_posts()) : ?>
    <?php echo ivanhoe_paginate_links($wp_query);?>
    <?php while(have_posts()) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <a class="game-link" href="<?php the_permalink(); ?>">
        <?php if ( has_post_thumbnail() ) { the_post_thumbnail('medium'); } ?>
        <h2 class="game-title"><?php the_title(); ?></h2></a>
        <?php the_excerpt(); ?>
    </article>

<?php endwhile; ?>
<?php echo ivanhoe_paginate_links();?>
<?php else : ?>
<p><?php _e( 'Apologies, but no results were found.', 'ivanhoe' ); ?></p>
<?php endif; ?>

</div>

<?php get_footer();

