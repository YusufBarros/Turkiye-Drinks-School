<?php
// Laad de databaseverbinding en hulpfuncties in
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Na het aanpassen keert de gebruiker terug naar het winkelmandje
$terugkeerUrl = BASE_URL . '/pages/cart.php';

// Alleen POST-verzoeken verwerken; blokkeer directe GET-verzoeken
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $terugkeerUrl);
    exit;
}

// Haal het drank-ID op uit het formulier en zet het om naar een integer
$drankId = isset($_POST['drank_id']) ? (int) $_POST['drank_id'] : 0;
// Haal het nieuwe gewenste aantal op; bij 0 of minder verwijdert wijzigAantalInWinkelmandje het product
$nieuwAantal = isset($_POST['nieuw_aantal']) ? (int) $_POST['nieuw_aantal'] : 0;

// Pas het aantal aan als het ID geldig is
if ($drankId > 0) {
    wijzigAantalInWinkelmandje($drankId, $nieuwAantal);
}

// Stuur de gebruiker terug naar het winkelmandje
header('Location: ' . $terugkeerUrl);
exit;
