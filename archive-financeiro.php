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
        <div class="input-group flex-shrink-1">
            <span class="input-group-text" id="data-lancamentos-asc-label"><?php _e('De', 'pu'); ?></span>
            <input type="date" name="data-lancamentos-asc" id="data-lancamentos-asc" class="form-control" aria-describedby="data-lancamentos-asc-label">
        </div>
        <div class="input-group flex-shrink-1">
            <span class="input-group-text" id="data-lancamentos-desc-label"><?php _e('Até', 'pu'); ?></span>
            <input type="date" name="data-lancamentos-desc" id="data-lancamentos-desc" class="form-control" aria-describedby="data-lancamentos-desc-label">
        </div>

        <input class="form-control search-input" type="text" name="search-lancamentos" id="search-lancamentos" aria-label="<?php _e('Buscar', 'pu'); ?>" placeholder="<?php _e('Buscar', 'pu'); ?>">
        <a
            href="#"
            class="btn btn-secondary btn-with-icon flex-shrink-0"
            data-bs-toggle="modal"
            data-bs-target="#modal-editar-lancamento-financeiro-obra">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M18 12.998H13V17.998C13 18.2633 12.8946 18.5176 12.7071 18.7052C12.5196 18.8927 12.2652 18.998 12 18.998C11.7348 18.998 11.4804 18.8927 11.2929 18.7052C11.1054 18.5176 11 18.2633 11 17.998V12.998H6C5.73478 12.998 5.48043 12.8927 5.29289 12.7052C5.10536 12.5176 5 12.2633 5 11.998C5 11.7328 5.10536 11.4785 5.29289 11.2909C5.48043 11.1034 5.73478 10.998 6 10.998H11V5.99805C11 5.73283 11.1054 5.47848 11.2929 5.29094C11.4804 5.1034 11.7348 4.99805 12 4.99805C12.2652 4.99805 12.5196 5.1034 12.7071 5.29094C12.8946 5.47848 13 5.73283 13 5.99805V10.998H18C18.2652 10.998 18.5196 11.1034 18.7071 11.2909C18.8946 11.4785 19 11.7328 19 11.998C19 12.2633 18.8946 12.5176 18.7071 12.7052C18.5196 12.8927 18.2652 12.998 18 12.998Z" fill="white" />
            </svg>
            <?php _e('Adicionar', 'pu'); ?>
        </a>
    </div>
    <div class="col-md-12 d-flex flex-column flex-md-row align-items-stretch align-items-md-center justify-content-between gap-3 flex-wrap flex-md-nowrap mt-3">
        <div class="lancamentos-totals total-entrada">
            <label for="total-entrada-lancamentos"><?php _e('Total de entradas no período:', 'pu'); ?></label>
            <input type="text" id="total-entrada-lancamentos" name="total-entrada-lancamentos" class="money-input text-center" value="0" placeholder="0" readonly>
        </div>
        <div class="lancamentos-totals total-saida">
            <label for="total-saida-lancamentos"><?php _e('Total de saída no período:', 'pu'); ?></label>
            <input type="text" id="total-saida-lancamentos" name="total-saida-lancamentos" class="money-input text-center" value="0" placeholder="0" readonly>
        </div>
    </div>
</div>
<div class="row gap-4">
    <div class="col-md-12">
        <div class="card mt-4">
            <div class="card-body">
                <div id="table-alert-placeholder"></div>
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
                                <th></th>
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
