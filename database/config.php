<?php
// Start de sessie voor het winkelmandje (dit bestand wordt niet meer gebruikt door de app;
// de actieve databaseverbinding zit in includes/db.php via PDO)
session_start();

// Verbindingsgegevens voor de lokale MySQL-database
$DB_HOST = "localhost";         // databaseserver (meestal localhost bij XAMPP)
$DB_USER = "yusuf";            // MySQL-gebruikersnaam
$DB_PASS = "Mamapapa2006!";    // MySQL-wachtwoord
$DB_NAME = "turkiye_drinks";   // naam van de database

// Maak de verbinding aan via MySQLi
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Stop de uitvoering als de verbinding mislukt en toon de foutmelding
if ($conn->connect_error) {
    die("Database connectie mislukt: " . $conn->connect_error);
}
