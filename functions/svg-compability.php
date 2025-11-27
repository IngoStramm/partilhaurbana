<?php
add_filter('upload_mimes', 'pu_mime_types');

function pu_mime_types($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}

add_action('admin_head', 'pu_fix_svg');

function pu_fix_svg()
{
    echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}
