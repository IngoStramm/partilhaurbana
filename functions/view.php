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
 * mi_site_logo_url
 *
 * @return string
 */
function pu_site_logo_url()
{
    $custom_logo_id = get_theme_mod('custom_logo');
    $image = wp_get_attachment_image_src($custom_logo_id, 'full');
    return $image[0];
}
