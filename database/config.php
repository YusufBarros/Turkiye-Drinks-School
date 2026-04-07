<?php
session_start(); // start sessie voor winkelmand

$DB_HOST = "localhost:3306";   // voor XAMPP
$DB_USER = "yusuf";        // standaard XAMPP user
$DB_PASS = "Mamapapa2006!";            // standaard leeg
$DB_NAME = "turkiye_drinks";        // jouw database naam

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if ($conn->connect_error) {
    die("Database connectie mislukt: " . $conn->connect_error);
}

