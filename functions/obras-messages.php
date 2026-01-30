<?php
add_action('pu_select_obra_content_message', 'pu_obra_content_messages');

/**
 * pu_obra_content_messages
 *
 * @return void
 */
function pu_obra_content_messages()
{
    // Mensagens de erro da seleção de conteúdo da Obra 
    if (isset($_SESSION['pu_select_obra_content_error_message']) && $_SESSION['pu_select_obra_content_error_message']) {
        echo pu_dismissible_alert($_SESSION['pu_select_obra_content_error_message'], 'danger');
        unset($_SESSION['pu_select_obra_content_error_message']);
    }
}
