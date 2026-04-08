<?php
error_reporting(0); 
header('Content-Type: application/json');

include '../gestionDatos/conexion.php';

try {
    $id_placa = $_GET['id_placa'] ?? null;

    if (!$id_placa) {
        echo json_encode(['existe' => false, 'error' => 'No id']);
        exit;
    }


    $stmt = $pdo->prepare("SELECT id FROM reportes WHERE id_placa = ? AND estado = 0 LIMIT 1");
    $stmt->execute([$id_placa]);
    $resultado = $stmt->fetch();

    echo json_encode(['existe' => (bool)$resultado]);

} catch (Exception $e) {
    echo json_encode(['existe' => false, 'error' => $e->getMessage()]);
}

//codigo para verificar si la placa que selecinoe tiene un reporte abierto esto no deja que realice otro reporte
