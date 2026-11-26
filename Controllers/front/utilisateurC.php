<?php
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../Models/utilisateur.php';
require_once __DIR__ . '/../../Models/medecin.php';
require_once __DIR__ . '/../../Models/pharmacie.php';
require_once __DIR__ . '/../../Models/admin.php';
require_once __DIR__ . '/medecinC.php';
require_once __DIR__ . '/pharmacieC.php';
require_once __DIR__ . '/../back/adminC.php';
require_once __DIR__ . '/mailC.php';

class utilisateurC{
    public function listeUtilisateur(){
        $db = config::getConnexion();
        try{
            $liste = $db->query("SELECT * FROM utilisateurs");
            return $liste;
        }catch(Exception $e){
            die("Erreur: ".$e->getMessage());
        }
    }

    public function addUtilisateur($u){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("INSERT INTO utilisateurs 
            (nom, prenom, email, mot_de_passe_hash, telephone, photo_profil_url, role, statut)
            VALUES(:n,:p,:e,:m,:t,:i,:r,:s)" );
            $req->execute([
                ":n" => $u->nom,
                ":p" => $u->prenom,
                ":e" => $u->email,
                ":m" => $u->mot_de_passe_hash,
                ":t" => $u->telephone,
                ":i" => $u->photo_profil_url,
                ":r" => $u->role,
                ":s" => $u->statut
            ]);
            return (int) $db->lastInsertId();
        }catch(Exception $e){
            die("Error: ".$e->getMessage());
        }
    }

    public function removeUtilisateur($id){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("DELETE FROM `utilisateurs` WHERE id_utilisateur = :id");
            $req->execute([
                ":id" => $id
            ]);
        }catch(Exception $e){
            die("Error: ".$e->getMessage());
        }
    }

    public function updateUtilisateur($id,array $post, array $files): array{
        $erreurs = [];

        $nom = trim($post['nom'] ?? '');
        $prenom = trim($post['prenom'] ?? '');
        $email = trim($post['email'] ?? '');
        $telephone = trim($post['telephone'] ?? '');
        $fichier = $files['photo'] ?? null;

        if($nom === '') {
            $erreurs[] = "Le nom est obligatoire.";
        }
        if($prenom === '') {
            $erreurs[] = "Le prénom est obligatoire.";
        }
        if($email === '') {
            $erreurs[] = "L'email est obligatoire.";
        }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {//fonction mat3 php par defaut
            $erreurs[] = "L'adresse e-mail n'est pas valide.";
        }if($telephone === '') {
            $erreurs[] = "Le numéro de téléphone est obligatoire.";
        } elseif (!preg_match('/^[2459]\d{7}$/', $telephone)) {
            $erreurs[] = "Le numéro de téléphone n'est pas valide.";
        }
        //nekhdou el photo eli mahtouta tawa
        $userActuel = $this->affUtilisateur($id);
        $photo = $userActuel['photo_profil_url'];
        $ext = '';
        $photoEnvoyee = ($fichier !== null && $fichier['error'] !== UPLOAD_ERR_NO_FILE);
        if ($photoEnvoyee) {
            if ($fichier['error'] === UPLOAD_ERR_INI_SIZE || $fichier['size'] > 2 * 1024 * 1024) {
                $erreurs[] = "La photo ne doit pas dépasser 2 Mo.";
            } elseif ($fichier['error'] !== UPLOAD_ERR_OK) {
                $erreurs[] = "Erreur lors de l'envoi de la photo.";
            } else {
                // nchoufou el extention chniya
                $info = @getimagesize($fichier['tmp_name']);
                $types = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp'];
                if ($info === false || !isset($types[$info[2]])) {
                    $erreurs[] = "La photo doit être une image JPG, PNG ou WEBP.";
                } else {
                    $ext = $types[$info[2]];
                }
            }
        }

        // nkoso script w nraj3o tableau err
        if (!empty($erreurs)) {
            return $erreurs;
        }
        // email mawjoud deja wala le
        $email_exist = $this->emailexiste($email);
        if($email_exist && $email_exist['id_utilisateur'] !== $id) {
            $erreurs[] = "Un autre compte avec cet email existe deja.";
            return $erreurs;
        }
        //nsajlo taswira
        if ($photoEnvoyee) {
            $nomFichier = bin2hex(random_bytes(16)) . '.' . $ext;
            $dossier = __DIR__ . '/../../Views/assets/uploads/profils/';
            if (!move_uploaded_file($fichier['tmp_name'], $dossier . $nomFichier)) {
                $erreurs[] = "Impossible d'enregistrer la photo.";
                return $erreurs;
            }
            $photo = 'assets/uploads/profils/' . $nomFichier;
        }
        //nabathou lel base donne
        $db = config::getConnexion();
        try {
            $req = $db->prepare("UPDATE utilisateurs
                SET nom = :n, prenom = :p, email = :e, telephone = :t, photo_profil_url = :i
                WHERE id_utilisateur = :id");
            $req->execute([
                ":n"  => $nom,
                ":p"  => $prenom,
                ":e"  => $email,
                ":t"  => $telephone,
                ":i"  => $photo,
                ":id" => $id
            ]);
            //mettre a jour info fel session si le compte qui a fais la modif et le meme connecter
            if ($_SESSION['id_utilisateur'] === $id) {
                $_SESSION['nom'] = $nom;
                $_SESSION['prenom'] = $prenom;
                $_SESSION['photo'] = $photo;
            }

            return [];
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }

    }

    public function updateUtilisateurStatut($id,$s){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("UPDATE utilisateurs SET statut= :s
            WHERE id_utilisateur=:id ");
            $req->execute([
                ":s" => $s,
                ":id" => $id
            ]);
            $user = $this->affUtilisateur($id);
            //nabathou email confirmation
            $env = @parse_ini_file(__DIR__ . '/../../.env');
            $contenu = '<p>Bonjour ' . htmlspecialchars($user['prenom'] .' '. $user['nom'], ENT_QUOTES, 'UTF-8') . ',</p>'
            . '<p>Le statut de votre compte sur <strong>Carthage Santé</strong> a été mis à jour.</p>'
            . '<p>Nouveau statut : <strong>' . htmlspecialchars($s, ENT_QUOTES, 'UTF-8') . '</strong>.</p>'
            . '<p>Pour toute question concernant cette modification, veuillez contacter notre équipe support.</p>'
            . '<p>Cordialement,<br>L\'équipe Carthage Santé</p>';
            (new mailC())->envoyer($user['email'], "Mise à jour du statut de votre compte - Carthage Santé", $contenu);
        }catch(Exception $e){
            die("Error: ".$e->getMessage());
        } 

    }

    public function updateDerniereConnexion($id){
        $db = config::getConnexion();
        try {
            $req = $db->prepare("UPDATE utilisateurs SET derniere_connexion = NOW() WHERE id_utilisateur = :id");
            $req->execute([
                ":id" => $id
            ]);
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    //gestion mot de passs oublier
    //pour la page profil
    public function updateMotDePasse(int $id, array $post): array{
        $erreurs = [];
        //recup champ + tandifh
        $mdpActuel = (string) ($post['mdp_actuel'] ?? '');
        $mdpNouveau = (string) ($post['mdp_nouveau'] ?? '');
        $mdpConfirm = (string) ($post['mdp_confirm'] ?? '');
        //controle saisiee
        if ($mdpActuel === '') {
        $erreurs[] = "Le mot de passe actuel est obligatoire.";
        }
        if (strlen($mdpNouveau) < 8) {
            $erreurs[] = "Le nouveau mot de passe doit contenir au moins 8 caractères.";
        }
        if ($mdpConfirm !== $mdpNouveau) {
            $erreurs[] = "La confirmation du mot de passe ne correspond pas.";
        }
        //nkosou script w nabathou erreur
        if (!empty($erreurs)) {
            return $erreurs;
        }
        //verification mot de passe act shih wala le 
        $user = $this->affUtilisateur($id);
        if (!password_verify($mdpActuel, $user['mot_de_passe_hash'])){
            $erreurs[] = "Le mot de passe actuel est incorrect.";
            return $erreurs;
        }
        //nouv mdps dif du nom
        if ($user['nom'] !== '' && $mdpNouveau === $user['nom']) {
            $erreurs[] = "Le mot de passe doit être différent du nom.";
            return $erreurs;
        }

        //nabathou lel base 
        $db = config::getConnexion();
        try {
            $req = $db->prepare("UPDATE utilisateurs SET mot_de_passe_hash = :m WHERE id_utilisateur = :id");
            $req->execute([
                ":m"  => password_hash($mdpNouveau, PASSWORD_DEFAULT),
                ":id" => $id
            ]);
            return [];
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }
    //pour mot de pass oublier sans connection
    public function demanderReset(array $post): array{
        $erreurs = [];
        $email = trim($post['email'] ?? '');

        if($email === '') {
            $erreurs[] = "L'email est obligatoire.";
        }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = "L'adresse e-mail n'est pas valide.";
        }
        if (!empty($erreurs)) {
            return $erreurs;
        }

        $user = $this->affutilisateurmail($email);
        // ma n9olouch lel visiteur si l'email existe wala le (securite)
        if ($user) {
            $token = bin2hex(random_bytes(32));
            $db = config::getConnexion();
            try{
                //nsajlo el hash mta token w expiration ba3d 1 heure
                $req = $db->prepare("UPDATE utilisateurs 
                    SET token_reset = :t, reset_expiration = DATE_ADD(NOW(), INTERVAL 1 HOUR)
                    WHERE id_utilisateur = :id");
                $req->execute([
                    ":t" => hash('sha256', $token),
                    ":id" => $user['id_utilisateur']
                ]);
            }catch(Exception $e){
                die("Error: ".$e->getMessage());
            }

            //nabathou el lien bel email
            $env = @parse_ini_file(__DIR__ . '/../../.env');
            $lien = $env['APP_URL'] . '/reset-password.php?token=' . $token;
            $contenu = '<p>Bonjour ' . htmlspecialchars($user['prenom'] .' '. $user['nom'], ENT_QUOTES, 'UTF-8') . ',</p>'
                . '<p>Vous avez demandé la réinitialisation de votre mot de passe sur Carthage Santé.</p>'
                . '<p><a href="' . $lien . '">Cliquez ici pour choisir un nouveau mot de passe</a></p>'
                . '<p>Ce lien est valable 1 heure. Si vous n\'êtes pas à l\'origine de cette demande, ignorez cet email.</p>';
            (new mailC())->envoyer($email, "Réinitialisation de votre mot de passe", $contenu);
        }
        return [];
    }

    public function verifierTokenReset($token){
        $db = config::getConnexion();
        try{
            //token s7i7 w mazel ma expirech
            $req = $db->prepare("SELECT * FROM utilisateurs 
                WHERE token_reset = :t AND reset_expiration > NOW()");
            $req->execute([
                ":t" => hash('sha256', (string) $token)
            ]);
            return $req->fetch();
        }catch(Exception $e){
            die("Erreur ".$e->getMessage());
        }
    }
    //modif mdp mel page mot de passe oublier
    public function changerMotDePasseReset($token, array $post): array{
        $erreurs = [];
        $mdpNouveau = (string) ($post['mdp_nouveau'] ?? '');
        $mdpConfirm = (string) ($post['mdp_confirm'] ?? '');

        if (strlen($mdpNouveau) < 8) {
            $erreurs[] = "Le mot de passe doit contenir au moins 8 caractères.";
        }
        if ($mdpConfirm !== $mdpNouveau) {
            $erreurs[] = "La confirmation du mot de passe ne correspond pas.";
        }
        if (!empty($erreurs)) {
            return $erreurs;
        }

        $user = $this->verifierTokenReset($token);
        if (!$user) {
            $erreurs[] = "Ce lien est invalide ou a expiré.";
            return $erreurs;
        }
        if ($mdpNouveau === $user['nom']) {
            $erreurs[] = "Le mot de passe doit être différent du nom.";
            return $erreurs;
        }

        $db = config::getConnexion();
        try{
            //nbadlou el mdp w nfas5ou token reset w token souvenir
            $req = $db->prepare("UPDATE utilisateurs 
                SET mot_de_passe_hash = :m, token_reset = NULL, reset_expiration = NULL,
                    token_souvenir = NULL, token_expiration = NULL
                WHERE id_utilisateur = :id");
            $req->execute([
                ":m" => password_hash($mdpNouveau, PASSWORD_DEFAULT),
                ":id" => $user['id_utilisateur']
            ]);
            return [];
        }catch(Exception $e){
            die("Error: ".$e->getMessage());
        }
    }

    public function affUtilisateur($id){
        $db=config::getConnexion();
        try{
            $req = $db->prepare("SELECT * FROM `utilisateurs` WHERE id_utilisateur = :id");
            $req->execute([
                ":id" => $id
            ]);
            return $req-> fetch();
        }catch(Exception $e){
            die("Erreur ".$e->getMessage());
        }
    }

    public function affutilisateurmail($em){
        $db=config::getConnexion();
        try{
            $req = $db->prepare("SELECT * FROM `utilisateurs` WHERE email = :e");
            $req->execute([
                ":e" => $em
            ]);
            return $req-> fetch();
        }catch(Exception $e){
            die("Erreur ".$e->getMessage());
        }
    }

    public function emailexiste($em){
        $db=config::getConnexion();
        try{
            $req = $db->prepare("SELECT * FROM utilisateurs WHERE email = :e ");
            $req->execute([
                ":e" => $em
            ]);
            return $req->fetch();
        }catch(Exception $e){
            die("Erreur ".$e->getMessage());
        }
    }

    public function inscription(array $post, array $files, bool $autoriserAdmin): array{
        $erreurs = [];
        //champs commun
        $nom = trim($post['nom'] ?? '');
        $prenom = trim($post['prenom'] ?? '');
        $email = trim($post['email'] ?? '');
        $password = (string) ($post['password'] ?? '');
        $cpassword = (string) ($post['Cpassword'] ?? '');
        $telephone = trim($post['telephone'] ?? '');
        $role = trim($post['role'] ?? '');
        //champs pro
        $numPro = trim($post['num_pro'] ?? '');
        $adresse = trim($post['adresse'] ?? '');
        $ville = trim($post['ville'] ?? '');
        $horaires = trim($post['horaires'] ?? '');
        $latitude = trim($post['latitude'] ?? '');
        $longitude = trim($post['longitude'] ?? '');
        $specialite = trim($post['specialite'] ?? '');
        $nomOfficine = trim($post['nom_officine'] ?? '');
        //champs admin
        $niveau_acces = trim($post['niv'] ?? '');

        if($nom === '') {
            $erreurs[] = "Le nom est obligatoire.";
        }
        if($prenom === '') {
            $erreurs[] = "Le prénom est obligatoire.";
        }
        if($email === '') {
            $erreurs[] = "L'email est obligatoire.";
        }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {//fonction mat3 php par defaut
            $erreurs[] = "L'adresse e-mail n'est pas valide.";
        }
        if(strlen($password) < 8) {
            $erreurs[] = "Le mot de passe doit contenir au moins 8 caractères.";
        }elseif ($nom !== '' && $password === $nom) {
            $erreurs[] = "Le mot de passe doit être différent du nom.";
        }
        if($cpassword !== $password) {
            $erreurs[] = "La confirmation du mot de passe ne correspond pas.";
        }
        if($telephone === '') {
            $erreurs[] = "Le numéro de téléphone est obligatoire.";
        } elseif (!preg_match('/^[2459]\d{7}$/', $telephone)) {
            $erreurs[] = "Le numéro de téléphone n'est pas valide.";
        }
        $rolesAutorises = ['patient', 'medecin', 'pharmacie'];
        //admin yetzed lel liste ken fel apel mta page ajout admin
        if ($autoriserAdmin) {
            $rolesAutorises[] = 'admin';
        }
        if(!in_array($role, $rolesAutorises, true)) {
            $erreurs[] = "Veuillez choisir un type de compte valide.";
        }
        //verif si compte pro
        if($role === 'medecin' || $role === 'pharmacie') {
            if($numPro === '') {
                $erreurs[] = "Le numéro professionnel est obligatoire.";
            }
            if($adresse === '') {
                $erreurs[] = "L'adresse est obligatoire.";
            }
            if($ville === '') {
                $erreurs[] = "La ville est obligatoire.";
            }
            if($horaires === '') {
                $erreurs[] = "Les horaires sont obligatoires.";
            }
            if($latitude === '') {
                $erreurs[] = "La latitude est obligatoire.";
            } elseif (!is_numeric($latitude) || (float) $latitude < -90 || (float) $latitude > 90) {
                $erreurs[] = "La latitude doit être comprise entre -90 et 90.";
            }
            if($longitude === '') {
                $erreurs[] = "La longitude est obligatoire.";
            }elseif (!is_numeric($longitude) || (float) $longitude < -180 || (float) $longitude > 180) {
                $erreurs[] = "La longitude doit être comprise entre -180 et 180.";
            }
            if($role === 'medecin' && $specialite === '') {
                $erreurs[] = "La spécialité est obligatoire.";
            }
            if ($role === 'pharmacie' && $nomOfficine === '') {
                $erreurs[] = "Le nom de la pharmacie est obligatoire.";
            }
        }
        //verif si admin
        if($role === 'admin'){
            if($niveau_acces !== 'super' && $niveau_acces !== 'moderateur'){
                $erreurs[] = "Veuillez choisir un Niveau D'acces.";
            }
        }

        //photo de profil
        $photo = 'assets/uploads/default-avatar.svg';   // taswira par defaut
        $fichier = $files['photo'] ?? null;
        $ext = '';
        $photoEnvoyee = ($fichier !== null && $fichier['error'] !== UPLOAD_ERR_NO_FILE);
        if ($photoEnvoyee) {
            if ($fichier['error'] === UPLOAD_ERR_INI_SIZE || $fichier['size'] > 2 * 1024 * 1024) {
                $erreurs[] = "La photo ne doit pas dépasser 2 Mo.";
            } elseif ($fichier['error'] !== UPLOAD_ERR_OK) {
                $erreurs[] = "Erreur lors de l'envoi de la photo.";
            } else {
                // nchoufou el extention chniya
                $info = @getimagesize($fichier['tmp_name']);
                $types = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp'];
                if ($info === false || !isset($types[$info[2]])) {
                    $erreurs[] = "La photo doit être une image JPG, PNG ou WEBP.";
                } else {
                    $ext = $types[$info[2]];
                }
            }
        }

        // nkoso script w nraj3o tableau err
        if (!empty($erreurs)) {
            return $erreurs;
        }
        // email mawjoud deja wala le
        if($this->emailexiste($email)) {
            $erreurs[] = "Un compte avec cet email existe deja.";
            return $erreurs;
        }
        if ($role === 'medecin' && (new medecinC())->numordreexiste($numPro)) {
            $erreurs[] = "Un médecin avec ce numéro d'ordre existe déjà.";
            return $erreurs;
        }
        if ($role === 'pharmacie' && (new pharmacieC())->numautorisationexiste($numPro)) {
            $erreurs[] = "Une pharmacie avec ce numéro d'autorisation existe déjà.";
            return $erreurs;
        }

        //nsajlo taswira fel dossier
        if ($photoEnvoyee) {
            $nomFichier = bin2hex(random_bytes(16)) . '.' . $ext;
            $dossier = __DIR__ . '/../../Views/assets/uploads/profils/';
            if (!move_uploaded_file($fichier['tmp_name'], $dossier . $nomFichier)) {
                $erreurs[] = "Impossible d'enregistrer la photo.";
                return $erreurs;
            }
            $photo = 'assets/uploads/profils/' . $nomFichier;
        }

        // Hacher el mdps
        $motDePasseHash = password_hash($password, PASSWORD_DEFAULT);
        //creation utilisateur normal
        $u = new utilisateur(
            $nom,
            $prenom,
            $email,
            $motDePasseHash,
            $telephone,
            $photo,
            $role
        );

        // najoutiw lel tableau
        $db = config::getConnexion();
        $db->beginTransaction();
        try {
            $id = (int) $this->addUtilisateur($u);
            if ($id <= 0) {
                $db->rollBack();
                $erreurs[] = "Une erreur est survenue lors de la création du compte.";
                return $erreurs;
            }
            //creation claase pour compte pro
            if ($role === 'medecin') {
                $m = new medecin(
                    $id,
                    $specialite,
                    $numPro,
                    $adresse,
                    $ville,
                    $latitude,
                    $longitude,
                    $horaires
                );
                (new medecinC())->addMedecin($m);
            }elseif ($role === 'pharmacie') {
                $p = new pharmacie(
                    $id,
                    $nomOfficine,
                    $numPro,
                    $adresse,
                    $ville,
                    $latitude,
                    $longitude,
                    $horaires
                );
                (new pharmacieC())->addPharmacie($p);
            }elseif ($role === 'admin') {
                $m = new admin(
                    $id,
                    $niveau_acces
                );
                (new adminC())->addAdmin($m);
            }
            $db->commit();
        }catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            $erreurs[] = "Une erreur est survenue lors de l'enregistrement du profil.";
            return $erreurs;
        }
        //nabathou el lien bel email
        $env = @parse_ini_file(__DIR__ . '/../../.env');
        $messageValidation = '';
        if($role === 'medecin' || $role === 'pharmacie'){
            $messageValidation = '<p>Votre compte est en cours de validation par un administrateur.</p>'
            . '<p>Vous recevrez un email de confirmation dès qu\'un administrateur aura terminé la validation.</p>';
        }
        $contenu = '<p>Bonjour ' . htmlspecialchars($prenom .' '. $nom, ENT_QUOTES, 'UTF-8') . ',</p>'
        . '<p>Bienvenue sur <strong>Carthage Santé</strong> ! Votre compte a bien été créé.</p>'
        .  $messageValidation
        . '<p>Vous pouvez dès maintenant vous connecter à votre espace :</p>'
        . '<p>Si vous n\'êtes pas à l\'origine de cette inscription, merci de nous contacter ou d\'ignorer cet email.</p>'
        . '<p>À très bientôt,<br>L\'équipe Carthage Santé</p>';
        (new mailC())->envoyer($email, "Bienvenue sur Carthage Santé", $contenu);
        return [];
    }

    public function connexion(array $post): array{
        $erreurs = [];

        $email = trim($post['email'] ?? '');
        $password = (string) ($post['password'] ?? '');

        if($email === '') {
            $erreurs[] = "L'email est obligatoire.";
        }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {//fonction mat3 php par defaut
            $erreurs[] = "L'adresse e-mail n'est pas valide.";
        }
        if($password === "") {
            $erreurs[] = "Le mot de passe est obligatoire.";
        }
        if (!empty($erreurs)) {
            return $erreurs;
        }

        $user = $this->affutilisateurmail($email);
        if(!$user || !password_verify($password, $user['mot_de_passe_hash']) ){
            $erreurs[] = "Email ou mot de passe incorrect.";
            return $erreurs;
        }
        if($user['statut'] === 'en_attente'){
            $erreurs[] = "Votre compte est en cours de validation.";
            return $erreurs;
        }
        if($user['statut'] === 'suspendu'){
            $erreurs[] = "Votre compte est suspendu.";
            return $erreurs;
        }
        //creation session
        $this->ouvrirSession($user);
        //si btn souvenir de moi : creation cokkie
        if (isset($_POST['souvenir'])) {
            $this->creerSouvenir((int) $user['id_utilisateur']);
        }

        return [];
    }

    //creation session
    private function ouvrirSession(array $user){
        session_regenerate_id(true);
        $_SESSION['id_utilisateur'] = (int) $user['id_utilisateur'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['last_activity'] = time();
        $_SESSION['date_creation'] = $user['date_creation'];
        $_SESSION['photo'] = $user['photo_profil_url'];
        if ($user['role'] === 'admin'){
            $admin = (new adminC())->affAdmin((int) $user['id_utilisateur']);
            $_SESSION['niveau_acces'] = $admin['niveau_acces'];
        }
        $this->updateDerniereConnexion((int) $user['id_utilisateur']);
    }

    public function deconnexion(){
        //ken andou session avec souvenir: nfaskhou le cokkie 
        if (isset($_SESSION['id_utilisateur'])) {
            $this->oublierSouvenir((int) $_SESSION['id_utilisateur']);
        }
        $_SESSION = [];
        // nfas5ou el cookie PHPSESSID 
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        // na7iw el session cote serveur
        session_destroy();
    }

    //gestion sesion souvenir
    // nsajlo el token fel base (hash) w fel cookie
    private function creerSouvenir($id){
        $token = bin2hex(random_bytes(32));
        $db = config::getConnexion();
        try{
            $req = $db->prepare("UPDATE utilisateurs 
                SET token_souvenir = :t, token_expiration = DATE_ADD(NOW(), INTERVAL 30 DAY)
                WHERE id_utilisateur = :id");
            $req->execute([
                ":t" => hash('sha256', $token),
                ":id" => $id
            ]);
        }catch(Exception $e){
            die("Error: ".$e->getMessage());
        }
        setcookie('souvenir', $id . ':' . $token, [
            'expires' => time() + 30 * 24 * 3600,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }
    // nfas5ou el token (deconnexion)
    private function oublierSouvenir($id){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("UPDATE utilisateurs 
                SET token_souvenir = NULL, token_expiration = NULL
                WHERE id_utilisateur = :id");
            $req->execute([":id" => $id]);
        }catch(Exception $e){
            die("Error: ".$e->getMessage());
        }
        setcookie('souvenir', '', time() - 3600, '/');
        unset($_COOKIE['souvenir']);
    }
    // reconnexion automatique avec el cookie
    public function connexionSouvenir(){
        //coupe le coukie en partie (1 est le id)
        $parties = explode(':', $_COOKIE['souvenir'] ?? '');
        //si partie diff alors cokie modifier ou invalid
        if (count($parties) !== 2) {
            return;
        }
        $user = $this->affUtilisateur((int) $parties[0]);
        if ($user && $user['token_souvenir'] !== null
            && $user['statut'] === 'actif'
            && strtotime($user['token_expiration']) > time()
            && hash_equals($user['token_souvenir'], hash('sha256', $parties[1]))) {
            $this->ouvrirSession($user);
        } else {
            setcookie('souvenir', '', time() - 3600, '/');
        }
    }
}
?>