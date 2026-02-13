<?php

add_action('init', 'pu_diario_da_obra_post_type', 1);

function pu_diario_da_obra_post_type()
{
    $post_types = pu_obra_posts_types();
    $portfolio = new PU_Post_Type(
        $post_types['diario-da-obra'], // Nome (Singular) do Post Type.
        'diario-da-obra' // Slug do Post Type.;
    );

    $portfolio->set_labels(
        array(
            'name'               => __('Diário da Obra', 'mi'),
            'singular_name'      => __('Diário da Obra', 'mi'),
            'menu_name'          => __('Diário da Obra', 'mi'),
            'name_admin_bar'     => __('Diário da Obra', 'mi'),
            'add_new'            => __('Adicionar Novo Diário da Obra', 'mi'),
            'add_new_item'       => __('Adicionar Novo Diário da Obra', 'mi'),
            'new_item'           => __('Novo Diário da Obra', 'mi'),
            'edit_item'          => __('Editar Diário da Obra', 'mi'),
            'view_item'          => __('Visualizar Diário da Obra', 'mi'),
            'all_items'          => __('Todos os Diários da Obra', 'mi'),
            'search_items'       => __('Pesquisar Diários da Obra', 'mi'),
            'parent_item_colon'  => __('Diários da Obra Pai', 'mi'),
            'not_found'          => __('Nenhum Diário da Obra encontrado', 'mi'),
            'not_found_in_trash' => __('Nenhum Diário da Obra encontrado na lixeira.', 'mi'),
        )
    );

    $portfolio->set_arguments(
        array(
            'supports'             => array('title', 'thumbnail'),
            'menu_icon'         => 'dashicons-calculator',
            'show_in_nav_menus' => true
        )
    );
}
