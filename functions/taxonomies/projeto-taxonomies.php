<?php

add_action('init', 'pu_status_do_projeto_tax', 1);

function pu_status_do_projeto_tax()
{
    $tax = new PU_Taxonomy(
        __('Status do Projeto', 'mi'), // Nome (Singular) do nova Taxonomia.
        'status-do-projeto', // Slug do Taxonomia.
        'projetos' // Nome do tipo de conteúdo que a taxonomia irá fazer parte.
    );

    $tax->set_labels(
        array(
            'menu_name'                             => __('Status do Projeto', 'mi'),
            'name'                                  => __('Status dos Projetos', 'mi'),
            'add_new_item'                          => __('Adicionar novo Status do Projeto', 'mi'),
            'new_item_name'                         => __('Nova Status do Projeto', 'mi'),
            'all_items'                             => __('Todos Status dos Projetos', 'mi'),
            'separate_items_with_commas'            => __('Status dos Projetos separados por vírgula', 'mi'),
            'choose_from_most_used'                 => __('Escolha a partir dos Status dos Projetos mais usados', 'mi'),
        )
    );

    $tax->set_arguments(
        array(
            'hierarchical' => false,
            'default_term' => array(
                'name' => __('Projeto', 'mi'),
                'slug' => 'projeto',
            )
        )
    );
}

// add_action('init', 'pu_dono_do_projeto_tax', 1);

function pu_dono_do_projeto_tax()
{
    $tax = new PU_Taxonomy(
        __('Dono do Projeto', 'mi'), // Nome (Singular) do nova Taxonomia.
        'dono-do-projeto', // Slug do Taxonomia.
        'projetos' // Nome do tipo de conteúdo que a taxonomia irá fazer parte.
    );

    $tax->set_labels(
        array(
            'menu_name'                             => __('Dono do Projeto', 'mi'),
            'name'                                  => __('Donos dos Projetos', 'mi'),
            'add_new_item'                          => __('Adicionar nova Dono do Projeto', 'mi'),
            'new_item_name'                         => __('Nova Dono do Projeto', 'mi'),
            'all_items'                             => __('Todos Donos dos Projetos', 'mi'),
            'separate_items_with_commas'            => __('Donos dos Projetos separados por vírgula', 'mi'),
            'choose_from_most_used'                 => __('Escolha a partir dos Donos dos Projetos mais usados', 'mi'),
        )
    );

    $tax->set_arguments(
        array(
            'hierarchical' => false,
            // 'default_term' => array(
            //     'name' => __('Geral', 'mi'),
            //     'slug' => 'geral',
            // )
        )
    );
}
