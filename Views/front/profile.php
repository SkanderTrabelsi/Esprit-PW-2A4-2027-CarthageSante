<?php
$pageTitle = "Profile";
$currentPage = "profile";
require_once '../../Controllers/front/includes/session.php';
require_once '../../Controllers/front/utilisateurC.php';

// compte conecter kahaw ichouf page 
if (empty($_SESSION['id_utilisateur'])) {
    header('Location: ./login.php');
    exit;
} 

//importation donne du compte
$utilisateurCtrl = new utilisateurC();
$user = $utilisateurCtrl->affUtilisateur($_SESSION['id_utilisateur']);

//importation doonne pro
if ($user['role'] === 'medecin') {
    require_once '../../Controllers/front/medecinC.php';
    $medecinCtrl = new medecinC();
    $pro = $medecinCtrl->affMedecin($_SESSION['id_utilisateur']);
}else if ($user['role'] === 'pharmacie') {
    require_once '../../Controllers/front/pharmacieC.php';
    $pharmacieCtrl = new pharmacieC();
    $pro = $pharmacieCtrl->affPharmacie($_SESSION['id_utilisateur']);
}
 
//couleurs et nom mta les role
$roles = [
    'admin' => ['Administrateur', 'danger'],
    'patient' => ['Patient', 'primary'],
    'medecin' => ['Médecin', 'info'],
    'pharmacie' => ['Pharmacie', 'secondary']
];

$erreurs = [];
$succes = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //idha ken mis a jour info perso
        if($_POST['form_type'] === 'perso'){
            $erreurs = $utilisateurCtrl->updateUtilisateur($_SESSION['id_utilisateur'], $_POST, $_FILES);
            if (empty($erreurs)) {
                $user = $utilisateurCtrl->affUtilisateur($_SESSION['id_utilisateur']); // met les infos à jour
                $succes = "Informations personnelles mises à jour.";
            }
        }
    //idha ken mis a jour info pro
        if($_POST['form_type'] === 'pro'){
            //ken compte medecin
                if($_SESSION['role'] === 'medecin'){
                    $erreurs = $medecinCtrl->updateMedecin($pro['id_medecin'], $_POST);
                    if (empty($erreurs)) {
                        $pro = $medecinCtrl->affMedecin($_SESSION['id_utilisateur']); // met les infos à jour
                        $succes = "Informations professionnelles mises à jour.";
                    }
                }
            //ken compte pharmacie
                if($_SESSION['role'] === 'pharmacie'){
                    $erreurs = $pharmacieCtrl->updatePharmacie($pro['id_pharmacie'], $_POST);
                    if (empty($erreurs)) {
                        $pro = $pharmacieCtrl->affPharmacie($_SESSION['id_utilisateur']); // met les infos à jour
                        $succes = "Informations professionnelles mises à jour.";
                    }
                }
        }
    //idha mise a jour mot de passe 
        elseif ($_POST['form_type'] === 'securite'){
            $erreurs = $utilisateurCtrl->updateMotDePasse($_SESSION['id_utilisateur'], $_POST);
            if (empty($erreurs)) {
                $succes = "Mot de passe mis à jour.";
            }
        }
    //idha mise a jour 2fa
        elseif ($_POST['form_type'] === '2fa') {
            $erreurs = $utilisateurCtrl->update2FA($_SESSION['id_utilisateur'], $_POST);
            if (empty($erreurs)) {
                $user = $utilisateurCtrl->affUtilisateur($_SESSION['id_utilisateur']);
                $succes = "Double authentification mise à jour.";
            }
        }
    //idha supresion compte
        elseif ($_POST['form_type'] === 'supprimer_compte') {
            (new utilisateurC())->removeUtilisateur($_SESSION['id_utilisateur']);
            header('Location: logout.php');
            exit;
        }
}
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
                                <h1 class="heading-title">Mon profil</h1>
                                <p class="mb-0">
                                    Gérez vos informations personnelles
                                    <?php if($_SESSION['role'] !== 'patient'){ ?>
                                        et vos informations professionnelles.
                                    <?php } ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <nav class="breadcrumbs">
                    <div class="container">
                        <ol>
                            <li><a href="index.php">Home</a></li>
                            <li class="current">Mon profil</li>
                        </ol>
                    </div>
                </nav>
            </div>
        <!-- Profile Section -->
        <section id="profil" class="profile contact section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row g-4">
                    <!-- Menu de gauche -->
                        <div class="col-lg-3">
                            <div class="profile-card">
                                <div class="text-center mb-4">
                                    <img src="../<?php echo($_SESSION['photo']) ?>" alt="Photo de profil"
                                        class="profile-avatar">
                                </div>
                              <div class="profile-identity">
                                    <strong class="profile-name"><?php echo($_SESSION['prenom'] . ' ' . $_SESSION['nom']) ?></strong>
                                    <small class="profile-since">
                                        <i class="bi bi-calendar3" aria-hidden="true"></i>
                                        Membre depuis le <?= date('d/m/Y', strtotime($_SESSION['date_creation'])) ?>
                                    </small>
                                    <small>
                                        <span class="badge text-bg-<?= $roles[$_SESSION['role']][1] ?>">
                                            <?php if($_SESSION['role'] === 'admin'){
                                                echo( ($_SESSION['niveau_acces'] === 'super') ? 'Super Moderateur' : 'Moderateur' );
                                            }else{
                                                echo ($_SESSION['role']);
                                            }?>
                                        </span>
                                    </small> 
                                </div>
                                <div class="profile-nav nav nav-pills flex-column" id="profile-nav" role="tablist">
                                    <a href="#infos-perso" class="nav-link active" data-bs-toggle="pill" role="tab"
                                        aria-selected="true">
                                        <i class="bi bi-person" aria-hidden="true"></i> Informations personnelles
                                    </a>
                                    <?php if($_SESSION['role'] === 'medecin' || $_SESSION['role'] === 'pharmacie'){?>
                                        <a href="#infos-pro" class="nav-link" data-bs-toggle="pill" role="tab">
                                            <i class="bi bi-briefcase" aria-hidden="true"></i> Informations professionnelles
                                        </a>
                                    <?php }?>
                                    <a href="#securite" class="nav-link" data-bs-toggle="pill" role="tab">
                                        <i class="bi bi-shield-lock" aria-hidden="true"></i> Sécurité
                                    </a>
                                    <a href="#zone-danger" class="nav-link nav-link-danger" data-bs-toggle="pill"
                                        role="tab">
                                        <i class="bi bi-exclamation-triangle" aria-hidden="true"></i> Zone de danger
                                    </a>
                                </div>
                            </div>
                        </div>

                    <!-- Contenu -->
                    <div class="col-lg-9">
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
                        <div class="tab-content">
                            <!-- Informations personnelles -->
                                <div class="tab-pane fade show active" id="infos-perso" role="tabpanel">
                                    <div class="contact-form-card">
                                        <h2>Informations personnelles</h2>
                                        <p>Ces informations sont visibles sur votre compte.</p>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="form_type" value="perso">
                                            <div class="row gx-4">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="profil-prenom">Prénom</label>
                                                    <input type="text" class="form-control" id="profil-prenom" 
                                                    name="prenom" value="<?= htmlspecialchars($user['prenom'], ENT_QUOTES, 'UTF-8') ?>" >
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="profil-nom">Nom</label>
                                                    <input type="text" class="form-control" id="profil-nom"
                                                    name="nom" value="<?= htmlspecialchars($user['nom'], ENT_QUOTES, 'UTF-8') ?>" >
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="profil-email">Email</label>
                                                    <input type="text" class="form-control" id="profil-email" 
                                                    name="email" value="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>" >
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="profil-telephone">Téléphone</label>
                                                    <input type="text" class="form-control" id="profil-telephone"
                                                    name="telephone" value="<?= htmlspecialchars($user['telephone'], ENT_QUOTES, 'UTF-8') ?>" >
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label" for="profil-photo">Photo de profil</label>
                                                    <input type="file" class="form-control" id="profil-photo" name="photo">
                                                    <small class="text-secondary d-block mb-4">
                                                        JPG, PNG ou WEBP, 2 Mo maximum.
                                                    </small>
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-submit w-auto px-5">
                                                        Enregistrer les modifications
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            <!-- Informations professionnelles (médecins et pharmacies) -->
                                <div class="tab-pane fade" id="infos-pro" role="tabpanel">
                                    <div class="contact-form-card">
                                        <h2>Informations professionnelles</h2>
                                        <p>Ces informations concernent votre 
                                            <?php if($_SESSION['role'] === 'medecin'){ ?>
                                              cabinet.
                                            <?php }else if (($_SESSION['role'] === 'pharmacie')){ ?>
                                              votre officine.
                                            <?php }?>    
                                        </p>
                                        <form action="" method="post">
                                            <input type="hidden" name="form_type" value="pro">
                                            <div class="row gx-4">
                                                <!-- Médecin et pharmacie -->
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="profil-num-pro">
                                                            <?php if($_SESSION['role'] === 'medecin'){ ?>
                                                                N° d'ordre</label>
                                                                <input type="text" class="form-control" id="profil-num-pro"
                                                                name="num_pro" value="<?= htmlspecialchars($pro['num_ordre_medecin'], ENT_QUOTES, 'UTF-8') ?>" >
                                                            <?php }else if (($_SESSION['role'] === 'pharmacie')){ ?>
                                                                N° d'autorisation</label>
                                                                <input type="text" class="form-control" id="profil-num-pro"
                                                                name="num_pro" value="<?= htmlspecialchars($pro['num_autorisation'], ENT_QUOTES, 'UTF-8') ?>" >
                                                            <?php }?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="profil-ville">Ville</label>
                                                        <input type="text" class="form-control" id="profil-ville"
                                                        name="ville" value="<?= htmlspecialchars($pro['ville'], ENT_QUOTES, 'UTF-8') ?>">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label" for="profil-adresse">Adresse</label>
                                                        <?php if($_SESSION['role'] === 'medecin'){ ?>
                                                            <input type="text" class="form-control" id="profil-adresse"
                                                            name="adresse" value="<?= htmlspecialchars($pro['adresse_cabinet'], ENT_QUOTES, 'UTF-8') ?>">
                                                        <?php }else if (($_SESSION['role'] === 'pharmacie')){ ?>
                                                            <input type="text" class="form-control" id="profil-adresse"
                                                            name="adresse" value="<?= htmlspecialchars($pro['adresse'], ENT_QUOTES, 'UTF-8') ?>">
                                                        <?php }?>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label" for="profil-horaires">
                                                        <?php if($_SESSION['role'] === 'medecin'){ ?>
                                                                horaires_travail</label>
                                                                <input type="text" class="form-control" id="profil-num-pro"
                                                                name="horaires_travail" value="<?= htmlspecialchars($pro['horaires_travail'], ENT_QUOTES, 'UTF-8') ?>" >
                                                            <?php }else if (($_SESSION['role'] === 'pharmacie')){ ?>
                                                                horaires_ouverture</label>
                                                                <input type="text" class="form-control" id="profil-num-pro"
                                                                name="horaires_ouverture" value="<?= htmlspecialchars($pro['horaires_ouverture'], ENT_QUOTES, 'UTF-8') ?>" >
                                                            <?php }?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="profil-latitude">Latitude</label>
                                                        <input type="text" class="form-control" id="profil-latitude"
                                                        name="latitude" value="<?= htmlspecialchars($pro['latitude'], ENT_QUOTES, 'UTF-8') ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="profil-longitude">Longitude</label>
                                                        <input type="text" class="form-control" id="profil-longitude"
                                                        name="longitude" value="<?= htmlspecialchars($pro['longitude'], ENT_QUOTES, 'UTF-8') ?>">
                                                    </div>
                                                <!-- Médecin uniquement -->
                                                    <?php if($_SESSION['role'] === 'medecin'){ ?>
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="profil-specialite">Spécialité</label>
                                                            <input type="text" class="form-control" id="profil-specialite"
                                                            name="specialite" value="<?= htmlspecialchars($pro['specialite'], ENT_QUOTES, 'UTF-8') ?>">
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
                                                    <?php if($_SESSION['role'] === 'pharmacie'){ ?>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="profil-officine">Nom de l'officine</label>
                                                        <input type="text" class="form-control" id="profil-officine"
                                                        name="nom_officine" value="<?= htmlspecialchars($pro['nom_officine'], ENT_QUOTES, 'UTF-8') ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span class="form-label d-block">Pharmacie de jour ou nuit</span>
                                                        <input type="radio" class="form-check-input" id="profil-type_jour"
                                                            name="type_ouverture" value="jour"
                                                            <?= $pro['type_ouverture'] ==='jour' ? 'checked' : '' ?>
                                                        >Jour
                                                        <input type="radio" class="form-check-input" id="profil-type_nuit"
                                                            name="type_ouverture" value="nuit"
                                                            <?= $pro['type_ouverture'] === 'nuit' ? 'checked' : '' ?>
                                                        >Nuit
                                                        <div class="form-check mt-4">
                                                            <input type="checkbox" class="form-check-input" id="profil-est_de_garde"
                                                                name="est_de_garde" value="1"
                                                                <?= !empty($pro['est_de_garde']) ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="profil-est_de_garde">Pharmacie de garde</label>
                                                        </div>
                                                    </div>
                                                    <?php } ?> 
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-submit w-auto px-5">
                                                        Enregistrer les modifications
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            <!-- Sécurité -->
                                <div class="tab-pane fade" id="securite" role="tabpanel">
                                    <div class="contact-form-card">
                                        <h2>Mot de passe</h2>
                                        <p>Choisissez un mot de passe d'au moins 8 caractères.</p>
                                        <form action="" method="post">
                                            <input type="hidden" name="form_type" value="securite">
                                            <div class="row gx-4">
                                                <div class="col-12">
                                                    <label class="form-label" for="profil-mdp-actuel">
                                                        Mot de passe actuel
                                                    </label>
                                                    <input type="password" class="form-control" id="profil-mdp-actuel" name="mdp_actuel">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="profil-mdp-nouveau">
                                                        Nouveau mot de passe
                                                    </label>
                                                    <input type="password" class="form-control" id="profil-mdp-nouveau" name="mdp_nouveau">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="profil-mdp-confirm">
                                                        Confirmer le nouveau mot de passe
                                                    </label>
                                                    <input type="password" class="form-control" id="profil-mdp-confirm" name="mdp_confirm">
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-submit w-auto px-5">
                                                        Changer le mot de passe
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
<!-- Formulaire 2FA -->
<div class="contact-form-card mt-4">
    <h2>Double authentification (2FA)</h2>
    <p>Ajoutez une sécurité supplémentaire lors de votre connexion.</p>

    <form action="" method="post">
        <input type="hidden" name="form_type" value="2fa">

        <div class="mb-3">
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" role="switch"
                       id="a2f_email" name="a2f_email" value="1"
                       <?= !empty($user['a2f_email']) ? 'checked' : '' ?>>
                <label class="form-check-label" for="a2f_email">
                    Code de vérification par email (<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>)
                </label>
            </div>

            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch"
                       id="a2f_totp" name="a2f_totp" value="1"
                       <?= (!empty($user['a2f_totp']) || (!empty($_POST['a2f_totp']) && empty($user['a2f_totp']))) ? 'checked' : '' ?>>
                <label class="form-check-label" for="a2f_totp">
                    Application d'authentification (Google Authenticator)
                </label>
            </div>
        </div>

        <?php if (!empty($_POST['a2f_totp']) && empty($user['a2f_totp']) && !empty($_SESSION['a2f_setup_secret'])): ?>
            <div id="bloc-totp-setup" class="border rounded-2 p-3 mb-3 bg-body-tertiary">
                <p class="small mb-2">
                    Scannez ce QR code ou saisissez la clé secrète dans <strong>Google Authenticator</strong>
                    (compte : <code><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></code>) :
                </p>
                <div class="mb-2 text-center">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=<?= urlencode('otpauth://totp/Carthage%20Sante:' . rawurlencode($user['email']) . '?secret=' . $_SESSION['a2f_setup_secret'] . '&issuer=Carthage%20Sante') ?>"
                         alt="QR Code Google Authenticator" class="img-thumbnail">
                </div>
                <div class="mb-3 text-center">
                    <code class="fs-6 user-select-all"><?= htmlspecialchars($_SESSION['a2f_setup_secret'], ENT_QUOTES, 'UTF-8') ?></code>
                </div>
                <label class="form-label small" for="code_totp_verif">
                    Entrez le code à 6 chiffres généré par l'application pour confirmer :
                </label>
                <input type="text" class="form-control" id="code_totp_verif" name="code_totp_verif"
                       maxlength="6" inputmode="numeric" placeholder="123456">
            </div>
        <?php elseif (!empty($user['a2f_totp'])): ?>
            <div class="mb-3">
                <span class="badge text-bg-success">Google Authenticator activé</span>
            </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-submit w-auto px-5">
            Enregistrer les préférences 2FA
        </button>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const switchTotp = document.getElementById("a2f_totp");
    const blocTotp = document.getElementById("bloc-totp-setup");
    if (switchTotp && blocTotp) {
        switchTotp.addEventListener("change", function () {
            blocTotp.classList.toggle("d-none", !switchTotp.checked);
        });
    }
});
</script>
                                </div>

                            <!-- Zone de danger -->
                                <div class="tab-pane fade" id="zone-danger" role="tabpanel">
                                    <div class="contact-form-card danger-card">
                                        <h2 class="text-danger">Zone de danger</h2>
                                        <p>Cette action est définitive.</p>

                                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                                            <div>
                                                <p class="mb-0 fw-semibold">Supprimer mon compte</p>
                                                <small class="text-secondary">
                                                    Votre compte et toutes les données associées seront supprimés
                                                    définitivement.
                                                </small>
                                            </div>
                                            <button type="button" class="btn btn-danger px-4"
                                                data-bs-toggle="modal" data-bs-target="#modal-delete-user"
                                                aria-label="Supprimer"    
                                                title="Supprimer" 
                                            >Supprimer mon compte</button>
                                        </div>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>


    <!--Model de suppresion de compte-->
        <div class="modal fade" id="modal-delete-user" tabindex="-1"
            aria-labelledby="modal-delete-user-label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-delete-user-label">Supprimer mon compte</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">
                            Êtes-vous sûr de vouloir supprimer <strong> <?php echo($_SESSION['prenom'].' '.$_SESSION['nom']); ?> </strong> ?
                            Tout le contenu appartenant à votre compte sera définitivement perdu.
                        </p>
                    </div>  
                    <div class="modal-footer">
                        <form method="post" action="" class="d-flex gap-2">
                            <input type="hidden" name="form_type" value="supprimer_compte">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-danger">Oui supprimer mon compte</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <?php require_once './includes/footer.php'; ?>
    <?php require_once './includes/scripts.php'; ?>

</body>


</html>