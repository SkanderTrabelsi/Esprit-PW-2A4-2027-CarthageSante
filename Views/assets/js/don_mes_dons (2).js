// page front : formulaire d'inscription au don (controle de saisie)
const formulaire = document.getElementById("formu");
const radiosDeja = document.querySelectorAll("input[name='deja_donne']");
const blocDernier = document.getElementById("champsDernierDon");

//afficher ou cacher les champs du dernier don
function toggleDernierDon() {
    const oui = document.getElementById("dejaOui").checked;
    blocDernier.classList.toggle("d-none", !oui);
}

//fnc afficher les erreur
function affe(input, message) {
    const champ = input.closest(".champ");
    const spanErreur = champ.querySelector(".err");
    spanErreur.textContent = message;
    if (input.type !== "radio") {
        input.classList.toggle("is-invalid", message !== "");
    }
}

radiosDeja.forEach(function (r) {
    r.addEventListener("change", toggleDernierDon);
});
toggleDernierDon();

//controle saisie
formulaire.addEventListener("submit", function (event) {
    let frmv = true;

    const type = document.getElementById("typeDon");
    const nature = document.getElementById("natureDon");
    const heure = document.getElementById("heureRdv");
    const oui = document.getElementById("dejaOui");
    const non = document.getElementById("dejaNon");
    const dateDernier = document.getElementById("dateDernierDon");
    const lieuDernier = document.getElementById("lieuDernierDon");
    const debut = formulaire.dataset.debut;
    const fin = formulaire.dataset.fin;

    if (type.value === "") {
        affe(type, "Veuillez choisir un type de don");
        frmv = false;
    } else {
        affe(type, "");
    }

    if (nature.value === "") {
        affe(nature, "Veuillez choisir la nature du don");
        frmv = false;
    } else {
        affe(nature, "");
    }

    if (heure.value === "") {
        affe(heure, "L'heure du rendez-vous est obligatoire");
        frmv = false;
    } else if (heure.value < debut || heure.value >= fin) {
        affe(heure, "L'heure doit etre comprise entre " + debut + " et " + fin);
        frmv = false;
    } else {
        affe(heure, "");
    }

    if (!oui.checked && !non.checked) {
        affe(oui, "Veuillez repondre par oui ou non");
        frmv = false;
    } else {
        affe(oui, "");
    }

    //champs du dernier don : seulement si deja donne
    if (oui.checked) {
        const aujourdhui = new Date().toISOString().slice(0, 10);
        if (dateDernier.value === "") {
            affe(dateDernier, "La date du dernier don est obligatoire");
            frmv = false;
        } else if (dateDernier.value > aujourdhui) {
            affe(dateDernier, "La date du dernier don ne peut pas etre dans le futur");
            frmv = false;
        } else {
            affe(dateDernier, "");
        }

        if (lieuDernier.value.trim() === "") {
            affe(lieuDernier, "Le lieu du dernier don est obligatoire");
            frmv = false;
        } else {
            affe(lieuDernier, "");
        }
    }

    //si une erreur on bloque l'envoi
    if (!frmv) {
        event.preventDefault();
    }
});
