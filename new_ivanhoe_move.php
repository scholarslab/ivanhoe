<?php
/*
Template Name: Ivanhoe Move Form
*/

// If parent_post is set as a query variable, use it, otherwise set to null.
$ivanhoe_game_id = isset( $_GET['parent_post'] ) ? $_GET['parent_post'] : null;
$ivanhoe_move_source = isset ( $_GET['move_source'] ) ? $_GET['move_source'] : null;
$ivanhoe_post_title = !empty ( $_POST['post_title'] ) ? $_POST['post_title'] : null;
$ivanhoe_post_content = !empty ( $_POST['post_content'] ) ? $_POST['post_content'] : null;

// Creates an empty array for error messages.
$error_messages = array();

// If there is no game ID, move content, or move title an appropriate error message will display.
if ( !$ivanhoe_game_id ) {
    $error_messages[''] = 'There is no game ID.';
}
if ( !$ivanhoe_post_title && !empty( $_POST ) ) {
    $error_messages[''] = 'There is no move title.';
}
if ( !$ivanhoe_post_content && !empty( $_POST ) ) {
    $error_messages[''] = 'There is no move content.';
}

// If we have a game ID and a post title, insert a post.
if ( empty ( $error_messages ) && !empty( $_POST ) ) {

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
}

get_header();

?>

<h1>Make a Move!</h1>

<h3>Game: <?php echo get_the_title($ivanhoe_game_id); ?></h3>
<form action="" method="post">
    <div>
    <label for="post_title">Title</label>
    <input type="text" name="post_title" value="<?php echo $ivanhoe_post_title; ?>" required>
    </div>

    <div>
    <label for="post_content">Content</label>
    <?php wp_editor( '', "post_content"); ?>
    </div>

	<input type="submit" value="Submit">
</form>

<?php if( $error_messages ) : ?>
    <ul>
        <?php foreach($error_messages as $message) : ?>
        <li><?php echo $message; ?></li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php

    get_footer();

?>
