<?php
// Laad de databaseverbinding en hulpfuncties in
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Standaard terugkeerpagina als er geen terugkeer-URL meegegeven is
$standaardUrl = BASE_URL . '/pages/products.php';

// Alleen POST-verzoeken verwerken; GET-verzoeken (bijv. directe URL-bezoeken) worden omgeleid
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $standaardUrl);
    exit;
}

// Haal het drank-ID op uit het formulier en zet het om naar een integer
$drankId = isset($_POST['drank_id']) ? (int) $_POST['drank_id'] : 0;
// Haal de terugkeer-URL op die door het formulier meegegeven is
$terugkeerUrl = isset($_POST['terugkeer_url']) ? $_POST['terugkeer_url'] : $standaardUrl;

// Controleer of het drank-ID geldig is (groter dan 0) en of het product ook echt bestaat
if ($drankId <= 0 || !haalDrankOpId($db, $drankId)) {
    header('Location: ' . $standaardUrl);
    exit;
}

// Voeg het product toe aan het winkelmandje in de sessie (standaard 1 stuks)
voegToeAanWinkelmandje($drankId, 1);

// Stuur de gebruiker terug naar de pagina waar hij vandaan kwam
header('Location: ' . $terugkeerUrl);
exit;
