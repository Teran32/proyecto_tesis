<?php
// conexion.php
$host     = "db.quojudgnpxpndmyrhjcy.supabase.co";
$port     = "5432";
$dbname   = "postgres";
$user     = "postgres";
$password = "Amorfoda32..";

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    die("Error al conectar: " . $e->getMessage());
}
?>