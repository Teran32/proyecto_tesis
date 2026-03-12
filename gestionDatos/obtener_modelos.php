<?php
include 'conexion.php';
$id_marca = $_GET['id_marca'];

$stmt = $pdo->prepare("SELECT id_modelos, modelos FROM modelos WHERE id_marcas = ? ORDER BY modelos ASC");
$stmt->execute([$id_marca]);
$modelos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo '<option value="">Seleccionar Modelo...</option>';
foreach ($modelos as $m) {
    echo "<option value='{$m['id_modelos']}'>{$m['modelos']}</option>";
}
?>
