<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
include '../includes/header.php';

// ── Haal filterwaarden op uit GET-parameters ─────────────────────
$filters = [
    'zoekterm' => isset($_GET['zoekterm']) ? trim($_GET['zoekterm']) : '',
    'met_prik' => (isset($_GET['met_prik']) && $_GET['met_prik'] !== '') ? $_GET['met_prik'] : '',
    'alcohol' => (isset($_GET['alcohol']) && $_GET['alcohol'] !== '') ? $_GET['alcohol'] : '',
    'regio' => isset($_GET['regio']) ? trim($_GET['regio']) : '',
];

// ── Haal producten en regiolijst op via prepared statements ──────
$drankLijst = haalAlleDrankenOp($db, $filters);
$regioLijst = haalAlleRegiosOp($db);
?>

<div class="producten-pagina">

    <!-- ── Sidebar: filters ───────────────────────────────────── -->
    <aside class="filter-sidebar">
        <h2 class="filter-sidebar__titel">Filters</h2>

        <form method="GET" action="" class="filter-formulier" id="filter-formulier">

            <!-- Zoekbalk -->
            <div class="filter-groep">
                <label for="zoekbalk" class="filter-label">Zoeken</label>
                <input type="text" id="zoekbalk" name="zoekterm" value="<?= zuiverString($filters['zoekterm']) ?>"
                    placeholder="Zoek een drank..." class="zoek-invoer" autocomplete="off">
            </div>

            <!-- Prik filter -->
            <div class="filter-groep">
                <p class="filter-label">Prik</p>
                <label class="filter-optie">
                    <input type="radio" name="met_prik" value="" <?= $filters['met_prik'] === '' ? 'checked' : '' ?>>
                    Toon alles
                </label>
                <label class="filter-optie">
                    <input type="radio" name="met_prik" value="1" <?= $filters['met_prik'] === '1' ? 'checked' : '' ?>>
                    Met prik
                </label>
                <label class="filter-optie">
                    <input type="radio" name="met_prik" value="0" <?= $filters['met_prik'] === '0' ? 'checked' : '' ?>>
                    Zonder prik
                </label>
            </div>

            <!-- Alcohol filter -->
            <div class="filter-groep">
                <p class="filter-label">Alcohol</p>
                <label class="filter-optie">
                    <input type="radio" name="alcohol" value="" <?= $filters['alcohol'] === '' ? 'checked' : '' ?>>
                    Toon alles
                </label>
                <label class="filter-optie">
                    <input type="radio" name="alcohol" value="1" <?= $filters['alcohol'] === '1' ? 'checked' : '' ?>>
                    Met alcohol
                </label>
                <label class="filter-optie">
                    <input type="radio" name="alcohol" value="0" <?= $filters['alcohol'] === '0' ? 'checked' : '' ?>>
                    Zonder alcohol
                </label>
            </div>

            <!-- Regio filter -->
            <div class="filter-groep">
                <label for="regio" class="filter-label">Regio</label>
                <select name="regio" id="regio" class="filter-select" onchange="this.form.submit()">
                    <option value="">Alle regio's</option>
                    <?php foreach ($regioLijst as $regio): ?>
                        <option value="<?= zuiverString($regio) ?>" <?= $filters['regio'] === $regio ? 'selected' : '' ?>>
                            <?= zuiverString($regio) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="knop knop--filter">Filteren</button>
            <a href="<?= BASE_URL ?>/pages/products.php" class="knop knop--reset">
                Wis alle filters
            </a>
        </form>
    </aside>

    <!-- ── Producten overzicht ────────────────────────────────── -->
    <section class="producten-sectie">
        <div class="producten-header">
            <h1 class="producten-titel">Onze Dranken</h1>
            <span class="producten-aantal">
                <?= count($drankLijst) ?> product<?= count($drankLijst) !== 1 ? 'en' : '' ?> gevonden
            </span>
        </div>

        <!-- "Geen resultaten" – getoond door JS bij live-zoeken -->
        <p id="geen-resultaten" style="display:none;" class="geen-producten">
            Geen dranken gevonden met deze zoekopdracht.
        </p>

        <?php if (empty($drankLijst)): ?>
            <p class="geen-producten">
                Geen dranken gevonden met de huidige filters.
                <a href="<?= BASE_URL ?>/pages/products.php">Wis alle filters</a>
            </p>
        <?php else: ?>
            <div class="producten-grid">
                <?php foreach ($drankLijst as $drank): ?>
                    <article class="product-kaart" data-naam="<?= zuiverString($drank['naam']) ?>">

                        <a href="<?= BASE_URL ?>/pages/detail.php?id=<?= (int) $drank['id'] ?>"
                            class="product-kaart__afbeelding-link">
                            <img src="<?= BASE_URL ?>/assets/images/<?= zuiverString($drank['afbeelding']) ?>"
                                alt="<?= zuiverString($drank['naam']) ?>" class="product-kaart__afbeelding"
                                onerror="this.onerror=null;this.src='<?= BASE_URL ?>/assets/images/placeholder.png'">
                        </a>

                        <div class="product-kaart__info">
                            <h3 class="product-kaart__naam">
                                <a href="<?= BASE_URL ?>/pages/detail.php?id=<?= (int) $drank['id'] ?>">
                                    <?= zuiverString($drank['naam']) ?>
                                </a>
                            </h3>

                            <p class="product-kaart__regio">
                                <?= zuiverString($drank['regio']) ?>
                            </p>

                            <div class="product-kaart__kenmerken">
                                <?php if ($drank['met_prik']): ?>
                                    <span class="kenmerk kenmerk--prik">Prik</span>
                                <?php else: ?>
                                    <span class="kenmerk kenmerk--geen-prik">Geen prik</span>
                                <?php endif; ?>
                                <?php if ($drank['alcohol']): ?>
                                    <span class="kenmerk kenmerk--alcohol">Alcohol</span>
                                <?php endif; ?>
                            </div>

                            <p class="product-kaart__prijs">
                                <?= formateerPrijs((float) $drank['prijs']) ?>
                            </p>

                            <form action="<?= BASE_URL ?>/actions/add.php" method="POST" class="product-kaart__formulier">
                                <input type="hidden" name="drank_id" value="<?= (int) $drank['id'] ?>">
                                <input type="hidden" name="terugkeer_url" value="<?= BASE_URL ?>/pages/products.php">
                                <button type="submit" class="knop knop--toevoegen">
                                    + Toevoegen
                                </button>
                            </form>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

</div>

<?php include '../includes/footer.php'; ?>