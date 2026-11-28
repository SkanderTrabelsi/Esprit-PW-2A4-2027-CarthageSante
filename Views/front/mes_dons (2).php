<?php
$pageTitle = "Inscription au don de sang";
$currentPage = "campagnes_don";
require_once '../../Controllers/front/includes/session.php';
require_once '../../Controllers/front/includes/csrf.php';
require_once '../../Controllers/front/campagne_donC.php';
require_once '../../Controllers/front/inscription_donC.php';

// compte conecter kahaw ichouf page
if (empty($_SESSION['id_utilisateur'])) {
    header('Location: ./login.php');
    exit;
}

//la campagne choisie
$idCampagne = (int) ($_GET['id_campagne'] ?? 0);
$campagne = (new campagne_donC())->affCampagne($idCampagne);
if (!$campagne) {
    header('Location: ./404.php');
    exit;
}

$erreurs = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf::verifier()) {
        $erreurs[] = "Session expirée, veuillez réessayer.";
    } else {
        $erreurs = (new inscription_donC())->inscrire($_SESSION['id_utilisateur'], $campagne, $_POST);
        if (empty($erreurs)) {
            header('Location: mes_dons.php?succes=1');
            exit;
        }
    }
}

$h = fn($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
$dejaDonne = $_POST['deja_donne'] ?? '';
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
                            <h1 class="heading-title">Inscription au don de sang</h1>
                            <p class="mb-0">
                                <?= $h($campagne['titre']) ?> - <?= date('d/m/Y', strtotime($campagne['date_campagne'])) ?>
                                (<?= $h($campagne['lieu']) ?>, <?= $h($campagne['ville']) ?>)
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="breadcrumbs">
                <div class="container">
                    <ol>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="campagnes_don.php">Don de sang</a></li>
                        <li class="current">Inscription</li>
                    </ol>
                </div>
            </nav>
        </div>

        <section id="inscription" class="contact section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row justify-content-center">
                    <div class="col-lg-8">

                        <!-- messages d'erreur php -->
                        <?php if (!empty($erreurs)): ?>
                            <div class="alert alert-danger mb-4" role="alert">
                                <ul class="mb-0">
                                    <?php foreach ($erreurs as $err): ?>
                                        <li><?= $h($err) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <div class="contact-form-card">
                            <h2>Formulaire d'inscription</h2>
                            <p>Réservez votre créneau pendant les horaires de la campagne
                                (<?= substr($campagne['heure_debut'], 0, 5) ?> - <?= substr($campagne['heure_fin'], 0, 5) ?>).</p>

                            <form action="" method="post" id="formu"
                                data-debut="<?= substr($campagne['heure_debut'], 0, 5) ?>"
                                data-fin="<?= substr($campagne['heure_fin'], 0, 5) ?>">
                                <?= csrf::champ() ?>
                                <div class="row gx-4 gy-3">

                                    <div class="col-md-6 champ">
                                        <label class="form-label" for="typeDon">Type de don</label>
                                        <select id="typeDon" name="type_don" class="form-control">
                                            <option value="">Choisir un type de don</option>
                                            <?php foreach (inscription_donC::TYPES as $t): ?>
                                                <option value="<?= $h($t) ?>" <?= (($_POST['type_don'] ?? '') === $t) ? 'selected' : '' ?>><?= $h($t) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="err text-danger"></small>
                                    </div>

                                    <div class="col-md-6 champ">
                                        <label class="form-label" for="natureDon">Nature du don</label>
                                        <select id="natureDon" name="nature_don" class="form-control">
                                            <option value="">Choisir la nature du don</option>
                                            <?php foreach (inscription_donC::NATURES as $n): ?>
                                                <option value="<?= $h($n) ?>" <?= (($_POST['nature_don'] ?? '') === $n) ? 'selected' : '' ?>><?= $h($n) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="err text-danger"></small>
                                    </div>

                                    <div class="col-md-6 champ">
                                        <label class="form-label" for="dateRdv">Date du rendez-vous</label>
                                        <!-- la date est celle de la campagne -->
                                        <input type="date" id="dateRdv" name="date_rdv" class="form-control" readonly
                                            value="<?= $h($campagne['date_campagne']) ?>">
                                        <small class="err text-danger"></small>
                                    </div>

                                    <div class="col-md-6 champ">
                                        <label class="form-label" for="heureRdv">Heure du rendez-vous</label>
                                        <input type="time" id="heureRdv" name="heure_rdv" class="form-control"
                                            min="<?= substr($campagne['heure_debut'], 0, 5) ?>" max="<?= substr($campagne['heure_fin'], 0, 5) ?>"
                                            value="<?= $h($_POST['heure_rdv'] ?? '') ?>">
                                        <small class="err text-danger"></small>
                                    </div>

                                    <div class="col-12 champ">
                                        <span class="form-label d-block">Avez-vous déjà donné votre sang ?</span>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="deja_donne" id="dejaOui" value="1" <?= $dejaDonne === '1' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="dejaOui">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="deja_donne" id="dejaNon" value="0" <?= $dejaDonne === '0' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="dejaNon">Non</label>
                                        </div>
                                        <small class="err text-danger d-block"></small>
                                    </div>

                                    <!-- visible seulement si deja donne -->
                                    <div id="champsDernierDon" class="col-12 d-none">
                                        <div class="row gx-4 gy-3">
                                            <div class="col-md-6 champ">
                                                <label class="form-label" for="dateDernierDon">Date du dernier don</label>
                                                <input type="date" id="dateDernierDon" name="date_dernier_don" class="form-control"
                                                    max="<?= date('Y-m-d') ?>" value="<?= $h($_POST['date_dernier_don'] ?? '') ?>">
                                                <small class="err text-danger"></small>
                                            </div>
                                            <div class="col-md-6 champ">
                                                <label class="form-label" for="lieuDernierDon">Lieu du dernier don</label>
                                                <input type="text" id="lieuDernierDon" name="lieu_dernier_don" class="form-control" maxlength="255"
                                                    value="<?= $h($_POST['lieu_dernier_don'] ?? '') ?>">
                                                <small class="err text-danger"></small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-submit w-auto px-5">Valider l'inscription</button>
                                        <a href="campagnes_don.php" class="ms-3">Annuler</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php require_once './includes/footer.php'; ?>
    <?php require_once './includes/scripts.php'; ?>
    <script src="../assets/js/don_inscription.js"></script>
</body>

</html>
