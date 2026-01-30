<?php

/**
 * The main template file.
 *
 * The template for displaying financeiro archive pages
 *
 * @package partilha-urbana
 */

get_header(); ?>

<?php
$obra_id = isset($_GET['obra_id']) && $_GET['obra_id'] ? $_GET['obra_id'] : get_the_ID();
$help_section_title = __('Obras', 'pu');
$help_section_text = __('Acompanhamento completo da obra e transparência com cliente.', 'pu');
$help_section_url = '#';
$estagios_obra = get_post_meta($obra_id, 'estagios_settings', true);
$lancamentos_datas_asc = pu_get_datas_lancamento_financeiro_obra_asc();
$lancamentos_datas_desc = pu_get_datas_lancamento_financeiro_obra_desc();
get_template_part(
    'template-parts/general/help',
    'section',
    array(
        'title' => $help_section_title,
        'text' => $help_section_text,
        'url' => $help_section_url
    )
);
get_template_part(
    'template-parts/single/obras-header',
    'section',
    array('post_id' => $obra_id)
);

get_template_part('template-parts/general/select-obra', 'content', array('post_id' => $obra_id));
?>
<div class="row mt-3">
    <div class="col-md-12 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
        <select class="form-select" name="estagios-lancamentos" id="estagios-lancamentos">
            <option value=""><?php _e('Todas as fases da obra', 'pu'); ?></option>
            <?php foreach ($estagios_obra as $estagio) {
                $title = $estagio['title'];
                echo "<option>$title</option>";
            } ?>
        </select>
        <select class="form-select" name="data-lancamentos-asc" id="data-lancamentos-asc">
            <option value=""><?php _e('Da mais antiga', 'pu'); ?></option>
            <?php foreach ($lancamentos_datas_asc as $data) {
                echo '<option data-date="' . $data . '">' . sprintf('<strong>%s</strong> %s', __('Início', 'pu'), $data) . '</option>';
            } ?>
        </select>
        <select class="form-select" name="data-lancamentos-desc" id="data-lancamentos-desc">
            <option value=""><?php _e('Até a mais recente', 'pu'); ?></option>
            <?php foreach ($lancamentos_datas_desc as $data) {
                echo '<option data-date="' . $data . '">' . sprintf('<strong>%s</strong> %s', __('Término', 'pu'), $data) . '</option>';
            } ?>
        </select>
        <input class="form-control search-input" type="text" name="search-lancamentos" id="search-lancamentos" aria-label="<?php _e('Buscar', 'pu'); ?>" placeholder="<?php _e('Buscar', 'pu'); ?>">
    </div>
    <div class="col-md-12 d-flex flex-column flex-md-row align-items-stretch align-items-md-center justify-content-between gap-3 flex-wrap flex-md-nowrap mt-3">
        <div class="lancamentos-totals total-entrada">
            <label for="total-entrada-lancamentos"><?php _e('Total de entradas no período:', 'pu'); ?></label>
            <input type="text" id="total-entrada-lancamentos" name="total-entrada-lancamentos" class="money-no-decimals-input text-center" value="0" placeholder="0" readonly>
        </div>
        <div class="lancamentos-totals total-saida">
            <label for="total-saida-lancamentos"><?php _e('Total de saída no período:', 'pu'); ?></label>
            <input type="text" id="total-saida-lancamentos" name="total-saida-lancamentos" class="money-no-decimals-input text-center" value="0" placeholder="0" readonly>
        </div>
    </div>
</div>
<div class="row gap-4">
    <div class="col-md-12">
        <div class="card mt-4">
            <div class="card-body">
                <div class="table-responsive" id="table-financeiro-obra">
                    <table class="table w-100 text-nowrap">
                        <thead>
                            <tr>
                                <th><?php _e('Data', 'pu'); ?></th>
                                <th><?php _e('Tipo', 'pu'); ?></th>
                                <th><?php _e('Fase', 'pu'); ?></th>
                                <th><?php _e('Usuário', 'pu'); ?></th>
                                <th><?php _e('Nome', 'pu'); ?></th>
                                <th></th>
                                <th><?php _e('Valor €', 'pu'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();
