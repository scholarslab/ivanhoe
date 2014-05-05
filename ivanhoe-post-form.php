<?php
if(!is_user_logged_in()) {
    wp_redirect(get_home_url()); // ensure user is logged in
}

require_once ABSPATH . 'wp-admin/includes/post.php' ;

// Get our post type.
$post_type = $_GET['ivanhoe'];

$newpost = get_default_post_to_edit($post_type, true);

// Set some variables based on our post_type.
switch ($post_type) {
    case 'ivanhoe_game':
        $form_title = 'Make a Game';
        $post_title_label = __( 'Game Title', 'ivanhoe' );
        $post_thumbnail_label = __( 'Game Thumbnail', 'ivanhoe' );
        $post_content_label = __( 'Game Description', 'ivanhoe' );
    break;

    case 'ivanhoe_move':
        $form_title = __( 'Make a Move', 'ivanhoe' );
        $post_title_label = __( 'Move Title', 'ivanhoe' );
        $post_content_label = __( 'Move Content', 'ivanhoe' );
        $post_rationale_label = __( 'Rationale', 'ivanhoe' );
        break;

    case 'ivanhoe_role':
        $form_title = __( 'Make a Role', 'ivanhoe' );
        $post_title_label = __( 'Role Name', 'ivanhoe' );
        $post_thumbnail_label = __( 'Role Thumbnail', 'ivanhoe' );
        $post_content_label = __( 'Role Description', 'ivanhoe' );
        break;

    default: // If there's no valid value passed to the ivanhoe var.
      die;
}

// Form fields. All post types have these.
$post_title = !empty ( $_POST['post_title'] ) ? $_POST['post_title'] : null;
$post_content = !empty ( $_POST['post_content'] ) ? $_POST['post_content'] : null;

// Move variables.
$parent_post = isset( $_GET['parent_post'] ) ? $_GET['parent_post'] : null;
$move_source = isset ( $_GET['move_source'] ) ? $_GET['move_source'] : null;
$role_id = isset( $_GET['ivanhoe_role_id'] ) ? $_GET['ivanhoe_role_id'] : null;
$post_rationale = !empty ( $_POST['post_rationale']) ? $_POST['post_rationale'] : null;

// special fields
$rationale_title = "";
$rationale_content = "";
echo "<pre><code>";
echo "move source:";
print_r($move_source);
echo "parent post";
print_r($parent_post);
echo "post";
print_r($_POST);
echo "</code></pre>";




// Creates an empty array for error messages.
$error_messages = array();

// If we have a game ID and a post title, insert a post.
if ( !empty( $_POST )) {

    $newpost_data = array(
        'ID' => $newpost->ID,
        'post_content' => $post_content,
        'post_title' => $post_title,
        'post_status' => 'publish',
        'post_type' => $post_type,
      );

    // check post types
    if(empty($post_title)) {
      $error_messages[] = __('A title is required', 'ivanhoe');
    }

    if(empty($post_content)) {
      $error_messages[] = __('A description is required', 'ivanhoe');
    }

    // Set the post_parent if one is passed to the form, regardless of post type.
    if ($parent_post) {
        $newpost_data['post_parent'] = $parent_post;
    }

    if(!empty($error_messages)) {
      GOTO fail; // if we ever catch you doing this, we will hunt you down
    }

    $newpost = wp_insert_post( $newpost_data );

    if ($_FILES['post_thumbnail']) {
        ivanhoe_add_image('post_thumbnail', $newpost);
    }

    // If there's a move source, save it as post meta.
    if ($move_source) {
        update_post_meta(
            $newpost,
            'Ivanhoe Move Source',
            $move_source
        );
    }

    // If there is a post_rationale.
    if ($post_rationale) {

        $journal_entry_data = array(
            'post_content' => $post_rationale,
            'post_title' =>  sprintf( __( 'Journal Entry for %s', 'ivanhoe' ), $post_title ),
            'post_status' => 'publish', //TODO: Decide whether or not we want the RJ to be public
            'post_type' => 'ivanhoe_role_journal',
            'post_parent' => $newpost
          );

        $journal_entry = wp_insert_post( $journal_entry_data );

        update_post_meta(
            $journal_entry,
            'Ivanhoe Game Source',
            $parent_post
        );

        update_post_meta(
            $journal_entry,
            'Ivanhoe Role ID',
            $role_id
        );

    }

    $redirect_url = get_post_type_archive_link('ivanhoe_game');

    if ($parent_post) {
        $redirect_url = get_permalink($parent_post);
    }

    wp_redirect( $redirect_url );
    exit;

}

fail:
  // keep on truckin'

// Get theme header.
get_header();

// Create a game info message.
$message = '';

if ( $post_type == 'ivanhoe_move' || $post_type == 'ivanhoe_role' ) {

$ivanhoe_game = get_post($parent_post);
if ( $post_type == 'ivanhoe_move' ) {
    $message = sprintf( __( 'You are making a move on the game &#8220;<a href="%1$s">%2$s</a>.&#8221;', 'ivanhoe'), get_permalink($parent_post), $ivanhoe_game->post_title );
} else {
    $message = sprintf( __( 'You are making a role on the game &#8220;<a href="%1$s">%2$s</a>.&#8221;', 'ivanhoe'), get_permalink($parent_post), $ivanhoe_game->post_title );
}

if ($move_source) {
    $ivanhoe_source = get_post($move_source);

    $message = sprintf(
        __( 'You are making a move on the game &#8220;<a href="%1$s">%2$s</a>&#8221; in response to the move &#8220;<a href="%3$s">%4$s</a>.&#8221;' , 'ivanhoe' ), 
        get_permalink($parent_post),
        $ivanhoe_game->post_title,
        get_permalink($move_source),
        $ivanhoe_source->post_title
    );
    }
}
?>

<header>
<h1><?php echo $form_title; ?></h1>
</header>

<?php if( $error_messages ) : ?>
  <?php echo print_errors($error_messages); ?>
<?php endif; ?>

<?php if ( $message ) : ?>
<div class="new-ivanhoe-meta new-ivanhoe-move-meta">
    <p><strong><?php echo $message; ?></strong></p>
</div>
<?php endif; ?>

<form action="" class="new-ivanhoe-form" method="post" enctype="multipart/form-data">

<div>
    <label for="post_title"><?php echo $post_title_label; ?></label>
    <input type="text" size="50" name="post_title" value="<?php echo $post_title; ?>" required>
</div>

<?php if( $post_type !== 'ivanhoe_move' ):  ?>
<div>
    <label for="post_thumbnail"><?php echo $post_thumbnail_label; ?></label>
    <input type="file" name="post_thumbnail">
</div>

<?php endif; ?>

<div>
    <label for="post_content"><?php echo $post_content_label; ?></label>
    <?php wp_editor( $post_content, "post_content"); ?>
</div>

<?php if($post_type == 'ivanhoe_move'): ?>
  <div>
    <label for="post_rationale"><?php echo $post_rationale_label; ?></label>
    <?php wp_editor( $post_rationale, 'post_rationale', array('media_btns' => false)); ?>
  </div>
<?php endif; ?>

<input type="submit" class="btn" value="<?php _e( 'Save', 'ivanhoe' ); ?>">

</form>

<?php get_footer();

