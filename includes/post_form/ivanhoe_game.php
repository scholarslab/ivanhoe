<?php

require_once dirname(__FILE__) . "/BasePostForm.php";

/**
 * This defines the concrete implementation for the game form.
 */
class IvanhoeGame extends BasePostForm
{

    public function get_post_type()
    {
        return 'ivanhoe_game';
    }

    public function populate_labels()
    {
        $this->form_title    = 'Make a Game';
        $this->title_label   = __( 'Game Title', 'ivanhoe' );
        $this->other_label   = __( 'Game Thumbnail', 'ivanhoe' );
        $this->content_label = __( 'Game Description', 'ivanhoe' );
    }
    
    public function get_status_message()
    {
        return;
    }

    public function get_move_source_message($game)
    {
        return;
    }
}

