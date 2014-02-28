<?php get_header(); ?>

<?php 
	$curauth = ( isset( $_GET['author_name'] ) ) ? get_user_by( 'slug', $author_name ) : get_userdata( intval( $author ) );
?>

<h1>Hello <?php echo $curauth->first_name; ?></h1>	

<p>Here's a little bit about yourself: <?php echo $curauth->user_description; ?></p>

<!-- What we need to do is write a custom loop that pulls all the roles that the user has, and then also displays the title of the game that role is associated with -->

<h3>Here are your roles:</h3>

<?php 

	$args = array 
		(
			'post_type' => 'ivanhoe_role',
			'author_name' => $author_name
		);

	$author_role_query = new WP_Query($args);
	
	if ($author_role_query->have_posts()) : while($author_role_query->have_posts()) : $author_role_query->the_post();	
?>


<p><strong>Game title: </strong><a href="<?php echo get_permalink( $post->post_parent ); ?>"><?php echo get_the_title( $post->post_parent ); ?></a></p>
<p><strong>Role: </strong><?php the_title(); ?></p>
	

<?php 
	$args = array
	(
		'post_type' => 'ivanhoe_move',
		'post_parent' => $post->post_parent,
		'author' => $author_name,
		'posts_per_page' => '5'
	);	

	$moves_per_role_query = new WP_Query( $args );

	if ($moves_per_role_query->have_posts()) : while($moves_per_role_query->have_posts()) : $moves_per_role_query->the_post();

?>

<ul>
	<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
</ul>

<?php	

	endwhile;
	endif;

	wp_reset_postdata();

?>

<?php

	endwhile;
	endif;

?>	






<?php get_footer(); ?>