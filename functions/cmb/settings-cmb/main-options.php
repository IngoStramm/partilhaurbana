<?php

add_action('cmb2_admin_init', 'pu_register_main_options_metabox');

function pu_register_main_options_metabox()
{

    $cmb_options = new_cmb2_box(array(
        'id'           => 'pu_theme_options_page',
        'title'        => esc_html__('Configurações Partilha Urbana', 'pu'),
        'object_types' => array('options-page'),

        'option_key'      => 'pu_theme_options',
        'icon_url'        => 'dashicons-admin-generic',
    ));

    $cmb_options->add_field(array(
        'name'    => esc_html__('E-mails que receberão as mensagens do formulário de contato.', 'pu'),
        'id'      => 'pu_contact_form_emails',
        'type'    => 'text_email',
        'repeatable'    => true,
        'required'      => true
    ));

    $cmb_options->add_field(array(
        'name'    => esc_html__('E-mails que receberão as inscrições de newsletter.', 'pu'),
        'id'      => 'pu_newsletter_form_emails',
        'type'    => 'text_email',
        'repeatable'    => true,
        'required'      => true
    ));

    $cmb_options->add_field(array(
        'name' => esc_html__('Imagem padrão dos projetos/obras', 'pu'),
        'desc' => esc_html__('A imagem padrão será exibido quando uma imagem não for definida/econtrada para os projetos/obras.', 'pu'),
        'id'   => 'pu_default_image',
        'type' => 'file',
        'attributes' => array(
            'accept' => '.jpg,.jpeg,.png'
        )
    ));
}
