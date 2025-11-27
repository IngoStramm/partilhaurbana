<?php

add_filter('lostpassword_url',  'pu_lostpassword_url', 10, 0);
function pu_lostpassword_url()
{
    $lostpassword_id = pu_get_option('pu_lostpassword_page', false, 'pu_site_pages_options');
    return $lostpassword_id ? pu_get_page_url('lostpassword') : site_url('/wp-login.php?action=lostpassword');
}

add_filter('login_url', 'pu_login_url', 10, 3);
function pu_login_url($login_url, $redirect, $force_reauth)
{
    $login_page_url = pu_get_page_url('login');
    $login_url = $login_page_url ? $login_page_url : $login_url;
    if (! empty($redirect)) {
        $login_url = add_query_arg('redirect_to', urlencode($redirect), $login_url);
    }
    if ($force_reauth) {
        $login_url = add_query_arg('reauth', '1', $login_url);
    }
    return $login_url;
}
