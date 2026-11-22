<?php
$pageTitle = "Détails de la demande";
$currentPage = "voir_demnd_pro";
require_once '../../Controllers/front/includes/session.php';
require_once '../../Controllers/front/utilisateurC.php';


// admin kahaw ichouf page
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../front/login.php');
    exit;
}

$id = (int) ($_GET['id'] ?? 0);
$role = (string) ($_GET['role']);
//importation liste des utilisateurs lkolhom
$u = (new utilisateurC())->affUtilisateur($id);
if($role === 'pharmacie'){
    require_once '../../Controllers/front/pharmacieC.php';
    $d = (new pharmacieC())->affPharmacie($id);
}else if($role === 'medecin'){
    require_once '../../Controllers/front/medecinC.php';
    $d = (new medecinC())->affMedecin($id);
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
            <div class="app-content">
                <div class="container-fluid">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">
                                Demande de <strong><?= htmlspecialchars($u['prenom'] . ' ' . $u['nom'], ENT_QUOTES, 'UTF-8') ?></strong>
                            </h3>
                        </div>
                        <!-- les champs -->
                            <div class="card-body">
                                <!-- les champs norml -->
                                    <div class="row mb-2"><div class="col-sm-4 fw-semibold">Type de compte</div>
                                        <div class="col-sm-8"><?= $role ?></div></div>
                                    <div class="row mb-2"><div class="col-sm-4 fw-semibold">Email</div>
                                        <div class="col-sm-8"><?= htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8') ?></div></div>
                                    <div class="row mb-2"><div class="col-sm-4 fw-semibold">Téléphone</div>
                                        <div class="col-sm-8"><?= htmlspecialchars($u['telephone'] , ENT_QUOTES, 'UTF-8') ?></div></div>
                                    <div class="row mb-2"><div class="col-sm-4 fw-semibold">Date de la demande</div>
                                        <div class="col-sm-8"><?= date('d/m/Y', strtotime($u['date_creation'])) ?></div></div>
                                <hr>
                                <h3>Les Information Pro :</h3>
                                <!-- les champs pro -->
                                    <?php if ($role === 'medecin') { ?>
                                        <div class="row mb-2"><div class="col-sm-4 fw-semibold">Spécialité</div>
                                            <div class="col-sm-8"><?= htmlspecialchars($d['specialite'], ENT_QUOTES, 'UTF-8') ?></div></div>
                                        <div class="row mb-2"><div class="col-sm-4 fw-semibold">N° d'ordre</div>
                                            <div class="col-sm-8"><?= htmlspecialchars($d['num_ordre_medecin'], ENT_QUOTES, 'UTF-8') ?></div></div>
                                        <div class="row mb-2"><div class="col-sm-4 fw-semibold">Horaires de travail</div>
                                            <div class="col-sm-8"><?= htmlspecialchars($d['horaires_travail'], ENT_QUOTES, 'UTF-8') ?></div></div>
                                        <div class="row mb-2"><div class="col-sm-4 fw-semibold">Adresse de la cabinet</div>
                                            <div class="col-sm-8"><?= htmlspecialchars($d['adresse_cabinet'], ENT_QUOTES, 'UTF-8') ?></div></div>
                                    <?php } else { ?>
                                        <div class="row mb-2"><div class="col-sm-4 fw-semibold">Nom de l'officine</div>
                                            <div class="col-sm-8"><?= htmlspecialchars($d['nom_officine'], ENT_QUOTES, 'UTF-8') ?></div></div>
                                        <div class="row mb-2"><div class="col-sm-4 fw-semibold">N° d'autorisation</div>
                                            <div class="col-sm-8"><?= htmlspecialchars($d['num_autorisation'], ENT_QUOTES, 'UTF-8') ?></div></div>
                                        <div class="row mb-2"><div class="col-sm-4 fw-semibold">Horaires d'ouverture</div>
                                            <div class="col-sm-8"><?= htmlspecialchars($d['horaires_ouverture'], ENT_QUOTES, 'UTF-8') ?></div></div>
                                        <div class="row mb-2"><div class="col-sm-4 fw-semibold">Adresse de la pharmacie</div>
                                            <div class="col-sm-8"><?= htmlspecialchars($d['adresse'], ENT_QUOTES, 'UTF-8') ?></div></div>
                                    <?php } ?>
                                    <div class="row mb-2"><div class="col-sm-4 fw-semibold">Ville</div>
                                        <div class="col-sm-8"><?= htmlspecialchars($d['ville'], ENT_QUOTES, 'UTF-8') ?></div></div>
                                    <div class="row mb-2"><div class="col-sm-4 fw-semibold">Coordonnées</div>
                                        <div class="col-sm-8"><?= htmlspecialchars(($d['latitude'] && $d['longitude']) ? $d['latitude'] . ', ' . $d['longitude'] : 'Non renseigné', ENT_QUOTES, 'UTF-8') ?></div></div>
                            </div>
                        <div class="card-footer d-flex gap-2">
                            <a href="demnd_pro.php" class="btn btn-secondary">Retour</a>
                            <form action="demnd_pro.php" method="post">
                                <input type="hidden" name="id_statut" value="<?= $d['id_utilisateur'] ?>">
                                <input type="hidden" name="statut" value="actif">
                                <button type="submit" class="btn btn-success">Accepter</button>
                            </form>
                            <form action="demnd_pro.php" method="post"
                                onsubmit="return confirm('Refuser cette demande et supprimer le compte ?')">
                                <input type="hidden" name="id_supprimer" value="<?= $d['id_utilisateur'] ?>">
                                <button type="submit" class="btn btn-danger">Refuser</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php require_once './includes/footer.php'; ?>
    </div>

    <?php require_once './includes/scripts.php'; ?>
    <script src="../assets/js/liste_user.js"></script>
</body>
</html>