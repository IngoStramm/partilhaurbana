<?php
$pu_lostpassword_nonce = wp_create_nonce('pu_lostpassword_nonce');
// pu_debug($_SESSION);
?>
<div
    class="d-flex align-items-center justify-content-center w-100">
    <div class="row justify-content-center w-100">
        <div class="col-md-8 col-lg-6 col-xxl-3 auth-card">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="text-nowrap logo-img text-center d-block mb-5 w-100">
                        <img
                            src="<?php echo pu_site_logo_url(); ?>"
                            class="dark-logo"
                            alt="Logo-Dark" />
                    </div>
                    <div
                        class="position-relative text-center my-4">
                        <h1><?php the_title(); ?></h1>
                    </div>
                    <form id="lostpassword-form" class="lostpassword-form needs-validation" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="needs-validation new-projeto-form" novalidate>
                        <div class="mb-3">
                            <label
                                for="user_login"
                                class="form-label"><?php _e('Digite seu email', 'pu'); ?></label>
                            <input
                                type="email"
                                class="form-control"
                                id="user_login"
                                name="user_login"
                                required />
                        </div>
                        <div
                            class="d-flex align-items-center justify-content-end mb-4">
                        </div>
                        <?php do_action('pu_login_error_message'); ?>
                        <button
                            type="submit"
                            class="btn btn-success w-100 py-8 mb-4 text-dark">
                            <?php _e('Entrar', 'pu'); ?>
                        </button>
                        <a href="<?php echo pu_get_page_url('login'); ?>" class="d-block text-center"><?php _e('Voltar', 'pu'); ?></a>

                        <input type="hidden" name="pu_lostpassword_nonce" value="<?php echo $pu_lostpassword_nonce ?>" />
                        <input type="hidden" value="pu_lostpassword_form" name="action">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>