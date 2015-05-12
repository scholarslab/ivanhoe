<?php

add_action( 'show_user_profile', 'ivanhoe_display_notifications_options' );
add_action( 'edit_user_profile', 'ivanhoe_display_notifications_options' );


function ivanhoe_display_notifications_options( $user ) {

    $notification_all_moves = get_the_author_meta( 'notification_all_moves', $user->ID );
    $notification_response_moves = get_the_author_meta( 'notification_response_moves', $user->ID );

?>
  <h3><?php _e("Ivanhoe Notifications", "ivanhoe"); ?></h3>
  <table class="form-table">
    <tr>
      <th><label for="notification_all_moves"><?php _e("All moves"); ?></label></th>
      <td>
        <input type="checkbox" name="notification_all_moves" id="notification_all_moves" value="true"
        <?php if ($notification_all_moves == true):?> checked="checked"<?php endif; ?>
 /><br />
        <span class="description"><?php _e("Check if you wish to receive an email every time someone makes a move on the games your playing."); ?></span>
    </td>
    </tr>
    <tr>
      <th><label for="notification_responses"><?php _e("Responses to your moves"); ?></label></th>
      <td>
        <input type="checkbox" name="notification_response_moves" id="notification_response_moves" value="true"
        <?php if ($notification_response_moves == true):?> checked="checked"<?php endif; ?>
 /><br />
        <span class="description"><?php _e("Check if you wish to receive an email every time someone makes a move that responds to a move you made."); ?></span>
    </td>
    </tr>

  </table>
<?php
}

add_action( 'personal_options_update', 'ivanhoe_save_notification_options' );
add_action( 'edit_user_profile_update', 'ivanhoe_save_notification_options' );

function ivanhoe_save_notification_options( $user_id ) {

    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;

    update_user_meta( $user_id, 'notification_all_moves', $_POST['notification_all_moves'] );
    update_user_meta( $user_id, 'notification_response_moves', $_POST['notification_response_moves'] );

}
