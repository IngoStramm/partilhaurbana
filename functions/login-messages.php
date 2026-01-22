<?php
add_action('pu_login_error_message', 'pu_login_messages');

/**
 * pu_login_messages
 *
 * @return void
 */
function pu_login_messages()
{
    // Mensagens de erro de login 
    if (isset($_SESSION['pu_login_error_message']) && $_SESSION['pu_login_error_message']) {
        echo pu_dismissible_alert($_SESSION['pu_login_error_message'], 'danger');
        unset($_SESSION['pu_login_error_message']);
    }

    // Mensagens de erro de senha perdida 
    if (isset($_SESSION['pu_lostpassword_error_message']) && $_SESSION['pu_lostpassword_error_message']) {
        echo pu_dismissible_alert($_SESSION['pu_lostpassword_error_message'], 'danger');
        unset($_SESSION['pu_lostpassword_error_message']);
    }

    // Mensagens de successo de senha perdida
    if (isset($_SESSION['pu_lostpassword_success_message']) && $_SESSION['pu_lostpassword_success_message']) {
        echo pu_dismissible_alert($_SESSION['pu_lostpassword_success_message'], 'success');
        unset($_SESSION['pu_lostpassword_success_message']);
    }

    // Mensagens de erro de reset password 
    if (isset($_SESSION['pu_resetpassword_error_message']) && $_SESSION['pu_resetpassword_error_message']) {
        echo pu_dismissible_alert($_SESSION['pu_resetpassword_error_message'], 'danger');
        unset($_SESSION['pu_resetpassword_error_message']);
    }    

    // Mensagens de successo de redefinição senha
    if (isset($_SESSION['pu_resetpassword_success_message']) && $_SESSION['pu_resetpassword_success_message']) {
        echo pu_dismissible_alert($_SESSION['pu_resetpassword_success_message'], 'success');
        unset($_SESSION['pu_resetpassword_success_message']);
    }

    // Mensagens de erro de novo usuário
    if (isset($_SESSION['pu_register_new_user_error_message']) && $_SESSION['pu_register_new_user_error_message']) {
        echo pu_dismissible_alert($_SESSION['pu_register_new_user_error_message'], 'danger');
        unset($_SESSION['pu_register_new_user_error_message']);
    }
}
