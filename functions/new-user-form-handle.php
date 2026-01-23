<?php

add_action('admin_post_pu_newuser_form', 'pu_register_new_user_form_handle');
add_action('admin_post_nopriv_pu_newuser_form', 'pu_newuser_form_handle');

/**
 * pu_register_new_user_handle
 *
 * @return void
 */
function pu_newuser_form_handle()
{
    nocache_headers();
    $login_page_url = pu_get_page_url('login');
    $register_new_user_page_id = pu_get_option('pu_new_user_page', false, 'pu_site_pages_options');
    $register_new_user_page_url = pu_get_page_url('newuser');
    unset($_SESSION['pu_register_new_user_error_message']);

    if (!isset($_POST['pu_newuser_nonce']) || !wp_verify_nonce($_POST['pu_newuser_nonce'], 'pu_newuser_nonce')) {

        $_SESSION['pu_register_new_user_error_message'] = __('Não foi possível validar a requisição.', 'pu');
        wp_safe_redirect($register_new_user_page_url);
        exit;
    }

    if (!isset($_POST['user_name']) || !$_POST['user_name']) {

        $_SESSION['pu_register_new_user_error_message'] = __('Nome inválido.', 'pu');
        wp_safe_redirect($register_new_user_page_url);
        exit;
    }
    $user_name = sanitize_text_field($_POST['user_name']);
    $user_login = pu_generate_unique_username($user_name);

    if (!isset($_POST['user_surname']) || !$_POST['user_surname']) {

        $_SESSION['pu_register_new_user_error_message'] = __('Sobrenome inválido.', 'pu');
        wp_safe_redirect($register_new_user_page_url);
        exit;
    }
    $user_surname = sanitize_text_field($_POST['user_surname']);

    if (!isset($_POST['user_email']) || !$_POST['user_email']) {

        $_SESSION['pu_register_new_user_error_message'] = __('E-mail inválido.', 'pu');
        wp_safe_redirect($register_new_user_page_url);
        exit;
    }
    $user_email = sanitize_email($_POST['user_email']);

    if (!isset($_POST['user_pass']) || !$_POST['user_pass']) {

        $_SESSION['pu_register_new_user_error_message'] = __('Palavra-passe inválida.', 'pu');
        wp_safe_redirect($register_new_user_page_url);
        exit;
    }
    $user_password = $_POST['user_pass'];

    if (!isset($_POST['empresa']) || !$_POST['empresa']) {

        $_SESSION['pu_register_new_user_error_message'] = __('Empresa inválida.', 'pu');
        wp_safe_redirect($register_new_user_page_url);
        exit;
    }
    $empresa = $_POST['empresa'];

    // if (!isset($_POST['user_avatar']) || !$_POST['user_avatar']) {

    //     $_SESSION['pu_register_new_user_error_message'] = __('Avatar inválido.', 'pu');
    //     wp_safe_redirect($register_new_user_page_url);
    //     exit;
    // }
    // $user_avatar = $_POST['user_avatar'];

    $userdata = array(
        'user_pass'                => $user_password,     //(string) The plain-text user password.
        'user_login'             => $user_login,     //(string) The user's login username.
        'user_nicename'         => $user_name,     //(string) The URL-friendly user name.
        'user_url'                 => '',     //(string) The user URL.
        'user_email'             => $user_email,     //(string) The user email address.
        'display_name'             => $user_name,     //(string) The user's display name. Default is the user's username.
        'nickname'                 => $user_name,     //(string) The user's nickname. Default is the user's username.
        'first_name'             => $user_name,     //(string) The user's first name. For new users, will be used to build the first part of the user's display name if $display_name is not specified.
        'last_name'             => $user_surname,     //(string) The user's last name. For new users, will be used to build the second part of the user's display name if $display_name is not specified.
        // 'description'             => '',     //(string) The user's biographical description.
        // 'rich_editing'             => '',     //(string|bool) Whether to enable the rich-editor for the user. False if not empty.
        // 'syntax_highlighting'     => '',     //(string|bool) Whether to enable the rich code editor for the user. False if not empty.
        // 'comment_shortcuts'     => '',     //(string|bool) Whether to enable comment moderation keyboard shortcuts for the user. Default false.
        // 'admin_color'             => '',     //(string) Admin color scheme for the user. Default 'fresh'.
        'use_ssl'                 => 'true',     //(bool) Whether the user should always access the admin over https. Default false.
        // 'user_registered'         => '',     //(string) Date the user registered. Format is 'Y-m-d H:i:s'.
        'show_admin_bar_front'     => 'false',     //(string|bool) Whether to display the Admin Bar for the user on the site's front end. Default true.
        'role'                     => 'subscriber',     //(string) User's role.
        // 'locale'                 => '',     //(string) User's locale. Default empty.
        'meta_input'            => array(
            'empresa'      => $empresa,
        )

    );
    $register_new_user_result = wp_insert_user($userdata);

    if (is_wp_error($register_new_user_result)) {
        $error_string = $register_new_user_result->get_error_message() ? $register_new_user_result->get_error_message() : __('Ocorreu um erro ao tentar cadastrar o usuário. Revise os dados inseridos e tente novamente.', 'pu');
        $_SESSION['pu_register_new_user_error_message'] = $error_string;
        wp_safe_redirect($register_new_user_page_url);
        exit;
    }

    $user = get_user_by('id', $register_new_user_result);

    $_SESSION['pu_register_new_user_success_message'] = wp_sprintf(__('Seja bem vindo(a), %s!', 'pu'), $user->display_name);

    echo '<h3>' . __('Novo usuário cadastrado com sucesso! Por favor, aguarde enquanto está sendo redicionando...', 'pu') . '</p>';

    $login_result = wp_signon(array(
        'user_login'        => $user_login,
        'user_password'     => $user_password,
    ));

    if (is_wp_error($login_result)) {
        $error_string = $login_result->get_error_message() ? $login_result->get_error_message() : __('Login falhou. Verifique se os dados de login estão corretos e tente novamente.', 'pu');
        $_SESSION['pu_login_error_message'] = $error_string;
        wp_safe_redirect($login_page_url);
        exit;
    }

    wp_safe_redirect(get_home_url());
    exit;
}
