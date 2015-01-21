<?php

/**
 * This has the default implementation of the POST form for all of the post types.
 */
abstract class BasePostForm
{
    /**
     * The post to edit.
     */
    var $new_post;

    /**
     * This is the title of the form.
     *
     * @var string
     */
    var $form_title;

    /**
     * This is the post title label.
     *
     * @var string
     */
    var $title_label;

    /**
     * This is the post's content.
     *
     * @var string
     */
    var $content_label;

    /**
     * This is the label for the post's rationale or thumbnail or whatever.
     *
     * @var string
     */
    var $other_label;

    /**
     * The post title.
     *
     * @var string
     */
    var $title;

    /**
     * The post content.
     *
     * @var string
     */
    var $content;

    /**
     * The parent post.
     *
     * @var string/int
     */
    var $parent_post;

    /**
     * The move being responded to.
     *
     * @var array
     */
    var $move_source;

    /**
     * The role creating the move.
     *
     * @var string/int
     */
    var $role_id;

    /**
     * The rationale for the post.
     *
     * @var string
     */
    var $rationale;

    /**
     * The title of the rationale.
     *
     * @var string
     */
    var $rationale_title;

    /**
     * The rationale's content.
     *
     * @var string
     */
    var $rationale_content;

    /**
     * An array of error messages accumulated during processing.
     *
     * @var array(string)
     */
    var $error_messages;

    /**
     * Create the post form.
     */
    public function __construct()
    {
        $this->new_post = get_default_post_to_edit(
            $this->get_post_type(),
            true
        );

        $this->populate_labels();
        $this->read_http_vars();

        $this->rationale_title   = "";
        $this->rationale_content = "";

        $this->error_messages = array();
    }

    /**
     * This renders the entire page.
     *
     * @return string
     * @author Eric Rochester <erochest@virginia.edu>
     **/
    public function render()
    {
        if (!empty($_POST)) {
            $this->validate_post();
            if (!$this->has_errors()) {
                $this->insert_new_post();
                $this->redirect();
                exit;
            }
        }

        echo "<strong>Starting to render...</strong>";
        $game   = get_post($this->parent_post);
        $buffer = "";

        $buffer .= $this->get_header();
        $buffer .= $this->get_status_message($game);
        $buffer .= $this->render_form_title();
        $buffer .= $this->render_errors();
        $buffer .= $this->render_message($game);
        $buffer .= $this->render_form();
        $buffer .= $this->get_footer();
        echo "<strong>Done rendering...</strong>";

        return $buffer;
    }

    /**
     * This redirects to the game or to the parent.
     *
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     */
    public function redirect()
    {
        $url = get_post_type_archive_link('ivanhoe_game');

        if ($this->parent_post) {
            $url = get_permalink($this->parent_post);
        }

        wp_redirect($url);
    }

    /**
     * Return the type of post being created.
     *
     * @return string
     * @author Eric Rochester <erochest@virginia.edu>
     **/
    abstract function get_post_type();

    /**
     * This needs to populate the label properties.
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     **/
    abstract function populate_labels();

    /**
     * This reads the post variables from the POST and GET data.
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     */
    public function read_http_vars()
    {
        $this->title       = !empty ( $_POST['post_title'] )
            ? $_POST['post_title'] : null;
        $this->content     = !empty ( $_POST['post_content'] )
            ? $_POST['post_content'] : null;

        // Move variables.
        $this->parent_post = isset( $_GET['parent_post'] )
            ? $_GET['parent_post'] : null;
        $this->move_source = isset ( $_GET['move_source'] )
            ? $_GET['move_source'] : null;
        $this->role_id     = isset( $_GET['ivanhoe_role_id'] )
            ? $_GET['ivanhoe_role_id'] : null;
        $this->rationale   = !empty ( $_POST['post_rationale'])
            ? $_POST['post_rationale'] : null;

        if (!is_array($this->move_source)) {
            $this->move_source = array($this->move_source);
        }
    }

    /**
     * Add an error message.
     *
     * @param $msg string The error message to add.
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     */
    public function error($msg)
    {
        $this->error_messages[] = __($msg, 'ivanhoe');
    }

    /**
     * Does error checking on the POST data.
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     */
    public function validate_post($post)
    {
        if(empty($this->title)) {
            $this->error('A title is required');
        }

        if(empty($this->content)) {
            $this->error('A description is required');
        }
    }

    /**
     * Test whether any errors have been flagged.
     *
     * @return bool
     * @author Eric Rochester <erochest@virginia.edu>
     */
    public function has_errors()
    {
        return (! empty($this->error_messages));
    }

    /**
     * This inserts the new post into the database and returns it.
     *
     * @return post
     * @author Eric Rochester <erochest@virginia.edu>
     */
    public function insert_new_post()
    {
        $data = array(
            'ID'           => $this->new_post->ID,
            'post_content' => $this->content,
            'post_title'   => $this->title,
            'post_status'  => 'publish',
            'post_type'    => $this->get_post_type()
        );

        if ($this->parent_post) {
            $data['post_parent'] = $this->parent_post;
        }

        $post = wp_insert_post($data);
        $this->add_image($post);
        $this->add_move_source($post);
        $this->add_rationale($post);

        return $post;
    }

    /**
     * This adds an image (if there is one) to the form.
     *
     * @param $post Post The post to add the image to.
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     */
    public function add_image($post)
    {
        if (isset($_FILES['post_thumbnail'])) {
            ivanhoe_add_image('post_thumbnail', $post);
        }
    }

    /**
     * This adds whatever move source metadata is there to the post.
     *
     * @param $post Post The post to add the metadata to.
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     */
    public function add_move_source($post)
    {
        if ($this->move_source) {
            foreach ($this->move_source as $move) {
                add_post_meta(
                    $post,
                    'Ivanhoe Move Source',
                    $move
                );
            }
        }
    }

    /**
     * This adds whatever rationale is there to the post.
     *
     * TODO: Decide whether or not we want the RJ to be public.
     *
     * @param $post Post The post to add the rationale to.
     *
     * @return rationale post or null
     * @author Eric Rochester <erochest@virginia.edu>
     */
    public function add_rationale($post)
    {
        $journal_entry = null;

        if ($this->rationale) {
            $title = sprintf(
                __( 'Journal Entry for %s', 'ivanhoe' ),
                $this->title
            );
            // TODO: check which rationale is which
            $journal_entry_data = array(
                'post_content' => $this->rationale,
                'post_title'   => $title,
                'post_status'  => 'publish',
                'post_type'    => 'ivanhoe_role_journal',
                'post_parent'  => $post
            );

            $journal_entry = wp_insert_post( $journal_entry_data );
            update_post_meta(
                $journal_entry,
                'Ivanhoe Game Source',
                $this->parent_post
            );
            update_post_meta(
                $journal_entry,
                'Ivanhoe Role ID',
                $this->role_id
            );
        }

        return $journal_entry;
    }

    /**
     * This returns a status message, telling the user what she just did.
     *
     * @return string
     * @author Eric Rochester <erochest@virginia.edu>
     */
    public function get_status_message($game)
    {
        $message = "";

        $message .= $this->get_making_message($game);
        $message .= $this->get_move_source_message($game);

        return $message;
    }

    /**
     * This returns a 'You are making a....' message.
     *
     * @return string
     * @author Eric Rochester <erochest@virginia.edu>
     **/
    abstract function get_making_message($game);

    /**
     * This creates a move source status message.
     *
     * @param $game post
     *
     * @return string
     * @author Eric Rochester <erochest@virginia.edu>
     */
    public function get_move_source_message($game)
    {
        $message = "";

        if ($this->move_source) {
            $message .= sprintf(
                __( 'You are making a move on the game '
                . '&#8220;<a href="%1$s">%2$s</a>&#8221; in response '
                . 'to the following: <ul>' , 'ivanhoe' ),
                get_permalink($this->parent_post),
                $game->post_title
            );

            foreach ($this->move_source as $move) {
                $link  = get_permalink($move);
                $title = get_the_title($move);
                $message .= "<li><a href='$link'>$title</a></li>";
            }

            $message .= "</ul>";
        }
        return $message;
    }

    /**
     * This returns the header for the form.
     *
     * @return string
     * @author Eric Rochester <erochest@virginia.edu>
     */
    public function render_form_title()
    {
        return "<header><h1>{$this->form_title}</h1></header>";
    }

    /**
     * This returns any output for error messages.
     *
     * @return string
     * @author Eric Rochester <erochest@virginia.edu>
     */
    public function render_errors()
    {
        $output = "";

        if ($this->has_errors()) {
            $output .= ivanhoe_print_errors($this->error_messages);
            $this->error_messages = array();
        }

        return $output;
    }

    /**
     * This gets and renders the message, if there is one.
     *
     * @return string
     * @author Eric Rochester <erochest@virginia.edu>
     */
    public function render_message($game)
    {
        $message = $this->get_status_message($game);

        if (!empty($message)) {
            $message =
                "<div class='new-ivanhoe-meta new-ivanhoe-move-meta'>" .
                "<p><strong>$message</strong></p></div>";
        }

        return $message;
    }

    /**
     * This returns the page header as a string.
     *
     * @return string
     * @author Eric Rochester <erochest@virginia.edu>
     */
    public function get_header()
    {
        ob_start();
        get_header();
        $header = ob_end_flush();
        return $header;
    }

    /**
     * This returns the page footer as a string.
     *
     * @return string
     * @author Eric Rochester <erochest@virginia.edu>
     */
    public function get_footer()
    {
        ob_start();
        get_footer();
        $footer = ob_end_flush();
        return $footer;
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
        $form  = "";

        $form .= '<form action="" class="new-ivanhoe-form" '
            . 'method="post" enctype="multipart/form-data">';

        $form .= "<div>"
            . "<label for='post_title'>$this->title_label</label>"
            . "<input type='text' size='50' name='post_title' value='$title' required>"
            . "</div>";

        $form .= $this->render_thumbnail();

        $form .= "<div>"
            . "<label for='post_content'>$this->content_label</label>"
            . $this->wp_editor($this->content, "post_content")
            . "</div>";

        $form .= $this->render_rationale();

        $form .= '<input type="submit" class="btn" value="'
            . _e( 'Save', 'ivanhoe' ) . '">';

        $form .= '</form>';

        return $form;
    }

    /**
     * Renders the form elements for the thumbnail.
     *
     * @return string
     * @author Eric Rochester <erochest@virginia.edu>
     */
    public function render_thumbnail()
    {
        $input = "<div>"
            . "<label for='post_thumbnail'>$this->other_label</label>"
            . "<input type='file' name='post_thumbnail'>"
            . "</div>";
        return $input;
    }

    /**
     * This outputs the WP editor.
     *
     * @return string
     * @author Eric Rochester <erochest@virginia.edu>
     */
    public function wp_editor($content, $name, $param=array())
    {
        ob_start();
        wp_editor($content, $name, $param);
        $editor = ob_get_contents();
        ob_end_clean();

        return $editor;
    }

    /**
     * This returns the form element for the rationale.
     *
     * @return string
     * @author Eric Rochester <erochest@virginia.edu>
     */
    public function render_rationale()
    {
        return "";
    }
}
