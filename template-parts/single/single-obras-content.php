<?php
$post_id = get_the_ID();
$post_types = pu_obra_posts_types();
get_template_part('template-parts/single/obras-header', 'section', array('post_id' => $post_id));
get_template_part('template-parts/general/select-obra', 'content', array('post_id' => $post_id));
