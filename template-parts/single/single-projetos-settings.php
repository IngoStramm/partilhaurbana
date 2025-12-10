<?php
$post_id = get_the_ID();
$preco = get_post_meta($post_id, 'preco', true);
$preco = $preco ? $preco : 0;
$dono_imovel = get_the_terms($post_id, 'dono-do-projeto');
$dono_imovel_nome = $dono_imovel ? $dono_imovel[0]->name : null;
$dono_imovel_id = $dono_imovel ? $dono_imovel[0]->term_id : null;
$post_thumbnail_url = get_the_post_thumbnail_url($post_id, 'full');
?>
<div class="col-md-12">
    <div class="projetos-header">
        <div class="d-flex flex-column align-items-start justify-content-start gap-2 mb-4">
            <h2 class="section-title mb-0"><?php _e('O que deseja fazer?', 'pu'); ?></h2>
            <p><?php _e('Projetos podem ser transformados em obra após sua validação.', 'pu'); ?></p>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <form action="" method="post">
                <div class="row mb-5">
                    <div class="col-md-6 mb-4">
                        <div class="form-group">
                            <label class="mb-2 form-label" for="title"><?php _e('Como deseja chamar este projeto', 'pu'); ?></label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php the_title(); ?>">
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="form-group">
                            <label class="mb-2 form-label " for="price"><?php _e('Preço de compra (ou preço do imóvel)', 'pu'); ?></label>
                            <input type="text" class="form-control money-no-decimals-input" id="price" name="price" value="<?php echo $preco; ?>">
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="form-group">
                            <label class="mb-2 form-label" for="owner"><?php _e('Quem é o dono deste imóvel?', 'pu'); ?></label>
                            <input type="text" class="form-control" id="owner" name="owner" value="<?php echo $dono_imovel_nome; ?>">
                            <input type="hidden" id="dono-do-imovel-id" name="dono-do-imovel-id" value="<?php echo $dono_imovel_id; ?>">
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="form-group form-group-image <?php echo $post_thumbnail_url ? 'has-image' : ''; ?>">
                            <div class="mb-2 form-label"><?php _e('Imagem do projeto', 'pu'); ?></div>
                            <div class="featured-image-preview">
                                <?php if ($post_thumbnail_url) { ?>
                                    <img src="<?php echo $post_thumbnail_url; ?>">
                                <?php } ?>
                                <div class="featured-image-preview-btns">
                                    <button type="button" class="btn btn-danger"><?php _e('Remover imagem', 'pu'); ?></button>
                                </div>
                            </div>
                            <div class="featured-image-inputs">
                                <input class="form-control" type="file" id="featured-image" name="featured-image" aria-label="<?php _e('Imagem do projeto', 'pu'); ?>" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p><?php _e('Aqui temos um modelo de estágios para seus Projetos e Obras. Cada Projeto e Obra podem ter estágios diferentes. A ordem e os estágios devem ser condizentes com este Projeto.', 'pu'); ?></p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle text-nowrap estagios-repeater">
                        <thead class="header-item">
                            <tr>
                                <th width="200"><?php _e('Ordem', 'pu'); ?></th>
                                <th><?php _e('Estágio', 'pu'); ?></th>
                                <th width="200"><?php _e('Ação', 'pu'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3">
                                    <button type="button" class="btn btn-secondary btn-with-icon add-new-estagio">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M18 12.998H13V17.998C13 18.2633 12.8946 18.5176 12.7071 18.7052C12.5196 18.8927 12.2652 18.998 12 18.998C11.7348 18.998 11.4804 18.8927 11.2929 18.7052C11.1054 18.5176 11 18.2633 11 17.998V12.998H6C5.73478 12.998 5.48043 12.8927 5.29289 12.7052C5.10536 12.5176 5 12.2633 5 11.998C5 11.7328 5.10536 11.4785 5.29289 11.2909C5.48043 11.1034 5.73478 10.998 6 10.998H11V5.99805C11 5.73283 11.1054 5.47848 11.2929 5.29094C11.4804 5.1034 11.7348 4.99805 12 4.99805C12.2652 4.99805 12.5196 5.1034 12.7071 5.29094C12.8946 5.47848 13 5.73283 13 5.99805V10.998H18C18.2652 10.998 18.5196 11.1034 18.7071 11.2909C18.8946 11.4785 19 11.7328 19 11.998C19 12.2633 18.8946 12.5176 18.7071 12.7052C18.5196 12.8927 18.2652 12.998 18 12.998Z" fill="white" />
                                        </svg>
                                        <?php _e('Adicionar linha', 'pu'); ?>
                                    </button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="alert alert-secondary bg-secondary">
                    <h3 class="text-white fs-4 mb-2"><?php _e('Atenção:', 'pu'); ?></h3>
                    <ul>
                        <li><?php _e('Assim que um projeto virar uma obra, você não poderá mais fazer edições nos estágios', 'pu'); ?></li>
                        <li><?php _e('Cada projeto e obra podem ter seus próprios estágios.', 'pu'); ?></li>
                    </ul>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-danger"><?php _e('Cancelar', 'pu'); ?></button>
                    <button type="submit" class="btn btn-success"><?php _e('Salvar e Publicar', 'pu'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>