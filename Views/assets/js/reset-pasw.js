const formulaire = document.getElementById("formu");
const btna = document.getElementById("affmdp");
const icnm = document.getElementById("iconm");
const icnn = document.getElementById("iconn");
//les champs 
const nmdp = document.getElementById("nouvMdp");
const cmdp = document.getElementById("conMdps");

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

    if(nmdp.value.length < 8){
        affe(nmdp,"Le mdp ne contenir au moin 8 caractaires");
        frmv = false;
    }else {
        affe(nmdp, "");
    }
    
    if(cmdp.value != nmdp.value){
        affe(cmdp,"Le mdp est diferent");
        frmv = false;
    }else {
        affe(cmdp, "");
    }

    //nabathou le formulair si tt est corecte
    if (frmv) {
        formulaire.submit();
    }
});

//aff mdps
function affmdp(){
    if(btna.checked){
        icnm.classList.add("bi-unlock-fill");
        icnn.classList.add("bi-unlock-fill");
        nmdp.type = 'text';
        cmdp.type = 'text';
    }else{
        icnm.classList.remove("bi-unlock-fill");
        icnn.classList.remove("bi-unlock-fill");
        nmdp.type = 'password';
        cmdp.type = 'password';
    }
}btna.addEventListener("change", affmdp);