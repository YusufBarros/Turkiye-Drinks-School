<?php
// Laad de databaseverbinding en hulpfuncties in
require_once '../includes/db.php';
require_once '../includes/functions.php';
// Toon de gedeelde header
include '../includes/header.php';

// Haal het drank-ID op uit de URL; gebruik 0 als het ontbreekt of geen getal is
$drankId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
// Zoek het product op als het ID groter dan 0 is, anders null
$drank = ($drankId > 0) ? haalDrankOpId($db, $drankId) : null;
?>

<?php if (!$drank): ?>
    <!-- Product bestaat niet of ID ontbreekt in de URL -->
    <div class="fout-container">
        <h2>Product niet gevonden</h2>
        <a href="<?= BASE_URL ?>/pages/products.php" class="knop">← Terug naar producten</a>
    </div>

<?php else: ?>
    <!-- Detailpagina: afbeelding links, info rechts -->
    <div class="detail-pagina">

        <!-- Productafbeelding in een witte kaart; valt terug op placeholder als de afbeelding ontbreekt -->
        <div class="detail-afbeelding-container">
            <img
                src="<?= BASE_URL ?>/assets/images/<?= zuiverString($drank['afbeelding']) ?>"
                alt="<?= zuiverString($drank['naam']) ?>"
                class="detail-afbeelding"
                onerror="this.onerror=null;this.src='<?= BASE_URL ?>/assets/images/placeholder.png'">
        </div>

        <!-- Rechterkolom: naam, regio, kenmerken, beschrijving, prijs en knoppen -->
        <div class="detail-info">

            <!-- Grote productnaam bovenaan -->
            <h1 class="detail-naam"><?= zuiverString($drank['naam']) ?></h1>

            <!-- Regio waar de drank vandaan komt -->
            <p class="detail-regio">Regio: <?= zuiverString($drank['regio']) ?></p>

            <!-- Labels voor prik en alcohol, kleur wordt bepaald door de database-waarden -->
            <div class="detail-kenmerken">
                <!-- Selecteer de juiste CSS-klasse op basis van het prik-veld (1 of 0) -->
                <span class="kenmerk <?= $drank['met_prik'] ? 'kenmerk--prik' : 'kenmerk--geen-prik' ?>">
                    <?= $drank['met_prik'] ? 'Met prik' : 'Zonder prik' ?>
                </span>
                <!-- Selecteer de juiste CSS-klasse op basis van het alcohol-veld (1 of 0) -->
                <span class="kenmerk <?= $drank['alcohol'] ? 'kenmerk--alcohol' : 'kenmerk--geen-alcohol' ?>">
                    <?= $drank['alcohol'] ? 'Met alcohol' : 'Zonder alcohol' ?>
                </span>
            </div>

            <!-- Productbeschrijving uit de database -->
            <p class="detail-beschrijving"><?= zuiverString($drank['beschrijving']) ?></p>

            <!-- Prijs geformatteerd met euroteken -->
            <p class="detail-prijs"><?= formateerPrijs((float) $drank['prijs']) ?></p>

            <!-- Actieknoppen: toevoegen aan mandje of terug naar overzicht -->
            <div class="detail-knoppen">

                <!-- Formulier om dit product toe te voegen aan het winkelmandje -->
                <form action="<?= BASE_URL ?>/actions/add.php" method="POST">
                    <!-- Verborgen veld met het ID van dit product -->
                    <input type="hidden" name="drank_id" value="<?= (int) $drank['id'] ?>">
                    <!-- Na toevoegen keert de gebruiker terug naar deze zelfde detailpagina -->
                    <input type="hidden" name="terugkeer_url"
                           value="<?= BASE_URL ?>/pages/detail.php?id=<?= (int) $drank['id'] ?>">
                    <button type="submit" class="knop knop--groot">+ Voeg toe aan winkelmandje</button>
                </form>

                <!-- Teruglink naar de productenpagina -->
                <a href="<?= BASE_URL ?>/pages/products.php" class="knop knop--secundair">← Terug naar producten</a>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
// Sluit de pagina af met de gedeelde footer
include '../includes/footer.php';
?>
