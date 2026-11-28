<?php
$pageTitle = "Ajouter une campagne";
$currentPage = "add_campagne";

require_once '../../Controllers/front/includes/session.php';
require_once '../../Controllers/front/includes/csrf.php';
require_once '../../Controllers/front/campagne_donC.php';

// admin kahaw ichouf page
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../front/login.php');
    exit;
}

$erreurs = [];
$v = $_POST;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf::verifier()) {
        $erreurs[] = "Session expirée, veuillez réessayer.";
    } else {
        $erreurs = (new campagne_donC())->ajouterCampagne($_POST);
        if (empty($erreurs)) {
            header("Location: liste_campagne.php?succes=ajout");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<?php require_once './includes/head.php'; ?>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php require_once './includes/header.php'; ?>
        <?php require_once './includes/sidebar.php'; ?>

        <main class="app-main">
            <!--begin::App Header-->
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h1 class="mb-0 fs-3">Ajouter une campagne</h1>
                        </div>
                        <div class="col-sm-6">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb float-sm-end">
                                    <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                                    <li class="breadcrumb-item"><a href="liste_campagne.php">campagnes</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">ajouter</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::App Content-->
            <div class="app-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <!-- affichage des err mta3 php -->
                                    <?php if (!empty($erreurs)): ?>
                                        <div class="alert alert-danger" role="alert">
                                            <ul class="mb-0">
                                                <?php foreach ($erreurs as $err): ?>
                                                    <li><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                    <?php $modif = false; require './includes/form_campagne.php'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <?php require_once './includes/footer.php'; ?>
    </div>

    <?php require_once './includes/scripts.php'; ?>
    <script src="../assets/js/don_admin_form.js"></script>
</body>

</html>
