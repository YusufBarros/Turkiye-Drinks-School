<?php include '../includes/db.php'; include '../includes/functions.php'; include '../includes/header.php'; ?>

<h2>Winkelmand</h2>

<?php if(empty($_SESSION['cart'])){ ?>
<p>Je winkelmand is leeg, voeg producten toe om af te rekenen</p>
<?php } else { ?>

<table>
<tr><th>Product</th><th>Aantal</th><th>Prijs</th><th></th></tr>
<?php foreach($_SESSION['cart'] as $id=>$q){
$r=$conn->query("SELECT * FROM dranken WHERE id=$id")->fetch_assoc(); ?>
<tr>
<td><?php echo $r['naam']; ?></td>
<td><?php echo $q; ?></td>
<td>€<?php echo $r['prijs']*$q; ?></td>
<td><a href="../actions/remove.php?id=<?php echo $id; ?>">X</a></td>
</tr>
<?php } ?>
</table>

<h3 style="text-align:center;">Totaal: €<?php echo total($conn); ?></h3>

<form action="../actions/order.php" method="POST">
<input name="name" placeholder="Naam" required>
<input name="email" placeholder="Email" required>
<input name="adres" placeholder="Adres" required>
<button>Bestellen</button>
</form>

<?php } ?>
