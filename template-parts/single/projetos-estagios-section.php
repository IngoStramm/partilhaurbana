<?php
$post_id = get_the_ID();
global $total_effort, $total_cost;
?>
<div class="col-md-12">
    <div class="card card-body">
        <h2 class="section-title"><?php _e('Estágios de restauração previstos', 'pu'); ?></h2>
        <div class="table-responsive">
            <table class="table align-middle text-nowrap">
                <thead class="header-item">
                    <tr>
                        <th><?php _e('Estágio', 'pu'); ?></th>
                        <th width="200"><?php _e('Esforço Por Etapa', 'pu'); ?></th>
                        <th width="200"><?php _e('Custo Estimado', 'pu'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- start row -->
                    <?php
                    $estagios = get_post_meta($post_id, 'estagios_settings', true);
                    // pu_debug($estagios);
                    if ($estagios) {

                        foreach ($estagios as $k => $estagio) {
                            $estagio_title = isset($estagio['title']) && $estagio['title'] ? $estagio['title'] : null;
                            $estagio_effort = isset($estagio['effort']) && $estagio['effort'] ? $estagio['effort'] : 0;
                            $estagio_cost = isset($estagio['cost']) && $estagio['cost'] ? $estagio['cost'] : 0;
                            $total_effort += pu_format_number($estagio_effort);
                            $total_cost += pu_format_number($estagio_cost);
                            if ($estagio_title) {
                    ?>
                                <tr>
                                    <td>
                                        <?php echo $estagio_title; ?>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input
                                                type="text"
                                                id="<?php echo 'effort-' . $k; ?>"
                                                name="effort[]"
                                                class="form-control pct-input text-center effort-input"
                                                value="<?php echo $estagio_effort; ?>"
                                                data-estagio-id="<?php echo $k; ?>"
                                                aria-label="<?php printf(__('Esforço (%s)', 'pu'), $estagio_title); ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input
                                                type="text"
                                                id="<?php echo 'cost-' . $k; ?>"
                                                name="cost[]"
                                                class="form-control money-no-decimals-input text-center cost-input"
                                                data-estagio-id="<?php echo $k; ?>"
                                                value="<?php echo $estagio_cost; ?>"
                                                aria-label="<?php printf(__('Custo (%s)', 'pu'), $estagio_title); ?>">
                                        </div>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="3"><?php _e('Nenhum estágio definido para este projeto', 'pu'); ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <!-- end row -->
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td>
                            <div class="form-group green-input">
                                <input
                                    type="text" id="total-estagios-effort"
                                    name="total-effort"
                                    class="form-control pct-no-limit-input text-center"
                                    value="<?php echo $total_effort; ?>"
                                    aria-label="<?php printf(__('Esforço Total (%s)', 'pu'), $estagio_title); ?>"
                                    readonly>
                            </div>
                        </td>
                        <td>
                            <div class="form-group green-input">
                                <input
                                    type="text"
                                    id="total-estagios-cost"
                                    name="total-cost"
                                    class="form-control money-no-decimals-input text-center"
                                    value="<?php echo $total_cost; ?>"
                                    aria-label="<?php printf(__('Custo Total (%s)', 'pu'), $estagio_title); ?>"
                                    readonly>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>