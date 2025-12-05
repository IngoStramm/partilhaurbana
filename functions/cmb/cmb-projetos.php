<?php
add_action('cmb2_admin_init', 'pu_register_seetings_projetos_metabox');


function pu_register_seetings_projetos_metabox()
{
    $cmb = new_cmb2_box(array(
        'id'           => 'pu_settings_projetos_metabox',
        'title'        => esc_html__('Administração do Projeto', 'pu'),
        'object_types' => array('projetos'),
    ));

    $cmb->add_field(array(
        'name'                      => esc_html__('Status do projeto?', 'pu'),
        'id'                        => 'status_do_projeto',
        'type'                      => 'taxonomy_radio',
        'taxonomy'                  => 'status-do-projeto',
        'show_option_none'          => false,
    ));
    $cmb->add_field(array(
        'name'                      => esc_html__('Preço de compra (ou preço do imóvel)', 'pu'),
        'id'                        => 'preco',
        'description'               => __('Apenas números', 'pu'),
        'type'                      => 'text_small',
        'attributes'        => array(
            'type'  => 'number',
            'min'   => 0
        )
    ));

    $cmb->add_field(array(
        'name'                      => esc_html__('Quem é o dono deste imóvel?', 'pu'),
        'id'                        => 'dono_do_projeto',
        'type'                      => 'taxonomy_radio',
        'taxonomy'                  => 'dono-do-projeto',
        'show_option_none'          => false,
    ));

    $group_field_id_settings = $cmb->add_field(array(
        'id'          => 'estagios_settings',
        'type'        => 'group',
        'options'     => array(
            'group_title'    => esc_html__('Estágio {#}', 'pu'), // {#} gets replaced by row number
            'add_button'     => esc_html__('Adicionar novo estágio', 'pu'),
            'remove_button'  => esc_html__('Remover estágio', 'pu'),
            'sortable'       => true,
        ),
    ));

    $cmb->add_group_field($group_field_id_settings, array(
        'name'       => esc_html__('Título', 'pu'),
        'id'         => 'title',
        'type'       => 'text',
    ));

    $cmb->add_group_field($group_field_id_settings, array(
        'name'       => esc_html__('Esforço', 'pu'),
        'after_field'               => ' %',
        'id'         => 'effort',
        'type'       => 'text_small',
        'attributes'        => array(
            'type'  => 'number',
            'min'   => 0
        ),
        'default'               => 0,
    ));

    $cmb->add_group_field($group_field_id_settings, array(
        'name'       => esc_html__('Custo', 'pu'),
        'after_field'               => ' €',
        'id'         => 'cost',
        'type'       => 'text_small',
        'attributes'        => array(
            'type'  => 'number',
            'min'   => 0
        ),
        'default'               => 0,
    ));
}

add_action('cmb2_admin_init', 'pu_register_projecao_lucratividade_projetos_metabox');


function pu_register_projecao_lucratividade_projetos_metabox()
{


    $cmb = new_cmb2_box(array(
        'id'           => 'pu_projecao_lucratividade_projetos_metabox',
        'title'        => esc_html__('Projeção de lucratividade', 'pu'),
        'object_types' => array('projetos'),
    ));

    $items = array(
        'preco_venda'                   => __('Por quanto pretende vender este imóvel:', 'pu'),
        'comissao_impotos'              => __('Comissão da imobiliária e impostos:', 'pu'),
        'certificado_documentacao'      => __('Certificados e documentação:', 'pu'),
        'imposto_lucro'                 => __('Imposto sobre o lucro (mais-valia):', 'pu'),
        'escrituras_registros'          => __('Escrituras e registros:', 'pu'),
        'outros_a'                      => __('Outros A:', 'pu'),
        'outros_b'                      => __('Outros B:', 'pu'),
    );

    $cmb = pu_projecao_lucratividade_fields($cmb, $items);
};

add_action('cmb2_admin_init', 'pu_register_observacoes_projetos_metabox');


function pu_register_observacoes_projetos_metabox()
{


    $cmb = new_cmb2_box(array(
        'id'           => 'pu_extra_projetos_metabox',
        'title'        => esc_html__('Extra', 'pu'),
        'object_types' => array('projetos'),
    ));

    $cmb->add_field(array(
        'name'                      => __('Observações', 'pu'),
        'id'                        => 'observacoes',
        'type'                      => 'textarea',
        'attributes'        => array(
            'rows'      => 3
        )
    ));
};

function pu_projecao_lucratividade_fields($cmb, $items)
{
    $i = 0;
    foreach ($items as $slug => $item) {
        $cmb->add_field(array(
            'name'                      => $item,
            'id'                        => $slug . '_title',
            'type'                      => 'title',
        ));

        $cmb->add_field(array(
            'name'                      => __('Valor', 'pu'),
            'id'                        => $slug . '_valor',
            'type'                      => 'text_small',
            'attributes'        => array(
                'type'  => 'number',
                'min'   => 0
            ),
            'default'               => 0,
        ));

        $cmb->add_field(array(
            'name'                      => __('Tipo', 'pu'),
            'id'                        => $slug . '_tipo',
            'type'                      => 'select',
            'options'       => array(
                'pct'       => __('%', 'pu'),
                'fixed'     => __('€', 'pu')
            )
        ));
        $i++;
    }
    return $cmb;
}
