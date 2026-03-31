<?php
session_start();

function addToCart($id)
{
    if (!isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = 1;
    } else {
        $_SESSION['cart'][$id]++;
    }
}

function total($conn)
{
    $t = 0;
    if (!isset($_SESSION['cart']))
        return 0;

    foreach ($_SESSION['cart'] as $id => $q) {
        $r = $conn->query("SELECT prijs FROM dranken WHERE id=$id")->fetch_assoc();
        $t += $r['prijs'] * $q;
    }
    return $t;
}
?>