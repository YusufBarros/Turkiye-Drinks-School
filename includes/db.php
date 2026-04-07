<?php
// Bereken de basis-URL op basis van de map van het project
$_docRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
$_appRoot = str_replace('\\', '/', realpath(__DIR__ . '/..'));
define('BASE_URL', str_replace($_docRoot, '', $_appRoot));
unset($_docRoot, $_appRoot);

// Maak een databaseverbinding en sla hem op zodat het maar 1x gebeurt
function maakDatabaseVerbinding(): PDO
{
    static $verbinding = null;

    // Geef de bestaande verbinding terug als die er al is
    if ($verbinding !== null) {
        return $verbinding;
    }

    $host = 'localhost';
    $databaseNaam = 'turkiye_drinks';
    $gebruikersnaam = 'root';
    $wachtwoord = '';

    try {
        $verbinding = new PDO(
            "mysql:host={$host};dbname={$databaseNaam};charset=utf8mb4",
            $gebruikersnaam,
            $wachtwoord,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Gooi fouten als exceptions
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Geef resultaten terug als array
                PDO::ATTR_EMULATE_PREPARES => false,                   // Gebruik echte prepared statements
            ]
        );
    } catch (PDOException $fout) {
        // Laat een foutmelding zien als de verbinding mislukt
        die(
            '<p style="font-family:Arial;color:#C0392B;padding:20px;">'
            . 'Databaseverbinding mislukt. Controleer of MySQL actief is.'
            . '</p>'
        );
    }

    return $verbinding;
}

$db = maakDatabaseVerbinding();