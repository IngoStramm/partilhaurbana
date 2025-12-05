<?php

/**
 * pu_sidebar_menu_items
 *
 * @param  string $menu_slug
 * @return string
 */
function pu_sidebar_menu_items($menu_slug)
{
    $output = '';
    $menu_object  = wp_get_nav_menu_object($menu_slug);
    if ($menu_object) {
        $menu_items = wp_get_nav_menu_items($menu_object->term_id);
        // pu_debug($menu_items);
    }
    if ($menu_items) {
        $i = 0;
        foreach ($menu_items as $item) {
            $output .= "
                <li class='sidebar-item " . implode($item->classes) . "'>
                    <a class='sidebar-link'
                        href='$item->url'
                        aria-expanded='false'>
                        <span class='sidebar-link-icon'>" . pu_get_icon(implode($item->classes)) . "</span>
                        <span class='hide-menu'>$item->title</span>
                    </a>
                </li>";
            $i++;
        }
    }

    return $output;
}

function pu_get_icon($name)
{
    $icon = empty($name) || is_null($name) ? null : file_get_contents(PU_DIR . '/assets/icons/' . $name . '.svg');
    return !$icon ? null : $icon;
}

/**
 * pu_site_logo_url
 *
 * @return string
 */
function pu_site_logo_url()
{
    $custom_logo_id = get_theme_mod('custom_logo');
    $image = wp_get_attachment_image_src($custom_logo_id, 'full');
    return $image[0];
}

/**
 * pu_projecao_lucratividade_item
 *
 * @param  string $title
 * @param  string $field_text
 * @param  object $field
 * @param  string $slug
 * @param  boolean $td
 * @param  boolean $no_border
 * @return string
 */
function pu_projecao_lucratividade_item($title, $field_text, $field, $slug, $td = true, $no_border = true)
{
    $tipo_valor_options = pu_tipo_valor_options();
    $table_line = $td ? 'td' : 'th';
    $table_line .= $no_border ? ' class="no-border"' : ' class="with-border"';
    $input_color = $td ? 'pink-input' : 'green-input';
    $output = '';
    $output .= "
    <tr>
        <$table_line>$title</$table_line>
        <$table_line width='200'>
            <div class='form-group'>
                <input
                    type='number'
                    min='0'
                    step='1'
                    id='$slug-valor'
                    name='$slug-valor'
                    class='form-control'
                    value='$field->valor'
                    aria-label='" . sprintf(__('Valor de %s', 'pu'), $field_text) . "'>
            </div>
        </$table_line>
        <$table_line width='120'>
            <div class='form-group'>
                <select class='form-select mr-sm-2' id='$slug-tipo' name='$slug-tipo' aria-label='" . sprintf(__('Tipo do valor de %s', 'pu'), $field_text) . "'>";

    foreach ($tipo_valor_options as $k => $option) {
        $selected = $field->tipo === $k ? 'selected' : '';
        $output .= "<option value='$k' $selected>$option</option>";
    }

    $output .= "
                </select>
            </div>
        </$table_line>
        <$table_line width='200'>
            <div class='form-group $input_color'>
                <input
                    type='text'
                    id='$slug-total'
                    name='$slug-total'
                    class='form-control money-no-decimals-input'
                    value='0'
                    aria-label='" . sprintf(__('Valor total de %s', 'pu'), $field_text) . "'
                    readonly>
            </div>
        </$table_line>
    </tr>";
    return $output;
}
