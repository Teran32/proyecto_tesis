<?php
include '../gestionDatos/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id_reporte'];
    $accion = $_POST['accion'];

    $estado = ($accion === 'finalizado') ? 1 : 0;

    $fecha_entrada = $_POST['fecha_entrada'];
    $fecha_salida = !empty($_POST['fecha_salida']) ? $_POST['fecha_salida'] : null;
    $id_placa = $_POST['id_placa'];
    $id_chofer = $_POST['id_chofer'];
    $id_tipo_trabajo = $_POST['id_tipo_trabajo'];
    $km_actual = empty($_POST['km_actual']) ? 0 : $_POST['km_actual'];
    $km_prox = empty($_POST['km_prox']) ? 0 : $_POST['km_prox'];
    $falla_detectada = $_POST['falla_detectada'];
    $trabajo_realizado = $_POST['trabajo_realizado'];
    $repuestos = $_POST['repuestos'];
    $observacion = $_POST['observacion'];
    $pedidos = $_POST['pedidos'];

    try {
        $sql = "UPDATE reportes SET 
                fecha_entrada = ?, 
                fecha_salida = ?, 
                id_placa = ?, 
                id_chofer = ?, 
                id_tipo_trabajo = ?, 
                km_actual = ?, 
                km_prox = ?, 
                falla_detectada = ?, 
                trabajo_realizado = ?, 
                repuestos = ?, 
                observacion = ?, 
                pedidos = ?, 
                estado = ? 
                WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $fecha_entrada, 
            $fecha_salida, 
            $id_placa, 
            $id_chofer, 
            $id_tipo_trabajo, 
            $km_actual, 
            $km_prox, 
            $falla_detectada, 
            $trabajo_realizado, 
            $repuestos, 
            $observacion, 
            $pedidos, 
            $estado, 
            $id
        ]);

        header("Location: en_proceso.php?status=success", true, 303);
        exit();

    } catch (PDOException $e) {
        die("Error de SQL al actualizar el reporte: " . $e->getMessage());
    }
} else {
    header("Location: en_proceso.php", true, 303);
    exit();
}
?>
