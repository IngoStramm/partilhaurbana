<?php

add_action('wp_enqueue_scripts', 'pu_frontend_scripts');

function pu_frontend_scripts()
{

    $min = (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1', '10.0.0.3'))) ? '' : '.min';
    $version = pu_version();

    if (empty($min)) :
        wp_enqueue_script('partilha-urbana-livereload', 'http://localhost:35729/livereload.js?snipver=1', array(), null, true);
    endif;

    #region Modernize Template scripts

    wp_register_script('vendor-script', PU_URL . '/assets/js/vendor.min.js', array('jquery'), $version, true);

    // Import Js Files
    wp_register_script('bootstrap-script', PU_URL . '/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js', array('jquery', 'vendor-script'), $version, true);

    wp_register_script('simplebar-script', PU_URL . '/assets/libs/simplebar/dist/simplebar.min.js', array('jquery', 'vendor-script', 'bootstrap-script'), $version, true);

    wp_register_script('app-init-script', PU_URL . '/assets/js/theme/app.init.js', array('jquery', 'vendor-script', 'bootstrap-script', 'simplebar-script'), $version, true);

    wp_register_script('theme-script', PU_URL . '/assets/js/theme/theme.js', array('jquery', 'vendor-script', 'bootstrap-script', 'simplebar-script', 'app-init-script'), $version, true);

    wp_register_script('app-script', PU_URL . '/assets/js/theme/app.min.js', array('jquery', 'vendor-script', 'bootstrap-script', 'simplebar-script', 'app-init-script', 'theme-script'), $version, true);

    wp_register_script('sidebarmenu-script', PU_URL . '/assets/js/theme/sidebarmenu.js', array('jquery', 'vendor-script', 'bootstrap-script', 'simplebar-script', 'app-init-script', 'theme-script', 'app-script'), $version, true);

    // Solar icons
    wp_register_script('iconify-icon-script', PU_URL . '/assets/js/iconify-icon/iconify-icon.min.js', array('jquery', 'vendor-script', 'bootstrap-script', 'simplebar-script', 'app-init-script', 'theme-script', 'app-script', 'sidebarmenu-script'), $version, true);

    // Highlight.js (code view)
    wp_register_script('highlight-script', PU_URL . '/assets/js/highlights/highlight.min.js', array('jquery', 'vendor-script', 'bootstrap-script', 'simplebar-script', 'app-init-script', 'theme-script', 'app-script', 'sidebarmenu-script', 'iconify-icon-script'), $version, true);

    wp_register_script('owl-script', PU_URL . '/assets/libs/owl.carousel/dist/owl.carousel.min.js', array('jquery', 'vendor-script', 'bootstrap-script', 'simplebar-script', 'app-init-script', 'theme-script', 'app-script', 'sidebarmenu-script', 'iconify-icon-script', 'highlight-script'), $version, true);

    wp_register_script('apexcharts-script', PU_URL . '/assets/libs/apexcharts/dist/apexcharts.min.js', array('jquery', 'vendor-script', 'bootstrap-script', 'simplebar-script', 'app-init-script', 'theme-script', 'app-script', 'sidebarmenu-script', 'iconify-icon-script', 'highlight-script', 'owl-script'), $version, true);

    wp_register_script('dashboard-script', PU_URL . '/assets/js/dashboards/dashboard.js', array('jquery', 'vendor-script', 'bootstrap-script', 'simplebar-script', 'app-init-script', 'theme-script', 'app-script', 'sidebarmenu-script', 'iconify-icon-script', 'highlight-script', 'owl-script', 'apexcharts-script'), $version, true);

    #endregion

    wp_register_script('imask-script', PU_URL . '/assets/js/imask.min.js', array('jquery', 'vendor-script', 'bootstrap-script', 'simplebar-script', 'app-init-script', 'theme-script', 'app-script', 'sidebarmenu-script', 'iconify-icon-script', 'highlight-script', 'owl-script', 'apexcharts-script'), $version, array('strategy' => 'defer', 'in_footer' => true));
    
    wp_register_script('partilha-urbana-script', PU_URL . '/assets/js/partilha-urbana' . $min . '.js', array('jquery', 'imask-script', 'vendor-script', 'vendor-script', 'bootstrap-script', 'simplebar-script', 'app-init-script', 'theme-script', 'app-script', 'sidebarmenu-script', 'iconify-icon-script', 'highlight-script', 'owl-script', 'apexcharts-script'), $version, true);

    wp_enqueue_script('partilha-urbana-script');

    wp_localize_script('partilha-urbana-script', 'ajax_object', array(
        'ajax_url'                  => admin_url('admin-ajax.php'),
        'plugin_url'                => PU_URL,
    ));


    wp_enqueue_style('theme-style', PU_URL . '/assets/css/styles.css', array(), $version, 'all');
    wp_enqueue_style('owl-style', PU_URL . '/assets/libs/owl.carousel/dist/assets/owl.carousel.min.css', array(), $version, 'all');
    wp_enqueue_style('partilha-urbana-style', PU_URL . '/assets/css/partilha-urbana.css', array('theme-style', 'owl-style'), $version, 'all');
}

// add_filter('style_loader_tag',  'pu_preload_filter', 10, 2);
function pu_preload_filter($html, $handle)
{
    if (strcmp($handle, 'fonts-morada-style') == 0) {
        $html = str_replace("rel='stylesheet'", "rel='preload'", $html);
    }
    return $html;
}

add_action('admin_enqueue_scripts', 'pu_admin_scripts');

function pu_admin_scripts()
{
    if (!is_user_logged_in())
        return;

    $version = pu_version();

    $min = (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1', '10.0.0.3'))) ? '' : '.min';

    wp_register_script('imask-script', PU_URL . '/assets/js/imask.min.js', array('jquery'), $version, array('strategy' => 'defer', 'in_footer' => true));

    wp_register_script('partilha-urbana-admin-script', PU_URL . '/assets/js/partilha-urbana-admin' . $min . '.js', array('jquery', 'imask-script'), $version, array('strategy' => 'defer', 'in_footer' => true));

    wp_enqueue_script('partilha-urbana-admin-script');

    wp_localize_script('partilha-urbana-admin-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
