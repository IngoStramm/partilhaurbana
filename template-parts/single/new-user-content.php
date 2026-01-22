<?php
$redirect_to = isset($_GET['redirect_to']) && $_GET['redirect_to'] ? $_GET['redirect_to'] : get_home_url();
$pu_newuser_nonce = wp_create_nonce('pu_newuser_nonce');
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
                            class="mb-0 fs-4 px-3 d-inline-block text-dark z-index-5 position-relative"><?php the_title(); ?></p>
                    </div>
                    <form id="newuser" class="newuser needs-validation" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="needs-validation new-projeto-form" novalidate>
                        <div class="mb-3">
                            <label
                                for="user_name"
                                class="form-label"><?php _e('Nome', 'pu'); ?></label>
                            <input
                                type="text"
                                class="form-control"
                                id="user_name"
                                name="user_name"
                                required />
                        </div>
                        <div class="mb-3">
                            <label
                                for="user_surname"
                                class="form-label"><?php _e('Sobrenome', 'pu'); ?></label>
                            <input
                                type="text"
                                class="form-control"
                                id="user_surname"
                                name="user_surname"
                                required />
                        </div>
                        <div class="mb-3">
                            <label
                                for="user_email"
                                class="form-label"><?php _e('Email (será seu login)', 'pu'); ?></label>
                            <input
                                type="email"
                                class="form-control"
                                id="user_email"
                                name="user_email"
                                required />
                        </div>
                        <div class="mb-4">
                            <label
                                for="user_pass"
                                class="form-label"><?php _e('Palavra passe', 'pu'); ?></label>
                            <input
                                type="password"
                                class="form-control check-pass"
                                id="user_pass"
                                name="user_pass"
                                required />
                        </div>
                        <div class="mb-4">
                            <label
                                for="repeat_pass"
                                class="form-label"><?php _e('Repita sua palavra passe', 'pu'); ?></label>
                            <input
                                type="password"
                                class="form-control check-pass"
                                id="repeat_pass"
                                name="repeat_pass"
                                required />
                            <div class="invalid-feedback"><?php _e('As senhas precisam combinar', 'pu'); ?></div>
                        </div>
                        <div class="mb-7">
                            <label
                                for="empresa"
                                class="form-label"><?php _e('Qual nome da sua empresa de remodelação?', 'pu'); ?></label>
                            <input
                                type="text"
                                class="form-control"
                                id="empresa"
                                name="empresa"
                                required />
                        </div>
                        <?php do_action('pu_login_error_message'); ?>
                        <button
                            type="submit"
                            class="btn btn-secondary w-100 py-8 mb-4">
                            <?php _e('Criar Conta', 'pu'); ?>
                        </button>
                        <a href="<?php echo pu_get_page_url('login'); ?>" class="d-block text-center"><?php _e('Voltar', 'pu'); ?></a>

                        <input type="hidden" name="pu_newuser_nonce" value="<?php echo $pu_newuser_nonce ?>" />
                        <input type="hidden" value="pu_newuser_form" name="action">
                        <input type="hidden" value="<?php echo esc_attr($redirect_to); ?>" name="redirect_to">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>