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
    
}


