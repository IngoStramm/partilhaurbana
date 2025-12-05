<?php

add_action('pre_get_posts', 'pu_filter_archive_by_status');
function pu_filter_archive_by_status($query)
{
    if (!$query->is_main_query() || is_admin() || !$query->is_archive()) {
        return;
    }
    $status = isset($_GET['status']) && ! empty($_GET['status']) ? $_GET['status'] : 'projeto';
    $term_slug = sanitize_text_field($status);

    $tax_query = array(
        array(
            'taxonomy' => 'status-do-projeto', // Replace with your custom taxonomy slug
            'field'    => 'slug',
            'terms'    => $term_slug,
        ),
    );
    $query->set('tax_query', $tax_query);
}

add_action('pre_get_posts', 'pu_pre_get_posts_author_archives');
function pu_pre_get_posts_author_archives($query)
{
    if (!$query->is_main_query() || is_admin() || !$query->is_archive()) {
        return;
    }
    $user_id = get_current_user_id();
    if (!$user_id) {
        $query->set('post__in', array(0));
    } else {
        $query->set('author', $user_id);
    }
}
