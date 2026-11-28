<?php
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../Models/inscription_don.php';

class inscription_donC{
    const TYPES = ['Sang total', 'Plasma', 'Plaquettes'];
    const NATURES = ['Bénévole', 'Familial / dirigé'];
    const STATUTS = ['en_attente', 'confirmee', 'annulee', 'effectuee'];

    //libelle et couleur bootstrap de chaque statut
    const LIBELLES = [
        'en_attente' => ['En attente', 'warning'],
        'confirmee'  => ['Confirmée', 'success'],
        'annulee'    => ['Annulée', 'danger'],
        'effectuee'  => ['Effectuée', 'info']
    ];

    private function aujourdhui(): string{
        return (new DateTime('now', new DateTimeZone('Africa/Tunis')))->format('Y-m-d');
    }

    //inscrits d'une campagne avec les infos du donneur (back office)
    public function listeInscriptionCampagne($idCampagne){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("SELECT i.*, u.nom, u.prenom, u.email, u.telephone, u.photo_profil_url
                FROM inscriptions_don i
                JOIN utilisateurs u ON u.id_utilisateur = i.id_utilisateur
                WHERE i.id_campagne = :id ORDER BY i.heure_rdv ASC");
            $req->execute([":id" => $idCampagne]);
            return $req;
        }catch(Exception $e){
            die("Erreur: ".$e->getMessage());
        }
    }

    //inscriptions d'un utilisateur avec les infos de la campagne (front office)
    public function listeInscriptionUtilisateur($idUtilisateur){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("SELECT i.*, c.titre, c.ville, c.lieu
                FROM inscriptions_don i
                JOIN campagnes_don c ON c.id_campagne = i.id_campagne
                WHERE i.id_utilisateur = :idu ORDER BY i.date_rdv DESC, i.heure_rdv DESC");
            $req->execute([":idu" => $idUtilisateur]);
            return $req;
        }catch(Exception $e){
            die("Erreur: ".$e->getMessage());
        }
    }

    public function addInscription($i){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("INSERT INTO inscriptions_don 
                (id_campagne, id_utilisateur, type_don, nature_don, date_rdv, heure_rdv, deja_donne, date_dernier_don, lieu_dernier_don)
                VALUES(:idc,:idu,:t,:n,:d,:h,:dd,:ddd,:ldd)");
            $req->execute([
                ":idc" => $i->id_campagne,
                ":idu" => $i->id_utilisateur,
                ":t"   => $i->type_don,
                ":n"   => $i->nature_don,
                ":d"   => $i->date_rdv,
                ":h"   => $i->heure_rdv,
                ":dd"  => $i->deja_donne,
                ":ddd" => $i->date_dernier_don,
                ":ldd" => $i->lieu_dernier_don
            ]);
        }catch(Exception $e){
            die("Error: ".$e->getMessage());
        }
    }

    //controle de saisie + inscription ($c = ligne de la campagne)
    public function inscrire(int $idUtilisateur, array $c, array $post): array{
        $d = $this->nettoyer($post);
        $erreurs = $this->controle($d, $c);
        if (!empty($erreurs)) {
            return $erreurs;
        }

        $db = config::getConnexion();
        try{
            //transaction : on bloque la campagne pour ne pas depasser le nombre de places
            $db->beginTransaction();
            $req = $db->prepare("SELECT nb_places FROM campagnes_don WHERE id_campagne = :id FOR UPDATE");
            $req->execute([":id" => $c['id_campagne']]);
            $places = (int) $req->fetchColumn();

            $req = $db->prepare("SELECT COUNT(*) AS total, SUM(id_utilisateur = :idu) AS deja 
                FROM inscriptions_don WHERE id_campagne = :id AND statut <> 'annulee'");
            $req->execute([":idu" => $idUtilisateur, ":id" => $c['id_campagne']]);
            $r = $req->fetch();

            if ((int) $r['deja'] > 0) {
                $db->rollBack();
                return ["Vous êtes déjà inscrit(e) à cette campagne."];
            }
            if ((int) $r['total'] >= $places) {
                $db->rollBack();
                return ["Il n'y a plus de places disponibles pour cette campagne."];
            }

            $i = new inscription_don((int) $c['id_campagne'], $idUtilisateur, $d['type_don'], $d['nature_don'],
                                     $d['date_rdv'], $d['heure_rdv'], (int) $d['deja_donne'],
                                     $d['date_dernier_don'], $d['lieu_dernier_don']);
            $this->addInscription($i);
            $db->commit();
            return [];
        }catch(Exception $e){
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            die("Error: ".$e->getMessage());
        }
    }

    //l'utilisateur annule sa propre inscription
    public function annulerInscription($id, $idUtilisateur){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("UPDATE inscriptions_don SET statut = 'annulee' 
                WHERE id_inscription = :id AND id_utilisateur = :idu AND statut IN ('en_attente','confirmee')");
            $req->execute([":id" => $id, ":idu" => $idUtilisateur]);
            return $req->rowCount() > 0;
        }catch(Exception $e){
            die("Error: ".$e->getMessage());
        }
    }

    //l'admin change le statut
    public function updateInscriptionStatut($id, $statut){
        if (!in_array($statut, self::STATUTS, true)) {
            return false;
        }
        $db = config::getConnexion();
        try{
            $req = $db->prepare("UPDATE inscriptions_don SET statut = :s WHERE id_inscription = :id");
            $req->execute([":s" => $statut, ":id" => $id]);
            return true;
        }catch(Exception $e){
            die("Error: ".$e->getMessage());
        }
    }

    public function removeInscription($id){
        $db = config::getConnexion();
        try{
            $req = $db->prepare("DELETE FROM inscriptions_don WHERE id_inscription = :id");
            $req->execute([":id" => $id]);
        }catch(Exception $e){
            die("Error: ".$e->getMessage());
        }
    }

    //recuperation des champs et tandhifa
    private function nettoyer(array $post): array{
        $dejaDonne = (($post['deja_donne'] ?? '') === '1') ? 1 : 0;
        $dateDernier = trim($post['date_dernier_don'] ?? '');
        $lieuDernier = trim($post['lieu_dernier_don'] ?? '');
        return [
            'type_don'         => trim($post['type_don'] ?? ''),
            'nature_don'       => trim($post['nature_don'] ?? ''),
            'date_rdv'         => trim($post['date_rdv'] ?? ''),
            'heure_rdv'        => trim($post['heure_rdv'] ?? ''),
            'deja_donne_saisi' => $post['deja_donne'] ?? '',
            'deja_donne'       => $dejaDonne,
            //si jamais donne, on ne garde pas les infos du dernier don
            'date_dernier_don' => ($dejaDonne && $dateDernier !== '') ? $dateDernier : null,
            'lieu_dernier_don' => ($dejaDonne && $lieuDernier !== '') ? $lieuDernier : null
        ];
    }

    private function dateValide(string $d): bool{
        $x = DateTime::createFromFormat('Y-m-d', $d);
        return $x && $x->format('Y-m-d') === $d;
    }

    private function controle(array $d, array $c): array{
        $erreurs = [];

        //la campagne doit etre ouverte
        if (!in_array($c['statut'], ['prevue', 'en_cours'], true) || $c['date_campagne'] < $this->aujourdhui()) {
            $erreurs[] = "Cette campagne n'accepte plus d'inscriptions.";
            return $erreurs;
        }

        if (!in_array($d['type_don'], self::TYPES, true)) {
            $erreurs[] = "Veuillez choisir un type de don valide.";
        }
        if (!in_array($d['nature_don'], self::NATURES, true)) {
            $erreurs[] = "Veuillez choisir la nature du don.";
        }

        //le rendez-vous a lieu le jour de la campagne, pendant ses horaires
        if ($d['date_rdv'] !== $c['date_campagne']) {
            $erreurs[] = "Le rendez-vous doit avoir lieu le jour de la campagne.";
        }
        $debut = substr($c['heure_debut'], 0, 5);
        $fin = substr($c['heure_fin'], 0, 5);
        if (!preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $d['heure_rdv'])) {
            $erreurs[] = "L'heure du rendez-vous est invalide.";
        } elseif ($d['heure_rdv'] < $debut || $d['heure_rdv'] >= $fin) {
            $erreurs[] = "L'heure du rendez-vous doit être comprise entre $debut et $fin.";
        }

        //deja donne : OUI / NON
        if ($d['deja_donne_saisi'] !== '0' && $d['deja_donne_saisi'] !== '1') {
            $erreurs[] = "Veuillez indiquer si vous avez déjà donné votre sang.";
        } elseif ($d['deja_donne'] === 1) {
            if ($d['date_dernier_don'] === null) {
                $erreurs[] = "La date du dernier don est obligatoire.";
            } elseif (!$this->dateValide($d['date_dernier_don']) || $d['date_dernier_don'] > $this->aujourdhui()) {
                $erreurs[] = "La date du dernier don est invalide.";
            }
            if ($d['lieu_dernier_don'] === null) {
                $erreurs[] = "Le lieu du dernier don est obligatoire.";
            } elseif (mb_strlen($d['lieu_dernier_don']) > 255) {
                $erreurs[] = "Le lieu du dernier don ne doit pas dépasser 255 caractères.";
            }
        }

        return $erreurs;
    }
}
