// page front : liste des campagnes (recherche par ville, lieu ou titre)
const recherche = document.getElementById("campagne-search");
const cartes = document.querySelectorAll(".campagne-carte");

function filtrer() {
    const texte = recherche.value.trim().toLowerCase();

    cartes.forEach(function (carte) {
        const ok = carte.textContent.toLowerCase().includes(texte);
        //nahiw les cartes eli mahomch fel recherche
        carte.classList.toggle("d-none", !ok);
    });
}

recherche.addEventListener("input", filtrer);
