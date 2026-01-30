<?php

add_action('init', 'pu_financeiro_post_type', 1);

function pu_financeiro_post_type()
{
    $post_types = pu_obra_posts_types();
    $portfolio = new PU_Post_Type(
        $post_types['financeiro'], // Nome (Singular) do Post Type.
        'financeiro' // Slug do Post Type.;
    );

    $portfolio->set_labels(
        array(
            'name'               => __('Financeiro', 'mi'),
            'singular_name'      => __('Financeiro', 'mi'),
            'menu_name'          => __('Lançamentos Financeiro', 'mi'),
            'name_admin_bar'     => __('Financeiro', 'mi'),
            'add_new'            => __('Adicionar Lançamento Financeiro', 'mi'),
            'add_new_item'       => __('Adicionar Novo Lançamento Financeiro', 'mi'),
            'new_item'           => __('Novo Lançamento Financeiro', 'mi'),
            'edit_item'          => __('Editar Lançamento Financeiro', 'mi'),
            'view_item'          => __('Visualizar Lançamento Financeiro', 'mi'),
            'all_items'          => __('Todos os Lançamentos Financeiros', 'mi'),
            'search_items'       => __('Pesquisar Lançamentos Financeiros', 'mi'),
            'parent_item_colon'  => __('Lançamentos Financeiros Pai', 'mi'),
            'not_found'          => __('Nenhum Lançamento Financeiro encontrado', 'mi'),
            'not_found_in_trash' => __('Nenhum Lançamento Financeiro encontrado na lixeira.', 'mi'),
        )
    );

    $portfolio->set_arguments(
        array(
            'supports'             => array('title'),
            'menu_icon'         => 'dashicons-calculator',
            'show_in_nav_menus' => true
        )
    );
}
