-- ============================================================
-- Module Don de sang (Abed El Malek TRABELSI)
-- Tables : campagnes_don et inscriptions_don
-- A importer APRES la base de l'equipe (la table utilisateurs doit exister)
-- ============================================================

--
-- Structure de la table `campagnes_don`
--

CREATE TABLE `campagnes_don` (
  `id_campagne` int(11) NOT NULL,
  `titre` varchar(150) NOT NULL,
  `ville` varchar(100) NOT NULL,
  `lieu` varchar(255) NOT NULL,
  `date_campagne` date NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `description` text DEFAULT NULL,
  `organisateur` varchar(150) NOT NULL,
  `nb_places` int(11) NOT NULL,
  `statut` enum('prevue','en_cours','terminee','annulee') NOT NULL DEFAULT 'prevue'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inscriptions_don`
--

CREATE TABLE `inscriptions_don` (
  `id_inscription` int(11) NOT NULL,
  `id_campagne` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `type_don` varchar(50) NOT NULL,
  `nature_don` varchar(50) NOT NULL,
  `date_rdv` date NOT NULL,
  `heure_rdv` time NOT NULL,
  `deja_donne` tinyint(1) NOT NULL DEFAULT 0,
  `date_dernier_don` date DEFAULT NULL,
  `lieu_dernier_don` varchar(255) DEFAULT NULL,
  `statut` enum('en_attente','confirmee','annulee','effectuee') NOT NULL DEFAULT 'en_attente',
  `date_inscription` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index
--
ALTER TABLE `campagnes_don`
  ADD PRIMARY KEY (`id_campagne`);

ALTER TABLE `inscriptions_don`
  ADD PRIMARY KEY (`id_inscription`),
  ADD KEY `id_campagne` (`id_campagne`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- AUTO_INCREMENT
--
ALTER TABLE `campagnes_don`
  MODIFY `id_campagne` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `inscriptions_don`
  MODIFY `id_inscription` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes (1 campagne -> 0..* inscriptions ; 1 utilisateur -> 0..* inscriptions)
--
ALTER TABLE `inscriptions_don`
  ADD CONSTRAINT `fk_inscription_campagne` FOREIGN KEY (`id_campagne`) REFERENCES `campagnes_don` (`id_campagne`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_inscription_user` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE;
