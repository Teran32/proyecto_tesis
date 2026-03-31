<?php
include '../gestionDatos/conexion.php';
include 'correlativo_año.php';

$placas = $pdo->query("SELECT id_placas, placas FROM placas")->fetchAll(PDO::FETCH_ASSOC);
$choferes = $pdo->query("SELECT id_chofer, chofer FROM choferes")->fetchAll(PDO::FETCH_ASSOC);
$tipos = $pdo->query("SELECT id_tipo_trabajo, tipo_trabajo FROM tipo_trabajo")->fetchAll(PDO::FETCH_ASSOC);

$fecha_hoy = date('Y-m-d\TH:i');

$nuevo_codigo = generarCorrelativo($pdo); 
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Sertransfal - Reporte Detallado</title>
    <link rel="stylesheet" href="reporte.css">
</head>

<body>
    <header>
        <h1>SERTRANSAFAL</h1>
        <div class="sub_header">
            <span>Reporte de Vehículo</span>
            <span id="num_reporte">
                <?= $nuevo_codigo ?>
            </span>
            <input type="hidden" name="correlativo" value="<?= $nuevo_codigo ?>" style="border: black solid 2px;">
        </div>
    </header>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <script>alert("¡Reporte Guardado Exitosamente!");</script>
    <?php endif; ?>

    <main class="contenedor_principal">
        <form action="guarda_reporte.php" method="POST" id="formulario_maestro" enctype="multipart/form-data" onsubmit="return validarEnvio(event)">
            <input type="hidden" name="correlativo" value="<?= $nuevo_codigo ?>">
            <section class="tarjeta_form" style="background-color: #f4f4f4; padding: 20px; border-radius: 8px;">
                <h3><i class="icono">🚗</i> Datos de la Unidad</h3>
                <div class="cuadricula">
                    <div class="campo">
                        <label>Fecha/Hora Entrada</label>
                        <input class="input" type="datetime-local" name="fecha_entrada" value="<?= $fecha_hoy ?>"
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
                        <span class="selector" style="font-size: 12px;">cables de buejias</span>
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
                <textarea name="falla_detectada" rows="3" required
                    placeholder="Describa la falla reportada..."></textarea>

                <label>Trabajo Realizado</label>
                <textarea name="trabajo_realizado" rows="3" id="trabajo_realizado"
                    placeholder="Detalle las reparaciones efectuadas..."></textarea>

                <label>Repuestos Utilizados</label>
                <textarea name="repuestos" rows="3" placeholder="Lista de repuestos instalados..."></textarea>

                <label>Observaciones</label>
                <textarea name="observacion" rows="2" placeholder="Notas adicionales..."></textarea>

                <label>Solicitud de Repuestos</label>
                <textarea name="pedidos" rows="2" id="detalles_repuestos"
                    placeholder="Repuestos pendientes por solicitar..."></textarea>
                <button type="button" onclick="enviarRepuestosPorEmail()"
                    style="background-color: #0275d8; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer; margin-top: 10px;">
                    <i>📧</i> Enviar Solicitud por Correo (API)
                </button>
            </section>

            <!--para agregalas las fotos de los vehiculos si es necesario -->
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

                        <div class="carruselFotos" id="galeriaDinamica">
                            <div class="noFotos" id="placeholderTexto">
                                <p>Las fotos que añadas aparecerán aquí para revisión</p>
                            </div>
                        </div>
                    </div>
                    <div id="modalVisor" class="modal-foto" onclick="cerrarVisor()">
                        <span class="cerrar-modal">&times;</span>
                        <img id="imgGrande" src="">
                    </div>
                </div>
            </section>

            <div class="acciones_finales">
                <button class="btn_finalizar" type="submit" name="accion" value="en_proceso">en
                    Proceso</button>
                <button class="btn_finalizar" type="submit" name="accion" value="finalizado">💾
                    Guardar
                    Reporte</button>
                <a href="../InterfazPrincipal.php" class="btn_cancelar">❌ Salir</a>
            </div>

        </form>
    </main>


</body>

</html>


<script src="carrusel.js"></script>

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



    // Lógica para autocompletar Marca y elo al cambiar la Placa
    function obtenerDatosVehiculo(idPlaca) {
        if (!idPlaca) {
            document.getElementById('mostrarMarca').value = "";
            document.getElementById('mostrarModelo').value = "";
            return;
        }

        fetch(`buscar_vehiculo_datos.php?id_placa=${idPlaca}`)
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


</script>