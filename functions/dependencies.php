<?php

# Ref: @link: https://getbutterfly.com/how-to-install-wordpress-plugin-dependencies/

/**
 * pu_register_required_plugins
 *
 * @param  array $plugin_array
 * @return array
 */
function pu_register_required_plugins($plugin_array)
{
    $action = 'install-plugin';

    $output = [];

    foreach ($plugin_array as $recommended_plugin) {
        $name = $recommended_plugin['name'];
        $slug = $recommended_plugin['slug'];
        $file = $recommended_plugin['file'];

        $link = wp_nonce_url(
            add_query_arg(
                [
                    'action' => $action,
                    'plugin' => $slug,
                ],
                admin_url('update.php')
            ),
            $action . '_' . $slug
        );
        $all_status = [];

        $status = new stdClass();
        $status->slug = 'not-installed';
        $status->message =  __('não instalado', 'mi');
        $all_status[] = $status;

        $status = new stdClass();
        $status->slug = 'active';
        $status->message =  __('ativo', 'mi');
        $all_status[] = $status;

        $status = new stdClass();
        $status->slug = 'inactive';
        $status->message =  __('inativo', 'mi');
        $all_status[] = $status;

        $curr_status = new stdClass();

        $curr_status->slug = $all_status[0]->slug;
        $curr_status->message = $all_status[0]->message;;

        if (file_exists(WP_PLUGIN_DIR . '/' . $file)) {
            $curr_status->message = (is_plugin_active($file)) ? $all_status[1]->message : $all_status[2]->message;
            $curr_status->slug = (is_plugin_active($file)) ? $all_status[1]->slug : $all_status[2]->slug;
        }
        $plugin = new stdClass();
        $plugin->name = $name;
        $plugin->link = $link;
        $plugin->status = $curr_status->slug;
        $plugin->message = $curr_status->message;
        $output[] = $plugin;
    }

    return $output;
}

add_action('admin_notices', 'pu_admin_notice__warning');

/**
 * pu_admin_notice__warning
 *
 * @return string
 */
function pu_admin_notice__warning()
{

    $required_plugins = [
        [
            'name' => 'CMB2',
            'slug' => 'cmb2',
            'file' => 'cmb2/init.php',
        ]
    ];

    $need_plugin = false;
    $plugins = pu_register_required_plugins($required_plugins);
    foreach ($plugins as $plugin) {
        if ($plugin->status !== 'active') {
            $need_plugin = true;
        }
    }
    if (!$need_plugin) {
        return '';
    }
?>
    <div class="notice notice-warning is-dismissible">
        <h4><?php _e('Plugins obrigatórios', 'mi'); ?></h4>
        <ul>
            <?php
            $plugins = pu_register_required_plugins($required_plugins);
            foreach ($plugins as $plugin) {
                if ($plugin->status !== 'active') {
                    echo '<li><a href="' . $plugin->link . '" class="button button-secondary button-' . $plugin->status . '">' . $plugin->name . ' (' . $plugin->message . ')</a></li>';
                }
            }
            ?>
        </ul>
    </div>
<?php
}
