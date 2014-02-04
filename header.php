<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">

   <title><?php wp_title( '|', true, 'right' ); ?></title>


    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">


</head>

<body>

<?php wp_nav_menu(array('theme_location' => 'ivanhoe_default', 'menu' => 'ivanhoe_default')); ?>


