<?php
$pageTitle = "S'inscrire";
$currentPage = "register";
require_once '../../Controllers/front/includes/session.php';
require_once '../../Controllers/front/utilisateurC.php';

$erreurs = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $utilisateurCtrl = new utilisateurC();
    $erreurs = $utilisateurCtrl->inscription($_POST, $_FILES,false);
    if (empty($erreurs)) {
        header("Location: login.php?inscrit=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<?php require_once '../back/includes/head.php'; ?>

<body class="register-page bg-body-secondary">
    <main class="register-box">
        <h1 class="register-logo">
            <a href="./index.php"><b>Carthage</b> Santé</a>
        </h1>
        <!-- /.register-logo -->
        <div class="card">
            <div class="card-body register-card-body">
                <p class="register-box-msg">Cree un compte</p>
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
                            <button type="button" id="btnGenMdp" class="btn btn-outline-secondary" title="Suggérer un mot de passe fort">
                                    <i class="bi bi-magic"></i> 
                            </button>
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
                            </select>
                            <div class="input-group-text">
                                <span class="bi bi-person-badge"></span>
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

                            <div class="mb-3">
                                <button type="button" id="btnLocaliser" class="btn btn-sm btn-outline-primary w-100">
                                    <i class="bi bi-geo-alt-fill me-1"></i> Récupérer ma position actuelle
                                </button>
                                <small id="statusLocalisation" class="text-muted d-block mt-1"></small>
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

                            <?php
                            $joursChoisis = $_POST['jours'] ?? [];
                            $heureDebut = $_POST['heure_debut'] ?? '08:00';
                            $heureFin   = $_POST['heure_fin'] ?? '18:30';
                            $joursListe = [
                                'Lun' => 'Lu',
                                'Mar' => 'Ma',
                                'Mer' => 'Me',
                                'Jeu' => 'Je',
                                'Ven' => 'Ve',
                                'Sam' => 'Sa',
                                'Dim' => 'Di',
                            ];
                            ?>
                            <label class="form-label mb-1">Horaires</label>
                            <input type="hidden" id="registerHoraires" name="horaires"
                                value="<?= htmlspecialchars($_POST['horaires'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            <span class="err "></span>
                            <div class="input-group border rounded-2 p-2 mb-3">
                                <div class="d-flex justify-content-between align-items-center w-100 mb-2">
                                    <?php foreach ($joursListe as $val => $court): ?>
                                        <div class="form-check m-0">
                                            <input class="form-check-input" type="checkbox" name="jours[]" id="j_<?= $val ?>" value="<?= $val ?>"
                                                <?= in_array($val, $joursChoisis, true) ? 'checked' : '' ?>>
                                            <label class="form-check-label small" for="j_<?= $val ?>" title="<?= $val ?>"><?= $court ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="row g-2 w-100">
                                    <div class="col-6">
                                        <label class="form-label small mb-1" for="heure_debut">De</label>
                                        <input type="time" class="form-control form-control-sm" id="heure_debut" name="heure_debut"
                                            value="<?= htmlspecialchars($heureDebut, ENT_QUOTES, 'UTF-8') ?>">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small mb-1" for="heure_fin">À</label>
                                        <input type="time" class="form-control form-control-sm" id="heure_fin" name="heure_fin"
                                            value="<?= htmlspecialchars($heureFin, ENT_QUOTES, 'UTF-8') ?>">
                                    </div>
                                </div>
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


                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-8">
                            <span class="err "></span>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="conditions" name="conditions" />
                                <label class="form-check-label" for="conditions">
                                    J'accepte les <a href="terms.php" target="_blank">Conditions</a>
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">S'inscrire</button>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!--end::Row-->
                </form>

                <div class="social-auth-links text-center mb-3 d-grid gap-2">
                    <p>- OU -</p>
                    <a href="#" class="btn btn-danger">
                        <i class="bi bi-google me-2"></i> S'inscrire avec Google
                    </a>
                </div>
                <!-- /.social-auth-links -->

                <p class="mb-0">
                    <a href="login.php" class="text-center">J'ai deja un compte</a>
                </p>
            </div>
            <!-- /.register-card-body -->
        </div>
    </main>


    <script src="../assets/js/register.js"></script>
    
    

</body>

</html>