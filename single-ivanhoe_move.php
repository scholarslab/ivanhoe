<?php get_header(); ?>
<?php if (have_posts()) : while(have_posts()) : the_post(); ?>
<?php
    global $post;
    $game_id = $post->post_parent;
    $role = ivanhoe_user_has_role($game_id);
    $game_title = ivanhoe_get_title_by_id($game_id);
    $parent_game = get_post($game_id);
?>

        <div class="discussion-source">
            <?php echo ivanhoe_display_move_source( $post ); ?>
        </div>
        <div class="discussion-response">
            <?php echo ivanhoe_display_move_responses( $post ); ?>
        </div>
        <div class="game-description">
             <?php if ( has_post_thumbnail($game_id) ) {
              echo "<div class = 'move-thumbnail'>"
             . get_the_post_thumbnail($game_id,'medium')
             . "</div>";
             } ?>

            <h2><?php _e( 'Game Description', 'ivanhoe' ); ?></h2>
            <?php
                echo('<h3>' . $game_title . '</h3>');?>

                <div id='game-excerpt'>
                    <?php
                        $game_description = $parent_game->post_content;
                        global $wp_embed;
                        echo $wp_embed->run_shortcode($game_description);
                    ?>
                </div>

        </div>

    </div>

<article class="single-move">

    <header>
        <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>

        <p>
            <span class="byline"><?php the_author_posts_link(); ?></span>
            &middot;
            <time datetime="<?php the_time('Y-m-d'); ?>">
                <?php the_time('F j, Y'); ?>
            </time>
        </p>
        <?php if ( is_user_logged_in() ) : ?>
            <?php if ( $role !== FALSE ) : ?>
                <?php echo ivanhoe_move_link( $post ); ?>
            <?php else : ?>

                <?php $url = ivanhoe_role_form_url($post); ?>
                <?php echo ivanhoe_a($url, 'Make a Role', "class = 'btn'", ESCAPE_TEXT); ?>

            <?php endif; ?>

        <?php endif; ?>

    </header>

    <div id="moves">

        <?php the_content(); ?>

        <p class="return-btn">
            <?php $url = get_permalink( $post->post_parent); ?>
            <?php echo ivanhoe_a($url, 'Return to game'); ?>
        </p>

    </div>

</article>

<?php endwhile; endif; ?>

<?php get_footer(); ?>

<script>

$(document).ready(function(){
"use strict";

    $('#game-excerpt').readmore({});

});

</script>
