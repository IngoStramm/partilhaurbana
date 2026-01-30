<?php

add_action('pre_get_posts', 'pu_filter_financeiro_archive_by_obra_id');
function pu_filter_financeiro_archive_by_obra_id($query)
{
    if (!$query->is_main_query() || is_admin() || !$query->is_post_type_archive('financeiro')) {
        return;
    }
    $obra_id = isset($_GET['obra_id']) && ! empty($_GET['obra_id']) ? $_GET['obra_id'] : get_the_ID();
    $obra_id = sanitize_text_field($obra_id);
    $query->set('meta_key', 'projeto_id');
    $query->set('meta_value', $obra_id);
    $query->set('nopaging', true);
}
