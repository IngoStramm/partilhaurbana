<?php

add_action('init', 'pu_documento_post_type', 1);

function pu_documento_post_type()
{
    $post_types = pu_obra_posts_types();
    $documento = new PU_Post_Type(
        $post_types['documento'], // Nome (Singular) do Post Type.
        'documento' // Slug do Post Type.;
    );

    $documento->set_labels(
        array(
            'name'               => __('Documento', 'mi'),
            'singular_name'      => __('Documento', 'mi'),
            'menu_name'          => __('Documento', 'mi'),
            'name_admin_bar'     => __('Documento', 'mi'),
            'add_new'            => __('Adicionar Novo Documento', 'mi'),
            'add_new_item'       => __('Adicionar Novo Documento', 'mi'),
            'new_item'           => __('Novo Documento', 'mi'),
            'edit_item'          => __('Editar Documento', 'mi'),
            'view_item'          => __('Visualizar Documento', 'mi'),
            'all_items'          => __('Todos os Documentos', 'mi'),
            'search_items'       => __('Pesquisar Documentos', 'mi'),
            'parent_item_colon'  => __('Documentos Pai', 'mi'),
            'not_found'          => __('Nenhum Documento encontrado', 'mi'),
            'not_found_in_trash' => __('Nenhum Documento encontrado na lixeira.', 'mi'),
        )
    );

    $documento->set_arguments(
        array(
            'supports'             => array('title'),
            'menu_icon'         => 'dashicons-media-document',
            'show_in_nav_menus' => true
        )
    );
}
