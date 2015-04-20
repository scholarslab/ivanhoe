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
            $game->post_title,
            '</div>'
        );
    }

    public function render_content(){
        return;
    }

    /**
     * This renders the form element.
     *
     * @return string
     * @author Eric Rochester <erochest@virginia.edu>
     */
    public function render_form()
    {
        $title = htmlspecialchars($this->title);

        echo '<form action="" class="new-ivanhoe-form" '
            . 'method="post" enctype="multipart/form-data">';

        echo "<div>"
            . "<label for='post_title'>$this->title_label</label>"
            . "<input type='text' size='50' name='post_title' value='$title' required>"
            . "</div>";

        $this->render_thumbnail();

        echo "<div><label for='post_content'>$this->content_label</label>";
        $this->wp_editor('Role placeholder', "post_content");
        echo "</div>";

        $this->render_rationale();

        echo '<input type="submit" class="btn" value="'
            . __( 'Save', 'ivanhoe' ) . '">';

        echo '</form>';
    }

    /**
     * Does error checking on the POST data.
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     */
    public function validate_post()
    {
        if(empty($this->title)) {
            $this->error('A title is required');
        }

        if($this->content === 'Role placeholder' ) {
            $this->error('A description is required');
        }
    }
}
