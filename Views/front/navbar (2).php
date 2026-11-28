<?php
$pageTitle = "Mes dons de sang";
$currentPage = "mes_dons";
require_once '../../Controllers/front/includes/session.php';
require_once '../../Controllers/front/includes/csrf.php';
require_once '../../Controllers/front/inscription_donC.php';

// compte conecter kahaw ichouf page
if (empty($_SESSION['id_utilisateur'])) {
    header('Location: ./login.php');
    exit;
}

$inscriptionCtrl = new inscription_donC();

// annulation d'une inscription (modal)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_annuler'])) {
    if (csrf::verifier()) {
        $inscriptionCtrl->annulerInscription((int) $_POST['id_annuler'], $_SESSION['id_utilisateur']);
    }
    header('Location: mes_dons.php?annule=1');
    exit;
}

$liste = $inscriptionCtrl->listeInscriptionUtilisateur($_SESSION['id_utilisateur'])->fetchAll();
$h = fn($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="fr">

<?php require_once './includes/head.php'; ?>

<body class="index-page">
    <?php require_once './includes/spinner.php'; ?>
    <?php require_once './includes/navbar.php'; ?>

    <main class="main">
        <!-- Page Title -->
        <div class="page-title page-title-compact">
            <div class="heading">
                <div class="container">
                    <div class="row d-flex justify-content-center text-center">
                        <div class="col-lg-8">
                            <h1 class="heading-title">Mes dons de sang</h1>
                            <p class="mb-0">Retrouvez vos inscriptions aux campagnes de don de sang.</p>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="breadcrumbs">
                <div class="container">
                    <ol>
                        <li><a href="index.php">Home</a></li>
                        <li class="current">Mes dons</li>
                    </ol>
                </div>
            </nav>
        </div>

        <section id="mes-dons" class="contact section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <?php if (isset($_GET['succes'])): ?>
                    <div class="alert alert-success mb-4" role="alert">Inscription enregistrée, elle est en attente de confirmation.</div>
                <?php endif; ?>
                <?php if (isset($_GET['annule'])): ?>
                    <div class="alert alert-success mb-4" role="alert">Votre demande d'annulation a été traitée.</div>
                <?php endif; ?>

                <div class="contact-form-card">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                        <h2 class="mb-0">Mes inscriptions</h2>
                        <a href="campagnes_don.php" class="btn btn-submit w-auto px-4">Voir les campagnes</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Campagne</th>
                                    <th>Date</th>
                                    <th>Heure</th>
                                    <th>Don</th>
                                    <th>Statut</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($liste)) { ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Vous n'avez aucune inscription</td>
                                    </tr>
                                <?php } ?>
                                <?php foreach ($liste as $i) { ?>
                                    <tr>
                                        <td>
                                            <strong><?= $h($i['titre']) ?></strong><br>
                                            <small class="text-secondary"><?= $h($i['lieu']) ?>, <?= $h($i['ville']) ?></small>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($i['date_rdv'])) ?></td>
                                        <td><?= substr($i['heure_rdv'], 0, 5) ?></td>
                                        <td><?= $h($i['type_don']) ?><br><small class="text-secondary"><?= $h($i['nature_don']) ?></small></td>
                                        <td>
                                            <span class="badge text-bg-<?= inscription_donC::LIBELLES[$i['statut']][1] ?>">
                                                <?= inscription_donC::LIBELLES[$i['statut']][0] ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <?php if ($i['statut'] === 'en_attente' || $i['statut'] === 'confirmee') { ?>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal" data-bs-target="#modal-annuler-don"
                                                    data-id="<?= (int) $i['id_inscription'] ?>"
                                                    data-titre="<?= $h($i['titre']) ?>">
                                                    Annuler
                                                </button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!--Modal d'annulation-->
    <div class="modal fade" id="modal-annuler-don" tabindex="-1" aria-labelledby="modal-annuler-don-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-annuler-don-label">Annuler l'inscription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">
                        Voulez-vous vraiment annuler votre inscription à <strong id="titre-annuler"></strong> ?
                    </p>
                </div>
                <div class="modal-footer">
                    <form method="post" action="" class="d-flex gap-2">
                        <?= csrf::champ() ?>
                        <input type="hidden" name="id_annuler" id="id-annuler" value="">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Retour</button>
                        <button type="submit" class="btn btn-danger">Oui, annuler</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php require_once './includes/footer.php'; ?>
    <?php require_once './includes/scripts.php'; ?>
    <script src="../assets/js/don_mes_dons.js"></script>
</body>

</html>
