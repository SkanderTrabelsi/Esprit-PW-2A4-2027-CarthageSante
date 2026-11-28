<?php
class inscription_don{
    private int $id_inscription = 0;
    private int $id_campagne;
    private int $id_utilisateur;
    private string $type_don;
    private string $nature_don;
    private string $date_rdv;
    private string $heure_rdv;
    private int $deja_donne = 0;
    private ?string $date_dernier_don;
    private ?string $lieu_dernier_don;
    private string $statut = "en_attente";
    private string $date_inscription = "";

    public function __construct(int $id_campagne, int $id_utilisateur, string $type_don, string $nature_don,
                                string $date_rdv, string $heure_rdv, int $deja_donne,
                                ?string $date_dernier_don, ?string $lieu_dernier_don
    ){
        $this->id_campagne = $id_campagne;
        $this->id_utilisateur = $id_utilisateur;
        $this->type_don = $type_don;
        $this->nature_don = $nature_don;
        $this->date_rdv = $date_rdv;
        $this->heure_rdv = $heure_rdv;
        $this->deja_donne = $deja_donne;
        $this->date_dernier_don = $date_dernier_don;
        $this->lieu_dernier_don = $lieu_dernier_don;
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
