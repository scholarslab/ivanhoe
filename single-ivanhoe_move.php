<?php get_header(); ?>
<?php if (have_posts()) : while(have_posts()) : the_post(); ?>
<article>
    <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
    <?php the_content();

		ivanhoe_get_move_source( $post );	
		ivanhoe_get_move_responses( $post );
	?>	

	<p>Written by: <?php the_author_posts_link(); ?></p>

	<a href="<?php echo ivanhoe_response_form_url( $post ); ?>" class="button">Respond to this move</a>

	<a href="<?php echo get_permalink( $post->post_parent ); ?>">Return to game</a>

</article>

<?php endwhile; else : ?>
<p>OMG NO POSTS!!!!!</p>
<?php endif; ?>

<?php get_footer(); ?>
