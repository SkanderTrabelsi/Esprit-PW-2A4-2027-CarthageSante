<?php
$pageTitle = "Liste des utilisateurs";
$currentPage = "liste_user";
require_once '../../Controllers/front/includes/session.php';
require_once '../../Controllers/front/utilisateurC.php';

// admin kahaw ichouf page
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../front/login.php');
    exit;
} 

$id = (int) ($_GET['id'] ?? 0);
//importation donne du compte
$utilisateurCtrl = new utilisateurC();
$user = $utilisateurCtrl->affUtilisateur($id);

//importation doonne pro
if ($user['role'] === 'medecin') {
    require_once '../../Controllers/front/medecinC.php';
    $medecinCtrl = new medecinC();
    $pro = $medecinCtrl->affMedecin($id);
}else if ($user['role'] === 'pharmacie') {
    require_once '../../Controllers/front/pharmacieC.php';
    $pharmacieCtrl = new pharmacieC();
    $pro = $pharmacieCtrl->affPharmacie($id);
}else if($user['role'] === 'admin'){
    require_once '../../Controllers/back/adminC.php';
    $adminCtrl = new adminC();
    $pro = $adminCtrl->affAdmin($id);
}

$erreurs = [];
$succes = isset($_GET['ok']) ? "Le compte a été mis à jour." : '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //les info commun
    $erreurs = $utilisateurCtrl->updateUtilisateur($id, $_POST, $_FILES);
    //info haseb el role
    if (empty($erreurs)) {
        if ($user['role'] === 'medecin') {
            $erreurs = $medecinCtrl->updateMedecin($pro['id_medecin'], $_POST);
        }elseif ($user['role'] === 'pharmacie') {
            $erreurs = $pharmacieCtrl->updatePharmacie($pro['id_pharmacie'], $_POST);
        }elseif ($user['role'] === 'admin') {
            //recup niv acce admin
            $niv = trim($_POST['niv'] ?? '');
            //pti controle de saisie
            if ($niv !== 'super' && $niv !== 'moderateur') {
                $erreurs[] = "Veuillez choisir un niveau d'accès.";
            } else {
                $adminCtrl->updateAdmin($pro['id_admin'], new admin($id, $niv));
            }
        }
    }
    //on recharge la page (evite le renvoi du formulaire avec F5)et recharger les nouv info
    if (empty($erreurs)) {
        header("Location: modif_user.php?id=" . $id . "&ok=1");
        exit;
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
            <!--begin::App Content Header-->
                <div class="app-content-header">
                    <!--begin::Container--> 
                    <div class="container-fluid">
                        <!--begin::Row-->
                        <div class="row">
                            <div class="col-sm-6">
                                <h1 class="mb-0 fs-3">Modifier Un utilisateur</h1>
                            </div>
                            <div class="col-sm-6">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb float-sm-end">
                                        <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Modifier un utilisateur</li>
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
                                <!-- messages err et susc -->
                                    <!-- msg err -->
                                        <?php if (!empty($erreurs)): ?>
                                            <div class="alert alert-danger mb-4" role="alert">
                                                <ul class="mb-0">
                                                    <?php foreach ($erreurs as $err): ?>
                                                        <li><?= htmlspecialchars((string) $err, ENT_QUOTES, 'UTF-8') ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    <!-- msg suces --> 
                                        <?php if (!empty($succes)): ?>
                                            <div class="alert alert-success mb-4" role="alert">
                                                <?= htmlspecialchars((string) $succes, ENT_QUOTES, 'UTF-8') ?>
                                            </div>
                                        <?php endif; ?>
                                <form action="" method="post" id="formu" enctype="multipart/form-data">
                                    <!-- champs en commun pour tous les utilisateurs -->
                                        <label class="visually-hidden" for="registerName">Nom</label>Nom
                                        <span class="err "></span>
                                        <div class="input-group mb-3">
                                            <input id="registerName" name="nom" type="text" class="form-control" placeholder="Nom" 
                                                value="<?= htmlspecialchars($user['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                                            />
                                            <div class="input-group-text">
                                                <span class="bi bi-person"></span>
                                            </div>
                                        </div>
                                        <label class="visually-hidden" for="registerPreNom">Prenom</label>Prenom
                                        <span class="err "></span>
                                        <div class="input-group mb-3">
                                            <input id="registerPreNom" name="prenom" type="text" class="form-control" placeholder="Prenom" 
                                                    value="<?= htmlspecialchars($user['prenom'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                            />
                                            <div class="input-group-text">
                                                <span class="bi bi-person"></span>
                                            </div>
                                        </div>
                                        <label class="visually-hidden" for="registerEmail">Email</label>Email
                                        <span class="err "></span>
                                        <div class="input-group mb-3">
                                            <input id="registerEmail" name="email" type="text" class="form-control" placeholder="Email" 
                                                    value="<?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                            />
                                            <div class="input-group-text">
                                                <span class="bi bi-envelope"></span>
                                            </div>
                                        </div>
                                        <label class="visually-hidden" for="registerTelephone">Telephone</label>Telephone
                                        <span class="err "></span>
                                        <div class="input-group mb-3">
                                            <input id="registerTelephone" name="telephone" type="text" class="form-control" placeholder="Votre numero de telephone" 
                                                    value="<?= htmlspecialchars($user['telephone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                            />
                                            <div class="input-group-text">
                                                <span class="bi bi-phone"></span>
                                            </div>
                                        </div>
                                        <label class="visually-hidden" for="registerPhoto">photoDeProfil</label>photoDeProfil
                                        <span class="err "></span>
                                        <div class="input-group mb-3">
                                            <input id="registerPhoto" name="photo" type="file" class="form-control" placeholder="photo de profil" />
                                            <div class="input-group-text">
                                                <span class="bi bi-image"></span>
                                            </div>
                                            <div class="avatar-preview-box position-relative flex-shrink-0">
                                                <img src="../<?php echo($user['photo_profil_url']) ?>" alt="Photo de profil" class="avatar-preview-img rounded-circle">
                                            </div>
                                        </div>
                                    <!-- champs en commun pour medecin et pharmacie -->
                                        <?php if($user['role'] === 'medecin' || $user['role'] === 'pharmacie'){ ?>                                
                                            <div id="champsPro" >
                                                <h5>Champs pour comptes pro</h5>
                                                <label class="visually-hidden" for="registerNumPro">NumPro</label>NumPro
                                                <span class="err "></span>
                                                <div class="input-group mb-3">
                                                    <input id="registerNumPro" name="num_pro" type="text" class="form-control" placeholder="N° professionnel" 
                                                        value="<?= htmlspecialchars($pro['num_ordre_medecin'] ?? $pro['num_autorisation'], ENT_QUOTES, 'UTF-8') ?>"
                                                    />
                                                    <div class="input-group-text"><span class="bi bi-hash"></span></div>
                                                </div>

                                                <label class="visually-hidden" for="registerAdresse">Adresse</label>Adresse
                                                <span class="err "></span>
                                                <div class="input-group mb-3">
                                                    <input id="registerAdresse" name="adresse" type="text" class="form-control" placeholder="Adresse" 
                                                        value="<?= htmlspecialchars($pro['adresse_cabinet'] ?? $pro['adresse'], ENT_QUOTES, 'UTF-8') ?>"
                                                    />
                                                    <div class="input-group-text"><span class="bi bi-geo-alt"></span></div>
                                                </div>

                                                <label class="visually-hidden" for="registerVille">Ville</label>Ville
                                                <span class="err "></span>
                                                <div class="input-group mb-3">
                                                    <input id="registerVille" name="ville" type="text" class="form-control" placeholder="Ville" 
                                                        value="<?= htmlspecialchars($pro['ville'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                    />
                                                    <div class="input-group-text"><span class="bi bi-building"></span></div>
                                                </div>

                                                <label class="visually-hidden" for="registerHoraires">Horaires</label>Horaires
                                                <span class="err "></span>
                                                <div class="input-group mb-3">
                                                    <input id="registerHoraires" name="<?= $user['role'] === 'medecin' ? 'horaires_travail' : 'horaires_ouverture' ?>"
                                                        type="text" class="form-control" placeholder="Horaires (ex : Lun-Ven 08h-18h)" 
                                                        value="<?= htmlspecialchars($pro['horaires_travail'] ?? $pro['horaires_ouverture'], ENT_QUOTES, 'UTF-8') ?>"
                                                    />
                                                    <div class="input-group-text"><span class="bi bi-clock"></span></div>
                                                </div>

                                                <label class="visually-hidden" for="registerLatitude">Latitude</label>Latitude
                                                <span class="err "></span>
                                                <div class="input-group mb-3">
                                                    <input id="registerLatitude" name="latitude" type="text" class="form-control" placeholder="Latitude (ex : 36.806389)" 
                                                        value="<?= htmlspecialchars($pro['latitude'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                    />
                                                    <div class="input-group-text"><span class="bi bi-compass"></span></div>
                                                </div>

                                                <label class="visually-hidden" for="registerLongitude">Longitude</label>Longitude
                                                <span class="err "></span>
                                                <div class="input-group mb-3">
                                                    <input id="registerLongitude" name="longitude" type="text" class="form-control" placeholder="Longitude (ex : 10.181667)" 
                                                        value="<?= htmlspecialchars($pro['longitude'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                    />
                                                    <div class="input-group-text"><span class="bi bi-compass"></span></div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <!-- Medecin uniquement -->
                                        <?php if($user['role'] === 'medecin'){ ?>
                                            <div id="champsMedecin" >
                                                <label class="visually-hidden" for="registerSpecialite">Spécialité</label>Spécialité
                                                <span class="err "></span>
                                                <div class="input-group mb-3">
                                                    <input id="registerSpecialite" name="specialite" type="text" class="form-control" placeholder="Generaliste" 
                                                            value="<?= htmlspecialchars($pro['specialite'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                    />
                                                    <div class="input-group-text"><span class="bi bi-heart-pulse"></span></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check mt-4">
                                                    <input type="checkbox" class="form-check-input" id="profil-teleconsultation"
                                                        name="teleconsultation_active" value="1"
                                                        <?= !empty($pro['teleconsultation_active']) ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="profil-teleconsultation">Téléconsultation</label>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <!-- Pharmacie uniquement -->
                                        <?php if($user['role'] === 'pharmacie'){ ?>
                                            <div id="champsPharmacie">
                                                <label class="visually-hidden" for="registerNomOfficine">Nom de l'officine</label>Nom de l'officine
                                                <span class="err "></span>
                                                <div class="input-group mb-3">
                                                    <input id="registerNomOfficine" name="nom_officine" type="text" class="form-control" placeholder="Nom de la pharmacie" 
                                                            value="<?= htmlspecialchars($pro['nom_officine'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                    />
                                                    <div class="input-group-text"><span class="bi bi-shop"></span></div>
                                                </div>
                                            </div>
                                            <div >
                                                <span class="form-label d-block">Pharmacie de jour ou nuit</span>
                                                <input type="radio" class="form-check-input" id="profil-type_jour"
                                                    name="type_ouverture" value="jour"
                                                    <?= $pro['type_ouverture'] ==='jour' ? 'checked' : '' ?>
                                                >Jour
                                                <input type="radio" class="form-check-input" id="profil-type_nuit"
                                                    name="type_ouverture" value="nuit"
                                                    <?= $pro['type_ouverture'] === 'nuit' ? 'checked' : '' ?>
                                                >Nuit
                                                <div class="input-group mb-3">
                                                    <input type="checkbox" class="form-check-input" id="profil-est_de_garde"
                                                        name="est_de_garde" value="1"
                                                        <?= !empty($pro['est_de_garde']) ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="profil-est_de_garde">Pharmacie de garde</label>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <!-- champs pour admin -->
                                        <?php if($user['role'] === 'admin'){ ?>
                                            <div id="champsAdmin" >
                                                <h5>Niveau acces de l'admin</h5>
                                                <label class="visually-hidden" for="registerNiv">niveau_acces</label>
                                                <span class="err "></span>
                                                <div class="input-group mb-3">
                                                    <select id="registerNiv" name="niv" class="form-select">
                                                        <option value="">Choisir un niveau d'acces</option>
                                                        <option value="super" <?php if (($pro["niveau_acces"] ?? "") == "super") echo "selected"; ?>>Super moderateur</option>
                                                        <option value="moderateur" <?php if (($pro["niveau_acces"] ?? "") == "moderateur") echo "selected"; ?>>Moderateur</option>
                                                    </select>
                                                    <div class="input-group-text">
                                                        <span class="bi bi-person-badge"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <!-- btn -->
                                        <div >
                                            <div class="d-grid gap-2">
                                                <button type="submit" class="btn btn-primary">Modifier le compte</button>
                                            </div>
                                        </div>
                                </form>
                                <!--end::Card-->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Container-->
                </div>
            <!--end::App Content-->
        </main>

        <?php require_once './includes/footer.php'; ?>
    </div>


    <?php require_once './includes/scripts.php'; ?>
</body>

</html>