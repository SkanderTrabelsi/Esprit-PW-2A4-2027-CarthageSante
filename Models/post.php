<?php
/**
 * models/Post.php
 * Module FORUM — entité `posts`
 */
class Post
{
    public const TYPES   = ['question', 'information'];
    public const STATUTS = ['public', 'modere'];

    private ?int $id_post;
    private int $id_utilisateur;
    private string $contenu;
    private string $type;
    private ?string $date_publication;
    private string $statut;

    public function __construct(
        int $id_utilisateur,
        string $contenu,
        string $type,
        string $statut = 'public',
        ?int $id_post = null,
        ?string $date_publication = null
    ) {
        $this->id_post          = $id_post;
        $this->id_utilisateur   = $id_utilisateur;
        $this->setContenu($contenu);
        $this->setType($type);
        $this->setStatut($statut);
        $this->date_publication = $date_publication;
    }

    /** Construit un Post à partir d'une ligne de la table `posts`. */
    public static function fromArray(array $row): self
    {
        return new self(
            (int) $row['id_utilisateur'],
            $row['contenu'],
            $row['type'],
            $row['statut'] ?? 'public',
            isset($row['id_post']) ? (int) $row['id_post'] : null,
            $row['date_publication'] ?? null
        );
    }

    // ---------- getters ----------
    public function getIdPost(): ?int            { return $this->id_post; }
    public function getIdUtilisateur(): int      { return $this->id_utilisateur; }
    public function getContenu(): string         { return $this->contenu; }
    public function getType(): string            { return $this->type; }
    public function getDatePublication(): ?string { return $this->date_publication; }
    public function getStatut(): string          { return $this->statut; }

    // ---------- setters (avec validation) ----------
    public function setContenu(string $contenu): void
    {
        $contenu = trim($contenu);
        if ($contenu === '') {
            throw new InvalidArgumentException("Le contenu du post est obligatoire.");
        }
        $this->contenu = $contenu;
    }

    public function setType(string $type): void
    {
        if (!in_array($type, self::TYPES, true)) {
            throw new InvalidArgumentException("Type invalide : 'question' ou 'information' attendu.");
        }
        $this->type = $type;
    }

    public function setStatut(string $statut): void
    {
        if (!in_array($statut, self::STATUTS, true)) {
            throw new InvalidArgumentException("Statut invalide : 'public' ou 'modere' attendu.");
        }
        $this->statut = $statut;
    }

    /** Tableau associatif (utile pour les vues ou une réponse JSON). */
    public function toArray(): array
    {
        return [
            'id_post'          => $this->id_post,
            'id_utilisateur'   => $this->id_utilisateur,
            'contenu'          => $this->contenu,
            'type'             => $this->type,
            'date_publication' => $this->date_publication,
            'statut'           => $this->statut,
        ];
    }
}