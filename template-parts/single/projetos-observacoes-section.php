<?php
$post_id = get_the_ID();
$observacoes = get_post_meta($post_id, 'observacoes', true);
?>
<div class="col-md-12">
    <h2 class="section-title"><?php _e('Observações', 'pu'); ?></h2>
    <div class="card card-body">
        <div class="form-group">
            <textarea id="projeto-observacoes" name="projeto-observacoes" class="form-control" rows="4" aria-label="<?php _e('Observações', 'pu'); ?>"><?php echo $observacoes; ?></textarea>
        </div>
    </div>
</div>