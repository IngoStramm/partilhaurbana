<?php
add_action('cmb2_admin_init', 'pu_register_documento_metabox');

function pu_register_documento_metabox()
{
    $cmb = new_cmb2_box(array(
        'id'           => 'pu_documento_metabox',
        'title'        => esc_html__('Opções', 'pu'),
        'object_types' => array('documento'),
    ));


    $cmb->add_field(array(
        'name'       => esc_html__('Tipo', 'pu'),
        'id'         => 'documento_type',
        'type'       => 'select',
        'show_option_none' => true,
        'options_cb'       => 'pu_documento_type'
    ));

    $cmb->add_field(array(
        'name' => esc_html__('Arquivo', 'pu'),
        'id'   => 'file',
        'type' => 'file',
        'preview_size' => array(100, 100), // Default: array( 50, 50 )
    ));

    $cmb->add_field(array(
        'name'                      => esc_html__('ID do projeto', 'pu'),
        'id'                        => 'projeto_id',
        'type'                      => 'text_small',
        'attributes'        => array(
            'type'  => 'number',
            'min'   => 0
        )
    ));
}
