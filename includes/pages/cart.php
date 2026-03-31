<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$conn = getDB();
?>

<link rel="stylesheet" href="../css/style.css">
<?php include '../includes/header.php'; ?>

<h2>Winkelmand</h2>

<?php if (empty($_SESSION['cart'])): ?>
    <p>Je winkelmand is leeg</p>
<?php else: ?>

    <table>
        <tr>
            <th>Product</th>
            <th>Aantal</th>
            <th>Prijs</th>
        </tr>

        <?php foreach ($_SESSION['cart'] as $id => $qty):
            $stmt = $conn->prepare("SELECT * FROM dranken WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $product = $stmt->get_result()->fetch_assoc();
            ?>

            <tr>
                <td>
                    <?php echo $product['naam']; ?>
                </td>
                <td>
                    <?php echo $qty; ?>
                </td>
                <td>€
                    <?php echo $product['prijs'] * $qty; ?>
                </td>
            </tr>

        <?php endforeach; ?>

    </table>

    <h3>Totaal: €
        <?php echo calculateTotal($conn); ?>
    </h3>

    <form action="../actions/order.php" method="POST">
        <input type="text" name="name" placeholder="Naam" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="text" name="adres" placeholder="Adres" required><br>

        <select name="payment">
            <option>iDeal</option>
            <option>PayPal</option>
        </select><br>

        <button type="submit">Bestellen</button>
    </form>

<?php endif; ?>