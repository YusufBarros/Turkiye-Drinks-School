<?php
function db()
{
    $conn = new mysqli("localhost", "root", "", "turkiye_drinks");
    if ($conn->connect_error) {
        die("DB error");
    }
    return $conn;
}
?>