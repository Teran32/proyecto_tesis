<?php
include '../gestionDatos/conexion.php';
$id_marca = $_GET['id'];

$sql = "SELECT p.id_placas, p.placas, mo.modelos 
        FROM placas p 
        JOIN modelos mo ON p.id_modelos = mo.id_modelos 
        WHERE mo.id_marcas = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_marca]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));