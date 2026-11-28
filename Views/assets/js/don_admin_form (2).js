// page back : liste des campagnes (recherche, filtre et modal de suppression)
const recherche = document.getElementById("campagne-search");
const filtreStatut = document.getElementById("campagne-statut-filter");

//les ligne avec campagne
const lignes = document.querySelectorAll("tbody tr[data-statut]");
//pour le modal de supresion
const modalSuppr = document.getElementById("modal-delete-campagne");
const champId = document.getElementById("id-supprimer");
const champTitre = document.getElementById("titre-supprimer");

//filtrage par recherche et filtre
function filtrer() {
    const texte = recherche.value.trim().toLowerCase();
    const statutChoisi = filtreStatut.value;

    lignes.forEach(function (ligne) {
        const okStatut = (statutChoisi === "all" || ligne.dataset.statut === statutChoisi);
        const okTexte = ligne.textContent.toLowerCase().includes(texte);

        //nahiw les tr eli mahomch fel recherche wala filtre
        ligne.classList.toggle("d-none", !(okStatut && okTexte));
    });
}

//nabathou el id a supprimer lel modal
modalSuppr.addEventListener("show.bs.modal", function (event) {
    const bouton = event.relatedTarget;   // btn eli hal le modal
    champId.value = bouton.dataset.id;
    champTitre.textContent = bouton.dataset.titre;
});

recherche.addEventListener("input", filtrer);
filtreStatut.addEventListener("change", filtrer);
filtrer();
