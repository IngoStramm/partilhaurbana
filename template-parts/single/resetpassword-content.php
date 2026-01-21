<?php
$pu_resetpassword_nonce = wp_create_nonce('pu_resetpassword_nonce');
$login = isset($_REQUEST['login']) ? $_REQUEST['login'] : null;
$key = isset($_REQUEST['key']) ? $_REQUEST['key'] : null;
if (!$login) {
    $msg = __('Usuário ausente. Utilize o link enviado por e-mail para acessar esta página.', 'pu');
    $_SESSION['pu_resetpassword_error_message'] = !isset(
        $_SESSION['pu_resetpassword_error_message']
    ) || empty($_SESSION['pu_resetpassword_error_message']) ? $msg : "\n" . $msg;
}

if (!$key) {
    $msg = __('Chave de redefinição de palavra-passe ausente. Utilize o link enviado por e-mail para acessar esta página.', 'pu');
    $_SESSION['pu_resetpassword_error_message'] = !isset(
        $_SESSION['pu_resetpassword_error_message']
    ) || empty($_SESSION['pu_resetpassword_error_message']) ? $msg : "\n" . $msg;
}
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
                    <form id="resetpassword-form" class="resetpassword-form needs-validation" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="needs-validation new-projeto-form" novalidate>
                        <div class="mb-3">
                            <label
                                for="user_pass"
                                class="form-label"><?php _e('Palavra-passe', 'pu'); ?></label>
                            <input
                                type="password"
                                class="form-control"
                                id="user_pass"
                                name="user_pass"
                                required />
                        </div>
                        <div
                            class="d-flex align-items-center justify-content-end mb-4">
                        </div>
                        <?php do_action('pu_login_error_message'); ?>
                        <button
                            type="submit"
                            class="btn btn-success w-100 py-8 mb-4 text-dark">
                            <?php _e('Obter nova palavra-passe', 'pu'); ?>
                        </button>
                        <a href="<?php echo pu_get_page_url('login'); ?>" class="d-block text-center"><?php _e('Voltar', 'pu'); ?></a>

                        <input type="hidden" name="pu_resetpassword_nonce" value="<?php echo $pu_resetpassword_nonce ?>" />
                        <input type="hidden" value="pu_resetpassword_form" name="action">
                        <input type="hidden" name="user_login" value="<?php echo $login; ?>" />
                        <input type="hidden" name="key" value="<?php echo $key; ?>" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>