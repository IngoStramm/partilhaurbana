<?php

/**
 * pu_debug
 *
 * @param  mixed $a
 * @return string
 */
function pu_debug($a)
{
    echo '<pre>';
    var_dump($a);
    echo '</pre>';
}
/**
 * pu_version
 *
 * @return string
 */
function pu_version()
{
    // $version = '1.0.1';
    $version = rand(0, 9999);
    // generate random version

    return $version;
}

/**
 * pu_the_html_classes
 *
 * @return string
 */
function pu_the_html_classes()
{
    /**
     * Filters the classes for the main <html> element.
     *
     * @param string The list of classes. Default empty string.
     */
    $classes = apply_filters('pu_html_classes', '');
    if (!$classes) {
        return;
    }
    echo 'class="' . esc_attr($classes) . '"';
}

/**
 * pu_pagination.
 *
 * @global array $wp_query   Current WP Query.
 * @global array $wp_rewrite URL rewrite rules.
 *
 * @param  int   $mid   Total of items that will show along with the current page.
 * @param  int   $end   Total of items displayed for the last few pages.
 * @param  bool  $show  Show all items.
 * @param  mixed $query Custom query.
 *
 * @return string       Return the pagination.
 */
function pu_pagination($mid = 2, $end = 1, $show = false, $query = null)
{
    // Prevent show pagination number if Infinite Scroll of JetPack is active.
    if (!isset($_GET['infinity'])) {

        global $wp_query, $wp_rewrite;

        $total_pages = $wp_query->max_num_pages;

        if (is_object($query) && null != $query) {
            $total_pages = $query->max_num_pages;
        }

        if ($total_pages > 1) {
            $url_base = $wp_rewrite->pagination_base;
            $big = 999999999;

            // Sets the paginate_links arguments.
            $arguments = apply_filters(
                'pu_pagination_args',
                array(
                    'base'      => esc_url_raw(str_replace($big, '%#%', get_pagenum_link($big, false))),
                    'format'    => '',
                    'current'   => max(1, get_query_var('paged')),
                    'total'     => $total_pages,
                    'show_all'  => $show,
                    'end_size'  => $end,
                    'mid_size'  => $mid,
                    'type'      => 'list',
                    'prev_text' => '<i class="ti ti-chevron-left"></i>',
                    'next_text' => '<i class="ti ti-chevron-right"></i>',
                )
            );

            // Aplica o HTML/classes CSS do bootstrap
            $pu_paginate_links = paginate_links($arguments);

            $pu_paginate_links = str_replace('<ul class=\'page-numbers\'>', '<ul class="pagination justify-content-center my-5">', $pu_paginate_links);
            $pu_paginate_links = str_replace('<li>', '<li class="page-item">', $pu_paginate_links);
            $pu_paginate_links = str_replace('page-numbers', 'page-link border-0 rounded-circle text-dark round-32 mx-1 d-flex align-items-center justify-content-center', $pu_paginate_links);

            $pu_paginate_links = str_replace('<li class="page-item"><span aria-current="page" class="page-link border-0 rounded-circle text-dark round-32 mx-1 d-flex align-items-center justify-content-center current">', '<li class="page-item active"><a class="page-link border-0 rounded-circle round-32 mx-1 d-flex align-items-center justify-content-center" href="javascript:void(0)">', $pu_paginate_links);

            $pu_paginate_links = str_replace('</span>', '</a>', $pu_paginate_links);
            $pu_paginate_links = str_replace('&hellip;', '...', $pu_paginate_links);

            $pu_paginate_links = str_replace('<span class="page-link border-0 rounded-circle text-dark round-32 mx-1 d-flex align-items-center justify-content-center dots">', '<a class="page-link border-0 rounded-circle text-dark round-32 mx-1 d-flex align-items-center justify-content-center" href="javascript:void(0)">', $pu_paginate_links);

            $pagination = '<nav aria-label="' . __('Navegação da Página', 'pu') . '">' . $pu_paginate_links . '</nav>';

            // Prevents duplicate bars in the middle of the url.
            if ($url_base) {
                $pagination = str_replace('//' . $url_base . '/', '/' . $url_base . '/', $pagination);
            }

            return $pagination;
        }
    }
}

if (!function_exists('pu_paging_nav')) {

    /**
     * Print HTML with meta information for the current post-date/time and author.
     *
     * @since 2.2.0
     */
    function pu_paging_nav()
    {
        $mid  = 2;     // Total of items that will show along with the current page.
        $end  = 1;     // Total of items displayed for the last few pages.
        $show = false; // Show all items.

        echo pu_pagination($mid, $end, false);
    }
}

/**
 * pu_check_if_plugin_is_active
 *
 * @param  string $plugin
 * @return boolean
 */
function pu_check_if_plugin_is_active($plugin)
{
    $active_plugins = get_option('active_plugins');
    return in_array($plugin, $active_plugins);
}

/**
 * pu_get_pages
 *
 * @return array
 */
function pu_get_pages()
{
    $pages = get_pages();
    $return_array = [];
    foreach ($pages as $page) {
        $return_array[$page->ID] = $page->post_title;
    }
    return $return_array;
}

/**
 * pu_logo
 *
 * @return string
 */
function pu_logo()
{
    $html = '';
    if (has_custom_logo()) {
        $custom_logo_id = get_theme_mod('custom_logo');
        $image = wp_get_attachment_image_src($custom_logo_id, 'medium');
        $html .= '<img class="site-logo" src="' . $image[0] . '" />';
    }
    return $html;
}

function pu_get_wysiwyg_output($meta_key, $post_id = 0)
{
    global $wp_embed;

    $post_id = $post_id ? $post_id : get_the_id();

    $content = get_post_meta($post_id, $meta_key, 1);
    $content = $wp_embed->autoembed($content);
    $content = $wp_embed->run_shortcode($content);
    $content = wpautop($content);
    $content = do_shortcode($content);

    return $content;
}

function pu_get_user_name($user_id = '')
{
    $nome = '';
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    if ($user_id) {
        $user = get_userdata($user_id);
        $nome = $user->first_name && $user->last_name ?
            $user->first_name . ' ' . $user->last_name :
            $user->display_name;
    }
    return $nome;
}

function get_user_email($user_id = '') {
    $email = '';
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    if ($user_id) {
        $user = get_userdata($user_id);
        $email = $user->user_email;
    }
    return $email;
}

function pu_text_login_btn()
{
    $output = '';
    if (is_user_logged_in()) {
        $nome = pu_get_user_name();
        $output = sprintf(__('Olá, %s', 'iv'), $nome);
    } else {
        $output = __('Entrar/Cadastrar', 'iv');
    }
    return $output;
}

/**
 * pu_get_page_id
 *
 * @param  string $slug ('login', 'newuser', 'lostpassword', 'resetpassword', 'newprojeto')
 * @return string
 */
function pu_get_page_id($slug)
{
    $return_id = '';
    switch ($slug) {
        case 'login':
            $login_page_id = pu_get_option('pu_login_page', false, 'pu_site_pages_options');
            if ($login_page_id) {
                $return_id = $login_page_id;
            }
            break;

        case 'newuser':
            $new_user_page_id = pu_get_option('pu_new_user_page', false, 'pu_site_pages_options');
            if ($new_user_page_id) {
                $return_id = $new_user_page_id;
            }
            break;

        case 'lostpassword':
            $lostpassword_page_id = pu_get_option('pu_lostpassword_page', false, 'pu_site_pages_options');
            if ($lostpassword_page_id) {
                $return_id = $lostpassword_page_id;
            }
            break;

        case 'resetpassword':
            $resetpassword_page_id = pu_get_option('pu_resetpassword_page', false, 'pu_site_pages_options');
            if ($resetpassword_page_id) {
                $return_id = $resetpassword_page_id;
            }
            break;

        case 'newprojeto':
            $resetpassword_page_id = pu_get_option('pu_new_projeto', false, 'pu_site_pages_options');
            if ($resetpassword_page_id) {
                $return_id = $resetpassword_page_id;
            }
            break;

        default:
            $return_id = null;
            break;
    }
    return $return_id;
}

/**
 * pu_get_page_url
 *
 * @param  string $slug ('login', 'newuser', 'lostpassword', 'resetpassword', 'newprojeto')
 * @return string
 */
function pu_get_page_url($slug)
{
    $return_url = '';
    switch ($slug) {
        case 'login':
            $login_page_id = pu_get_page_id('login');
            if ($login_page_id) {
                $return_url = get_page_link($login_page_id);
            }
            break;

        case 'newuser':
            $new_user_page_id = pu_get_page_id('newuser');
            if ($new_user_page_id) {
                $return_url = get_page_link($new_user_page_id);
            }
            break;

        case 'lostpassword':
            $lostpassword_page_id = pu_get_page_id('lostpassword');
            if ($lostpassword_page_id) {
                $return_url = get_page_link($lostpassword_page_id);
            }
            break;

        case 'resetpassword':
            $resetpassword_page_id = pu_get_page_id('resetpassword');
            if ($resetpassword_page_id) {
                $return_url = get_page_link($resetpassword_page_id);
            }
            break;
        case 'newprojeto':
            $newprojeto_page_id = pu_get_page_id('newprojeto');
            if ($newprojeto_page_id) {
                $return_url = get_page_link($newprojeto_page_id);
            }
            break;

        default:
            $return_url = get_home_url();
            break;
    }
    return $return_url;
}

/**
 * pu_get_option
 *
 * @param  string $key
 * @param  boolean $default
 * @param  string $option_key
 * @return mixed
 */
function pu_get_option($key = '', $default = false, $option_key = 'pu_theme_options')
{
    if (function_exists('cmb2_get_option')) {
        // Use cmb2_get_option as it passes through some key filters.
        return cmb2_get_option($option_key, $key, $default);
    }
    // Fallback to get_option if CMB2 is not loaded yet.
    $opts = get_option($option_key, $default);
    $val = $default;
    if ('all' == $key) {
        $val = $opts;
    } elseif (is_array($opts) && array_key_exists($key, $opts) && false !== $opts[$key]) {
        $val = $opts[$key];
    }
    return $val;
}


/**
 * pu_atualiza_termos
 *
 * @param  array/string $terms_id
 * @param  int $post_id
 * @param  string $tax_slug
 * @return array/WP_Error
 */
function pu_atualiza_termos($terms_id, $post_id, $tax_slug)
{
    // Converte para int os IDs dos termos no array
    // Isso é necessário para que a função 'wp_set_object_terms' entenda que se trata de IDs,
    // senão ela irá criar novos termos tratando os IDs como se fossem títulos (ou slugs) dos termos
    // como não irá encontrar estes termos, então irá criar novos termos usando os IDs como título
    if (is_array($terms_id)) {
        $int_terms_id = [];
        foreach ($terms_id as $term) {
            $int_terms_id[] = intval($term);
        }
    } else {
        $int_terms_id = (int)$terms_id;
    }

    // os termos precisam ser inseridos após o post ser criado, 
    // porque o usuário não tem permissãi para criar termos
    $insert_terms = wp_set_object_terms($post_id, $int_terms_id, $tax_slug);
    return $insert_terms;
}

/**
 * pu_format_money
 *
 * @param  mixed $number
 * @return string
 */
function pu_format_money($number, $decimal = 0)
{
    if (!is_numeric($number)) {
        $number = str_replace('.', '', $number);
        $number = str_replace(',', '.', $number);
    }

    $number = floatval($number);

    return number_format($number, $decimal, ',', '.');
}

/**
 * pu_format_number
 *
 * @param  string $number
 * @return float
 */
function pu_format_number($number)
{
    $number = str_replace('.', '', $number);
    $number = str_replace(',', '.', $number);
    $number = floatval($number);
    return $number;
}

function pu_update_profile_user_image($file, $user_id, $slug, $changed_thumbnail)
{
    $account_page_id = pu_get_option('pu_account_page');
    $account_page_url = $account_page_id ? get_page_link($account_page_id) : get_home_url();
    unset($_SESSION['pu_update_user_error_message']);

    if ($file) {
        $filename = $file['name'];
        $file_size = $file['size'];
        $file_tmp_name = $file['tmp_name'];
        $file_url = '';
        if ($file_size > 2097152) {
            $_SESSION['pu_update_user_error_message'] = sprintf(__('O arquivo %s é muito pesado, o tamanho máximo permitido é de 2MB..', 'mi'), $filename);
            wp_safe_redirect($account_page_url);
            exit;
        }
        $upload_file = wp_upload_bits($filename, null, @file_get_contents($file_tmp_name));
        // exit;
        if (!$upload_file['error']) {
            // Check the type of file. We'll use this as the 'post_mime_type'.
            $filetype = wp_check_filetype($filename, null);

            // Get the path to the upload directory.
            $wp_upload_dir = wp_upload_dir();

            // Prepare an array of post data for the attachment.
            $attachment = array(
                'post_mime_type' => $filetype['type'],
                'post_title'     => preg_replace('/\.[^.]+$/', '', $filename),
                'post_content'   => '',
                'post_status'    => 'inherit',
            );

            // Insert the attachment.
            $attach_id = wp_insert_attachment($attachment, $upload_file['file']);

            if (!is_wp_error($attach_id)) {
                // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                require_once(ABSPATH . 'wp-admin/includes/image.php');

                // Generate the metadata for the attachment, and update the database record.
                $attach_data = wp_generate_attachment_metadata($attach_id, $upload_file['file']);
                wp_update_attachment_metadata($attach_id, $attach_data);

                $file_url = wp_get_attachment_url($attach_id);

                update_user_meta($user_id, 'pu_user_avatar_' . $slug, $attach_id);
            } else {
                $_SESSION['pu_update_user_error_message'] = $attach_id->get_error_message();
                wp_safe_redirect($account_page_url);
                exit;
            }
        } else {
            $_SESSION['pu_update_user_error_message'] = sprintf(__('Ocorreu um erro ao tentar fazer o upload do arquivo %s.', 'mi'), $filename);
            wp_safe_redirect($account_page_url);
            exit;
        }
        $updated_user_image = update_user_meta($user_id, $slug, $file_url);
        if (!$updated_user_image) {
            $_SESSION['pu_update_user_error_message'] = __('Ocorreu um erro ao tentar atualizar a imagem do usuário.', 'mi');
            wp_safe_redirect($account_page_url);
            exit;
        }
    } else if ($changed_thumbnail) {
        $deleted_user_image = true;
        $deleted_user_image_id = true;

        $file_id = get_user_meta($user_id, 'pu_user_avatar_' . $slug, true);
        if ($file_id) {
            $deleted_user_image_id = delete_user_meta($user_id, 'pu_user_avatar_' . $slug);
        }

        $file = get_user_meta($user_id, $slug, true);
        if ($file) {
            $deleted_user_image = delete_user_meta($user_id, $slug);
        }

        if (!$deleted_user_image || !$deleted_user_image_id) {
            $_SESSION['pu_update_user_error_message'] = __('Ocorreu um erro ao tentar remover o avatar atual usuário.', 'mi');
            wp_safe_redirect($account_page_url);
            exit;
        }

        if ($file_id) {
            $delete_user_image_attachment = wp_delete_attachment($file_id);
            if (!$delete_user_image_attachment) {
                $_SESSION['pu_update_user_error_message'] = __('Ocorreu um erro ao tentar remover o arquivo do servidor, porém mesmo assim a imagem foi removida do perfil do usuário.', 'mi');
                wp_safe_redirect($account_page_url);
                exit;
            }
        }
    }
}


/**
 * pu_slugify
 *
 * @param  string $text
 * @param  string $divider
 * @return string
 */
function pu_slugify($text, string $divider = '-')
{
    // Mapeamento de caracteres acentuados comuns em português
    $acentos = array(
        'à',
        'á',
        'â',
        'ã',
        'ä',
        'å',
        'ç',
        'è',
        'é',
        'ê',
        'ë',
        'ì',
        'í',
        'î',
        'ï',
        'ñ',
        'ò',
        'ó',
        'ô',
        'õ',
        'ö',
        'ù',
        'ü',
        'ú',
        'ÿ',
        'À',
        'Á',
        'Â',
        'Ã',
        'Ä',
        'Å',
        'Ç',
        'È',
        'É',
        'Ê',
        'Ë',
        'Ì',
        'Í',
        'Î',
        'Ï',
        'Ñ',
        'Ò',
        'Ó',
        'Ô',
        'Õ',
        'Ö',
        'Ù',
        'Ü',
        'Ú',
        'Ÿ',
        'ä',
        'ö',
        'ü',
        'ß',
        'Ä',
        'Ö',
        'Ü'
    );
    $semAcentos = array(
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'c',
        'e',
        'e',
        'e',
        'e',
        'i',
        'i',
        'i',
        'i',
        'n',
        'o',
        'o',
        'o',
        'o',
        'o',
        'u',
        'u',
        'u',
        'y',
        'A',
        'A',
        'A',
        'A',
        'A',
        'A',
        'C',
        'E',
        'E',
        'E',
        'E',
        'I',
        'I',
        'I',
        'I',
        'N',
        'O',
        'O',
        'O',
        'O',
        'O',
        'U',
        'U',
        'U',
        'Y',
        'ae',
        'oe',
        'ue',
        'ss',
        'Ae',
        'Oe',
        'Ue'
    );

    $text = strtolower($text); // Converte para minúsculas
    $text = str_replace($acentos, $semAcentos, $text); // Remove acentos

    // Substitui caracteres não alfanuméricos e espaços por hífen
    $text = preg_replace('~[^\\pL\\pN]+~u', $divider, $text);

    $text = trim($text, $divider); // Remove hífens extras no início/fim
    $text = preg_replace('~-+~', $divider, $text); // Remove hífens duplicados


    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

/**
 * pu_previsao_lucro_liquido
 *
 * @param  int $post_id
 * @return string
 */
function pu_previsao_lucro_liquido($post_id)
{
    $previsao_lucro = get_post_meta($post_id, 'projeto_lucro', true);
    return $previsao_lucro ? $previsao_lucro : __('Ainda não definido', 'pu');
}

/**
 * pu_tipo_valor_options
 *
 * @return array
 */
function pu_tipo_valor_options()
{
    $options = array(
        'pct'       => __('%', 'pu'),
        'fixed'     => __('€', 'pu')
    );
    return $options;
}

/**
 * pu_projecao_lucratividade_field
 *
 * @param  int $post_id
 * @param  string $slug
 * @return object
 */
function pu_projecao_lucratividade_field($post_id, $slug)
{
    $field = new stdClass();
    $field->valor = get_post_meta($post_id, $slug . '_valor', true);
    $field->valor = $field->valor ? $field->valor : 0;
    $field->tipo = get_post_meta($post_id, $slug . '_tipo', true);
    $field->tipo = $field->tipo ? $field->tipo : 'fixed';
    return $field;
}

function pu_get_projeto_data($post_id = null)
{
    $post_id = !$post_id ? get_the_ID() : $post_id;
    $dono_projeto = get_post_meta($post_id, 'dono_do_projeto', true);
    $projeto = new stdClass();
    $projeto->title = get_the_title($post_id);
    $projeto->price = get_post_meta($post_id, 'preco', true);
    $projeto->observacoes = get_post_meta($post_id, 'observacoes', true);
    $projeto->owner = $dono_projeto;
    $projeto->estagios = is_singular('projetos') ? get_post_meta($post_id, 'estagios_settings', true) : array();

    $projeto->images = array();
    $post_thumbnail_url = get_the_post_thumbnail_url($post_id, 'full');
    if ($post_thumbnail_url) {
        $projeto->images[] = $post_thumbnail_url;
    }
    $projeto->id = $post_id;

    $preco_venda_valor = get_post_meta($post_id, 'preco_venda_valor', true);
    $preco_venda_valor = !$preco_venda_valor ? 0 : $preco_venda_valor;
    $preco_venda_tipo = get_post_meta($post_id, 'preco_venda_tipo', true);
    $preco_venda_tipo = !$preco_venda_tipo ? 'fixed' : $preco_venda_tipo;

    $comissao_impostos_valor = get_post_meta($post_id, 'comissao_impostos_valor', true);
    $comissao_impostos_valor = !$comissao_impostos_valor ? 0 : $comissao_impostos_valor;
    $comissao_impostos_tipo = get_post_meta($post_id, 'comissao_impostos_tipo', true);
    $comissao_impostos_tipo = !$comissao_impostos_tipo ? 'fixed' : $comissao_impostos_tipo;

    $certificado_documentacao_valor = get_post_meta($post_id, 'certificado_documentacao_valor', true);
    $certificado_documentacao_valor = !$certificado_documentacao_valor ? 0 : $certificado_documentacao_valor;
    $certificado_documentacao_tipo = get_post_meta($post_id, 'certificado_documentacao_tipo', true);
    $certificado_documentacao_tipo = !$certificado_documentacao_tipo ? 'fixed' : $certificado_documentacao_tipo;

    $imposto_lucro_valor = get_post_meta($post_id, 'imposto_lucro_valor', true);
    $imposto_lucro_valor = !$imposto_lucro_valor ? 0 : $imposto_lucro_valor;
    $imposto_lucro_tipo = get_post_meta($post_id, 'imposto_lucro_tipo', true);
    $imposto_lucro_tipo = !$imposto_lucro_tipo ? 'fixed' : $imposto_lucro_tipo;

    $escrituras_registros_valor = get_post_meta($post_id, 'escrituras_registros_valor', true);
    $escrituras_registros_valor = !$escrituras_registros_valor ? 0 : $escrituras_registros_valor;
    $escrituras_registros_tipo = get_post_meta($post_id, 'escrituras_registros_tipo', true);
    $escrituras_registros_tipo = !$escrituras_registros_tipo ? 'fixed' : $escrituras_registros_tipo;

    $outros_a_valor = get_post_meta($post_id, 'outros_a_valor', true);
    $outros_a_valor = !$outros_a_valor ? 0 : $outros_a_valor;
    $outros_a_tipo = get_post_meta($post_id, 'outros_a_tipo', true);
    $outros_a_tipo = !$outros_a_tipo ? 'fixed' : $outros_a_tipo;

    $outros_b_valor = get_post_meta($post_id, 'outros_b_valor', true);
    $outros_b_valor = !$outros_b_valor ? 0 : $outros_b_valor;
    $outros_b_tipo = get_post_meta($post_id, 'outros_b_tipo', true);
    $outros_b_tipo = !$outros_b_tipo ? 'fixed' : $outros_b_tipo;

    $projeto->projecao = array(
        'preco_venda_valor'                 => $preco_venda_valor,
        'preco_venda_tipo'                  => $preco_venda_tipo,
        'comissao_impostos_valor'            => $comissao_impostos_valor,
        'comissao_impostos_tipo'             => $comissao_impostos_tipo,
        'certificado_documentacao_valor'    => $certificado_documentacao_valor,
        'certificado_documentacao_tipo'     => $certificado_documentacao_tipo,
        'imposto_lucro_valor'               => $imposto_lucro_valor,
        'imposto_lucro_tipo'                => $imposto_lucro_tipo,
        'escrituras_registros_valor'        => $escrituras_registros_valor,
        'escrituras_registros_tipo'         => $escrituras_registros_tipo,
        'outros_a_valor'                    => $outros_a_valor,
        'outros_a_tipo'                     => $outros_a_tipo,
        'outros_b_valor'                    => $outros_b_valor,
        'outros_b_tipo'                     => $outros_b_tipo
    );
    return $projeto;
}

/**
 * pu_form_get_field
 *
 * @param  string $name
 * @param  string $msg
 * @param  string $field_type (field_type[default], email, url, absint, money, array)
 * @return string
 */
function pu_form_get_field($name, $msg, $field_type = 'text')
{
    if (!isset($_POST[$name]) || !$_POST[$name]) {
        wp_send_json_error(array('msg' => $msg), 200);
    }
    switch ($field_type) {
        case 'textarea':
            $field = sanitize_textarea_field($_POST[$name]);
            break;

        case 'email':
            $field = sanitize_email($_POST[$name]);
            break;

        case 'url':
            $field = esc_url_raw($_POST[$name]);
            break;

        case 'absint':
            $field = absint($_POST[$name]);
            break;

        case 'money':
            $field = sanitize_text_field($_POST[$name]);
            $field = pu_format_number($field);
            break;

        case 'array':
            $field_arr = $_POST[$name];
            if (count($field_arr) <= 0) {
                wp_send_json_error(array('msg' => $msg), 200);
            }
            $field = [];
            foreach ($_POST[$name] as $k => $v) {
                $field[] = sanitize_text_field($v);
            }
            break;

        default:
            $field = sanitize_text_field($_POST[$name]);
            break;
    }

    return $field;
}

/**
 * pu_update_campos_projecao
 *
 * @param  int $post_id
 * @param  string $post_key
 * @param  string $msg
 * @param  string $meta_key
 * @param  boolean $empty_value
 * @return string/array
 */
function pu_update_campos_projecao($post_id, $post_key, $msg, $meta_key, $empty_value = false)
{
    if (!isset($_POST[$post_key])) {
        wp_send_json_error(array('msg' => $msg), 200);
    }
    if (!$empty_value && !$_POST[$post_key]) {
        wp_send_json_error(array('msg' => $msg), 200);
    }
    update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$post_key]));
    return get_post_meta($post_id, $meta_key, true);
}

/**
 * Recursive function to generate a unique username.
 *
 * If the username already exists, will add a numerical suffix which will increase until a unique username is found.
 *
 * @param string $username
 *
 * @return string The unique username.
 */
function pu_generate_unique_username($username)
{
    $username = sanitize_title($username);
    static $i;
    if (null === $i) {
        $i = 1;
    } else {
        $i++;
    }

    if (!username_exists($username)) {
        return $username;
    }

    $new_username = sprintf('%s-%s', $username, $i);

    if (!username_exists($new_username)) {
        return $new_username;
    } else {
        return call_user_func(__FUNCTION__, $username);
    }
}
