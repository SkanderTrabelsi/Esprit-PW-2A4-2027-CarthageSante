const formulaire = document.getElementById("formu");
//select mta3 role
const role = document.getElementById("registerRole");
//les div
const dcomunmf = document.getElementById("champsPro");
const dm = document.getElementById("champsMedecin");
const df = document.getElementById("champsPharmacie");


//fnc activ desactiv les champs pro
function toggleChampsPro() {
    console.log("role.value au chargement :", JSON.stringify(role.value));
    if (role.value === "medecin" || role.value === "pharmacie") {
        dcomunmf.classList.remove("d-none");
        if(role.value === "medecin"){
            dm.classList.remove("d-none");
            df.classList.add("d-none");
        }else{
            df.classList.remove("d-none");
            dm.classList.add("d-none");
        }
    } else {
        dcomunmf.classList.add("d-none");
        dm.classList.add("d-none");
        df.classList.add("d-none");
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

    //les champs commun pour tous les utilisateur
    const nom = document.getElementById("registerName");
    const pren = document.getElementById("registerPreNom");
    const email = document.getElementById("registerEmail");
    const mdp = document.getElementById("registerPassword");
    const cmdp = document.getElementById("registerCPassword");
    const tel = document.getElementById("registerTelephone");
    //les buttons
    const cond = document.getElementById("conditions");

    if(nom.value.trim() === ""){
        affe(nom,"Le nom est obligatoire");
        frmv = false;
    }else {
        affe(nom, "");
    }

    if(pren.value.trim() === ""){
        affe(pren,"Le prenom est obligatoire");
        frmv = false;
    }else {
        affe(pren, "");
    }

    if(email.value.trim() === ""){
        affe(email,"L'email est obligatoire");
        frmv = false;
    }else if (! /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        affe(email, "L'adresse e-mail n'est pas valide.");
        frmv = false;
    } else {
        affe(email, "");
    }

    if(mdp.value.length < 8){
        affe(mdp,"Le mdp ne contenir au moin 8 caractaires");
        frmv = false;
    }else if(mdp.value === nom.value){
        affe(mdp,"Le mdp doit etre diferent que le nom");
        frmv = false;
    }else {
        affe(mdp, "");
    }
    
    if(cmdp.value != mdp.value){
        affe(cmdp,"Le mdp est diferent");
        frmv = false;
    }else {
        affe(cmdp, "");
    }

    if(tel.value === ""){
        affe(tel,"Le numero est obligatoire");
        frmv = false;
    }else if( !( /^\d+$/.test(tel.value) ) ||  ! (tel.value.startsWith("2") || tel.value.startsWith("4") || tel.value.startsWith("5") || tel.value.startsWith("9") )  ){
        affe(tel,"Le numero n'est pas valide");
        frmv = false;
    }else if(tel.value.length != 8){
        affe(tel,"Le numero est composer de 8 chiffres");
        frmv = false;
    }else{
        affe(tel, "");
    }

    if(role.value === ""){
        affe(role,"Choisir un type de compte");
        frmv = false;
    }else{
        affe(role, "");
    }

    if(!cond.checked){
        affe(cond,"Vous devez accepter les conditions.");
        frmv = false;
    }else{
        affe(cond, "");
    }
    //controle saisie champs medecin et pharmacie
    if(role.value === "medecin" || role.value === "pharmacie"){
        //les champs commun pro
        const npro = document.getElementById("registerNumPro");
        const adr = document.getElementById("registerAdresse");
        const ville = document.getElementById("registerVille");
        const hor = document.getElementById("registerHoraires");
        const lat = document.getElementById("registerLatitude");
        const long = document.getElementById("registerLongitude");

        if(npro.value.trim() === ""){
            affe(npro,"Le numero pro est obligatoire");
            frmv = false;
        }else {
            affe(npro, "");
        }

        if(adr.value.trim() === ""){
            affe(adr,"L'adresse est obligatoire");
            frmv = false;
        }else {
            affe(adr, "");
        }        

        if(ville.value.trim() === ""){
            affe(ville,"La ville est obligatoire");
            frmv = false;
        }else {
            affe(ville, "");
        }

        if(hor.value.trim() === ""){
            affe(hor,"Les horaires sont obligatoires");
            frmv = false;
        }else {
            affe(hor, "");
        }

        if(lat.value.trim() === ""){
            affe(lat,"Latitude est obligatoire");
            frmv = false;
        }else {
            affe(lat, "");
        }

        if(long.value.trim() === ""){
            affe(long,"La longitude est obligatoire");
            frmv = false;
        }else {
            affe(long, "");
        }

        //controle saisie medecin
        if(role.value === "medecin"){
            //champ medecin
            const spes = document.getElementById("registerSpecialite");
            
            if(spes.value.trim() === ""){
                affe(spes,"La specialite est obligatoire");
                frmv = false;
            }else {
                affe(spes, "");
            }
        }//controle saisie pharmacie
        else{
            //champ pharmacie
            const nomf = document.getElementById("registerNomOfficine");

            if(nomf.value.trim() === ""){
                affe(nomf,"Le nom de la pharmacie est obligatoire");
                frmv = false;
            }else {
                affe(nomf, "");
            }
        }
    }

    //nabathou le formulair si tt est corecte
    if (frmv) {
        formulaire.submit();
    }
});


role.addEventListener("change", toggleChampsPro);
toggleChampsPro();