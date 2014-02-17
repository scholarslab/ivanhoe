<?php get_header(); ?>

<?php 

$ivanhoe_game_id = $post->ID;
$ivanhoe_parent_permalink = get_permalink( $post->ID );

 ?>

<?php if (have_posts()) : while(have_posts()) : the_post(); ?>
<article>
    <h1><?php the_title(); ?></h1>
    <?php the_content(); ?>

<?php

$args = array ( 'post_type' => 'ivanhoe_move', 'post_parent' => $post->ID);
$query = new WP_Query( $args );

if ( $query->have_posts()) : while($query->have_posts()) : $query->the_post(); ?>
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
?>
<a href="<?php echo $url; ?>" class="button">Respond to this move</a>

</article>

<?php endwhile; else : ?>
<p>OMG NO POSTS!!!!!</p>
<?php endif; ?>

</article>

<?php endwhile; endif; ?>

<div id = "make-a-move-button">
<?php
$url = add_query_arg(
    "parent_post",
    $ivanhoe_game_id,
    get_permalink(get_option('ivanhoe_move_page'))
);
?>
<a href="<?php echo $url; ?>" class="button" id="make-a-move">Make a move</a>
</div>

<?php get_footer(); ?>
