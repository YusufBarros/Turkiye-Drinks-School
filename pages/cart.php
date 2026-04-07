<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
include '../includes/header.php';

// Haal alle items op uit het winkelmandje en bereken de totaalprijs
$winkelmandjeLijst = haalWinkelmandjeItems($db);
$totaalprijs = berekenTotaalprijs($db);
?>

<div class="winkelmandje-pagina">
    <h1 class="pagina-titel">Winkelmandje</h1>

    <?php if (empty($winkelmandjeLijst)): ?>
        <!-- Mandje is leeg, toon een melding en knop naar producten -->
        <div class="leeg-mandje">
            <p>Je winkelmandje is leeg.</p>
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
                        <!-- Afbeelding + naam van het product -->
                        <td class="mandje-product">
                            <img src="<?= BASE_URL ?>/assets/images/<?= zuiverString($item['drank']['afbeelding']) ?>"
                                alt="<?= zuiverString($item['drank']['naam']) ?>" class="mandje-afbeelding"
                                onerror="this.onerror=null;this.src='<?= BASE_URL ?>/assets/images/placeholder.png'">
                            <span><?= zuiverString($item['drank']['naam']) ?></span>
                        </td>

                        <!-- Prijs per stuk -->
                        <td><?= formateerPrijs((float) $item['drank']['prijs']) ?></td>

                        <!-- Aantal aanpassen met min en plus knop -->
                        <td>
                            <div class="aantal-bediening">

                                <!-- Min knop: verlaagt het aantal met 1, disabled bij 1 -->
                                <form action="<?= BASE_URL ?>/actions/update.php" method="POST" class="inline-formulier">
                                    <input type="hidden" name="drank_id" value="<?= (int) $item['drank']['id'] ?>">
                                    <input type="hidden" name="nieuw_aantal" value="<?= $item['aantal'] - 1 ?>">
                                    <button type="submit" class="aantal-knop" <?= $item['aantal'] <= 1 ? 'disabled' : '' ?>>
                                        &minus;
                                    </button>
                                </form>

                                <!-- Huidig aantal -->
                                <span class="aantal-waarde"><?= $item['aantal'] ?></span>

                                <!-- Plus knop: verhoogt het aantal met 1 -->
                                <form action="<?= BASE_URL ?>/actions/update.php" method="POST" class="inline-formulier">
                                    <input type="hidden" name="drank_id" value="<?= (int) $item['drank']['id'] ?>">
                                    <input type="hidden" name="nieuw_aantal" value="<?= $item['aantal'] + 1 ?>">
                                    <button type="submit" class="aantal-knop">+</button>
                                </form>

                            </div>
                        </td>

                        <!-- Prijs x aantal -->
                        <td><?= formateerPrijs($item['subtotaal']) ?></td>

                        <!-- Verwijder het product volledig uit het mandje -->
                        <td>
                            <form action="<?= BASE_URL ?>/actions/remove.php" method="POST" class="inline-formulier">
                                <input type="hidden" name="drank_id" value="<?= (int) $item['drank']['id'] ?>">
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
                    <td colspan="3" class="totaal-label">Totaal</td>
                    <td colspan="2" class="totaal-prijs"><?= formateerPrijs($totaalprijs) ?></td>
                </tr>
            </tfoot>
        </table>

        <!-- Navigatieknoppen: terug winkelen of doorgaan naar checkout -->
        <div class="mandje-acties">
            <a href="<?= BASE_URL ?>/pages/products.php" class="knop knop--secundair">← Verder winkelen</a>
            <a href="<?= BASE_URL ?>/pages/checkout.php" class="knop knop--bestellen">Afrekenen →</a>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>