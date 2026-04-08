<?php
include '../gestionDatos/conexion.php';
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    // Agregamos ma.id_marcas y mo.id_modelos a la consulta
    $sql = "SELECT r.*, p.placas, 
                   ma.id_marcas, ma.marcas, 
                   mo.id_modelos, mo.modelos 
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