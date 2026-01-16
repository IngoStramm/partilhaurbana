<?php
$post_id = get_the_ID();
$post_thumbnail_url = get_the_post_thumbnail_url($post_id, 'full');
$post_thumbnail_url = $post_thumbnail_url ? $post_thumbnail_url : pu_get_option('pu_default_image');
$status_do_projeto = get_the_terms($post_id, 'status-do-projeto');
$status_do_projeto = $status_do_projeto ? $status_do_projeto[0] : null;
$proprietario_do_projeto = get_post_meta($post_id, 'dono_do_projeto', true);
$preco_projeto = get_post_meta($post_id, 'preco', true);
?>
<div class="projeto-item <?php echo is_single() ? 'mb-4' : ''; ?>">
    <div class="projeto-item-content">
        <img src="<?php echo $post_thumbnail_url; ?>" alt="<?php the_title(); ?>" class="projeto-item-img">
        <div class="projeto-item-info">
            <h2 class="projeto-item-title"><?php the_title(); ?></h2>
            <ul class="projeto-item-info-list">
                <?php if ($proprietario_do_projeto) { ?>
                    <li>
                        <strong><?php _e('Proprietário do imóvel:', 'pu'); ?></strong> <?php echo $proprietario_do_projeto; ?>
                    </li>
                <?php } ?>
                <?php if ($preco_projeto) { ?>
                    <li>
                        <strong><?php _e('Preço de compra:', 'pu'); ?></strong> <?php echo pu_format_money($preco_projeto) . '€'; ?>
                    </li>
                <?php } ?>
                <?php if ($status_do_projeto) { ?>
                    <li>
                        <strong><?php _e('Estágio do imóvel:', 'pu'); ?></strong> <span class="projeto-status <?php echo $status_do_projeto->slug; ?>"><?php echo $status_do_projeto->name; ?></span>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <?php if (!is_single()) { ?>
            <a class="projeto-item-btn btn btn-secondary btn-with-icon" href="<?php echo get_permalink(); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <path d="M2.45625 11.472C1.81875 10.644 1.5 10.2293 1.5 9C1.5 7.77 1.81875 7.35675 2.45625 6.528C3.729 4.875 5.8635 3 9 3C12.1365 3 14.271 4.875 15.5438 6.528C16.1813 7.3575 16.5 7.77075 16.5 9C16.5 10.23 16.1813 10.6432 15.5438 11.472C14.271 13.125 12.1365 15 9 15C5.8635 15 3.729 13.125 2.45625 11.472Z" stroke="white" stroke-width="1.5" />
                    <path d="M11.25 9C11.25 9.59674 11.0129 10.169 10.591 10.591C10.169 11.0129 9.59674 11.25 9 11.25C8.40326 11.25 7.83097 11.0129 7.40901 10.591C6.98705 10.169 6.75 9.59674 6.75 9C6.75 8.40326 6.98705 7.83097 7.40901 7.40901C7.83097 6.98705 8.40326 6.75 9 6.75C9.59674 6.75 10.169 6.98705 10.591 7.40901C11.0129 7.83097 11.25 8.40326 11.25 9Z" stroke="white" stroke-width="1.5" />
                </svg>
                <?php _e('Ver', 'pu'); ?>
            </a>
        <?php } ?>
    </div>
    <?php if (!is_single()) { ?>
        <div class="projeto-item-footer">
            <h3 class="projeto-item-footer-title"><?php _e('Previsão de lucro líquido:', 'pu'); ?></h3>
            <h4 class="projeto-item-footer-preco"><?php echo pu_previsao_lucro_liquido($post_id); ?></h4>
        </div>
    <?php } ?>
</div>