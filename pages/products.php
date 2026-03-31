<?php include '../includes/db.php';
include '../includes/functions.php';
$conn = db();
include '../includes/header.php'; ?>
<h2>Producten</h2>
<div class="products">
    <?php $r = $conn->query("SELECT * FROM dranken");
    while ($row = $r->fetch_assoc()) { ?>
        <div class="product">
            <h3><?php echo $row['naam']; ?></h3>
            <p><?php echo $row['regio']; ?></p>
            <p>€<?php echo $row['prijs']; ?></p>
            <a href="../actions/add.php?id=<?php echo $row['id']; ?>">Toevoegen</a>
        </div>
    <?php } ?>
</div>