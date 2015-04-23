<?php

require_once dirname(__FILE__) . "/BasePostForm.php";

/**
 * This defines the concrete implementation for the game form.
 */
class IvanhoeMove extends BasePostForm
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

    public function render_thumbnail()
    {
        return;
    }
    public function render_rationale()
    {
        echo "<div><label for='post_rationale'>$this->other_label</label>";
        $this->wp_editor($this->rationale_content, 'post_rationale', array('media_btns' => false));
        echo "</div>";
    }

    public function get_move_source_message($game)
    {


        $buffer = '';


        if ( has_post_thumbnail($this->parent_post) ) {
            $buffer .= "<div class = 'move-thumbnail'>"
             . get_the_post_thumbnail($this->parent_post,'medium')
             . "</div>";
        }

         $buffer .= sprintf(
            __( 'You are making a move on the game '
                . '&#8220;<a href="%1$s">%2$s</a>&#8221;', 'ivanhoe'),
            get_permalink($this->parent_post),
            $game->post_title
        );
        if ($this->move_source) {
            $buffer .= sprintf(
                __( ' in response to the following: <ul>' , 'ivanhoe' ),
                get_permalink($this->parent_post),
                $game->post_title
            );


            foreach ($this->move_source as $move) {
                $link  = get_permalink($move);
                $title = get_the_title($move);
                $buffer .= "<li><a href='$link'>$title</a></li>";
            }

            $buffer .= "</ul>";

        } else {
            $buffer .= ".";
        }

        return $buffer;
    }

    /* Shows parent moves -ARB */
    public function render_content(){

        $content = "";

        $parent_moves = $this->move_source;

        if ($parent_moves) {
            $content = "<div class = 'parent-moves'>";
            $content = "<h2>Parent Moves</h2>";

            //count posts for possible conditions based on number of posts and to name anchors
            $count = 0;

            foreach ($this->move_source as $move) {

                $args = array (
                    'post_type'   => 'ivanhoe_role',
                    'author'      => get_post_field("post_author", $move),
                );
                $posts = get_posts($args);
                $role = reset($posts);

                $content .= "<div class=parent>";
                $content .= "<h3 class=parent-title>" . get_post_field("post_title", $move) . " by ";
                $content .= $role->post_title ."</h3>";
                $content .= "<div class = 'parent-move'>";
                $content .= get_post_field("post_content", $move);
                $content .= "</div></div>";

                $count++;

            }
        }

        // Runs shortcode to handle embeds
        global $wp_embed;
        echo $wp_embed->run_shortcode($content);

    }
}


