<?php
class utilisateur{
    private int $id_utilisateur = 0;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $mot_de_passe_hash;
    private ?string $telephone;          
    private ?string $photo_profil_url;
    private string $role;
    private string $statut;
    private string $date_creation = "";

    public function __construct(string $nom, string $prenom, string $email,string $mot_de_passe_hash,
                                ?string $telephone, ?string $photo_profil_url, string $role){
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->mot_de_passe_hash = $mot_de_passe_hash;
        $this->telephone = $telephone;
        $this->photo_profil_url = $photo_profil_url;
        $this->role = $role;
        $this->statut = ($role === 'patient' || $role === 'admin') ? 'actif' : 'en_attente';
        
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