<aside class="left-sidebar with-vertical">
    <div>
        <div
            class="brand-logo d-flex align-items-center justify-content-between">
            <a
                href="<?php echo get_site_url(); ?>"
                class="text-nowrap logo-img">
                <img
                    src="<?php echo pu_site_logo_url(); ?>"
                    class="dark-logo"
                    alt="Logo-Dark" />
            </a>
            <a
                href="javascript:void(0)"
                class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
                <i class="ti ti-x"></i>
            </a>
        </div>

        <nav class="sidebar-nav scroll-sidebar" data-simplebar>
            <ul id="sidebarnav">

                <?php /* ?>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu"><?php _e('Início', 'pu'); ?></span>
                </li>
                <?php */ ?>

                <?php echo pu_sidebar_menu_items('menu-principal'); ?>

                <li class="nav-small-cap">
                    <i
                        class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu"><?php _e('Ajuda e Suporte', 'pu'); ?></span>
                </li>

                <?php echo pu_sidebar_menu_items('menu-secundario'); ?>

            </ul>
        </nav>
    </div>
</aside>