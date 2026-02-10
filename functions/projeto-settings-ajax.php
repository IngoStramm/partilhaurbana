<?php

add_action('wp_ajax_nopriv_pu_projeto_settings_form', 'pu_projeto_settings_form');
add_action('wp_ajax_pu_projeto_settings_form', 'pu_projeto_settings_form');

function pu_projeto_settings_form()
{
    if (!isset($_POST['pu_projeto_settings_form_nonce']) || !wp_verify_nonce($_POST['pu_projeto_settings_form_nonce'], 'pu_projeto_settings_form_nonce')) {
        wp_send_json_error(array('msg' => __('Não foi possível validar a requisição.', 'pu')), 200);
    }

    $post_id = isset($_POST['post_id']) && $_POST['post_id'] ? $_POST['post_id'] : null;
    $new_projeto = !$post_id ? true : false;

    // Verifica se o usuário existe
    $user_id = pu_form_get_field('user_id', __('ID do usuário ausente', 'pu'), 'absint');
    $check_user_exists = get_user_by('id', $user_id);
    if (!$check_user_exists) {
        wp_send_json_error(array('msg' => __('Usuário inválido.', 'pu')), 200);
    }

    // Verifica se o usuário pode editar o post
    $check_user_permition = pu_user_can_access();

    if (!$check_user_permition) {
        wp_send_json_error(array('msg' => __('Você não possui permissão para editar este projeto.', 'pu')), 200);
    }

    if (!isset($_POST['title']) || !$_POST['title']) {
        wp_send_json_error(array('msg' => __('Título do projeto ausente.', 'pu')), 200);
    }

    $post_title = pu_form_get_field('title', __('Título do projeto ausente.', 'pu'));
    $post_price = pu_form_get_field('price', __('Preço do projeto ausente.', 'pu'), 'money');
    $post_owner = pu_form_get_field('owner', __('Dono do projeto ausente.', 'pu'));

    $estagios = pu_form_get_field('estagios', __('Estágios do projeto ausente.', 'pu'), 'array');
    // Argumentos para salvar/criar o post
    $args = [];
    $args['post_type'] = 'projetos';
    if ($post_id) {
        // se existir o post, define como publicado
        $post_data = get_post($post_id);
        $args['ID'] = $post_id;
        $args['post_content'] = get_post_field('post_content', $post_id);
        $args['post_status'] = $post_data->post_status;
        $args['post_author'] = $post_data->post_author;
        $args['post_title'] = $post_title;
    } else {
        // senão, define como rascunho
        $args['post_status'] = 'publish';
        $args['post_author'] = $user_id;
        $args['post_title'] = $post_title;
        $args['post_content'] = '';
        $args['post_excerpt'] = '';
    }

    $meta_input = [];
    $meta_input['preco'] = $post_price;
    $meta_input['dono_do_projeto'] = $post_owner;
    $meta_input['estagios_settings'] = [];
    $old_estagios = get_post_meta($post_id, 'estagios_settings', true);
    if (is_array($estagios)) {
        foreach ($estagios as $estagio_name) {
            $effort = 0;
            $cost = 0;
            foreach ($old_estagios as $old_estagio) {
                if ($old_estagio['title'] === $estagio_name) {
                    $effort = $old_estagio['effort'];
                    $cost = $old_estagio['cost'];
                }
            }
            $estagio = array(
                'title'         => $estagio_name,
                'effort'        => $effort,
                'cost'          => $cost,
            );
            $meta_input['estagios_settings'][] = $estagio;
        }
    }
    $args['meta_input'] = $meta_input;

    $update_projeto_id = wp_insert_post($args, true);
    if (is_wp_error($update_projeto_id)) {
        $error_message = $update_projeto_id->get_error_message();
        wp_send_json_error(array('msg' => $error_message), 200);
    }

    $update_status_do_projeto = wp_set_post_terms($update_projeto_id, array('projeto'), 'status-do-projeto');
    if (is_wp_error($update_status_do_projeto) || !$update_status_do_projeto) {
        $error_message = is_wp_error($update_status_do_projeto) ? $update_status_do_projeto->get_error_message() : __('Ocorreu um erro ao salvar o status do projeto');
        wp_send_json_error(array('msg' => $error_message), 200);
    }

    // Imagem do Projeto

    $featured_image_file = isset($_FILES['featured-image']) && $_FILES['featured-image'] ? $_FILES['featured-image'] : null;

    $delete_featured_image = isset($_POST['delete-featured-image']) && $_POST['delete-featured-image'] ? (bool)$_POST['delete-featured-image'] : null;

    // Apaga a imagem atual do projeto
    if ($delete_featured_image && $update_projeto_id) {
        $deleted_projeto_thumbnail_id = true;

        $projeto_thumbnail_id = get_post_meta($update_projeto_id, '_thumbnail_id', true);
        if ($projeto_thumbnail_id) {
            $deleted_projeto_thumbnail_id = delete_post_meta($update_projeto_id, '_thumbnail_id');
        }

        if (!$deleted_projeto_thumbnail_id) {
            wp_send_json_error(array('msg' => __('Ocorreu um erro ao tentar remover a imagem principal do imóvel.', 'pu')), 200);
        }

        if ($projeto_thumbnail_id) {
            $delete_projeto_thumbnail_attachment = wp_delete_attachment($projeto_thumbnail_id);
            if (!$delete_projeto_thumbnail_attachment) {
                wp_send_json_error(array('msg' =>  __('Ocorreu um erro ao tentar remover o arquivo do servidor, porém mesmo assim o avatar foi removido do perfil do usuário.', 'pu')), 200);
            }
        }
    }

    // Adiciona a nova imagem do projeto
    if (isset($featured_image_file['name']) && $featured_image_file['name'] && $update_projeto_id) {

        // Pega as infromações do arquivo
        $file = $featured_image_file;
        $filename = $file['name'];
        $file_size = $file['size'];
        $file_tmp_name = $file['tmp_name'];

        // verifica o tamanho do arquivo
        if ($file_size > 2097152) {
            wp_send_json_error(array('msg' => sprintf(__('O arquivo %s é muito pesado, o tamanho máximo permitido é de 2MB..', 'pu'), $filename)), 200);
        }

        // Faz o upload da nova imagem
        $upload_file = wp_upload_bits($filename, null, @file_get_contents($file_tmp_name));
        // exit;
        if (!$upload_file['error']) {
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
            );

            // Insert the attachment.
            $attach_id = wp_insert_attachment($attachment, $upload_file['file']);

            if (!is_wp_error($attach_id)) {
                // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                require_once(ABSPATH . 'wp-admin/includes/image.php');

                // Generate the metadata for the attachment, and update the database record.
                $attach_data = wp_generate_attachment_metadata($attach_id, $upload_file['file']);
                wp_update_attachment_metadata($attach_id, $attach_data);
            } else {
                wp_send_json_error(array('msg' => $attach_id->get_error_message()), 200);
            }
        } else {
            wp_send_json_error(array('msg' => sprintf(__('Ocorreu um erro ao tentar fazer o upload do arquivo %s.', 'pu'), $filename)), 200);
        }
        $updated_thumbnail = set_post_thumbnail($update_projeto_id, $attach_id);
        if (!$updated_thumbnail) {
            wp_send_json_error(array('msg' => __('Ocorreu um erro ao atualizar a imagem principal do projeto', 'pu')), 200);
        }
    }

    $projetos_data = pu_get_projeto_data($update_projeto_id);

    $response = array(
        'msg'                   => __('Projeto salvo com sucesso!', 'pu'),
        'projetos_data'         => $projetos_data
    );

    if ($new_projeto) {
        $response['redirect_to'] = get_post_permalink($update_projeto_id) . '?view=settings';
    }

    wp_send_json_success($response);
}
