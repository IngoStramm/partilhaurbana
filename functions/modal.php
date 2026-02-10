<?php

add_action('modal', 'pu_modal_transformar_em_obra');

function pu_modal_transformar_em_obra()
{
?>
    <div class="modal fade" id="modal-transforma-projeto-em-obra" tabindex="-1" aria-labelledby="modal-transforma-projeto-em-obra" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-extra-padding modal-fullscreen-lg-down">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4><?php _e('Deseja transformar este projeto em obra?', 'pu') ?></h4>
                    <p><strong><?php _e('Ao transformar este projeto em obra você irá:', 'pu'); ?></strong></p>
                    <ul class="list-with-dots">
                        <li><?php _e('Criar a Obra deste projeto na aba Obras', 'pu'); ?></li>
                        <li><?php _e('Você poderá evoluir o diário da obra, marketing, financeiro e documentos da nova Obra', 'pu'); ?></li>
                        <li><?php _e('Poderá atribuir usuários de equipe (para trabalhar) ou clientes (para acompanhar) a Obra', 'pu'); ?></li>
                        <li><?php _e('Você pode continuar a editar os esforços por etapa e projeção de lucratividades sempre que quiser aqui na aba projetos', 'pu'); ?></li>
                        <li><?php _e('Não poderá excluir uma Obra até que ela seja Finalizada', 'pu'); ?></li>
                    </ul>
                </div>
                <div class="modal-footer d-block">
                    <p><strong><?php _e('Esta ação é irreversível, deseja prosseguir?', 'pu'); ?></strong></p>
                    <div class="d-flex gap-4 mt-4">
                        <button type="button" class="btn bg-danger text-white" data-bs-dismiss="modal">
                            <?php _e('Cancelar', 'pu'); ?>
                        </button>
                        <form id="form-convert-projeto-to-obra" class="form-convert-projeto-to-obra needs-validation" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="needs-validation new-projeto-form" novalidate>
                            <button id="btn-confirmar-transformar-projeto-em-obra" type="submit" class="btn btn-success">
                                <?php _e('Transformar o projeto em obra', 'pu'); ?>
                            </button>
                            <input type="hidden" name="action" value="pu_convert_projeto_to_obra">
                            <input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>">
                            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('pu_convert_projeto_to_obra_nonce'); ?>">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}

add_action('modal', 'pu_modal_editar_lancamento_financeiro_obra');

function pu_modal_editar_lancamento_financeiro_obra()
{
    $obra_id = isset($_GET['obra_id']) && $_GET['obra_id'] ? $_GET['obra_id'] : get_the_ID();
    $estagios_obra = get_post_meta($obra_id, 'estagios_settings', true);
    // pu_debug(wp_timezone());
?>
    <div class="modal fade" id="modal-editar-lancamento-financeiro-obra" tabindex="-1" aria-labelledby="modal-editar-lancamento-financeiro-obra" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-extra-padding modal-fullscreen-lg-down">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form
                        id="form-edit-lancamento-financeiro-obra"
                        class="form-edit-lancamento-financeiro-obra needs-validation"
                        action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
                        method="post"
                        class="needs-validation new-projeto-form"
                        enctype="multipart/form-data"
                        novalidate>

                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <h4><?php _e('Novo lançamento financeiro', 'pu') ?></h4>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="form-group flex-shrink-0 flex-grow-1">
                                        <label for="data-lancamento" class="form-label mb-3"><?php _e('Data lançamento', 'pu'); ?></label>
                                        <input type="date" name="data-lancamento" id="data-lancamento" class="form-control" required>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group flex-shrink-0 flex-grow-1">
                                        <label for="tipo-lancamento" class="form-label mb-3"><?php _e('Tipo de lançamento', 'pu'); ?></label>
                                        <select name="tipo-lancamento" id="tipo-lancamento" class="form-select" required>
                                            <option value=""><?php _e('Selecione uma opção', 'pu'); ?></option>
                                            <option value="entrada"><?php _e('Entrada', 'pu') ?></option>
                                            <option value="saida"><?php _e('Saída', 'pu') ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group flex-shrink-0 flex-grow-1">
                                        <label for="estagio-lancamento" class="form-label mb-3"><?php _e('Fase do lançamento', 'pu'); ?></label>
                                        <select name="estagio-lancamento" id="estagio-lancamento" class="form-select">
                                            <option value=""><?php _e('Selecione uma opção', 'pu'); ?></option>
                                            <?php foreach ($estagios_obra as $k => $estagio) {
                                                $title = $estagio['title'];
                                                echo "<option>$title</option>";
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <div class="form-group flex-shrink-0 flex-grow-1">
                                        <label for="title-lancamento" class="form-label mb-3"><?php _e('Nome do lançamento', 'pu'); ?></label>
                                        <input id="title-lancamento" name="title-lancamento" type="text" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group flex-shrink-0 flex-grow-2">
                                        <label for="valor-lancamento" class="form-label mb-3"><?php _e('Valor', 'pu'); ?></label>
                                        <input id="valor-lancamento" name="valor-lancamento" type="text" class="form-control money-input" default="0,00" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="form-group file-input" id="file-input">
                                        <label for="arquivo-lancamento" class="mb-2 form-label">
                                            <?php _e('Adicionar comprovante', 'pu'); ?>
                                        </label>
                                        <input class="form-control" type="file" id="arquivo-lancamento" name="arquivo-lancamento">
                                        <input type="hidden" name="arquivo-lancamento-url" id="arquivo-lancamento-url">
                                        <div id="arquivo-lancamento-url-text" class="arquivo-lancamento-url-text"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="modal-alert-placeholder"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-end align-items-center gap-3 pb-5">
                                    <button id="btn-delete-lancamento" type="submit" name="delete-post" class="btn bg-danger text-white me-auto" data-original-text="<?php _e('Excluir', 'pu'); ?>" disabled>
                                        <?php _e('Excluir', 'pu'); ?>
                                    </button>

                                    <button type="button" class="btn bg-warning text-white" data-bs-dismiss="modal">
                                        <?php _e('Cancelar', 'pu'); ?>
                                    </button>

                                    <button id="btn-save-lancamento-financeiro" type="submit" class="btn btn-success" data-original-text="<?php _e('Salvar', 'pu'); ?>" disabled>
                                        <?php _e('Salvar', 'pu'); ?>
                                    </button>

                                    <input type="hidden" name="action" value="pu_edit_lancamento_financeiro_obra">
                                    <input type="hidden" name="post_id" value="">
                                    <input type="hidden" name="obra_id" value="<?php echo $obra_id; ?>">
                                    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('pu_edit_lancamento_financeiro_obra_nonce'); ?>">
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
