<div class="row">
    <div class="col-md-12">
        <div class="single-settings-bar">
            <h2 class="single-settings-bar-title"><?php _e('Administrador', 'pu'); ?></h2>
            <a href="<?php echo is_singular('projetos') ? get_permalink() : site_url('projetos'); ?>" class="btn btn-secondary btn-with-icon single-settings-bar-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="24" viewBox="0 0 12 24" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.03592 12L10 17.8335L8.80735 19L2.24695 12.5833C2.08883 12.4286 2 12.2188 2 12C2 11.7812 2.08883 11.5714 2.24695 11.4167L8.80735 5L10 6.16653L4.03592 12Z" fill="white" />
                </svg>
                <?php _e('Voltar', 'pu'); ?>
            </a>
        </div>
    </div>
</div>