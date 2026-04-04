<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
include 'includes/header.php';
?>
<section class="hero">
    <div class="hero__overlay">
        <h2 class="hero__titel">Welkom bij Turkiye Drinks</h2>
        <p class="hero__ondertitel">
            De lekkerste authentieke Turkse dranken, thuisbezorgd in Nederland.
        </p>
        <a href="<?= BASE_URL ?>/pages/products.php" class="knop knop--groot">
            Bekijk ons assortiment
        </a>
    </div>
</section>
<?php include 'includes/footer.php'; ?>