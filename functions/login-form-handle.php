<?php

add_action('admin_post_pu_login_form', 'pu_login_form_handle');
add_action('admin_post_nopriv_pu_login_form', 'pu_login_form_handle');

/**
 * pu_login_form_handle
 *
 * @return void
 */
function pu_login_form_handle()
{
    nocache_headers();
    $login_page_url = pu_get_page_url('login');
    $redirect_to = get_home_url();

    if (!isset($_POST['pu_loginform_nonce']) || !wp_verify_nonce($_POST['pu_loginform_nonce'], 'pu_loginform_nonce')) {
        $_SESSION['pu_login_error_message'] = __('Não foi possível validar a requisição.', 'mi');
        wp_safe_redirect($login_page_url);
        exit;
    }

    if (isset($_POST['redirect_to']) && $_POST['redirect_to']) {
        $redirect_to = $_POST['redirect_to'];
    }

    $login_result = wp_signon();

    if (is_wp_error($login_result)) {

        $error_string = $login_result->get_error_message() ? $login_result->get_error_message() : __('Login falhou. Verifique se os dados de login estão corretos e tente novamente.', 'mi');
        $_SESSION['pu_login_error_message'] = $error_string;
        wp_safe_redirect($login_page_url);
        exit;
    }
    $user = $login_result;
    $_SESSION['pu_login_success_message'] = sprintf(__('Olá, %s! Bem vindo de volta!', 'mi'), $user->first_name);
    wp_safe_redirect($redirect_to);
    exit;
}
