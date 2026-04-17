<?php
include '../gestionDatos/conexion.php';

// Traemos todos los choferes con estadísticas
$sqlChoferes = "
    SELECT 
        ch.id_chofer,
        ch.chofer,
        COUNT(r.id) AS total_reportes,
        COUNT(DISTINCT r.id_placa) AS total_vehiculos,
        MAX(r.fecha_entrada) AS ultima_visita,
        SUM(CASE WHEN r.estado = 0 THEN 1 ELSE 0 END) AS en_taller,
        SUM(CASE WHEN r.estado = 1 THEN 1 ELSE 0 END) AS finalizados
    FROM choferes ch
    LEFT JOIN reportes r ON ch.id_chofer = r.id_chofer
    GROUP BY ch.id_chofer, ch.chofer
    ORDER BY total_reportes DESC
";
$choferes = $pdo->query($sqlChoferes)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control por Chofer - Sertransfal</title>
    <link rel="stylesheet" href="control_chofer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <a href="../InterfazPrincipal.php" class="boton-volver">
        <i class="fas fa-arrow-left"></i> Volver
    </a>

    <div class="contenedor-principal">
        <header class="cabecera">
            <div class="cabecera-icono"><i class="fas fa-users"></i></div>
            <h1>Control por Chofer</h1>
            <p>Selecciona un chofer para ver sus vehículos y el historial de reportes</p>
        </header>

        <!-- Barra de búsqueda -->
        <div class="barra-busqueda">
            <i class="fas fa-search icono-busqueda"></i>
            <input type="text" id="buscador" placeholder="Buscar chofer..." onkeyup="filtrarChoferes()">
        </div>



        <!-- Grid de choferes -->
        <div class="rejilla-choferes" id="rejillaChoferes">
            <?php if (empty($choferes)): ?>
                <p class="sin-datos">No hay choferes registrados.</p>
            <?php endif; ?>
            <?php foreach ($choferes as $c): ?>
                <div class="tarjeta-chofer" 
                     data-nombre="<?= strtolower($c['chofer']) ?>"
                     onclick="window.location='historial_chofer.php?id=<?= $c['id_chofer'] ?>'">
                    
                    <div class="avatar-chofer">
                        <i class="fas fa-user-tie"></i>
                    </div>

                    <div class="info-chofer">
                        <h3 class="nombre-chofer"><?= htmlspecialchars($c['chofer']) ?></h3>

                        <div class="chips-chofer">
                            <span class="chip chip-vehicles">
                                <i class="fas fa-car"></i> <?= $c['total_vehiculos'] ?> vehículo<?= $c['total_vehiculos'] != 1 ? 's' : '' ?>
                            </span>
                            <span class="chip chip-reportes">
                                <i class="fas fa-clipboard-list"></i> <?= $c['total_reportes'] ?> reporte<?= $c['total_reportes'] != 1 ? 's' : '' ?>
                            </span>
                            <?php if ($c['en_taller'] > 0): ?>
                            <span class="chip chip-taller">
                                <i class="fas fa-tools"></i> <?= $c['en_taller'] ?> en taller
                            </span>
                            <?php endif; ?>
                        </div>

                        <div class="ultima-visita">
                            <i class="fas fa-clock"></i>
                            <?= $c['ultima_visita'] 
                                ? 'Última visita: ' . date('d/m/Y', strtotime($c['ultima_visita'])) 
                                : 'Sin reportes aún' ?>
                        </div>
                    </div>

                    <div class="flecha-tarjeta">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

    <script>
        function filtrarChoferes() {
            const texto = document.getElementById('buscador').value.toLowerCase();
            const tarjetas = document.querySelectorAll('.tarjeta-chofer');
            tarjetas.forEach(t => {
                const nombre = t.dataset.nombre;
                t.style.display = nombre.includes(texto) ? 'flex' : 'none';
            });
        }
    </script>
</body>

</html>
