<?php
/*
Template Name: Ivanhoe Move Form
*/

// If parent_post is set as a query variable, use it, otherwise set to null.
$ivanhoe_game_id = isset( $_POST['parent_post'] ) ? $_POST['parent_post'] : null;
$ivanhoe_move_sources = isset ( $_POST['move_source'] ) ? $_POST['move_source'] : null;
$ivanhoe_post_title = !empty ( $_POST['post_title'] ) ? $_POST['post_title'] : null;
$ivanhoe_post_content = !empty ( $_POST['post_content'] ) ? $_POST['post_content'] : null;
$ivanhoe_post_rationale = !empty ( $_POST['post_rationale']) ? $_POST['post_rationale'] : null;
$ivanhoe_role_id = isset( $_POST['ivanhoe_role_id'] ) ? $_POST['ivanhoe_role_id'] : null;

print_r($ivanhoe_game_id);
print_r($ivanhoe_move_sources);
print_r($ivanhoe_role_id);
// Creates an empty array for error messages.
$error_messages = array();

// If there is no game ID, move content, or move title an appropriate error message will display.
if ( !$ivanhoe_game_id ) {
    $error_messages[''] = __( 'There is no game ID.', 'ivanhoe' );
}
if ( !$ivanhoe_post_title && !empty( $_POST ) ) {
    $error_messages[''] = __( 'There is no move title.', 'ivanhoe' );
}
if ( !$ivanhoe_post_content && !empty( $_POST ) ) {
    $error_messages[''] = __( 'There is no move content.', 'ivanhoe' );
}

// If we have a game ID and a post title, insert a post.
if ( empty ( $error_messages ) && !empty( $_POST ) ) {

    $move = array(
        'post_content' => $ivanhoe_post_content,
        'post_title' => $ivanhoe_post_title,
        'post_status' => 'publish',
        'post_type' => 'ivanhoe_move',
        'post_parent' => $ivanhoe_game_id,
    );

    $new_ivanhoe_post_id = wp_insert_post( $move );
    // foreach ($ivanhoe_move_sources as $ivanhoe_move_source) {
            update_post_meta(
                $new_ivanhoe_post_id,
                'Ivanhoe Move Source',
                $ivanhoe_move_source
            );
    // }


    if ( !empty( $ivanhoe_post_rationale ) )
    {
    $journal_entry = array(
        'post_content' => $ivanhoe_post_rationale,
        'post_title' =>  sprintf( __( 'Journal Entry for %s', 'ivanhoe' ), $ivanhoe_post_title ),
        'post_status' => 'publish', //TODO: Decide whether or not we want the RJ to be public
        'post_type' => 'ivanhoe_role_journal',
        'post_parent' => $new_ivanhoe_post_id
        );

    $new_ivanhoe_journal_entry = wp_insert_post( $journal_entry );
    update_post_meta(
        $new_ivanhoe_journal_entry,
        'Ivanhoe Game Source',
        $ivanhoe_game_id
        );
    update_post_meta(
        $new_ivanhoe_journal_entry,
        'Ivanhoe Role ID',
        $ivanhoe_role_id
        );
    }

    wp_redirect( get_permalink($ivanhoe_game_id) );
    exit;
}

get_header();

// Get the game post.
$ivanhoe_game = get_post($ivanhoe_game_id);
$message = sprintf(
        __( 'You are making a move on the game &#8220;<a href="%1$s">%2$s</a>&#8221;.' , 'ivanhoe'), get_permalink($ivanhoe_game_id), $ivanhoe_game->post_title );

if ($ivanhoe_move_sources) {
    $message = $message
             . ' '
             . __( 'You are responding to the following moves:', 'ivanhoe' )
             . '<ul>';
        foreach($ivanhoe_move_sources as $ivanhoe_move_source) {
            $ivanhoe_source = get_post($ivanhoe_move_source);
            $message .= '<li>'
                      . '<a href="'
                      . get_permalink($ivanhoe_move_source)
                      . '">'
                      . $ivanhoe_source->post_title
                      . '</a></li>';
        }
    $message .= '<ul>';
}
// } else {
//     $message = sprintf( __( 'You are making a move on the game &#8220;<a href="%1$s">%2$s</a>.&#8221;', 'ivanhoe'), get_permalink($ivanhoe_game_id), $ivanhoe_game->post_title );}

?>

<header>
<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
</header>

<div class="new-ivanhoe-meta new-ivanhoe-move-meta">
    <p><strong><?php echo $message; ?></strong></p>
</div>

<form action="" method="post" class="new-ivanhoe-form new-ivanhoe-move">
    <div>
    <label for="post_title"><?php _e( 'Title', 'ivanhoe' ); ?></label>
    <input type="text" name="post_title" value="<?php echo $ivanhoe_post_title; ?>" required>
    </div>

    <div>
    <label for="post_content"><?php _e( 'Content', 'ivanhoe' ); ?></label>
    <?php wp_editor( '', "post_content"); ?>
    </div>

    <div>
    <label for="post_title"><?php _e( 'Rationale', 'ivanhoe' ); ?></label>
    <?php wp_editor( '', "post_rationale"); ?>
    </div>

    <input type='hidden' name='parent_post' value='<?php echo $ivanhoe_game_id; ?>'>
    <input type='hidden' name='ivanhoe_role_id' value='<?php echo $ivanhoe_role_id; ?>'>
    <?php foreach ($ivanhoe_move_sources as $ivanhoe_move_source): ?>
        <?php settype($ivanhoe_move_source, "integer"); ?>
        <input type='hidden' name='move_source' value='<?php echo $ivanhoe_move_source; ?>' >
    <?php endforeach; ?>

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
