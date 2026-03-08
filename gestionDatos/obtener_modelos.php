<?php
include 'conexion.php';
$marca_id = $_GET['marca_id'] ?? null;

if ($marca_id) {
    $stmt = $pdo->prepare("SELECT id, nombre FROM modelos WHERE marca_id = ? ORDER BY nombre ASC");
    $stmt->execute([$marca_id]);
    $modelos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo '<option value="">Seleccionar Modelo...</option>';
    foreach ($modelos as $m) {
        echo "<option value='{$m['id']}'>" . htmlspecialchars($m['nombre']) . "</option>";
    }
}
?>