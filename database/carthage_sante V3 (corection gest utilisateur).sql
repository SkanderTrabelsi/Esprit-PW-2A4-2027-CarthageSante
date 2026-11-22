-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 22 nov. 2026 à 17:21
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `carthage_sante`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `niveau_acces` enum('super','moderateur') DEFAULT 'moderateur'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id_admin`, `id_utilisateur`, `niveau_acces`) VALUES
(1, 4, 'super');

-- --------------------------------------------------------

--
-- Structure de la table `medecins`
--

CREATE TABLE `medecins` (
  `id_medecin` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `specialite` varchar(100) NOT NULL,
  `num_ordre_medecin` varchar(50) NOT NULL,
  `adresse_cabinet` varchar(255) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `horaires_travail` varchar(255) DEFAULT NULL,
  `teleconsultation_active` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `medecins`
--

INSERT INTO `medecins` (`id_medecin`, `id_utilisateur`, `specialite`, `num_ordre_medecin`, `adresse_cabinet`, `ville`, `latitude`, `longitude`, `horaires_travail`, `teleconsultation_active`) VALUES
(1, 2, 'generaliste', '0125445', 'Ariana, Ennaser', 'Ariana', 80.12345600, 100.12345600, 'Lun-Ven', 0);

-- --------------------------------------------------------

--
-- Structure de la table `pharmacies`
--

CREATE TABLE `pharmacies` (
  `id_pharmacie` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `nom_officine` varchar(150) NOT NULL,
  `num_autorisation` varchar(50) NOT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `horaires_ouverture` varchar(255) DEFAULT NULL,
  `type_ouverture` enum('jour','nuit') DEFAULT 'jour',
  `est_de_garde` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `pharmacies`
--

INSERT INTO `pharmacies` (`id_pharmacie`, `id_utilisateur`, `nom_officine`, `num_autorisation`, `adresse`, `ville`, `latitude`, `longitude`, `horaires_ouverture`, `type_ouverture`, `est_de_garde`) VALUES
(1, 3, 'Nour\'s', '0125445', 'Tunis, hbib bourgiba', 'Tunis', 20.65432100, 150.65432100, 'Lun-Jeu', 'jour', 0);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe_hash` varchar(255) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `photo_profil_url` varchar(255) DEFAULT NULL,
  `role` enum('admin','patient','medecin','pharmacie') NOT NULL,
  `statut` enum('actif','en_attente','suspendu') DEFAULT 'actif',
  `date_creation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `nom`, `prenom`, `email`, `mot_de_passe_hash`, `telephone`, `photo_profil_url`, `role`, `statut`, `date_creation`) VALUES
(1, 'Skander', 'TRABELSI', 'skander.trabelsi@esprit.tn', '$2y$10$xqu0cKKvCT/ZmrqOMGBgSeQKf/74QuILXtk5UBO7fLFB5UdsK.QiG', '93524523', NULL, 'patient', 'actif', '2026-11-21 22:37:05'),
(2, 'Louay', 'CHOKKI', 'louay.chokki@esprit.tn', '$2y$10$wlmsYMHzYXZrxEPKNuPf8eGvJ6HEfjH.tit4ugu4GJCZ8EqjEJX4y', '92345678', NULL, 'medecin', 'en_attente', '2026-11-22 00:52:15'),
(3, 'Nour', 'Azzabi', 'nour.azzabi@esprit.tn', '$2y$10$XszIKY6TUPWl29WK0c8YrufPY95JM9wBcpxnK3ruVanPke68vB31q', '92345678', NULL, 'pharmacie', 'en_attente', '2026-11-22 00:54:43'),
(4, 'Skander', 'Admin', 'skander.3.trabelsi@gmail.com', '$2y$10$bk2hSricpb9jtCZn39ms9uZZQdRXzVCC1XhEnmpnsYaQwDaDYpASG', '93524523', NULL, 'admin', 'actif', '2026-11-22 02:57:39');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `medecins`
--
ALTER TABLE `medecins`
  ADD PRIMARY KEY (`id_medecin`),
  ADD UNIQUE KEY `id_utilisateur` (`id_utilisateur`),
  ADD UNIQUE KEY `num_ordre_medecin` (`num_ordre_medecin`);

--
-- Index pour la table `pharmacies`
--
ALTER TABLE `pharmacies`
  ADD PRIMARY KEY (`id_pharmacie`),
  ADD UNIQUE KEY `id_utilisateur` (`id_utilisateur`),
  ADD UNIQUE KEY `num_autorisation` (`num_autorisation`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `medecins`
--
ALTER TABLE `medecins`
  MODIFY `id_medecin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `pharmacies`
--
ALTER TABLE `pharmacies`
  MODIFY `id_pharmacie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `fk_admin_user` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `medecins`
--
ALTER TABLE `medecins`
  ADD CONSTRAINT `fk_medecin_user` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `pharmacies`
--
ALTER TABLE `pharmacies`
  ADD CONSTRAINT `fk_pharmacie_user` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
