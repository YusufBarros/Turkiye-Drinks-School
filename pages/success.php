<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Voorkom directe toegang zonder een bestelling te hebben geplaatst
if (empty($_SESSION['bestellingSucces'])) {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}
unset($_SESSION['bestellingSucces']);

include '../includes/header.php';
?>

<div class="succes-pagina">
    <div class="succes-kaart">
        <div class="succes-icoon">&#10003;</div>
        <h1>Bedankt voor je bestelling!</h1>
        <p>We hebben je bestelling ontvangen en gaan er direct mee aan de slag.</p>
        <a href="<?= BASE_URL ?>/pages/products.php" class="knop knop--groot">
            Verder winkelen
        </a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>