<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$terugkeerUrl = BASE_URL . '/pages/cart.php';

// Alleen POST-verzoeken verwerken
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $terugkeerUrl);
    exit;
}

$drankId = isset($_POST['drank_id']) ? (int) $_POST['drank_id'] : 0;
$nieuwAantal = isset($_POST['nieuw_aantal']) ? (int) $_POST['nieuw_aantal'] : 0;

if ($drankId > 0) {
    wijzigAantalInWinkelmandje($drankId, $nieuwAantal);
}

header('Location: ' . $terugkeerUrl);
exit;
