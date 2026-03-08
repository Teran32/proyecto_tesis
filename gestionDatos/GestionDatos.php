<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Datos - Sertransfal</title>
    <link rel="stylesheet" href="gestionDatos.css">
</head>

<body>

    <div class="gestionContainer">
        <aside class="Container">
            <div class="arriba">
                <div class="arriba2">
                    <i class="icon">👤</i>
                    <h3>Registro de Chofer</h3>
                </div>
                <form action="guardar_chofer.php" method="POST" id="formChofer" class="form">
                    <div class="contenedorInput">
                        <label>Nombre Completo</label>
                        <input type="text" name="nombre_completo" placeholder="Nombre del chofer" required>
                    </div>
                    <div class="contenedorInput">
                        <label>Cédula / ID</label>
                        <input type="text" name="cedula" placeholder="V-00000000" required>
                    </div>
                    <button type="submit" class="btnPrincipal">Registrar Chofer</button>
                </form>
            </div>
        </aside>

        <main class="panel-listado">
            <div class="arriba">
                <div class="arriba2">
                    <h3>Listado de Choferes</h3>
                    <?php
            $stmtCountCh = $pdo->query("SELECT count(*) FROM choferes");
            $totalChoferes = $stmtCountCh->fetchColumn();
            ?>
                    <span class="contador">
                        <?php echo $totalChoferes; ?> choferes
                    </span>
                </div>
                <div class="tabla">
                    <table class="tablaModerna">
                        <thead>
                            <tr>
                                <th>Chofer</th>
                                <th>Cédula</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                    $stmtCh = $pdo->query("SELECT * FROM choferes ORDER BY nombre_completo ASC");
                    while ($chofer = $stmtCh->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td>
                                    <?php echo htmlspecialchars($chofer['nombre_completo']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($chofer['cedula']); ?>
                                </td>
                                <td>
                                    <button class="btnUltimo editar">✏️</button>
                                    <button class="btnUltimo borrar"
                                        onclick="eliminarChofer(<?php echo $chofer['id']; ?>)">🗑️</button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div class="gestionContainer">
        <aside class="Container">
            <div class="arriba">
                <div class="arriba2">
                    <i class="icon">👤</i>
                    <h3>Registro de Chofer</h3>
                </div>
                <form id="formChofer" class="form">
                    <div class="contenedorInput">
                        <label>Nombre Completo</label>
                        <input type="text" placeholder="Nombre del chofer">
                    </div>
                    <div class="contenedorInput">
                        <label>Cédula / ID</label>
                        <input type="text" placeholder="V-00000000">
                    </div>
                    <button type="submit" class="btnPrincipal">Registrar Chofer</button>
                </form>
            </div>
        </aside>

        <main class="panel-listado">
            <div class="arriba">
                <div class="arriba2">
                    <h3>Listado de Choferes</h3>
                    <?php
                // Consultamos el total directamente a la tabla de choferes en Supabase
                $stmtCountCh = $pdo->query("SELECT count(*) FROM choferes"); 
                $totalChoferes = $stmtCountCh->fetchColumn(); 
            ?>
                    <span class="contador">
                        <?php echo $totalChoferes; ?> choferes
                    </span>
                </div>
                <div class="tabla">
                    <table class="tablaModerna">
                        <thead>
                            <tr>
                                <th>Chofer</th>
                                <th>Cédula</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                    // Luego recorremos la tabla para mostrar los nombres y cédulas
                    $stmtCh = $pdo->query("SELECT * FROM choferes ORDER BY nombre_completo ASC");
                    while ($chofer = $stmtCh->fetch(PDO::FETCH_ASSOC)): 
                    ?>
                            <tr>
                                <td>
                                    <?php echo htmlspecialchars($chofer['nombre_completo']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($chofer['cedula']); ?>
                                </td>
                                <td>
                                    <button class="btnUltimo editar">✏️</button>
                                    <button class="btnUltimo borrar">🗑️</button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="gestionDatos.js"></script>
</body>

<footer>
    <button class="btnPrincipal" onclick="regresar()">Regresar</button>
</footer>
<script src="../funciones.js"></script>

</html>