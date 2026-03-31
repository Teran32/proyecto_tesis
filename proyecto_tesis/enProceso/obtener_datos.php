<?php
include '../gestionDatos/conexion.php';
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $sql = "SELECT r.*, p.placas, ma.marcas, mo.modelos 
            FROM reportes r
            JOIN placas p ON r.id_placa = p.id_placas
            JOIN modelos mo ON p.id_modelos = mo.id_modelos
            JOIN marcas ma ON mo.id_marcas = ma.id_marcas
            WHERE r.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_GET['id']]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode($resultado);
}
?>