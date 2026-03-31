<?php include '../includes/db.php'; include '../includes/header.php'; ?>
<link rel="stylesheet" href="../css/style.css">
<div class="products">
<?php $r=$conn->query("SELECT * FROM dranken");
while($row=$r->fetch_assoc()){ ?>
<div class="product">
<h3><?php echo $row['naam']; ?></h3>
<p><?php echo $row['regio']; ?></p>
<p>€<?php echo $row['prijs']; ?></p>
</div>
<?php } ?>
</div>
