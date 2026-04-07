<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Haal alle dranken op, optioneel gefilterd op zoekterm, prik, alcohol of regio
function haalAlleDrankenOp(PDO $db, array $filters = []): array
{
    $sql = "SELECT * FROM dranken WHERE 1=1";
    $parameters = [];

    if (!empty($filters['zoekterm'])) {
        $sql .= " AND naam LIKE :zoekterm";
        $parameters[':zoekterm'] = '%' . $filters['zoekterm'] . '%';
    }

    if (isset($filters['met_prik']) && $filters['met_prik'] !== '') {
        $sql .= " AND met_prik = :met_prik";
        $parameters[':met_prik'] = (int) $filters['met_prik'];
    }

    if (isset($filters['alcohol']) && $filters['alcohol'] !== '') {
        $sql .= " AND alcohol = :alcohol";
        $parameters[':alcohol'] = (int) $filters['alcohol'];
    }

    if (!empty($filters['regio'])) {
        $sql .= " AND regio = :regio";
        $parameters[':regio'] = $filters['regio'];
    }

    $sql .= " ORDER BY naam ASC";

    try {
        $statement = $db->prepare($sql);
        $statement->execute($parameters);
        return $statement->fetchAll();
    } catch (PDOException $fout) {
        return [];
    }
}

// Haal één drank op via ID, geeft null terug als die niet bestaat
function haalDrankOpId(PDO $db, int $drankId): ?array
{
    try {
        $statement = $db->prepare("SELECT * FROM dranken WHERE id = :id LIMIT 1");
        $statement->execute([':id' => $drankId]);
        $drank = $statement->fetch();
        return $drank ?: null;
    } catch (PDOException $fout) {
        return null;
    }
}

// Haal alle unieke regio's op voor het filter-dropdown
function haalAlleRegiosOp(PDO $db): array
{
    try {
        $statement = $db->prepare(
            "SELECT DISTINCT regio FROM dranken
             WHERE regio IS NOT NULL
             ORDER BY regio ASC"
        );
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $fout) {
        return [];
    }
}

// Voeg een drank toe aan het winkelmandje
function voegToeAanWinkelmandje(int $drankId, int $aantal = 1): void
{
    if (!isset($_SESSION['winkelmandje'])) {
        $_SESSION['winkelmandje'] = [];
    }

    $sleutel = (string) $drankId;

    // Als de drank er al in zit, tel het aantal op
    if (isset($_SESSION['winkelmandje'][$sleutel])) {
        $_SESSION['winkelmandje'][$sleutel] += $aantal;
    } else {
        $_SESSION['winkelmandje'][$sleutel] = $aantal;
    }
}

// Verwijder een drank volledig uit het winkelmandje
function verwijderUitWinkelmandje(int $drankId): void
{
    unset($_SESSION['winkelmandje'][(string) $drankId]);
}

// Pas het aantal aan, bij 0 of minder wordt de drank verwijderd
function wijzigAantalInWinkelmandje(int $drankId, int $nieuwAantal): void
{
    if ($nieuwAantal <= 0) {
        verwijderUitWinkelmandje($drankId);
    } else {
        $_SESSION['winkelmandje'][(string) $drankId] = $nieuwAantal;
    }
}

// Geeft alle winkelmandje-items terug met drank-info en subtotaal
function haalWinkelmandjeItems(PDO $db): array
{
    if (empty($_SESSION['winkelmandje'])) {
        return [];
    }

    $winkelmandjeLijst = [];

    foreach ($_SESSION['winkelmandje'] as $drankId => $aantal) {
        $drank = haalDrankOpId($db, (int) $drankId);
        if ($drank) {
            $winkelmandjeLijst[] = [
                'drank' => $drank,
                'aantal' => (int) $aantal,
                'subtotaal' => round((float) $drank['prijs'] * $aantal, 2),
            ];
        }
    }

    return $winkelmandjeLijst;
}

// Berekent de totaalprijs van het winkelmandje
function berekenTotaalprijs(PDO $db): float
{
    $totaalprijs = 0.0;

    foreach (haalWinkelmandjeItems($db) as $item) {
        $totaalprijs += $item['subtotaal'];
    }

    return round($totaalprijs, 2);
}

// Geeft het totale aantal artikelen in het winkelmandje
function haalAantalWinkelmandjeArtikelen(): int
{
    if (empty($_SESSION['winkelmandje'])) {
        return 0;
    }
    return (int) array_sum($_SESSION['winkelmandje']);
}

// Sla de bestelling op in de database via een transactie
// Als er iets fout gaat, wordt alles teruggedraaid
function slaBestellingOp(PDO $db): bool
{
    if (empty($_SESSION['winkelmandje'])) {
        return false;
    }

    $totaalprijs = berekenTotaalprijs($db);

    try {
        $db->beginTransaction();

        // Sla de hoofdbestelling op
        $bestellingStatement = $db->prepare(
            "INSERT INTO bestellingen (datum, totaalprijs) VALUES (NOW(), :totaalprijs)"
        );
        $bestellingStatement->execute([':totaalprijs' => $totaalprijs]);
        $bestellingId = (int) $db->lastInsertId();

        // Sla elk product op als losse regel
        $itemStatement = $db->prepare(
            "INSERT INTO bestelling_items (bestelling_id, drank_id, aantal)
             VALUES (:bestelling_id, :drank_id, :aantal)"
        );

        foreach ($_SESSION['winkelmandje'] as $drankId => $aantal) {
            $itemStatement->execute([
                ':bestelling_id' => $bestellingId,
                ':drank_id' => (int) $drankId,
                ':aantal' => (int) $aantal,
            ]);
        }

        $db->commit();

        // Winkelmandje leegmaken na succesvolle bestelling
        $_SESSION['winkelmandje'] = [];
        $_SESSION['bestellingSucces'] = true;

        return true;
    } catch (PDOException $fout) {
        $db->rollBack();
        return false;
    }
}

// Formatteer een getal als prijs, bv: € 1,50
function formateerPrijs(float $prijs): string
{
    return '€&nbsp;' . number_format($prijs, 2, ',', '.');
}

// Maak gebruikersinput veilig door tags te strippen en HTML te escapen
function zuiverString(string $invoer): string
{
    return htmlspecialchars(strip_tags(trim($invoer)), ENT_QUOTES, 'UTF-8');
}