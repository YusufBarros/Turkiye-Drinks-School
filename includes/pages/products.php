<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$conn = getDB();

// Filters ophalen
$filters = [
    'prik' => $_GET['prik'] ?? null,
    'alcohol' => $_GET['alcohol'] ?? null,
    'regio' => $_GET['regio'] ?? null
];

// Query bouwen
$query = "SELECT * FROM dranken ";
$query .= buildFilterQuery($filters);

$result = $conn->query($query);
?>

<link rel="stylesheet" href="../css/style.css">
<?php include '../includes/header.php'; ?>

<div class="container">

    <!-- FILTER -->
    <div class="filter">
        <h3>Filter</h3>

        <a href="?prik=1">Met prik</a><br>
        <a href="?prik=0">Zonder prik</a><br>

        <a href="?alcohol=1">Met alcohol</a><br>
        <a href="?alcohol=0">Zonder alcohol</a><br>

        <a href="?regio=Istanbul">Istanbul</a><br>
        <a href="?regio=Anatolie">Anatolie</a><br>
    </div>

    <!-- PRODUCTEN -->
    <div class="products">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product">
                <img src="../assets/images/<?php echo $row['naam']; ?>.png" width="100">

                <h3>
                    <?php echo $row['naam']; ?>
                </h3>
                <p>€
                    <?php echo $row['prijs']; ?>
                </p>
                <p>
                    <?php echo $row['regio']; ?>
                </p>

                <a href="product_detail.php?id=<?php echo $row['id']; ?>">Bekijk</a><br>
                <a href="../actions/add_to_cart.php?id=<?php echo $row['id']; ?>">Toevoegen</a>
            </div>
        <?php endwhile; ?>
    </div>

</div>