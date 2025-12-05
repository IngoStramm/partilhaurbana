<?php

/**
 * The template for displaying all single posts.
 *
 * @package partilha-urbana
 */

get_header(); ?>

<?php

if (have_posts()) { ?>
    <div class="row">
        <?php // Load posts loop.
        while (have_posts()) {
            the_post();
            get_template_part('template-parts/single/single', 'content');
        } ?>
    </div>
<?php
} else {
    get_template_part('template-parts/content/content-none');
}
get_footer();
