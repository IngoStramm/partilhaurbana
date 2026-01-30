<?php

/**
 * The template for displaying all single projetos posts.
 *
 * @package partilha-urbana
 */

get_header();
if (pu_user_can_access()) {
    $status_do_projeto = get_the_terms(get_the_ID(), 'status-do-projeto');
    $status_do_projeto = is_array($status_do_projeto) ? $status_do_projeto[0] : $status_do_projeto;
    $help_section_title = $status_do_projeto->slug === 'projeto' ? __('Projetos!', 'pu') : __('Obras', 'pu');
    $help_section_text = $status_do_projeto->slug === 'projeto' ? __('Os projetos ajudam a planejar a obra.', 'pu') : __('Acompanhamento completo da obra e transparência com cliente.', 'pu');
    $help_section_url = '#';
    $view = isset($_GET['view']) && $_GET['view'] ? $_GET['view'] : null;

    do_action('projeto_messages');

    if ($view === 'settings' && $status_do_projeto->slug === 'projeto') {
        get_template_part('template-parts/single/single', 'settings-bar');
    }

    if ($view !== 'settings' && $status_do_projeto->slug === 'projeto') {
        get_template_part('template-parts/single/single', 'admin-bar');
    }

    get_template_part('template-parts/general/help', 'section', array('title' => $help_section_title, 'text' => $help_section_text, 'url' => $help_section_url));

    if (have_posts()) { ?>
        <div class="row">
            <?php // Load posts loop.
            while (have_posts()) {
                the_post();
                if ($status_do_projeto->slug === 'projeto') {
                    if ($view === 'settings') {
                        get_template_part('template-parts/single/single', 'projetos-settings');
                    } else {
                        get_template_part('template-parts/single/single', 'projetos-content');
                    }
                } else {
                    get_template_part('template-parts/single/single', 'obras-content');
                }
            } ?>
        </div>
<?php
    } else {
        get_template_part('template-parts/content/content-none');
    }
} else {
    get_template_part('template-parts/single/single', 'access-denied');
}
get_footer();
