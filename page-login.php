<?php

/**
 * Template Name: Login
 * 
 * The template for Login
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Partilha Urbana
 */

get_header('login');

/* Start the Loop */
while (have_posts()) :
    the_post();
    get_template_part('template-parts/single/login', 'content');

endwhile; // End of the loop.
get_footer('login');
