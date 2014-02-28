<?php get_header(); ?>
<?php if (have_posts()) : while(have_posts()) : the_post(); ?>
<article>
    <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
    
    <?php the_content(); ?>	

	<h3>Moves:</h3>

	<?php 
		$args = array
		(
			'post_type' => "ivanhoe_move",
			'post_parent' => $post->post_parent,
			'author' => $post->post_author
		);

		$move_query = new WP_Query( $args );

		if ($move_query->have_posts()) : while($move_query->have_posts()) : $move_query->the_post(); 
	?>
	
	<ul>
		<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
	</ul>	

	<?php

		endwhile;
		endif;

		wp_reset_postdata();
	?>

	
	<a href="<?php echo get_permalink( $post->post_parent ); ?>">Return to game</a>

</article>

<?php endwhile; else : ?>
<p>OMG NO POSTS!!!!!</p>
<?php endif; ?>

<?php get_footer(); ?>