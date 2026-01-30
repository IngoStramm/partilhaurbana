<?php $post_id = $args['post_id']; ?>
<div class="col-md-12">
    <?php get_template_part(
        'template-parts/general/obra',
        'item',
        array('post_id' => $post_id)
    ); ?>
</div>