<?php
class campagne_don{
    private int $id_campagne = 0;
    private string $titre;
    private string $ville;
    private string $lieu;
    private string $date_campagne;
    private string $heure_debut;
    private string $heure_fin;
    private ?string $description;
    private string $organisateur;
    private int $nb_places;
    private string $statut = "prevue";

    public function __construct(string $titre, string $ville, string $lieu, string $date_campagne,
                                string $heure_debut, string $heure_fin, ?string $description,
                                string $organisateur, int $nb_places
    ){
        $this->titre = $titre;
        $this->ville = $ville;
        $this->lieu = $lieu;
        $this->date_campagne = $date_campagne;
        $this->heure_debut = $heure_debut;
        $this->heure_fin = $heure_fin;
        $this->description = $description;
        $this->organisateur = $organisateur;
        $this->nb_places = $nb_places;
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
