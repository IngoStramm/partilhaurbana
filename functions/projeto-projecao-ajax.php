<?php

add_action('wp_ajax_nopriv_projeto_projecao_form', 'pu_projeto_projecao_form');
add_action('wp_ajax_pu_projeto_projecao_form', 'pu_projeto_projecao_form');

function pu_projeto_projecao_form()
{
    if (!isset($_POST['pu_projeto_projecao_form_nonce']) || !wp_verify_nonce($_POST['pu_projeto_projecao_form_nonce'], 'pu_projeto_projecao_form_nonce')) {
        wp_send_json_error(array('msg' => __('Não foi possível validar a requisição.', 'pu')), 200);
    }

    // Verifica se o usuário existe
    $user_id = pu_form_get_field('user_id', __('ID do usuário ausente', 'pu'), 'absint');
    $check_user_exists = get_user_by('id', $user_id);
    if (!$check_user_exists) {
        wp_send_json_error(array('msg' => __('Usuário inválido.', 'pu')), 200);
    }

    // Verifica se o usuário pode editar o post
    $check_user_permition = pu_user_can_access();

    if (!$check_user_permition) {
        wp_send_json_error(array('msg' => __('Você não possui permissão para editar este projeto.', 'pu')), 200);
    }

    $projetos_data = new stdClass();
    $old_estagios = [];
    $post = $_POST;

    if (!isset($_POST['post_id']) || !$_POST['post_id']) {
        wp_send_json_error(array('msg' => __('Não foi possível identificar o projeto.', 'pu')), 200);
    }
    $post_id = $_POST['post_id'];
    $projetos_data->post_id = $post_id;

    $previsao_lucro_resultado = isset($_POST['previsao-lucro-resultado']) && !empty($_POST['previsao-lucro-resultado']) ? $_POST['previsao-lucro-resultado'] : null;

    update_post_meta($post_id, 'projeto_lucro', sanitize_text_field($previsao_lucro_resultado));

    // Atualiza campos projeção

    $certificado_documentacao_valor = pu_update_campos_projecao($post_id, 'certificado-documentacao-valor', __('Não foi possível identificar o valor da certificação.', 'pu'), 'certificado_documentacao_valor', true);
    $projetos_data->certificado_documentacao_valor = $certificado_documentacao_valor;

    $certificado_documentacao_tipo = pu_update_campos_projecao($post_id, 'certificado-documentacao-tipo', __('Não foi possível identificar o tipo da certificação.', 'pu'), 'certificado_documentacao_tipo');
    $projetos_data->certificado_documentacao_tipo = $certificado_documentacao_tipo;

    $comissao_impostos_tipo = pu_update_campos_projecao($post_id, 'comissao-impostos-tipo', __('Não foi possível identificar o tipo da comissão.', 'pu'), 'comissao_impostos_tipo');
    $projetos_data->comissao_impostos_tipo = $comissao_impostos_tipo;

    $updated_projeto_comissao_impostos_valor = pu_update_campos_projecao($post_id, 'comissao-impostos-valor', __('Não foi possível identificar o valor da comissão.', 'pu'), 'comissao_impostos_valor', true);
    $projetos_data->comissao_impostos_valor = $updated_projeto_comissao_impostos_valor;

    $escrituras_registros_tipo = pu_update_campos_projecao($post_id, 'escrituras-registros-tipo', __('Não foi possível identificar o tipo das escrituras e registros.', 'pu'), 'escrituras_registros_tipo');
    $projetos_data->escrituras_registros_tipo = $escrituras_registros_tipo;

    $updated_projeto_escrituras_registros_valor = pu_update_campos_projecao($post_id, 'escrituras-registros-valor', __('Não foi possível identificar o valor das escrituras e registros.', 'pu'), 'escrituras_registros_valor', true);
    $projetos_data->escrituras_registros_valor = $updated_projeto_escrituras_registros_valor;

    $updated_projeto_imposto_lucro_tipo = pu_update_campos_projecao($post_id, 'imposto-lucro-tipo', __('Não foi possível identificar o tipo do imposto sobre o lucro.', 'pu'), 'imposto_lucro_tipo');
    $projetos_data->imposto_lucro_tipo = $updated_projeto_imposto_lucro_tipo;

    $updated_projeto_imposto_lucro_valor = pu_update_campos_projecao($post_id, 'imposto-lucro-valor', __('Não foi possível identificar o valor do imposto sobre o lucro.', 'pu'), 'imposto_lucro_valor', true);
    $projetos_data->imposto_lucro_valor = $updated_projeto_imposto_lucro_valor;

    $updated_projeto_outros_tipo_a = pu_update_campos_projecao($post_id, 'outros-a-tipo', __('Não foi possível identificar o tipo do outros A.', 'pu'), 'outros_a_tipo');
    $projetos_data->outros_a_tipo = $updated_projeto_outros_tipo_a;

    $updated_projeto_outros_valor_a = pu_update_campos_projecao($post_id, 'outros-a-valor', __('Não foi possível identificar o valor do outros A.', 'pu'), 'outros_a_valor', true);
    $projetos_data->outros_a_valor = $updated_projeto_outros_valor_a;

    $updated_projeto_outros_tipo_b = pu_update_campos_projecao($post_id, 'outros-b-tipo', __('Não foi possível identificar o tipo do outros B.', 'pu'), 'outros_b_tipo');
    $projetos_data->outros_b_tipo = $updated_projeto_outros_tipo_b;

    $updated_projeto_outros_valor_b = pu_update_campos_projecao($post_id, 'outros-b-valor', __('Não foi possível identificar o valor do outros B.', 'pu'), 'outros_b_valor', true);
    $projetos_data->outros_b_valor = $updated_projeto_outros_valor_b;

    $updated_projeto_preco_venda_tipo = pu_update_campos_projecao($post_id, 'preco-venda-tipo', __('Não foi possível identificar o tipo do preço de venda.', 'pu'), 'preco_venda_tipo');
    $projetos_data->preco_venda_tipo = $updated_projeto_preco_venda_tipo;

    $updated_projeto_preco_venda_valor = pu_update_campos_projecao($post_id, 'preco-venda-valor', __('Não foi possível identificar o valor do preço de venda.', 'pu'), 'preco_venda_valor', true);
    $projetos_data->preco_venda_valor = $updated_projeto_preco_venda_valor;

    // Atualiza Observações

    $updated_projeto_observacoes = pu_update_campos_projecao($post_id, 'projeto-observacoes', __('Não foi encontrada as observações do projeto.', 'pu'), 'observacoes', true);

    // Atualiza estágios (effort e cost)

    if (!isset($_POST['cost'])) {
        wp_send_json_error(array('msg' => __('Não foi possível identificar os custos das etapas de restauração previstos.', 'pu')), 200);
    }
    $costs = $_POST['cost'];

    if (!isset($_POST['effort'])) {
        wp_send_json_error(array('msg' => __('Não foi possível identificar os custos das etapas de restauração previstos.', 'pu')), 200);
    }
    $efforts = $_POST['effort'];

    $old_estagios = get_post_meta($post_id, 'estagios_settings', true);
    $new_estagios = [];
    foreach ($old_estagios as $k => $estagio) {
        $new_estagio = [];
        $new_estagio['title'] = $estagio['title'];
        $new_estagio['effort'] = $efforts[$k];
        $new_estagio['cost'] = $costs[$k];
        $new_estagios[$k] = $new_estagio;
    }

    $update_estagios = update_post_meta($post_id, 'estagios_settings', $new_estagios);
    $updated_estagios = get_post_meta($post_id, 'estagios_settings', true);

    $response = array(
        'msg'                   => __('Projeto atualizado com sucesso!', 'pu'),
        'post'                  => $post,
        'projetos_data'         => $projetos_data,
        'estagios'              => $updated_estagios,
        'projeto_observacoes'   => $updated_projeto_observacoes,
        'projeto_lucro'         => $previsao_lucro_resultado
    );

    wp_send_json_success($response);
    exit;
}
