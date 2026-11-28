<?php

require_once __DIR__ . '/../../config/database.php';

class Reponses_post
{
    private PDO $db;

    public function __construct()
    {
         $this->db = config::getConnexion()();
    }

    /** Réponses d'un post, de la plus ancienne à la plus récente, avec l'auteur. */
    public function liste_reponses(int $id_post): array
    {
        $stmt = $this->db->prepare(
            "SELECT r.id_reponse_post, r.id_post, r.id_utilisateur, r.contenu,
                    r.date_reponse, u.nom, u.prenom, u.photo_profil_url, u.role
               FROM reponses_post r
               JOIN utilisateurs u ON u.id_utilisateur = r.id_utilisateur
              WHERE r.id_post = :id
              ORDER BY r.date_reponse ASC"
        );
        $stmt->execute([':id' => $id_post]);
        return $stmt->fetchAll();
    }

    /** Une seule réponse (pré-remplissage du formulaire de modification). */
    public function get_reponse(int $id_reponse_post): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM reponses_post WHERE id_reponse_post = :id"
        );
        $stmt->execute([':id' => $id_reponse_post]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Ajoute une réponse et retourne son id.
     * Refuse si le post n'existe pas ou n'est pas public.
     */
    public function addreponse(int $id_post, int $id_utilisateur, string $contenu): int
    {
        $contenu = trim($contenu);
        if ($contenu === '') {
            throw new InvalidArgumentException("Le contenu de la réponse est obligatoire.");
        }

        $check = $this->db->prepare(
            "SELECT 1 FROM posts WHERE id_post = :id AND statut = 'public'"
        );
        $check->execute([':id' => $id_post]);
        if (!$check->fetchColumn()) {
            throw new InvalidArgumentException("Ce post n'existe pas ou n'est plus accessible.");
        }

        $stmt = $this->db->prepare(
            "INSERT INTO reponses_post (id_post, id_utilisateur, contenu, date_reponse)
             VALUES (:p, :u, :c, NOW())"
        );
        $stmt->execute([':p' => $id_post, ':u' => $id_utilisateur, ':c' => $contenu]);
        return (int) $this->db->lastInsertId();
    }

    /** Supprime une réponse (auteur uniquement ; la modération se fait côté back). */
    public function removereponse(int $id_reponse_post, int $id_utilisateur): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM reponses_post
              WHERE id_reponse_post = :id AND id_utilisateur = :u"
        );
        $stmt->execute([':id' => $id_reponse_post, ':u' => $id_utilisateur]);
        return $stmt->rowCount() > 0;
    }

    /** Modifie le contenu d'une réponse (auteur uniquement). */
    public function modifyreponse(int $id_reponse_post, int $id_utilisateur, string $contenu): bool
    {
        $contenu = trim($contenu);
        if ($contenu === '') {
            throw new InvalidArgumentException("Le contenu de la réponse est obligatoire.");
        }

        $stmt = $this->db->prepare(
            "UPDATE reponses_post
                SET contenu = :c
              WHERE id_reponse_post = :id AND id_utilisateur = :u"
        );
        $stmt->execute([':c' => $contenu, ':id' => $id_reponse_post, ':u' => $id_utilisateur]);
        return $stmt->rowCount() > 0;
    }
}