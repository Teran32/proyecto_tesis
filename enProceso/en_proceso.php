<?php
include '../gestionDatos/conexion.php';
include '../nuevoReportes/correlativo_año.php';
$reportes = $pdo->query("SELECT r.id,r.correlativo, r.fecha_entrada, p.placas, c.chofer 
                        FROM reportes r
                        JOIN placas p ON r.id_placa = p.id_placas
                        JOIN choferes c ON r.id_chofer = c.id_chofer
                        WHERE r.estado = 0 
                        ORDER BY r.id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertransfal - Trabajos en Taller</title>
    <link rel="stylesheet" href="en_proceso.css">
</head>

<body>
    <header>
        <h1>SERTRANSAFAL</h1>
        <div class="sub_header"><span>Vehículos en Taller (Borradores)</span></div>
    </header>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <script>alert("¡Reporte actualizado exitosamente!");</script>
    <?php endif; ?>

    <main class="contenedor_principal">
        <div class="tarjeta_tabla">
            <div class="tabla_responsiva">
                <table>
                    <thead>
                        <tr>
                            <th>Correlativo</th>
                            <th>Fecha de Ingreso</th>
                            <th>Placa</th>
                            <th>Responsable</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($reportes) > 0): ?>
                            <?php foreach ($reportes as $r): ?>
                                <tr>
                                    <td class="id_celda">#<?= $r['correlativo'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($r['fecha_entrada'])) ?></td>
                                    <td><span class="etiqueta_placa"><?= $r['placas'] ?></span></td>
                                    <td><?= $r['chofer'] ?></td>
                                    <td>
                                        <a href="editar_reporte.php?id=<?= $r['id'] ?>" class="btn_accion btn_continuar">
                                            <i>🛠️</i> Continuar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="tabla_vacia">No hay vehículos en proceso en este momento.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="acciones_inferiores">
            <a href="nuevo_reporte.php" class="btn_footer btn_nuevo">➕ Nuevo Ingreso</a>
            <a href="../InterfazPrincipal.php" class="btn_footer btn_salir">❌ Salir</a>
        </div>
    </main>
</body>

</html>