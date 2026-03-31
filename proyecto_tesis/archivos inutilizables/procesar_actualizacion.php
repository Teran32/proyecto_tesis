<?php
include '../gestionDatos/conexion.php';

$id = $_POST['id'];
$estado = $_POST['estado'];
$trabajo = $_POST['trabajo_realizado'];
$repuestos = $_POST['repuestos'];
$pedidos = $_POST['pedidos'];
$km = $_POST['km_actual'];

$sql = "UPDATE reportes SET 
        trabajo_realizado = ?, 
        repuestos = ?, 
        pedidos = ?, 
        km_actual = ?,
        estado = ? 
        WHERE id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$trabajo, $repuestos, $pedidos, $km, $estado, $id]);

echo json_encode(['success' => true]);
?>
