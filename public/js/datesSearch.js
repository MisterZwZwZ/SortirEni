let dateMin = document.getElementById("recherche_sorties_dateHeureDebutRecherche");
let dateMax = document.getElementById("recherche_sorties_dateFinRecherche");

dateMin.addEventListener("change", contrainteChoixDateAccueil);
dateMax.addEventListener("change", contrainteChoixDateAccueil);
/**
 * Permet de restreindre les choix disponible dans un champs date en fonction de la valeur selectionnée dans l'autre
 */
function contrainteChoixDateAccueil() {
    dateMax.setAttribute("min", dateMin.value);
    dateMin.setAttribute("max", dateMax.value);
}

