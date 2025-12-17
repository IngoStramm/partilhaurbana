<?php
$new_projeto = !is_singular('projetos');
$post_id = $new_projeto ? null : get_the_ID();
$user_id = get_current_user_id();
$preco = $new_projeto ? null : get_post_meta($post_id, 'preco', true);
$preco = !$new_projeto && $preco ? $preco : 0;
$dono_projeto = $new_projeto ? null : get_the_terms($post_id, 'dono-do-projeto');
$dono_projeto_nome = $dono_projeto ? $dono_projeto[0]->name : null;
$dono_projeto_id = $dono_projeto ? $dono_projeto[0]->term_id : null;
$post_thumbnail_url = $new_projeto ? '' : get_the_post_thumbnail_url($post_id, 'full');
$pu_projeto_settings_form_nonce = wp_create_nonce('pu_projeto_settings_form_nonce');
$estagios = $new_projeto ? [] : get_post_meta($post_id, 'estagios_settings', true);
// pu_debug(pu_get_projeto_data());
?>
<div class="col-md-12">
    <div class="projetos-header">
        <div class="d-flex flex-column align-items-start justify-content-start gap-2 mb-4">
            <?php if ($new_projeto) { ?>
                <h2 class="section-title mb-0"><?php _e('Novo projeto!', 'pu'); ?></h2>
            <?php } else { ?>
                <h2 class="section-title mb-0"><?php _e('O que deseja fazer?', 'pu'); ?></h2>
            <?php } ?>
            <p><?php _e('Projetos podem ser transformados em obra após sua validação.', 'pu'); ?></p>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <form id="form-settings-projeto" class="form-settings-projeto needs-validation" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="needs-validation new-projeto-form" enctype="multipart/form-data" novalidate>
                <div class="row mb-5">
                    <div class="col-md-6 mb-4">
                        <div class="form-group">
                            <label class="mb-2 form-label" for="title"><?php _e('Como deseja chamar este projeto', 'pu'); ?></label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo $new_projeto ? '' : get_the_title(); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="form-group">
                            <label class="mb-2 form-label " for="price"><?php _e('Preço de compra (ou preço do imóvel)', 'pu'); ?></label>
                            <input type="text" class="form-control money-no-decimals-input" id="price" name="price" value="<?php echo $preco; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="form-group">
                            <label class="mb-2 form-label" for="owner"><?php _e('Quem é o dono deste imóvel?', 'pu'); ?></label>
                            <input type="text" class="form-control" id="owner" name="owner" value="<?php echo $dono_projeto_nome; ?>" required>
                            <input type="hidden" id="dono-do-projeto-id" name="dono-do-projeto-id" value="<?php echo $dono_projeto_id ? $dono_projeto_id : ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="form-group file-image-preview" id="file-image-preview">
                            <div class="mb-2 form-label"><?php _e('Imagem do projeto', 'pu'); ?></div>
                            <ul id="images-preview" class="images-preview">
                            </ul>
                            <div class="image-inputs">
                                <input class="form-control" type="file" id="featured-image" name="featured-image" aria-label="<?php _e('Imagem do projeto', 'pu'); ?>" />
                                <input type="hidden" id="delete-featured-image" name="delete-featured-image">
                            </div>
                            <input type="hidden" class="changed-thumbnail">
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

                <div id="form-alert-placeholder"></div>
                <div class="form-actions">
                    <?php /* ?><button type="submit" class="btn btn-danger"><?php _e('Cancelar', 'pu'); ?></button><?php */ ?>
                    <button type="submit" class="btn btn-success" disabled><?php _e('Salvar e Publicar', 'pu'); ?></button>
                </div>
                <input type="hidden" value="pu_projeto_settings_form" name="action">
                <input type="hidden" value="<?php echo $pu_projeto_settings_form_nonce; ?>" name="pu_projeto_settings_form_nonce" id="pu_projeto_settings_form_nonce">
                <?php if (!$new_projeto) { ?>
                    <input type="hidden" value="<?php echo $post_id; ?>" name="post_id" id="post_id">
                <?php } ?>
                <input type="hidden" value="<?php echo $user_id; ?>" name="user_id" id="$user_id">
            </form>
        </div>
    </div>
</div>