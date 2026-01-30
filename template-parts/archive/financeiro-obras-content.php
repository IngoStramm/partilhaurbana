<?php
$post_id = get_the_ID();
$tipo = get_post_meta($post_id, 'tipo', true);
$valor = get_post_meta($post_id, 'valor', true);
$data = get_post_meta($post_id, 'data', true);
$comprovante = get_post_meta($post_id, 'comprovante', true);
$estagio_id = get_post_meta($post_id, 'estagio_lancamento', true);
$projeto_id = get_post_meta($post_id, 'projeto_id', true);
$estagios_projeto = get_post_meta($projeto_id, 'estagios_settings', true);

$tipo_icon = $tipo === 'saida' ?
    '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
  <path d="M9 14.25L4.5 9.75M9 14.25L13.5 9.75M9 14.25L9 3.75" stroke="#FA896B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>' :
    '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
  <path d="M9 3.75L13.5 8.25M9 3.75L4.5 8.25M9 3.75V14.25" stroke="#52D85F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';

$download_icon =
    '<svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none">
  <path d="M5.87984 9.50008L9.49888 13.329L13.1179 9.53718M9.49888 3.16675V13.1191M4.07031 15.8334H14.9275" stroke="#5A6A85" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';
?>
<tr>
    <td><?php echo $data; ?></td>
    <td><?php
        echo $tipo_icon;
        echo $tipo === 'entrada' ? __('Entrada', 'pu') : __('Saída', 'pu'); ?></td>
    <td><?php echo isset($estagios_projeto[$estagio_id]['title']) && $tipo === 'saida' ? $estagios_projeto[$estagio_id]['title'] : ''; ?></td>
    <td><?php echo get_the_author(); ?></td>
    <td>
        <?php echo ($comprovante) ? "<a target='_blank' href='$comprovante'>" : ''; ?>
        <?php the_title(); ?>
        <?php echo ($comprovante) ? '</a>' : ''; ?>
    </td>
    <td>
        <?php if ($comprovante) { ?>
            <a target="_blank" href="<?php echo $comprovante; ?>"><?php echo $download_icon; ?></a>
        <?php } ?>
    </td>
    <td><?php echo $valor; ?></td>
</tr>