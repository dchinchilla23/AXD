<?php
$db_host = __DIR__ . "/../db/hotel.sqlite";

try {
// conexion a PDO a sql
    $pdo = new PDO("sqlite:{$db_host}");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: a la bd revisar por favor  " . $e->getMessage());
}
?>