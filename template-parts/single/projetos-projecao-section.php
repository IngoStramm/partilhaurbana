<?php
global $total_cost;
$post_id = get_the_ID();
$preco = get_post_meta($post_id, 'preco', true);

$preco_venda = pu_projecao_lucratividade_field($post_id, 'preco_venda');
$comissao_impostos = pu_projecao_lucratividade_field($post_id, 'comissao_impostos');
$certificado_documentacao = pu_projecao_lucratividade_field($post_id, 'certificado_documentacao');
$imposto_lucro = pu_projecao_lucratividade_field($post_id, 'imposto_lucro');
$escrituras_registros = pu_projecao_lucratividade_field($post_id, 'escrituras_registros');
$outros_a = pu_projecao_lucratividade_field($post_id, 'outros_a');
$outros_b = pu_projecao_lucratividade_field($post_id, 'outros_b');

$tipo_valor_options = pu_tipo_valor_options();
?>
<div class="col-md-12">
    <div class="card card-body">
        <h2 class="section-title"><?php _e('Projeção de lucratividade', 'pu'); ?></h2>
        <p><?php printf(__('Investimento total previsto no estágios da restauração: <input type="text" id="total-estagios-cost-view" class="money-no-decimals-input invisible-input" value="%s" disabled>', 'pu'), pu_format_money($total_cost)); ?><br><?php printf(__('Preço de compra do imóvel: %s €', 'pu'), pu_format_money($preco)); ?></p>

        <div class="table-responsive">
            <table class="table align-middle text-nowrap">
                <thead class="header-item">
                    <tr>
                        <?php echo pu_projecao_lucratividade_item(__('Por quanto pretende vender este imóvel:', 'pu'), __('Preço de venda', 'pu'), $preco_venda, 'preco-venda', false, false); ?>
                    </tr>
                    <tr>
                        <th colspan="4" class="no-border"><?php _e('Caso queira fazer as deduções com precisão, preencha os dados:', 'pu'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php echo pu_projecao_lucratividade_item(__('Comissão da imobiliária e impostos:', 'pu'), __('Comissão de impostos', 'pu'), $comissao_impostos, 'comissao-impostos'); ?>
                    </tr>
                    <tr>
                        <?php echo pu_projecao_lucratividade_item(__('Certificados e documentação:', 'pu'), __('Certificados e documentação', 'pu'), $certificado_documentacao, 'certificado-documentacao'); ?>
                    </tr>
                    <tr>
                        <?php echo pu_projecao_lucratividade_item(__('Imposto sobre o lucro (mais-valia):', 'pu'), __('Imposto sobre o lucro', 'pu'), $imposto_lucro, 'imposto-lucro'); ?>
                    </tr>
                    <tr>
                        <?php echo pu_projecao_lucratividade_item(__('Escrituras e registros:', 'pu'), __('Escrituras e registros', 'pu'), $escrituras_registros, 'escrituras-registros'); ?>
                    </tr>
                    <tr>
                        <?php echo pu_projecao_lucratividade_item(__('Outros A:', 'pu'), __('Outros A', 'pu'), $outros_a, 'outros-a'); ?>
                    </tr>
                    <tr>
                        <?php echo pu_projecao_lucratividade_item(__('Outros B:', 'pu'), __('Outros B', 'pu'), $outros_b, 'outros-b'); ?>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="previsao-lucro-resultado-container">
            <div class="previsao-lucro-resultado-aside">
                <h3 class="previsao-lucro-resultado-aside-title"><?php _e('Previsão de lucro líquido:', 'pu'); ?></h3>
                <p class="previsao-lucro-resultado-aside-text">
                    <?php _e('Retorno sobre o investimento (ROI):', 'pu'); ?>
                    <span class="roi">
                        <span class="roi-lucro">0</span> / <span class="roi-custo">0</span> = <span class="roi-resultado">0</span>%
                    </span>
                </p>
            </div>
            <div class="previsao-lucro-resultado-aside">
                <input
                    type="text"
                    id="previsao-lucro-resultado"
                    name="previsao-lucro-resultado" class="previsao-lucro-resultado previsao-lucro-resultado-input invisible-input"
                    value="0"
                    readonly />
            </div>
        </div>
    </div>
</div>