<?php
session_start();
function addToCart($id){
if(!isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id]=1;
else $_SESSION['cart'][$id]++;
}
?>