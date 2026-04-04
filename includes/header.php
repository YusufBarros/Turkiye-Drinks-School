<?php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/db.php';
}
if (!function_exists('haalAantalWinkelmandjeArtikelen')) {
    require_once __DIR__ . '/functions.php';
}

$aantalArtikelen = haalAantalWinkelmandjeArtikelen();
$huidigePagina = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turkiye Drinks</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>

<body>
    <header class="header">
        <div class="header__logo">
            <a href="<?= BASE_URL ?>/index.php">Turkiye Drinks</a>
        </div>
        <nav class="header__nav">
            <a href="<?= BASE_URL ?>/index.php" class="<?= $huidigePagina === 'index.php' ? 'actief' : '' ?>">Home</a>
            <a href="<?= BASE_URL ?>/pages/products.php"
                class="<?= $huidigePagina === 'products.php' ? 'actief' : '' ?>">Producten</a>
            <a href="<?= BASE_URL ?>/pages/cart.php"
                class="header__nav--mandje <?= $huidigePagina === 'cart.php' ? 'actief' : '' ?>">
                Winkelmandje
                <?php if ($aantalArtikelen > 0): ?>
                    <span class="header__badge"><?= $aantalArtikelen ?></span>
                <?php endif; ?>
            </a>
        </nav>
    </header>
    <main class="main-content">