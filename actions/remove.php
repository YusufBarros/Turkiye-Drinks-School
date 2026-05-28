<?php
// Laad de databaseverbinding en hulpfuncties in
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Na het verwijderen keert de gebruiker terug naar het winkelmandje
$terugkeerUrl = BASE_URL . '/pages/cart.php';

// Alleen POST-verzoeken verwerken; blokkeer directe GET-verzoeken
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $terugkeerUrl);
    exit;
}

// Haal het drank-ID op uit het formulier en zet het om naar een integer
$drankId = isset($_POST['drank_id']) ? (int) $_POST['drank_id'] : 0;

// Verwijder het product als het ID geldig is (groter dan 0)
if ($drankId > 0) {
    verwijderUitWinkelmandje($drankId);
}

// Stuur de gebruiker terug naar het winkelmandje
header('Location: ' . $terugkeerUrl);
exit;
