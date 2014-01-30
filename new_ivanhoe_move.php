<?php
    require(dirname(__FILE__) . '/../../../wp-blog-header.php');
    get_header(); ?>

<?php
    $ivanhoe_game_id = $_GET['parent_post'];
    if ( current_user_can('edit_post', $ivanhoe_game_id))
    	echo "I can edit this.";
    do_action('edit_post', 0);

?>

here are some words.


<?php get_footer(); ?>