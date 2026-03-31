<?php
require_once '../includes/functions.php';

// ID ophalen
$id = $_GET['id'];

// Functie gebruiken
addToCart($id);

// Terug naar producten
header("Location: ../pages/products.php");
exit;