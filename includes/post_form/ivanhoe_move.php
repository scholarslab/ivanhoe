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
        $buffer = sprintf(
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
        
        $content = "<div class = 'parent-moves'>";
        
        //count posts for possible conditions based on number of posts and to name anchors
        $count = 0;
               
        foreach ($this->move_source as $move) {
                        
            $args = array (
                'post_type'   => 'ivanhoe_role',
                'author'      => get_post_field("post_author", $move),
            );
            $posts = get_posts($args);
            $role = reset($posts);
            
<<<<<<< HEAD
            $author = '';
            
            $content .= "<div class = 'parent-move'>";
            $content .= "<h1>" . get_post_field("post_title", $move) . " by ";
            $content .= $role->post_title ."</h1>"; 
=======
            $content .= "<div id = parent>" ;        
            $content .= "<h2 class = parent-title>" . get_post_field("post_title", $move) . " by ";
            $content .= $role->post_title ."</h2>"; 
            $content .= "<div class = 'parent-move'>";
>>>>>>> c73815b... Add .js to toggle parent moves on move page
            $content .= get_post_field("post_content", $move);
            $content .= "</div></div>";
                
            $count++;
            
          
        }
        
        //fixes dumb stuff with shortcode embeds
        global $wp_embed;
        echo $wp_embed->run_shortcode($content) . "</div>";
        
    }
}


