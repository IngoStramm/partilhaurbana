<?php
add_action('cmb2_admin_init', 'pu_register_diario_da_obra_metabox');

function pu_register_diario_da_obra_metabox()
{
    $cmb = new_cmb2_box(array(
        'id'           => 'pu_diario_da_obra_metabox',
        'title'        => esc_html__('Opções', 'pu'),
        'object_types' => array('diario-da-obra'),
    ));

    $cmb->add_field(array(
        'name'                      => esc_html__('Data Lançamento', 'pu'),
        'id'                        => 'data',
        'type'                  => 'text_date',
        'date_format' => 'd-m-Y',
    ));

    $cmb->add_field(array(
        'name' => esc_html__('Descrição', 'pu'),
        'id'   => 'description',
        'type' => 'textarea',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Semana', 'pu'),
        'id'         => 'semana',
        'type'       => 'select',
        'show_option_none' => true,
        'options_cb'       => function ($cmb) {
            $options = [];
            for ($i = 1; $i <= 52; $i++) {
                $options[$i] = $i;
            }
            return $options;
        }
    ));


    $cmb->add_field(array(
        'name'       => esc_html__('Fase (estágio)', 'pu'),
        'id'         => 'estagio_diario',
        'type'       => 'select',
        'show_option_none' => true,
        'options_cb'       => function ($cmb) {
            $lancamento_id = $cmb->object_id;
            $projeto_id = get_post_meta($lancamento_id, 'projeto_id', true);
            $estagios = get_post_meta($projeto_id, 'estagios_settings', true);
            $options = [];
            if ($projeto_id) {
                foreach ($estagios as $estagio) {
                    $options[] = $estagio['title'];
                }
            }
            return $options;
        }
    ));

    $cmb->add_field(array(
        'name' => esc_html__('Fotos', 'pu'),
        'id'   => 'photos',
        'type' => 'file_list',
        'preview_size' => array(100, 100), // Default: array( 50, 50 )
    ));

    $cmb->add_field(array(
        'name' => esc_html__('Vídeos', 'pu'),
        'id'   => 'videos',
        'type' => 'file_list',
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
