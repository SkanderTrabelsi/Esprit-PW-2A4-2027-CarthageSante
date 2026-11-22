//champs recherche et button
const recherche = document.getElementById("user-search");
const filtreRole = document.getElementById("user-role-filter"); 

//les ligne avec utilisateur
const lignes = document.querySelectorAll("tbody tr[data-role]");
//pour le modal de supresion
const modalSuppr = document.getElementById("modal-delete-user");
const champId = document.getElementById("id-supprimer");
const champNom = document.getElementById("nom-supprimer");

//filtrage par recherche et filtre
function filtrer() {
    const texte = recherche.value.trim().toLowerCase();
    const roleChoisi = filtreRole.value;


    lignes.forEach(function (ligne) {
        const okRole = (roleChoisi === "all" || ligne.dataset.role === roleChoisi);
        const okTexte = ligne.textContent.toLowerCase().includes(texte);

        //nahiw les tr eli mahomch fel recherche wala filtre
        ligne.classList.toggle("d-none", !(okRole && okTexte ));
    });
}

//nabathou el id a supprimer lel modal
modalSuppr.addEventListener("show.bs.modal", function (event) {
    const bouton = event.relatedTarget;   // btn eli hal le modal 
    champId.value = bouton.dataset.id;    // nabathou el id mta user lel model
    champNom.textContent = bouton.dataset.pren +' '+ bouton.dataset.nom;
});

recherche.addEventListener("input", filtrer);
filtreRole.addEventListener("change", filtrer);
filtrer();