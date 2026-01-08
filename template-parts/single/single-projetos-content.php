<?php
$total_effort = 0;
$total_cost = 0;
$pu_projeto_projecao_form_nonce = wp_create_nonce('pu_projeto_projecao_form_nonce');
$post_id = get_the_ID();
$user_id = get_current_user_id();
get_template_part('template-parts/single/projetos-header', 'section');
?>
<form id="form-projecao-projeto" class="form-projecao-projeto needs-validation" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="needs-validation new-projeto-form" enctype="multipart/form-data" novalidate>
    <?php
    get_template_part('template-parts/single/projetos-estagios', 'section');
    get_template_part('template-parts/single/projetos-projecao', 'section');
    get_template_part('template-parts/single/projetos-observacoes', 'section');
    get_template_part('template-parts/single/projetos-actions', 'section');
    ?>
    <input type="hidden" value="pu_projeto_projecao_form" name="action">
    <input type="hidden" value="<?php echo $pu_projeto_projecao_form_nonce; ?>" name="pu_projeto_projecao_form_nonce" id="pu_projeto_projecao_form_nonce">
    <input type="hidden" value="<?php echo $post_id; ?>" name="post_id" id="post_id">
    <input type="hidden" value="<?php echo $user_id; ?>" name="user_id" id="$user_id">
</form>