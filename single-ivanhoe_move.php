<?php get_header(); ?>
<?php if (have_posts()) : while(have_posts()) : the_post(); ?>
<article class="single-move">

    <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
    <?php the_content();

		ivanhoe_get_move_source( $post );	
		ivanhoe_get_move_responses( $post );
	?>	

	<p>Written by: <?php the_author_posts_link(); ?></p>

<?php echo ivanhoe_move_link ( $post ); ?>

<div id="return-button">

	<a href="<?php echo get_permalink( $post->post_parent ); ?>">Return to game</a>

</div>

</article>

<?php endwhile; else : ?>
<p>No one has made a move yet in this game.  Make the first move!</p>
<?php endif; ?>

<?php get_footer(); ?>
