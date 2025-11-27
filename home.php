<?php

/**
 * Template Name: Homepage
 * 
 * The template for Home Page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Partilha Urbana
 */

get_header();

/* Start the Loop */
while (have_posts()) :
    the_post();
    get_template_part('template-parts/content/home/home', 'content');

endwhile; // End of the loop.

get_footer();
