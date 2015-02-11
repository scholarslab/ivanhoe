<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">

    <title><?php echo wp_title( '|', true, 'right' ); ?><?php echo get_bloginfo( 'name' ); ?></title>

    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/stylesheets/screen.css'; ?>">

    <?php
    if ( is_singular() && get_option( 'thread_comments' ) )
       wp_enqueue_script( 'comment-reply' );
   ?>

   <?php wp_head(); ?>

   <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>


</head>

<body <?php echo body_class(); ?>>

    <div id="wrap">

        <header role="banner">

            <h1><a href="<?php echo home_url(); ?>"><?php echo get_bloginfo('site_title'); ?></a></h1>
            <?php
                if($description = get_bloginfo('description')):?>
                <p><?php echo $description ?></p>
            <?php endif; ?>

            <?php
            wp_nav_menu( array( 'fallback_cb' => 'ivanhoe_page_menu', 'theme_location' => 'header', 'container' => 'nav' ) );
            ?>

        </header>

        <main>
