<?php
add_action('init', 'pu_init_session');

function pu_init_session()
{
    if (!session_id()) {
        session_start();
    }
}
