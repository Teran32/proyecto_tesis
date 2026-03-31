<?php
include '../gestionDatos/conexion.php';

// Traemos las marcas y contamos cuántos vehículos tiene cada una
$sqlMarcas = "SELECT m.id_marcas, m.marcas, COUNT(p.id_placas) as total 
              FROM marcas m 
              LEFT JOIN modelos mo ON m.id_marcas = mo.id_marcas 
              LEFT JOIN placas p ON mo.id_modelos = p.id_modelos 
              GROUP BY m.id_marcas";
$marcas = $pdo->query($sqlMarcas)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Control de Flota - Sertransfal</title>
    <link rel="stylesheet" href="control_vehicular.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

    <div class="contenedor-principal">
        <header class="cabecera">
            <h1><i class="fas fa-shuttle-van"></i> Control Vehicular</h1>
            <p>Seleccione una marca para ver sus unidades registradas</p>
        </header>

        <div class="barra-busqueda">
            <input type="text" id="buscadorGlobal" placeholder="Escribe una placa o modelo para búsqueda rápida..."
                onkeyup="buscarRapido()">
        </div>

        <div class="rejilla-marcas">
            <?php foreach ($marcas as $m): ?>
                <div class="tarjeta-marca" onclick="verDetalles('<?= $m['id_marcas'] ?>')">
                    <div class="icono-marca"><i class="fas fa-car"></i></div>
                    <div class="info-marca">
                        <h3>
                            <?= $m['marcas'] ?>
                        </h3>
                        <span>
                            <?= $m['total'] ?> Vehículos
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="panelDetalles" class="panel-detalles">
            <div class="cabecera-detalle">
                <h2 id="tituloMarca">Seleccione una marca</h2>
                <button onclick="cerrarPanel()" class="btn-cerrar">×</button>
            </div>
            <div id="listaVehiculos" class="lista-unidades">
            </div>
        </div>

        <div class="pie-pagina">
            <a href="../InterfazPrincipal.php" class="boton-volver" id="boton-volver">Volver al Inicio</a>
        </div>
    </div>

    <script>
        function verDetalles(idMarca) {
            const panel = document.getElementById('panelDetalles');
            const lista = document.getElementById('listaVehiculos');
            const botonVolver = document.getElementById('boton-volver');

            botonVolver.style.display = 'none';

            panel.classList.add('activo');
            lista.innerHTML = '<p style="color:white;">Cargando unidades...</p>';

            fetch(`obtener_unidades_marca.php?id=${idMarca}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('tituloMarca').innerText = "Unidades de la Marca";
                    lista.innerHTML = "";

                    data.forEach(v => {
                        lista.innerHTML += `
                    <div class="item-vehiculo">
                        <span class="placa">${v.placas}</span>
                        <span class="modelo">${v.modelos}</span>
                        <a href="historial_vehiculo.php?placa=${v.id_placas}" class="btn-ver">Ver Historial</a>
                    </div>
                `;
                    });
                });
        }

        function cerrarPanel() {
            document.getElementById('panelDetalles').classList.remove('activo');
            document.getElementById('boton-volver').style.display = 'inline-flex';
        }
    </script>
</body>

</html>