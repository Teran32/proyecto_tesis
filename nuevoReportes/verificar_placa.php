<?php
// Desactivar la visualización de errores HTML para que no rompan el JSON
error_reporting(0); 
header('Content-Type: application/json');

include '../gestionDatos/conexion.php';

try {
    $id_placa = $_GET['id_placa'] ?? null;

    if (!$id_placa) {
        echo json_encode(['existe' => false, 'error' => 'No id']);
        exit;
    }

    // Usamos el WHERE que mencionaste: placa y estado en proceso (0)
    $stmt = $pdo->prepare("SELECT id FROM reportes WHERE id_placa = ? AND estado = 0 LIMIT 1");
    $stmt->execute([$id_placa]);
    $resultado = $stmt->fetch();

    echo json_encode(['existe' => (bool)$resultado]);

} catch (Exception $e) {
    // Si hay un error de base de datos, enviamos el error como JSON, no como HTML
    echo json_encode(['existe' => false, 'error' => $e->getMessage()]);
}