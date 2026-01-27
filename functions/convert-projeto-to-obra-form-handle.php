<?php

add_action('admin_post_pu_convert_projeto_to_obra', 'pu_convert_projeto_to_obra_handle');
add_action('admin_post_nopriv_pu_convert_projeto_to_obra', 'pu_convert_projeto_to_obra_handle');

function pu_convert_projeto_to_obra_handle()
{
    nocache_headers();

    unset($_SESSION['pu_convert_projeto_to_obra_error_message']);

    if (!isset($_POST['post_id']) || !$_POST['post_id']) {
        echo '<h1>' . __('ID do post ausente. Não foi possível prosseguir com a requisição.', 'pu') . '</h1>';
        exit;
    }
    $post_id = $_POST['post_id'];
    $post_url = get_permalink($post_id);

    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'pu_convert_projeto_to_obra_nonce')) {

        $_SESSION['pu_convert_projeto_to_obra_error_message'] = __('Não foi possível validar a requisição.', 'pu');
        wp_safe_redirect($post_url);
        exit;
    }

    $update_status_do_projeto = wp_set_post_terms($post_id, array('obra'), 'status-do-projeto');
    if (is_wp_error($update_status_do_projeto) || !$update_status_do_projeto) {
        $_SESSION['pu_convert_projeto_to_obra_error_message'] = __('Ocorreu um erro ao salvar o status do projeto.', 'pu');
        wp_safe_redirect($post_url);
        exit;
    }

    $_SESSION['pu_convert_projeto_to_obra_success_message'] = __('Projeto convertido em obra com sucesso!', 'pu');

    echo '<h3>' . __('E-mail de redefinição de palavra-passe enviado com sucesso! Por favor, aguarde enquanto está sendo redicionando...', 'pu') . '</p>';

    wp_safe_redirect($post_url);
    exit;
}
