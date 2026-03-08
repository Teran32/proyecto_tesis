<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $placa = strtoupper(trim($_POST['placa']));
    $modelo_id = $_POST['modelo_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO vehiculos (placa, modelo_id) VALUES (?, ?)");
        $stmt->execute([$placa, $modelo_id]);
        header("Location: GestionDatos.php?status=success");
    } catch (Exception $e) {
        header("Location: GestionDatos.php?status=error");
    }
}
?>