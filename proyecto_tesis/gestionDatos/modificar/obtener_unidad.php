<?php
include '../conexion.php';
header('Content-Type: application/json');

$id = $_GET['id'] ?? null;
if ($id) {
    try {
        $stmt = $pdo->prepare("
            SELECT p.id_placas, p.placas, p.id_modelos, mo.id_marcas, mo.modelos, ma.marcas 
            FROM placas p 
            INNER JOIN modelos mo ON p.id_modelos = mo.id_modelos 
            INNER JOIN marcas ma ON mo.id_marcas = ma.id_marcas
            WHERE p.id_placas = ?
        ");
        $stmt->execute([$id]);
        $unidad = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($unidad);
    } catch(Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
