<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
include '../includes/header.php';

$winkelmandjeLijst = haalWinkelmandjeItems($db);
$totaalprijs = berekenTotaalprijs($db);
?>

<div class="winkelmandje-pagina">
    <h1 class="pagina-titel">Winkelmandje</h1>

    <?php if (empty($winkelmandjeLijst)): ?>
        <div class="leeg-mandje">
            <p>Je winkelmandje is leeg.</p>
            <a href="<?= BASE_URL ?>/pages/products.php" class="knop knop--groot">
                Bekijk producten
            </a>
        </div>

    <?php else: ?>
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
                        <!-- Product naam + afbeelding -->
                        <td class="mandje-product">
                            <img src="<?= BASE_URL ?>/assets/images/<?= zuiverString($item['drank']['afbeelding']) ?>"
                                alt="<?= zuiverString($item['drank']['naam']) ?>" class="mandje-afbeelding"
                                onerror="this.onerror=null;this.src='<?= BASE_URL ?>/assets/images/placeholder.png'">
                            <span><?= zuiverString($item['drank']['naam']) ?></span>
                        </td>

                        <!-- Stuksprijs -->
                        <td><?= formateerPrijs((float) $item['drank']['prijs']) ?></td>

                        <!-- Aantal met +/- knoppen -->
                        <td>
                            <div class="aantal-bediening">
                                <!-- Min-knop -->
                                <form action="<?= BASE_URL ?>/actions/update.php" method="POST" class="inline-formulier">
                                    <input type="hidden" name="drank_id" value="<?= (int) $item['drank']['id'] ?>">
                                    <input type="hidden" name="nieuw_aantal" value="<?= $item['aantal'] - 1 ?>">
                                    <button type="submit" class="aantal-knop" <?= $item['aantal'] <= 1 ? 'disabled' : '' ?>>
                                        &minus;
                                    </button>
                                </form>

                                <span class="aantal-waarde"><?= $item['aantal'] ?></span>

                                <!-- Plus-knop -->
                                <form action="<?= BASE_URL ?>/actions/update.php" method="POST" class="inline-formulier">
                                    <input type="hidden" name="drank_id" value="<?= (int) $item['drank']['id'] ?>">
                                    <input type="hidden" name="nieuw_aantal" value="<?= $item['aantal'] + 1 ?>">
                                    <button type="submit" class="aantal-knop">+</button>
                                </form>
                            </div>
                        </td>

                        <!-- Subtotaal -->
                        <td><?= formateerPrijs($item['subtotaal']) ?></td>

                        <!-- Verwijder -->
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
            <tfoot>
                <tr>
                    <td colspan="3" class="totaal-label">Totaal</td>
                    <td colspan="2" class="totaal-prijs"><?= formateerPrijs($totaalprijs) ?></td>
                </tr>
            </tfoot>
        </table>

        <div class="mandje-acties">
            <a href="<?= BASE_URL ?>/pages/products.php" class="knop knop--secundair">
                ← Verder winkelen
            </a>
            <a href="<?= BASE_URL ?>/pages/checkout.php" class="knop knop--bestellen">
                Afrekenen →
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>