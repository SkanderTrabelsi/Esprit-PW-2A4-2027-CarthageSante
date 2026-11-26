<?php
require_once __DIR__ . '/../../db.php';

class medecinC{
    public function listeMedecin(){
        $db = config::getConnexion();
        try{
            $liste = $db->query("SELECT * FROM medecins");
            return $liste;
        }catch(Exception $e){
            die("Erreur: ".$e->getMessage());
        }
    }

    public function addMedecin($m){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("INSERT INTO medecins 
            VALUES(NULL,:idu,:s,:n,:a,:v,:la,:lo,:h,DEFAULT)" );
            $req->execute([
                ":idu" => $m->id_utilisateur ,
                ":s" => $m->specialite,
                ":n" => $m->num_ordre_medecin ,
                ":a" => $m->adresse_cabinet,
                ":v" => $m->ville,
                ":la" => $m->latitude,
                ":lo" => $m->longitude,
                ":h" => $m->horaires_travail
            ]);
        }catch(Exception $e){
            die("Error: ".$e->getMessage());
        }
    } 

    public function removeMedecin($id){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("DELETE FROM medecins WHERE id_medecin = :id");
            $req->execute([
                ":id" => $id
            ]);
        }catch(Exception $e){
            die("Error: ".$e->getMessage());
        }
    }

    public function updateMedecin($id, array $post) :array{
        $erreurs = [];
        //reupt les champs et netoiyage
        $numOrdre = trim($post['num_pro'] ?? '');
        $ville = trim($post['ville'] ?? '');
        $adresse = trim($post['adresse'] ?? '');
        $horaires = trim($post['horaires_travail'] ?? '');
        $latitude = trim($post['latitude'] ?? '');
        $longitude = trim($post['longitude'] ?? '');
        $specialite = trim($post['specialite'] ?? '');
        $teleconsultation = isset($post['teleconsultation_active']) ? 1 : 0;
        
        //controle saisie
        if ($numOrdre === '') {
            $erreurs[] = "Le numéro d'ordre est obligatoire.";
        }
        if ($ville === '') {
            $erreurs[] = "La ville est obligatoire.";
        }
        if ($adresse === '') {
            $erreurs[] = "L'adresse du cabinet est obligatoire.";
        }
        if ($horaires === '') {
            $erreurs[] = "Les horaires de travail sont obligatoires.";
        }
        if ($latitude === '') {
            $erreurs[] = "La latitude est obligatoire.";
        } elseif (!is_numeric($latitude) || (float) $latitude < -90 || (float) $latitude > 90) {
            $erreurs[] = "La latitude doit être comprise entre -90 et 90.";
        }
        if ($longitude === '') {
            $erreurs[] = "La longitude est obligatoire.";
        } elseif (!is_numeric($longitude) || (float) $longitude < -180 || (float) $longitude > 180) {
            $erreurs[] = "La longitude doit être comprise entre -180 et 180.";
        }
        if ($specialite === '') {
            $erreurs[] = "La spécialité est obligatoire.";
        }  

        //nkosou script w nrajou err
        if (!empty($erreurs)) {
            return $erreurs;
        }

        // Vérifie si le numéro d'ordre appartient déjà à un AUTRE médecin
        $num_pro = $this->numordreexiste($numOrdre);
        if($num_pro && $num_pro['id_medecin'] !== $id) {
            $erreurs[] = "Un autre compte avec le meme numero existe deja.";
            return $erreurs;
        }
        //nabathou lel base de donne 
        $db = config::getConnexion();
        try{ 
            $req = $db->prepare("UPDATE medecins SET specialite=:s ,num_ordre_medecin=:n ,adresse_cabinet=:a ,ville=:v ,latitude =:la ,
            longitude=:lo ,horaires_travail=:h ,teleconsultation_active =:t
            WHERE id_medecin=:id ");
            $req->execute([
                ":s"  => $specialite,
                ":n"  => $numOrdre,
                ":a"  => $adresse,
                ":v"  => $ville,
                ":la" => $latitude,
                ":lo" => $longitude,
                ":h"  => $horaires,
                ":t"  => $teleconsultation,
                ":id" => $id
            ]);
            return [];
        }catch(Exception $e){
            die("Error: ".$e->getMessage());
        }
    }

    public function affMedecin($id){
        $db=config::getConnexion();
        try{
            $req = $db->prepare("SELECT * FROM medecins WHERE id_utilisateur = :id");
            $req->execute([
                ":id" => $id
            ]);
            return $req-> fetch(); 
        }catch(Exception $e){
            die("Erreur ".$e->getMessage());
        }
    }

    public function numordreexiste($or){
        $db=config::getConnexion();
        try{
            $req = $db->prepare("SELECT * FROM medecins WHERE num_ordre_medecin = :o ");
            $req->execute([
                ":o" => $or
            ]);
             return $req->fetch();
        }catch(Exception $e){
            die("Erreur ".$e->getMessage());
        }
    }
}
?>