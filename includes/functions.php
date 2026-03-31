<?php
session_start();

/**
 * Product toevoegen aan winkelmand
 */
function addToCart($id)
{
    if (!isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = 1;
    } else {
        $_SESSION['cart'][$id]++;
    }
}

/**
 * Totaal berekenen
 */
function calculateTotal($conn)
{
    $total = 0;

    if (!isset($_SESSION['cart']))
        return 0;

    foreach ($_SESSION['cart'] as $id => $qty) {
        $stmt = $conn->prepare("SELECT prijs FROM dranken WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        $total += $result['prijs'] * $qty;
    }

    return $total;
}

/**
 * Filter query bouwen
 */
function buildFilterQuery($filters)
{
    $conditions = [];

    if (!empty($filters['prik'])) {
        $conditions[] = "prik=" . (int) $filters['prik'];
    }

    if (!empty($filters['alcohol'])) {
        $conditions[] = "alcohol=" . (int) $filters['alcohol'];
    }

    if (!empty($filters['regio'])) {
        $conditions[] = "regio='" . $filters['regio'] . "'";
    }

    return count($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
}
?>