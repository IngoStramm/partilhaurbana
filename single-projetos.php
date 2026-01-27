<?php

/**
 * The template for displaying all single projetos posts.
 *
 * @package partilha-urbana
 */

get_header();
if (pu_user_can_access()) {
    do_action('projeto_messages');
    $view = isset($_GET['view']) && $_GET['view'] ? $_GET['view'] : null;
    if ($view === 'settings') {
        get_template_part('template-parts/single/single', 'settings-bar');
    } else {
        get_template_part('template-parts/single/single', 'admin-bar');
        get_template_part('template-parts/general/help', 'section', array('title' => __('Projetos!', 'pu'), 'text' => __('Os projetos ajudam a planejar a obra.', 'pu'), 'url' => '#'));
    }
    if (have_posts()) { ?>
        <div class="row">
            <?php // Load posts loop.
            while (have_posts()) {
                the_post();
                if ($view === 'settings') {
                    get_template_part('template-parts/single/single', 'projetos-settings');
                } else {
                    get_template_part('template-parts/single/single', 'projetos-content');
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
