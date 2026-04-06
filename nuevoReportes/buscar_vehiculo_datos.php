<?php
include '../gestionDatos/conexion.php';
header('Content-Type: application/json');

$tipo = $_GET['tipo'] ?? 'placa';
$valor = $_GET['valor'] ?? '';

try {
    // La consulta es la misma, solo cambia el WHERE dinámicamente
    $columna = ($tipo == 'placa') ? 'p.id_placas' : (($tipo == 'marca') ? 'ma.id_marcas' : 'mo.id_modelos');
    
    $sql = "SELECT p.id_placas, ma.id_marcas, mo.id_modelos, ma.marcas, mo.modelos 
            FROM placas p
            JOIN modelos mo ON p.id_modelos = mo.id_modelos
            JOIN marcas ma ON mo.id_marcas = ma.id_marcas
            WHERE $columna = ? LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$valor]);
    $v = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($v) {
        echo json_encode([
            'success' => true,
            'id_placa' => $v['id_placas'],
            'id_marca' => $v['id_marcas'],
            'id_modelo' => $v['id_modelos'],
            'nombre_marca' => $v['marcas'],
            'nombre_modelo' => $v['modelos']
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}