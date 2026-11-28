<?php
$pageTitle = "Liste des campagnes";
$currentPage = "liste_campagne";
require_once '../../Controllers/front/includes/session.php';
require_once '../../Controllers/front/includes/csrf.php';
require_once '../../Controllers/front/campagne_donC.php';

// admin kahaw ichouf page
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../front/login.php');
    exit;
}

$campagneCtrl = new campagne_donC();

// suppression (modal)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_supprimer'])) {
    if (csrf::verifier()) {
        $idSuppr = (int) $_POST['id_supprimer'];
        if ($idSuppr > 0) {
            $campagneCtrl->removeCampagne($idSuppr);
        }
    }
    header('Location: liste_campagne.php?succes=suppr');
    exit;
}

//importation liste des campagnes
$liste = $campagneCtrl->listeCampagne()->fetchAll();
$h = fn($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');

$messages = [
    'ajout'  => "La campagne a été ajoutée.",
    'modif'  => "La campagne a été modifiée.",
    'suppr'  => "La campagne a été supprimée."
];
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
                            <h1 class="mb-0 fs-3">Campagnes de don de sang</h1>
                        </div>
                        <div class="col-sm-6">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb float-sm-end">
                                    <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">campagnes</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::App Content-->
            <div class="app-content">
                <div class="container-fluid">
                    <?php if (isset($_GET['succes']) && isset($messages[$_GET['succes']])): ?>
                        <div class="alert alert-success" role="alert"><?= $messages[$_GET['succes']] ?></div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-4">
                                <!--El Header du tableau-->
                                <div class="card-header">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-12 col-md-4">
                                            <h3 class="card-title">Liste des campagnes</h3>
                                        </div>
                                        <div class="col-12 col-md-8">
                                            <div class="d-flex flex-wrap justify-content-md-end gap-2">
                                                <div class="input-group input-group-sm w-auto">
                                                    <span class="input-group-text"><i class="bi bi-search" aria-hidden="true"></i></span>
                                                    <!--recherche-->
                                                    <input type="search" id="campagne-search" class="form-control"
                                                        placeholder="Rechercher" aria-label="Rechercher une campagne" style="width: 180px" />
                                                </div>
                                                <!-- filtre des statuts -->
                                                <select id="campagne-statut-filter" class="form-select form-select-sm w-auto" aria-label="Filtrer par statut">
                                                    <option value="all" selected>tous les statuts</option>
                                                    <?php foreach (campagne_donC::LIBELLES as $cle => $lib) { ?>
                                                        <option value="<?= $cle ?>"><?= $lib[0] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <a href="add_campagne.php" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-plus-circle me-1" aria-hidden="true"></i>
                                                    Ajouter une campagne
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--El tbleau daffichage-->
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle m-0">
                                            <thead>
                                                <tr>
                                                    <th>Titre</th>
                                                    <th>Lieu</th>
                                                    <th>Date</th>
                                                    <th>Horaires</th>
                                                    <th>Inscrits</th>
                                                    <th>Statut</th>
                                                    <th class="text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($liste)) { ?>
                                                    <tr>
                                                        <td colspan="7" class="text-center">Aucune campagne à afficher</td>
                                                    </tr>
                                                <?php } ?>
                                                <?php foreach ($liste as $c) { ?>
                                                    <tr data-statut="<?= $h($c['statut']) ?>">
                                                        <td class="fw-medium"><?= $h($c['titre']) ?></td>
                                                        <td><?= $h($c['lieu']) ?>, <?= $h($c['ville']) ?></td>
                                                        <td><?= date('d/m/Y', strtotime($c['date_campagne'])) ?></td>
                                                        <td><?= substr($c['heure_debut'], 0, 5) ?> - <?= substr($c['heure_fin'], 0, 5) ?></td>
                                                        <td><?= (int) $c['nb_inscrits'] ?> / <?= (int) $c['nb_places'] ?></td>
                                                        <td>
                                                            <span class="badge text-bg-<?= campagne_donC::LIBELLES[$c['statut']][1] ?>">
                                                                <?= campagne_donC::LIBELLES[$c['statut']][0] ?>
                                                            </span>
                                                        </td>
                                                        <!-- les buttons -->
                                                        <td class="text-end">
                                                            <div class="btn-group btn-group-sm">
                                                                <!-- btn inscrits -->
                                                                <a href="liste_inscription.php?id=<?= (int) $c['id_campagne'] ?>" class="btn btn-sm btn-outline-info"
                                                                    title="voir les inscrits" aria-label="voir les inscrits">
                                                                    <i class="bi bi-people" aria-hidden="true"></i>
                                                                </a>
                                                                <!-- btn modifier -->
                                                                <a href="modif_campagne.php?id=<?= (int) $c['id_campagne'] ?>" class="btn btn-sm btn-outline-rose"
                                                                    title="modifier la campagne" aria-label="modifier la campagne">
                                                                    <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                                                </a>
                                                                <!-- btn suprimer -->
                                                                <button type="button" class="btn btn-outline-danger"
                                                                    data-bs-toggle="modal" data-bs-target="#modal-delete-campagne"
                                                                    aria-label="Supprimer" title="Supprimer"
                                                                    data-id="<?= (int) $c['id_campagne'] ?>"
                                                                    data-titre="<?= $h($c['titre']) ?>">
                                                                    <i class="bi bi-trash" aria-hidden="true"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!--Footer du tableau-->
                                <div class="card-footer clearfix">
                                    <div class="float-start pt-1 fs-7 text-body-secondary">
                                        Total : <?= count($liste) ?> campagne(s)
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--Modal de suppression campagne-->
                    <div class="modal fade" id="modal-delete-campagne" tabindex="-1" aria-labelledby="modal-delete-campagne-label" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modal-delete-campagne-label">Supprimer une campagne</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="mb-0">
                                        Êtes-vous sûr de vouloir supprimer <strong id="titre-supprimer"></strong> ?
                                        Toutes les inscriptions liées à cette campagne seront aussi supprimées.
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <form method="post" action="" class="d-flex gap-2">
                                        <?= csrf::champ() ?>
                                        <input type="hidden" name="id_supprimer" id="id-supprimer" value="">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                    </form>
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
    <script src="../assets/js/don_admin_liste.js"></script>
</body>

</html>
