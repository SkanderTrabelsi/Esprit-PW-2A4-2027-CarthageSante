<?php
$pageTitle = "Liste des utilisateurs";
$currentPage = "liste_user";
require_once '../../Controllers/front/includes/session.php';
require_once '../../Controllers/front/utilisateurC.php';
require_once '../../Controllers/back/statC.php';

// admin kahaw ichouf page
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../front/login.php');
    exit;
} 

// suppression (modal)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_supprimer'])) {
    $idSuppr = (int) $_POST['id_supprimer'];
    if ($idSuppr > 0 ) {
        (new utilisateurC())->removeUtilisateur($idSuppr);
    }
    //pour garder les filtre apres recharge de la page si il ya 
    header('Location: liste_user.php' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''));
    exit;
}

// changement de statut
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ( $_POST['statut']=== 'actif' || $_POST['statut']=== 'suspendu' ) ) {
    $idmodif = (int) $_POST['id_statut'];

    if ($idmodif > 0 && $_POST['statut']=== 'actif' ) {
        (new utilisateurC())->updateUtilisateurStatut($idmodif, "actif");
    }else if ($idmodif > 0 && $_POST['statut']=== 'suspendu' ) {
        (new utilisateurC())->updateUtilisateurStatut($idmodif, "suspendu");
    }
    //pour garder les filtre apres recharge de la page si il ya
    header('Location: liste_user.php' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''));
    exit;
}

//importation liste des utilisateurs lkolhom
$uc = new utilisateurC();
$toutes = $uc->listeUtilisateur()->fetchAll();

//parametres eli fel url
$role = $_GET['role'] ?? '';
$statut = $_GET['statut'] ?? '';
$periode = $_GET['periode'] ?? '';
if (!in_array($role, ['admin', 'patient', 'medecin', 'pharmacie'], true)) {
    $role = '';
}
if (!in_array($statut, ['actif', 'en_attente', 'suspendu'], true)) {
    $statut = '';
}
if ($periode !== 'semaine') {
    $periode = '';
}
$liste = (new statC())->filtrer($toutes, $role, $statut, $periode);

//couleurs et nom mta les role et statut
$roles = [
    'admin' => ['Administrateur', 'danger'],
    'patient' => ['Patient', 'primary'],
    'medecin' => ['Médecin', 'info'],
    'pharmacie' => ['Pharmacie', 'secondary']
];
$statuts = [
    'actif' => ['Actif', 'success'],
    'en_attente' => ['En attente', 'warning'],
    'suspendu' => ['Suspendu', 'danger']
];

//texte du filtre actif
$morceaux = [];
if ($role !== '') {
    $morceaux[] = 'rôle : ' . $roles[$role][0];
}
if ($statut !== '') {
    $morceaux[] = 'statut : ' . $statuts[$statut][0];
}
if ($periode === 'semaine') {
    $morceaux[] = 'créés cette semaine';
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
            <!--begin::App Content Header-->
                <div class="app-content-header">
                    <!--begin::Container--> 
                    <div class="container-fluid">
                        <!--begin::Row-->
                        <div class="row">
                            <div class="col-sm-6">
                                <h1 class="mb-0 fs-3">Liste de tous les utilisateurs</h1>
                                <!-- ken fama filtre actif nafichiwah -->
                                <?php if (!empty($morceaux)) { ?>
                                    <p class="text-body-secondary mb-0">
                                        Filtre : <?= htmlspecialchars(implode(' · ', $morceaux), ENT_QUOTES, 'UTF-8') ?>
                                        - <a href="liste_user.php">réinitialiser le filtre</a>
                                    </p>
                                <?php } ?>
                            </div>
                            <div class="col-sm-6">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb float-sm-end">
                                        <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">utilisateurs</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Container-->
                </div>
            <!--end::App Content Header-->
            <!--begin::App Content-->
                <div class="app-content">
                    <!--begin::Container-->
                    <div class="container-fluid">
                        <!--begin::Row-->
                        <div class="row">
                            <div class="col-12">
                                <!--begin::Card-->
                                <div class="card mb-4">
                                    <!--El Header du tableau-->
                                        <div class="card-header">
                                            <div class="row g-2 align-items-center">
                                                <div class="col-12 col-md-4">
                                                    <h3 class="card-title">Dictionaire des utilisateurs</h3>
                                                </div>
                                                <div class="col-12 col-md-8">
                                                    <div class="d-flex flex-wrap justify-content-md-end gap-2">
                                                        <div class="input-group input-group-sm w-auto">
                                                            <span class="input-group-text">
                                                                <i class="bi bi-search" aria-hidden="true"></i>
                                                            </span>
                                                            <!--recherche-->
                                                            <input type="search" id="user-search" class="form-control"
                                                                placeholder="Search users" aria-label="Search users"
                                                                style="width: 180px" />
                                                        </div>
                                                        <!-- filtre des roles -->
                                                            <select id="user-role-filter" class="form-select form-select-sm w-auto"
                                                                aria-label="Filter by role">
                                                                <option value="all" <?= $role === '' ? 'selected' : '' ?>>tous les rôles</option>
                                                                <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
                                                                <option value="patient" <?= $role === 'patient' ? 'selected' : '' ?>>Patient</option>
                                                                <option value="medecin" <?= $role === 'medecin' ? 'selected' : '' ?>>Medecin</option>
                                                                <option value="pharmacie" <?= $role === 'pharmacie' ? 'selected' : '' ?>>Pharmacie</option>
                                                            </select>
                                                        <a href="add_user.php">
                                                            <button type="button" class="btn btn-sm btn-primary">
                                                                <i class="bi bi-person-plus-fill me-1" aria-hidden="true"> </i>
                                                                Ajouter un Utilisateur
                                                            </button>                                           
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
                                                            <th>nom</th>
                                                            <th>Email</th>
                                                            <th>Role</th>
                                                            <th>Status</th>
                                                            <th>Creation</th>
                                                            <th>Dernière connexion</th>
                                                            <th class="text-end">Actions</th>
                                                        </tr>
                                                    </thead>    
                                                <!-- affichages des utilisateurs --> 
                                                    <tbody>
                                                        <?php if (empty($liste)) { ?>
                                                            <tr>
                                                                <td colspan="7" class="text-center">Aucun utilisateur à afficher</td>
                                                            </tr>
                                                        <?php } ?>
                                                        <?php foreach ($liste as $u) { ?>
                                                            <tr data-role="<?= htmlspecialchars($u['role'], ENT_QUOTES, 'UTF-8') ?>"
                                                                data-statut="<?= htmlspecialchars($u['statut'], ENT_QUOTES, 'UTF-8') ?>">
                                                                <td>    
                                                                    <div class="d-flex align-items-center">
                                                                        <img src="../<?= htmlspecialchars($u['photo_profil_url'], ENT_QUOTES, 'UTF-8') ?>" alt=""
                                                                            class="img-size-32 rounded-circle me-2" />
                                                                        <span class="fw-medium">
                                                                            <?= htmlspecialchars($u['prenom'] . ' ' . $u['nom'], ENT_QUOTES, 'UTF-8') ?>
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                                <td><?= htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8') ?></td>
                                                                <td>
                                                                    <span class="badge text-bg-<?= $roles[$u['role']][1] ?>">
                                                                        <?= $roles[$u['role']][0] ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span class="badge text-bg-<?= $statuts[$u['statut']][1] ?>">
                                                                        <?= $statuts[$u['statut']][0] ?>
                                                                    </span>
                                                                </td>
                                                                <td><?= date('d/m/Y', strtotime($u['date_creation'])) ?></td>
                                                                <td>
                                                                    <span class="fw-medium">
                                                                        <?= $u['derniere_connexion'] ? date('d/m/Y H:i', strtotime($u['derniere_connexion'])) : 'Jamais' ?>
                                                                    </span>
                                                                </td>
                                                                <!-- les buttons -->
                                                                    <td class="text-end"> 
                                                                        <div class="btn-group btn-group-sm"> 
                                                                            <?php if ($u['id_utilisateur'] != $_SESSION['id_utilisateur']) { ?>
                                                                                <!-- btn modifier -->                                                                             
                                                                                    <a href="modif_user.php?id=<?= $u['id_utilisateur'] ?>" class="btn btn-sm btn-outline-rose"
                                                                                        title="modifier le compte" aria-label="modifier le compte">
                                                                                        <i class="bi bi-pencil-square" aria-hidden="true"> </i>
                                                                                    </a>
                                                                                <!-- btn statu -->    
                                                                                    <?php if ($u['statut'] === 'actif') { ?>
                                                                                        <!-- compte actif -->
                                                                                            <form action="" method="post" class="d-inline">
                                                                                                <input type="hidden" name="id_statut" value="<?= $u['id_utilisateur'] ?>">
                                                                                                <input type="hidden" name="statut" value="suspendu">
                                                                                                <button type="submit" class="btn btn-sm btn-outline-warning"
                                                                                                    title="Suspendre" aria-label="Suspendre">
                                                                                                    <i class="bi bi-slash-circle" aria-hidden="true"> </i>
                                                                                                </button>
                                                                                            </form>
                                                                                    <?php } elseif ($u['statut'] === 'suspendu') { ?>
                                                                                        <!-- compte suspendu -->
                                                                                            <form action="" method="post" class="d-inline">
                                                                                                <input type="hidden" name="id_statut" value="<?= $u['id_utilisateur'] ?>">
                                                                                                <input type="hidden" name="statut" value="actif">
                                                                                                <button type="submit" class="btn btn-sm btn-outline-success"
                                                                                                    title="Activer" aria-label="Activer">
                                                                                                    <i class="bi bi-check-circle" aria-hidden="true"> </i>
                                                                                                </button>
                                                                                            </form>
                                                                                    <?php } elseif ($u['statut'] === 'en_attente') { ?>
                                                                                        <!-- compte en attente-->
                                                                                            <a href="demnd_pro.php" class="btn btn-sm btn-outline-info"
                                                                                                title="Voir la demande" aria-label="Voir la demande">
                                                                                                <i class="bi bi-hourglass-split" aria-hidden="true"> </i>
                                                                                            </a>
                                                                                    <?php } ?>
                                                                                <!-- btn suprimer -->                                                                             
                                                                                    <button type="button" class="btn btn-outline-danger"
                                                                                        data-bs-toggle="modal" data-bs-target="#modal-delete-user"
                                                                                        aria-label="Supprimer"    
                                                                                        title="Supprimer"                                                                
                                                                                        data-id="<?= $u['id_utilisateur'] ?>"
                                                                                        data-nom="<?= $u['nom'] ?>"
                                                                                        data-pren="<?= $u['prenom'] ?>"  
                                                                                        >                                                                      
                                                                                        <i class="bi bi-trash" aria-hidden="true"> </i>
                                                                                    </button>                                                                           
                                                                            <?php }else{ ?>
                                                                                <h6>votre compte</h6>  
                                                                            <?php } ?>
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
                                            <!--nb utilisateur total-->
                                            <div class="float-start pt-1 fs-7 text-body-secondary">
                                                Total : <?= count($liste) ?> utilisateur(s)
                                            </div>
                                        </div>
                                    
                                </div>
                                <!--end::Card-->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!--end::Row-->

                        <!--Model de suppresion utilisateur-->
                            <div class="modal fade" id="modal-delete-user" tabindex="-1"
                                aria-labelledby="modal-delete-user-label" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modal-delete-user-label">Supprimer un compte</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="mb-0">
                                                Êtes-vous sûr de vouloir supprimer <strong id="nom-supprimer"></strong> ?
                                                Tout le contenu appartenant à ce compte sera définitivement perdu.
                                            </p>
                                        </div>  
                                        <div class="modal-footer">
                                            <form method="post" action="" class="d-flex gap-2">
                                                <input type="hidden" name="id_supprimer" id="id-supprimer" value="">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-danger">Supprimer</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                    </div>
                    <!--end::Container-->
                </div>
            <!--end::App Content-->
        </main>

        <?php require_once './includes/footer.php'; ?>
    </div>


    <?php require_once './includes/scripts.php'; ?>
    <script src="../assets/js/liste_user.js"></script>
</body>

</html>