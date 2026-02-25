<?php

add_action('wp_ajax_nopriv_pu_get_diario_da_obra_data', 'pu_get_diario_da_obra_data');
add_action('wp_ajax_pu_get_diario_da_obra_data', 'pu_get_diario_da_obra_data');

function pu_get_diario_da_obra_data()
{
    if (!isset($_POST['post_id']) || !$_POST['post_id']) {
        wp_send_json_error(array('msg' => __('ID do post ausente.', 'pu')), 200);
    }
    $post_id = $_POST['post_id'];
    $post_data = pu_get_diario_da_obra_by_id($post_id);

    $response = array(
        'msg'                   => __('Dados do diário da obra encontrados.', 'pu'),
        'diario_da_obra'        => $post_data
    );

    wp_send_json_success($response);
}

add_action('wp_ajax_nopriv_pu_edit_diario_da_obra', 'pu_edit_diario_da_obra');
add_action('wp_ajax_pu_edit_diario_da_obra', 'pu_edit_diario_da_obra');

function pu_edit_diario_da_obra()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'pu_edit_diario_da_obra_nonce')) {
        wp_send_json_error(array('msg' => __('Não foi possível validar a requisição.', 'pu')), 200);
    }

    // Verifica se o usuário pode editar o post
    $check_user_permition = pu_user_can_access();

    if (!$check_user_permition) {
        wp_send_json_error(array('msg' => __('Você não possui permissão para criar/editar diários da obra.', 'pu')), 200);
    }

    $user_id = get_current_user_id();
    $data_diario_da_obra_str = pu_form_get_field('diario-da-obra-date', __('Data do diário da obra ausente.', 'pu'));
    $site_timezone = wp_timezone();
    $data_obj = new DateTime($data_diario_da_obra_str, $site_timezone);
    $data_diario_da_obra = $data_obj->format('d-m-Y');

    $diario_semana = pu_form_get_field('diario-da-obra-semana', __('semana do diário ausente.', 'pu'));

    $diario_estagio = pu_form_get_field('estagio-diario', __('Estágio do diário ausente.', 'pu'));

    $diario_descricao = pu_form_get_field('diario-da-obra-content', __('Descrição do diário ausente.', 'pu'));

    $diario_title = sprintf(__('Dia %s de %s de %s', 'pu'), $data_obj->format('d'), pu_get_translated_month($data_obj->format('m')), $data_obj->format('Y'));


    $obra_id = pu_form_get_field('obra_id', __('ID da obra do diário da obra ausente.', 'pu'), 'absint');
    $post_id = isset($_POST['post_id']) && $_POST['post_id'] ? absint($_POST['post_id']) : null;
    // Verifica se é um post existente ou se é um novo post
    $new_post = !$post_id ? true : false;

    // verifica se está apagando o post
    $delete_post = isset($_POST['delete-post']) && $post_id;


    $deleted_videos_meta = false;
    $deleted_post = false;

    $featured_image_url = isset($_POST['urls-diario-da-obra-featured-image']) && $_POST['urls-diario-da-obra-featured-image'] ? $_POST['urls-diario-da-obra-featured-image'] : null;
    $featured_image_file = isset($_FILES['diario-da-obra-featured-image']) && $_FILES['diario-da-obra-featured-image'] ? $_FILES['diario-da-obra-featured-image'] : null;

    $gallery_image_urls = isset($_POST['urls-diario-da-obra-gallery-image']) && $_POST['urls-diario-da-obra-gallery-image'] ? $_POST['urls-diario-da-obra-gallery-image'] : null;
    $gallery_image_files = isset($_FILES['diario-da-obra-gallery-image']) && $_FILES['diario-da-obra-gallery-image'] ? $_FILES['diario-da-obra-gallery-image'] : null;

    $gallery_video_urls = isset($_POST['urls-diario-da-obra-gallery-video']) && $_POST['urls-diario-da-obra-gallery-video'] ? $_POST['urls-diario-da-obra-gallery-video'] : null;
    $gallery_video_files = isset($_FILES['diario-da-obra-gallery-video']) && $_FILES['diario-da-obra-gallery-video'] ? $_FILES['diario-da-obra-gallery-video'] : null;

    $check_featured_image_size = pu_check_files_size($featured_image_file, false);
    if ($check_featured_image_size->status !== 'success') {
        wp_send_json_error(array('msg' => $check_featured_image_size->msg), 200);
    }

    $check_gallery_image_size = pu_check_files_size($gallery_image_files);
    if ($check_gallery_image_size->status !== 'success') {
        wp_send_json_error(array('msg' => $check_gallery_image_size->msg), 200);
    }

    $check_gallery_video_size = pu_check_files_size($gallery_video_files, true, 'video');
    if ($check_gallery_video_size->status !== 'success') {
        wp_send_json_error(array('msg' => $check_gallery_video_size->msg), 200);
    }

    // Controle de remoção de arquivos
    $remove_featured_image = false;
    $photos_to_remove = [];
    $videos_to_remove = [];
    $photos_meta = $post_id ? get_post_meta($post_id, 'photos', true) : [];
    $videos_meta = $post_id ? get_post_meta($post_id, 'videos', true) : [];

    // Só irá fazer a verificação se NÃO for um novo post

    // Verificação Post Thumbnail (featured image)
    // se for para apagar o post 
    // ou o arquivo atual foi removido 
    // ou um novo arquivo foi passado (é para remover a imagem atual)
    if ($delete_post || !$featured_image_url || $featured_image_file['tmp_name']) {
        $remove_featured_image = true;
    }

    // Verificação Galerias de Imagem
    // se for para apagar o post ou o campo de url vier vazio (é para remover tudo)
    if ($delete_post || !$gallery_image_urls) {
        $photos_to_remove = $photos_meta;
    } else {
        $urls = explode(',', $gallery_image_urls);
        foreach ($photos_meta as $id => $video) {
            // Verifica se algum arquivo existente foi removido e armazena em um array
            if (!in_array($video, $urls)) {
                $photos_to_remove[$id] = $video;
            }
        }
    }

    // Verificação Galerias de Vídeo
    // se for para apagar o post ou o campo de url vier vazio (é para remover tudo)
    if ($delete_post || !$gallery_video_urls) {
        $videos_to_remove = $videos_meta;
    } else {
        $urls = explode(',', $gallery_video_urls);
        foreach ($videos_meta as $id => $video) {
            // Verifica se algum arquivo existente foi removido e armazena em um array
            if (!in_array($video, $urls)) {
                $videos_to_remove[$id] = $video;
            }
        }
    }

    // Remove o featured image (post thumbnail)
    if ($delete_post || ($remove_featured_image && $post_id)) {
        $removed_thumbnail_id = true;

        $curr_thumbnail_id = get_post_meta($post_id, '_thumbnail_id', true);
        if ($curr_thumbnail_id) {
            $removed_thumbnail_id = delete_post_meta($post_id, '_thumbnail_id');
        }

        if (!$removed_thumbnail_id) {
            wp_send_json_error(array('msg' => __('Ocorreu um erro ao tentar remover a imagem principal do imóvel.', 'pu')), 200);
        }

        if ($curr_thumbnail_id) {
            $remove_thumbnail_attachment = wp_delete_attachment($curr_thumbnail_id);
            if (!$remove_thumbnail_attachment) {
                wp_send_json_error(array('msg' =>  __('Ocorreu um erro ao tentar remover o arquivo do servidor, porém mesmo assim o avatar foi removido do perfil do usuário.', 'pu')), 200);
            }
        }
    }

    // Remove Galeria de imagens
    if ($delete_post || ($photos_to_remove || count($photos_to_remove) > 0)) {
        foreach ($photos_to_remove as $thumb_id => $video) {
            $count = 0;
            if (in_array($video, $photos_meta)) {
                if (isset($photos_meta[$thumb_id])) {
                    unset($photos_meta[$thumb_id]);
                }
                $count++;
            }
        }

        // Remove completamente as fotos
        $deleted_photos_meta = delete_post_meta($post_id, 'photos');

        // Se não for para apagar o post
        if (!$delete_post) {
            // readiciona as fotos atualizadas
            $add_photos_meta = add_post_meta($post_id, 'photos', $photos_meta);
        }

        if (!$deleted_photos_meta) {
            wp_send_json_error(array(
                'msg' => __('Ocorreu um erro ao tentar remover a(s) foto(s) do diário.', 'pu'),
                'post_id'                           => $post_id,
                'photos_meta'            => $photos_meta,
                'deleted_photos_meta'            => $deleted_photos_meta,
                'photos_to_remove'          => $photos_to_remove
            ), 200);
        }

        foreach ($photos_to_remove as $thumb_id => $video) {
            $delete_attachment = wp_delete_attachment($thumb_id);
            if (!$delete_attachment) {
                wp_send_json_error(array('msg' =>  __('Ocorreu um erro ao tentar remover o arquivo da foto do servidor, porém mesmo assim a foto foi removida do diário.', 'pu')), 200);
            }
        }
    }

    // Remove Galeria de videos
    if ($delete_post || $videos_to_remove || count($videos_to_remove) > 0) {
        foreach ($videos_to_remove as $thumb_id => $video) {
            $count = 0;
            if (in_array($video, $videos_meta)) {
                if (isset($videos_meta[$thumb_id])) {
                    unset($videos_meta[$thumb_id]);
                }
                $count++;
            }
        }

        // Remove completamente aos vídeos
        $deleted_videos_meta = delete_post_meta($post_id, 'videos');

        // Se não for para apagar o post
        if (!$delete_post) {
            // readiciona as fotos atualizadas
            $add_videos_meta = add_post_meta($post_id, 'videos', $videos_meta);
        }

        if (!$deleted_videos_meta) {
            wp_send_json_error(array(
                'msg' => __('Ocorreu um erro ao tentar remover o(s) vídeo(s) do diário.', 'pu'),
                'post_id'                           => $post_id,
                'videos_meta'            => $videos_meta,
                'deleted_videos_meta'            => $deleted_videos_meta,
                'videos_to_remove'          => $videos_to_remove
            ), 200);
        }

        foreach ($videos_to_remove as $thumb_id => $video) {
            $delete_attachment = wp_delete_attachment($thumb_id);
            if (!$delete_attachment) {
                wp_send_json_error(array('msg' =>  __('Ocorreu um erro ao tentar remover o arquivo de vídeo do servidor, porém mesmo assim o vídeo foi removido do diário.', 'pu')), 200);
            }
        }
    }

    // Se for para remover o post
    if ($delete_post && $post_id) {

        $deleted_post = wp_delete_post($post_id, true);
        if (!$deleted_post) {
            wp_send_json_error(array('msg' =>  __('Ocorreu um erro ao tentar remover o diário da obra.', 'pu')), 200);
        }
    } else {  // Se for para salvar/editar o post
        $args = [];
        $meta_input = [];
        $args['post_type'] = 'diario-da-obra';
        $post_data = null;
        if (!$new_post) {
            // se já existir o post
            $post_data = get_post($post_id);
            $args['ID'] = $post_id;
            $estagio_index = pu_get_estagio_index($post_id, $diario_estagio);
            $meta_input['estagio_diario'] = $estagio_index;
        } else {
            // se for um novo post
            $args['post_author'] = $user_id;
            $meta_input['projeto_id'] = $obra_id;
        }
        $args['post_status'] = 'publish';
        $args['post_excerpt'] = '';
        $args['post_content'] = '';
        $args['post_title'] = $diario_title;
        $meta_input['data'] = $data_diario_da_obra;
        $meta_input['semana'] = $diario_semana;
        $meta_input['description'] = $diario_descricao;
        $args['meta_input'] = $meta_input;

        $diario_id = wp_insert_post($args, true);
        if (is_wp_error($diario_id)) {
            $error_message = $diario_id->get_error_message();
            wp_send_json_error(array('msg' => $error_message), 200);
        }
        if ($new_post && $diario_id) {
            $estagio_index = pu_get_estagio_index($diario_id, $diario_estagio);
            update_post_meta($diario_id, 'estagio_diario', $estagio_index);
        }

        // Salva a Featured Image (post thumbnail)
        if (isset($featured_image_file['name']) && $featured_image_file['name'] && $diario_id) {

            // Pega as informações do arquivo
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
            $updated_thumbnail = set_post_thumbnail($diario_id, $attach_id);
            if (!$updated_thumbnail) {
                wp_send_json_error(array('msg' => __('Ocorreu um erro ao atualizar a imagem principal do diário', 'pu')), 200);
            }
        }

        // Salva a galeria de imagens
        if ($gallery_image_files['tmp_name'][0]) {

            $count = 0;
            $galeria_urls = array();
            foreach ($gallery_image_files['tmp_name'] as $tmp_name) {
                $file = $gallery_image_files;
                $filename = $file['name'][$count];
                $file_size = $file['size'][$count];
                $file_tmp_name = $file['tmp_name'][$count];

                // if ($file_size > 2097152) {
                //     wp_send_json_error(array('msg' => sprintf(__('O arquivo %s é muito pesado, o tamanho máximo permitido é de 2MB..', 'pu'), $filename)), 200);
                // }

                $upload_file = wp_upload_bits($filename, null, @file_get_contents($file_tmp_name));
                if ($upload_file['error']) {
                    wp_send_json_error(array('msg' => sprintf(__('Ocorreu um erro ao tentar fazer o upload do arquivo %s.', 'mi'), $filename)), 200);
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
                        'post_parent'    => $diario_id
                    );

                    // Insert the attachment.
                    $attach_id = wp_insert_attachment($attachment, $upload_file['file'], $diario_id);

                    if (is_wp_error($attach_id)) {
                        wp_send_json_error(array('msg' => $attach_id->get_error_message()), 200);
                    } else {
                        // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                        require_once(ABSPATH . 'wp-admin/includes/image.php');

                        // Generate the metadata for the attachment, and update the database record.
                        $attach_data = wp_generate_attachment_metadata($attach_id, $upload_file['file']);
                        wp_update_attachment_metadata($attach_id, $attach_data);

                        $galeria_urls[$attach_id] = wp_get_attachment_url($attach_id);
                    }
                }
                $count++;
            }
            $new_galeria_meta = [];
            foreach ($galeria_urls as $id => $url) {
                $new_galeria_meta[$id] = $url;
            }
            $new_galeria_meta = array_replace($photos_meta, $new_galeria_meta);
            delete_post_meta($diario_id, 'photos');
            $updated_gallery = update_post_meta($diario_id, 'photos', $new_galeria_meta);
            if (!$updated_gallery) {
                wp_send_json_error(array('msg' => __('Ocorreu um erro ao tentar atualizar a galeria de imagens.', 'pu')), 200);
            }
        }

        // Salva a galeria de vídeos
        if ($gallery_video_files['tmp_name'][0]) {

            $count = 0;
            $galeria_urls = array();
            foreach ($gallery_video_files['tmp_name'] as $tmp_name) {
                $file = $gallery_video_files;
                $filename = $file['name'][$count];
                $file_size = $file['size'][$count];
                $file_tmp_name = $file['tmp_name'][$count];

                // if ($file_size > 2097152) {
                //     wp_send_json_error(array('msg' => sprintf(__('O arquivo %s é muito pesado, o tamanho máximo permitido é de 2MB..', 'pu'), $filename)), 200);
                // }

                $upload_file = wp_upload_bits($filename, null, @file_get_contents($file_tmp_name));
                if ($upload_file['error']) {
                    wp_send_json_error(array('msg' => sprintf(__('Ocorreu um erro ao tentar fazer o upload do arquivo %s.', 'mi'), $filename)), 200);
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
                        'post_parent'    => $diario_id
                    );

                    // Insert the attachment.
                    $attach_id = wp_insert_attachment($attachment, $upload_file['file'], $diario_id);

                    if (is_wp_error($attach_id)) {
                        wp_send_json_error(array('msg' => $attach_id->get_error_message()), 200);
                    } else {
                        // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                        require_once(ABSPATH . 'wp-admin/includes/image.php');

                        // Generate the metadata for the attachment, and update the database record.
                        $attach_data = wp_generate_attachment_metadata($attach_id, $upload_file['file']);
                        wp_update_attachment_metadata($attach_id, $attach_data);

                        $galeria_urls[$attach_id] = wp_get_attachment_url($attach_id);
                    }
                }
                $count++;
            }
            $new_galeria_meta = [];
            foreach ($galeria_urls as $id => $url) {
                $new_galeria_meta[$id] = $url;
            }
            $new_galeria_meta = array_replace($photos_meta, $new_galeria_meta);
            delete_post_meta($diario_id, 'videos');
            $updated_gallery = update_post_meta($diario_id, 'videos', $new_galeria_meta);
            if (!$updated_gallery) {
                wp_send_json_error(array('msg' => __('Ocorreu um erro ao tentar atualizar a galeria de imagens.', 'pu')), 200);
            }
        }
    }

    // $post = $_POST;
    // $files = $_FILES;

    $post_data = $diario_id ? pu_get_diario_da_obra_by_id($diario_id) : null;
    $diarios_da_obra = pu_get_diarios_de_obra_by_obra_id($obra_id);

    $response = array(
        'msg'                           => $deleted_post ? __('Diário da obra excluído com sucesso!', 'pu') : __('diário da obra salvo com sucesso!', 'pu'),
        // 'post'                          => $post,
        // 'files'                         => $files,
        // 'delete_post'                   => $delete_post,
        'gallery_image_files'                   => $gallery_image_files,
        'gallery_video_files'                   => $gallery_video_files,
        'diario'                        => $post_data,
        'diarios_da_obra'                    => $diarios_da_obra,
    );

    wp_send_json_success($response);
}
