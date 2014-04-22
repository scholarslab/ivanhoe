<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">

   <title><?php echo wp_title( '|', true, 'right' ); ?><?php echo get_bloginfo( 'name' ); ?></title>

   <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/stylesheets/screen.css'; ?>">


<?php wp_head(); ?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

</head>

<body <?php echo body_class(); ?>>

<div id="wrap">

<header role="banner">

<h1><a href="<?php echo home_url(); ?>"><?php echo get_bloginfo('site_title'); ?></a></h1>
<?php if($description = get_bloginfo('description')):?>
<p><?php echo $description ?></p>
<?php endif; ?>

<?php

	$make_a_game = get_page_by_title( 'Make a Game' );
	$make_a_move = get_page_by_title( 'Make a Move' );
	$make_a_role = get_page_by_title( 'Make a Role' );
    
    $ids = "{$make_a_game->ID},{$make_a_move->ID},{$make_a_role->ID}";
    wp_nav_menu( array( 'fallback_cb' => 'ivanhoe_page_menu', 'theme_location' => 'header', 'exclude' => $ids, 'container' => 'nav' ) ); 
// wp_nav_menu(array('theme_location' => 'ivanhoe_default', 'menu' => 'ivanhoe_default', 'container'=>'nav')); ?>

</header>

<main>
