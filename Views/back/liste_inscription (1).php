<?php
$pageTitle = "Modifier une campagne";
$currentPage = "modif_campagne";

require_once '../../Controllers/front/includes/session.php';
require_once '../../Controllers/front/includes/csrf.php';
require_once '../../Controllers/front/campagne_donC.php';

// admin kahaw ichouf page
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../front/login.php');
    exit;
}

//la campagne a modifier
$id = (int) ($_GET['id'] ?? 0);
$campagneCtrl = new campagne_donC();
$v = $campagneCtrl->affCampagne($id);
if (!$v) {
    header('Location: liste_campagne.php');
    exit;
}

$erreurs = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf::verifier()) {
        $erreurs[] = "Session expirée, veuillez réessayer.";
    } else {
        $erreurs = $campagneCtrl->updateCampagne($id, $_POST);
        if (empty($erreurs)) {
            header("Location: liste_campagne.php?succes=modif");
            exit;
        }
    }
    $v = array_merge($v, $_POST); // on garde ce que l'admin a saisi
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
                            <h1 class="mb-0 fs-3">Modifier la campagne</h1>
                        </div>
                        <div class="col-sm-6">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb float-sm-end">
                                    <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                                    <li class="breadcrumb-item"><a href="liste_campagne.php">campagnes</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">modifier</li>
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
                                    <?php $modif = true; require './includes/form_campagne.php'; ?>
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
