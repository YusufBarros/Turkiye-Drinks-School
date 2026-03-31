<?php include '../includes/functions.php';
addToCart($_GET['id']);
header("Location: ../pages/products.php");
?>