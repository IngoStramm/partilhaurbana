<div class="col-md-12">
    <h2 class="section-title"><?php _e('O que deseja fazer', 'pu'); ?></h2>
    <div class="d-flex gap-5">
        <button id="btn-salvar-projeto" type="submit" class="btn btn-success" disabled>
            <?php _e('Salvar', 'pu'); ?>
        </button>
        <button id="btn-transformar-projeto-em-obra"  type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-transforma-projeto-em-obra" disabled>
            <?php _e('Transformar o projeto em obra', 'pu'); ?>
        </button>

        <input type="hidden" value="<?php echo $user_id; ?>" name="user_id" id="$user_id">
        <button
            type="button"
            id="btn-remove-projeto"
            class="btn btn-danger btn-with-icon btn-remove-projeto"
            data-post-id="<?php echo get_the_ID(); ?>"
            data-user-id="<?php echo get_current_user_id(); ?>"
            data-action="pu_remove_projeto"
            data-nonce="<?php echo wp_create_nonce('pu_remove_projeto_nonce'); ?>"
            disabled>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M18 9L17.16 17.398C17.033 18.671 16.97 19.307 16.68 19.788C16.4257 20.2114 16.0516 20.55 15.605 20.761C15.098 21 14.46 21 13.18 21H10.82C9.541 21 8.902 21 8.395 20.76C7.94805 20.5491 7.57361 20.2106 7.319 19.787C7.031 19.307 6.967 18.671 6.839 17.398L6 9M13.5 15.5V10.5M10.5 15.5V10.5M4.5 6.5H9.115M9.115 6.5L9.501 3.828C9.613 3.342 10.017 3 10.481 3H13.519C13.983 3 14.386 3.342 14.499 3.828L14.885 6.5M9.115 6.5H14.885M14.885 6.5H19.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            <?php _e('Remover Projeto', 'pu'); ?>
        </button>
    </div>
    <div id="form-alert-placeholder"></div>
</div>