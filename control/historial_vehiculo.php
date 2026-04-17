<?php
include '../gestionDatos/conexion.php';

// 1. Obtener el ID de la placa desde la URL
$id_placa = isset($_GET['placa']) ? $_GET['placa'] : null;

if (!$id_placa) {
    die("Error: No se especificó una unidad.");
}

// 2. Consultar datos del vehículo
$sqlVehiculo = "SELECT p.placas, mo.modelos, ma.marcas 
                FROM placas p 
                JOIN modelos mo ON p.id_modelos = mo.id_modelos 
                JOIN marcas ma ON mo.id_marcas = ma.id_marcas 
                WHERE p.id_placas = ?";
$stmtV = $pdo->prepare($sqlVehiculo);
$stmtV->execute([$id_placa]);
$vehiculo = $stmtV->fetch(PDO::FETCH_ASSOC);

// 3. Historial: todos los finalizados + el "en proceso" solo si es el más reciente
$sqlHistorial = "SELECT r.*, t.tipo_trabajo, c.chofer 
                 FROM reportes r
                 LEFT JOIN tipo_trabajo t ON r.id_tipo_trabajo = t.id_tipo_trabajo
                 LEFT JOIN choferes c ON r.id_chofer = c.id_chofer
                 WHERE r.id_placa = ?
                   AND (
                       r.estado = 1
                       OR (
                           r.estado = 0
                           AND r.id = (
                               SELECT id FROM reportes
                               WHERE id_placa = ?
                               ORDER BY fecha_entrada DESC
                               LIMIT 1
                           )
                       )
                   )
                 ORDER BY r.fecha_entrada DESC";
$stmtH = $pdo->prepare($sqlHistorial);
$stmtH->execute([$id_placa, $id_placa]);
$reportes = $stmtH->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Historial - <?= $vehiculo['placas'] ?></title>
    <link rel="stylesheet" href="control_vehicular.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">    
</head>

<body>

    <a href="control_vehicular.php" class="boton-volver"><i class="fas fa-arrow-left"></i> Volver</a>

    <div class="contenedor-historial">
        <header class="cabecera">
            <h1><?= $vehiculo['marcas'] ?> <?= $vehiculo['modelos'] ?></h1>
            <p>Placa: <span style="color: #60a5fa; font-weight: bold;"><?= $vehiculo['placas'] ?></span></p>
        </header>

        <?php if (empty($reportes)): ?>
            <p style="text-align:center; color:white;">No hay reportes registrados para esta unidad.</p>
        <?php endif; ?>

        <?php foreach ($reportes as $r): ?>
            <div class="tarjeta-reporte">
                <div class="header-reporte" onclick="toggleDetalle(this)">
                    <div>
                        <strong>#<?= $r['correlativo'] ?></strong> -
                        <?= date('d/m/Y', strtotime($r['fecha_entrada'])) ?>
                    </div>
                    <div>
                        <span class="badge <?= ($r['estado'] == 1) ? 'operativo' : 'no-operativo' ?>">
                            <?= ($r['estado'] == 1) ? 'OPERATIVO' : 'EN TALLER' ?>
                        </span>
                        <i class="fas fa-chevron-down" style="margin-left:10px;"></i>
                    </div>
                </div>

                <div class="cuerpo-reporte">
                    <div class="grid-detalles">
                        <div>
                            <div class="dato"><label>Tipo de Mantenimiento</label><?= $r['tipo_trabajo'] ?></div>
                            <div class="dato"><label>Falla Detectada</label><?= $r['falla_detectada'] ?></div>
                            <div class="dato"><label>Trabajo Realizado</label><?= $r['trabajo_realizado'] ?></div>
                            <div class="dato"><label>Mecánico Participante</label><?= $r['mecanico'] ?? 'No asignado' ?>
                            </div>
                        </div>
                        <div>
                            <div class="dato"><label>Kilometraje Entrada</label><?= number_format($r['km_actual']) ?> Km
                            </div>
                            <div class="dato"><label>Kilometraje
                                    Próximo</label><?= $r['km_prox'] ? number_format($r['km_prox']) . ' Km' : 'N/A' ?></div>
                            <div class="dato"><label>Repuestos/Insumos</label><?= $r['repuestos'] ?></div>
                            <div class="dato"><label>Ponderación</label><?= $r['ponderacion'] ?? 'N/A' ?></div>
                        </div>
                        <div>
                            <div class="dato"><label>Solicitud de Repuestos</label><?= $r['pedidos'] ?: 'Ninguna' ?></div>
                            <div class="dato"><label>Observaciones</label><?= $r['observacion'] ?></div>
                            <div class="dato"><label>Chofer</label><?= $r['chofer'] ?></div>
                            <div class="dato"><label>Fecha Entrada</label><?= $r['fecha_entrada'] ?: 'Pendiente' ?></div>
                            <div class="dato"><label>Fecha Salida</label><?= $r['fecha_salida'] ?: 'Pendiente' ?></div>
                        </div>
                    </div>
                    <a href="../finalizados/generar_pdf.php?id=<?= $r['id'] ?>" target="_blank" class="boton-pdf">
                                        <i class="fas fa-file-pdf"></i> Imprimir
                                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function toggleDetalle(elemento) {
            const cuerpo = elemento.nextElementSibling;
            const icono = elemento.querySelector('.fa-chevron-down');

            if (cuerpo.style.display === "block") {
                cuerpo.style.display = "none";
                icono.style.transform = "rotate(0deg)";
            } else {
                cuerpo.style.display = "block";
                icono.style.transform = "rotate(180deg)";
            }
        }
    </script>
</body>

</html>