<?php

add_action('admin_post_pu_lostpassword_form', 'pu_lostpassword_form_handle');
add_action('admin_post_nopriv_pu_lostpassword_form', 'pu_lostpassword_form_handle');

function pu_lostpassword_form_handle()
{
    nocache_headers();

    $login_page_url = pu_get_page_url('login');
    $lostpassword_page_url = pu_get_page_url('lostpassword');
    unset($_SESSION['pu_lostpassword_error_message']);

    if (!isset($_POST['pu_lostpassword_nonce']) || !wp_verify_nonce($_POST['pu_lostpassword_nonce'], 'pu_lostpassword_nonce')) {

        $_SESSION['pu_lostpassword_error_message'] = __('Não foi possível validar a requisição.', 'pu');
        wp_safe_redirect($lostpassword_page_url);
        exit;
    }

    if (!isset($_POST['user_login']) || !$_POST['user_login']) {

        $_SESSION['pu_lostpassword_error_message'] = __('Usuário ou e-mail inválido.', 'pu');
        wp_safe_redirect($lostpassword_page_url);
        exit;
    }

    $user_login = $_POST['user_login'];

    $lostpassword_result = retrieve_password($user_login);
    if (is_wp_error($lostpassword_result)) {
        // Errors found 
        $redirect_url = home_url('member-password-lost');
        $redirect_url = add_query_arg('errors', join(',', $lostpassword_result->get_error_codes()), $redirect_url);

        $error_string = $lostpassword_result->get_error_message() ? $lostpassword_result->get_error_message() : __('Login falhou. Verifique se os dados de login estão corretos e tente novamente.', 'pu');
        $_SESSION['pu_lostpassword_error_message'] = $error_string;
        wp_safe_redirect($lostpassword_page_url);
        exit;
    }

    $_SESSION['pu_lostpassword_success_message'] = __('E-mail de redefinição de palavra-passe enviado. Verifique as instruções no e-mail para redefinr a sua palavra-passe.', 'pu');

    echo '<h3>' . __('E-mail de redefinição de palavra-passe enviado com sucesso! Por favor, aguarde enquanto está sendo redicionando...', 'pu') . '</p>';

    wp_safe_redirect($login_page_url);
    exit;
}
