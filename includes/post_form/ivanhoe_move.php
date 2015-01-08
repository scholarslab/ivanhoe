<?php

require_once dirname(__FILE__) . "BasePostForm.php";

/**
 * This defines the concrete implementation for the game form.
 */
class IvanhoeMove
{

    public function get_post_type()
    {
        return 'ivanhoe_move';
    }

    public function populate_labels()
    {
        $this->form_title    = __( 'Make a Move', 'ivanhoe' );
        $this->title_label   = __( 'Move Title', 'ivanhoe' );
        $this->content_label = __( 'Move Content', 'ivanhoe' );
        $this->other_label   = __( 'Rationale', 'ivanhoe' );
    }
    
    public function get_making_message($game)
    {
        return sprintf(
            __( 'You are making a move on the game '
                . '&#8220;<a href="%1$s">%2$s</a>.&#8221;', 'ivanhoe'),
            get_permalink($parent_post),
            $ivanhoe_game->post_title
        );
    }
}


