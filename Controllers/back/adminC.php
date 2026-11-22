<?php
require_once __DIR__ . '/../../db.php';

class adminC{
    public function listeAdmin(){
        $db = config::getConnexion();
        try{
            $liste = $db->query("SELECT * FROM admin");
            return $liste;
        }catch(Exception $e){
            die("Erreur: ".$e->getMessage());
        }
    }

    public function addAdmin($a){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("INSERT INTO admin 
            VALUES(NULL,:idu,:n)" );
            $req->execute([
                ":idu" => $a->id_utilisateur ,
                ":n" => $a->niveau_acces,
            ]);
        }catch(Exception $e){
            die("Error: ".$e->getMessage());
        }
    }

    public function removeAdmin($id){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("DELETE FROM admin WHERE id_admin  = :id");
            $req->execute([
                ":id" => $id
            ]);
        }catch(Exception $e){
            die("Error: ".$e->getMessage());
        }
    }

    public function updateAdmin($id,$a){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("UPDATE admin SET niveau_acces=:n
            WHERE id_admin =:id ");
            $req->execute([
                ":n" => $a->niveau_acces,
                ":id" => $id
            ]);
        }catch(Exception $e){
            die("Error: ".$e->getMessage());
        }
    }

    public function affAdmin($id){
        $db=config::getConnexion();
        try{
            $req = $db->prepare("SELECT * FROM admin WHERE id_admin  = :id");
            $req->execute([
                ":id" => $id
            ]);
            return $req-> fetch();
        }catch(Exception $e){
            die("Erreur ".$e->getMessage());
        }
    }
}
?>