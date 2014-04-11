<?php
/*
Template Name: Ivanhoe Role Form
*/

// If parent_post is set as a query variable, use it, otherwise set to null.
$ivanhoe_game_id = isset( $_GET['parent_post'] ) ? $_GET['parent_post'] : null;
$ivanhoe_post_title = !empty ( $_POST['post_title'] ) ? $_POST['post_title'] : null;
$ivanhoe_post_content = !empty ( $_POST['post_content'] ) ? $_POST['post_content'] : null;

// Creates an empty array for error messages.
$error_messages = array();

// If there is no game ID, move content, or move title an appropriate error message will display.
if ( !$ivanhoe_game_id ) {
    $error_messages[''] = __( 'There is no game ID.', 'ivanhoe' );
}
if ( !$ivanhoe_post_title && !empty( $_POST ) ) {
    $error_messages[''] = __( 'Please enter a name for your role.', 'ivanhoe' );
}
if ( !$ivanhoe_post_content && !empty( $_POST ) ) {
    $error_messages[''] = __( 'Please add a description for your role.', 'ivanhoe' );
}

// If we have a game ID and a post title, insert a post.
if ( empty ( $error_messages ) && !empty( $_POST ) ) {

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

    if ($_FILES['post_thumbnail']) {
        ivanhoe_add_image('post_thumbnail', $new_ivanhoe_post_id);
    }

    wp_redirect( get_permalink($ivanhoe_game_id) );
    exit;

}

get_header();

// Get the game post.
$ivanhoe_game = get_post($ivanhoe_game_id);

$message = sprintf( __( 'You are making a new role on the game &#8220;<a href="%1$s">%2$s</a>.&#8221;', 'ivanhoe'), get_permalink($ivanhoe_game_id), $ivanhoe_game->post_title );

echo $message;
?>

<header>
<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
</header>

<form action="" class="new-post" method="post" enctype="multipart/form-data">
<div><label for="post_title"><?php _e( 'Role Name', 'ivanhoe' ); ?></label>
    <input type="text" size="50" name="post_title" required>
    </div>

    <div>
    <label for="post_thumbnail"><?php _e( 'Role Thumbnail', 'ivanhoe' ); ?></label>
    <input type="file" name="post_thumbnail">
    </div>

    <div>
    <label for="post_content"><?php _e( 'Description', 'ivanhoe' ); ?></label>
    <?php wp_editor( '', "post_content"); ?>
    </div>

    <input type="submit" value="<?php _e( 'Submit', 'ivanhoe' ); ?>">
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

