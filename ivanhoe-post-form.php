<?php
if(!is_user_logged_in()) {
    wp_redirect(get_home_url()); // ensure user is logged in
}

require_once ABSPATH . 'wp-admin/includes/post.php' ;

$IVANHOE_DIR = dirname(__FILE__);

$post_type = $_GET['ivanhoe'];
$class_name = str_replace(' ', '', ucwords(str_replace('_', ' ', $post_type)));
require_once($IVANHOE_DIR . "/includes/post_form/$post_type.php");
$post_form = new $class_name();

$post_form->render();
