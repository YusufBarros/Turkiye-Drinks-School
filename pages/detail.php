<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
include '../includes/header.php';

// Haal het drank-ID op uit de URL en zoek het product op
$drankId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$drank = ($drankId > 0) ? haalDrankOpId($db, $drankId) : null;
?>

<?php if (!$drank): ?>
    <!-- Product niet gevonden -->
    <div class="fout-container">
        <h2>Product niet gevonden</h2>
        <a href="<?= BASE_URL ?>/pages/products.php" class="knop">← Terug naar producten</a>
    </div>

<?php else: ?>
    <div class="detail-pagina">

        <!-- Afbeelding -->
        <div class="detail-afbeelding-container">
            <img src="<?= BASE_URL ?>/assets/images/<?= zuiverString($drank['afbeelding']) ?>"
                alt="<?= zuiverString($drank['naam']) ?>" class="detail-afbeelding"
                onerror="this.onerror=null;this.src='<?= BASE_URL ?>/assets/images/placeholder.png'">
        </div>

        <!-- Productinfo -->
        <div class="detail-info">
            <h1 class="detail-naam">
                <?= zuiverString($drank['naam']) ?>
            </h1>
            <p class="detail-regio">Regio:
                <?= zuiverString($drank['regio']) ?>
            </p>

            <!-- Kenmerken zoals prik en alcohol -->
            <div class="detail-kenmerken">
                <span class="kenmerk <?= $drank['met_prik'] ? 'kenmerk--prik' : 'kenmerk--geen-prik' ?>">
                    <?= $drank['met_prik'] ? 'Met prik' : 'Zonder prik' ?>
                </span>
                <span class="kenmerk <?= $drank['alcohol'] ? 'kenmerk--alcohol' : 'kenmerk--geen-alcohol' ?>">
                    <?= $drank['alcohol'] ? 'Met alcohol' : 'Zonder alcohol' ?>
                </span>
            </div>

            <p class="detail-beschrijving">
                <?= zuiverString($drank['beschrijving']) ?>
            </p>
            <p class="detail-prijs">
                <?= formateerPrijs((float) $drank['prijs']) ?>
            </p>

            <div class="detail-knoppen">
                <!-- Toevoegen aan winkelmandje -->
                <form action="<?= BASE_URL ?>/actions/add.php" method="POST">
                    <input type="hidden" name="drank_id" value="<?= (int) $drank['id'] ?>">
                    <input type="hidden" name="terugkeer_url"
                        value="<?= BASE_URL ?>/pages/detail.php?id=<?= (int) $drank['id'] ?>">
                    <button type="submit" class="knop knop--groot">+ Voeg toe aan winkelmandje</button>
                </form>

                <a href="<?= BASE_URL ?>/pages/products.php" class="knop knop--secundair">← Terug naar producten</a>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>