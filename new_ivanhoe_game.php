<?php
/*
Template Name: Ivanhoe Game Form
*/

$ivanhoe_post_title = !empty ( $_POST['post_title'] ) ? $_POST['post_title'] : null;

// Creates an empty array for error messages.
$error_messages = array();

// If there is no game ID, move content, or move title an appropriate error message will display.
if ( !$ivanhoe_post_title && !empty( $_POST ) ) {
    $error_messages[''] = __( 'Please enter a name for your game.', 'ivanhoe' );
}

// If we have a game ID and a post title, insert a post.
if ( !empty( $_POST ) ) {

    $ivanhoe_post_title = $_POST['post_title'];
    $ivanhoe_post_content = $_POST['post_content'];

    $game = array(
        'post_content' => $ivanhoe_post_content,
        'post_title' => $ivanhoe_post_title,
        'post_status' => 'publish',
        'post_type' => 'ivanhoe_game',
    );

    $new_ivanhoe_post_id = wp_insert_post( $game );

    if ($_FILES['post_thumbnail']) {
        ivanhoe_add_image('post_thumbnail', $new_ivanhoe_post_id);
    }

    wp_redirect( get_post_type_archive_link('ivanhoe_game') );
    exit;

}

get_header();

?>

<header>
<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
</header>

<?php if( $error_messages ) : ?>
  <?php echo print_errors($error_messages); ?>
<?php endif; ?>

<form action="" class="new-post" method="post" enctype="multipart/form-data">
<div><label for="post_title"><?php _e( 'Game Title', 'ivanhoe' ); ?></label>
    <input type="text" size="50" name="post_title" required>
    </div>

    <div>
    <label for="post_thumbnail"><?php _e( 'Game Thumbnail', 'ivanhoe' ); ?></label>
    <input type="file" name="post_thumbnail">
    </div>

    <div>
    <label for="post_content"><?php _e( 'Game Description', 'ivanhoe' ); ?></label>
    <?php wp_editor( '', "post_content"); ?>
    </div>

    <input type="submit" value="<?php _e( 'Submit', 'ivanhoe' ); ?>">
</form>



<?php 

    get_footer(); 

?>

