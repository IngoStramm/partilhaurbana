<?php

add_action('login_form_rp', 'pu_redirect_to_custom_resetpassword');
add_action('login_form_resetpass', 'pu_redirect_to_custom_resetpassword');

function pu_redirect_to_custom_resetpassword()
{
    $login_page_url = pu_get_page_url('login');
    unset($_SESSION['pu_resetpassword_error_message']);

    if (!isset($_REQUEST['key']) || !$_REQUEST['key']) {

        $_SESSION['pu_resetpassword_error_message'] = __('Não foi possível validar a requisição.', 'mi');
        wp_safe_redirect($login_page_url);
        exit;
    }

    if (!isset($_REQUEST['login']) || !$_REQUEST['login']) {

        $_SESSION['pu_resetpassword_error_message'] = __('Utilizador inválido.', 'mi');
        wp_safe_redirect($login_page_url);
        exit;
    }

    $user = check_password_reset_key($_REQUEST['key'], $_REQUEST['login']);
    if (!$user || is_wp_error($user)) {

        if ($user && $user->get_error_code() === 'expired_key') {

            $error_string = $user->get_error_message() ? $user->get_error_message() : __('O link de redefinição de palavra-chave expirou.', 'mi');
            $_SESSION['pu_resetpassword_error_message'] = $error_string;
            wp_safe_redirect($login_page_url);
        } else {

            $error_string = $user->get_error_message() ? $user->get_error_message() : __('Url inválida.', 'mi');
            $_SESSION['pu_resetpassword_error_message'] = $error_string;
            wp_safe_redirect($login_page_url);
        }
        exit;
    }

    $redirect_url = pu_get_page_url('resetpassword');
    $redirect_url = add_query_arg('login', esc_attr($_REQUEST['login']), $redirect_url);
    $redirect_url = add_query_arg('key', esc_attr($_REQUEST['key']), $redirect_url);
    wp_safe_redirect($redirect_url);
    exit;
}

add_action('admin_post_pu_resetpassword_form', 'pu_resetpassword_form_handle');
add_action('admin_post_nopriv_pu_resetpassword_form', 'pu_resetpassword_form_handle');

function pu_resetpassword_form_handle()
{
    nocache_headers();

    $login_page_url = pu_get_page_url('login');
    $resetpassword_page_url = pu_get_page_url('resetpassword');
    unset($_SESSION['pu_resetpassword_error_message']);

    if (!isset($_POST['pu_resetpassword_nonce']) || !wp_verify_nonce($_POST['pu_resetpassword_nonce'], 'pu_resetpassword_nonce')) {

        $_SESSION['pu_resetpassword_error_message'] = __('Não foi possível validar a requisição.', 'mi');
        wp_safe_redirect($resetpassword_page_url);
        exit;
    }


    if (!isset($_POST['key']) || !$_POST['key']) {

        $_SESSION['pu_resetpassword_error_message'] = __('Chave de redefinição de palavra-chave inválida.', 'mi');
        wp_safe_redirect($resetpassword_page_url);
        exit;
    }

    if (!isset($_POST['user_pass']) || !$_POST['user_pass']) {

        $_SESSION['pu_resetpassword_error_message'] = __('Palavra-chave inválida.', 'mi');
        wp_safe_redirect($resetpassword_page_url);
        exit;
    }

    if (!isset($_POST['user_login']) || !$_POST['user_login']) {

        $_SESSION['pu_resetpassword_error_message'] = __('Utilizador inválido.', 'mi');
        wp_safe_redirect($resetpassword_page_url);
        exit;
    }

    if (!isset($_POST['action']) || $_POST['action'] !== 'pu_resetpassword_form') {

        $_SESSION['pu_resetpassword_error_message'] = __('Formulário inválido.', 'mi');
        wp_safe_redirect($resetpassword_page_url);
        exit;
    }

    $user_login = $_POST['user_login'];
    $user_pass = $_POST['user_pass'];
    $rp_key = $_POST['key'];

    $user = check_password_reset_key($rp_key, $user_login);

    if (!$user || is_wp_error($user)) {
        if ($user && $user->get_error_code() === 'expired_key') {
            $error_string = $user->get_error_message() ? $user->get_error_message() : __('A chave de redefinição de palavra-chave expirou. Solicite um novo link de redefinição de palavra-chave clicando na opção "Esqueceu a palavra-chave?" na tela de inicia sessão.', 'mi');
        } else {
            $error_string = $user->get_error_message() ? $user->get_error_message() : __('A chave de redefinição de palavra-chave é inválida. Solicite um novo link de redefinição de palavra-chave clicando na opção "Esqueceu a palavra-chave?" na tela de inicia sessão.', 'mi');
        }
        $_SESSION['pu_resetpassword_error_message'] = $error_string;
        wp_safe_redirect($login_page_url);
        exit;
    }

    reset_password($user, $user_pass);

    $_SESSION['pu_resetpassword_success_message'] = __('Palavra-passe alterada com sucesso.', 'mi');

    echo '<h3>' . __('Palavra-passe alterada com sucesso! Por favor, aguarde enquanto está sendo redicionando...', 'mi') . '</p>';

    wp_safe_redirect($login_page_url);
    exit;
}
