<?php
$pageTitle = "Se connecter";
$currentPage = "login";
require_once '../../Controllers/front/includes/session.php';
require_once '../../Controllers/front/utilisateurC.php';

$erreurs = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $utilisateurCtrl = new utilisateurC();
     // Google renvoie un champ 'credential', le formulaire normal non
        $erreurs = isset($_POST['credential']) ? $utilisateurCtrl->connexionGoogle($_POST) : $utilisateurCtrl->connexion($_POST);
    if (empty($erreurs)) {
      $role = $_SESSION['role'] ?? '';
      if($role === 'admin'){
          header('Location: ../back/index.php');
      }else{
          header('Location: index.php');
      }
      exit;
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
        <p class="login-box-msg">Connectez-vous à votre compte</p>

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
                 <?php if (isset($_POST['credential']) && isset($_SESSION['email_google'])): ?>
              <div class="d-grid gap-2 mb-3">
                  <a href="register.php" class="btn btn-primary">S'inscrire avec ce mail</a>
                  <a href="login.php" class="btn btn-outline-secondary">Se reconnecter</a>
              </div>
          <?php endif; ?>

        <form action="" method="post" id="formu">
          <label class="visually-hidden" for="loginEmail">Email</label>
          <span class="err "></span>
          <div class="input-group mb-3">
            <input id="loginEmail" name="email" type="text" class="form-control" placeholder="Email " autocomplete="new-email"
                        value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            />
            <div class="input-group-text">
              <span class="bi bi-envelope"></span>
            </div>
          </div>
          <label class="visually-hidden" for="loginPassword">MotDePasse</label>
          <span class="err "></span>
          <div class="input-group mb-3">
            <input id="loginPassword" name="password" type="password" class="form-control" placeholder="Mot de passe" autocomplete="new-password"/>
            <div class="input-group-text">
              <span class="bi bi-lock-fill"></span>
            </div>
          </div>
          <!--begin::Row-->
          <div class="row">
            <div class="col-8">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="souvenir" name="souvenir" />
                <label class="form-check-label" for="souvenir"> Se souvenir de moi </label>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">SeConnecter</button>
              </div>
            </div>
            <!-- /.col -->
          </div>
          <!--end::Row-->
        </form>

        <div class="social-auth-links text-center mb-3 d-grid gap-2">
          <p>- OU -</p>
          <a href="#" class="btn btn-primary">
            <i class="bi bi-facebook me-2"></i> Se connecter avec Facebook
          </a>
                   <!-- bouton Google officiel : apres le choix du compte, Google renvoie vers cette page -->
                    <div id="g_id_onload"
               data-client_id="<?= htmlspecialchars(config::env('GOOGLE_CLIENT_ID'), ENT_QUOTES, 'UTF-8') ?>"
               data-login_uri="<?= (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] ?>"
               data-ux_mode="redirect"
               data-auto_prompt="false"></div>
          
               
                     <div class="position-relative mx-auto" style="width:300px; height:40px;">
              <button type="button" class="btn btn-danger w-100 h-100">
                  <i class="bi bi-google me-2"></i> Se connecter avec Google
              </button>
              <div class="g_id_signin" data-type="standard" data-size="large" data-width="300"
                   style="position:absolute; top:0; left:0; width:300px; height:40px; overflow:hidden; opacity:0.01;"></div>
          </div>
        </div>
        <!-- /.social-auth-links -->

        <p class="mb-1">
          <a href="forgot-password.php">Mot de passe oublie ?</a>
        </p>
        <p class="mb-0">
          <a href="register.php" class="text-center"> Pas de compte ? Cree un compte </a>
        </p>
      </div>
      <!-- /.login-card-body -->
    </div>
  </main>



  <?php require_once './includes/scripts.php'; ?>
  <script src="../assets/js/login.js"></script>
  <script src="https://accounts.google.com/gsi/client" async defer></script>
  <script src="https://accounts.google.com/gsi/client" async defer></script>

</body>

</html>