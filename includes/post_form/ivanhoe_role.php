<?php

require_once dirname(__FILE__) . "BasePostForm.php";

/**
 * This defines the concrete implementation for the game form.
 */
class IvanhoeRole
{

    public function get_post_type()
    {
        return 'ivanhoe_role';
    }

    public function populate_labels()
    {
        $this->form_title    = __( 'Make a Role', 'ivanhoe' );
        $this->title_label   = __( 'Role Name', 'ivanhoe' );
        $this->other_label   = __( 'Role Thumbnail', 'ivanhoe' );
        $this->content_label = __( 'Role Description', 'ivanhoe' );
    }
    
}


