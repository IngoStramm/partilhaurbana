<?php
add_action('modal', 'pu_modal_editar_diario_da_obra');

function pu_modal_editar_diario_da_obra()
{
    if (!is_post_type_archive('diario-da-obra')) {
        return;
    }
    $obra_id = isset($_GET['obra_id']) && $_GET['obra_id'] ? $_GET['obra_id'] : get_the_ID();
    $estagios_obra = get_post_meta($obra_id, 'estagios_settings', true);
?>
    <div class="modal fade" id="modal-editar-diario-da-obra" tabindex="-1" aria-labelledby="#modal-editar-diario-da-obra" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-extra-padding modal-fullscreen-lg-down">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form
                        id="form-editar-diario-da-obra"
                        class="form-editar-diario-da-obra needs-validation"
                        action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
                        method="post"
                        class="needs-validation new-projeto-form"
                        enctype="multipart/form-data"
                        novalidate>

                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <h4><?php _e('Diário da Obra', 'pu') ?></h4>
                                </div>
                            </div>
                            <div class="row mb-3 gy-3">

                                <div class="col-md-4">
                                    <div class="form-group flex-shrink-0 flex-grow-1">
                                        <label for="diario-da-obra-date" class="form-label mb-3"><?php _e('Data da evolução', 'pu'); ?></label>
                                        <input id="diario-da-obra-date" name="diario-da-obra-date" type="date" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group flex-shrink-0 flex-grow-1">
                                        <label for="diario-da-obra-semana" class="form-label mb-3"><?php _e('Semana', 'pu'); ?></label>
                                        <select name="diario-da-obra-semana" id="diario-da-obra-semana" class="form-select" required="">
                                            <option value="">Selecione uma opção</option>
                                            <?php
                                            for ($i = 1; $i <= 52; $i++) {
                                                echo "<option>$i</option>";
                                            } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group flex-shrink-0 flex-grow-1">
                                        <label for="estagio-diario" class="form-label mb-3"><?php _e('Fase do diário', 'pu'); ?></label>
                                        <select name="estagio-diario" id="estagio-diario" class="form-select">
                                            <option value=""><?php _e('Selecione uma opção', 'pu'); ?></option>
                                            <?php foreach ($estagios_obra as $k => $estagio) {
                                                $title = $estagio['title'];
                                                echo "<option>$title</option>";
                                            } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group flex-shrink-0 flex-grow-1">
                                        <label for="diario-da-obra-content" class="form-label mb-3"><?php _e('Fale um pouco sobre a evolução', 'pu'); ?></label>
                                        <textarea id="diario-da-obra-content" name="diario-da-obra-content" class="form-control" required></textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div id="diario-da-obra-featured-image-container"></div>
                                </div>
                                <div class="col-md-12">
                                    <div id="diario-da-obra-gallery-image-container"></div>
                                </div>
                                <div class="col-md-12">
                                    <div id="diario-da-obra-gallery-video-container"></div>
                                </div>


                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="modal-alert-placeholder"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-end align-items-center gap-3 pb-5">
                                    <button id="btn-delete-diario-da-obra" type="submit" name="delete-post" class="btn bg-danger text-white me-auto" data-original-text="<?php _e('Excluir', 'pu'); ?>" disabled>
                                        <?php _e('Excluir', 'pu'); ?>
                                    </button>

                                    <button type="button" class="btn bg-warning text-white" data-bs-dismiss="modal">
                                        <?php _e('Cancelar', 'pu'); ?>
                                    </button>

                                    <button id="btn-save-diario-da-obra" type="submit" class="btn btn-success" data-original-text="<?php _e('Salvar', 'pu'); ?>" disabled>
                                        <?php _e('Salvar', 'pu'); ?>
                                    </button>

                                    <input type="hidden" name="action" value="pu_edit_diario_da_obra">
                                    <input type="hidden" name="post_id" value="">
                                    <input type="hidden" name="obra_id" value="<?php echo $obra_id; ?>">
                                    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('pu_edit_diario_da_obra_nonce'); ?>">
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
}
