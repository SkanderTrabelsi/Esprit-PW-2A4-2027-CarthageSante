<?php
class pharmacie{
    private int $id_pharmacie = 0;
    private int $id_utilisateur;
    private string $nom_officine;
    private string $num_autorisation;
    private string $adresse;
    private string $ville;
    private ?string $latitude;          
    private ?string $longitude;
    private string $horaires_ouverture;
    private string $type_ouverture = "";
    private int $est_de_garde = 0;
    private string $is_valide = "";

    public function __construct(int $id_utilisateur,string $nom_officine, string $num_autorisation, string $adresse,string $ville,
                                ?string $latitude, ?string $longitude, string $horaires_ouverture
    ){
        $this->id_utilisateur = $id_utilisateur;
        $this->nom_officine = $nom_officine;
        $this->num_autorisation = $num_autorisation;
        $this->adresse = $adresse;
        $this->ville = $ville;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->horaires_ouverture = $horaires_ouverture;
        
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