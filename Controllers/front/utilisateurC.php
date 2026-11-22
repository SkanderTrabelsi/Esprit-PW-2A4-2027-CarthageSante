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

    public function updateUtilisateur($id,$u){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("UPDATE utilisateurs SET nom=:n ,prenom=:p ,email=:e ,mot_de_passe_hash=:m ,telephone =:t ,
            photo_profil_url=:i ,role=:r 
            WHERE id_utilisateur=:id ");
            $req->execute([
                ":n" => $u->nom,
                ":p" => $u->prenom,
                ":e" => $u->email,
                ":m" => $u->mot_de_passe_hash,
                ":t" => $u->telephone,
                ":i" => $u->photo_profil_url,
                ":r" => $u->role,
                ":id" => $id
            ]);
        }catch(Exception $e){
            die("Error: ".$e->getMessage());
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
            return (bool) $req->fetch();
        }catch(Exception $e){
            die("Erreur ".$e->getMessage());
        }
    }

    public function inscription(array $post): array{
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
        $rolesAutorises = ['patient', 'medecin', 'pharmacie', 'admin'];
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
            if($niveau_acces === ''){
                $erreurs[] = "Veuillez choisir un Niveau D'acces.";
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
        // Hacher el mdps
        $motDePasseHash = password_hash($password, PASSWORD_DEFAULT);
        //creation utilisateur normal
        $u = new utilisateur(
            $nom,
            $prenom,
            $email,
            $motDePasseHash,
            $telephone,
            null,
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

        session_regenerate_id(true);
        $_SESSION['id_utilisateur'] = (int) $user['id_utilisateur'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['last_activity'] = time();

        return [];
    }
}
?>