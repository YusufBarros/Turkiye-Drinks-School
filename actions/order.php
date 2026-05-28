<?php
// Laad de databaseverbinding en hulpfuncties in
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Alleen POST-verzoeken verwerken; bij een GET-verzoek stuur terug naar checkout
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/pages/checkout.php');
    exit;
}

// Haal de ingevulde formuliervelden op en verwijder overtollige spaties
$naam  = isset($_POST['naam'])  ? trim($_POST['naam'])  : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$adres = isset($_POST['adres']) ? trim($_POST['adres']) : '';

// Verzamel validatiefouten in een array
$fouten = [];

// Naam moet minimaal 2 tekens bevatten
if (mb_strlen($naam) < 2) {
    $fouten[] = 'Vul een geldige naam in (minimaal 2 tekens).';
}

// Controleer of het e-mailadres een geldig formaat heeft
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $fouten[] = 'Vul een geldig e-mailadres in.';
}

// Adres moet minimaal 5 tekens bevatten
if (mb_strlen($adres) < 5) {
    $fouten[] = 'Vul een geldig bezorgadres in.';
}

// Je kunt niet bestellen als het winkelmandje leeg is
if (empty($_SESSION['winkelmandje'])) {
    $fouten[] = 'Je winkelmandje is leeg.';
}

// Als er validatiefouten zijn, sla ze op in de sessie en stuur terug naar checkout
if (!empty($fouten)) {
    // Voeg alle foutmeldingen samen tot één string
    $_SESSION['bestelling_fout'] = implode(' ', $fouten);
    header('Location: ' . BASE_URL . '/pages/checkout.php');
    exit;
}

// Sla de bestelling op in de database via een transactie
$bestellingSucces = slaBestellingOp($db);

// Stuur naar de successpagina als alles goed ging, anders terug naar checkout met foutmelding
if ($bestellingSucces) {
    header('Location: ' . BASE_URL . '/pages/success.php');
} else {
    $_SESSION['bestelling_fout'] =
        'Er is een fout opgetreden bij het opslaan van je bestelling. Probeer het opnieuw.';
    header('Location: ' . BASE_URL . '/pages/checkout.php');
}
exit;
