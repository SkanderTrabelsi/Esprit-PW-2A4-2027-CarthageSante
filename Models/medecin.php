<?php
class medecin{
    private int $id_medecin  = 0;
    private int $id_utilisateur;
    private string $specialite;
    private string $num_ordre_medecin;
    private string $adresse_cabinet;
    private string $ville;
    private ?string $latitude;          
    private ?string $longitude;
    private string $horaires_travail;
    private int $teleconsultation_active = 0;
    private string $is_valide = "";

    public function __construct(int $id_utilisateur,string $specialite, string $num_ordre_medecin, string $adresse_cabinet,string $ville,
                                ?string $latitude, ?string $longitude, string $horaires_travail
    ){
        $this->id_utilisateur = $id_utilisateur;
        $this->specialite = $specialite;
        $this->num_ordre_medecin = $num_ordre_medecin;
        $this->adresse_cabinet = $adresse_cabinet;
        $this->ville = $ville;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->horaires_travail = $horaires_travail;
        
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