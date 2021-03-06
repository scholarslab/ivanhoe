<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">

    <title><?php echo wp_title( '|', true, 'right' ); ?><?php echo get_bloginfo( 'name' ); ?></title>

    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/stylesheets/screen.css'; ?>">
    <?php 
    global $post;

    if($post->post_type == 'ivanhoe_game' && has_post_thumbnail() ) {


    }

    ?>


    <?php
    if ( is_singular() && get_option( 'thread_comments' ) )
       wp_enqueue_script( 'comment-reply' );
   ?>

   <?php wp_head(); ?>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="<?php echo get_template_directory_uri() .  '/javascripts/parentmove.js'; ?>"></script> 

</head>

<body <?php echo body_class(); ?>>

    <div id="wrap">

   

           <header role="banner">

           <h1 class = "banner-title"><a href="<?php echo home_url(); ?>"><?php echo get_bloginfo('site_title'); ?></a></h1> 

<?php wp_nav_menu( array( 'fallback_cb' => 'ivanhoe_page_menu') ) ?>


        </header>
        
 
  <div class="sidebar">
