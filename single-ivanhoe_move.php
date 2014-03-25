<?php get_header(); ?>
<?php if (have_posts()) : while(have_posts()) : the_post(); ?>
<article class="single-move">
    <header>
            <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
            <p>-<cite><?php the_author_posts_link(); ?></cite></p> 
            <p class="date-published"><?php the_date(); ?></p>
           <a href="<?php echo ivanhoe_response_form_url( $post ); ?>" class="button">Respond to this move</a> 
    </header>
    <div class="source-response-container">

        <div class="discussion-source">
            <?php ivanhoe_get_move_source( $post ); ?>
        </div>
	   <div class="discussion-response">
            <!-- <h2><?php echo get_the_title($post->post_parent); ?></h2> -->
            <?php ivanhoe_get_move_responses( $post ); ?> 
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
