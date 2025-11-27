<?php

add_action('toast', 'pu_welcome_toast_message');

function pu_welcome_toast_message()
{
?>
    <div
        class="toast toast-onload align-items-center text-bg-primary border-0"
        role="alert"
        aria-live="assertive"
        aria-atomic="true">
        <div class="toast-body hstack align-items-start gap-6">
            <i class="ti ti-alert-circle fs-6"></i>
            <div>
                <h5 class="text-white fs-3 mb-1"><?php _e('Bem vindo', 'pu'); ?></h5>
                <h6 class="text-white fs-2 mb-0">
                    <?php _e('Lorem ipsum dolor!', 'pu'); ?>
                </h6>
            </div>
            <button
                type="button"
                class="btn-close btn-close-white fs-2 m-0 ms-auto shadow-none"
                data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
<?php
}
