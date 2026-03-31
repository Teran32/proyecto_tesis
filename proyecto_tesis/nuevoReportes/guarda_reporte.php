<?php
include '../gestionDatos/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $boton_presionado = $_POST['accion'];
    if ($boton_presionado === 'finalizado') {
        $estado = 1;
        $fecha_salida = $_POST['fecha_salida'];
    } else {
        $estado = 0;
        $fecha_salida = !empty($_POST['fecha_salida']) ? $_POST['fecha_salida'] : null;
    }


    $id_placa = $_POST['id_placa'];
    $id_chofer = $_POST['id_chofer'];
    $id_tipo_trabajo = $_POST['id_tipo_trabajo'];
    $fecha_entrada = $_POST['fecha_entrada'];
    $km_actual = $_POST['km_actual'];
    $km_prox = $_POST['km_prox'];
    $falla_detectada = $_POST['falla_detectada'];
    $trabajo_realizado = $_POST['trabajo_realizado'];
    $repuestos = $_POST['repuestos'];
    $observacion = $_POST['observacion'];
    $pedidos = isset($_POST['pedidos']) ? $_POST['pedidos'] : null;
    $correlativo = isset($_POST['correlativo']) ? $_POST['correlativo'] : null;

    try {
        $sql = "INSERT INTO reportes (
                    id_placa, id_chofer, id_tipo_trabajo, fecha_entrada, 
                    fecha_salida, km_actual, km_prox, falla_detectada, 
                    trabajo_realizado, repuestos, observacion, pedidos, estado, correlativo
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);

        $resultado = $stmt->execute([
            $id_placa,
            $id_chofer,
            $id_tipo_trabajo,
            $fecha_entrada,
            $fecha_salida,
            $km_actual,
            $km_prox,
            $falla_detectada,
            $trabajo_realizado,
            $repuestos,
            $observacion,
            $pedidos,
            $estado,
            $correlativo
        ]);

        if ($resultado) {
            header("Location: nuevo_reporte.php?status=success", true, 303);
            exit();
        }

    } catch (PDOException $e) {
        die("Error crítico al guardar el reporte: " . $e->getMessage());
    }
}
?>