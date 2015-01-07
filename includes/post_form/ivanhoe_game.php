<?php

require_once dirname(__FILE__) . "BasePostForm.php";

/**
 * This defines the concrete implementation for the game form.
 */
class IvanhoeGame
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
    
}
