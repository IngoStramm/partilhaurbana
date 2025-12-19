<?php
$total_effort = 0;
$total_cost = 0;
get_template_part('template-parts/single/projetos-header', 'section');
?>
<form id="form-projecao-projeto" class="form-projecao-projeto needs-validation" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="needs-validation new-projeto-form" enctype="multipart/form-data" novalidate>
    <?php
    get_template_part('template-parts/single/projetos-estagios', 'section');
    get_template_part('template-parts/single/projetos-projecao', 'section');
    get_template_part('template-parts/single/projetos-observacoes', 'section');
    get_template_part('template-parts/single/projetos-actions', 'section');
    ?>
</form>