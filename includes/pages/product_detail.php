<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$conn = getDB();

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM dranken WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

$product = $stmt->get_result()->fetch_assoc();
?>

<link rel="stylesheet" href="../css/style.css">
<?php include '../includes/header.php'; ?>

<div class="product-detail">
    <h2>
        <?php echo $product['naam']; ?>
    </h2>

    <img src="../assets/images/<?php echo $product['naam']; ?>.png" width="200">

    <p>
        <?php echo $product['beschrijving']; ?>
    </p>
    <p>Regio:
        <?php echo $product['regio']; ?>
    </p>
    <p>Prijs: €
        <?php echo $product['prijs']; ?>
    </p>

    <a href="../actions/add_to_cart.php?id=<?php echo $product['id']; ?>">Toevoegen</a>
</div>