<?php
// formulaire partage par add_campagne.php et modif_campagne.php
// variables attendues : $v (valeurs), $modif (true = modification)
$hv = fn($k) => htmlspecialchars((string) ($v[$k] ?? ''), ENT_QUOTES, 'UTF-8');
?>
<form action="" method="post" id="formu" data-creation="<?= $modif ? '0' : '1' ?>">
    <?= csrf::champ() ?>

    <label class="form-label" for="campTitre">Titre</label>
    <span class="err"></span>
    <div class="input-group mb-3">
        <input id="campTitre" name="titre" type="text" class="form-control" placeholder="Ex : Collecte de sang ESPRIT" maxlength="150" value="<?= $hv('titre') ?>" />
        <div class="input-group-text"><span class="bi bi-droplet"></span></div>
    </div>

    <label class="form-label" for="campVille">Ville</label>
    <span class="err"></span>
    <div class="input-group mb-3">
        <input id="campVille" name="ville" type="text" class="form-control" placeholder="Ville" maxlength="100" value="<?= $hv('ville') ?>" />
        <div class="input-group-text"><span class="bi bi-geo-alt"></span></div>
    </div>

    <label class="form-label" for="campLieu">Lieu</label>
    <span class="err"></span>
    <div class="input-group mb-3">
        <input id="campLieu" name="lieu" type="text" class="form-control" placeholder="Adresse ou nom du lieu" maxlength="255" value="<?= $hv('lieu') ?>" />
        <div class="input-group-text"><span class="bi bi-pin-map"></span></div>
    </div>

    <label class="form-label" for="campDate">Date de la campagne</label>
    <span class="err"></span>
    <div class="input-group mb-3">
        <input id="campDate" name="date_campagne" type="date" class="form-control" value="<?= $hv('date_campagne') ?>" />
        <div class="input-group-text"><span class="bi bi-calendar3"></span></div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <label class="form-label" for="campHeureDebut">Heure de début</label>
            <span class="err"></span>
            <div class="input-group mb-3">
                <input id="campHeureDebut" name="heure_debut" type="time" class="form-control" value="<?= htmlspecialchars(substr((string) ($v['heure_debut'] ?? ''), 0, 5), ENT_QUOTES, 'UTF-8') ?>" />
                <div class="input-group-text"><span class="bi bi-clock"></span></div>
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="campHeureFin">Heure de fin</label>
            <span class="err"></span>
            <div class="input-group mb-3">
                <input id="campHeureFin" name="heure_fin" type="time" class="form-control" value="<?= htmlspecialchars(substr((string) ($v['heure_fin'] ?? ''), 0, 5), ENT_QUOTES, 'UTF-8') ?>" />
                <div class="input-group-text"><span class="bi bi-clock-history"></span></div>
            </div>
        </div>
    </div>

    <label class="form-label" for="campOrganisateur">Organisateur</label>
    <span class="err"></span>
    <div class="input-group mb-3">
        <input id="campOrganisateur" name="organisateur" type="text" class="form-control" placeholder="Ex : CNTS" maxlength="150" value="<?= $hv('organisateur') ?>" />
        <div class="input-group-text"><span class="bi bi-building"></span></div>
    </div>

    <label class="form-label" for="campPlaces">Nombre de places</label>
    <span class="err"></span>
    <div class="input-group mb-3">
        <input id="campPlaces" name="nb_places" type="text" class="form-control" placeholder="Ex : 50" value="<?= $hv('nb_places') ?>" />
        <div class="input-group-text"><span class="bi bi-people"></span></div>
    </div>

    <?php if ($modif) { ?>
        <label class="form-label" for="campStatut">Statut</label>
        <span class="err"></span>
        <div class="input-group mb-3">
            <select id="campStatut" name="statut" class="form-select">
                <?php foreach (campagne_donC::LIBELLES as $cle => $lib) { ?>
                    <option value="<?= $cle ?>" <?= (($v['statut'] ?? '') === $cle) ? 'selected' : '' ?>><?= $lib[0] ?></option>
                <?php } ?>
            </select>
            <div class="input-group-text"><span class="bi bi-toggles"></span></div>
        </div>
    <?php } ?>

    <label class="form-label" for="campDescription">Description (facultatif)</label>
    <span class="err"></span>
    <div class="input-group mb-3">
        <textarea id="campDescription" name="description" class="form-control" rows="3" maxlength="1000"><?= $hv('description') ?></textarea>
    </div>

    <div class="d-flex gap-2 mb-3">
        <button type="submit" class="btn btn-primary"><?= $modif ? 'Enregistrer les modifications' : 'Ajouter la campagne' ?></button>
        <a href="liste_campagne.php" class="btn btn-outline-secondary">Annuler</a>
    </div>
</form>
