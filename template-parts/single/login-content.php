<?php
$redirect_to = isset($_GET['redirect_to']) && $_GET['redirect_to'] ? $_GET['redirect_to'] : get_home_url();
$pu_loginform_nonce = wp_create_nonce('pu_loginform_nonce');
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
                        <h1><?php _e('Seja bem-vindo!', 'pu'); ?></h1>
                        <p
                            class="mb-0 fs-4 px-3 d-inline-block text-dark z-index-5 position-relative"><?php _e('Pronto para remodelar?', 'pu'); ?></p>
                    </div>
                    <form id="loginform" class="loginform needs-validation" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="needs-validation new-projeto-form" novalidate>
                        <div class="mb-3">
                            <label
                                for="email"
                                class="form-label"><?php _e('Email', 'pu'); ?></label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="log"
                                required />
                        </div>
                        <div class="mb-4">
                            <label
                                for="password"
                                class="form-label"><?php _e('Palavra passe', 'pu'); ?></label>
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="pwd"
                                required />
                        </div>
                        <div
                            class="d-flex align-items-center justify-content-end mb-4">
                            <a
                                class="text-success fw-medium"
                                href="<?php echo pu_get_page_url('lostpassword'); ?>"><?php _e('Esqueceu a palavra passe?', 'pu'); ?></a>
                        </div>
                        <?php do_action('pu_login_error_message'); ?>
                        <button
                            type="submit"
                            class="btn btn-success w-100 py-8 mb-4 text-dark">
                            <?php _e('Entrar', 'pu'); ?>
                        </button>
                        <a href="<?php echo pu_get_page_url('newuser'); ?>" class="btn btn-secondary py-8 w-100"><?php _e('Não tem conta? Crie aqui.', 'pu'); ?></a>

                        <input type="hidden" name="pu_loginform_nonce" value="<?php echo $pu_loginform_nonce ?>" />
                        <input type="hidden" value="pu_login_form" name="action">
                        <input type="hidden" value="<?php echo esc_attr($redirect_to); ?>" name="redirect_to">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>