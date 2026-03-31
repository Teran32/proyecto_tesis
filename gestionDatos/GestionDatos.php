<?php
include 'conexion.php'; 

// Prevenir el almacenamiento en caché para evitar que las alertas se repitan al volver atrás
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Consultas dinámicas para los selectores
$marcas = $pdo->query("SELECT id_marcas, marcas FROM marcas ORDER BY marcas ASC")->fetchAll(PDO::FETCH_ASSOC);
$listaChoferes = $pdo->query("SELECT * FROM choferes ORDER BY chofer ASC")->fetchAll(PDO::FETCH_ASSOC);

$sqlUnidades = "SELECT p.id_placas, p.placas, ma.marcas, mo.modelos 
                FROM placas p
                JOIN modelos mo ON p.id_modelos = mo.id_modelos
                JOIN marcas ma ON mo.id_marcas = ma.id_marcas
                ORDER BY p.id_placas DESC";
$unidades = $pdo->query($sqlUnidades)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertransfal - Gestión Maestra</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="assets/css/gestionDatos.css">
</head>
<body >
    <div class="top-nav">
        <a href="../InterfazPrincipal.php" class="btnVolver">← Volver al Menú</a>
    </div>

    <div class="main-wrapper">


    <div class="gestionContainer">
        <aside class="Container">
            <div class="arriba">
                <div class="arriba2">
                    <i class="icon">🚗</i>
                    <h3>Registro de Unidad</h3>
                </div>

                <form id="formUnidad" action="api/guardar_unidad.php" method="POST" class="form">
                    <div class="contenedorInput">
                        <label>Marca</label>
                        <div class="inputBoton">
                            <select id="marcaVehiculo" name="id_marca" onchange="verificarMarca(this.value)" required>
                                <option value="">Seleccionar...</option>
                                <?php foreach($marcas as $m): ?>
                                    <option value="<?= $m['id_marcas'] ?>"><?= $m['marcas'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btnMini" onclick="agregarMaestro('marca')">+</button>
                        </div>
                    </div>

                    <div class="contenedorInput">
                        <label>Modelo</label>
                        <div class="inputBoton">
                            <select id="modeloVehiculo" name="id_modelo" disabled required>
                                <option value="">Selecciona marca primero...</option>
                            </select>
                            <button type="button" class="btnMini" onclick="agregarMaestro('modelo')">+</button>
                        </div>
                    </div>

                    <div class="contenedorInput">
                        <label>Número de Placa</label>
                        <input type="text" name="placa" placeholder="EJ: AB123CD" required>
                    </div>
                    <button type="submit" class="btnPrincipal">Vincular Unidad</button>
                </form>
            </div>
        </aside>

        <main class="panel-listado">
            <div class="arriba">
                <div class="arriba2">
                    <h3>Unidades vehiculares</h3>
                    <span class="contador"><?= count($unidades) ?> Registros</span>
                </div>
                <div class="tabla">
                    <table class="tablaModerna display" id="tablaUnidades">
                        <thead>
                            <tr><th>Placa</th><th>Vehículo</th><th>Acciones</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach($unidades as $u): ?>
                            <tr>
                                <td><span class="placa-tag"><?= $u['placas'] ?></span></td>
                                <td><?= $u['marcas'] . " " . $u['modelos'] ?></td>
                                <td>
                                    <button class="btnUltimo editar" onclick="modificar('vehiculo', <?= $u['id_placas'] ?>)">✏️</button>
                                    <button class="btnUltimo borrar" onclick="eliminar('vehiculo', <?= $u['id_placas'] ?>)">🗑️</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>



    <div class="gestionContainer">
        <aside class="Container">
            <div class="arriba">
                <div class="arriba2">
                    <i class="icon">👤</i>
                    <h3>Registro de Chofer</h3>
                </div>
                <form action="api/guardar_chofer.php" method="POST" class="form">
                    <div class="contenedorInput">
                        <label>Nombre del Chofer</label>
                        <input type="text" name="chofer" placeholder="Nombre y Apellido" required>
                    </div>
                    <button type="submit" class="btnPrincipal">Registrar Personal</button>
                </form>
            </div>
        </aside>

        <main class="panel-listado">
            <div class="arriba">
                <div class="arriba2">
                    <h3>Personal Registrado</h3>
                    <span class="contador"><?= count($listaChoferes) ?> Choferes</span>
                </div>
                <div class="tabla">
                    <table class="tablaModerna display" id="tablaChoferes">
                        <thead>
                            <tr>
                                <th>Nombre Completo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($listaChoferes as $c): ?>
                            <tr>
                                <td><?= $c['chofer'] ?></td>
                                <td>
                                    <button class="btnUltimo editar" onclick="modificar('chofer', <?= $c['id_chofer'] ?>)">✏️</button>
                                    <button class="btnUltimo borrar" onclick="eliminar('chofer', <?= $c['id_chofer'] ?>)">🗑️</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="assets/js/gestionDatos.js"></script>
        <script src="assets/js/modificar.js"></script>
        <script>

            // para mostrar alertas de guardado y eliminacion y si hay algo ya registrado 
            <?php if (isset($_SESSION['alerta'])): 
                $a = $_SESSION['alerta'];
                $id_alerta = $_SESSION['alerta_id'] ?? 'default';
                unset($_SESSION['alerta']); 
            ?>
                (function() {
                    const idActual = "<?= $id_alerta ?>";
                    // Si el ID ya está en el almacenamiento del navegador, es que ya se mostró (por historial)
                    if (sessionStorage.getItem('alerta_mostrada') === idActual) {
                        return;
                    }

                    const res = "<?= $a['res'] ?>";
                    const placa = "<?= $a['placa'] ?? '' ?>";

                    if (res === 'duplicado') {
                        Swal.fire({
                            title: '¡Placa Duplicada!',
                            text: `La placa ${placa} ya está registrada en el sistema.`,
                            icon: 'error',
                            confirmButtonColor: '#3498db'
                        });
                    } else if (res === 'ok') {
                        Swal.fire({
                            title: '¡Éxito!',
                            text: 'Operación realizada correctamente.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else if (res === 'ok_del') {
                        Swal.fire({
                            title: 'Eliminado',
                            text: 'El registro ha sido borrado.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }

                    // Marcar este mensaje como mostrado para siempre en esta pestaña
                    sessionStorage.setItem('alerta_mostrada', idActual);
                })();
            <?php endif; ?>

            function eliminar(tipo, id) {
            Swal.fire({
                title: '¿Eliminar ' + tipo + '?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Sí, borrar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `api/eliminar.php?tipo=${tipo}&id=${id}`;
                }
            });
        }
    </script>
</body>
</html>