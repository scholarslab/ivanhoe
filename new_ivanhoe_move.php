<?php
    require(dirname(__FILE__) . '/../../../wp-blog-header.php');
    get_header(); ?>

<?php 

if ( !empty( $_POST['post_title'] ) ){

$ivanhoe_post_title = $_POST['post_title'];
$ivanhoe_post_content = $_POST['post_content'];
$ivanhoe_post_parent = $_POST['parent_id'];
$ivanhoe_permalink = $_POST['ivanhoe_parent'];

$move = array(
	'post_content' => $ivanhoe_post_content,
	'post_title' => $ivanhoe_post_title,
	'post_status' => 'publish',
	'post_type' => 'ivanhoe_move',
	'post_parent' => $ivanhoe_post_parent
);

wp_insert_post( $move );

wp_redirect( $ivanhoe_permalink );
exit;

}; 



?>     

<?php
    $ivanhoe_game_id = $_GET['parent_post'];
    $ivanhoe_parent_permalink = $_GET['parent_permalink'];
    if ( current_user_can('edit_post', $ivanhoe_game_id))
    	echo "I can edit this.";
    echo $ivanhoe_parent_permalink;
 //   do_action('edit_post', 0);

?>

<form action="new_ivanhoe_move.php" method="post">
	Title: <input type="text" name="post_title"><br>
	Content: <?php wp_editor( '', "post_content"); ?><br>
	<input type="hidden" value="<?php echo $ivanhoe_game_id ?>" name="parent_id"><br>
	<input type="hidden" value="<?php echo $ivanhoe_parent_permalink ?>" name="ivanhoe_parent"><br>
	<input type="submit" value="Submit">
</form>

<?php get_footer(); ?>