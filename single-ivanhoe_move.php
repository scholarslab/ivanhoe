<?php get_header(); ?>
<?php if (have_posts()) : while(have_posts()) : the_post(); ?>
<article class="single-move">

    <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
	<div id="game-data">

 <p><?php echo get_the_title($post->post_parent); ?></p>
 <p>Written by: <?php the_author_posts_link(); ?></p>
 <p>Posted on: <?php the_date(); ?></p>

	</div>

    <div id="moves">

    <?php the_content(); ?>

        <div class="discussion-source">
        <?php ivanhoe_get_move_source( $post ); ?>
        </div>
        
        <div class="discussion-response">
        <?php ivanhoe_get_move_responses( $post ); ?>  
        </div>

        <div class="options">

        <a href="<?php echo ivanhoe_response_form_url( $post ); ?>" class="button">Respond to this move</a>

            <div id="return-button">

            <a href="<?php echo get_permalink( $post->post_parent ); ?>">Return to game</a>

            </div>
    </div>

</article>

<?php endwhile; else : ?>
<p>No one has made a move yet in this game.  Make the first move!</p>
<?php endif; ?>

</div>

<?php get_footer(); ?>
