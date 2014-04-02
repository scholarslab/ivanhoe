<?php get_header(); ?>

<?php 
	$curauth = ( isset( $_GET['author_name'] ) ) ? get_user_by( 'slug', $author_name ) : get_userdata( intval( $author ) );
?>

<article>
<h1><?php echo $curauth->display_name; ?></h1>

<?php if ($description = $curauth->user_description) : ?>
<div class="description">
<h2>Description</h2>
<?php echo wpautop($description); ?>
</div>
<?php endif; ?>
<!-- What we need to do is write a custom loop that pulls all the roles that the user has, and then also displays the title of the game that role is associated with -->

<?php 

	$args = array 
		(
			'post_type' => 'ivanhoe_role',
			'author' => $curauth->ID
		);

	$author_role_query = new WP_Query($args);
	
    if ($author_role_query->have_posts()) : ?>

<h2>Roles</h2>

<div class="roles">

<?php while($author_role_query->have_posts()) : $author_role_query->the_post();	?>

<article class="role">
    <?php the_post_thumbnail('thumbnail'); ?>
    <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
    <p class="game"><strong>Game: </strong><a href="<?php echo get_permalink( $post->post_parent ); ?>"><?php echo get_the_title( $post->post_parent ); ?></a></p>

<?php 
	$args = array
	(
		'post_type' => 'ivanhoe_move',
		'post_parent' => $post->post_parent,
		'author' => $curauth->ID,
		'posts_per_page' => '5'
	);	

	$moves_per_role_query = new WP_Query( $args );

    if ($moves_per_role_query->have_posts()) : ?>

    <ul class="moves">
    <?php while($moves_per_role_query->have_posts()) : $moves_per_role_query->the_post(); ?>

        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>

    <?php endwhile; ?>
    </ul>

    <?php endif; ?>

<?php wp_reset_postdata(); ?>
</article>    

<?php

	endwhile;
	endif;

?>	
</div>
</article>



<?php get_footer(); ?>
