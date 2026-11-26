const formulaire = document.getElementById("formu");
const icon = document.getElementById("iconm");
const btna = document.getElementById("affmdp");
const cmdp = document.getElementById("loginPassword");

//aff mdps
function affmdp(){
    if(btna.checked){
        icon.classList.add("bi-unlock-fill");
        cmdp.type = 'text';
    }else{
        icon.classList.remove("bi-unlock-fill");
        cmdp.type = 'password';
    }
}

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
    const email = document.getElementById("loginEmail");
    const mdp = document.getElementById("loginPassword");

    if(email.value.trim() === ""){
        affe(email,"L'email est obligatoire");
        frmv = false;
    }else if (! /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        affe(email, "L'adresse e-mail n'est pas valide.");
        frmv = false;
    } else {
        affe(email, "");
    }

    if(mdp.value === ""){
        affe(mdp,"Le mdp est obligatoire");
        frmv = false;
    }else {
        affe(mdp, "");
    }

    //nabathou le formulair si tt est corecte
    if (frmv) {
        formulaire.submit();
    }
});

btna.addEventListener("change", affmdp);