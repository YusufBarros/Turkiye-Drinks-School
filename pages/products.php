<?php include '../includes/db.php'; include '../includes/functions.php'; include '../includes/header.php'; ?>
<div class="products">
<?php $r=$conn->query("SELECT * FROM dranken");
while($row=$r->fetch_assoc()){ ?>
<div class="product">
<img src="../assets/images/<?php echo strtolower($row['naam']); ?>.png">
<h3><?php echo $row['naam']; ?></h3>
<p>€<?php echo $row['prijs']; ?></p>
<a href="../actions/add.php?id=<?php echo $row['id']; ?>">Toevoegen</a>
</div>
<?php } ?>
</div>
