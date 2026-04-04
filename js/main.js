/**
 * Turkiye Drinks – JavaScript
 * Live zoekopdracht: filtert productkaarten terwijl de gebruiker typt.
 */
document.addEventListener('DOMContentLoaded', function () {
    const zoekbalk       = document.getElementById('zoekbalk');
    const geenResultaten = document.getElementById('geen-resultaten');

    if (!zoekbalk) return;

    zoekbalk.addEventListener('input', function () {
        const zoekterm        = this.value.toLowerCase().trim();
        const productKaarten  = document.querySelectorAll('.product-kaart');
        let aantalZichtbaar   = 0;

        productKaarten.forEach(function (kaart) {
            const naam = (kaart.dataset.naam || '').toLowerCase();

            if (naam.includes(zoekterm)) {
                kaart.style.display = '';
                aantalZichtbaar++;
            } else {
                kaart.style.display = 'none';
            }
        });

        // Toon "geen resultaten" melding indien nodig
        if (geenResultaten) {
            geenResultaten.style.display = aantalZichtbaar === 0 ? 'block' : 'none';
        }
    });
});
