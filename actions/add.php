<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$standaardUrl = BASE_URL . '/pages/products.php';

// Alleen POST-verzoeken verwerken
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $standaardUrl);
    exit;
}

$drankId = isset($_POST['drank_id']) ? (int) $_POST['drank_id'] : 0;
$terugkeerUrl = isset($_POST['terugkeer_url']) ? $_POST['terugkeer_url'] : $standaardUrl;

// Valideer dat het drank-ID geldig is en het product bestaat
if ($drankId <= 0 || !haalDrankOpId($db, $drankId)) {
    header('Location: ' . $standaardUrl);
    exit;
}

voegToeAanWinkelmandje($drankId, 1);

header('Location: ' . $terugkeerUrl);
exit;
