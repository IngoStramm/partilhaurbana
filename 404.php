<?php

/**
 * The template for displaying 404 pages (Not Found)
 */
get_header(); ?>
<div class="row justify-content-center w-100">
    <div class="col-lg-4">
        <div class="text-center">
            <img src="<?php echo PU_URL; ?>/assets/images/backgrounds/errorimg.svg" alt="modernize-img" class="img-fluid" width="500">
            <h1 class="fw-semibold mb-7 fs-9"><?php _e('Ooops!!!', 'pu'); ?></h1>
            <h4 class="fw-semibold mb-7"><?php _e('A página que você tentou acessar não foi encontrada.', 'pu'); ?></h4>
            <a class="btn btn-primary" href="<?php echo get_site_url(); ?>" role="button"><?php _e('Voltar', 'pu'); ?></a>
        </div>
    </div>
</div>

<?php get_footer();
