<?php

/**
 * The main template file.
 *
 * The template for displaying archive pages
 *
 * @package partilha-urbana
 */

get_header(); ?>

<?php

if (have_posts()) { ?>
    <div class="row">
        <?php
        // Load posts loop.
        while (have_posts()) {
            the_post();
            get_template_part('template-parts/archive/archive', 'content');
        } ?>
    </div>
<?php
} else {
    // If no content, include the "No posts found" template.
    get_template_part('template-parts/content/content-none');
}
get_footer();
