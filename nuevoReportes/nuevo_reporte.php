<?php
include '../gestionDatos/conexion.php';
include 'correlativo_año.php';

$placas = $pdo->query("SELECT id_placas, placas FROM placas")->fetchAll(PDO::FETCH_ASSOC);
$marcas = $pdo->query("SELECT id_marcas, marcas FROM marcas")->fetchAll(PDO::FETCH_ASSOC);
$modelos = $pdo->query("SELECT id_modelos, modelos FROM modelos")->fetchAll(PDO::FETCH_ASSOC);
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
        <script>
            Swal.fire({
                title: '¡Éxito!',
                text: 'Reporte guardado exitosamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        </script>
    <?php endif; ?>

    <main class="contenedor_principal">
        <form action="guarda_reporte.php" method="POST" id="formulario_maestro" enctype="multipart/form-data" onsubmit="return validarEnvio(event)">
            <input type="hidden" name="correlativo" value="<?= $nuevo_codigo ?>">
            <section class="tarjeta_form" style="background-color: #f4f4f4; padding: 20px; border-radius: 8px;">
                <h3><i class="icono">🚗</i> Datos de la Unidad</h3>
                <div class="cuadricula">
                    <div class="campo">
                        <label>Fecha/Hora Entrada</label>
                        <input class="input" type="datetime-local" name="fecha_entrada" id="fecha_entrada" value="<?= $fecha_hoy ?>"
                            required>
                    </div>
                    <div class="campo">
                        <label>Fecha/Hora Salida</label>
                        <input class="input" type="datetime-local" name="fecha_salida" id="fecha_salida">
                    </div>
                    <div class="campo">
                        <label>Placa (Unidad)</label>
                        <select id="selectPlaca" name="id_placa" class="input" onchange="gestionarCambioVehiculo('placa', this.value)" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($placas as $p): ?>
                                <option value="<?= $p['id_placas'] ?>"><?= $p['placas'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>






                    <div class="campo">
                        <label>marca</label>
                        <select id="mostrarMarca" name="id_marca" class="input" onchange="gestionarCambioVehiculo('marca', this.value)" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($marcas as $p): ?>
                                <option value="<?= $p['id_marcas'] ?>"><?= $p['marcas'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="campo">
                        <label>modelo</label>
                        <select id="mostrarModelo" name="id_modelo" class="input" onchange="gestionarCambioVehiculo('modelo', this.value)" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($modelos as $p): ?>
                                <option value="<?= $p['id_modelos'] ?>"><?= $p['modelos'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>





                    

                    <div class="campo">
                        <label>Chofer </label>
                        <select name="id_chofer" class="input" >
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
                        <input type="number" name="km_actual" class="input">
                    </div>
                    <div class="campo">
                        <label>Próximo Kilometraje</label>
                        <input type="number" name="km_prox" class="input">
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
                <textarea name="pedidos" rows="2" id="detalles_repuestos" placeholder="Repuestos pendientes por solicitar..."></textarea>
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

<script src="carrusel.js"></script>
<script src="placa_modelo_marca.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>




<script>

const inicio = document.getElementById('fecha_entrada');
const salida = document.getElementById('fecha_salida');

function validarEnvio(e) {
    const boton = e.submitter ? e.submitter.value : document.activeElement.value;
    
    const fechaSalidaVal = salida.value;
    const fechaEntradaVal = inicio.value;

    if (boton === 'en_proceso' && fechaSalidaVal !== "") {
        Swal.fire({
    title: '¡no se puede!',
    text: '¡NO! Un reporte "En Proceso" no debe tener fecha de salida. Por favor, bórrela para continuar.',
    icon: 'error',
    confirmButtonText: 'Aceptar'
});
        e.preventDefault();
        return false;
    }
    
    if (boton === 'finalizado' && fechaSalidaVal === "") {
        Swal.fire({
    title: '¡buenass!',
    text: 'no no no, debe ingresar una fecha de salida para poder finalizar el reporte.',
    icon: 'error',
    confirmButtonText: 'Aceptar'
});
        e.preventDefault();
        return false;
    }

    if (fechaEntradaVal && fechaSalidaVal) {
        const dateEntrada = new Date(fechaEntradaVal);
        const dateSalida = new Date(fechaSalidaVal);

        if (dateSalida <= dateEntrada) {
            Swal.fire({
                title: '¡Error!',
                text: 'La fecha de salida debe ser mayor a la fecha de entrada.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            e.preventDefault();
            return false;
        }
    }

    return true; 
}







// Función auxiliar para limpiar y rellenar selects dinámicamente
function filtrarSelectores(idElemento, opciones) {
    const select = document.getElementById(idElemento);
    select.innerHTML = '<option value="">Seleccione...</option>'; // Limpiar
    
    opciones.forEach(opt => {
        const el = document.createElement('option');
        el.value = opt.id;
        el.textContent = opt.nombre;
        select.appendChild(el);
    });
}





function limpiarFormularioVehiculo() {
    const ids = ['selectPlaca', 'mostrarMarca', 'mostrarModelo'];
    ids.forEach(id => {
        const el = document.getElementById(id);
        if(el) el.value = "";
    });
}


// Función auxiliar para no repetir código de limpieza
function limpiarCamposVehiculo() {
    document.getElementById('selectPlaca').value = "";
    if(document.getElementById('mostrarMarca')) document.getElementById('mostrarMarca').value = "";
    if(document.getElementById('mostrarModelo')) document.getElementById('mostrarModelo').value = "";
}



    function enviarRepuestosPorEmail() {
        const lista = document.getElementById('detalles_repuestos').value;

        if (lista.trim() === "") {
            Swal.fire({
                title: '¡Atención!',
                text: 'Por favor, escribe los repuestos antes de enviar.',
                icon: 'warning',
                confirmButtonText: 'Aceptar'
            });
            console.log("Por favor, escribe los repuestos antes de enviar.");
            return;
        }

        fetch('../API_GMAIL/procesar_envio.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'repuestos=' + encodeURIComponent(lista)
        })
            .then(response => response.text())
            .then(data => {
                Swal.fire({
                    title: '¡Éxito!',
                    text: 'Solicitud enviada con éxito a Elastic Email',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });
                console.log(data);
            })
            .catch(error => {
                Swal.fire({
                    title: '¡Error!',
                    text: 'Error al conectar con la API',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
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