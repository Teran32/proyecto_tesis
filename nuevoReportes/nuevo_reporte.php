<?php 
// Cargamos placas, choferes y tipos de trabajo para los selectores
include '../gestionDatos/conexion.php'; 
$placas = $pdo->query("SELECT id_placas, placas FROM placas")->fetchAll(PDO::FETCH_ASSOC);
$choferes = $pdo->query("SELECT id_chofer, chofer FROM choferes")->fetchAll(PDO::FETCH_ASSOC);
$tipos = $pdo->query("SELECT id_tipo_trabajo, tipo_trabajo FROM tipo_trabajo")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sertransafal - Reporte Detallado</title>
    <link rel="stylesheet" href="reporte.css">
</head>
<body>
    <header>
        <h1>SERTRANSAFAL</h1>
        <div class="sub_header"> <span>Reporte de Vehículo</span>
            <span id="num_reporte">No. Dinámico</span>
        </div>
    </header>

    <main class="contenedor_principal">
        <form action="guardar_reporte_maestro.php" method="POST" id="formulario_maestro" enctype="multipart/form-data">

            <section class="tarjeta_form" style="background-color: #f4f4f4; padding: 20px; border-radius: 8px;">
                <h3><i class="icono">🚗</i> Datos de la Unidad</h3>
                <div class="cuadricula">
                    <div class="campo">
                        <label>Fecha Entrada</label>
                        <input class="input" type="date" name="fecha_entrada" required>
                    </div>
                    <div class="campo">
                        <label>Fecha Salida</label>
                        <input class="input" type="date" name="fecha_salida">
                    </div>
                    <div class="campo">
                        <label>Placa (Unidad)</label>
                        <select name="id_placa" class="input" required>
                            <option value="">Seleccione...</option>
                            <?php foreach($placas as $p): ?>
                                <option value="<?= $p['id_placas'] ?>"><?= $p['placas'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="campo">
                        <label>Chofer Asignado</label>
                        <select name="id_chofer" class="input" required>
                            <option value="">Seleccione...</option>
                            <?php foreach($choferes as $c): ?>
                                <option value="<?= $c['id_chofer'] ?>"><?= $c['chofer'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="campo">
                        <label>Tipo de Trabajo</label>
                        <select name="id_tipo_trabajo" class="input" required>
                            <option value="">Seleccione...</option>
                            <?php foreach($tipos as $t): ?>
                                <option value="<?= $t['id_tipo_trabajo'] ?>"><?= $t['tipo_trabajo'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="campo">
                        <label>Kilometraje Actual</label>
                        <input type="number" name="km_actual" required>
                    </div>
                    <div class="campo">
                        <label>Próximo Km</label>
                        <input type="number" name="km_prox" required>
                    </div>
                </div>
            </section>

            <section class="tarjeta_form">
                <h3><i class="icono">🔧</i> Informe Técnico</h3>
                <label>Falla Detectada</label>
                <textarea name="falla_detectada" rows="3" required></textarea>

                <label>Trabajo Realizado</label>
                <textarea name="trabajo_realizado" rows="3"></textarea>

                <label>Repuestos Utilizados</label>
                <textarea name="repuestos" rows="3"></textarea>

                <label>Observaciones</label>
                <textarea name="observacion" rows="2"></textarea>

                <label>Pedido de Repuestos</label>
                <textarea name="pedidos" rows="2"></textarea>
            </section>

            <div class="acciones_finales">
                <button class="btn_finalizar" type="submit">💾 Guardar Reporte</button>
                <a href="../InterfazPrincipal.html" class="btn_cancelar">❌ Salir</a>
            </div>
        </form>
    </main>
</body>
</html>