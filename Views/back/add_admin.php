<?php
$pageTitle = "Ajouter un Admin";
$currentPage = "add_admin";

require_once '../../Controllers/front/includes/session.php';
require_once '../../Controllers/front/utilisateurC.php';

$erreurs = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $utilisateurCtrl = new utilisateurC();
    $erreurs = $utilisateurCtrl->inscription($_POST);
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
                                <h1 class="mb-0 fs-3">Ajouter un admin</h1>
                            </div>
                            <div class="col-sm-6">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb float-sm-end">
                                        <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">ajouter un admin</li>
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
                                        <input type="hidden" id="registerRole" name="role" value="admin">
                                    <!-- champs -->
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
                                                <span class="bi bi-lock-fill"></span>
                                            </div>
                                        </div>
                                        <label class="visually-hidden" for="registerCPassword">ConfirmerMotDePasse</label>
                                        <span class="err "></span>
                                        <div class="input-group mb-3">
                                            <input id="registerCPassword" name="Cpassword" type="password" class="form-control" placeholder="Confirmer le Mot De Passe" />
                                            <div class="input-group-text">
                                                <span class="bi bi-lock-fill"></span>
                                            </div>
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
                                                        
</body>

</html>