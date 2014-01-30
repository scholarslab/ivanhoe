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
    <?php the_content(); ?>
</article>

<?php endwhile; else : ?>
<p>OMG NO POSTS!!!!!</p>
<?php endif; ?>

</article>

<?php endwhile; endif; ?>

<h1>Want to make a move?</h1>
    <form action = "<?php bloginfo('template_url')?>/new_ivanhoe_move.php" method = "GET">
    	<input type = "submit" value = "Make a Move">
        <input type = "hidden" value = "<?php echo $ivanhoe_game_id ?>" name = "parent_post">
        <input type="hidden" value = "<?php echo $ivanhoe_parent_permalink ?>" name="parent_permalink">
    </form>

<?php get_footer(); ?>