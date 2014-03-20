<?php
/*
Template Name: Ivanhoe Move Form
*/

// If parent_post is set as a query variable, use it, otherwise set to null.
$ivanhoe_game_id = isset( $_GET['parent_post'] ) ? $_GET['parent_post'] : null;
$ivanhoe_move_source = isset ( $_GET['move_source'] ) ? $_GET['move_source'] : null;

// If we have a game ID and a post title, insert a post.
if ( $ivanhoe_game_id && !empty( $_POST['post_title'] ) ) {

    $ivanhoe_post_title = $_POST['post_title'];
    $ivanhoe_post_content = $_POST['post_content'];

    $move = array(
        'post_content' => $ivanhoe_post_content,
        'post_title' => $ivanhoe_post_title,
        'post_status' => 'publish',
        'post_type' => 'ivanhoe_move',
        'post_parent' => $ivanhoe_game_id
    );

    $new_ivanhoe_post_id = wp_insert_post( $move );
    update_post_meta(
        $new_ivanhoe_post_id,
        'Ivanhoe Move Source',
        $ivanhoe_move_source
        );
    
    wp_redirect( get_permalink($ivanhoe_game_id) );
    exit;

} elseif ( isset($_POST['post_title']) ) {
     $error_message = "Please enter a title for your move.";
}

get_header();

?>
<form action="" method="post">
	Title: <input type="text" name="post_title" required><br>
	Content: <?php wp_editor( '', "post_content"); ?><br>
	<input type="submit" value="Submit">
</form>

<?php 

    if( isset($error_message) )
    {
        echo $error_message;
    }

    get_footer(); 

?>
