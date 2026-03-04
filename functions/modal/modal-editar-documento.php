<?php

add_action('modal', 'pu_modal_editar_documento_obra');

function pu_modal_editar_documento_obra()
{
    $obra_id = isset($_GET['obra_id']) && $_GET['obra_id'] ? $_GET['obra_id'] : get_the_ID();
    if (!is_post_type_archive('documento')) {
        return;
    }
?>
    <div class="modal fade" id="modal-editar-documento-obra" tabindex="-1" aria-labelledby="modal-editar-documento-obra" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-extra-padding modal-fullscreen-lg-down">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form
                        id="form-edit-documento-obra"
                        class="form-edit-documento-obra needs-validation"
                        action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
                        method="post"
                        class="needs-validation new-documento-form"
                        enctype="multipart/form-data"
                        novalidate>

                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <h4><?php _e('Novo documento', 'pu') ?></h4>
                                </div>
                            </div>
                            <div class="row mb-3 gy-3">

                                <div class="col-md-7">
                                    <div class="form-group flex-shrink-0 flex-grow-1">
                                        <label for="title" class="form-label mb-3"><?php _e('Nome do documento', 'pu'); ?></label>
                                        <input id="title" name="title" type="text" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group flex-shrink-0 flex-grow-1">
                                        <label for="tipo" class="form-label mb-3"><?php _e('Fornecedor', 'pu'); ?></label>
                                        <select name="tipo" id="tipo" class="form-select" required>
                                            <option value=""><?php _e('Selecione uma opção', 'pu'); ?></option>
                                            <?php $options = pu_documento_type();
                                            foreach ($options as $k => $option) {
                                                echo "<option value='$k'>$option</option>";
                                            } ?>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group" id="arquivo-documento-container"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div id="modal-alert-placeholder"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-end align-items-center gap-3 pb-5">
                                    <button id="btn-delete-documento" type="submit" name="delete-post" class="btn bg-danger text-white me-auto" data-original-text="<?php _e('Excluir', 'pu'); ?>" disabled>
                                        <?php _e('Excluir', 'pu'); ?>
                                    </button>

                                    <button type="button" class="btn bg-warning text-white" data-bs-dismiss="modal">
                                        <?php _e('Cancelar', 'pu'); ?>
                                    </button>

                                    <button id="btn-save-documento" type="submit" class="btn btn-success" data-original-text="<?php _e('Salvar', 'pu'); ?>" disabled>
                                        <?php _e('Salvar', 'pu'); ?>
                                    </button>

                                    <input type="hidden" name="action" value="pu_edit_documento_obra">
                                    <input type="hidden" name="post_id" value="">
                                    <input type="hidden" name="obra_id" value="<?php echo $obra_id; ?>">
                                    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('pu_edit_documento_obra_nonce'); ?>">
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
