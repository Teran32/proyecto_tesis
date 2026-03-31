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

// 3. Consultar los reportes (El Historial) con todos los campos que pediste
$sqlHistorial = "SELECT r.*, t.tipo_trabajo, c.chofer 
                 FROM reportes r
                 LEFT JOIN tipo_trabajo t ON r.id_tipo_trabajo = t.id_tipo_trabajo
                 LEFT JOIN choferes c ON r.id_chofer = c.id_chofer
                 WHERE r.id_placa = ? 
                 ORDER BY r.fecha_entrada DESC";
$stmtH = $pdo->prepare($sqlHistorial);
$stmtH->execute([$id_placa]);
$reportes = $stmtH->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Historial - <?= $vehiculo['placas'] ?></title>
    <link rel="stylesheet" href="control_vehicular.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Estilos específicos para el historial */
        .contenedor-historial {
            width: 100%;
            max-width: 900px;
            margin-top: 80px;
        }

        .tarjeta-reporte {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            margin-bottom: 20px;
            color: white;
            overflow: hidden;
        }

        .header-reporte {
            padding: 15px 20px;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }

        .cuerpo-reporte {
            padding: 20px;
            display: none;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .grid-detalles {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .dato {
            margin-bottom: 10px;
            font-size: 0.9rem;
        }

        .dato label {
            color: #60a5fa;
            font-weight: bold;
            display: block;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .operativo {
            background: #10b981;
        }

        .no-operativo {
            background: #ef4444;
        }
    </style>
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
                            <div class="dato"><label>Fecha Salida</label><?= $r['fecha_salida'] ?: 'Pendiente' ?></div>
                        </div>
                    </div>
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