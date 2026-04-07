// Wacht tot de pagina volledig geladen is
document.addEventListener('DOMContentLoaded', function () {
    const zoekbalk       = document.getElementById('zoekbalk');
    const geenResultaten = document.getElementById('geen-resultaten');

    // Stop als er geen zoekbalk op de pagina staat
    if (!zoekbalk) return;

    // Voer de filter uit elke keer als de gebruiker typt
    zoekbalk.addEventListener('input', function () {
        const zoekterm       = this.value.toLowerCase().trim();
        const productKaarten = document.querySelectorAll('.product-kaart');
        let aantalZichtbaar  = 0;

        productKaarten.forEach(function (kaart) {
            const naam = (kaart.dataset.naam || '').toLowerCase();

            // Toon de kaart als de naam overeenkomt, anders verberg
            if (naam.includes(zoekterm)) {
                kaart.style.display = '';
                aantalZichtbaar++;
            } else {
                kaart.style.display = 'none';
            }
        });

        // Toon de "geen resultaten" melding als niets overeenkomt
        if (geenResultaten) {
            geenResultaten.style.display = aantalZichtbaar === 0 ? 'block' : 'none';
        }
    });
});