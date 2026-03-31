<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$conn = getDB();

$name = $_POST['name'];
$email = $_POST['email'];
$adres = $_POST['adres'];

$total = calculateTotal($conn);

// bestelling opslaan
$stmt = $conn->prepare("INSERT INTO bestellingen (naam, email, adres, totaal) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssd", $name, $email, $adres, $total);
$stmt->execute();

$order_id = $stmt->insert_id;

// items opslaan
foreach ($_SESSION['cart'] as $id => $qty) {
    $stmt = $conn->prepare("INSERT INTO bestelling_items (bestelling_id, drank_id, aantal) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $order_id, $id, $qty);
    $stmt->execute();
}

// mand leegmaken
unset($_SESSION['cart']);

echo "Bestelling geplaatst!";