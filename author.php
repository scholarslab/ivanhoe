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

<ul>
	<li><?php the_title(); ?></li>
	<li><a href="<?php echo get_permalink( $post->post_parent ); ?>"><?php echo get_the_title( $post->post_parent ); ?></a></li>
</ul>	

<?php

	endwhile;
	endif;

?>	






<?php get_footer(); ?>