<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Alleen POST-verzoeken verwerken
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/pages/checkout.php');
    exit;
}

// ── Haal invoervelden op en trim spaties ──────────────────────────
$naam = isset($_POST['naam']) ? trim($_POST['naam']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$adres = isset($_POST['adres']) ? trim($_POST['adres']) : '';

// ── Valideer invoer ───────────────────────────────────────────────
$fouten = [];

if (mb_strlen($naam) < 2) {
    $fouten[] = 'Vul een geldige naam in (minimaal 2 tekens).';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $fouten[] = 'Vul een geldig e-mailadres in.';
}

if (mb_strlen($adres) < 5) {
    $fouten[] = 'Vul een geldig bezorgadres in.';
}

if (empty($_SESSION['winkelmandje'])) {
    $fouten[] = 'Je winkelmandje is leeg.';
}

if (!empty($fouten)) {
    $_SESSION['bestelling_fout'] = implode(' ', $fouten);
    header('Location: ' . BASE_URL . '/pages/checkout.php');
    exit;
}

// ── Sla bestelling op via transactie ─────────────────────────────
$bestellingSucces = slaBestellingOp($db);

if ($bestellingSucces) {
    header('Location: ' . BASE_URL . '/pages/success.php');
} else {
    $_SESSION['bestelling_fout'] =
        'Er is een fout opgetreden bij het opslaan van je bestelling. Probeer het opnieuw.';
    header('Location: ' . BASE_URL . '/pages/checkout.php');
}
exit;
