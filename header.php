<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">

   <title><?php wp_title( '|', true, 'right' ); ?></title>


    <link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/stylesheets/screen.css'; ?>">


<?php wp_head(); ?>

</head>

<body>

<header role="banner">

<h1><?php bloginfo('name');?></h1>

<?php if($description = get_bloginfo('description')):?>
<p><?php echo $description ?></p>
<?php endif; ?>

<?php wp_nav_menu(array('theme_location' => 'ivanhoe_default', 'menu' => 'ivanhoe_default', 'container'=>'nav')); ?>

</header>

<main>
