<?php
$pageTitle = "Inscrits à la campagne";
$currentPage = "liste_inscription";
require_once '../../Controllers/front/includes/session.php';
require_once '../../Controllers/front/includes/csrf.php';
require_once '../../Controllers/front/campagne_donC.php';
require_once '../../Controllers/front/inscription_donC.php';

// admin kahaw ichouf page
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../front/login.php');
    exit;
}

//la campagne
$id = (int) ($_GET['id'] ?? 0);
$campagne = (new campagne_donC())->affCampagne($id);
if (!$campagne) {
    header('Location: liste_campagne.php');
    exit;
}

$inscriptionCtrl = new inscription_donC();

// changement de statut d'une inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_inscription'])) {
    if (csrf::verifier()) {
        $inscriptionCtrl->updateInscriptionStatut((int) $_POST['id_inscription'], $_POST['statut'] ?? '');
    }
    header('Location: liste_inscription.php?id=' . $id);
    exit;
}

$liste = $inscriptionCtrl->listeInscriptionCampagne($id)->fetchAll();
$h = fn($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="fr">

<?php require_once './includes/head.php'; ?>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php require_once './includes/header.php'; ?>
        <?php require_once './includes/sidebar.php'; ?>

        <main class="app-main">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h1 class="mb-0 fs-3">Inscrits : <?= $h($campagne['titre']) ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb float-sm-end">
                                    <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                                    <li class="breadcrumb-item"><a href="liste_campagne.php">campagnes</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">inscrits</li>
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
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <?= date('d/m/Y', strtotime($campagne['date_campagne'])) ?> -
                                        <?= substr($campagne['heure_debut'], 0, 5) ?> à <?= substr($campagne['heure_fin'], 0, 5) ?> -
                                        <?= $h($campagne['lieu']) ?>, <?= $h($campagne['ville']) ?>
                                    </h3>
                                </div>

                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle m-0">
                                            <thead>
                                                <tr>
                                                    <th>Donneur</th>
                                                    <th>Contact</th>
                                                    <th>Heure</th>
                                                    <th>Don</th>
                                                    <th>Dernier don</th>
                                                    <th>Statut</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($liste)) { ?>
                                                    <tr>
                                                        <td colspan="6" class="text-center">Aucune inscription pour cette campagne</td>
                                                    </tr>
                                                <?php } ?>
                                                <?php foreach ($liste as $i) { ?>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <img src="../<?= $h($i['photo_profil_url']) ?>" alt="" class="img-size-32 rounded-circle me-2" />
                                                                <span class="fw-medium"><?= $h($i['prenom'] . ' ' . $i['nom']) ?></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <?= $h($i['email']) ?><br>
                                                            <small class="text-body-secondary"><?= $h($i['telephone']) ?></small>
                                                        </td>
                                                        <td><?= substr($i['heure_rdv'], 0, 5) ?></td>
                                                        <td><?= $h($i['type_don']) ?><br><small class="text-body-secondary"><?= $h($i['nature_don']) ?></small></td>
                                                        <td>
                                                            <?php if ($i['deja_donne']) { ?>
                                                                <?= date('d/m/Y', strtotime($i['date_dernier_don'])) ?><br>
                                                                <small class="text-body-secondary"><?= $h($i['lieu_dernier_don']) ?></small>
                                                            <?php } else { ?>
                                                                <small class="text-body-secondary">Premier don</small>
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <!-- changement de statut -->
                                                            <form action="" method="post" class="d-flex align-items-center gap-2">
                                                                <?= csrf::champ() ?>
                                                                <input type="hidden" name="id_inscription" value="<?= (int) $i['id_inscription'] ?>">
                                                                <span class="badge text-bg-<?= inscription_donC::LIBELLES[$i['statut']][1] ?>">
                                                                    <?= inscription_donC::LIBELLES[$i['statut']][0] ?>
                                                                </span>
                                                                <select name="statut" class="form-select form-select-sm w-auto" aria-label="Changer le statut" onchange="this.form.submit()">
                                                                    <?php foreach (inscription_donC::LIBELLES as $cle => $lib) { ?>
                                                                        <option value="<?= $cle ?>" <?= $i['statut'] === $cle ? 'selected' : '' ?>><?= $lib[0] ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card-footer clearfix">
                                    <div class="float-start pt-1 fs-7 text-body-secondary">
                                        Total : <?= count($liste) ?> inscription(s) - capacité : <?= (int) $campagne['nb_places'] ?> place(s)
                                    </div>
                                    <a href="liste_campagne.php" class="btn btn-sm btn-outline-secondary float-end">Retour aux campagnes</a>
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
</body>

</html>
