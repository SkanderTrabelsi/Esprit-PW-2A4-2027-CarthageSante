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

    public function updatePharmacie($id,$f){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("UPDATE pharmacies SET nom_officine=:o ,num_autorisation=:n ,adresse=:a ,ville=:v ,latitude =:la ,
            longitude=:lo ,horaires_ouverture=:h ,type_ouverture =:t ,est_de_garde =:g
            WHERE id_pharmacie=:id ");
            $req->execute([
                ":o" => $f->nom_officine,
                ":n" => $f->num_autorisation,
                ":a" => $f->adresse,
                ":v" => $f->ville,
                ":la" => $f->latitude,
                ":lo" => $f->longitude,
                ":h" => $f->horaires_ouverture,
                ":t" => $f->type_ouverture,
                ":g" => $f->est_de_garde,
                ":id" => $id
            ]);
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
            return (bool) $req->fetch();
        }catch(Exception $e){
            die("Erreur ".$e->getMessage());
        }
    }
}
?>