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

echo $post_form->render();

// HERE WE ARE

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

    if (isset($_FILES['post_thumbnail'])) {
        ivanhoe_add_image('post_thumbnail', $newpost);
    }

    // If there's a move source, save it as post meta.
    if ($move_source) {
        if (is_array($move_source)) {
            foreach ($move_source as $move) {
                add_post_meta(
                $newpost,
                'Ivanhoe Move Source',
                $move
                );
            }
        } else {
            add_post_meta(
                $newpost,
                'Ivanhoe Move Source',
                $move_source
                );
        }

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
    // $ivanhoe_source = get_post($move_source);

    $message = sprintf(
        __( 'You are making a move on the game &#8220;<a href="%1$s">%2$s</a>&#8221; in response to the following: <ul>' , 'ivanhoe' ),
        get_permalink($parent_post),
        $ivanhoe_game->post_title
    );
    if (is_array($move_source)) {
        foreach ($move_source as $single_source) {
            $source_link = get_permalink($single_source);
            $source_title = get_the_title($single_source);
            $message .= "<a href='$source_link'><li>$source_title</li></a>";
        };
    } else {
        $source_link = get_permalink($move_source);
        $source_title = get_the_title($move_source);
        $message .= "<a href='$source_link'><li>$source_title</li></a>";
    }

    $message .= "</ul>";
    }
}
?>

<header>
<h1><?php echo $form_title; ?></h1>
</header>

<?php if( $error_messages ) : ?>
  <?php echo ivanhoe_print_errors($error_messages); ?>
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
