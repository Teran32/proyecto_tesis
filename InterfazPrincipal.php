<?php
include 'gestionDatos/conexion.php';

try {
    $stmtProceso = $pdo->query("SELECT COUNT(*) FROM reportes WHERE estado = 0");
    $totalProceso = $stmtProceso->fetchColumn();

    // 3. Contar vehículos "Finalizados" en el mes actual (estado = 1)
    // Usamos MONTH(CURRENT_DATE) para que solo cuente los de este mes
    $stmtFinalizado = $pdo->query("SELECT COUNT(*) FROM reportes 
                                   WHERE estado = 1 
                                   AND MONTH(fecha_entrada) = MONTH(CURRENT_DATE()) 
                                   AND YEAR(fecha_entrada) = YEAR(CURRENT_DATE())");
    $totalFinalizado = $stmtFinalizado->fetchColumn();

} catch (PDOException $e) {
    $totalProceso = 0;
    $totalFinalizado = 0;
}
?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Sertransafal - Gestión de Vehículos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
</head>

<body>
<a href="" style="display: flex; justify-content: end; top: 10px; right: 10px; color:white; text-decoration: none; margin: 10px;">Entrar como administrador</a>
    <header>
        <h1>SERTRANSFAL</h1>
        <p>Sistema de Gestión de Vehículos</p>

    </header>

    <div class="contenedor_menu">
        <a href="nuevoReportes/nuevo_reporte.php" class="caja_opcion">
            <div>➕</div>
            Nuevo Reporte
        </a>
        <a href="enProceso/en_proceso.php" class="caja_opcion">
            <div>🛠️</div>
            En Proceso
        </a>

        <a href="finalizados/lista_reportes.php" class="caja_opcion">
            <div>✅</div>
            Finalizados
        </a>
        <a href="gestionDatos/GestionDatos.php" class="caja_opcion">
            <div>⚙️</div>
            Gestión de Datos
        </a>
        <a href="control/control_vehicular.php" class="caja_opcion">
            <div>🚗</div>
            control vehicular
        </a>
    </div>

    <footer class="barra_estado">
        <div class="footer_info">
            <span class="pildora_estado">
                <span class="punto azul"></span>
                En taller: <b id="total_proceso">
                    <?= $totalProceso ?>
                </b>
            </span>
            <span class="pildora_estado">
                <span class="punto verde"></span>
                Listos: <b id="total_finalizado">
                    <?= $totalFinalizado ?>
                </b>
            </span>
        </div>
        <div class="footer_copyright">
            Sertransafal © 2026
        </div>
    </footer>

</body>

</html>