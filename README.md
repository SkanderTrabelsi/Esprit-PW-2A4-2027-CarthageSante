# Carthage Santé – Plateforme web de santé familiale

> Projet Technologies Web 2A – **Esprit School of Engineering** – Année universitaire 2026-2027
> Équipe : **NovaLAB**

## Table des matières

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Architecture](#architecture)
- [Contributors](#contributors)
- [Academic Context](#academic-context)
- [Acknowledgments](#acknowledgments)

## Overview

Ce projet a été réalisé dans le cadre du module **Projet Technologies Web 2A** à **Esprit School of Engineering** (année universitaire 2026-2027).

**Carthage Santé** est une plateforme web de santé qui centralise, dans un seul espace simple et sécurisé, les informations de santé et les services de proximité dont une famille tunisienne a besoin au quotidien.

### Contexte et problème

La santé numérique progresse en Tunisie (rendez-vous en ligne, applications de pharmacies de garde), mais les services restent **séparés** les uns des autres. Beaucoup de familles gèrent encore leur santé avec un carnet papier et leur mémoire : vaccins, contrôles et rendez-vous sont oubliés, les informations médicales sont dispersées, et trouver rapidement un médecin ou une pharmacie de garde reste difficile.

Les solutions existantes que nous avons étudiées ne couvrent chacune qu'une partie du besoin : pas de compte, pas de rappels, une interface peu moderne ou limitée à un chatbot, ou un service centré uniquement sur les pharmacies.

### Problématique

> Comment aider une famille tunisienne à suivre la santé de tous ses membres et à accéder rapidement aux services de santé proches (médecins, pharmacies, gardes), dans un seul espace simple et sécurisé ?

### Solution proposée

Carthage Santé est un **carnet de santé familial connecté**, avec :

- un espace personnel pour chaque utilisateur et un dossier médical,
- l'accès aux médecins et aux pharmacies partenaires (rendez-vous, médicaments, gardes),
- un **assistant IA** d'orientation (sans diagnostic) et un **scan de médicament par photo**,
- une communauté (forum) et des campagnes de **don de sang**,
- des espaces dédiés selon le rôle : client, médecin, pharmacien et administrateur.

### Objectifs

1. **Centraliser** les informations de santé de chaque utilisateur.
2. **Faciliter l'accès** aux services de santé : médecins, pharmacies, gardes, campagnes de don de sang.
3. **Guider** l'utilisateur grâce à une assistance par IA (orientation et identification de médicaments).
4. **Faire communiquer** les patients, les médecins et les pharmaciens sur une même plateforme.
5. **Réduire le gaspillage** et mieux utiliser les ressources de santé.

### Développement durable et IA

Le projet s'inscrit dans trois Objectifs de Développement Durable :

- **ODD 3** – Bonne santé et bien-être,
- **ODD 9** – Industrie, innovation et infrastructure,
- **ODD 12** – Consommation et production responsables.

L'intelligence artificielle intervient à deux endroits : un **assistant d'orientation** (pharmacies et médecins de garde, conseils généraux, sans jamais poser de diagnostic) et la **reconnaissance d'un médicament par photo**, qui affiche sa fiche et sa posologie issues de notre base de données.

### Identité visuelle

| Élément | Choix |
|---|---|
| Nom du site | Carthage Santé (référence à la culture tunisienne et à la santé et bien-être) |
| Vert profond | `#104840` (couleur principale) |
| Or sable | `#C09868` (couleur secondaire) |
| Crème ivoire | `#FDF9F0` (arrière-plan) |

## Features

### Acteurs

| Acteur | Rôle |
|---|---|
| Visiteur | Consulte le site et s'inscrit |
| Client | utilise les services de santé |
| Médecin | Gère ses rendez-vous et ses consultations |
| Pharmacien | Gère le catalogue de sa pharmacie et sa garde |
| Administrateur | Gère les comptes, les contenus et les statistiques |
| Service IA (acteur secondaire) | Assistant d'orientation et reconnaissance de médicaments |


### Fonctionnalités par module

**Tronc commun – Gestion des utilisateurs (travaillé par tout le groupe)**
- Inscription et connexion unique avec redirection selon le rôle.
- Statuts de compte.
- Gestion des profil.
- Espace administrateur.
- Authentification avancée.
- Tableau de bord administrateur avec statistiques.

**Médicaments et pharmacies**
- Catalogue de médicaments classés par catégorie, avec gestion du stock.
- Pharmacies partenaires et pharmacies de garde.
- Scan d'un médicament par photo : affichage de sa fiche et de sa posologie.
- Assistant IA : orientation, pharmacies et médecins de nuit et de garde.

**Médecins et rendez-vous**
- Prise de rendez-vous chez les médecins partenaires.
- Consultation en ligne.
- Ordonnances et documents médicaux.

**Dossier médical**
- Dossier médical de chaque utilisateur.
- Suivi des maladies.

**Forum**
- Publications et réponses entre utilisateurs pour échanger conseils et expériences.

**Don de sang**
- Campagnes de don de sang à proximité.
- Inscription des donneurs aux campagnes.

**Réclamations**
- Envoi d'une réclamation à l'administrateur en cas de problème.

## Tech Stack

### Frontend

- HTML5, CSS3, JavaScript
- Template **Front Office** : Clinic
- Template **Back Office** : AdminLTE 4
- Contrôle de saisie des formulaires en JavaScript

### Backend

- PHP 8, programmation orientée objet
- Architecture **MVC**
- **PDO** + MySQL (requêtes préparées, gestion des erreurs par exceptions)
- Sessions PHP et mots de passe hachés

## Architecture

Le projet suit le modèle **MVC** :

- **Models** : les classes qui représentent les données (une classe par table).
- **Views** : l'interface PHP (Front Office et Back Office), avec les éléments communs dans les dossiers `includes/`.
- **Controllers** : reçoivent les requêtes, appellent le modèle et choisissent la vue.

```
Esprit-PW-2A4-2027-CarthageSante/
├── Controllers/
│   ├── front/
│   └── back/
├── Models/
├── Views/
│   ├── front/        (Clinic) pages + includes/
│   ├── back/         (AdminLTE 4) pages + includes/
│   └── assets/       CSS, images, JavaScript, bibliothèques
├── database/         dumps SQL de la base carthage_sante
├── db.php            connexion PDO
└── README.md
```

### Base de données
 
La base **carthage sante** (MySQL) contient un tronc commun de gestion des utilisateurs et les tables propres à chaque module.

Règles suivies : noms de tables en français, clé primaire `id`, clés étrangères `id_<table>`, aucune utilisation de MySQLi (PDO uniquement).

## Contributors

| Membre | Module et tables |
|---|---|
| Skander TRABELSI | Catégories de médicaments, médicaments et stock ; assistant IA et scan de médicament |
| Bechir BRAHMI | Réclamations |
| Rimes BEN ROMDHANE | Rendez-vous, consultations en ligne, ordonnances, documents |
| Nour AZZABI | Forum : posts et réponses |
| Louay CHOKKI | Maladies et dossier médical |
| Abed El Malek TRABELSI | Don de sang : campagnes et inscriptions |

Le tronc commun (gestion des utilisateurs) est réalisé par l'ensemble du groupe.

## Academic Context

Développé à **Esprit School of Engineering – Tunisie**
Projet Technologies Web – 2A | 2026-2027
Équipe NovaLAB – Classe 2A4

### Travailler en équipe

- Chaque membre travaille sur **sa propre branche** `module-<prénom>`.
- Aucun envoi direct sur `main` : on passe par une **pull request**, validée par le responsable du dépôt.

## Acknowledgments

Merci à l'équipe pédagogique du module Projet Technologies Web et à nos tuteurs pour leur accompagnement tout au long du projet.