<?php
class admin{
    private int $id_admin = 0;
    private int $id_utilisateur;
    private string $niveau_acces;

    public function __construct(int $id_utilisateur,string $niveau_acces){
        $this->id_utilisateur = $id_utilisateur;
        $this->niveau_acces = $niveau_acces;        
    }

    public function __get($prop){
        if(property_exists($this, $prop)){
            return  $this-> $prop;
        }
    }

    public function __set($prop, $value){
        if(property_exists($this, $prop)){
            $this-> $prop = $value;
        }
    }
}