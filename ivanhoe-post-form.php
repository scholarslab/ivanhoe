<?php
if(!is_user_logged_in()) {
    wp_redirect(get_home_url()); // ensure user is logged in
}

require_once ABSPATH . 'wp-admin/includes/post.php' ;

$IVANHOE_DIR = dirname(__FILE__);

$post_type = $_GET['ivanhoe'];
$class_name = str_replace(' ', '', ucwords(str_replace('_', ' ', $post_type)));
require_once($IVANHOE_DIR . "/includes/post_form/$post_type.php");
$post_form = new $class_name();

echo $post_form->render();

?>

<!-- HERE WE ARE -->

<header>
<h1><?php echo $form_title; ?></h1>
</header>

<?php if( $error_messages ) : ?>
  <?php echo ivanhoe_print_errors($error_messages); ?>
<?php endif; ?>

<?php if ( $message ) : ?>
<div class="new-ivanhoe-meta new-ivanhoe-move-meta">
    <p><strong><?php echo $message; ?></strong></p>
</div>
<?php endif; ?>

<form action="" class="new-ivanhoe-form" method="post" enctype="multipart/form-data">

<div>
    <label for="post_title"><?php echo $post_title_label; ?></label>
    <input type="text" size="50" name="post_title" value="<?php echo $post_title; ?>" required>
</div>

<?php if( $post_type !== 'ivanhoe_move' ):  ?>
<div>
    <label for="post_thumbnail"><?php echo $post_thumbnail_label; ?></label>
    <input type="file" name="post_thumbnail">
</div>

<?php endif; ?>

<div>
    <label for="post_content"><?php echo $post_content_label; ?></label>
    <?php wp_editor( $post_content, "post_content"); ?>
</div>

<?php if($post_type == 'ivanhoe_move'): ?>
  <div>
    <label for="post_rationale"><?php echo $post_rationale_label; ?></label>
    <?php wp_editor( $post_rationale, 'post_rationale', array('media_btns' => false)); ?>
  </div>
<?php endif; ?>

<input type="submit" class="btn" value="<?php _e( 'Save', 'ivanhoe' ); ?>">

</form>

<?php get_footer();
