<?php

/**
 * The template for displaying all single projetos posts.
 *
 * @package partilha-urbana
 */

get_header();
get_template_part('template-parts/single/single', 'admin-bar');
?>
<?php
get_template_part('template-parts/general/help', 'section', array('title' => __('Projetos!', 'pu'), 'text' => __('Os projetos ajudam a planejar a obra.', 'pu'), 'url' => '#'));
if (have_posts()) { ?>
    <div class="row">
        <?php // Load posts loop.
        while (have_posts()) {
            the_post();
            get_template_part('template-parts/single/single', 'projetos-content');
        } ?>
    </div>
<?php
} else {
    get_template_part('template-parts/content/content-none');
}
get_footer();
