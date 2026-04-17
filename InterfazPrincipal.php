<?php
include 'gestionDatos/conexion.php';

try {
    // Reportes en taller (estado = 0)
    $totalProceso = $pdo->query("SELECT COUNT(*) FROM reportes WHERE estado = 0")->fetchColumn();

    // Finalizados en el mes actual (estado = 1)
    $totalFinalizado = $pdo->query("SELECT COUNT(*) FROM reportes WHERE estado = 1
                                    AND MONTH(fecha_entrada) = MONTH(CURRENT_DATE())
                                    AND YEAR(fecha_entrada) = YEAR(CURRENT_DATE())")->fetchColumn();

    // Total de choferes registrados
    $totalChoferes = $pdo->query("SELECT COUNT(*) FROM choferes")->fetchColumn();

    // Total de reportes históricos
    $totalReportes = $pdo->query("SELECT COUNT(*) FROM reportes")->fetchColumn();

     $totalVehiculos = $pdo->query("SELECT COUNT(*) FROM placas")->fetchColumn();
    // Choferes con vehículo actualmente en taller
    $choferesEnTaller = $pdo->query(
        "SELECT COUNT(DISTINCT id_chofer) FROM reportes WHERE estado = 0"
    )->fetchColumn();

    // Vehículos activosss
    $vehiculosActivos = $pdo->query(
        "SELECT COUNT(DISTINCT id_placa) FROM reportes"
    )->fetchColumn();

} catch (PDOException $e) {
    $totalProceso = 0;
    $totalFinalizado = 0;
    $totalChoferes = 0;
    $totalReportes = 0;
    $vehiculosActivos = 0;
    $totalVehiculos = 0;
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

    <!-- Sidebar de estadísticas -->
    <aside class="sidebar-stats">
        <div class="sidebar-titulo">📊 Resumen</div>

        <div class="sidebar-stat">
            <span class="sidebar-num" style="color:#60a5fa"><?= $totalChoferes ?></span>
            <span class="sidebar-label">Choferes</span>
        </div>
        <div class="sidebar-stat">
            <span class="sidebar-num" style="color:#a78bfa"><?= $vehiculosActivos ?></span>
            <span class="sidebar-label">Vehículos activos</span>
        </div>

        <div class="sidebar-stat">
            <span class="sidebar-num" style="color:#10b981"><?= $totalVehiculos ?></span>
            <span class="sidebar-label">total de vehiculos</span>
        </div>

        <div class="sidebar-divider"></div>

        <div class="sidebar-stat">
            <span class="sidebar-num" style="color:#ef4444"><?= $totalProceso ?></span>
            <span class="sidebar-label">En taller ahora</span>
        </div>
        <div class="sidebar-stat">
            <span class="sidebar-num" style="color:#10b981"><?= $totalFinalizado ?></span>
            <span class="sidebar-label">Finalizados este mes</span>
        </div>
        <div class="sidebar-stat">
            <span class="sidebar-num" style="color:#f59e0b"><?= $totalReportes ?></span>
        <span class="sidebar-label">Reportes totales</span>
        </div>
        <img src="imagenes/sertran.jpg" style="width: 90%; height: 20%; margin-top:20px; border-radius:20px;">
    </aside>

    <main class="contenido-principal">

    <header style="display: flex; align-items: center; justify-content: center; text-decoration: none;">
        <div>
        <h1>SERTRANSFAL</h1>
        <p>Sistema de Gestión de Vehículos</p>
        </div>
            <button class="btn_salir" style="
            top:20px;
            right: 20px;
            position: fixed;text-decoration: none;
        padding: 12px 25px;
        border-radius: 10px;
        font-weight: bold;
        transition: 0.3s;
        cursor:pointer;"> cerrar sesion
            </button>
            <button id="btn_gestionUsuario" class="btn_salir" style="
            
            margin-top:22px;
            right: 20px;
            position: fixed;
            text-decoration: none;
        padding: 10px 10px;
        border-radius: 10px;
        font-weight: bold;
        transition: 0.3s;
        cursor:pointer;"> gestion de usuarios
            </button>
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
        <a href="control_chofer/control_chofer.php" class="caja_opcion">
            <div>👨</div>
            control por chofer
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
                Listos en el mes: <b id="total_finalizado">
                    <?= $totalFinalizado ?>
                </b>
            </span>
        </div>
        <div class="footer_copyright">
            Sertransafal © 2026
        </div>
    </footer>

    </main><!-- fin contenido-principal -->

</body>

</html>

<script>

    const salir = document.querySelector('.btn_salir');
    salir.addEventListener('click', () => {
        window.location.href = 'index.php';
    });
    const gestionUsuario = document.getElementById('btn_gestionUsuario');
    gestionUsuario.addEventListener('click', () => {
        window.location.href = 'gestion_usuarios/gestion_de_usuarios.php';
    });

</script>