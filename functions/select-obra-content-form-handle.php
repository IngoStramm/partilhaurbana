<?php

add_action('admin_post_pu_select_obra_content', 'pu_select_obra_content_handle');
add_action('admin_post_nopriv_pu_select_obra_content', 'pu_select_obra_content_handle');
function pu_select_obra_content_handle()
{
    nocache_headers();
    $redirect_to = get_site_url(null, '/projetos/?status=obra');

    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'pu_select_obra_content_nonce')) {

        $_SESSION['pu_select_obra_content_error_message'] = __('Não foi possível validar a requisição.', 'pu');
        wp_safe_redirect($redirect_to);;
        exit;
    }

    if (!isset($_POST['post_id']) || !$_POST['post_id']) {

        $_SESSION['pu_select_obra_content_error_message'] = __('ID da obra inválido.', 'pu');
        wp_safe_redirect($redirect_to);
        exit;
    }
    $obra_id = sanitize_text_field($_POST['post_id']);
    $redirect_to = get_permalink($obra_id);

    if (!isset($_POST['select-obra-content']) || !$_POST['select-obra-content']) {
        // $_SESSION['pu_select_obra_content_error_message'] = __('Nenhum conteúdo selecionado.', 'pu');
        // Retorna para a tela inicial da obra quando nenhum conteúdo é selecionado    
        wp_safe_redirect($redirect_to);
        exit;
    }
    $selected_content = sanitize_text_field($_POST['select-obra-content']);
    $redirect_to = get_site_url(null, $selected_content . '/?obra_id=' . $obra_id);
    wp_safe_redirect($redirect_to);
    exit;
}
