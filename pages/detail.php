<?php include '../includes/db.php'; include '../includes/header.php';
$id=$_GET['id'];
$r=$conn->query("SELECT * FROM dranken WHERE id=$id")->fetch_assoc();
?>
<div class="detail">
<img src="../assets/images/<?php echo $r['naam']; ?>.png">
<h1><?php echo $r['naam']; ?></h1>
<p><?php echo $r['beschrijving']; ?></p>
<p>Regio: <?php echo $r['regio']; ?></p>
<p>€<?php echo $r['prijs']; ?></p>
</div>
