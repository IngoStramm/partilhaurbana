<?php

/**
 * Template Name: Novo Projeto
 * 
 * The template for Novo Projeto
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Partilha Urbana
 */

get_header();
if (pu_user_can_access()) {
    get_template_part('template-parts/single/single', 'settings-bar');

    /* Start the Loop */
    while (have_posts()) :
        the_post();
        get_template_part('template-parts/single/single-projetos', 'settings');

    endwhile; // End of the loop.
} else {
    get_template_part('template-parts/single/single', 'access-denied');
}
get_footer();
