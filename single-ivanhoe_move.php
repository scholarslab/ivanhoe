<?php get_header(); ?>
<?php if (have_posts()) : while(have_posts()) : the_post(); ?>
<article>
    <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
    <?php the_content();

		ivanhoe_get_move_source( $post );	
		ivanhoe_get_move_responses( $post );
	?>	

	<a href="<?php echo get_permalink( $post->post_parent ); ?>">Return to game</a>

	<?php echo $post->post_parent; ?>
</article>

<?php endwhile; else : ?>
<p>OMG NO POSTS!!!!!</p>
<?php endif; ?>

<?php get_footer(); ?>
