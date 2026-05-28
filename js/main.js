// Wacht tot de DOM volledig geladen is voordat we elementen ophalen
document.addEventListener('DOMContentLoaded', function () {

    // Zoek de zoekbalk en de "geen resultaten"-melding op in de DOM
    const zoekbalk       = document.getElementById('zoekbalk');
    const geenResultaten = document.getElementById('geen-resultaten');

    // Stop hier als er geen zoekbalk op de pagina staat (bijv. op andere pagina's dan producten)
    if (!zoekbalk) return;

    // Luister naar elke toetsaanslag in de zoekbalk
    zoekbalk.addEventListener('input', function () {

        // Zet de zoekterm om naar kleine letters en verwijder spaties aan het begin/einde
        const zoekterm       = this.value.toLowerCase().trim();
        // Selecteer alle productkaarten op de pagina
        const productKaarten = document.querySelectorAll('.product-kaart');
        // Bijhouden hoeveel kaarten zichtbaar zijn na het filteren
        let aantalZichtbaar  = 0;

        // Loop door elke productkaart en vergelijk de naam met de zoekterm
        productKaarten.forEach(function (kaart) {
            // data-naam is ingesteld in de PHP-template; gebruik lege string als fallback
            const naam = (kaart.dataset.naam || '').toLowerCase();

            // Toon de kaart als de naam de zoekterm bevat, verberg hem anders
            if (naam.includes(zoekterm)) {
                kaart.style.display = '';   // standaard display herstellen
                aantalZichtbaar++;
            } else {
                kaart.style.display = 'none';
            }
        });

        // Toon de "geen resultaten"-melding als geen enkele kaart overeenkomt met de zoekterm
        if (geenResultaten) {
            geenResultaten.style.display = aantalZichtbaar === 0 ? 'block' : 'none';
        }
    });
});
