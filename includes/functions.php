<?php
session_start();

function add($id,$q){$_SESSION['cart'][$id]=($_SESSION['cart'][$id]??0)+$q;}
function remove($id){unset($_SESSION['cart'][$id]);}

function total($db){
$t=0;
if(empty($_SESSION['cart'])) return 0;
foreach($_SESSION['cart'] as $i=>$q){
$r=$db->query("SELECT prijs FROM dranken WHERE id=$i")->fetch_assoc();
$t+=$r['prijs']*$q;
}
return $t;
}

function save($db,$n,$e,$a){
$t=total($db);
$db->query("INSERT INTO bestellingen (naam,email,adres,totaal) VALUES ('$n','$e','$a','$t')");
unset($_SESSION['cart']);
}
?>