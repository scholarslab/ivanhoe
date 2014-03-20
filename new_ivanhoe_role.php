<?php
/*
Template Name: Ivanhoe Role Form
*/

// If parent_post is set as a query variable, use it, otherwise set to null.
$ivanhoe_game_id = isset( $_GET['parent_post'] ) ? $_GET['parent_post'] : null;

// If we have a game ID and a post title, insert a post.
if ( $ivanhoe_game_id && !empty( $_POST['post_title'] ) ) {

    $ivanhoe_post_title = $_POST['post_title'];
    $ivanhoe_post_content = $_POST['post_content'];

    $role = array(
        'post_content' => $ivanhoe_post_content,
        'post_title' => $ivanhoe_post_title,
        'post_status' => 'publish',
        'post_type' => 'ivanhoe_role',
        'post_parent' => $ivanhoe_game_id
    );

    $new_ivanhoe_post_id = wp_insert_post( $role );
    
    wp_redirect( get_permalink($ivanhoe_game_id) );
    exit;

} elseif ( isset($_POST['post_title']) ) {
     $error_message = "Please enter a name for your role.";
}

get_header();

?>
<form action="" method="post">
	Role Name: <input type="text" name="post_title" required><br>
	Description: <?php wp_editor( '', "post_content"); ?><br>
	<input type="submit" value="Submit">
</form>

<?php 

    if( isset($error_message) )
    {
        echo $error_message;
    }

    get_footer(); 

?>

