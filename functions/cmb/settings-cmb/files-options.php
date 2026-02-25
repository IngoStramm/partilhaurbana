<?php


add_action('cmb2_admin_init', 'pu_register_site_files_options_metabox');

function pu_register_site_files_options_metabox()
{
    $cmb_options = new_cmb2_box(array(
        'id'                    => 'pu_site_files_options_page',
        'title'                 => esc_html__('Configurações de upload de arquivos', 'pu'),
        'object_types'          => array('options-page'),
        'option_key'            => 'pu_site_files_options',
        'icon_url'              => 'dashicons-admin-generic',
        'menu_title'            => esc_html__('Configurações de upload de arquivos', 'pu'),
        'parent_slug'           => 'pu_theme_options',
    ));

    $cmb_options->add_field(array(
        'name'    => esc_html__('Tamanho máximo de upload para cada arquivo de imagem.', 'pu'),
        'id'      => 'pu_single_image_max_upload',
        'type'    => 'select',
        'options_cb' => function () {
            $options = [];
            $options[''] = __('Selecione uma opção', 'pu');
            for ($i = 1; $i <= 10; $i++) {
                $bytes = $i * 1024 * 1024;
                $options[$bytes] = $i . ' MB';
            }
            return $options;
        },
        'required'      => true
    ));

    $cmb_options->add_field(array(
        'name'    => esc_html__('Tamanho máximo de upload para cada arquivo de vídeo.', 'pu'),
        'id'      => 'pu_single_video_max_upload',
        'type'    => 'select',
        'options_cb' => function () {
            $options = [];
            $options[''] = __('Selecione uma opção', 'pu');
            for ($i = 1; $i <= 20; $i++) {
                $bytes = $i * 1024 * 1024;
                $options[$bytes] = $i . ' MB';
            }
            return $options;
        },
        'required'      => true
    ));

    $cmb_options->add_field(array(
        'name'    => esc_html__('Tamanho máximo de upload de arquivos ao mesmo tempo.', 'pu'),
        'id'      => 'pu_files_max_upload',
        'type'    => 'select',
        'options_cb' => function () {
            $options = [];
            $options[''] = __('Selecione uma opção', 'pu');
            for ($i = 1; $i <= 50; $i++) {
                $bytes = $i * 1024 * 1024;
                $options[$bytes] = $i . ' MB';
            }
            return $options;
        },
        'required'      => true
    ));
}
