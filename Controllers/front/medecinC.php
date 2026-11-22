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

    public function updateMedecin($id,$m){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("UPDATE medecins SET specialite=:s ,num_ordre_medecin=:n ,adresse_cabinet=:a ,ville=:v ,latitude =:la ,
            longitude=:lo ,horaires_travail=:h ,teleconsultation_active =:t
            WHERE id_medecin=:id ");
            $req->execute([
                ":s" => $m->specialite,
                ":n" => $m->num_ordre_medecin ,
                ":a" => $m->adresse_cabinet,
                ":v" => $m->ville,
                ":la" => $m->latitude,
                ":lo" => $m->longitude,
                ":h" => $m->horaires_travail,
                ":t" => $m->teleconsultation_active,
                ":id" => $id
            ]);
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
            return (bool) $req->fetch();
        }catch(Exception $e){
            die("Erreur ".$e->getMessage());
        }
    }
}
?>