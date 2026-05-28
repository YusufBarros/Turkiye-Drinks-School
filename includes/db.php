<?php
// Bereken de basis-URL van de app op basis van de documentroot van de server
$_docRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
$_appRoot = str_replace('\\', '/', realpath(__DIR__ . '/..'));
// BASE_URL bevat het pad van de documentroot naar de projectmap, bijv. /turkiye-drinks
define('BASE_URL', str_replace($_docRoot, '', $_appRoot));
// Verwijder de tijdelijke variabelen zodat ze de globale scope niet vervuilen
unset($_docRoot, $_appRoot);

// Maakt een PDO-databaseverbinding en bewaart die via static zodat het maar 1x gebeurt
function maakDatabaseVerbinding(): PDO
{
    // static zorgt dat $verbinding bewaard blijft tussen aanroepen
    static $verbinding = null;

    // Geef de bestaande verbinding terug als die er al is (singleton-patroon)
    if ($verbinding !== null) {
        return $verbinding;
    }

    // Verbindingsinstellingen voor de lokale MySQL-database
    $host          = 'localhost';
    $databaseNaam  = 'turkiye_drinks';
    $gebruikersnaam = 'root';
    $wachtwoord    = '';

    try {
        $verbinding = new PDO(
            "mysql:host={$host};dbname={$databaseNaam};charset=utf8mb4",
            $gebruikersnaam,
            $wachtwoord,
            [
                // Gooi een exception bij een databasefout in plaats van stil te falen
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                // Geef resultaten terug als associatieve array (kolom => waarde)
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                // Gebruik echte prepared statements (betere beveiliging)
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
    } catch (PDOException $fout) {
        // Toon een leesbare foutmelding en stop de uitvoering als de verbinding mislukt
        die(
            '<p style="font-family:Arial;color:#C0392B;padding:20px;">'
            . 'Databaseverbinding mislukt. Controleer of MySQL actief is.'
            . '</p>'
        );
    }

    return $verbinding;
}

// Maak de verbinding aan en sla hem op in $db voor gebruik in alle pagina's
$db = maakDatabaseVerbinding();
