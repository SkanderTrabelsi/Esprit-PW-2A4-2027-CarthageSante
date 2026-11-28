// page front : mes dons (modal d'annulation)
const modalAnnuler = document.getElementById("modal-annuler-don");
const champId = document.getElementById("id-annuler");
const champTitre = document.getElementById("titre-annuler");

//nabathou el id a annuler lel modal
modalAnnuler.addEventListener("show.bs.modal", function (event) {
    const bouton = event.relatedTarget;   // btn eli hal le modal
    champId.value = bouton.dataset.id;
    champTitre.textContent = bouton.dataset.titre;
});
