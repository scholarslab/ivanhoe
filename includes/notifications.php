<?php

/**
 * Send move authors an email if someone responds to their move.
 */
function ivanhoe_notify_move_author($move_id) {

    $move = get_post( $move_id );

    if ( empty( $move ) )
        return false;

    $game = get_post( $move->post_parent );

    $author = get_userdata( $move->post_author );

    $emails = array();

    if ( $author ) {
        $emails[] = $author->user_email;
    }

    // Email subject.
    $subject = sprintf( __( 'New response on your move "%s"' ), $move->post_title );

    // Email message.
    $notify_message  = sprintf( __( 'New response on your move "%1$s" in the game "%2$s"' ), $move->post_title, $game->post_title ) . "\r\n";

    // Email headers.
    $message_headers = '';

    foreach ($emails as $email) {

        @wp_mail( $email, wp_specialchars_decode( $subject ), $notify_message, $message_headers );

    }

    return true;

}

