<?php
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../Models/campagne_don.php';

class campagne_donC{
    const STATUTS = ['prevue', 'en_cours', 'terminee', 'annulee'];

    //libelle et couleur bootstrap de chaque statut
    const LIBELLES = [
        'prevue'   => ['Prévue', 'primary'],
        'en_cours' => ['En cours', 'success'],
        'terminee' => ['Terminée', 'secondary'],
        'annulee'  => ['Annulée', 'danger']
    ];

    //date d'aujourd'hui en heure de Tunis
    private function aujourdhui(): string{
        return (new DateTime('now', new DateTimeZone('Africa/Tunis')))->format('Y-m-d');
    }

    //toutes les campagnes avec le nombre d'inscrits (back office)
    public function listeCampagne(){
        $db = config::getConnexion();
        try{
            $liste = $db->query("SELECT c.*, 
                    (SELECT COUNT(*) FROM inscriptions_don i 
                     WHERE i.id_campagne = c.id_campagne AND i.statut <> 'annulee') AS nb_inscrits
                FROM campagnes_don c ORDER BY c.date_campagne DESC");
            return $liste;
        }catch(Exception $e){
            die("Erreur: ".$e->getMessage());
        }
    }

    //campagnes encore ouvertes (front office)
    public function listeCampagneOuverte(){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("SELECT c.*, 
                    (SELECT COUNT(*) FROM inscriptions_don i 
                     WHERE i.id_campagne = c.id_campagne AND i.statut <> 'annulee') AS nb_inscrits
                FROM campagnes_don c 
                WHERE c.statut IN ('prevue','en_cours') AND c.date_campagne >= :d
                ORDER BY c.date_campagne ASC, c.heure_debut ASC");
            $req->execute([":d" => $this->aujourdhui()]);
            return $req;
        }catch(Exception $e){
            die("Erreur: ".$e->getMessage());
        }
    }

    //ids des campagnes ou l'utilisateur est deja inscrit
    public function campagnesInscrites($idUtilisateur): array{
        $db = config::getConnexion();
        try{
            $req = $db->prepare("SELECT id_campagne FROM inscriptions_don 
                                 WHERE id_utilisateur = :idu AND statut <> 'annulee'");
            $req->execute([":idu" => $idUtilisateur]);
            return $req->fetchAll(PDO::FETCH_COLUMN);
        }catch(Exception $e){
            die("Erreur: ".$e->getMessage());
        }
    }

    public function affCampagne($id){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("SELECT c.*, 
                    (SELECT COUNT(*) FROM inscriptions_don i 
                     WHERE i.id_campagne = c.id_campagne AND i.statut <> 'annulee') AS nb_inscrits
                FROM campagnes_don c WHERE c.id_campagne = :id");
            $req->execute([":id" => $id]);
            return $req->fetch();
        }catch(Exception $e){
            die("Erreur: ".$e->getMessage());
        }
    }

    public function addCampagne($c){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("INSERT INTO campagnes_don 
                (titre, ville, lieu, date_campagne, heure_debut, heure_fin, description, organisateur, nb_places)
                VALUES(:t,:v,:l,:d,:hd,:hf,:de,:o,:n)");
            $req->execute([
                ":t"  => $c->titre,
                ":v"  => $c->ville,
                ":l"  => $c->lieu,
                ":d"  => $c->date_campagne,
                ":hd" => $c->heure_debut,
                ":hf" => $c->heure_fin,
                ":de" => $c->description,
                ":o"  => $c->organisateur,
                ":n"  => $c->nb_places
            ]);
        }catch(Exception $e){
            die("Error: ".$e->getMessage());
        }
    }

    public function removeCampagne($id){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("DELETE FROM campagnes_don WHERE id_campagne = :id");
            $req->execute([":id" => $id]);
        }catch(Exception $e){
            die("Error: ".$e->getMessage());
        }
    }

    //controle de saisie + ajout (retourne la liste des erreurs, vide si tout est bon)
    public function ajouterCampagne(array $post): array{
        $d = $this->nettoyer($post);
        $erreurs = $this->controle($d, true);
        if (!empty($erreurs)) {
            return $erreurs;
        }
        $c = new campagne_don($d['titre'], $d['ville'], $d['lieu'], $d['date_campagne'], $d['heure_debut'],
                              $d['heure_fin'], $d['description'], $d['organisateur'], (int) $d['nb_places']);
        $this->addCampagne($c);
        return [];
    }

    //controle de saisie + modification
    public function updateCampagne($id, array $post): array{
        $d = $this->nettoyer($post);
        $erreurs = $this->controle($d, false);

        if (!in_array($d['statut'], self::STATUTS, true)) {
            $erreurs[] = "Le statut choisi est invalide.";
        }
        //on ne peut pas descendre en dessous du nombre d'inscrits
        $actuelle = $this->affCampagne($id);
        if ($actuelle && ctype_digit($d['nb_places']) && (int) $d['nb_places'] < (int) $actuelle['nb_inscrits']) {
            $erreurs[] = "Le nombre de places ne peut pas être inférieur au nombre d'inscrits (" . $actuelle['nb_inscrits'] . ").";
        }
        if (!empty($erreurs)) {
            return $erreurs;
        }

        $db = config::getConnexion();
        try{
            $req = $db->prepare("UPDATE campagnes_don SET titre=:t, ville=:v, lieu=:l, date_campagne=:d,
                heure_debut=:hd, heure_fin=:hf, description=:de, organisateur=:o, nb_places=:n, statut=:s
                WHERE id_campagne=:id");
            $req->execute([
                ":t"  => $d['titre'],
                ":v"  => $d['ville'],
                ":l"  => $d['lieu'],
                ":d"  => $d['date_campagne'],
                ":hd" => $d['heure_debut'],
                ":hf" => $d['heure_fin'],
                ":de" => $d['description'],
                ":o"  => $d['organisateur'],
                ":n"  => (int) $d['nb_places'],
                ":s"  => $d['statut'],
                ":id" => $id
            ]);
            return [];
        }catch(Exception $e){
            die("Error: ".$e->getMessage());
        }
    }

    //recuperation des champs et tandhifa
    private function nettoyer(array $post): array{
        $description = trim($post['description'] ?? '');
        return [
            'titre'         => trim($post['titre'] ?? ''),
            'ville'         => trim($post['ville'] ?? ''),
            'lieu'          => trim($post['lieu'] ?? ''),
            'date_campagne' => trim($post['date_campagne'] ?? ''),
            'heure_debut'   => trim($post['heure_debut'] ?? ''),
            'heure_fin'     => trim($post['heure_fin'] ?? ''),
            'description'   => ($description === '') ? null : $description,
            'organisateur'  => trim($post['organisateur'] ?? ''),
            'nb_places'     => trim($post['nb_places'] ?? ''),
            'statut'        => trim($post['statut'] ?? 'prevue')
        ];
    }

    private function dateValide(string $d): bool{
        $x = DateTime::createFromFormat('Y-m-d', $d);
        return $x && $x->format('Y-m-d') === $d;
    }

    private function heureValide(string $h): bool{
        return (bool) preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $h);
    }

    //controle de saisie ($creation = true : la date ne doit pas etre dans le passe)
    private function controle(array $d, bool $creation): array{
        $erreurs = [];

        if ($d['titre'] === '') {
            $erreurs[] = "Le titre est obligatoire.";
        } elseif (mb_strlen($d['titre']) > 150) {
            $erreurs[] = "Le titre ne doit pas dépasser 150 caractères.";
        }
        if ($d['ville'] === '') {
            $erreurs[] = "La ville est obligatoire.";
        } elseif (mb_strlen($d['ville']) > 100) {
            $erreurs[] = "La ville ne doit pas dépasser 100 caractères.";
        }
        if ($d['lieu'] === '') {
            $erreurs[] = "Le lieu est obligatoire.";
        } elseif (mb_strlen($d['lieu']) > 255) {
            $erreurs[] = "Le lieu ne doit pas dépasser 255 caractères.";
        }
        if ($d['organisateur'] === '') {
            $erreurs[] = "L'organisateur est obligatoire.";
        } elseif (mb_strlen($d['organisateur']) > 150) {
            $erreurs[] = "L'organisateur ne doit pas dépasser 150 caractères.";
        }
        if ($d['description'] !== null && mb_strlen($d['description']) > 1000) {
            $erreurs[] = "La description ne doit pas dépasser 1000 caractères.";
        }

        if ($d['date_campagne'] === '') {
            $erreurs[] = "La date de la campagne est obligatoire.";
        } elseif (!$this->dateValide($d['date_campagne'])) {
            $erreurs[] = "La date de la campagne est invalide.";
        } elseif ($creation && $d['date_campagne'] < $this->aujourdhui()) {
            $erreurs[] = "La date de la campagne ne peut pas être dans le passé.";
        }

        $heuresOk = true;
        if (!$this->heureValide($d['heure_debut'])) {
            $erreurs[] = "L'heure de début est invalide.";
            $heuresOk = false;
        }
        if (!$this->heureValide($d['heure_fin'])) {
            $erreurs[] = "L'heure de fin est invalide.";
            $heuresOk = false;
        }
        if ($heuresOk && $d['heure_fin'] <= $d['heure_debut']) {
            $erreurs[] = "L'heure de fin doit être après l'heure de début.";
        }

        if ($d['nb_places'] === '') {
            $erreurs[] = "Le nombre de places est obligatoire.";
        } elseif (!ctype_digit($d['nb_places']) || (int) $d['nb_places'] < 1 || (int) $d['nb_places'] > 10000) {
            $erreurs[] = "Le nombre de places doit être un entier entre 1 et 10000.";
        }

        return $erreurs;
    }
}
