<?php
// Laad de databaseverbinding en hulpfuncties in
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Haal alle items op uit het winkelmandje
$winkelmandjeLijst = haalWinkelmandjeItems($db);

// Leeg mandje → stuur de gebruiker terug naar het winkelmandje (checkout heeft geen zin zonder producten)
if (empty($winkelmandjeLijst)) {
    header('Location: ' . BASE_URL . '/pages/cart.php');
    exit;
}

// Bereken de totaalprijs van alle items
$totaalprijs = berekenTotaalprijs($db);

// Haal een eventuele foutmelding op die bij een mislukte bestelpoging is opgeslagen in de sessie
$foutmelding = $_SESSION['bestelling_fout'] ?? '';
// Verwijder de foutmelding uit de sessie zodat hij niet bij de volgende paginalading opnieuw verschijnt
unset($_SESSION['bestelling_fout']);

// Toon de gedeelde header (na de redirectcontrole, zodat we geen output sturen voor de header())
include '../includes/header.php';
?>

<div class="checkout-pagina">
    <h1 class="pagina-titel">Afrekenen</h1>

    <!-- Toon de foutmelding als de vorige bestelpoging mislukt is -->
    <?php if ($foutmelding): ?>
        <p class="foutmelding">
            <?= zuiverString($foutmelding) ?>
        </p>
    <?php endif; ?>

    <!-- Twee kolommen: besteloverzicht links, bestelformulier rechts -->
    <div class="checkout-container">

        <!-- Overzicht van alle producten in het mandje -->
        <div class="bestelling-overzicht">
            <h2>Jouw bestelling</h2>
            <ul class="overzicht-lijst">
                <?php foreach ($winkelmandjeLijst as $item): ?>
                    <!-- Elke rij toont de naam × aantal en het subtotaal -->
                    <li class="overzicht-item">
                        <span>
                            <?= zuiverString($item['drank']['naam']) ?> &times; <?= $item['aantal'] ?>
                        </span>
                        <span>
                            <?= formateerPrijs($item['subtotaal']) ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Totaalprijs vetgedrukt onderaan het overzicht -->
            <div class="overzicht-totaal">
                <strong>Totaal:</strong>
                <strong><?= formateerPrijs($totaalprijs) ?></strong>
            </div>
        </div>

        <!-- Bestelformulier: naam, e-mail en bezorgadres zijn verplicht -->
        <form action="<?= BASE_URL ?>/actions/order.php" method="POST" class="bestel-formulier">

            <!-- Naam-veld: minimaal 2 tekens, maximaal 100 tekens -->
            <div class="formulier-groep">
                <label for="naam">Naam *</label>
                <input type="text" id="naam" name="naam" required placeholder="Voor- en achternaam" maxlength="100">
            </div>

            <!-- E-mailveld: browser valideert het formaat via type="email" -->
            <div class="formulier-groep">
                <label for="email">E-mailadres *</label>
                <input type="email" id="email" name="email" required placeholder="jouw@email.nl" maxlength="150">
            </div>

            <!-- Adresveld: textarea voor meerdere regels, minimaal 5 tekens -->
            <div class="formulier-groep">
                <label for="adres">Bezorgadres *</label>
                <textarea id="adres" name="adres" required rows="3"
                          placeholder="Straat + huisnummer, postcode, stad" maxlength="500"></textarea>
            </div>

            <!-- Verstuur de bestelling naar order.php voor validatie en opslag -->
            <button type="submit" class="knop knop--groot knop--bestellen">Bestelling plaatsen</button>
        </form>
    </div>
</div>

<?php
// Sluit de pagina af met de gedeelde footer
include '../includes/footer.php';
?>
