<?php
require_once __DIR__ . '/../../db.php';

class pharmacieC{
    public function listePharmacie(){
        $db = config::getConnexion();
        try{
            $liste = $db->query("SELECT * FROM pharmacies");
            return $liste;
        }catch(Exception $e){
            die("Erreur: ".$e->getMessage());
        }
    }

    public function addPharmacie($f){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("INSERT INTO pharmacies 
            VALUES(NULL,:idu,:o,:n,:a,:v,:la,:lo,:h,DEFAULT,DEFAULT)" );
            $req->execute([
                ":idu" => $f->id_utilisateur ,
                ":o" => $f->nom_officine,
                ":n" => $f->num_autorisation,
                ":a" => $f->adresse,
                ":v" => $f->ville,
                ":la" => $f->latitude,
                ":lo" => $f->longitude,
                ":h" => $f->horaires_ouverture
            ]);
        }catch(Exception $e){
            die("Error: ".$e->getMessage());
        }
    }

    public function removePharmacie($id){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("DELETE FROM pharmacies WHERE id_pharmacie = :id");
            $req->execute([
                ":id" => $id
            ]);
        }catch(Exception $e){
            die("Error: ".$e->getMessage());
        }
    }

    public function updatePharmacie($id, array $post): array{
        $erreurs = [];
        //recupt champ et tandhifa
        $nomOfficine = trim($post['nom_officine'] ?? '');
        $numAutorisation = trim($post['num_pro'] ?? '');
        $ville = trim($post['ville'] ?? '');
        $adresse = trim($post['adresse'] ?? '');
        $horaires = trim($post['horaires_ouverture'] ?? '');
        $latitude = trim($post['latitude'] ?? '');
        $longitude = trim($post['longitude'] ?? '');
        $typeOuverture = trim($post['type_ouverture'] ?? '');
        $estDeGarde = isset($post['est_de_garde']) ? 1 : 0;
        
        //controle saisie
        if ($nomOfficine === '') {
            $erreurs[] = "Le nom de l'officine est obligatoire.";
        }
        if ($numAutorisation === '') {
            $erreurs[] = "Le numéro d'autorisation est obligatoire.";
        }
        if ($ville === '') {
            $erreurs[] = "La ville est obligatoire.";
        }
        if ($adresse === '') {
            $erreurs[] = "L'adresse est obligatoire.";
        }
        if ($horaires === '') {
            $erreurs[] = "Les horaires d'ouverture sont obligatoires.";
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
        if ($typeOuverture !== 'jour' && $typeOuverture !== 'nuit') {
            $erreurs[] = "Veuillez choisir si la pharmacie est de jour ou de nuit.";
        }
        
        //nkosou script w nraj3ou err
        if (!empty($erreurs)) {
            return $erreurs;
        }

        // Vérifie si le numéro d'autorisation appartient déjà à une AUTRE pharmacie
        $num_pro = $this->numautorisationexiste($numAutorisation);
        if($num_pro && $num_pro['id_pharmacie'] !== $id) {
            $erreurs[] = "Un autre compte avec le meme numero existe deja.";
            return $erreurs;
        }

        //nabathou lel BD
        $db = config::getConnexion();
        try{
            $req = $db->prepare("UPDATE pharmacies SET nom_officine=:o ,num_autorisation=:n ,adresse=:a ,ville=:v ,latitude =:la ,
            longitude=:lo ,horaires_ouverture=:h ,type_ouverture =:t ,est_de_garde =:g
            WHERE id_pharmacie=:id ");
            $req->execute([
                ":o"  => $nomOfficine,
                ":n"  => $numAutorisation,
                ":a"  => $adresse,
                ":v"  => $ville,
                ":la" => $latitude,
                ":lo" => $longitude,
                ":h"  => $horaires,
                ":t"  => $typeOuverture,
                ":g"  => $estDeGarde,
                ":id" => $id
            ]);
            return [];
        }catch(Exception $e){
            die("Error: ".$e->getMessage());
        }
    }

    public function affPharmacie($id){
        $db=config::getConnexion();
        try{
            $req = $db->prepare("SELECT * FROM pharmacies WHERE id_utilisateur = :id");
            $req->execute([
                ":id" => $id
            ]);
            return $req-> fetch();
        }catch(Exception $e){
            die("Erreur ".$e->getMessage());
        }
    }

    public function numautorisationexiste($no){
        $db=config::getConnexion();
        try{
            $req = $db->prepare("SELECT * FROM pharmacies WHERE num_autorisation = :o ");
            $req->execute([
                ":o" => $no
            ]);
            return $req->fetch();
        }catch(Exception $e){
            die("Erreur ".$e->getMessage());
        }
    }
}
?>