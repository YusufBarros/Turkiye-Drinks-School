<?php include '../includes/db.php'; include '../includes/functions.php'; include '../includes/header.php'; ?>
<h2>Winkelmand</h2>

<?php if(empty($_SESSION['cart'])){ echo "<p>Leeg</p>"; } else { 
foreach($_SESSION['cart'] as $id=>$q){
$r=$conn->query("SELECT * FROM dranken WHERE id=$id")->fetch_assoc();
echo "<p>".$r['naam']." x ".$q."</p>";
}
echo "<h3>Totaal: €".total($conn)."</h3>";
} ?>

<form action="../actions/order.php" method="POST">
<input name="name" placeholder="Naam" required>
<input name="email" placeholder="Email" required>
<input name="adres" placeholder="Adres" required>
<button type="submit">Bestellen</button>
</form>
