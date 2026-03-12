<?php
include 'conexion.php';
header('Content-Type: application/json');

$tipo = $_POST['tipo'];
$nombre = trim($_POST['nombre']);
$id_padre = $_POST['id_marca_padre'] ?? null;

try {
    if ($tipo === 'marca') {
        $stmt = $pdo->prepare("INSERT INTO marcas (marcas) VALUES (?)");
        $stmt->execute([$nombre]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO modelos (modelos, id_marcas) VALUES (?, ?)");
        $stmt->execute([$nombre, $id_padre]);
    }
    
    echo json_encode([
        'success' => true, 
        'id_nuevo' => $pdo->lastInsertId()
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
