const formulaire = document.getElementById("formu");


//fnc afficher les erreur 
function affe(input, message) {
    const groupe = input.closest(".input-group") || input.closest(".form-check");
    const spanErreur = groupe.previousElementSibling ;
    spanErreur.classList.add("erreur-msg", "text-danger", "small", "mt-1", "mb-2");
    
    if (message) {
        input.classList.add("is-invalid");
        spanErreur.textContent = message;
    } else {
        input.classList.remove("is-invalid");
        spanErreur.textContent = "";
    }
}

//controle saisie
formulaire.addEventListener("submit", function (event) {
    event.preventDefault();
    let frmv = true;
    //les champs 
    const email = document.getElementById("forgotEmail");

    if(email.value.trim() === ""){
        affe(email,"L'email est obligatoire");
        frmv = false;
    }else if (! /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        affe(email, "L'adresse e-mail n'est pas valide.");
        frmv = false;
    } else {
        affe(email, "");
    }

    //nabathou le formulair si tt est corecte
    if (frmv) {
        formulaire.submit();
    }
});