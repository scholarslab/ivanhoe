<?php
/*
Template Name: Ivanhoe Game Form
*/

// If we have a game ID and a post title, insert a post.
if ( !empty( $_POST ) ) {

    $ivanhoe_post_title = $_POST['post_title'];
    $ivanhoe_post_content = $_POST['post_content'];

    $game = array(
        'post_content' => $ivanhoe_post_content,
        'post_title' => $ivanhoe_post_title,
        'post_status' => 'publish',
        'post_type' => 'ivanhoe_game',
    );

    $new_ivanhoe_post_id = wp_insert_post( $game );

    wp_redirect( get_post_type_archive_link('ivanhoe_game') );
    exit;

}

get_header();

?>

<header>
<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
</header>

<form action="" class="new-post" method="post" enctype="multipart/form-data">
<div><label for="post_title"><?php _e( 'Game Title', 'ivanhoe' ); ?></label>
    <input type="text" size="50" name="post_title" required>
    </div>

    <div>
    <label for="post_content"><?php _e( 'Game Description', 'ivanhoe' ); ?></label>
    <?php wp_editor( '', "post_content"); ?>
    </div>

    <input type="submit" value="<?php _e( 'Submit', 'ivanhoe' ); ?>">
</form>


<?php 

    get_footer(); 

?>

