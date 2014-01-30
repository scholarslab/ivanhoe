<?php
    require(dirname(__FILE__) . '/../../../wp-blog-header.php');
    get_header(); ?>

<?php 

if ( !empty( $_POST['title'] ) ){

$ivanhoe_post_title = $_POST['title'];
$ivanhoe_post_content = $_POST['content'];
$ivanhoe_post_parent = $_POST['parent_id'];

$move = array(
	'post_content' => $ivanhoe_post_content,
	'post_title' => $ivanhoe_post_title,
	'post_status' => 'publish',
	'post_type' => 'ivanhoe_move',
	'post_parent' => $ivanhoe_post_parent
);

wp_insert_post( $move );

}; 

?>     

<?php
    $ivanhoe_game_id = $_GET['parent_post'];
    if ( current_user_can('edit_post', $ivanhoe_game_id))
    	echo "I can edit this.";
    echo $ivanhoe_game_id;
 //   do_action('edit_post', 0);

?>

<form action="new_ivanhoe_move.php" method="post">
	Title: <input type="text" name="title"><br>
	Content: <input type="textarea" name="content"><br>
	<input type="hidden" value="<?php echo $ivanhoe_game_id ?>" name="parent_id"><br>
	<input type="submit" value="Submit">
</form>

<?php get_footer(); ?>