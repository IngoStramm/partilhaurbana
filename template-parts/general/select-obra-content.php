<?php
$post_id = $args['post_id'];
$post_types = pu_obra_posts_types();
$queried_object = get_queried_object();
$archive_slug = null;
if (isset($queried_object->rewrite['slug'])) {
    $archive_slug = $queried_object->rewrite['slug'];
}
do_action('pu_select_obra_content_message');
?>
<div class="d-flex align-items-center justify-content-start gap-4">
    <h2 class="section-title mb-0"><?php _e('O que deseja ver:', 'pu'); ?></h2>
    <form id="form-select-obra-content" class="form-select-obra-content flex-shrink-0 flex-grow-1" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
        <select name="select-obra-content" id="select-obra-content" class="form-select mr-sm-2" data-post-id="<?php echo $post_id; ?>">
            <option value=""><?php _e('Selecione uma opção', 'pu'); ?></option>
            <?php foreach ($post_types as $key => $name) {
                $selected = $archive_slug === $key ? 'selected' : ''; ?>
                <option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $name; ?></option>
            <?php } ?>
        </select>
        <input type="hidden" name="action" value="pu_select_obra_content">
        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('pu_select_obra_content_nonce'); ?>">
    </form>
</div>