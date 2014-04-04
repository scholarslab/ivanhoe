<?php get_header(); ?>
<?php if (have_posts()) : while(have_posts()) : the_post(); ?>
<article class="single-move">

    <header>
            <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
            <p><span class="citation">By:</span><span class="author-date"><?php the_author_posts_link(); ?></span></p>
            <p class="date-published"><?php the_date(); ?></p>
            <?php
           $game_id = $post->post_parent;
           $role = ivanhoe_user_has_role( $game_id );

           if ( is_user_logged_in() ) :

            if ( $role ) : ?>

            <a href="<?php echo ivanhoe_response_form_url( $post ); ?>" class="button">Respond to this move</a> 

            <?php else : ?>

            <a href="<?php echo ivanhoe_role_form_url( $post ); ?>" class="button">Make a Role!</a>

            <?php endif; ?>

        <?php endif; ?>
    </header>
    <div class="source-response-container">

        <div class="discussion-source">
            <?php ivanhoe_get_move_source( $post ); ?>
        </div>
	   <div class="discussion-response">
            <!-- <h2><?php echo get_the_title($post->post_parent); ?></h2> -->
            <?php ivanhoe_get_move_responses( $post ); ?> 
       </div>
        <div class="game-description">
            <h2>Game Info</h2>
			<?php global $post;
            $parent_ID = $post->post_parent;
			$game_title = get_title_by_id( $parent_ID );
            $game_excerpt = get_excerpt_by_id( $parent_ID );
            echo $game_title;
			echo $game_excerpt; ?>

        </div>
    </div>
 
    <div id="moves">

    <?php the_content(); ?>
            
    <a class="return-button" href="<?php echo get_permalink( $post->post_parent ); ?>">Return to game</a>

    </div>

</article>

<?php endwhile; else : ?>
<p>No one has made a move yet in this game.  Make the first move!</p>
<?php endif; ?>



<?php get_footer(); ?>
