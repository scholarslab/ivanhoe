<?php
    get_header();

    $original_query           = $wp_query;
    $ivanhoe_game_id          = $post->ID;
    $ivanhoe_parent_permalink = get_permalink( $post->ID );
    $role                     = ivanhoe_user_has_role( $post->ID );

    // Character list
    $character_args = array(
        'post_type' => 'ivanhoe_role',
        'post_parent' => $ivanhoe_game_id
    );
    $characters = new WP_Query ( $character_args );
    $character_posts = $characters->get_posts();

    // Pagination
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $pagination_args = array (
        'post_type'   => 'ivanhoe_move',
        'post_parent' => $post->ID,
        'paged'       => $paged
    );

?>

<?php if (have_posts()) : while(have_posts()) : the_post(); ?>
<article class="game">
    <header>
        <h1><?php the_title(); ?></h1>
        <p>
            <?php printf( __('Playing since: %s', 'ivanhoe' ), get_the_time('F j, Y') ); ?>
        </p>

    </header>

    <div id="game-data">
        <!-- Shows role of current user -->
        <?php if ($role !== FALSE): ?>

        <h3><?php _e( 'Your Current Role', 'ivanhoe' ); ?></h3>
        <article class="role">
            <a href="<?php echo get_permalink( $role->ID); ?>" class="image-container"><?php echo get_the_post_thumbnail($role->ID, 'medium'); ?></a>
            <a href="<?php echo get_permalink( $role->ID ); ?>"><?php echo $role->post_title; ?></a>
        </article>

        <?php endif; ?>
        <!-- Ends section showing current role -->

        <!-- Shows list of other characters -->
        <?php
            if ( !empty( $character_posts ) ) :
            if ( is_user_logged_in() && $role !== FALSE ) : ?>
                <h3><?php _e( 'Other Characters', 'ivanhoe' ); ?></h3>
                <article>
                <?php if ( $characters->have_posts() ) : ?>
                    <ul class='character_list'>
                        <?php while ( $characters->have_posts() ) : $characters->the_post();
                            if ($post->ID == $role->ID) continue; ?>
                                <li class='role'>
                                    <a href="<?php echo get_permalink( $post->ID ); ?>" class="image-container"><?php echo get_the_post_thumbnail($post->ID, 'medium'); ?></a>
                                    <a href="<?php echo get_permalink( $post->ID ); ?>"><?php echo $post->post_title; ?></a>
                                </li>
                        <?php endwhile; ?>
                    </ul>
                <?php endif; ?>
                </article>
                <?php wp_reset_postdata(); ?>

            <?php else : ?>
                <h3><?php _e( 'Characters', 'ivanhoe' ); ?></h3>
                <article>
                    <?php if ( $characters->have_posts() ) : ?>
                        <ul class='character_list'>
                    <?php while ( $characters->have_posts() ) : $characters->the_post(); ?>
                        <li class='role'>
                            <a href="<?php echo get_permalink( $post->ID ); ?>" class="image-container"><?php echo get_the_post_thumbnail($post->ID, 'medium'); ?></a>
                            <a href="<?php echo get_permalink( $post->ID ); ?>"><?php echo $post->post_title; ?></a>
                        </li>
                    <?php endwhile; ?>
                        </ul>
                    <?php endif; ?>
                </article>
                <?php wp_reset_postdata(); ?>
                <?php endif;
            endif; ?>
            <!-- Ends section showing other characters -->

        <h3><?php _e( 'Game Description', 'ivanhoe' ); ?></h3>

        <?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?>

        <?php the_content(); ?>

        <div>
        <!-- Shows either the make a move button or the make a role button -->
            <?php if ( is_user_logged_in() ) :

                if ( $role !== FALSE ) :

                ?>

                    <form name="move_info" action='<?php echo home_url(); ?>' method='get'>
                        <input type="hidden" name="ivanhoe" value="ivanhoe_move">
                        <input type='hidden' name='parent_post' value='<?php echo $ivanhoe_game_id; ?>'>
                        <input type='hidden' name='ivanhoe_role_id' value='<?php echo $role->ID; ?>'>
                        <h3 id='multi_source_list_of_doom_header'>Responding to the following</h3>
                        <ul class="basic_element_of_semantically_incoherent_metaphor">
                        </ul>
                        <input type="submit" name="movesubmit" value="<?php _e( 'Make a Move', 'ivanhoe' ); ?>" class="btn" id="respond-to-move">
                    </form>

                <?php else : ?>

                    <?php $url = ivanhoe_role_form_url($post); ?>

                    <?php echo ivanhoe_a($url, 'Make a Role!', 'class="btn"', ESCAPE_TEXT); ?>

                <?php endif; ?>

            <?php endif; ?>
            <!-- Ends section showing buttons -->

        </div>
    </div>

    <!-- Main content of page -->
    <?php
    // removed pagination logic from here
    $wp_query = new WP_Query( $pagination_args );
    if ( $wp_query->have_posts()) : ?>

    <div id="moves">
        <?php echo ivanhoe_paginate_links($wp_query);?>

        <?php
        while($wp_query->have_posts()) : $wp_query->the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header>
                <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                <p><span class="byline"><?php the_author_posts_link(); ?></span>
            &middot; <time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('F j, Y'); ?></time></p>
                <?php $ivanhoe_post_id=$post->ID; ?>
                    <span class="new_source btn" data-title="<?php echo get_the_title($ivanhoe_post_id); ?>" data-value="<?php echo $ivanhoe_post_id; ?>">Add to Moves</span>
            </header>

            <div class="excerpt">

                <?php
                    echo ivanhoe_media_excerpt();
                    the_excerpt();
                ?>

            </div>

            <div class="game-discussion-source">
                <?php echo ivanhoe_display_move_source( $post ); ?>
            </div>

            <div class="game-discussion-response">
                <?php echo ivanhoe_display_move_responses( $post ); ?>
            </div>

        </article>

        <?php endwhile; ?>

        <?php echo ivanhoe_paginate_links($wp_query);?>
    </div>



    <?php else : ?>

    <p><?php _e( 'There are no moves for this game.', 'ivanhoe' ); ?></p>

    <?php endif; ?>

<?php $wp_query = $original_query; ?>

</article>
<!-- Ends main content of page -->

<?php endwhile; endif; ?>

<?php get_footer(); ?>

<script type="text/javascript">
    var ivanhoe_selected_moves = {};

    function update_button(){
        var li = $('.basic_element_of_semantically_incoherent_metaphor li');
        var header = $('#multi_source_list_of_doom_header');
        if (li.length === 0) {
            document.move_info.
            movesubmit.value="Make a Move";
            header.hide ();
        } else {
            document.move_info.
            movesubmit.value="Respond";
            header.show ();
        }
    }

    $('.new_source').click(function(){
        var $this = $(this);
        var value = $this.data('value');
        if (ivanhoe_selected_moves[value] == null) {
            $('.basic_element_of_semantically_incoherent_metaphor').append
            ("<li><input type='hidden' value='" + value + "' name='move_source[]'>" + $this.data('title') + "</li>").click
            (function( event ) {
                $(event.target).remove();
                update_button();
                delete ivanhoe_selected_moves[value];
            });

            update_button();
        ivanhoe_selected_moves[value] = true;
        }
    });

    update_button();

</script>
