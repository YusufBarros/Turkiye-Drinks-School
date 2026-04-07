<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$winkelmandjeLijst = haalWinkelmandjeItems($db);

// Leeg mandje → stuur terug naar het winkelmandje
if (empty($winkelmandjeLijst)) {
    header('Location: ' . BASE_URL . '/pages/cart.php');
    exit;
}

$totaalprijs = berekenTotaalprijs($db);

// Haal eventuele foutmelding op uit vorige poging
$foutmelding = $_SESSION['bestelling_fout'] ?? '';
unset($_SESSION['bestelling_fout']);

include '../includes/header.php';
?>

<div class="checkout-pagina">
    <h1 class="pagina-titel">Afrekenen</h1>

    <?php if ($foutmelding): ?>
        <p class="foutmelding">
            <?= zuiverString($foutmelding) ?>
        </p>
    <?php endif; ?>

    <div class="checkout-container">

        <!-- Overzicht van de producten in het mandje -->
        <div class="bestelling-overzicht">
            <h2>Jouw bestelling</h2>
            <ul class="overzicht-lijst">
                <?php foreach ($winkelmandjeLijst as $item): ?>
                    <li class="overzicht-item">
                        <span>
                            <?= zuiverString($item['drank']['naam']) ?> &times;
                            <?= $item['aantal'] ?>
                        </span>
                        <span>
                            <?= formateerPrijs($item['subtotaal']) ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="overzicht-totaal">
                <strong>Totaal:</strong>
                <strong>
                    <?= formateerPrijs($totaalprijs) ?>
                </strong>
            </div>
        </div>

        <!-- Formulier voor naam, email en adres -->
        <form action="<?= BASE_URL ?>/actions/order.php" method="POST" class="bestel-formulier">

            <div class="formulier-groep">
                <label for="naam">Naam *</label>
                <input type="text" id="naam" name="naam" required placeholder="Voor- en achternaam" maxlength="100">
            </div>

            <div class="formulier-groep">
                <label for="email">E-mailadres *</label>
                <input type="email" id="email" name="email" required placeholder="jouw@email.nl" maxlength="150">
            </div>

            <div class="formulier-groep">
                <label for="adres">Bezorgadres *</label>
                <textarea id="adres" name="adres" required rows="3" placeholder="Straat + huisnummer, postcode, stad"
                    maxlength="500"></textarea>
            </div>

            <button type="submit" class="knop knop--groot knop--bestellen">Bestelling plaatsen</button>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>