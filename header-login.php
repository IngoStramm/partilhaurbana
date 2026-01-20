<?php

/**
 * The header.
 *
 * This is the template that displays the login <head> section and everything up until main.
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

<body <?php body_class('login-page'); ?>>
    <?php do_action('toast'); ?>
    <?php do_action('preloader'); ?>
    <div id="main-wrapper" class="auth-customizer-none">
        <div
            class="position-relative overflow-hidden radial-gradient min-vh-100 w-100 d-flex align-items-center justify-content-center">