<?php
// Database connectie
function getDB()
{
    try {
        $conn = new mysqli("localhost", "root", "", "turkiye_drinks");

        if ($conn->connect_error) {
            throw new Exception("DB connectie mislukt");
        }

        return $conn;

    } catch (Exception $e) {
        die("Fout: " . $e->getMessage());
    }
}
?>