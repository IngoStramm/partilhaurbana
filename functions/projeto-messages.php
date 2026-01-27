<?php
add_action('projeto_messages', 'pu_projeto_messages');

/**
 * pu_projeto_messages
 *
 * @return void
 */
function pu_projeto_messages()
{
    // Mensagens de erro de conversão do projeto em obra 
    if (isset($_SESSION['pu_convert_projeto_to_obra_error_message']) && $_SESSION['pu_convert_projeto_to_obra_error_message']) {
        echo pu_dismissible_alert($_SESSION['pu_convert_projeto_to_obra_error_message'], 'danger');
        unset($_SESSION['pu_convert_projeto_to_obra_error_message']);
    }
    // Mensagens de sucesso de conversão do projeto em obra 
    if (isset($_SESSION['pu_convert_projeto_to_obra_success_message']) && $_SESSION['pu_convert_projeto_to_obra_success_message']) {
        echo pu_dismissible_alert($_SESSION['pu_convert_projeto_to_obra_success_message'], 'success');
        unset($_SESSION['pu_convert_projeto_to_obra_success_message']);
    }
}
