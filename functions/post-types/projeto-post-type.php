<?php

add_action('init', 'mi_imovel_post_type', 1);

function mi_imovel_post_type()
{
    $post_types = pu_obra_posts_types();
    $portfolio = new PU_Post_Type(
        $post_types['projetos'], // Nome (Singular) do Post Type.
        'projetos' // Slug do Post Type.;
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
            'supports'             => array('title', 'thumbnail'),
            'menu_icon'         => 'dashicons-book-alt',
            'show_in_nav_menus' => true
        )
    );
}
