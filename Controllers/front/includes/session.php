<?php

ini_set('session.use_strict_mode', '1');//accept ken les sesion eli php amalhom
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');

// ============ DEMARRAGE DE LA SESSION ============

session_start();//cree wala recupere la sesion


$dureeSession = 1800; //1800s= 30min
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $dureeSession)) {
    $_SESSION = [];
    session_destroy();
    session_start();
}

$_SESSION['last_activity'] = time();//fi kol chargement de page ne5dhou el wakt

// reconnexion auto si cookie "se souvenir de moi"
require_once __DIR__ . '/../utilisateurC.php';
if (empty($_SESSION['id_utilisateur']) && isset($_COOKIE['souvenir'])) {
    (new utilisateurC())->connexionSouvenir();
}



header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
