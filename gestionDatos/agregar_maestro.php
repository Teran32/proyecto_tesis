<?php
include 'conexion.php';

$tipo = $_GET['tipo'];
$nombre = $_GET['nombre'];
$marca_id = $_GET['marca_id'] ?? null;

if ($tipo == 'marca') {
    $stmt = $pdo->prepare("INSERT INTO marcas (nombre) VALUES (?)");
    $stmt->execute([$nombre]);
} elseif ($tipo == 'modelo' && $marca_id) {
    $stmt = $pdo->prepare("INSERT INTO modelos (nombre, marca_id) VALUES (?, ?)");
    $stmt->execute([$nombre, $marca_id]);
}

header("Location: GestionDatos.php");
?>