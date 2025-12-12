<?php


add_action('cmb2_admin_init', 'pu_register_site_pages_options_metabox');

function pu_register_site_pages_options_metabox()
{
    $cmb_options = new_cmb2_box(array(
        'id'                    => 'pu_site_pages_options_page',
        'title'                 => esc_html__('Páginas do site', 'mi'),
        'object_types'          => array('options-page'),
        'option_key'            => 'pu_site_pages_options',
        'icon_url'              => 'dashicons-admin-generic',
        'menu_title'            => esc_html__('Páginas do site', 'mi'),
        'parent_slug'           => 'pu_theme_options',
    ));

    // $cmb_options->add_field(array(
    //     'name'    => esc_html__('Página de login', 'mi'),
    //     'id'      => 'pu_login_page',
    //     'type'    => 'select',
    //     'options' => function () {
    //         $pages = pu_get_pages();
    //         $array = [];
    //         $array[''] = __('Selecione uma página', 'mi');
    //         foreach ($pages as $id => $title) {
    //             $array[$id] = $title;
    //         }
    //         return $array;
    //     },
    //     'required'      => true
    // ));

    // $cmb_options->add_field(array(
    //     'name'    => esc_html__('Página de cadastro de novo usuário', 'mi'),
    //     'id'      => 'pu_new_user_page',
    //     'type'    => 'select',
    //     'options' => function () {
    //         $pages = pu_get_pages();
    //         $array = [];
    //         $array[''] = __('Selecione uma página', 'mi');
    //         foreach ($pages as $id => $title) {
    //             $array[$id] = $title;
    //         }
    //         return $array;
    //     },
    //     'required'      => true
    // ));

    // $cmb_options->add_field(array(
    //     'name'    => esc_html__('Página de palavra-passe perdida', 'mi'),
    //     'id'      => 'pu_lostpassword_page',
    //     'type'    => 'select',
    //     'options' => function () {
    //         $pages = pu_get_pages();
    //         $array = [];
    //         $array[''] = __('Selecione uma página', 'mi');
    //         foreach ($pages as $id => $title) {
    //             $array[$id] = $title;
    //         }
    //         return $array;
    //     },
    //     'required'      => true
    // ));

    // $cmb_options->add_field(array(
    //     'name'    => esc_html__('Página de redefinição de Palavra-passe', 'mi'),
    //     'id'      => 'pu_resetpassword_page',
    //     'type'    => 'select',
    //     'options' => function () {
    //         $pages = pu_get_pages();
    //         $array = [];
    //         $array[''] = __('Selecione uma página', 'mi');
    //         foreach ($pages as $id => $title) {
    //             $array[$id] = $title;
    //         }
    //         return $array;
    //     },
    //     'required'      => true
    // ));

    // $cmb_options->add_field(array(
    //     'name'    => esc_html__('Página Termos de Serviços', 'mi'),
    //     'id'      => 'pu_service_terms',
    //     'type'    => 'select',
    //     'options' => function () {
    //         $pages = pu_get_pages();
    //         $array = [];
    //         $array[''] = __('Selecione uma página', 'mi');
    //         foreach ($pages as $id => $title) {
    //             $array[$id] = $title;
    //         }
    //         return $array;
    //     },
    //     'required'      => true
    // ));

    // $cmb_options->add_field(array(
    //     'name'    => esc_html__('Página Política de Cookies', 'mi'),
    //     'id'      => 'pu_cookies_policy',
    //     'type'    => 'select',
    //     'options' => function () {
    //         $pages = pu_get_pages();
    //         $array = [];
    //         $array[''] = __('Selecione uma página', 'mi');
    //         foreach ($pages as $id => $title) {
    //             $array[$id] = $title;
    //         }
    //         return $array;
    //     },
    //     'required'      => true
    // ));

    // $cmb_options->add_field(array(
    //     'name'    => esc_html__('Página de Contato', 'mi'),
    //     'id'      => 'pu_contact',
    //     'type'    => 'select',
    //     'options' => function () {
    //         $pages = pu_get_pages();
    //         $array = [];
    //         $array[''] = __('Selecione uma página', 'mi');
    //         foreach ($pages as $id => $title) {
    //             $array[$id] = $title;
    //         }
    //         return $array;
    //     },
    //     'required'      => true
    // ));

    $cmb_options->add_field(array(
        'name'    => esc_html__('Página de cadastro de novo projeto', 'mi'),
        'id'      => 'pu_new_projeto',
        'type'    => 'select',
        'options' => function () {
            $pages = pu_get_pages();
            $array = [];
            $array[''] = __('Selecione uma página', 'mi');
            foreach ($pages as $id => $title) {
                $array[$id] = $title;
            }
            return $array;
        },
        'required'      => true
    ));
}
