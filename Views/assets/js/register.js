//les champs commun pour tous les utilisateur
const nom = document.getElementById("registerName");
const pren = document.getElementById("registerPreNom");
const email = document.getElementById("registerEmail");
const mdp = document.getElementById("registerPassword");
const tel = document.getElementById("registerTelephone");
const role = document.getElementById("registerRole");
//les champs commun pro
const npro = document.getElementById("registerNumPro");
const adr = document.getElementById("registerAdresse");
const ville = document.getElementById("registerVille");
const hor = document.getElementById("registerHoraires");
const lat = document.getElementById("registerLatitude");
const long = document.getElementById("registerLongitude");
//champ medecin
const spes = document.getElementById("registerSpecialite");
//champ pharmacie
const nomf = document.getElementById("registerNomOfficine");
//les div
const dcomunmf = document.getElementById("champsPro");
const dm = document.getElementById("champsMedecin");
const df = document.getElementById("champsPharmacie");
//les buttons
const cond = document.getElementById("conditions");



//fnc activ desactiv les champs pro
function toggleChampsPro() {
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

role.addEventListener("change", toggleChampsPro);