<?php

/**
 * Template Name: New User
 * 
 * The template for New User
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Partilha Urbana
 */

get_header('login');

/* Start the Loop */
while (have_posts()) :
    the_post();
    get_template_part('template-parts/single/new-user', 'content');

endwhile; // End of the loop.
get_footer('login');
