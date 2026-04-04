<?php
// ── Basis-URL dynamisch berekenen ────────────────────────────
// Werkt zowel in de hoofd-map als in een git-worktree.
$_docRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
$_appRoot = str_replace('\\', '/', realpath(__DIR__ . '/..'));
define('BASE_URL', str_replace($_docRoot, '', $_appRoot));
unset($_docRoot, $_appRoot);

// ── PDO-databaseverbinding ───────────────────────────────────
function maakDatabaseVerbinding(): PDO
{
    static $verbinding = null;

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
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    } catch (PDOException $fout) {
        die(
            '<p style="font-family:Arial;color:#C0392B;padding:20px;">'
            . 'Databaseverbinding mislukt. Controleer of MySQL actief is.'
            . '</p>'
        );
    }

    return $verbinding;
}

$db = maakDatabaseVerbinding();
