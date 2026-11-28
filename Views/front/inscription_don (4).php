<?php
$pageTitle = "Don de sang";
$currentPage = "campagnes_don";
require_once '../../Controllers/front/includes/session.php';
require_once '../../Controllers/front/campagne_donC.php';

$campagneCtrl = new campagne_donC();
$campagnes = $campagneCtrl->listeCampagneOuverte()->fetchAll();

//campagnes ou l'utilisateur connecte est deja inscrit
$mesCampagnes = [];
if (!empty($_SESSION['id_utilisateur'])) {
    $mesCampagnes = $campagneCtrl->campagnesInscrites($_SESSION['id_utilisateur']);
}
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
                            <h1 class="heading-title">Don de sang</h1>
                            <p class="mb-0">
                                Trouvez une campagne de don de sang près de chez vous et réservez votre créneau.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="breadcrumbs">
                <div class="container">
                    <ol>
                        <li><a href="index.php">Home</a></li>
                        <li class="current">Don de sang</li>
                    </ol>
                </div>
            </nav>
        </div>

        <!-- Campagnes -->
        <section id="campagnes" class="contact section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <!-- recherche -->
                <div class="row justify-content-center mb-4">
                    <div class="col-md-6">
                        <input type="search" id="campagne-search" class="form-control"
                            placeholder="Rechercher par ville, lieu ou titre" aria-label="Rechercher une campagne">
                    </div>
                </div>

                <?php if (empty($campagnes)) { ?>
                    <p class="text-center">Aucune campagne ouverte pour le moment.</p>
                <?php } ?>

                <div class="row g-4">
                    <?php foreach ($campagnes as $c) {
                        $restantes = max(0, (int) $c['nb_places'] - (int) $c['nb_inscrits']);
                        $pourcent = $c['nb_places'] > 0 ? min(100, round($c['nb_inscrits'] / $c['nb_places'] * 100)) : 0;
                        $dejaInscrit = in_array($c['id_campagne'], $mesCampagnes);
                    ?>
                        <div class="col-lg-4 col-md-6 campagne-carte">
                            <div class="contact-form-card h-100 d-flex flex-column">
                                <h2><?= $h($c['titre']) ?></h2>
                                <p class="mb-1"><i class="bi bi-geo-alt me-2"></i><?= $h($c['lieu']) ?>, <?= $h($c['ville']) ?></p>
                                <p class="mb-1"><i class="bi bi-calendar3 me-2"></i><?= date('d/m/Y', strtotime($c['date_campagne'])) ?></p>
                                <p class="mb-1"><i class="bi bi-clock me-2"></i><?= substr($c['heure_debut'], 0, 5) ?> - <?= substr($c['heure_fin'], 0, 5) ?></p>
                                <p class="mb-3"><i class="bi bi-building me-2"></i><?= $h($c['organisateur']) ?></p>

                                <?php if (!empty($c['description'])) { ?>
                                    <p class="mb-3"><?= $h($c['description']) ?></p>
                                <?php } ?>

                                <!-- places -->
                                <div class="mt-auto">
                                    <small class="text-secondary"><?= $restantes ?> place(s) restante(s) sur <?= (int) $c['nb_places'] ?></small>
                                    <div class="progress mb-3" role="progressbar" aria-valuenow="<?= $pourcent ?>" aria-valuemin="0" aria-valuemax="100" style="height: 6px;">
                                        <div class="progress-bar bg-danger" style="width: <?= $pourcent ?>%"></div>
                                    </div>

                                    <?php if (empty($_SESSION['id_utilisateur'])) { ?>
                                        <a href="login.php" class="btn btn-submit w-auto px-4">Se connecter pour s'inscrire</a>
                                    <?php } elseif ($dejaInscrit) { ?>
                                        <span class="badge text-bg-success">Déjà inscrit(e)</span>
                                        <a href="mes_dons.php" class="ms-2">Voir mes dons</a>
                                    <?php } elseif ($restantes === 0) { ?>
                                        <span class="badge text-bg-danger">Complet</span>
                                    <?php } else { ?>
                                        <a href="inscription_don.php?id_campagne=<?= (int) $c['id_campagne'] ?>" class="btn btn-submit w-auto px-4">S'inscrire</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
    </main>

    <?php require_once './includes/footer.php'; ?>
    <?php require_once './includes/scripts.php'; ?>
    <script src="../assets/js/don_campagnes.js"></script>
</body>

</html>
