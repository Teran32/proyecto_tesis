<?php
include '../conexion.php';
header('Content-Type: application/json');

$id = $_GET['id'] ?? null;
if ($id) {
    try {
        $stmt = $pdo->prepare("SELECT id_chofer, chofer FROM choferes WHERE id_chofer = ?");
        $stmt->execute([$id]);
        $chofer = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($chofer);
    } catch(Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
