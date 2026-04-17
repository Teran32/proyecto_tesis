<?php
include '../gestionDatos/conexion.php';

$id_chofer = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id_chofer) {
    die("Error: No se especificó un chofer.");
}

// Datos del chofer
$stmtC = $pdo->prepare("SELECT * FROM choferes WHERE id_chofer = ?");
$stmtC->execute([$id_chofer]);
$chofer = $stmtC->fetch(PDO::FETCH_ASSOC);

if (!$chofer) {
    die("Chofer no encontrado.");
}

// Vehículos distintos que ha utilizado el chofer
$sqlVehiculos = "
    SELECT DISTINCT 
        p.id_placas, p.placas, mo.modelos, ma.marcas,
        COUNT(r.id) AS veces_usado,
        MAX(r.fecha_entrada) AS ultima_vez
    FROM reportes r
    JOIN placas p ON r.id_placa = p.id_placas
    JOIN modelos mo ON p.id_modelos = mo.id_modelos
    JOIN marcas ma ON mo.id_marcas = ma.id_marcas
    WHERE r.id_chofer = ?
    GROUP BY p.id_placas, p.placas, mo.modelos, ma.marcas
    ORDER BY ultima_vez DESC
";
$stmtV = $pdo->prepare($sqlVehiculos);
$stmtV->execute([$id_chofer]);
$vehiculos = $stmtV->fetchAll(PDO::FETCH_ASSOC);

// Filtro por placa seleccionada
$placa_filtro = isset($_GET['placa']) ? (int)$_GET['placa'] : 0;

// Historial: todos los finalizados + el "en proceso" solo si es el más reciente de cada vehículo
// La subconsulta obtiene el ID más reciente por cada vehículo usado por este chofer
$sqlHistorial = "
    SELECT r.*, t.tipo_trabajo, p.placas, mo.modelos, ma.marcas
    FROM reportes r
    LEFT JOIN tipo_trabajo t ON r.id_tipo_trabajo = t.id_tipo_trabajo
    LEFT JOIN placas p ON r.id_placa = p.id_placas
    LEFT JOIN modelos mo ON p.id_modelos = mo.id_modelos
    LEFT JOIN marcas ma ON mo.id_marcas = ma.id_marcas
    WHERE r.id_chofer = ?
      AND (
          r.estado = 1
          OR (
              r.estado = 0
              AND r.id = (
                  SELECT id FROM reportes
                  WHERE id_placa = r.id_placa
                  ORDER BY fecha_entrada DESC
                  LIMIT 1
              )
          )
      )
";
$params = [$id_chofer];
if ($placa_filtro) {
    $sqlHistorial .= " AND r.id_placa = ?";
    $params[] = $placa_filtro;
}
$sqlHistorial .= " ORDER BY r.fecha_entrada DESC";

$stmtH = $pdo->prepare($sqlHistorial);
$stmtH->execute($params);
$reportes = $stmtH->fetchAll(PDO::FETCH_ASSOC);

// Stats rápidas
$total = count($reportes);
$finalizados = count(array_filter($reportes, fn($r) => $r['estado'] == 1));
$en_taller = $total - $finalizados;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de <?= htmlspecialchars($chofer['chofer']) ?> - Sertransfal</title>
    <link rel="stylesheet" href="control_chofer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <a href="control_chofer.php" class="boton-volver">
        <i class="fas fa-arrow-left"></i> Choferes
    </a>

    <div class="contenedor-historial">

        <!-- Cabecera del chofer -->
        <header class="perfil-chofer">
            <div class="avatar-grande">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="perfil-info">
                <h1><?= htmlspecialchars($chofer['chofer']) ?></h1>
                <div class="perfil-stats">
                    <div class="pstat">
                        <span class="pstat-num"><?= count($vehiculos) ?></span>
                        <span class="pstat-label">Vehículos usados</span>
                    </div>
                    <div class="pstat">
                        <span class="pstat-num"><?= $total ?></span>
                        <span class="pstat-label">Reportes</span>
                    </div>
                    <div class="pstat">
                        <span class="pstat-num"><?= $en_taller ?></span>
                        <span class="pstat-label">En taller</span>
                    </div>
                    <div class="pstat">
                        <span class="pstat-num"><?= $finalizados ?></span>
                        <span class="pstat-label">Finalizados</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Vehículos que ha utilizado -->
        <section class="seccion-vehiculos">
            <h2 class="seccion-titulo"><i class="fas fa-car"></i> Vehículos Utilizados</h2>
            <div class="vehiculos-scroll">
                <a href="historial_chofer.php?id=<?= $id_chofer ?>" 
                   class="chip-vehiculo <?= !$placa_filtro ? 'activo' : '' ?>">
                    <i class="fas fa-list"></i> Todos
                </a>
                <?php foreach ($vehiculos as $v): ?>
                    <a href="historial_chofer.php?id=<?= $id_chofer ?>&placa=<?= $v['id_placas'] ?>"
                       class="chip-vehiculo <?= $placa_filtro == $v['id_placas'] ? 'activo' : '' ?>">
                        <i class="fas fa-car"></i>
                        <?= htmlspecialchars($v['placas']) ?>
                        <span class="chip-veces"><?= $v['veces_usado'] ?>x</span>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Si filtró por placa, mostrar info de ese vehiculo -->
        <?php if ($placa_filtro && !empty($vehiculos)):
            $vf = array_values(array_filter($vehiculos, fn($v) => $v['id_placas'] == $placa_filtro));
            if (!empty($vf)): $vf = $vf[0]; ?>
            <div class="info-vehiculo-filtrado">
                <i class="fas fa-info-circle"></i>
                Mostrando historial del vehículo: <strong><?= htmlspecialchars($vf['marcas'] . ' ' . $vf['modelos']) ?></strong> 
                — Placa <strong><?= htmlspecialchars($vf['placas']) ?></strong>
                — Ingresó <strong><?= $vf['veces_usado'] ?> vez<?= $vf['veces_usado'] != 1 ? 'es' : '' ?></strong>
            </div>
        <?php endif; endif; ?>

        <!-- Historial de reportes -->
        <section class="seccion-reportes">
            <h2 class="seccion-titulo">
                <i class="fas fa-clipboard-list"></i> 
                Historial de Reportes
                <span class="badge-count"><?= $total ?></span>
            </h2>

            <?php if (empty($reportes)): ?>
                <div class="sin-reportes">
                    <i class="fas fa-inbox"></i>
                    <p>No hay reportes para mostrar.</p>
                </div>
            <?php endif; ?>

            <?php foreach ($reportes as $r): ?>
                <div class="tarjeta-reporte">
                    <div class="header-reporte" onclick="toggleDetalle(this)">
                        <div class="header-izq">
                            <span class="correlativo">#<?= htmlspecialchars($r['correlativo']) ?></span>
                            <div class="header-meta">
                                <span><i class="fas fa-car"></i> <?= htmlspecialchars($r['marcas'] . ' ' . $r['modelos']) ?> — <strong><?= htmlspecialchars($r['placas']) ?></strong></span>
                                <span><i class="fas fa-calendar"></i> <?= date('d/m/Y', strtotime($r['fecha_entrada'])) ?></span>
                            </div>
                        </div>
                        <div class="header-der">
                            <span class="badge <?= $r['estado'] == 1 ? 'badge-ok' : 'badge-taller' ?>">
                                <?= $r['estado'] == 1 ? '<i class="fas fa-check-circle"></i> FINALIZADO' : '<i class="fas fa-tools"></i> EN TALLER' ?>
                            </span>
                            <i class="fas fa-chevron-down icono-toggle"></i>
                        </div>
                    </div>

                    <div class="cuerpo-reporte">
                        <div class="grid-detalles">
                            <div>
                                <div class="dato">
                                    <label>Tipo de Trabajo</label>
                                    <?= htmlspecialchars($r['tipo_trabajo'] ?? '—') ?>
                                </div>
                                <div class="dato">
                                    <label>Falla Detectada</label>
                                    <?= htmlspecialchars($r['falla_detectada'] ?? '—') ?>
                                </div>
                                <div class="dato">
                                    <label>Trabajo Realizado</label>
                                    <?= htmlspecialchars($r['trabajo_realizado'] ?? '—') ?>
                                </div>
                            </div>
                            <div>
                                <div class="dato">
                                    <label>Kilometraje Entrada</label>
                                    <?= $r['km_actual'] ? number_format($r['km_actual']) . ' Km' : '—' ?>
                                </div>
                                <div class="dato">
                                    <label>Próximo Kilometraje</label>
                                    <?= $r['km_prox'] ? number_format($r['km_prox']) . ' Km' : '—' ?>
                                </div>
                                <div class="dato">
                                    <label>Repuestos / Insumos</label>
                                    <?= htmlspecialchars($r['repuestos'] ?: 'Ninguno') ?>
                                </div>
                            </div>
                            <div>
                                <div class="dato">
                                    <label>Fecha Entrada</label>
                                    <?= $r['fecha_entrada'] ? date('d/m/Y H:i', strtotime($r['fecha_entrada'])) : '—' ?>
                                </div>
                                <div class="dato">
                                    <label>Fecha Salida</label>
                                    <?= $r['fecha_salida'] ? date('d/m/Y H:i', strtotime($r['fecha_salida'])) : 'Pendiente' ?>
                                </div>
                                <div class="dato">
                                    <label>Observaciones</label>
                                    <?= htmlspecialchars($r['observacion'] ?: '—') ?>
                                </div>
                                <div class="dato">
                                    <label>Solicitud de Repuestos</label>
                                    <?= htmlspecialchars($r['pedidos'] ?: 'Ninguna') ?>
                                </div>
                            </div>
                        </div>

                        <div class="acciones-reporte">
                            <a href="../finalizados/generar_pdf.php?id=<?= $r['id'] ?>" target="_blank" class="btn-pdf">
                                <i class="fas fa-file-pdf"></i> Imprimir PDF
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    </div>

    <script>
        function toggleDetalle(header) {
            const cuerpo = header.nextElementSibling;
            const icono = header.querySelector('.icono-toggle');
            const abierto = cuerpo.classList.toggle('visible');
            icono.style.transform = abierto ? 'rotate(180deg)' : 'rotate(0deg)';
        }
    </script>
</body>

</html>
