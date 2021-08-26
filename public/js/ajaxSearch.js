window.onload = () => {
    const FiltersForm = document.querySelector("#searchForm");

    // On boucle sur les input
    document.querySelectorAll("#searchForm input, #searchForm select").forEach(input => {
        input.addEventListener("change", () => {

            contrainteChoixDate()
            // On récupère les données du formulaire
            const Form = new FormData(FiltersForm);

            // On fabrique la queryString
            const Params = new URLSearchParams();

            Form.forEach((value, key) => {
                Params.append(key, value);
            });

            // On récupère l'url active
            const Url = new URL(window.location.href);

            // On lance la requête ajax
            fetch(Url.pathname + "?" + Params.toString() + "&ajax=1", {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            }).then(response =>
                response.json()
            ).then(data => {
                // On va chercher la zone de contenu
                const content = document.querySelector("#content");

                // On remplace le contenu
                content.innerHTML = data.content;

                // On met à jour l'url
                history.replaceState({}, null, Url.pathname + "?" + Params.toString());
            }).catch(e => alert(e));

        });
    });
}

/**
 * Permet de restreindre les choix disponible dans un champs date en fonction de la valeur selectionnée dans l'autre
 */
function contrainteChoixDate() {
    let dateMin = document.getElementById("recherche_sorties_dateHeureDebutRecherche");
    let dateMax = document.getElementById("recherche_sorties_dateFinRecherche");
    dateMax.setAttribute("min", dateMin.value);
    dateMin.setAttribute("max", dateMax.value);
}