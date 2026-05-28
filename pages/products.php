<?php
// Laad de databaseverbinding en hulpfuncties in
require_once '../includes/db.php';
require_once '../includes/functions.php';
// Toon de gedeelde header (navigatie, HTML-head)
include '../includes/header.php';

// Haal filterwaarden op uit de URL ($_GET); lege string als de waarde ontbreekt
$filters = [
    'zoekterm' => isset($_GET['zoekterm']) ? trim($_GET['zoekterm']) : '',
    'met_prik' => (isset($_GET['met_prik']) && $_GET['met_prik'] !== '') ? $_GET['met_prik'] : '',
    'alcohol'  => (isset($_GET['alcohol'])  && $_GET['alcohol']  !== '') ? $_GET['alcohol']  : '',
    'regio'    => isset($_GET['regio'])    ? trim($_GET['regio'])    : '',
];

// Haal de gefilterde lijst dranken op uit de database
$drankLijst = haalAlleDrankenOp($db, $filters);
// Haal alle beschikbare regio's op voor de dropdown in de sidebar
$regioLijst = haalAlleRegiosOp($db);
?>

<!-- Wrapper met sidebar en productenraster naast elkaar -->
<div class="producten-pagina">

    <!-- Linker sidebar met filteropties -->
    <aside class="filter-sidebar">
        <h2 class="filter-sidebar__titel">Filters</h2>

        <!-- Alle filters worden via GET verstuurd zodat de URL deelbaar is -->
        <form method="GET" action="" id="filter-formulier">

            <!-- Zoekbalk: live filtering via JS terwijl je typt, server-side via GET bij submit -->
            <div class="filter-groep">
                <label for="zoekbalk" class="filter-label">Zoeken</label>
                <input
                    type="text"
                    id="zoekbalk"
                    name="zoekterm"
                    value="<?= zuiverString($filters['zoekterm']) ?>"
                    placeholder="Zoek een drank..."
                    class="zoek-invoer"
                    autocomplete="off">
            </div>

            <!-- Radio-knoppen voor het prik-filter (alles / met prik / zonder prik) -->
            <div class="filter-groep">
                <p class="filter-label">Prik</p>
                <label class="filter-optie">
                    <!-- 'checked' als er geen prik-filter actief is -->
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

            <!-- Radio-knoppen voor het alcohol-filter (alles / met alcohol / zonder alcohol) -->
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

            <!-- Dropdown met alle regio's; verstuurt het formulier direct bij wijziging -->
            <div class="filter-groep">
                <label for="regio" class="filter-label">Regio</label>
                <select name="regio" id="regio" class="filter-select" onchange="this.form.submit()">
                    <!-- Standaard optie om het regio-filter te wissen -->
                    <option value="">Alle regio's</option>
                    <?php foreach ($regioLijst as $regio): ?>
                        <!-- 'selected' als deze regio momenteel gefilterd wordt -->
                        <option value="<?= zuiverString($regio) ?>" <?= $filters['regio'] === $regio ? 'selected' : '' ?>>
                            <?= zuiverString($regio) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Filterknop om het formulier te versturen -->
            <button type="submit" class="knop knop--filter">Filteren</button>
            <!-- Link om alle filters in één klik te wissen door terug te gaan zonder GET-parameters -->
            <a href="<?= BASE_URL ?>/pages/products.php" class="knop knop--reset">Wis alle filters</a>
        </form>
    </aside>

    <!-- Rechter sectie met het productenraster -->
    <section class="producten-sectie">

        <!-- Koptekst met de paginatitel en het aantal gevonden producten -->
        <div class="producten-header">
            <h1 class="producten-titel">Onze Dranken</h1>
            <!-- Enkelvoud/meervoud correct: "1 product" vs "3 producten" -->
            <span class="producten-aantal">
                <?= count($drankLijst) ?> product<?= count($drankLijst) !== 1 ? 'en' : '' ?> gevonden
            </span>
        </div>

        <!-- Verborgen melding die de JS-live-zoekfunctie toont als niets overeenkomt -->
        <p id="geen-resultaten" style="display:none;" class="geen-producten">
            Geen dranken gevonden met deze zoekopdracht.
        </p>

        <?php if (empty($drankLijst)): ?>
            <!-- Server-side: geen resultaten na filteren, toon een lege-staat melding -->
            <p class="geen-producten">
                Geen dranken gevonden met de huidige filters.
                <a href="<?= BASE_URL ?>/pages/products.php">Wis alle filters</a>
            </p>
        <?php else: ?>
            <!-- Raster met één kaart per gevonden product -->
            <div class="producten-grid">
                <?php foreach ($drankLijst as $drank): ?>
                    <!-- data-naam wordt gebruikt door de live zoekfunctie in main.js -->
                    <article class="product-kaart" data-naam="<?= zuiverString($drank['naam']) ?>">

                        <!-- Klikbare afbeelding die naar de detailpagina leidt -->
                        <a href="<?= BASE_URL ?>/pages/detail.php?id=<?= (int) $drank['id'] ?>"
                           class="product-kaart__afbeelding-link">
                            <img
                                src="<?= BASE_URL ?>/assets/images/<?= zuiverString($drank['afbeelding']) ?>"
                                alt="<?= zuiverString($drank['naam']) ?>"
                                class="product-kaart__afbeelding"
                                onerror="this.onerror=null;this.src='<?= BASE_URL ?>/assets/images/placeholder.png'">
                                <!-- onerror: als de afbeelding ontbreekt, laad de placeholder in -->
                        </a>

                        <!-- Tekstinformatie onder de afbeelding -->
                        <div class="product-kaart__info">

                            <!-- Productnaam als link naar de detailpagina -->
                            <h3 class="product-kaart__naam">
                                <a href="<?= BASE_URL ?>/pages/detail.php?id=<?= (int) $drank['id'] ?>">
                                    <?= zuiverString($drank['naam']) ?>
                                </a>
                            </h3>

                            <!-- Regio waar de drank vandaan komt -->
                            <p class="product-kaart__regio">
                                <?= zuiverString($drank['regio']) ?>
                            </p>

                            <!-- Kenmerk-badges: prik en alcohol -->
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

                            <!-- Prijs geformatteerd met euroteken -->
                            <p class="product-kaart__prijs">
                                <?= formateerPrijs((float) $drank['prijs']) ?>
                            </p>

                            <!-- Formulier om het product toe te voegen aan het winkelmandje -->
                            <form action="<?= BASE_URL ?>/actions/add.php" method="POST" class="product-kaart__formulier">
                                <!-- Verborgen veld met het ID van dit product -->
                                <input type="hidden" name="drank_id" value="<?= (int) $drank['id'] ?>">
                                <!-- Na toevoegen keert de gebruiker terug naar de productenpagina -->
                                <input type="hidden" name="terugkeer_url" value="<?= BASE_URL ?>/pages/products.php">
                                <button type="submit" class="knop knop--toevoegen">+ Toevoegen</button>
                            </form>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </section>

</div>

<?php
// Sluit de pagina af met de gedeelde footer
include '../includes/footer.php';
?>
