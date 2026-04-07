<?php
session_start(); // start sessie voor winkelmandd

$DB_HOST = "localhost";          // meestal localhost
$DB_USER = "yusuf";  // gebruiker uit stap 3
$DB_PASS = "Mamapapa2006!";    // wachtwoord uit stap 3
$DB_NAME = "turkiye_drinks";     // databasenaam uit stap 3

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if ($conn->connect_error) {
    die("Database connectie mislukt: " . $conn->connect_error);
}

