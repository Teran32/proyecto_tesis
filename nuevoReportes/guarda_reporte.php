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


    $sql_check = "SELECT COUNT(*) FROM reportes WHERE id = ? AND estado = 0";
$stmt_check = $pdo->prepare($sql_check);
$stmt_check->execute([$id_placa]);
$conteo = $stmt_check->fetchColumn();

if ($conteo > 0) {
    header("Location: nuevo_reporte.php?status=error_duplicado");
    exit();
}


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
            // Obtener el id del reporte insertado
            $id_reporte = $pdo->lastInsertId();

            // Procesar imágenes si existen
            if (isset($_FILES['fotos']) && count($_FILES['fotos']['name']) > 0) {
                $total = count($_FILES['fotos']['name']);
                for ($i = 0; $i < $total; $i++) {
                    if ($_FILES['fotos']['error'][$i] === UPLOAD_ERR_OK) {
                        $tmp_name = $_FILES['fotos']['tmp_name'][$i];
                        $nombre_original = basename($_FILES['fotos']['name'][$i]);
                        $extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));
                        $nombre_archivo = uniqid('img_') . '.' . $extension;
                        $ruta_destino = 'uploads/' . $nombre_archivo;
                        $ruta_completa = __DIR__ . '/' . $ruta_destino;

                        // Comprimir/redimensionar si es imagen
                        if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                            $calidad = 70; // calidad para compresión
                            if ($extension === 'jpg' || $extension === 'jpeg') {
                                $img = imagecreatefromjpeg($tmp_name);
                                imagejpeg($img, $ruta_completa, $calidad);
                                imagedestroy($img);
                            } elseif ($extension === 'png') {
                                $img = imagecreatefrompng($tmp_name);
                                imagepng($img, $ruta_completa, 7); // compresión png
                                imagedestroy($img);
                            }
                        } else {
                            move_uploaded_file($tmp_name, $ruta_completa);
                        }

                        // Guardar ruta en la base de datos
                        $sql_img = "INSERT INTO imagenes_reporte (id_reporte, ruta_imagen) VALUES (?, ?)";
                        $stmt_img = $pdo->prepare($sql_img);
                        $stmt_img->execute([$id_reporte, $ruta_destino]);
                    }
                }
            }

            header("Location: nuevo_reporte.php?status=success", true, 303);
            exit();
        }

    } catch (PDOException $e) {
        die("Error crítico al guardar el reporte: " . $e->getMessage());
    }
}
?>