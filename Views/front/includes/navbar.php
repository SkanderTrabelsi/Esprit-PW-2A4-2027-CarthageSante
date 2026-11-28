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
                    <li><a href="contact.php" class="<?php echo navActive('contact', $currentPage); ?>">Contact</a></li>
                    <!-- drop down user -->
                        <?php if (! empty($_SESSION['id_utilisateur'])): ?>
                            <li class="dropdown nav-user-dropdown">
                                <a href="#" class="nav-user-trigger <?php echo navActive('profile', $currentPage); ?>">
                                    <span class="nav-user-badge">
                                        <img src="../<?php echo($_SESSION['photo']) ?>" alt="<?php echo($_SESSION['nom']) ?>" class="nav-user-avatar">
                                        <span class="nav-user-name"><?php echo($_SESSION['prenom'] .' '. $_SESSION['nom']) ?></span>
                                    </span>
                                    <i class="bi bi-chevron-down toggle-dropdown"></i>
                                </a>
                                <ul class="nav-user-menu">
                                    <li class="nav-user-header">
                                        <img src="../<?php echo($_SESSION['photo']) ?>" alt="<?php echo($_SESSION['nom']) ?>" class="nav-user-avatar-lg">
                                        <div>
                                            <strong><?php echo($_SESSION['prenom'] .' '. $_SESSION['nom']) ?></strong>
                                            <small>Membre depuis le <?php echo( date('d/m/Y', strtotime($_SESSION['date_creation']))) ?></small>
                                        </div>
                                    </li>
                                    <li><a href="profile.php?id=<?php echo($_SESSION['id_utilisateur']) ?>" class="<?php echo navActive('profile', $currentPage); ?>"><i class="bi bi-person me-2"></i>Profil</a></li>
                                    <?php if ($_SESSION['role'] === 'admin'): ?>
                                        <li><a href="../back/index.php"><i class="bi bi-speedometer2 me-2"></i>Backoffice</a></li>
                                    <?php endif; ?>
                                    <li><a href="logout.php" class="nav-logout-link"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="login.php" class="nav-login-pill <?php echo navActive('login', $currentPage); ?>">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>Connexion
                                </a>
                            </li>
                        <?php endif; ?>
                    <!-- wfe drop down -->
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

        </div>

    </div>

</header>