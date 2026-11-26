<?php
$pageTitle = "Reset mot de passe";
$currentPage = "reset_password";

require_once '../../Controllers/front/includes/session.php';
require_once '../../Controllers/front/utilisateurC.php';

$utilisateurCtrl = new utilisateurC();
//nekraw el token mel lien
$token = $_GET['token'] ?? '';
//token s7i7 wala expire ?
$tokenValide = $utilisateurCtrl->verifierTokenReset($token);

$erreurs = [];
if ($tokenValide && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $erreurs = $utilisateurCtrl->changerMotDePasseReset($token, $_POST);
    if (empty($erreurs)) {
        header('Location: login.php?reset=ok');
        exit;
    }
}else if(!$tokenValide && $_SERVER['REQUEST_METHOD'] === 'POST'){
    $erreurs = ["imposible de changer le mot de passe"];
}

?>

<!DOCTYPE html>
<html lang="fr">

<?php require_once '../back/includes/head.php'; ?>

<body class="login-page bg-body-secondary">
    <main class="login-box">
        <h1 class="login-logo">
            <a href="./index.php"><b>Carthage</b> Santé</a>
        </h1>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">
                    Vous avez oublié votre mot de passe ? Ici, vous pouvez facilement cree un nouveau mot de passe.
                </p>

                <form action="" method="post" id="formu">
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
                    <label class="visually-hidden" for="nouvMdp">mot de passe</label>
                    <span class="err "></span>
                    <div class="input-group mb-3">
                        <input id="nouvMdp" name="mdp_nouveau" type="password" class="form-control" placeholder="Nouveau mot de passe" />
                        <div class="input-group-text">
                            <span id="iconm" class="bi bi-lock-fill"></span>
                        </div>
                    </div>
                    <label class="visually-hidden" for="conMdps">confirmer mot de passe</label>
                    <span class="err "></span>
                    <div class="input-group mb-3">
                        <input id="conMdps" name="mdp_confirm" type="password" class="form-control" placeholder="confirmer le mot de passe" />
                        <div class="input-group-text">
                            <span id="iconn" class="bi bi-lock-fill"></span>
                        </div>
                    </div>
                    <!-- aff mot de pass -->
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="affmdp">
                            <label class="form-check-label" for="affmdp">Afficher le mot de passe</label>
                        </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Cree un nouveau mot de passe</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.login-card-body -->
        </div>
    </main>

    <?php require_once './includes/scripts.php'; ?>
    <script src="../assets/js/reset-pasw.js"></script>

</body>

</html>