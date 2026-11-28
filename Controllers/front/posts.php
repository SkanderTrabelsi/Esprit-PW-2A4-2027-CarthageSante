<?php
require_once __DIR__ . '/../../config/database.php';

class PostController
{
    private const TYPES = ['question', 'information'];

    private PDO $db;

    public function __construct()
    {
        $this->db = config::getConnexion()();
    }

    /**
     * Liste des posts publics (les plus récents d'abord), avec l'auteur
     * et le nombre de réponses. $type = 'question' | 'information' | null (tous).
     */
    public function liste_posts(?string $type = null): array
    {
        $sql = "SELECT p.id_post, p.id_utilisateur, p.contenu, p.type,
                       p.date_publication, p.statut,
                       u.nom, u.prenom, u.photo_profil_url,
                       (SELECT COUNT(*) FROM reponses_post r
                         WHERE r.id_post = p.id_post) AS nb_reponses
                  FROM posts p
                  JOIN utilisateurs u ON u.id_utilisateur = p.id_utilisateur
                 WHERE p.statut = 'public'";
        $params = [];

        if ($type !== null) {
            $this->verifierType($type);
            $sql .= " AND p.type = :type";
            $params[':type'] = $type;
        }

        $sql .= " ORDER BY p.date_publication DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Un seul post (utile pour pré-remplir le formulaire de modification). */
    public function get_post(int $id_post): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, u.nom, u.prenom
               FROM posts p
               JOIN utilisateurs u ON u.id_utilisateur = p.id_utilisateur
              WHERE p.id_post = :id"
        );
        $stmt->execute([':id' => $id_post]);
        return $stmt->fetch() ?: null;
    }

    /** Ajoute un post et retourne son id. Lève InvalidArgumentException si invalide. */
    public function addpost(int $id_utilisateur, string $contenu, string $type): int
    {
        $contenu = trim($contenu);
        $this->verifierContenu($contenu);
        $this->verifierType($type);

        $stmt = $this->db->prepare(
            "INSERT INTO posts (id_utilisateur, contenu, type, date_publication, statut)
             VALUES (:u, :c, :t, NOW(), 'public')"
        );
        $stmt->execute([':u' => $id_utilisateur, ':c' => $contenu, ':t' => $type]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Supprime un post. Seul son auteur peut le supprimer ici
     * (la modération admin se fait côté controllers/back).
     * Les réponses liées sont supprimées par ON DELETE CASCADE.
     */
    public function removepost(int $id_post, int $id_utilisateur): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM posts WHERE id_post = :id AND id_utilisateur = :u"
        );
        $stmt->execute([':id' => $id_post, ':u' => $id_utilisateur]);
        return $stmt->rowCount() > 0;
    }

    /** Modifie le contenu et le type d'un post (auteur uniquement). */
    public function modifypost(int $id_post, int $id_utilisateur, string $contenu, string $type): bool
    {
        $contenu = trim($contenu);
        $this->verifierContenu($contenu);
        $this->verifierType($type);

        $stmt = $this->db->prepare(
            "UPDATE posts
                SET contenu = :c, type = :t
              WHERE id_post = :id AND id_utilisateur = :u"
        );
        $stmt->execute([
            ':c' => $contenu, ':t' => $type,
            ':id' => $id_post, ':u' => $id_utilisateur,
        ]);
        return $stmt->rowCount() > 0;
    }

    // ---------- validation ----------

    private function verifierContenu(string $contenu): void
    {
        if ($contenu === '') {
            throw new InvalidArgumentException("Le contenu du post est obligatoire.");
        }
    }

    private function verifierType(string $type): void
    {
        if (!in_array($type, self::TYPES, true)) {
            throw new InvalidArgumentException("Type invalide : 'question' ou 'information' attendu.");
        }
    }
}