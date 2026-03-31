<?php include '../includes/db.php'; include '../includes/functions.php';

$name=$_POST['name'];
$email=$_POST['email'];
$adres=$_POST['adres'];

$t = total($conn);

$conn->query("INSERT INTO bestellingen (naam,email,adres,totaal) VALUES ('$name','$email','$adres','$t')");
$order_id = $conn->insert_id;

foreach($_SESSION['cart'] as $id=>$q){
$conn->query("INSERT INTO bestelling_items (bestelling_id,drank_id,aantal) VALUES ($order_id,$id,$q)");
}

unset($_SESSION['cart']);
echo "Bestelling opgeslagen!";
?>