<?php
add_action('cmb2_admin_init', 'pu_register_financeiro_metabox');

function pu_register_financeiro_metabox()
{
    $cmb = new_cmb2_box(array(
        'id'           => 'pu_financeiro_metabox',
        'title'        => esc_html__('Opções', 'pu'),
        'object_types' => array('financeiro'),
    ));

    $cmb->add_field(array(
        'name'                      => esc_html__('Código da Fatura', 'pu'),
        'id'                        => 'codigo_fatura',
        'type'                  => 'text_small',
    ));

    $cmb->add_field(array(
        'name'                      => esc_html__('SKU', 'pu'),
        'id'                        => 'sku',
        'type'                  => 'text_small',
    ));

    $cmb->add_field(array(
        'name'                      => esc_html__('Data Lançamento', 'pu'),
        'id'                        => 'data',
        'type'                  => 'text_date',
        'date_format' => 'd-m-Y',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Tipo de lançamento', 'pu'),
        'id'         => 'tipo',
        'type'       => 'select',
        'options'       => array(
            'entrada'       => __('Entrada', 'pu'),
            'saida'       => __('Saída', 'pu'),
        )
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

    $cmb->add_field(array(
        'name'       => esc_html__('Fase (estágio)', 'pu'),
        'id'         => 'estagio_lancamento',
        'type'       => 'select',
        'show_option_none' => true,
        'options_cb'       => function ($cmb) {
            $lancamento_id = $cmb->object_id;
            $projeto_id = get_post_meta($lancamento_id, 'projeto_id', true);
            $estagios = get_post_meta($projeto_id, 'estagios_settings', true);
            $options = [];
            foreach ($estagios as $estagio) {
                $options[] = $estagio['title'];
            }
            return $options;
        }
    ));

    $cmb->add_field(array(
        'name'                      => esc_html__('Valor Unitário', 'pu'),
        'id'                        => 'valor_unitario',
        'description'               => __('Apenas números', 'pu'),
        'type'                      => 'text_small',
        'attributes'        => array(
            'type'  => 'number',
            'min'   => 0
        )
    ));

    $cmb->add_field(array(
        'name'                      => esc_html__('Quantidade', 'pu'),
        'id'                        => 'quantidade',
        'type'                      => 'text_small',
        'default'                   => 1,
        'attributes'        => array(
            'type'  => 'number',
            'min'   => 0
        )
    ));

    $cmb->add_field(array(
        'name'                      => esc_html__('Valor Total', 'pu'),
        'id'                        => 'valor',
        'description'               => __('Apenas números', 'pu'),
        'type'                      => 'text_small',
        'attributes'        => array(
            'type'  => 'number',
            'min'   => 0
        )
    ));

    $cmb->add_field(array(
        'name' => esc_html__('Comprovante', 'pu'),
        'id'   => 'comprovante',
        'type' => 'file',
    ));

    // Fornecedor: select
    // Código da Fatura: text
    // SKU: text
    // Quantidade: int
    // Valor Unitário: int


    //     $cmb->add_field(array(
    //         'name'       => esc_html__('Fase da saída', 'pu'),
    //         'id'         => 'estagio',
    //         'type'       => 'select',
    //         'options_cb'       => 'pu_return_estagios_options'
    //     ));
}
