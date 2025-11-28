<?php

add_action('init', 'mi_imovel_post_type', 1);

function mi_imovel_post_type()
{
    $portfolio = new PU_Post_Type(
        'Projeto', // Nome (Singular) do Post Type.
        'projeto' // Slug do Post Type.;
    );

    $portfolio->set_labels(
        array(
            'name'               => __('Projeto', 'mi'),
            'singular_name'      => __('Projeto', 'mi'),
            'menu_name'          => __('Projetos', 'mi'),
            'name_admin_bar'     => __('Projeto', 'mi'),
            'add_new'            => __('Adicionar Projeto', 'mi'),
            'add_new_item'       => __('Adicionar Novo Projeto', 'mi'),
            'new_item'           => __('Novo Projeto', 'mi'),
            'edit_item'          => __('Editar Projeto', 'mi'),
            'view_item'          => __('Visualizar Projeto', 'mi'),
            'all_items'          => __('Todos os Projetos', 'mi'),
            'search_items'       => __('Pesquisar Projetos', 'mi'),
            'parent_item_colon'  => __('Projetos Pai', 'mi'),
            'not_found'          => __('Nenhum Projeto encontrado', 'mi'),
            'not_found_in_trash' => __('Nenhum Projeto encontrado na lixeira.', 'mi'),
        )
    );

    $portfolio->set_arguments(
        array(
            'supports'             => array('title', 'thumbnail', 'revisions'),
            'menu_icon'         => 'dashicons-open-folder',
            'show_in_nav_menus' => true
        )
    );
}
