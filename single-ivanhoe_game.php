<?php get_header(); ?>

<?php 

$original_query = $wp_query;
$ivanhoe_game_id = $post->ID;
$ivanhoe_parent_permalink = get_permalink( $post->ID );

 ?>

<?php if (have_posts()) : while(have_posts()) : the_post(); ?>

    <div id = "right-column">
        <?php

        if ( is_user_logged_in() ) :

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

        <?php endif; endif; ?>
        
        <?php the_content(); ?>

    </div>

<article>
    <h1><?php the_title(); ?></h1>

<?php

$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$args = array (
    'post_type' => 'ivanhoe_move',
    'post_parent' => $post->ID,
    'paged' => $paged,
    'posts_per_page' => 10);
$wp_query = new WP_Query( $args );

if ( $wp_query->have_posts()) : while($wp_query->have_posts()) : $wp_query->the_post(); ?>
<article>
    <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
        
      <?php the_author_posts_link(); ?>  

        <?php the_excerpt(); ?>
    
	<?php

        //Pulls post source and displays it
        ivanhoe_get_move_source($post);

        //Pulls post responses and displays them
        ivanhoe_get_move_responses( $post );
    ?>  

<?php

    if ( is_user_logged_in() & ivanhoe_user_has_role( $post->ID ) ) : ?>

        <a href="<?php echo ivanhoe_response_form_url( $post ); ?>" class="button">Respond to this move</a>

<?php endif; 

?>    

</article>

<?php endwhile; ?>

<?php

$total = $wp_query->max_num_pages;

if ( $total > 1 ) {
    if ( !$current_page = $paged )
    $current_page = 1;
    if ( $format = '&paged=%#%' );

    echo paginate_links(array(
    'base'      => get_pagenum_link(1) . '%_%',
    'format'    => $format,
    'current'   => $current_page,
    'total'     => $total,
    'mid_size'  => 4,
    'type'      => 'plain'));
}

?>

<?php else : ?>

<p>No one has made a move yet in this game.  Make the first move!</p>
<?php endif; ?>

<?php $wp_query = $original_query; ?>

</article>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
