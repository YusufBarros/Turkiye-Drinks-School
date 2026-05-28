<?php
// Startpagina van Turkiye Drinks
// Laadt de databaseverbinding en hulpfuncties in
require_once 'includes/db.php';
require_once 'includes/functions.php';
// Toont de gedeelde header (HTML-head, navigatie)
include 'includes/header.php';
?>

<!-- Hero sectie: grote welkomstbanner met achtergrondafbeelding -->
<section class="hero">
    <!-- Overlay-blok met tekst en knop bovenop de afbeelding -->
    <div class="hero__overlay">
        <h2 class="hero__titel">Welkom bij Turkiye Drinks</h2>
        <p class="hero__ondertitel">
            De lekkerste authentieke Turkse dranken, thuisbezorgd in Nederland.
        </p>
        <!-- Knop verwijst naar de productenpagina -->
        <a href="<?= BASE_URL ?>/pages/products.php" class="knop knop--groot">Bekijk ons assortiment</a>
    </div>
</section>

<?php
// Sluit de pagina af met de gedeelde footer (copyright, JS-bestand)
include 'includes/footer.php';
?>
