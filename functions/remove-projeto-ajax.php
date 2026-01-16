<?php

add_action('wp_ajax_nopriv_remove_projeto', 'pu_remove_projeto');
add_action('wp_ajax_pu_remove_projeto', 'pu_remove_projeto');

function pu_remove_projeto()
{
    // Verifica nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'pu_remove_projeto_nonce')) {
        wp_send_json_error(array('msg' => __('Não foi possível validar a requisição.', 'pu')), 200);
    }

    // Verifica se o usuário existe
    $user_id = pu_form_get_field('user-id', __('ID do usuário ausente', 'pu'), 'absint');
    $check_user_exists = get_user_by('id', $user_id);
    if (!$check_user_exists) {
        wp_send_json_error(array('msg' => __('Usuário inválido.', 'pu')), 200);
    }

    $post_id = pu_form_get_field('post-id', __('ID do projeto ausente', 'pu'));

    // Verifica se o projeto existe
    $post_data = get_post($post_id);

    if (!$post_data) {
        wp_send_json_error(array('msg' => __('Não foi possível encontrar o projeto.', 'pu')), 200);
    }

    // Verifica se o usuário pode editar o post
    $check_user_permition = pu_user_can_access();

    if (!$check_user_permition) {
        wp_send_json_error(array('msg' => __('Você não possui permissão para editar este projeto.', 'pu')), 200);
    }
    // Passou por todas as verificações

    // Apagar imagem destacada
    $deleted_featured_image = true;

    $thumbnail_id = get_post_meta($post_id, '_thumbnail_id', true);

    // verifica se o post possui uma imagem
    if ($thumbnail_id) {
        $deleted_featured_image = delete_post_meta($post_id, '_thumbnail_id');
    }

    // Verifica se ocorreu um erro ao tentar remover o meta do post
    if (!$deleted_featured_image) {
        wp_send_json_error(array('msg' => __('Ocorreu um erro ao tentar remover a imagem principal do projeto.', 'pu')), 200);
    }

    // Se o post possuir uma imagem, tenta apagar o arquivo da biblioteca de mídia
    if ($thumbnail_id) {
        $delete_thumbnail_attachment = wp_delete_attachment($thumbnail_id);
        if (!$delete_thumbnail_attachment) {
            wp_send_json_error(array('msg' =>  __('Ocorreu um erro ao tentar remover o arquivo do servidor, porém mesmo assim a imagem foi retirada do projeto.', 'pu')), 200);
        }
    }

    $deleted_post = wp_delete_post($post_id, true);
    if (!$deleted_post) {
        wp_send_json_error(array('msg' =>  __('Ocorreu um erro ao tentar remover o projeto.', 'pu')), 200);
    }

    $redirect_to = get_site_url(null, '/projetos/');

    $post = $_POST;
    $response = array(
        'msg'                   => __('Projeto apagado com sucesso!', 'pu'),
        'redirect_to'           => $redirect_to,
        'post'                  => $post,
        'post_data'             => $post_data
    );

    wp_send_json_success($response);
    exit;
}
