<?php
if (!isset($currentPage)) {
    $currentPage = '';
}

if (!function_exists('navActive')) {
    function navActive($page, $currentPage) {
        return $page === $currentPage ? 'active' : '';
    }
}
?>
<header id="header" class="header fixed-top">

    <div class="branding d-flex align-items-center">

        <div class="container position-relative d-flex align-items-center justify-content-between">

            <a href="index.php" class="logo d-flex align-items-center">
                <h1 class="sitename">Carthage Santé</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="index.php" class="<?php echo navActive('index', $currentPage); ?>">Home</a></li>
                    <li><a href="about.php" class="<?php echo navActive('about', $currentPage); ?>">About</a></li>
                    <li><a href="404.php" class="<?php echo navActive('404', $currentPage); ?>">404</a></li>
                    <li><a href="page_vierge.php" class="<?php echo navActive('page_vierge', $currentPage); ?>">Page vierge</a></li>
                    <li class="dropdown">
                        <a href="#">
                            <span>Dropdown</span>
                            <i class="bi bi-chevron-down toggle-dropdown"></i>
                        </a>
                        <ul>
                            <li><a href="#">Dropdown 1</a></li>
                            <li><a href="#">Dropdown 2</a></li>
                        </ul>
                    </li>
                    <li><a href="contact.php" class="<?php echo navActive('contact', $currentPage); ?>">Contact</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

        </div>

    </div>

</header>