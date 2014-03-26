<?php get_header(); ?>

<?php 

$original_query = $wp_query;
$ivanhoe_game_id = $post->ID;
$ivanhoe_parent_permalink = get_permalink( $post->ID );

 ?>

<?php if (have_posts()) : while(have_posts()) : the_post(); ?>
<article class="game">
    <header>
        <h1 class="single_game_title"><?php the_title(); ?></h1>
        <p><span class="italic">Playing since</span>: <?php the_date(); ?></p> 
        <?php

        if ( $role = ivanhoe_user_has_role( $post->ID ) ) :
            $url = add_query_arg(
                "parent_post",
                $ivanhoe_game_id,
                get_permalink(get_option('ivanhoe_move_page'))
            );
        ?>
        <a href="<?php echo $url; ?>" class="button" id="make-a-move">Make a move</a>
        <?php else : ?>

        <a href="<?php echo ivanhoe_role_form_url( $post ); ?>" class="button">Make a Role!</a>

    <?php endif; ?>
    </header>   

    <div id="game-data">
        <h3>Game Description</h3>
        <?php the_content(); ?>

    </div>



<?php

$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$args = array (
    'post_type' => 'ivanhoe_move',
    'post_parent' => $post->ID,
    'paged' => $paged,
    'posts_per_page' => 10);
$wp_query = new WP_Query( $args );


if ( $wp_query->have_posts()) : ?>

<div id="moves">

<?php
 while($wp_query->have_posts()) : $wp_query->the_post(); ?>
<article class="move">
    <div class="excerpt">
        <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
        
      
      By: <?php the_author_posts_link(); ?>  

        <?php the_excerpt(); ?>
    </div>
    
        <div class="game-discussion-source">
        <?php ivanhoe_get_move_source( $post ); ?>
        </div>
        
        <div class="game-discussion-response">
        <?php ivanhoe_get_move_responses( $post ); ?>  
        </div>


      <div class="options">
        <a href="<?php echo ivanhoe_response_form_url( $post ); ?>" class="button">Respond to this move</a>
    </div>

</article>

<?php endwhile; ?>

</div>

<div id="pagination">
    <?php ivanhoe_paginate_links($wp_query);?>
</div>

</div>

<?php else : ?>

<p>No one has made a move yet in this game.  Make the first move!</p>
<?php endif; ?>

<?php $wp_query = $original_query; ?>

</article>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
