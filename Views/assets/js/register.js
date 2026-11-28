const formulaire = document.getElementById("formu");
//select mta3 role et aff mdps
const role = document.getElementById("registerRole");
const icnm = document.getElementById("iconm");
const icnn = document.getElementById("iconn");
const btna = document.getElementById("affmdp");
const btnGen = document.getElementById('btnGenMdp');
const nmdp = document.getElementById("registerPassword");
const cmdp = document.getElementById("registerCPassword");
//les div 
const dcomunmf = document.getElementById("champsPro");
const dm = document.getElementById("champsMedecin");
const df = document.getElementById("champsPharmacie");
const da = document.getElementById("champsAdmin") ?? "";
//localis auto
const btnLocaliser = document.getElementById("btnLocaliser");
const statusLocalisation = document.getElementById("statusLocalisation");
 
//fnc activ desactiv les champs pro
function toggleChampsPro() {
    if (role.value === "medecin" || role.value === "pharmacie") {
        dcomunmf.classList.remove("d-none");
        if(role.value === "medecin"){
            dm.classList.remove("d-none");
            df.classList.add("d-none");
            if (da !== ""){
                da.classList.add("d-none");
            }
        }else{
            df.classList.remove("d-none");
            dm.classList.add("d-none");
            if (da !== ""){
                da.classList.add("d-none");
            }
        }
    }else if(role.value === 'admin'){
        da.classList.remove("d-none");
        df.classList.add("d-none");
        dm.classList.add("d-none");
        dcomunmf.classList.add("d-none");
    }else {
        dcomunmf.classList.add("d-none");
        dm.classList.add("d-none");
        df.classList.add("d-none");
        if (da !== ""){
            da.classList.add("d-none");
        }
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
    }else if(mdp.value.includes(nom.value)){
        affe(mdp,"Le mdp doit pas contenir votre nom");
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

        //formatage et verif haoraire
        //controle saisie jours et heures pro
        const joursCoches = formulaire.querySelectorAll('input[name="jours[]"]:checked');
        const hDeb = document.getElementById("heure_debut");
        const hFin = document.getElementById("heure_fin");
        const blocHor = document.getElementById("j_Lun").closest(".input-group");

        if (joursCoches.length === 0 || hDeb.value === "" || hFin.value === "") {
            affe(blocHor, "Choisissez au moins un jour et les horaires.");
            frmv = false;
        } else {
            affe(blocHor, "");
            const premierJour = joursCoches[0].value;
            const dernierJour = joursCoches[joursCoches.length - 1].value;
            hor.value = `(${premierJour} / ${dernierJour} , ${hDeb.value} / ${hFin.value})`;
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
}

function genererMotDePasse(longeur) {
    const maj = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    const min = "abcdefghijklmnopqrstuvwxyz";
    const chiffres = "0123456789";
    const speciaux = "!@#$%&*?_-";
    const tous = maj + min + chiffres + speciaux;

    const aleatoire = (str) => {
        const array = new Uint32Array(1);
        window.crypto.getRandomValues(array);
        return str[array[0] % str.length];
    };

    const mdp = [
        aleatoire(maj),
        aleatoire(min),
        aleatoire(chiffres),
        aleatoire(speciaux),
    ];

    for (let i = mdp.length; i < longeur; i++) {
        mdp.push(aleatoire(tous));
    }

    for (let i = mdp.length - 1; i > 0; i--) {
        const array = new Uint32Array(1);
        window.crypto.getRandomValues(array);
        const j = array[0] % (i + 1);
        [mdp[i], mdp[j]] = [mdp[j], mdp[i]];
    }

    return mdp.join("");
}

function localiserClient() {
    const lat = document.getElementById("registerLatitude");
    const long = document.getElementById("registerLongitude");
    const ville = document.getElementById("registerVille");
    const adr = document.getElementById("registerAdresse");

    if (!navigator.geolocation) {
        statusLocalisation.textContent = "Géolocalisation non supportée.";
        return;
    }

    statusLocalisation.textContent = "Localisation en cours...";

    navigator.geolocation.getCurrentPosition(function (pos) {
        const latitude = pos.coords.latitude.toFixed(6);
        const longitude = pos.coords.longitude.toFixed(6);

        lat.value = latitude;
        affe(lat, "");
        long.value = longitude;
        affe(long, "");

        fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latitude}&lon=${longitude}`, {
            headers: { "Accept-Language": "fr" }
        })
        .then(function (res) {
            return res.json();
        })
        .then(function (data) {
            const a = data.address || {};
            const villeTrouvee = a.city || a.town || a.village || a.state || "";
            const adrTrouvee = [a.house_number, a.road, a.suburb].filter(Boolean).join(", ");

            if (villeTrouvee) {
                ville.value = villeTrouvee;
                affe(ville, "");
            }
            if (adrTrouvee) {
                adr.value = adrTrouvee;
                affe(adr, "");
            }
            statusLocalisation.textContent = "Position récupérée.";
        })
        .catch(function () {
            statusLocalisation.textContent = "Coordonnées récupérées (adresse non trouvée).";
        });
    }, function () {
        statusLocalisation.textContent = "Impossible de récupérer la position.";
    });
}

btna.addEventListener("change", affmdp);
role.addEventListener("change", toggleChampsPro);
toggleChampsPro();
btnGen.addEventListener("click", function () {
    const nouveauMdp = genererMotDePasse(12);
    nmdp.value = nouveauMdp;
    cmdp.value = nouveauMdp;
    btna.checked = true;
    affmdp();
});
btnLocaliser.addEventListener("click", localiserClient);
