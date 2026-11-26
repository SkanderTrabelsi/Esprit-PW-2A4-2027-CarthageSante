<?php
$pageTitle = "Ajouter un Utilisateur";
$currentPage = "add_user";

require_once '../../Controllers/front/includes/session.php';
require_once '../../Controllers/front/utilisateurC.php';

// admin kahaw ichouf page
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../front/login.php');
    exit;
} 

$erreurs = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $utilisateurCtrl = new utilisateurC();
    $erreurs = $utilisateurCtrl->inscription($_POST, $_FILES, true);
    if (empty($erreurs)) {
        header("Location: liste_user.php");
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
            <!--begin::App Header-->
                <div class="app-content-header">
                    <!--begin::Container-->
                    <div class="container-fluid">
                        <!--begin::Row-->
                        <div class="row">
                            <div class="col-sm-6">
                                <h1 class="mb-0 fs-3">Ajouter un Utilisateur</h1>
                            </div>
                            <div class="col-sm-6">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb float-sm-end">
                                        <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">ajouter un utilisateur</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Container-->
                </div>
            
            <!--begin::App Content-->
                <div class="app-content">
                    <!--begin::Container-->
                    <div class="container-fluid">
                        <!--begin::Row-->
                        <div class="row">
                            <div class="col-12">
                                <!--begin::Card-->
                                <div class="card mb-4">
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
                                    <form action="" method="post" id="formu" enctype="multipart/form-data">
                                        
                                    <!-- champs -->
                                        <!-- champs en commun pour tous les utilisateurs -->
                                            <label class="visually-hidden" for="registerName">Nom</label>
                                            <span class="err "></span>
                                            <div class="input-group mb-3">
                                                <input id="registerName" name="nom" type="text" class="form-control" placeholder="Nom" 
                                                    value="<?= htmlspecialchars($_POST['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                                                />
                                                <div class="input-group-text">
                                                    <span class="bi bi-person"></span>
                                                </div>
                                            </div>
                                            <label class="visually-hidden" for="registerPreNom">Prenom</label>
                                            <span class="err "></span>
                                            <div class="input-group mb-3">
                                                <input id="registerPreNom" name="prenom" type="text" class="form-control" placeholder="Prenom" 
                                                        value="<?= htmlspecialchars($_POST['prenom'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                />
                                                <div class="input-group-text">
                                                    <span class="bi bi-person"></span>
                                                </div>
                                            </div>
                                            <label class="visually-hidden" for="registerEmail">Email</label>
                                            <span class="err "></span>
                                            <div class="input-group mb-3">
                                                <input id="registerEmail" name="email" type="text" class="form-control" placeholder="Email" 
                                                        value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                />
                                                <div class="input-group-text">
                                                    <span class="bi bi-envelope"></span>
                                                </div>
                                            </div>
                                            <label class="visually-hidden" for="registerPassword">MotDePasse</label>
                                            <span class="err "></span>
                                            <div class="input-group mb-3">
                                                <input id="registerPassword" name="password" type="password" class="form-control" placeholder="Mot De Passe"/>
                                                <div class="input-group-text">
                                                    <span id="iconm" class="bi bi-lock-fill"></span>
                                                </div>
                                            </div>
                                            <label class="visually-hidden" for="registerCPassword">ConfirmerMotDePasse</label>
                                            <span class="err "></span>
                                            <div class="input-group mb-3">
                                                <input id="registerCPassword" name="Cpassword" type="password" class="form-control" placeholder="Confirmer le Mot De Passe" />
                                                <div class="input-group-text">
                                                    <span id="iconn" class="bi bi-lock-fill"></span>
                                                </div>
                                            </div>
                                            <!-- aff mot de pass -->
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="affmdp">
                                                    <label class="form-check-label" for="affmdp">Afficher le mot de passe</label>
                                                </div>
                                            <label class="visually-hidden" for="registerTelephone">Telephone</label>
                                            <span class="err "></span>
                                            <div class="input-group mb-3">
                                                <input id="registerTelephone" name="telephone" type="text" class="form-control" placeholder="Votre numero de telephone" 
                                                        value="<?= htmlspecialchars($_POST['telephone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                />
                                                <div class="input-group-text">
                                                    <span class="bi bi-phone"></span>
                                                </div>
                                            </div>
                                            <label class="visually-hidden" for="registerPhoto">photoDeProfil</label>
                                            <span class="err "></span>
                                            <div class="input-group mb-3">
                                                <input id="registerPhoto" name="photo" type="file" class="form-control" placeholder="photo de profil" />
                                                <div class="input-group-text">
                                                    <span class="bi bi-image"></span>
                                                </div>
                                            </div>
                                            <label class="visually-hidden" for="registerRole">Type de compte</label>
                                            <span class="err "></span>
                                            <div class="input-group mb-3">
                                                <select id="registerRole" name="role" class="form-select">
                                                    <option value="">Choisir un type de compte</option>
                                                    <option value="patient" <?php if (($_POST["role"] ?? "") == "patient") echo "selected"; ?>>Patient</option>
                                                    <option value="medecin" <?php if (($_POST["role"] ?? "") == "medecin") echo "selected"; ?>>Médecin</option>
                                                    <option value="pharmacie" <?php if (($_POST["role"] ?? "") == "pharmacie") echo "selected"; ?>>Pharmacie</option>
                                                    <option value="admin" <?php if (($_POST["role"] ?? "") == "admin") echo "selected"; ?>>Administrateur</option>
                                                </select>
                                                <div class="input-group-text">
                                                    <span class="bi bi-person-badge"></span>
                                                </div>
                                            </div>
                                        <!-- champs pour admin -->
                                            <div id="champsAdmin" class="d-none">
                                                <h5>Niveau acces de l'admin</h5>
                                                <label class="visually-hidden" for="registerNiv">niveau_acces</label>
                                                <span class="err "></span>
                                                <div class="input-group mb-3">
                                                    <select id="registerNiv" name="niv" class="form-select">
                                                        <option value="">Choisir un niveau d'acces</option>
                                                        <option value="super" <?php if (($_POST["niv"] ?? "") == "super") echo "selected"; ?>>Super moderateur</option>
                                                        <option value="moderateur" <?php if (($_POST["niv"] ?? "") == "moderateur") echo "selected"; ?>>Moderateur</option>
                                                    </select>
                                                    <div class="input-group-text">
                                                        <span class="bi bi-person-badge"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- champs en commun pour medecin et pharmacie -->
                                            <div id="champsPro" class="d-none"> 
                                                <h5>Champs pour comptes pro</h5>
                                                <label class="visually-hidden" for="registerNumPro">NumPro</label>
                                                <span class="err "></span>
                                                <div class="input-group mb-3">
                                                    <input id="registerNumPro" name="num_pro" type="text" class="form-control" placeholder="N° professionnel" 
                                                        value="<?= htmlspecialchars($_POST['num_pro'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                    />
                                                    <div class="input-group-text"><span class="bi bi-hash"></span></div>
                                                </div>

                                                <label class="visually-hidden" for="registerAdresse">Adresse</label>
                                                <span class="err "></span>
                                                <div class="input-group mb-3">
                                                    <input id="registerAdresse" name="adresse" type="text" class="form-control" placeholder="Adresse" 
                                                        value="<?= htmlspecialchars($_POST['adresse'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                    />
                                                    <div class="input-group-text"><span class="bi bi-geo-alt"></span></div>
                                                </div>

                                                <label class="visually-hidden" for="registerVille">Ville</label>
                                                <span class="err "></span>
                                                <div class="input-group mb-3">
                                                    <input id="registerVille" name="ville" type="text" class="form-control" placeholder="Ville" 
                                                        value="<?= htmlspecialchars($_POST['ville'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                    />
                                                    <div class="input-group-text"><span class="bi bi-building"></span></div>
                                                </div>

                                                <label class="visually-hidden" for="registerHoraires">Horaires</label>
                                                <span class="err "></span>
                                                <div class="input-group mb-3">
                                                    <input id="registerHoraires" name="horaires" type="text" class="form-control" placeholder="Horaires (ex : Lun-Ven 08h-18h)" 
                                                        value="<?= htmlspecialchars($_POST['horaires'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                    />
                                                    <div class="input-group-text"><span class="bi bi-clock"></span></div>
                                                </div>

                                                <label class="visually-hidden" for="registerLatitude">Latitude</label>
                                                <span class="err "></span>
                                                <div class="input-group mb-3">
                                                    <input id="registerLatitude" name="latitude" type="text" class="form-control" placeholder="Latitude (ex : 36.806389)" 
                                                        value="<?= htmlspecialchars($_POST['latitude'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                    />
                                                    <div class="input-group-text"><span class="bi bi-compass"></span></div>
                                                </div>

                                                <label class="visually-hidden" for="registerLongitude">Longitude</label>
                                                <span class="err "></span>
                                                <div class="input-group mb-3">
                                                    <input id="registerLongitude" name="longitude" type="text" class="form-control" placeholder="Longitude (ex : 10.181667)" 
                                                        value="<?= htmlspecialchars($_POST['longitude'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                    />
                                                    <div class="input-group-text"><span class="bi bi-compass"></span></div>
                                                </div>
                                            </div>
                                        <!-- champs pro pour medecin -->
                                            <div id="champsMedecin" class="d-none">
                                                <label class="visually-hidden" for="registerSpecialite">Spécialité</label>
                                                <span class="err "></span>
                                                <div class="input-group mb-3">
                                                    <input id="registerSpecialite" name="specialite" type="text" class="form-control" placeholder="Generaliste" 
                                                            value="<?= htmlspecialchars($_POST['specialite'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                    />
                                                    <div class="input-group-text"><span class="bi bi-heart-pulse"></span></div>
                                                </div>
                                            </div>
                                        <!-- champs pro pour pharmacie -->
                                            <div id="champsPharmacie" class="d-none">
                                                <label class="visually-hidden" for="registerNomOfficine">Nom de l'officine</label>
                                                <span class="err "></span>
                                                <div class="input-group mb-3">
                                                    <input id="registerNomOfficine" name="nom_officine" type="text" class="form-control" placeholder="Nom de la pharmacie" 
                                                            value="<?= htmlspecialchars($_POST['nom_officine'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                    />
                                                    <div class="input-group-text"><span class="bi bi-shop"></span></div>
                                                </div>
                                            </div>
                                    <div >
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary">Cree le compte admin</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                                <!--end::Card-->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!--end::Row-->                    
                    </div>
                    <!--end::Container-->
                </div>


        </main>

        <?php require_once './includes/footer.php'; ?>
    </div>


    <?php require_once './includes/scripts.php'; ?>
    <script src="../assets/js/register.js"></script>
    
                                                        
</body>

</html>