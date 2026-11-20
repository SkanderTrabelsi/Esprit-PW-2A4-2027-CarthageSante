<?php
$pageTitle = "mpds oublier";
$currentPage = "forgot-password";
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

                <form action="../examples/login.html" method="post">
                    <label class="visually-hidden" for="forgotEmail">Email</label>
                    <div class="input-group mb-3">
                        <input id="forgotEmail" name="email" type="email" class="form-control" placeholder="Email" />
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

</body>

</html>