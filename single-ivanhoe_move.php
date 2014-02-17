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
				echo "Source post:";
			?>
			<a href="<?php echo $source_permalink ?>"><?php echo $source_title ?></a>
			<?php
			} 	
		?>	

		<?php

			$args = array(
				'post_type' => 'ivanhoe_move',
				'post_per_page' => -1,
				'meta_key' => 'Ivanhoe Move Source',
				'meta_value' => $post->ID,
				'meta_value_compare' => '='
				);

			$source_query = new WP_Query( $args );

            if ($source_query->have_posts() ) : ?>
            <h2>Responses</h2>
            <ul>
			<?php while( $source_query->have_posts() ) : $source_query->the_post(); ?>

			<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>	

		    <?php endwhile; ?>
		    </ul>
		    <?php else : ?>
		<p>There are no responses to this post.</p>
	<?php endif; ?>
 
</article>

<?php endwhile; else : ?>
<p>OMG NO POSTS!!!!!</p>
<?php endif; ?>

<?php get_footer(); ?>
