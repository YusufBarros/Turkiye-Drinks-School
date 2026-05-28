<?php
// Laad db.php in als BASE_URL nog niet gedefinieerd is (voorkomt dubbele include)
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/db.php';
}
// Laad functions.php in als de hulpfuncties nog niet beschikbaar zijn
if (!function_exists('haalAantalWinkelmandjeArtikelen')) {
    require_once __DIR__ . '/functions.php';
}

// Haal het totale aantal artikelen op voor de badge in de navigatie
$aantalArtikelen = haalAantalWinkelmandjeArtikelen();
// Sla de bestandsnaam op van de huidige pagina voor actieve link-markering
$huidigePagina = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <!-- Zorgt dat speciale tekens correct weergegeven worden -->
    <meta charset="UTF-8">
    <!-- Maakt de pagina schaalbaar op mobiele apparaten -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turkiye Drinks</title>
    <!-- Laad het hoofd-stylesheet in -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>

<body>
    <!-- Rode navigatiebalk bovenaan de pagina -->
    <header class="header">
        <!-- Logo/merknaam links in de header, klikbaar naar de homepagina -->
        <div class="header__logo">
            <a href="<?= BASE_URL ?>/index.php">Turkiye Drinks</a>
        </div>

        <!-- Navigatielinks rechts in de header -->
        <nav class="header__nav">
            <!-- Actieve pagina krijgt de class 'actief' voor een gouden streep eronder -->
            <a href="<?= BASE_URL ?>/index.php"
               class="<?= $huidigePagina === 'index.php' ? 'actief' : '' ?>">
                Home
            </a>

            <a href="<?= BASE_URL ?>/pages/products.php"
               class="<?= $huidigePagina === 'products.php' ? 'actief' : '' ?>">
                Producten
            </a>

            <!-- Winkelmandje-link met optionele badge voor het aantal artikelen -->
            <a href="<?= BASE_URL ?>/pages/cart.php"
               class="header__nav--mandje <?= $huidigePagina === 'cart.php' ? 'actief' : '' ?>">
                Winkelmandje
                <?php if ($aantalArtikelen > 0): ?>
                    <!-- Gouden bolletje toont het aantal artikelen in het mandje -->
                    <span class="header__badge"><?= $aantalArtikelen ?></span>
                <?php endif; ?>
            </a>
        </nav>
    </header>

    <!-- Hoofdinhoud van de pagina begint hier, wordt gevuld door de ingeladen pagina -->
    <main class="main-content">
