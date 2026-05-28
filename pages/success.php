<?php
// Laad de databaseverbinding en hulpfuncties in
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Stuur de gebruiker terug naar de homepagina als er geen succesvolle bestelling is geplaatst
// Dit voorkomt dat iemand de successpagina direct kan openen zonder een bestelling te doen
if (empty($_SESSION['bestellingSucces'])) {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

// Verwijder de succes-vlag uit de sessie zodat de pagina maar één keer te zien is
unset($_SESSION['bestellingSucces']);

// Toon de gedeelde header
include '../includes/header.php';
?>

<!-- Gecentreerde successpagina na een geslaagde bestelling -->
<div class="succes-pagina">
    <div class="succes-kaart">
        <!-- Groen vinkje-icoon (&#10003; is het ✓-teken in HTML) -->
        <div class="succes-icoon">&#10003;</div>
        <h1>Bedankt voor je bestelling!</h1>
        <p>We hebben je bestelling ontvangen en gaan er direct mee aan de slag.</p>
        <!-- Knop om verder te winkelen na de bestelling -->
        <a href="<?= BASE_URL ?>/pages/products.php" class="knop knop--groot">Verder winkelen</a>
    </div>
</div>

<?php
// Sluit de pagina af met de gedeelde footer
include '../includes/footer.php';
?>
