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
     * @var string/int
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
        return "";
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
        $this->error_messages[] = $msg;
    }

}
