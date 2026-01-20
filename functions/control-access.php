<?php

add_action('wp_head', 'pu_control_user_access');

function pu_control_user_access()
{
    if (!pu_user_can_access()) {
        $login_page_url = pu_get_page_url('login');
        $login_page_url .= get_permalink() ? '?redirect_to=' . urlencode(get_permalink()) : '';
        $output = '
        <script>
        sessionStorage.removeItem("userAccessMsg");
        sessionStorage.setItem("userAccessMsg", "' . __('É preciso estar logado para acessar esta área', 'pu') . '");
        window.location = "' . $login_page_url . '";
        </script>
        ';
        echo $output;
    }
}

function pu_user_can_access()
{
    $access = true;
    $curr_page_id = get_the_ID();
    $public_pages_id = [];
    $public_pages_id[] = pu_get_page_id('login');
    $public_pages_id[] = pu_get_page_id('newuser');
    $public_pages_id[] = pu_get_page_id('lostpassword');
    $public_pages_id[] = pu_get_page_id('resetpassword');

    // Verifica se a página atual é pública
    if (!in_array($curr_page_id, $public_pages_id)) {

        // Verifica se o usuário está logado
        if (!is_user_logged_in()) {
            $access = false;
        }
    }
    return $access;
}
