<?php

/**
 * The header.
 *
 * This is the template that displays all of the <head> section and everything up until main.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package partilha-urbana
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>
    <?php pu_the_html_classes(); ?> dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
    <!-- Required meta tags -->
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php do_action('toast'); ?>
    <?php do_action('preloader'); ?>
    <div id="main-wrapper">

        <?php get_sidebar(); ?>

        <div class="page-wrapper">
            <?php get_template_part('template-parts/header/topbar'); ?>
            <div class="body-wrapper">
                <div class="container-fluid">