<?php

require_once dirname(__FILE__) . "/BasePostForm.php";

/**
 * This defines the concrete implementation for the game form.
 */
class IvanhoeRole extends BasePostForm
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
    
    public function get_move_source_message($game)
    {
        return sprintf(
            __( 'You are making a role on the game '
                . '&#8220;<a href="%1$s">%2$s</a>.&#8221;', 'ivanhoe'),
            get_permalink($this->parent_post),
            $game->post_title
        );
    }
}
