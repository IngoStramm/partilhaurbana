<?php

add_action('wp_ajax_nopriv_pu_get_documento_data', 'pu_get_documento_data');
add_action('wp_ajax_pu_get_documento_data', 'pu_get_documento_data');

function pu_get_documento_data()
{
    if (!isset($_POST['post_id']) || !$_POST['post_id']) {
        wp_send_json_error(array('msg' => __('ID do post ausente.', 'pu')), 200);
    }
    $post_id = $_POST['post_id'];
    $post_data = pu_get_documento_by_id($post_id);

    $response = array(
        'msg'                   => __('Dados do documento encontrados.', 'pu'),
        'lancamento'                  => $post_data
    );

    wp_send_json_success($response);
}

add_action('wp_ajax_nopriv_pu_edit_documento_obra', 'pu_edit_documento_obra');
add_action('wp_ajax_pu_edit_documento_obra', 'pu_edit_documento_obra');

function pu_edit_documento_obra()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'pu_edit_documento_obra_nonce')) {
        wp_send_json_error(array('msg' => __('Não foi possível validar a requisição.', 'pu')), 200);
    }

    // Verifica se o usuário pode editar o post
    $check_user_permition = pu_user_can_access();

    if (!$check_user_permition) {
        wp_send_json_error(array('msg' => __('Você não possui permissão para criar/editar documentos.', 'pu')), 200);
    }

    $delete_post = isset($_POST['delete-post']);

    $user_id = get_current_user_id();
    $tipo = pu_form_get_field('tipo', __('Tipo do documento ausente.', 'pu'));
    $post_title = pu_form_get_field('title', __('Título do documento ausente.', 'pu'));
    $obra_id = pu_form_get_field('obra_id', __('ID da obra do documento ausente.', 'pu'), 'absint');
    $post_id = isset($_POST['post_id']) && $_POST['post_id'] ? absint($_POST['post_id']) : null;
    // Verifica se é um post existente ou se é um novo post
    $new_post = !$post_id ? true : false;

    // verifica se está apagando o post
    $arquivo_documento_id = null;
    $deleted_arquivo_id = false;
    $deleted_arquivo_file = false;
    $deleted_post = false;

    $arquivo_documento_url = isset($_POST['urls-arquivo-documento']) && $_POST['urls-arquivo-documento'] ? $_POST['urls-arquivo-documento'] : null;
    $arquivo_documento_file = isset($_FILES['arquivo-documento']) && $_FILES['arquivo-documento'] ? $_FILES['arquivo-documento'] : null;

    $check_arquivo_size = pu_check_files_size($arquivo_documento_file, false);
    if ($check_arquivo_size->status !== 'success') {
        wp_send_json_error(array('msg' => $check_arquivo_size->msg), 200);
    }

    $remove_current_file = false;
    // Verifica as condições para apagar um arquivo

    // Se um novo arquivo foi passado
    if ($arquivo_documento_file['tmp_name']) {
        $remove_current_file = true;
    }
    // Se o arquivo atual foi removido
    if (!$arquivo_documento_url) {
        $remove_current_file = true;
    }
    // Se o post foi excluído
    if ($delete_post) {
        $remove_current_file = true;
    }

    // Remove o arquivo
    if ($remove_current_file) {
        $deleted_arquivo_id = true;
        $deleted_arquivo_file = true;
        $arquivo_documento_id = get_post_meta($post_id, 'file_id', true);
        if ($arquivo_documento_id) {
            $deleted_arquivo_id = delete_post_meta($post_id, 'file_id');
            $deleted_arquivo_file = delete_post_meta($post_id, 'file');
        }

        if (!$deleted_arquivo_id || !$deleted_arquivo_file) {
            wp_send_json_error(array(
                'msg' => __('Ocorreu um erro ao tentar remover o arquivo do documento.', 'pu'),
                'post_id'                           => $post_id,
                'arquivo_documento_id'         => $arquivo_documento_id,
                'deleted_arquivo_id'            => $deleted_arquivo_id,
                'deleted_arquivo_file'          => $deleted_arquivo_file
            ), 200);
        }

        if ($arquivo_documento_id) {
            $delete_arquivo_attachment = wp_delete_attachment($arquivo_documento_id);
            if (!$delete_arquivo_attachment) {
                wp_send_json_error(array('msg' =>  __('Ocorreu um erro ao tentar remover o arquivo do documento, porém mesmo assim o arquivo foi removido do perfil do usuário.', 'pu')), 200);
            }
        }
    }

    // Se for para remover o post
    if ($delete_post && $post_id) {
        $deleted_post = wp_delete_post($post_id, true);
        if (!$deleted_post) {
            wp_send_json_error(array('msg' =>  __('Ocorreu um erro ao tentar remover o documento.', 'pu')), 200);
        }
    } else {  // Se for para salvar/editar o post
        $args = [];
        $meta_input = [];
        $args['post_type'] = 'documento';
        $post_data = null;
        if (!$new_post) {
            // se já existir o post
            $post_data = get_post($post_id);
            $args['ID'] = $post_id;
        } else {
            // se for um novo post
            $args['post_author'] = $user_id;
            $meta_input['projeto_id'] = $obra_id;
        }
        $args['post_status'] = 'publish';
        $args['post_excerpt'] = '';
        $args['post_content'] = '';
        $args['post_title'] = $post_title;
        $meta_input['documento_type'] = $tipo;
        $args['meta_input'] = $meta_input;

        $documento_id = wp_insert_post($args, true);
        if (is_wp_error($documento_id)) {
            $error_message = $documento_id->get_error_message();
            wp_send_json_error(array('msg' => $error_message), 200);
        }

        // Salva o Arquivo
        if ($arquivo_documento_file['tmp_name']) {
            $filename = $arquivo_documento_file['name'];
            $file_size = $arquivo_documento_file['size'];
            $file_tmp_name = $arquivo_documento_file['tmp_name'];
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
                    'post_parent'    => $documento_id
                );

                // Insert the attachment.
                $attach_id = wp_insert_attachment($attachment, $upload_file['file'], $documento_id);

                if (is_wp_error($attach_id)) {
                    wp_send_json_error(array('msg' =>  $attach_id->get_error_message()), 200);
                } else {
                    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                    require_once(ABSPATH . 'wp-admin/includes/image.php');

                    // Generate the metadata for the attachment, and update the database record.
                    $attach_data = wp_generate_attachment_metadata($attach_id, $upload_file['file']);
                    wp_update_attachment_metadata($attach_id, $attach_data);

                    $file_url = wp_get_attachment_url($attach_id);
                    update_post_meta($documento_id, 'file', $file_url);
                    update_post_meta($documento_id, 'file_id', $attach_id);
                }
            }
        }
    }

    $post = $_POST;
    $files = $_FILES;

    $post_data = pu_get_documento_by_id($documento_id);
    $documentos = pu_get_documentos_by_obra_id($obra_id);

    $response = array(
        'msg'                           => $deleted_post ? __('Lançamento financeiro excluído com sucesso!', 'pu') : __('Lançamento financeiro salvo com sucesso!', 'pu'),
        'meta_input'                          => $meta_input,
        'post'                          => $post,
        'files'                         => $files,
        'delete_post'                   => $delete_post,
        'deleted_post'                   => $deleted_post,
        'documento'                    => $post_data,
        'documentos'                    => $documentos,
        'documento_arquivo_id'       => $arquivo_documento_id,
        'deleted_arquivo_id'       => $deleted_arquivo_id,
        'deleted_arquivo_file'       => $deleted_arquivo_file,
    );

    wp_send_json_success($response);
}
