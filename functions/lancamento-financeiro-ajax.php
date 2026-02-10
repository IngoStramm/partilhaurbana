<?php

add_action('wp_ajax_nopriv_pu_get_lancamento_financeiro_data', 'pu_get_lancamento_financeiro_data');
add_action('wp_ajax_pu_get_lancamento_financeiro_data', 'pu_get_lancamento_financeiro_data');

function pu_get_lancamento_financeiro_data()
{
    if (!isset($_POST['post_id']) || !$_POST['post_id']) {
        wp_send_json_error(array('msg' => __('ID do post ausente.', 'pu')), 200);
    }
    $post_id = $_POST['post_id'];
    $post_data = pu_get_lancamento_financeiro_by_id($post_id);

    $response = array(
        'msg'                   => __('Dados do lançamento financeiro encontrados.', 'pu'),
        'lancamento'                  => $post_data
    );

    wp_send_json_success($response);
}

add_action('wp_ajax_nopriv_pu_edit_lancamento_financeiro_obra', 'pu_edit_lancamento_financeiro_obra');
add_action('wp_ajax_pu_edit_lancamento_financeiro_obra', 'pu_edit_lancamento_financeiro_obra');

function pu_edit_lancamento_financeiro_obra()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'pu_edit_lancamento_financeiro_obra_nonce')) {
        wp_send_json_error(array('msg' => __('Não foi possível validar a requisição.', 'pu')), 200);
    }

    // Verifica se o usuário pode editar o post
    $check_user_permition = pu_user_can_access();

    if (!$check_user_permition) {
        wp_send_json_error(array('msg' => __('Você não possui permissão para criar/editar lançamentos financeiros.', 'pu')), 200);
    }

    $delete_post = isset($_POST['delete-post']);

    $user_id = get_current_user_id();
    $data_lancamento = pu_form_get_field('data-lancamento', __('Data do lançamento financeiro ausente.', 'pu'));
    $data = new DateTime($data_lancamento);
    $data_lancamento = $data->format('d-m-Y');
    $tipo_lancamento = pu_form_get_field('tipo-lancamento', __('Data do lançamento financeiro ausente.', 'pu'));
    $estagio_lancamento = isset($_POST['estagio-lancamento']) && $_POST['estagio-lancamento'] ? $_POST['estagio-lancamento'] : null;

    $valor_lancamento = pu_form_get_field('valor-lancamento', __('Valor do lançamento financeiro ausente.', 'pu'));
    $valor_lancamento = pu_format_number($valor_lancamento);
    $post_title = pu_form_get_field('title-lancamento', __('Título do lançamento financeiro ausente.', 'pu'));

    $obra_id = pu_form_get_field('obra_id', __('ID da obra do lançamento financeiro ausente.', 'pu'), 'absint');
    $post_id = isset($_POST['post_id']) && $_POST['post_id'] ? absint($_POST['post_id']) : null;
    // Verifica se é um post existente ou se é um novo post
    $new_post = !$post_id ? true : false;

    // verifica se está apagando o post
    $lancamento_comprovante_id = null;
    $deleted_comprovante_id = false;
    $deleted_comprovante_file = false;
    $deleted_post = false;

    $arquivo_lancamento_url = isset($_POST['arquivo-lancamento-url']) && $_POST['arquivo-lancamento-url'] ? $_POST['arquivo-lancamento-url'] : null;
    $arquivo_lancamento_file = isset($_FILES['arquivo-lancamento']) && $_FILES['arquivo-lancamento'] ? $_FILES['arquivo-lancamento'] : null;

    $remove_current_file = false;
    // Verifica as condições para apagar um arquivo

    // Se um novo arquivo foi passado
    if ($arquivo_lancamento_file['tmp_name']) {
        $remove_current_file = true;
    }
    // Se o arquivo atual foi removido
    if (!$arquivo_lancamento_url) {
        $remove_current_file = true;
    }
    // Se o post foi excluído
    if ($delete_post) {
        $remove_current_file = true;
    }

    // Remove o arquivo
    if ($remove_current_file) {
        $deleted_comprovante_id = true;
        $deleted_comprovante_file = true;
        $lancamento_comprovante_id = get_post_meta($post_id, 'comprovante_id', true);
        if ($lancamento_comprovante_id) {
            $deleted_comprovante_id = delete_post_meta($post_id, 'comprovante_id');
            $deleted_comprovante_file = delete_post_meta($post_id, 'comprovante');
        }

        if (!$deleted_comprovante_id || !$deleted_comprovante_file) {
            wp_send_json_error(array(
                'msg' => __('Ocorreu um erro ao tentar remover o comprovante do lançamento financeiro.', 'pu'),
                'post_id'                           => $post_id,
                'lancamento_comprovante_id'         => $lancamento_comprovante_id,
                'deleted_comprovante_id'            => $deleted_comprovante_id,
                'deleted_comprovante_file'          => $deleted_comprovante_file
            ), 200);
        }

        if ($lancamento_comprovante_id) {
            $delete_comprovante_attachment = wp_delete_attachment($lancamento_comprovante_id);
            if (!$delete_comprovante_attachment) {
                wp_send_json_error(array('msg' =>  __('Ocorreu um erro ao tentar remover o comprovante do lançamento financeiro, porém mesmo assim o comprovante foi removido do perfil do usuário.', 'pu')), 200);
            }
        }
    }

    // Se for para remover o post
    if ($delete_post && $post_id) {
        $deleted_post = wp_delete_post($post_id, true);
        if (!$deleted_post) {
            wp_send_json_error(array('msg' =>  __('Ocorreu um erro ao tentar remover o lançamento financeiro.', 'pu')), 200);
        }
    } else {  // Se for para salvar/editar o post
        $args = [];
        $meta_input = [];
        $args['post_type'] = 'financeiro';
        $post_data = null;
        if (!$new_post) {
            // se já existir o post
            $post_data = get_post($post_id);
            $args['ID'] = $post_id;
            $estagio_index = pu_get_estagio_index($post_id, $estagio_lancamento);
            $meta_input['estagio_lancamento'] = $estagio_index;
        } else {
            // se for um novo post
            $args['post_author'] = $user_id;
            $meta_input['projeto_id'] = $obra_id;
        }
        $args['post_status'] = 'publish';
        $args['post_excerpt'] = '';
        $args['post_content'] = '';
        $args['post_title'] = $post_title;
        $meta_input['valor'] = $valor_lancamento;
        $meta_input['data'] = $data_lancamento;
        $meta_input['tipo'] = $tipo_lancamento;
        $args['meta_input'] = $meta_input;

        $lancamento_id = wp_insert_post($args, true);
        if (is_wp_error($lancamento_id)) {
            $error_message = $lancamento_id->get_error_message();
            wp_send_json_error(array('msg' => $error_message), 200);
        }
        if ($new_post && $lancamento_id) {
            $estagio_index = pu_get_estagio_index($lancamento_id, $estagio_lancamento);
            update_post_meta($lancamento_id, 'estagio_lancamento', $estagio_index);
        }

        // Salva o Arquivo
        if ($arquivo_lancamento_file['tmp_name']) {
            $filename = $arquivo_lancamento_file['name'];
            $file_size = $arquivo_lancamento_file['size'];
            $file_tmp_name = $arquivo_lancamento_file['tmp_name'];
            if ($file_size > 2097152) {
                wp_send_json_error(array('msg' =>  sprintf(__('O arquivo %s é muito pesado, o tamanho máximo permitido é de 2MB.', 'pu'), $filename)), 200);
            }

            $upload_file = wp_upload_bits($filename, null, @file_get_contents($file_tmp_name));
            if ($upload_file['error']) {
                wp_send_json_error(array('msg' =>  sprintf(__('Ocorreu um erro ao tentar fazer o upload do arquivo %s.', 'pu'), $filename)), 200);
            } else {
                // Check the type of file. We'll use this as the 'post_mime_type'.
                $filetype = wp_check_filetype($filename, null);

                // Get the path to the upload directory.
                $wp_upload_dir = wp_upload_dir();

                // Prepare an array of post data for the attachment.
                $attachment = array(
                    'post_mime_type' => $filetype['type'],
                    'post_title'     => preg_replace('/\.[^.]+$/', '', $filename),
                    'post_content'   => '',
                    'post_status'    => 'inherit',
                    'post_parent'    => $post_id
                );

                // Insert the attachment.
                $attach_id = wp_insert_attachment($attachment, $upload_file['file'], $post_id);

                if (is_wp_error($attach_id)) {
                    wp_send_json_error(array('msg' =>  $attach_id->get_error_message()), 200);
                } else {
                    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                    require_once(ABSPATH . 'wp-admin/includes/image.php');

                    // Generate the metadata for the attachment, and update the database record.
                    $attach_data = wp_generate_attachment_metadata($attach_id, $upload_file['file']);
                    wp_update_attachment_metadata($attach_id, $attach_data);

                    $file_url = wp_get_attachment_url($attach_id);
                    update_post_meta($lancamento_id, 'comprovante', $file_url);
                    update_post_meta($lancamento_id, 'comprovante_id', $attach_id);
                }
            }
        }
    }

    $post = $_POST;
    $files = $_FILES;

    $post_data = pu_get_lancamento_financeiro_by_id($lancamento_id);
    $lancamentos_financeiros = pu_get_lancamentos_financeiro($obra_id);

    $response = array(
        'msg'                           => $deleted_post ? __('Lançamento financeiro excluído com sucesso!', 'pu') : __('Lançamento financeiro salvo com sucesso!', 'pu'),
        'post'                          => $post,
        'files'                         => $files,
        'delete_post'                   => $delete_post,
        'deleted_post'                   => $deleted_post,
        'lancamento'                    => $post_data,
        'lancamentos_financeiros'       => $lancamentos_financeiros,
        'lancamento_comprovante_id'       => $lancamento_comprovante_id,
        'deleted_comprovante_id'       => $deleted_comprovante_id,
        'deleted_comprovante_file'       => $deleted_comprovante_file,
    );

    wp_send_json_success($response);
}
