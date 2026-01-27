<?php

/**
 * The main template file.
 *
 * The template for displaying projetos archive pages
 *
 * @package partilha-urbana
 */

get_header(); ?>

<?php
$status = isset($_GET['status']) && ! empty($_GET['status']) ? $_GET['status'] : 'projeto';

$help_section_title = $status === 'projeto' ? __('Projetos', 'pu') : __('Obras', 'pu');
$help_section_text = $status === 'projeto' ? __('Os projetos ajudam a planejar a obra', 'pu') : __('Acompanhamento completo da obra e transparência com cliente.', 'pu');
$help_section_url = '#';

get_template_part(
    'template-parts/general/help',
    'section',
    array(
        'title' => $help_section_title,
        'text' => $help_section_text,
        'url' => $help_section_url
    )
);
if ($status === 'projeto') {
    get_template_part('template-parts/general/new-projeto', 'section');
}
if (have_posts()) { ?>
    <div class="row gap-4">
        <?php
        // Load posts loop.
        while (have_posts()) {
            the_post();
            if ($status === 'projeto') {
                get_template_part('template-parts/archive/archive-projetos', 'content');
            } else {
                get_template_part('template-parts/archive/archive-obras', 'content');
            }
        } ?>
        <?php pu_paging_nav(); ?>
    </div>
<?php
} else { ?>
    <div class="row">
        <?php
        // If no content, include the "No posts found" template.
        get_template_part('template-parts/archive/archive', 'not-found'); ?>
    </div>
<?php
}
get_footer();
