<?php
session_start();

function addToCart($id,$qty){
if(!isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id]=$qty;
else $_SESSION['cart'][$id]+=$qty;
}

function removeFromCart($id){
unset($_SESSION['cart'][$id]);
}

function total($conn){
$t=0;
if(empty($_SESSION['cart'])) return 0;
foreach($_SESSION['cart'] as $id=>$q){
$stmt=$conn->prepare("SELECT prijs FROM dranken WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$r=$stmt->get_result()->fetch_assoc();
$t+=$r['prijs']*$q;
}
return $t;
}

function saveOrder($conn,$n,$e,$a){
$t=total($conn);
$stmt=$conn->prepare("INSERT INTO bestellingen (naam,email,adres,totaal) VALUES (?,?,?,?)");
$stmt->bind_param("sssd",$n,$e,$a,$t);
$stmt->execute();
$id=$stmt->insert_id;

foreach($_SESSION['cart'] as $pid=>$q){
$s=$conn->prepare("INSERT INTO bestelling_items (bestelling_id,drank_id,aantal) VALUES (?,?,?)");
$s->bind_param("iii",$id,$pid,$q);
$s->execute();
}
unset($_SESSION['cart']);
}

function getProducts($conn,$p,$a){
$sql="SELECT * FROM dranken WHERE 1";
if($p) $sql.=" AND prik=1";
if($a) $sql.=" AND alcohol=1";
return $conn->query($sql);
}
?>