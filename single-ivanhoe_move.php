<?php get_header(); ?>
<?php if (have_posts()) : while(have_posts()) : the_post(); ?>
<article>
    <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
    <?php the_content(); ?>
		<?php 
			$post_source = get_post_meta($post->ID, 'Ivanhoe Move Source', true);
			$source_title = get_the_title($post_source);
			$source_permalink = get_permalink($post_source);
			if ( !empty($post_source) )
			{ 
				echo "Source post:"
			?>
			<a href="<?php echo $source_permalink ?>"><?php echo $source_title ?></a>
			<?php
			} 	
		?>	
</article>

<?php endwhile; else : ?>
<p>OMG NO POSTS!!!!!</p>
<?php endif; ?>

<?php get_footer(); ?>
