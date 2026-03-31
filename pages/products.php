<?php include '../includes/db.php'; include '../includes/functions.php'; include '../includes/header.php';

$filters = [
    'prik' => $_GET['prik'] ?? null,
    'alcohol' => $_GET['alcohol'] ?? null
];

$query = "SELECT * FROM dranken ";
$query .= filterQuery($filters);

$r = $conn->query($query);
?>

<div class="container">
<div class="filter">
<h3>Filters</h3>
<a href="?prik=1">Met prik</a><br>
<a href="?prik=0">Zonder prik</a><br>
<a href="?alcohol=1">Met alcohol</a><br>
<a href="?alcohol=0">Zonder alcohol</a><br>
</div>

<div class="products">
<?php while($row=$r->fetch_assoc()){ ?>
<div class="product">
<img src="../assets/images/<?php echo strtolower($row['naam']); ?>.png" onerror="this.src='../assets/images/default.png'">
<h3><?php echo $row['naam']; ?></h3>
<p><?php echo $row['regio']; ?></p>
<p>€<?php echo $row['prijs']; ?></p>
<a href="../actions/add.php?id=<?php echo $row['id']; ?>">Toevoegen</a>
</div>
<?php } ?>
</div>
</div>
