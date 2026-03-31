<?php include '../includes/db.php'; include '../includes/functions.php'; include '../includes/header.php';

$p=isset($_GET['prik']);
$a=isset($_GET['alcohol']);

$r=getProducts($conn,$p,$a);
?>

<div class="container">

<form class="filter">
<h3>Filters</h3>
<label><input type="checkbox" name="prik"> Met prik</label>
<label><input type="checkbox" name="alcohol"> Met alcohol</label>
<button>Filter</button>
</form>

<div class="products">
<?php while($row=$r->fetch_assoc()){ ?>
<div class="card">
<a href="detail.php?id=<?php echo $row['id']; ?>">
<img src="../assets/images/<?php echo $row['naam']; ?>.png">
</a>
<h2><?php echo $row['naam']; ?></h2>
<p><?php echo $row['beschrijving']; ?></p>
<p>€<?php echo $row['prijs']; ?></p>

<form action="../actions/add.php">
<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
<input type="number" name="qty" value="1" min="1">
<button>Toevoegen</button>
</form>

</div>
<?php } ?>
</div>
</div>
