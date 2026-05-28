<?php
// Laad de databaseverbinding en hulpfuncties in
require_once '../includes/db.php';
require_once '../includes/functions.php';
// Toon de gedeelde header
include '../includes/header.php';

// Haal alle items op uit het winkelmandje inclusief drank-info en subtotalen
$winkelmandjeLijst = haalWinkelmandjeItems($db);
// Bereken de totaalprijs van alle items samen
$totaalprijs = berekenTotaalprijs($db);
?>

<div class="winkelmandje-pagina">
    <h1 class="pagina-titel">Winkelmandje</h1>

    <?php if (empty($winkelmandjeLijst)): ?>
        <!-- Lege staat: het mandje bevat geen producten -->
        <div class="leeg-mandje">
            <p>Je winkelmandje is leeg.</p>
            <!-- Stuur de gebruiker terug naar de producten -->
            <a href="<?= BASE_URL ?>/pages/products.php" class="knop knop--groot">Bekijk producten</a>
        </div>

    <?php else: ?>
        <!-- Tabel met alle producten in het mandje -->
        <table class="mandje-tabel">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Prijs</th>
                    <th>Aantal</th>
                    <th>Subtotaal</th>
                    <th>Verwijder</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($winkelmandjeLijst as $item): ?>
                    <tr>
                        <!-- Miniatuurafbeelding en naam van het product naast elkaar -->
                        <td class="mandje-product">
                            <img
                                src="<?= BASE_URL ?>/assets/images/<?= zuiverString($item['drank']['afbeelding']) ?>"
                                alt="<?= zuiverString($item['drank']['naam']) ?>"
                                class="mandje-afbeelding"
                                onerror="this.onerror=null;this.src='<?= BASE_URL ?>/assets/images/placeholder.png'">
                            <span><?= zuiverString($item['drank']['naam']) ?></span>
                        </td>

                        <!-- Prijs per stuk -->
                        <td><?= formateerPrijs((float) $item['drank']['prijs']) ?></td>

                        <!-- Aantal aanpassen met min- en plusknop via aparte formulieren -->
                        <td>
                            <div class="aantal-bediening">

                                <!-- Min-knop: verlaagt het aantal met 1; uitgeschakeld bij 1 om naar 0 te gaan -->
                                <form action="<?= BASE_URL ?>/actions/update.php" method="POST" class="inline-formulier">
                                    <input type="hidden" name="drank_id" value="<?= (int) $item['drank']['id'] ?>">
                                    <!-- Stuurt het huidige aantal minus 1 naar de update-action -->
                                    <input type="hidden" name="nieuw_aantal" value="<?= $item['aantal'] - 1 ?>">
                                    <!-- disabled voorkomt dat het aantal onder 1 kan komen via de knop -->
                                    <button type="submit" class="aantal-knop" <?= $item['aantal'] <= 1 ? 'disabled' : '' ?>>
                                        &minus;
                                    </button>
                                </form>

                                <!-- Huidig aantal als tekst tussen de twee knoppen -->
                                <span class="aantal-waarde"><?= $item['aantal'] ?></span>

                                <!-- Plus-knop: verhoogt het aantal met 1 -->
                                <form action="<?= BASE_URL ?>/actions/update.php" method="POST" class="inline-formulier">
                                    <input type="hidden" name="drank_id" value="<?= (int) $item['drank']['id'] ?>">
                                    <!-- Stuurt het huidige aantal plus 1 naar de update-action -->
                                    <input type="hidden" name="nieuw_aantal" value="<?= $item['aantal'] + 1 ?>">
                                    <button type="submit" class="aantal-knop">+</button>
                                </form>

                            </div>
                        </td>

                        <!-- Subtotaal: prijs × aantal voor dit product -->
                        <td><?= formateerPrijs($item['subtotaal']) ?></td>

                        <!-- Verwijderknop: stuurt een POST naar remove.php om het product te wissen -->
                        <td>
                            <form action="<?= BASE_URL ?>/actions/remove.php" method="POST" class="inline-formulier">
                                <input type="hidden" name="drank_id" value="<?= (int) $item['drank']['id'] ?>">
                                <!-- &#x2715; is het ✕-teken -->
                                <button type="submit" class="knop knop--verwijder"
                                        title="Verwijder <?= zuiverString($item['drank']['naam']) ?>">
                                    &#x2715;
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

            <!-- Totaalrij onderaan de tabel -->
            <tfoot>
                <tr>
                    <!-- colspan zodat het label de eerste drie kolommen beslaat -->
                    <td colspan="3" class="totaal-label">Totaal</td>
                    <!-- Totaalprijs in de laatste twee kolommen -->
                    <td colspan="2" class="totaal-prijs"><?= formateerPrijs($totaalprijs) ?></td>
                </tr>
            </tfoot>
        </table>

        <!-- Navigatieknoppen: verder winkelen of doorgaan naar het bestelformulier -->
        <div class="mandje-acties">
            <a href="<?= BASE_URL ?>/pages/products.php" class="knop knop--secundair">← Verder winkelen</a>
            <a href="<?= BASE_URL ?>/pages/checkout.php" class="knop knop--bestellen">Afrekenen →</a>
        </div>
    <?php endif; ?>
</div>

<?php
// Sluit de pagina af met de gedeelde footer
include '../includes/footer.php';
?>
