window.onload = () => {
    //On récupère notre formulaire
    const FiltersForm = document.querySelector("#searchForm");

    // On boucle sur les input
    document.querySelectorAll("#searchForm input, #searchForm select").forEach(input => {
        input.addEventListener("change", () => {

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
                // On va chercher la div contenant les résultats
                const content = document.querySelector("#content");

                // On remplace le contenu de la div par les résultats de la requête Ajax
                content.innerHTML = data.content;

                // On met à jour l'url avec les paramètres de recherche
                history.replaceState({}, null, Url.pathname + "?" + Params.toString());
            }).catch(e => alert("erreur lors de la récupération des résultats"));
        });
    });
}

