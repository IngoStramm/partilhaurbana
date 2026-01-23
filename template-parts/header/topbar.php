<?php
$user_id = get_current_user_id();
$empresa = get_user_meta($user_id, 'empresa', true);
?>
<!--  Header Start -->
<header class="topbar">
    <div class="with-vertical">
        <!-- ---------------------------------- -->
        <!-- Start Vertical Layout Header -->
        <!-- ---------------------------------- -->
        <nav class="navbar navbar-expand-lg p-0">
            <ul class="navbar-nav">
                <li
                    class="nav-item nav-icon-hover-bg rounded-circle ms-n2">
                    <a
                        class="nav-link sidebartoggler"
                        id="headerCollapse"
                        href="javascript:void(0)">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
            </ul>
            <div class="d-block d-lg-none py-4">
                <a
                    href="<?php echo get_site_url(); ?>"
                    class="text-nowrap logo-img">
                    <img
                        src="<?php echo pu_site_logo_url(); ?>"
                        class="dark-logo"
                        alt="Logo-Dark" />
                </a>
            </div>
            <a
                class="navbar-toggler nav-icon-hover-bg rounded-circle p-0 mx-0 border-0"
                href="javascript:void(0)"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="ti ti-dots fs-7"></i>
            </a>
            <div
                class="collapse navbar-collapse justify-content-end"
                id="navbarNav">
                <div
                    class="d-flex align-items-center justify-content-between">
                    <ul
                        class="navbar-nav flex-row ms-auto align-items-center justify-content-center">

                        <!-- ------------------------------- -->
                        <!-- start profile Dropdown -->
                        <!-- ------------------------------- -->
                        <li class="nav-item dropdown">
                            <a
                                class="nav-link pe-0"
                                href="javascript:void(0)"
                                id="drop1"
                                aria-expanded="false">
                                <div
                                    class="d-flex align-items-center">
                                    <div class="user-profile-name">
                                        <?php echo pu_get_user_name(); ?>
                                    </div>
                                    <div
                                        class="user-profile-img">
                                        <img
                                            src="<?php echo PU_URL; ?>/assets/images/profile/user-1.jpg"
                                            class="rounded-circle"
                                            width="35"
                                            height="35"
                                            alt="modernize-img" />
                                    </div>
                                </div>
                            </a>
                            <div
                                class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                aria-labelledby="drop1">
                                <div
                                    class="profile-dropdown position-relative"
                                    data-simplebar>
                                    <div class="py-3 px-7 pb-0">
                                        <h5
                                            class="mb-0 fs-5 fw-semibold">
                                            <?php _e('Perfil do usuário', 'pu'); ?>
                                        </h5>
                                    </div>
                                    <div
                                        class="d-flex align-items-center py-9 mx-7 border-bottom">
                                        <?php /* ?>
                                        <img
                                            src="<?php echo PU_URL; ?>/assets/images/profile/user-1.jpg"
                                            class="rounded-circle"
                                            width="80"
                                            height="80"
                                            alt="modernize-img" /><?php */ ?>
                                        <div class="ms-3">
                                            <h5
                                                class="mb-1 fs-3">
                                                <?php echo pu_get_user_name(); ?>
                                            </h5>
                                            <?php if ($empresa) { ?>
                                                <span
                                                    class="mb-1 d-block"><?php echo get_user_meta($user_id, 'empresa', true); ?></span>
                                            <?php } ?>
                                            <p
                                                class="mb-0 d-flex align-items-center gap-2">
                                                <i
                                                    class="ti ti-mail fs-4"></i>
                                                <?php echo get_user_email(); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <?php /* ?>
                                    <div class="message-body">
                                        <a
                                            href="./main/page-user-profile.html"
                                            class="py-8 px-7 mt-8 d-flex align-items-center">
                                            <span
                                                class="d-flex align-items-center justify-content-center text-bg-light rounded-1 p-6">
                                                <img
                                                    src="<?php echo PU_URL; ?>/assets/images/svgs/icon-account.svg"
                                                    alt="modernize-img"
                                                    width="24"
                                                    height="24" />
                                            </span>
                                            <div
                                                class="w-100 ps-3">
                                                <h6
                                                    class="mb-1 fs-3 fw-semibold lh-base">
                                                    My Profile
                                                </h6>
                                                <span
                                                    class="fs-2 d-block text-body-secondary">Account
                                                    Settings</span>
                                            </div>
                                        </a>
                                        <a
                                            href="./main/app-email.html"
                                            class="py-8 px-7 d-flex align-items-center">
                                            <span
                                                class="d-flex align-items-center justify-content-center text-bg-light rounded-1 p-6">
                                                <img
                                                    src="<?php echo PU_URL; ?>/assets/images/svgs/icon-inbox.svg"
                                                    alt="modernize-img"
                                                    width="24"
                                                    height="24" />
                                            </span>
                                            <div
                                                class="w-100 ps-3">
                                                <h6
                                                    class="mb-1 fs-3 fw-semibold lh-base">
                                                    My Inbox
                                                </h6>
                                                <span
                                                    class="fs-2 d-block text-body-secondary">Messages &
                                                    Emails</span>
                                            </div>
                                        </a>
                                        <a
                                            href="./main/app-notes.html"
                                            class="py-8 px-7 d-flex align-items-center">
                                            <span
                                                class="d-flex align-items-center justify-content-center text-bg-light rounded-1 p-6">
                                                <img
                                                    src="<?php echo PU_URL; ?>/assets/images/svgs/icon-tasks.svg"
                                                    alt="modernize-img"
                                                    width="24"
                                                    height="24" />
                                            </span>
                                            <div
                                                class="w-100 ps-3">
                                                <h6
                                                    class="mb-1 fs-3 fw-semibold lh-base">
                                                    My Task
                                                </h6>
                                                <span
                                                    class="fs-2 d-block text-body-secondary">To-do and
                                                    Daily
                                                    Tasks</span>
                                            </div>
                                        </a>
                                    </div>
                                    <?php */ ?>
                                    <div
                                        class="d-grid py-4 px-7 pt-8">
                                        <a
                                            href="<?php echo wp_logout_url(get_permalink()); ?>"
                                            class="btn btn-outline-primary">Log Out</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- ------------------------------- -->
                        <!-- end profile Dropdown -->
                        <!-- ------------------------------- -->
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>
<!--  Header End -->