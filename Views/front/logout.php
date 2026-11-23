<?php
require_once '../../Controllers/front/includes/session.php';
require_once '../../Controllers/front/utilisateurC.php';

(new utilisateurC())->deconnexion();
header('Location: login.php');
exit;
?>