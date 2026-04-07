<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$terugkeerUrl = BASE_URL . '/pages/cart.php';

// Alleen POST-verzoeken verwerken
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $terugkeerUrl);
    exit;
}

// Haal het drank-ID op uit het formulier
$drankId = isset($_POST['drank_id']) ? (int) $_POST['drank_id'] : 0;

// Verwijder het product als het ID geldig is
if ($drankId > 0) {
    verwijderUitWinkelmandje($drankId);
}

header('Location: ' . $terugkeerUrl);
exit;