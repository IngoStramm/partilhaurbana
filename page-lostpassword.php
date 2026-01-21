<?php

/**
 * Template Name: Lost Password
 * 
 * The template for Lost Password
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Partilha Urbana
 */

get_header('login');

/* Start the Loop */
while (have_posts()) :
    the_post();
    get_template_part('template-parts/single/lostpassword', 'content');

endwhile; // End of the loop.
get_footer('login');
