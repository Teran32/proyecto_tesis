<?php
include '../gestionDatos/conexion.php';

$placas = $pdo->query("SELECT id_placas, placas FROM placas")->fetchAll(PDO::FETCH_ASSOC);
$choferes = $pdo->query("SELECT id_chofer, chofer FROM choferes")->fetchAll(PDO::FETCH_ASSOC);
$tipos = $pdo->query("SELECT id_tipo_trabajo, tipo_trabajo FROM tipo_trabajo")->fetchAll(PDO::FETCH_ASSOC);

$id_reporte = isset($_GET['id']) ? $_GET['id'] : 0;
$stmt_r = $pdo->prepare("SELECT * FROM reportes WHERE id = ?");
$stmt_r->execute([$id_reporte]);
$r = $stmt_r->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Sertransfal - Editando Reporte Detallado</title>
    <link rel="stylesheet" href="../nuevoReportes/reporte.css">
</head>

<body onload="cargarDatosMaestros()">
    <header>
        <h1>SERTRANSAFAL</h1>
        <div class="sub_header">
            <span>Reporte de Vehículo</span>
            <span id="num_reporte">
                <?= $r['correlativo'] ?? '' ?>
            </span>
            <input type="hidden" name="correlativo" value="<?= $r['correlativo'] ?? '' ?>" style="border: black solid 2px;">
        </div>
    </header>

    <main class="contenedor_principal">
        <form action="actualizar_reporte_maestro.php" method="POST" id="formulario_maestro" enctype="multipart/form-data"
            onsubmit="return validarEnvio(event)">
            
            <input type="hidden" name="id_reporte" id="input_id_reporte">

            <section class="tarjeta_form" style="background-color: #f4f4f4; padding: 20px; border-radius: 8px;">
                <h3><i class="icono">🚗</i> Datos de la Unidad (Modo Edición)</h3>
                <div class="cuadricula">
                    <div class="campo">
                        <label>Fecha/Hora Entrada</label>
                        <input class="input" type="datetime-local" name="fecha_entrada"
                            required>
                    </div>
                    <div class="campo">
                        <label>Fecha/Hora Salida</label>
                        <input class="input" type="datetime-local" name="fecha_salida" id="fecha_salida">
                    </div>
                    <div class="campo">
                        <label>Placa (Unidad)</label>
                        <select id="selectPlaca" name="id_placa" class="input"
                            onchange="obtenerDatosVehiculo(this.value)" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($placas as $p): ?>
                                <option value="<?= $p['id_placas'] ?>"><?= $p['placas'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="campo">
                        <label>Marca</label>
                        <input type="text" id="mostrarMarca" class="input" style="background: #e9ecef;" readonly
                            placeholder="Esperando placa...">
                    </div>
                    <div class="campo">
                        <label>Modelo</label>
                        <input type="text" id="mostrarModelo" class="input" style="background: #e9ecef;" readonly
                            placeholder="Esperando placa...">
                    </div>

                    <div class="campo">
                        <label>Chofer </label>
                        <select name="id_chofer" class="input" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($choferes as $c): ?>
                                <option value="<?= $c['id_chofer'] ?>"><?= $c['chofer'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="campo">
                        <label>Tipo de Trabajo</label>
                        <select name="id_tipo_trabajo" class="input" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($tipos as $t): ?>
                                <option value="<?= $t['id_tipo_trabajo'] ?>"><?= $t['tipo_trabajo'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="campo">
                        <label>Kilometraje Actual</label>
                        <input type="number" name="km_actual" class="input" required>
                    </div>
                    <div class="campo">
                        <label>Próximo Kilometraje</label>
                        <input type="number" name="km_prox" class="input" required>
                    </div>
                </div>
                
                <div class="ayuda">
                    <div class="ayudaa">
                        <span class="selector" style="font-size: 12px;">Aceite de motor</span>
                        <select name="" id="" onchange="actualizarDetalles(this, 'Aceite de motor')">
                            <option value=""></option>
                            <option value="cambio de">C</option>
                            <option value="verificado">V</option>
                            <option value="poca vida util">X</option>
                        </select>
                    </div>
                    <div class="ayudaa">
                        <span class="selector" style="font-size: 12px;">filtro de aceite</span>
                        <select name="" id="" onchange="actualizarDetalles(this, 'filtro de aceite')">
                            <option value=""></option>
                            <option value="cambio de">C</option>
                            <option value="verificado">V</option>
                            <option value="mantenimiento">M</option>
                        </select>
                    </div>
                    <div class="ayudaa">
                        <span class="selector" style="font-size: 12px;">filtro de aire</span>
                        <select name="" id="" onchange="actualizarDetalles(this, 'filtro de aire')">
                            <option value=""></option>
                            <option value="cambio de">C</option>
                            <option value="verificado">V</option>
                            <option value="mantenimiento">M</option>
                            <option value="poca vida util">X</option>
                        </select>
                    </div>
                    <div class="ayudaa">
                        <span class="selector" style="font-size: 12px;">filtro de gasolina</span>
                        <select name="" id="" onchange="actualizarDetalles(this, 'filtro de gasolina')">
                            <option value=""></option>
                            <option value="cambio de">C</option>
                            <option value="verificado">V</option>
                            <option value="poca vida util">X</option>
                        </select>
                    </div>
                    <div class="ayudaa">
                        <span class="selector" style="font-size: 12px;">bomba de gasolina</span>
                        <select name="" id="" onchange="actualizarDetalles(this, 'bomba de gasolina')">
                            <option value=""></option>
                            <option value="cambio de">C</option>
                            <option value="verificado">V</option>
                            <option value="poca vida util">X</option>
                        </select>
                    </div>
                    <div class="ayudaa">
                        <span class="selector" style="font-size: 12px;">bujias</span>
                        <select name="" id="" onchange="actualizarDetalles(this, 'bujias')">
                            <option value=""></option>
                            <option value="cambio de">C</option>
                            <option value="verificado">V</option>
                            <option value="mantenimiento">M</option>
                            <option value="poca vida util">X</option>
                        </select>
                    </div>
                    <div class="ayudaa">
                        <span class="selector" style="font-size: 12px;">cables de bujias</span>
                        <select name="" id="" onchange="actualizarDetalles(this, 'cables de bujias')">
                            <option value=""></option>
                            <option value="cambio de">C</option>
                            <option value="verificado">V</option>
                            <option value="mantenimiento">M</option>
                            <option value="poca vida util">X</option>
                        </select>
                    </div>
                    <div class="ayudaa">
                        <span class="selector" style="font-size: 12px;">correas</span>
                        <select name="" id="" onchange="actualizarDetalles(this, 'correas')">
                            <option value=""></option>
                            <option value="cambio de">C</option>
                            <option value="verificado">V</option>
                            <option value="poca vida util">X</option>
                        </select>
                    </div>
                    <div class="ayudaa">
                        <span class="selector" style="font-size: 12px;">inyectores</span>
                        <select name="" id="" onchange="actualizarDetalles(this, 'inyectores')">
                            <option value=""></option>
                            <option value="cambio de">C</option>
                            <option value="verificado">V</option>
                            <option value="mantenimiento">M</option>
                            <option value="poca vida util">X</option>
                        </select>
                    </div>
                    <div class="ayudaa">
                        <span class="selector" style="font-size: 12px;">bateria</span>
                        <select name="" id="" onchange="actualizarDetalles(this, 'bateria')">
                            <option value=""></option>
                            <option value="cambio de">C</option>
                            <option value="verificado">V</option>
                            <option value="mantenimiento">M</option>
                            <option value="poca vida util">X</option>
                        </select>
                    </div>
                    <div class="ayudaa">
                        <span class="selector" style="font-size: 12px;">refrigerante</span>
                        <select name="" id="" onchange="actualizarDetalles(this, 'refrigerante')">
                            <option value=""></option>
                            <option value="cambio de">C</option>
                            <option value="verificado">V</option>
                            <option value="poca vida util">X</option>
                        </select>
                    </div>
                    <div class="ayudaa">
                        <span class="selector" style="font-size: 12px;">limpiaparabrisas</span>
                        <select name="" id="" onchange="actualizarDetalles(this, 'limpiaparabrisas')">
                            <option value=""></option>
                            <option value="cambio de">C</option>
                            <option value="verificado">V</option>
                            <option value="poca vida util">X</option>
                        </select>
                    </div>
                    <div class="ayudaa">
                        <span class="selector" style="font-size: 12px;">cauchos</span>
                        <select name="" id="" onchange="actualizarDetalles(this, 'cauchos')">
                            <option value=""></option>
                            <option value="cambio de">C</option>
                            <option value="verificado">V</option>
                            <option value="rotacion">R</option>
                            <option value="poca vida util">X</option>
                        </select>
                    </div>
                    <div class="ayudaa">
                        <span class="selector" style="font-size: 12px;">pastillas</span>
                        <select name="" id="" onchange="actualizarDetalles(this, 'pastillas')">
                            <option value=""></option>
                            <option value="cambio de">C</option>
                            <option value="verificado">V</option>
                            <option value="poca vida util">X</option>
                        </select>
                    </div>
                    <div class="ayudaa">
                        <span class="selector" style="font-size: 12px;">liquido de frenos</span>
                        <select name="" id="" onchange="actualizarDetalles(this, 'liquido de frenos')">
                            <option value=""></option>
                            <option value="cambio de">C</option>
                            <option value="verificado">V</option>
                            <option value="poca vida util">X</option>
                        </select>
                    </div>
                    <div class="ayudaa">
                        <span class="selector" style="font-size: 12px;">bandas</span>
                        <select name="" id="" onchange="actualizarDetalles(this, 'bandas')">
                            <option value=""></option>
                            <option value="cambio de">C</option>
                            <option value="verificado">V</option>
                            <option value="poca vida util">X</option>
                        </select>
                    </div>
                    <div class="ayudaa">
                        <span class="selector" style="font-size: 12px;">disco de frenos</span>
                        <select name="" id="" onchange="actualizarDetalles(this, 'disco de frenos')">
                            <option value=""></option>
                            <option value="cambio de">C</option>
                            <option value="verificado">V</option>
                            <option value="poca vida util">X</option>
                        </select>
                    </div>
                    <div class="ayudaa">
                        <span class="selector" style="font-size: 12px;">engrase</span>
                        <select name="" id="" onchange="actualizarDetalles(this, 'engrase')">
                            <option value=""></option>
                            <option value="verificado">V</option>
                            <option value="mantenimiento">M</option>
                        </select>
                    </div>
                    <div class="ayudaa">
                        <span class="selector" style="font-size: 12px;">crucetas</span>
                        <select name="" id="" onchange="actualizarDetalles(this, 'crucetas')">
                            <option value=""></option>
                            <option value="cambio de">C</option>
                            <option value="verificado">V</option>
                            <option value="poca vida util">X</option>
                        </select>
                    </div>
                    <div class="ayudaa">
                        <span class="selector" style="font-size: 12px;">gato</span>
                        <select name="" id="" onchange="actualizarDetalles(this, 'gato')">
                            <option value=""></option>
                            <option value="cambio de">C</option>
                            <option value="verificado">V</option>
                            <option value="poca vida util">X</option>
                        </select>
                    </div>
                    <div class="ayudaa">
                        <span class="selector" style="font-size: 12px;">triangulo de seguridad</span>
                        <select name="" id="" onchange="actualizarDetalles(this, 'triangulo de seguridad')">
                            <option value=""></option>
                            <option value="cambio de">C</option>
                            <option value="verificado">V</option>
                            <option value="poca vida util">X</option>
                        </select>
                    </div>
                    <div class="ayudaa">
                        <span class="selector" style="font-size: 12px;">llave de rueda</span>
                        <select name="" id="" onchange="actualizarDetalles(this, 'llave de rueda')">
                            <option value=""></option>
                            <option value="cambio de">C</option>
                            <option value="verificado">V</option>
                            <option value="poca vida util">X</option>
                        </select>
                    </div>
                    <div class="ayudaa">
                        <span class="selector" style="font-size: 12px;">extintor</span>
                        <select name="" id="" onchange="actualizarDetalles(this, 'extintor')">
                            <option value=""></option>
                            <option value="cambio de">C</option>
                            <option value="verificado">V</option>
                            <option value="poca vida util">X</option>
                        </select>
                    </div>
                </div>
                <h5 style="margin: 0px; padding: 3px; opacity: 0.4;">leyenda: C=cambio de, V=verificado, X=poca vida
                    util,
                    M=Mantenimiento</h5>
                    
            </section>

            <section class="tarjeta_form">
                <h3><i class="icono">🔧</i> Informe Técnico</h3>
                <label>Falla Detectada</label>
                <textarea name="falla_detectada" rows="3" required></textarea>

                <label>Trabajo Realizado</label>
                <textarea name="trabajo_realizado" rows="3" id="trabajo_realizado"></textarea>

                <label>Repuestos Utilizados</label>
                <textarea name="repuestos" rows="3"></textarea>

                <label>Observaciones</label>
                <textarea name="observacion" rows="2"></textarea>

                <label>Solicitud de Repuestos</label>
                <textarea name="pedidos" rows="2" id="detalles_repuestos"></textarea>
                <button type="button" onclick="enviarRepuestosPorEmail()"
                    style="background-color: #0275d8; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer; margin-top: 10px;">
                    <i>📧</i> Enviar Solicitud por Correo (API)
                </button>
            </section>

            <section class="tarjeta_form">
            <div class="inspeccion">
                <h3>Inspección de Carrocería</h3>
                <p>Seleccione el daño: Rayado (R), Abolladura (A), Roto (X)</p>
                <div class="vehiculo">
                    <div class="contenedorInput">
                        <label>Galería de Inspección de la Unidad</label>
                        <div class=" colocarFotos" id="zonaFoto"
                            onclick="document.getElementById('inputFotos').click()">
                            <div class="fotico">📸</div>
                            <p><strong>Haz clic para subir</strong> o arrastra las fotos aquí</p>
                            <span>Formatos aceptados: JPG, PNG (Máx. 5MB)</span>
                        </div>

                        <input type="file" id="inputFotos" name="fotos[]" accept="image/*" multiple style="display: none;" onchange="manejarFotos(event)">

                        <!-- Mostrar imágenes ya guardadas -->
                        <div class="carruselFotos" id="galeriaDinamica">
                            <?php
                            if (!empty($id_reporte)) {
                                $stmt_imgs = $pdo->prepare("SELECT * FROM imagenes_reporte WHERE id_reporte = ?");
                                $stmt_imgs->execute([$id_reporte]);
                                $imagenes = $stmt_imgs->fetchAll(PDO::FETCH_ASSOC);
                                if ($imagenes) {
                                    foreach ($imagenes as $img) {
                                        echo '<div class="img-miniatura" style="display:inline-block;margin:5px;">';
                                        echo '<img src="../nuevoReportes/' . htmlspecialchars($img['ruta_imagen']) . '" alt="Imagen Inspección" style="max-width:120px;max-height:120px;border:1px solid #ccc;padding:2px;">';
                                        echo '</div>';
                                    }
                                } else {
                                    echo '<div class="noFotos" id="placeholderTexto"><p>No hay fotos guardadas para este reporte</p></div>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div id="modalVisor" class="modal-foto" onclick="cerrarVisor()">
                        <span class="cerrar-modal">&times;</span>
                        <img id="imgGrande" src="">
                    </div>
                </div>
            </section>

            <div class="acciones_finales">

                <button class="btn_finalizar" type="submit" name="accion" value="en_proceso"
                    style=" border: solid 2px black;">Mantener en Proceso</button>

                <button class="btn_finalizar" type="submit" name="accion" value="finalizado"
                    style=" border: solid 2px black;">💾 Finalizar y
                    Archivar</button>

                <a href="en_proceso.php" class="btn_cancelar" style=" border: solid 2px black;">❌
                    Salir</a>
            </div>
        </form>
    </main>

    <script src="../nuevoReportes/carrusel.js"></script>

    <script>
        function validarEnvio(e) {
            const boton = document.activeElement.value;
            const fecha = document.getElementById('fecha_salida').value;

            if (boton === 'finalizado' && fecha === "") {
                alert("¡Atención! Debe ingresar una fecha de salida para poder finalizar el reporte.");
                e.preventDefault();
                return false;
            }
            return true;
        }

        function obtenerDatosVehiculo(idPlaca) {
            if (!idPlaca) {
                document.getElementById('mostrarMarca').value = "";
                document.getElementById('mostrarModelo').value = "";
                return;
            }

            fetch(`../nuevoReportes/buscar_vehiculo_datos.php?id_placa=${idPlaca}`) 
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('mostrarMarca').value = data.marca;
                        document.getElementById('mostrarModelo').value = data.modelo;
                    }
                })
                .catch(err => console.error("Error:", err));
        }

        function enviarRepuestosPorEmail() {
            const lista = document.getElementById('detalles_repuestos').value;

            if (lista.trim() === "") {
                alert("Por favor, escribe los repuestos antes de enviar.");
                return;
            }

            fetch('../API_GMAIL/procesar_envio.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'repuestos=' + encodeURIComponent(lista)
            })
                .then(response => response.text())
                .then(data => {
                    alert("Solicitud enviada con éxito a Elastic Email");
                    console.log(data);
                })
                .catch(error => {
                    alert("Error al conectar con la API");
                    console.log(error);
                });
        }

        function actualizarDetalles(selectElement, nombreRepuesto) {
            const accion = selectElement.value;
            const cuadroTexto = document.getElementById('trabajo_realizado');

            if (accion !== "") {
                const nuevaLinea = `${accion} ${nombreRepuesto}`;

                if (cuadroTexto.value.length > 0) {
                    cuadroTexto.value += ", " + nuevaLinea;
                } else {
                    cuadroTexto.value = nuevaLinea;
                }
            }
        }

        function cargarDatosMaestros() {
            const id = new URLSearchParams(window.location.search).get('id');
            if (!id) return;

            fetch('obtener_datos.php?id=' + id)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('input_id_reporte').value = data.id;
                    
                    document.querySelector('[name="fecha_entrada"]').value = data.fecha_entrada ? data.fecha_entrada.replace(" ", "T") : "";
                    if (data.fecha_salida) document.getElementById('fecha_salida').value = data.fecha_salida.replace(" ", "T");

                    document.getElementById('selectPlaca').value = data.id_placa;
                    document.getElementById('mostrarMarca').value = data.marcas;
                    document.getElementById('mostrarModelo').value = data.modelos;
                    document.querySelector('[name="id_chofer"]').value = data.id_chofer;
                    document.querySelector('[name="id_tipo_trabajo"]').value = data.id_tipo_trabajo;
                    document.querySelector('[name="km_actual"]').value = data.km_actual;
                    document.querySelector('[name="km_prox"]').value = data.km_prox;

                    document.querySelector('[name="falla_detectada"]').value = data.falla_detectada;
                    document.getElementById('trabajo_realizado').value = data.trabajo_realizado;
                    document.querySelector('[name="repuestos"]').value = data.repuestos;
                    document.querySelector('[name="observacion"]').value = data.observacion;
                    document.getElementById('detalles_repuestos').value = data.pedidos;
                });
        }
    </script>
</body>

</html>