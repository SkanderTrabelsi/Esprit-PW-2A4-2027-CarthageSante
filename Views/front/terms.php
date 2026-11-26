<?php
$pageTitle = "Conditions Générales d'Utilisation";
$currentPage = "terms";
require_once '../../Controllers/front/includes/session.php';
?>

<!DOCTYPE html>
<html lang="fr">

<?php require_once './includes/head.php'; ?>

<body class="index-page">
    <?php require_once './includes/spinner.php'; ?>
    <?php require_once './includes/navbar.php'; ?>

    <main class="main">
        <!-- Page Title -->
        <div class="page-title page-title-compact">
            <div class="heading">
                <div class="container">
                    <div class="row d-flex justify-content-center text-center">
                        <div class="col-lg-8">
                            <h1 class="heading-title">Conditions Générales d'Utilisation</h1>
                            <p class="mb-0">
                                Règles d'utilisation de la plateforme Carthage Santé.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="breadcrumbs">
                <div class="container">
                    <ol>
                        <li><a href="index.php">Home</a></li>
                        <li class="current">Conditions d'utilisation</li>
                    </ol>
                </div>
            </nav>
        </div>

        <!-- Terms Section -->
        <section id="terms" class="contact section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="contact-form-card">
                            <div class="d-flex flex-wrap justify-content-between align-items-center border-bottom pb-3 mb-4 gap-2">
                                <span class="text-secondary small">
                                    <i class="bi bi-clock-history me-1" aria-hidden="true"></i>
                                    Dernière mise à jour : <?= date('d/m/Y') ?>
                                </span>
                                <span class="badge text-bg-light border">Carthage Santé</span>
                            </div>

                            <div class="mb-4">
                                <h2 class="h4">1. Objet de la plateforme</h2>
                                <p class="text-secondary">
                                    Carthage Santé est une plateforme numérique de mise en relation et d'information médicale
                                    permettant aux patients de rechercher des professionnels de santé (médecins, pharmacies),
                                    de consulter leurs horaires, leur disponibilité (téléconsultation, pharmacie de garde)
                                    et de gérer leur espace personnel.
                                </p>
                            </div>

                            <div class="mb-4">
                                <h2 class="h4">2. Acceptation des conditions</h2>
                                <p class="text-secondary">
                                    L'accès et l'utilisation de la plateforme impliquent l'acceptation pleine et entière
                                    des présentes Conditions Générales d'Utilisation (CGU). En créant un compte sur
                                    Carthage Santé, l'utilisateur reconnaît avoir lu et accepté ces conditions.
                                </p>
                            </div>

                            <div class="mb-4">
                                <h2 class="h4">3. Création de compte et sécurité</h2>
                                <p class="text-secondary mb-2">
                                    L'utilisateur s'engage à fournir des informations exactes, complètes et à jour lors
                                    de son inscription (nom, prénom, adresse e-mail, numéro de téléphone, ainsi que les
                                    justificatifs professionnels requis pour les médecins et pharmacies).
                                </p>
                                <ul class="text-secondary">
                                    <li>Chaque compte est personnel et confidentiel.</li>
                                    <li>L'utilisateur est responsable de la confidentialité de son mot de passe.</li>
                                    <li>Les comptes professionnels (médecins et pharmacies) sont soumis à une validation préalable avant activation.</li>
                                </ul>
                            </div>

                            <div class="mb-4">
                                <h2 class="h4">4. Obligations des professionnels de santé</h2>
                                <p class="text-secondary">
                                    Les médecins et pharmacies inscrits sur la plateforme garantissent l'exactitude de leur
                                    numéro d'ordre ou d'autorisation, de leur adresse, de leurs horaires d'ouverture ou de
                                    consultation, ainsi que de leur statut (téléconsultation active, pharmacie de jour/nuit ou de garde).
                                </p>
                            </div>

                            <div class="mb-4">
                                <h2 class="h4">5. Limitation de responsabilité médicale</h2>
                                <p class="text-secondary">
                                    Carthage Santé agit uniquement en tant qu'intermédiaire technique et annuaire de santé.
                                    Les informations publiées sur le site ne remplacent en aucun cas une consultation médicale,
                                    un diagnostic ou un avis professionnel. En cas d'urgence médicale, veuillez contacter
                                    immédiatement les services d'urgence compétents (SAMU / Protection Civile).
                                </p>
                            </div>

                            <div class="mb-4">
                                <h2 class="h4">6. Protection des données personnelles</h2>
                                <p class="text-secondary">
                                    Les données collectées (informations d'identité, coordonnées, données professionnelles)
                                    sont utilisées exclusivement pour le bon fonctionnement des services de Carthage Santé.
                                    Conformément à la réglementation en vigueur, chaque utilisateur peut modifier ses
                                    informations ou supprimer définitivement son compte depuis la page
                                    <a href="profile.php">Mon profil</a>.
                                </p>
                            </div>

                            <div class="mb-4">
                                <h2 class="h4">7. Suspension et suppression de compte</h2>
                                <p class="text-secondary">
                                    L'administration se réserve le droit de suspendre ou de supprimer tout compte en cas
                                    de non-respect des présentes conditions, d'usurpation d'identité ou de fourniture
                                    de fausses informations professionnelles.
                                </p>
                            </div>

                            <div class="mb-0">
                                <h2 class="h4">8. Contact</h2>
                                <p class="text-secondary mb-0">
                                    Pour toute question relative aux présentes Conditions Générales d'Utilisation, vous pouvez
                                    nous contacter via la rubrique <a href="contact.php">Contact</a> de la plateforme.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php require_once './includes/footer.php'; ?>
    <?php require_once './includes/scripts.php'; ?>

</body>

</html>