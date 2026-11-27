<?php
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../Models/utilisateur.php';
require_once __DIR__ . '/../../Models/medecin.php';
require_once __DIR__ . '/../../Models/pharmacie.php';
require_once __DIR__ . '/../../Models/admin.php';
require_once __DIR__ . '/medecinC.php';
require_once __DIR__ . '/pharmacieC.php';
require_once __DIR__ . '/../back/adminC.php';

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
            VALUES(NULL,:n,:p,:e,:m,:t,:i,:r,:s,DEFAULT)" );
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
            //mettre a jour info fel session 
            $_SESSION['nom'] = $nom;
            $_SESSION['prenom'] = $prenom;
            $_SESSION['photo'] = $photo;

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
        }catch(Exception $e){
            die("Error: ".$e->getMessage());
        }  
    }

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
        $id = $this->addUtilisateur($u);
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
        return [];
    }

    public function connexion(array $post): array{
        $erreurs = [];
        unset($_SESSION['email_google']);   // on efface l'ancien email memorise
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
        if(!$user){
            // aucun compte avec cet email
            $_SESSION['email_google'] = $email;   // pour pre-remplir l'inscription
            $erreurs[] = "Il n'y a pas de compte lie a l'email " . $email . ".";
            return $erreurs;
        }
        if(!password_verify($password, $user['mot_de_passe_hash'])){
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
        $this->ouvrirSession($user);

        return [];
    }
    private function ouvrirSession($user){
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
    }

   public function connexionGoogle(array $post, $inscription = false): array{
    $erreurs = [];

    // 1) Google doit avoir envoye le jeton et le code de securite
    if (!isset($post['credential']) || !isset($post['g_csrf_token']) || !isset($_COOKIE['g_csrf_token'])) {
        $erreurs[] = "Echec de la connexion Google.";
        return $erreurs;
    }
    // le code du formulaire doit etre le meme que celui du cookie
    if ($post['g_csrf_token'] !== $_COOKIE['g_csrf_token']) {
        $erreurs[] = "Echec de la connexion Google.";
        return $erreurs;
    }

    // 2) on demande a Google de verifier le jeton
    $reponse = @file_get_contents("https://oauth2.googleapis.com/tokeninfo?id_token=" . urlencode($post['credential']));
    if ($reponse === false) {
        $erreurs[] = "Echec de la connexion Google.";
        return $erreurs;
    }
    $g = json_decode($reponse, true);   // tableau avec email, given_name, family_name...

    // 3) le jeton doit etre pour notre site et l'email doit etre verifie
    if ($g['aud'] !== config::env('GOOGLE_CLIENT_ID')) {
        $erreurs[] = "Echec de la connexion Google.";
        return $erreurs;
    }
    if ($g['email_verified'] !== 'true') {
        $erreurs[] = "Votre email Google n'est pas verifie.";
        return $erreurs;
    }

    // 4) est ce que cet email existe deja dans notre base ?
    $user = $this->emailexiste($g['email']);
    if ($inscription && $user) {
    $erreurs[] = "Cet email a deja ete utilise pour creer un compte.";
    return $erreurs;
    }

    if (!$user) {
        // premiere connexion : on cree un compte patient
        $prenom = 'Utilisateur';
        if (isset($g['given_name'])) {
            $prenom = $g['given_name'];
        }
        $nom = '-';
        if (isset($g['family_name'])) {
            $nom = $g['family_name'];
        }
        $mdpAleatoire = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);

        $u = new utilisateur($nom, $prenom, $g['email'], $mdpAleatoire, null, 'assets/uploads/default-avatar.svg', 'patient');
        $this->addUtilisateur($u);
        $user = $this->affutilisateurmail($g['email']);
    }

    // 5) meme controle de statut que la connexion normale
    if ($user['statut'] === 'en_attente') {
        $erreurs[] = "Votre compte est en cours de validation.";
        return $erreurs;
    }
    if ($user['statut'] === 'suspendu') {
        $erreurs[] = "Votre compte est suspendu.";
        return $erreurs;
    }

    // 6) tout est bon : on ouvre la session
    $this->ouvrirSession($user);
    return $erreurs;   // tableau vide = pas d'erreur
}

    public function deconnexion(){
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
}
?>