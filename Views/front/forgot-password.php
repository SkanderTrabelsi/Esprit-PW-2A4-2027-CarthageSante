<?php
$pageTitle = "mpds oublier";
$currentPage = "forgot-password";

require_once '../../Controllers/front/includes/session.php';
require_once '../../Controllers/front/utilisateurC.php';

$erreurs = [];
$succes = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $erreurs = (new utilisateurC())->demanderReset($_POST);
    if (empty($erreurs)) {
        $succes = "Si un compte existe avec cet email, un lien de réinitialisation vient d'être envoyé.";
    }
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
                    Vous avez oublié votre mot de passe ? Ici, vous pouvez facilement obtenir un nouveau mot de passe.
                </p>
                <!-- msg err -->
                    <?php if (!empty($erreurs)): ?>
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0">
                                <?php foreach ($erreurs as $err): ?>
                                    <li><?= htmlspecialchars((string) $err, ENT_QUOTES, 'UTF-8') ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                <!-- msg suces -->
                    <?php if (!empty($succes)): ?>
                        <div class="alert alert-success" role="alert">
                            <?= htmlspecialchars((string) $succes, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>
                <form action="" method="post" id="formu">
                    <label class="visually-hidden" for="forgotEmail">Email</label>
                    <span class="err "></span>
                    <div class="input-group mb-3">
                        <input id="forgotEmail" name="email" type="text" class="form-control" placeholder="Email" />
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Demander un nouveau mot de passe</button>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mt-3 mb-1">
                    <a href="login.php">Se connecter</a>
                </p>
                <p class="mb-0">
                    <a href="register.php" class="text-center"> Créer un compte </a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </main>



    <?php require_once './includes/scripts.php'; ?>
    <script src="../assets/js/forg-pasw.js"></script>

</body>

</html>