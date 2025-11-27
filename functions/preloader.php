<?php

add_action('preloader', 'pu_preloader');

function pu_preloader()
{
?>
    <!-- Preloader -->
    <div class="preloader">
        <img
            src="<?php echo PU_URL; ?>/assets/images/logos/favicon.png"
            alt="loader"
            class="lds-ripple img-fluid" />
    </div>
<?php
}
