<?php

/**
 * Generate HTML for Ivanhoe Move metabox.
 *
 * @param WP_Post
 * @return string The HTML for the form element.
 */
function ivanhoe_move_meta_box($post=null)
{
    $post = (is_null($post)) ? get_post() : $post;
    $html = '<p><label for="post_parent">' . __('Game', 'ivanhoe' )
    . '</label></p>'
    . '<p><input type="text" name="post_parent" value="'
    . $post->post_parent . '">';

    return $html;
}

/**
 * Function for getting the metadata for the post(s) which respond to the
 * current move
 */
function ivanhoe_move_source()
{
    add_meta_box(
        'ivanhoe_move_source',
        __('Source for:', 'ivanhoe' ),
        'ivanhoe_move_source_meta_box',
        'ivanhoe_move'
        );
}

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function ivanhoe_move_source_meta_box($post=null) {
    $post = (is_null($post)) ? get_post() : $post;

    // Add an nonce field so we can check for it later.
    wp_nonce_field(
        'ivanhoe_move_source_meta_box',
        'ivanhoe_move_source_meta_box_nonce'
        );

    /*
     * Use get_post_meta() to retrieve an existing value
     * from the database and use the value for the form.
     */
    $value = get_post_meta( $post->ID, 'Ivanhoe Move Source', true );

    echo '<label for="ivanhoe_move_source">';
    __( 'Source for:', 'ivanhoe' );
    echo '</label> ';
    echo '<input type="text" id="ivanhoe_move_source" '
    . 'name="ivanhoe_move_source" value="' . esc_attr( $value ) .
    '" size="25" />';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function ivanhoe_move_source_save_meta_box_data($post_id=null) {
    $post_id = (is_null($post_id)) ? get_post()->ID : $post_id;

    /*
     * We need to verify this came from our screen and with proper
     * authorization, because the save_post action can be triggered at other
     * times.
     */

    // Check if our nonce is set.
    if ( ! isset( $_POST['ivanhoe_move_source_meta_box_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    $nonce = $_POST['ivanhoe_move_source_meta_box_nonce'];
    if ( ! wp_verify_nonce( $nonce, 'ivanhoe_move_source_meta_box' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't
    // want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) &&
       'ivanhoe_move' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

    } else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    /* OK, its safe for us to save the data now. */

    // Make sure that it is set.
    if ( ! isset( $_POST['ivanhoe_move_source'] ) ) {
        return;
    }

    // Sanitize user input.
    $my_data = sanitize_text_field( $_POST['ivanhoe_move_source'] );

    // Update the meta field in the database.
    update_post_meta( $post_id, 'Ivanhoe Role ID', $my_data );
}

add_action( 'save_post', 'ivanhoe_move_source_save_meta_box_data' );

/**
 * Function for getting the metadata for the post(s) to which the current move
 * responds
 */
function ivanhoe_move_response()
{
    add_meta_box(
        'ivanhoe_move_response',
        __('Responds to:', 'ivanhoe' ),
        'ivanhoe_response_meta_box',
        'ivanhoe_move'
        );
}

/**
 * Function to create metadata box for role associated with journal entries
 */
function ivanhoe_role_for_journal()
{
    add_meta_box(
        'ivanhoe_role_for_journal',
        __('Journal Entry For:', 'ivanhoe' ),
        'ivanhoe_role_id_meta_box',
        'ivanhoe_role_journal'
        );
}

add_action(
    'add_meta_boxes',
    'ivanhoe_move_source',
    'ivanhoe_move_response',
    'ivanhoe_role_for_journal'
    );

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function ivanhoe_role_id_meta_box($post=null) {
    $post = (is_null($post)) ? get_post() : $post;

    // Add an nonce field so we can check for it later.
    wp_nonce_field(
        'ivanhoe_role_id_meta_box',
        'ivanhoe_role_id_meta_box_nonce'
        );

    /*
     * Use get_post_meta() to retrieve an existing value
     * from the database and use the value for the form.
     */
    $value = get_post_meta( $post->ID, 'Ivanhoe Role ID', true );

    echo '<label for="ivanhoe_role_for_journal">';
    __( 'Journal Entry For:', 'ivanhoe' );
    echo '</label> ';
    echo '<input type="text" id="ivanhoe_role_id" '
    . 'name="ivanhoe_role_id" value="' . esc_attr( $value )
    . '" size="25" />';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function ivanhoe_role_id_save_meta_box_data($post_id=null) {
    $post_id = (is_null($post_id)) ? get_post()->ID : $post_id;

    /*
     * We need to verify this came from our screen and with proper
     * authorization, because the save_post action can be triggered at other
     * times.
     */

    // Check if our nonce is set.
    if ( ! isset( $_POST['ivanhoe_role_id_meta_box_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    $nonce = $_POST['ivanhoe_role_id_meta_box_nonce'];
    if ( ! wp_verify_nonce( $nonce, 'ivanhoe_role_id_meta_box' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't
    // want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) &&
       'ivanhoe_role_journal' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

    } else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    /* OK, its safe for us to save the data now. */

    // Make sure that it is set.
    if ( ! isset( $_POST['ivanhoe_role_id'] ) ) {
        return;
    }

    // Sanitize user input.
    $my_data = sanitize_text_field( $_POST['ivanhoe_role_id'] );

    // Update the meta field in the database.
    update_post_meta( $post_id, 'Ivanhoe Role ID', $my_data );
}
add_action( 'save_post', 'ivanhoe_role_id_save_meta_box_data' );

/**
 * Calls the source metadata for each move and displays it.
 *
 * @param WP_Post.
 */
function ivanhoe_get_move_source($post=null)
{
    $post = (is_null($post)) ? get_post() : $post;
    $html = '';

    // Get the Move Source ID from custom post metadata.
    $source_id = get_post_meta($post->ID, 'Ivanhoe Move Source', false);

    // Check if $source_id isn't 0 and if we can get another post with its value.
    if ( $source_id ) {

        // Set $html to a string with a link to source post.
        $html = '<h3>' . __('Source', 'ivanhoe' ) . '</h3>'
            . '<ul>';
        foreach ($source_id as $source ) {
            $source_link = get_permalink($source);
            $source_title = get_the_title($source);
            $html .= "<a href='$source_link'><li>$source_title</li></a>";
        }
        $html .= "</ul>";

    }

    // Print out the value of $html.
    echo $html;
}

/**
 * Gets responses for a move and displays them.
 *
 * @param WP_Post
 */
function ivanhoe_get_move_responses($post=null)
{
    $post = (is_null($post)) ? get_post() : $post;
    $html = '';

    $args = array(
        'post_type'          => 'ivanhoe_move',
        'post_per_page'      => -1,
        'meta_key'           => 'Ivanhoe Move Source',
        'meta_value'         => $post->ID,
        'meta_value_compare' => '='
    );

    $responses = get_posts( $args );

    if ($responses) {

        $html = '<h3>' . __('Responses', 'ivanhoe') . '</h3>'
            . '<ul>';

        foreach ( $responses as $response ) {
            $html .= '<li><a href="' . get_permalink($response->ID) . '">'
                . $response->post_title . '</a></li>';
        }

        $html .= '</ul>';

    }

    echo $html;

}

