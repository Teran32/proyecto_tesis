<?php
include '../gestionDatos/conexion.php';
include '../nuevoReportes/correlativo_año.php';

// Consulta optimizada
$consulta = $pdo->query("SELECT r.id, r.correlativo, r.fecha_entrada, p.placas, c.chofer 
                        FROM reportes r
                        JOIN placas p ON r.id_placa = p.id_placas
                        JOIN choferes c ON r.id_chofer = c.id_chofer
                        WHERE r.estado = 1
                        ORDER BY r.id DESC");
$reportes = $consulta->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Sertransfal - Listado de Reportes</title>
    <link rel="stylesheet" href="lista_reportes.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

    <div class="contenedor-principal">
        <header
            style="text-align: center; color: white; margin-bottom: 30px; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
            <h1 style="font-size: 2.5rem;"><i class="fas fa-file-invoice"></i> Reportes Finalizados</h1>
            <p>Historial de mantenimientos y servicios realizados</p>
        </header>

        <div class="barra-herramientas">
            <div class="caja-busqueda">
                <i class="fas fa-search"></i>
                <input type="text" id="busquedaTexto" placeholder="Buscar placa, chofer o código..."
                    onkeyup="ejecutarFiltros()">
            </div>
            <div class="caja-filtro">
                <i class="fas fa-calendar-alt"></i>
                <select id="filtroMes" onchange="ejecutarFiltros()">
                    <option value="">Mostrar todos los meses</option>
                    <option value="/01/">Enero</option>
                    <option value="/02/">Febrero</option>
                    <option value="/03/">Marzo</option>
                    <option value="/04/">Abril</option>
                    <option value="/05/">Mayo</option>
                    <option value="/06/">Junio</option>
                    <option value="/07/">Julio</option>
                    <option value="/08/">Agosto</option>
                    <option value="/09/">Septiembre</option>
                    <option value="/10/">Octubre</option>
                    <option value="/11/">Noviembre</option>
                    <option value="/12/">Diciembre</option>
                </select>
            </div>
        </div>

        <div class="tarjeta-tabla">
            <div class="area-desplazamiento">
                <table class="tabla-estilizada" id="tablaReportes">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Fecha Registro</th>
                            <th>Placa Unidad</th>
                            <th>Chofer Asignado</th>
                            <th style="text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportes as $fila): ?>
                            <tr>
                                <td><b><?= $fila['correlativo'] ?></b></td>
                                <td><?= date('d/m/Y', strtotime($fila['fecha_entrada'])) ?></td>
                                <td><span class="etiqueta-placa"><?= $fila['placas'] ?></span></td>
                                <td><?= $fila['chofer'] ?></td>
                                <td style="text-align: center;">
                                    <a href="generar_pdf.php?id=<?= $fila['id'] ?>" target="_blank" class="boton-pdf">
                                        <i class="fas fa-file-pdf"></i> Imprimir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div style="text-align: left;">
            <a href="../InterfazPrincipal.php" class="boton-volver">
                <i class="fas fa-arrow-left"></i> Volver al Menú
            </a>
        </div>
    </div>

    <script>
        function ejecutarFiltros() {
            const texto = document.getElementById("busquedaTexto").value.toUpperCase();
            const mes = document.getElementById("filtroMes").value;
            const tabla = document.getElementById("tablaReportes");
            const filas = tabla.getElementsByTagName("tr");

            for (let i = 1; i < filas.length; i++) {
                const contenidoFila = filas[i].textContent.toUpperCase();
                const celdaFecha = filas[i].getElementsByTagName("td")[1].textContent;

                const coincideTexto = contenidoFila.indexOf(texto) > -1;
                const coincideMes = mes === "" || celdaFecha.indexOf(mes) > -1;

                filas[i].style.display = (coincideTexto && coincideMes) ? "" : "none";
            }
        }
    </script>
</body>

</html>