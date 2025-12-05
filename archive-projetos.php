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
get_template_part('template-parts/general/help', 'section', array('title' => __('Projetos', 'pu'), 'text' => __('Os projetos ajudam a planejar a obra.', 'pu'), 'url' => '#'));
if (have_posts()) { ?>
    <div class="row gap-4">
        <?php
        // Load posts loop.
        while (have_posts()) {
            the_post();
            get_template_part('template-parts/archive/archive-projetos', 'content');
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
