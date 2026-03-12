<?php
session_start();
// Generar un ID único para la alerta si no existe aún
if (!isset($_SESSION['alerta_id'])) {
    $_SESSION['alerta_id'] = bin2hex(random_bytes(8));
}
// CONEXION A LA BASE DE DATOS CON PHPPPPP
$host     = "localhost";
$dbname   = "registros_reportes"; 
$user     = "root";
$password = "";

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Error al conectar localmente: " . $e->getMessage());
}
?>