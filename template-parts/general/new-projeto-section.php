<?php
$newprojeto_url = pu_get_page_url('newprojeto');
?>
<div class="row mb-4">
    <div class="col-md-12 d-md-flex justify-content-md-end">
        <a href="<?php echo $newprojeto_url; ?>" class="btn btn-warning btn-with-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle-plus">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M4.929 4.929a10 10 0 1 1 14.141 14.141a10 10 0 0 1 -14.14 -14.14zm8.071 4.071a1 1 0 1 0 -2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0 -2h-2v-2z" />
            </svg>
            <?php _e('Novo projeto', 'pu'); ?>
        </a>
    </div>
</div>