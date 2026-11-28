<?php
/**
 * models/ReponsePost.php
 * Module FORUM — entité `reponses_post`
 */
class ReponsePost
{
    private ?int $id_reponse_post;
    private int $id_post;
    private int $id_utilisateur;
    private string $contenu;
    private ?string $date_reponse;

    public function __construct(
        int $id_post,
        int $id_utilisateur,
        string $contenu,
        ?int $id_reponse_post = null,
        ?string $date_reponse = null
    ) {
        $this->id_reponse_post = $id_reponse_post;
        $this->id_post         = $id_post;
        $this->id_utilisateur  = $id_utilisateur;
        $this->setContenu($contenu);
        $this->date_reponse    = $date_reponse;
    }

    /** Construit une ReponsePost à partir d'une ligne de la table `reponses_post`. */
    public static function fromArray(array $row): self
    {
        return new self(
            (int) $row['id_post'],
            (int) $row['id_utilisateur'],
            $row['contenu'],
            isset($row['id_reponse_post']) ? (int) $row['id_reponse_post'] : null,
            $row['date_reponse'] ?? null
        );
    }

    // ---------- getters ----------
    public function getIdReponsePost(): ?int  { return $this->id_reponse_post; }
    public function getIdPost(): int          { return $this->id_post; }
    public function getIdUtilisateur(): int   { return $this->id_utilisateur; }
    public function getContenu(): string      { return $this->contenu; }
    public function getDateReponse(): ?string { return $this->date_reponse; }

    // ---------- setters (avec validation) ----------
    public function setContenu(string $contenu): void
    {
        $contenu = trim($contenu);
        if ($contenu === '') {
            throw new InvalidArgumentException("Le contenu de la réponse est obligatoire.");
        }
        $this->contenu = $contenu;
    }

    /** Tableau associatif (utile pour les vues ou une réponse JSON). */
    public function toArray(): array
    {
        return [
            'id_reponse_post' => $this->id_reponse_post,
            'id_post'         => $this->id_post,
            'id_utilisateur'  => $this->id_utilisateur,
            'contenu'         => $this->contenu,
            'date_reponse'    => $this->date_reponse,
        ];
    }
}