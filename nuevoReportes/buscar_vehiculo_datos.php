<?php
include '../gestionDatos/conexion.php';

$id_placa = $_GET['id_placa'];

// JOIN entre placas, modelos y marcas
$sql = "SELECT ma.marcas, mo.modelos 
        FROM placas p
        JOIN modelos mo ON p.id_modelos = mo.id_modelos
        JOIN marcas ma ON mo.id_marcas = ma.id_marcas
        WHERE p.id_placas = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id_placa]);
$datos = $stmt->fetch(PDO::FETCH_ASSOC);

if ($datos) {
    echo json_encode([
        'success' => true,
        'marca' => $datos['marcas'],
        'modelo' => $datos['modelos']
    ]);
} else {
    echo json_encode(['success' => false]);
}