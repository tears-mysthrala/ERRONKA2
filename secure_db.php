<?php
// CONFIGURACIÓN DE LA BBDD (XAMPP)
$DB_HOST = "localhost";
$DB_USER = "root";     // Cambia si tienes otro usuario
$DB_PASS = "";         // Cambia si tienes contraseña
$DB_NAME = "guenaga_farma";

try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Error al conectar a MySQL: " . $e->getMessage());
}
?>
