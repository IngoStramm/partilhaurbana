<?php

add_action('modal', 'pu_modal_transformar_em_obra');

function pu_modal_transformar_em_obra()
{
?>
    <div class="modal fade" id="modal-transforma-projeto-em-obra" tabindex="-1" aria-labelledby="modal-transforma-projeto-em-obra" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-extra-padding">
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
