<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">

   <title><?php wp_title( '|', true, 'right' ); ?></title>

   <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/stylesheets/screen.css'; ?>">


<?php wp_head(); ?>

</head>

<body <?php echo body_class(); ?>>

<header role="banner">

<h1><a href="<?php echo home_url(); ?>"><?php echo get_bloginfo('site_title'); ?></a></h1>
<?php if($description = get_bloginfo('description')):?>
<p><?php echo $description ?></p>
<?php endif; ?>

<?php wp_nav_menu(array('theme_location' => 'ivanhoe_default', 'menu' => 'ivanhoe_default', 'container'=>'nav')); ?>

<?php
	$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request) );

	wp_loginout( $current_url ); ?>

</header>

<main>
