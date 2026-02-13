<?php

add_action('init', 'pu_fornecedores_tax', 1);

function pu_fornecedores_tax()
{
    $tax = new PU_Taxonomy(
        __('Fornecedor', 'mi'), // Nome (Singular) do nova Taxonomia.
        'fornecedor', // Slug do Taxonomia.
        'financeiro' // Nome do tipo de conteúdo que a taxonomia irá fazer parte.
    );

    $tax->set_labels(
        array(
            'menu_name'                             => __('Fornecedor', 'mi'),
            'name'                                  => __('Fornecedores', 'mi'),
            'add_new_item'                          => __('Adicionar novo Fornecedor', 'mi'),
            'new_item_name'                         => __('Nova Fornecedor', 'mi'),
            'all_items'                             => __('Todos Fornecedores', 'mi'),
            'separate_items_with_commas'            => __('Fornecedores separados por vírgula', 'mi'),
            'choose_from_most_used'                 => __('Escolha a partir dos Fornecedores mais usados', 'mi'),
        )
    );

    $tax->set_arguments(
        array(
            'hierarchical'          => false
        )
    );
}
