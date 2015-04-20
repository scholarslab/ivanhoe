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

    public function get_status_message($game)
    {
        return;
    }

    public function get_move_source_message($game)
    {
        return '';
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
        $this->wp_editor('Game placeholder', "post_content");
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

        if($this->content === 'Game placeholder' ) {
            $this->error('A description is required');
        }
    }
}

