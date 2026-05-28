<?php
// Start de sessie als die nog niet actief is (nodig voor het winkelmandje)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Haal alle dranken op uit de database, optioneel gefilterd op zoekterm, prik, alcohol of regio
function haalAlleDrankenOp(PDO $db, array $filters = []): array
{
    // Basisquery die altijd alle dranken selecteert; filters worden er dynamisch aan toegevoegd
    $sql = "SELECT * FROM dranken WHERE 1=1";
    $parameters = [];

    // Voeg zoekterm-filter toe als die ingevuld is (gedeeltelijke naam-match)
    if (!empty($filters['zoekterm'])) {
        $sql .= " AND naam LIKE :zoekterm";
        $parameters[':zoekterm'] = '%' . $filters['zoekterm'] . '%';
    }

    // Voeg prik-filter toe (1 = met prik, 0 = zonder prik)
    if (isset($filters['met_prik']) && $filters['met_prik'] !== '') {
        $sql .= " AND met_prik = :met_prik";
        $parameters[':met_prik'] = (int) $filters['met_prik'];
    }

    // Voeg alcohol-filter toe (1 = met alcohol, 0 = zonder alcohol)
    if (isset($filters['alcohol']) && $filters['alcohol'] !== '') {
        $sql .= " AND alcohol = :alcohol";
        $parameters[':alcohol'] = (int) $filters['alcohol'];
    }

    // Voeg regio-filter toe als een specifieke regio gekozen is
    if (!empty($filters['regio'])) {
        $sql .= " AND regio = :regio";
        $parameters[':regio'] = $filters['regio'];
    }

    // Sorteer altijd alfabetisch op naam
    $sql .= " ORDER BY naam ASC";

    try {
        // Voer de query uit met de ingebouwde parameters (beschermt tegen SQL-injectie)
        $statement = $db->prepare($sql);
        $statement->execute($parameters);
        return $statement->fetchAll();
    } catch (PDOException $fout) {
        // Geef een lege array terug als de query mislukt
        return [];
    }
}

// Haal één drank op via het ID; geeft null terug als het product niet bestaat
function haalDrankOpId(PDO $db, int $drankId): ?array
{
    try {
        // LIMIT 1 zorgt dat de query stopt zodra het product gevonden is
        $statement = $db->prepare("SELECT * FROM dranken WHERE id = :id LIMIT 1");
        $statement->execute([':id' => $drankId]);
        $drank = $statement->fetch();
        // fetch() geeft false terug als er niets gevonden is; zet dit om naar null
        return $drank ?: null;
    } catch (PDOException $fout) {
        return null;
    }
}

// Haal alle unieke regio's op voor het filter-dropdown op de productenpagina
function haalAlleRegiosOp(PDO $db): array
{
    try {
        $statement = $db->prepare(
            "SELECT DISTINCT regio FROM dranken
             WHERE regio IS NOT NULL
             ORDER BY regio ASC"
        );
        $statement->execute();
        // FETCH_COLUMN geeft een platte array van waarden terug in plaats van geneste arrays
        return $statement->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $fout) {
        return [];
    }
}

// Voeg een drank toe aan het winkelmandje in de sessie
function voegToeAanWinkelmandje(int $drankId, int $aantal = 1): void
{
    // Zorg dat het winkelmandje-array bestaat in de sessie
    if (!isset($_SESSION['winkelmandje'])) {
        $_SESSION['winkelmandje'] = [];
    }

    // Gebruik de ID als string-sleutel voor de sessie-array
    $sleutel = (string) $drankId;

    // Als het product er al in zit, tel het nieuwe aantal op bij het bestaande
    if (isset($_SESSION['winkelmandje'][$sleutel])) {
        $_SESSION['winkelmandje'][$sleutel] += $aantal;
    } else {
        // Nieuw product: sla het aantal op
        $_SESSION['winkelmandje'][$sleutel] = $aantal;
    }
}

// Verwijder een drank volledig uit het winkelmandje
function verwijderUitWinkelmandje(int $drankId): void
{
    // unset verwijdert de sleutel uit de sessie-array
    unset($_SESSION['winkelmandje'][(string) $drankId]);
}

// Pas het aantal van een drank aan; bij 0 of minder wordt het product verwijderd
function wijzigAantalInWinkelmandje(int $drankId, int $nieuwAantal): void
{
    if ($nieuwAantal <= 0) {
        // Verwijder het product als het aantal op 0 of lager komt
        verwijderUitWinkelmandje($drankId);
    } else {
        // Overschrijf het huidige aantal met de nieuwe waarde
        $_SESSION['winkelmandje'][(string) $drankId] = $nieuwAantal;
    }
}

// Geeft alle winkelmandje-items terug als array met drank-info, aantal en subtotaal
function haalWinkelmandjeItems(PDO $db): array
{
    // Geef een lege array terug als het mandje leeg is
    if (empty($_SESSION['winkelmandje'])) {
        return [];
    }

    $winkelmandjeLijst = [];

    // Loop door alle items in de sessie en zoek de bijbehorende drank-info op
    foreach ($_SESSION['winkelmandje'] as $drankId => $aantal) {
        $drank = haalDrankOpId($db, (int) $drankId);
        if ($drank) {
            // Voeg drank-info, aantal en berekend subtotaal samen in één item
            $winkelmandjeLijst[] = [
                'drank'    => $drank,
                'aantal'   => (int) $aantal,
                'subtotaal' => round((float) $drank['prijs'] * $aantal, 2),
            ];
        }
    }

    return $winkelmandjeLijst;
}

// Berekent de totaalprijs van alle items in het winkelmandje
function berekenTotaalprijs(PDO $db): float
{
    $totaalprijs = 0.0;

    // Tel de subtotalen van alle items op
    foreach (haalWinkelmandjeItems($db) as $item) {
        $totaalprijs += $item['subtotaal'];
    }

    // Rond af op 2 decimalen om afrondingsfouten te voorkomen
    return round($totaalprijs, 2);
}

// Geeft het totale aantal artikelen in het winkelmandje (som van alle aantallen)
function haalAantalWinkelmandjeArtikelen(): int
{
    if (empty($_SESSION['winkelmandje'])) {
        return 0;
    }
    // array_sum telt alle waarden (aantallen) in de sessie-array op
    return (int) array_sum($_SESSION['winkelmandje']);
}

// Sla de bestelling op in de database via een transactie
// Als één stap mislukt, worden alle wijzigingen teruggedraaid (rollback)
function slaBestellingOp(PDO $db): bool
{
    // Verlaat de functie als het winkelmandje leeg is
    if (empty($_SESSION['winkelmandje'])) {
        return false;
    }

    $totaalprijs = berekenTotaalprijs($db);

    try {
        // Begin een transactie zodat alle inserts samen slagen of samen mislukken
        $db->beginTransaction();

        // Sla de hoofdbestelling op met datum en totaalprijs
        $bestellingStatement = $db->prepare(
            "INSERT INTO bestellingen (datum, totaalprijs) VALUES (NOW(), :totaalprijs)"
        );
        $bestellingStatement->execute([':totaalprijs' => $totaalprijs]);

        // Sla het ID van de zojuist ingevoegde bestelling op
        $bestellingId = (int) $db->lastInsertId();

        // Voorbereide query voor elk afzonderlijk product in de bestelling
        $itemStatement = $db->prepare(
            "INSERT INTO bestelling_items (bestelling_id, drank_id, aantal)
             VALUES (:bestelling_id, :drank_id, :aantal)"
        );

        // Sla elk product op als losse regel gekoppeld aan de bestelling
        foreach ($_SESSION['winkelmandje'] as $drankId => $aantal) {
            $itemStatement->execute([
                ':bestelling_id' => $bestellingId,
                ':drank_id'      => (int) $drankId,
                ':aantal'        => (int) $aantal,
            ]);
        }

        // Maak alle wijzigingen definitief
        $db->commit();

        // Leeg het winkelmandje na een succesvolle bestelling
        $_SESSION['winkelmandje'] = [];
        // Stel de succes-vlag in zodat de successpagina weet dat er een bestelling is
        $_SESSION['bestellingSucces'] = true;

        return true;
    } catch (PDOException $fout) {
        // Draai alle databasewijzigingen terug als er iets fout gaat
        $db->rollBack();
        return false;
    }
}

// Formatteer een getal als prijs met euroteken en Nederlandse notatie (bijv. € 1,50)
function formateerPrijs(float $prijs): string
{
    // number_format(waarde, decimalen, decimaalteken, duizendtal-scheidingsteken)
    return '€&nbsp;' . number_format($prijs, 2, ',', '.');
}

// Maak gebruikersinput veilig: verwijder spaties, tags en escapt HTML-tekens
function zuiverString(string $invoer): string
{
    // strip_tags verwijdert HTML/PHP-tags, htmlspecialchars escapet resterende speciale tekens
    return htmlspecialchars(strip_tags(trim($invoer)), ENT_QUOTES, 'UTF-8');
}
