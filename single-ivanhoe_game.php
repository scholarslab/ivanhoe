<?php get_header(); ?>

<?php 

$original_query = $wp_query;
$ivanhoe_game_id = $post->ID;
$ivanhoe_parent_permalink = get_permalink( $post->ID );

 ?>

<?php if (have_posts()) : while(have_posts()) : the_post(); ?>

    <div id = "make-a-move-button">
        <?php
        $url = add_query_arg(
            "parent_post",
            $ivanhoe_game_id,
            get_permalink(get_option('ivanhoe_move_page'))
        );
        ?>
        <a href="<?php echo $url; ?>" class="button" id="make-a-move">Make a move</a>

        <?php the_content(); ?>
    </div>

<article>
    <h1><?php the_title(); ?></h1>

<?php

$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$args = array (
    'post_type' => 'ivanhoe_move',
    'post_parent' => $post->ID,
    'paged' => $paged,
    'posts_per_page' => 10);
$wp_query = new WP_Query( $args );

if ( $wp_query->have_posts()) : while($wp_query->have_posts()) : $wp_query->the_post(); ?>
<article>
    <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
    <?php the_excerpt(); ?>
	<?php
		$ivanhoe_move_source = $post->ID;
		$ivanhoe_param = array(
		"parent_post" => $ivanhoe_game_id,
		"move_source" => $ivanhoe_move_source
		);

		$url = add_query_arg(
   		$ivanhoe_param,
    	get_permalink(get_option('ivanhoe_move_page'))
        );

        //Pulls post source and displays it
        ivanhoe_get_move_source($post);

        //Pulls post responses and displays them
        ivanhoe_get_move_responses( $post );
        ?>  

<a href="<?php echo $url; ?>" class="button">Respond to this move</a>

</article>

<?php endwhile; ?>

<?php previous_posts_link('newer'); ?> | <?php next_posts_link('older'); ?>

<?php else : ?>

<p>OMG NO POSTS!!!!!</p>
<?php endif; ?>

<?php $wp_query = $original_query; ?>

</article>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
