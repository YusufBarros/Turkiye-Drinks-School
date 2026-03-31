<?php include '../includes/db.php'; include '../includes/functions.php'; include '../includes/header.php'; ?>

<h2>Winkelmand</h2>

<?php if(empty($_SESSION['cart'])) echo "Leeg"; else { ?>
<table>
<tr><th>Product</th><th>Aantal</th><th>Prijs</th></tr>
<?php foreach($_SESSION['cart'] as $id=>$q){
$r=$conn->query("SELECT * FROM dranken WHERE id=$id")->fetch_assoc(); ?>
<tr>
<td><?php echo $r['naam']; ?></td>
<td><?php echo $q; ?></td>
<td>€<?php echo $r['prijs']*$q; ?></td>
</tr>
<?php } ?>
</table>

<h3>Totaal: €<?php echo total($conn); ?></h3>

<form action="../actions/order.php" method="POST">
<input name="name" placeholder="Naam" required>
<input name="email" placeholder="Email" required>
<input name="adres" placeholder="Adres" required>
<button type="submit">Bestellen</button>
</form>

<?php } ?>
