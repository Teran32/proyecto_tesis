<?php
// 1. Conexión y Librerías
include '../gestionDatos/conexion.php';
require_once '../librerias/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// 2. Obtener el ID del reporte desde la URL
$id_reporte = $_GET['id'] ?? null;

if (!$id_reporte) {
    die("Error: No se proporcionó un ID de reporte válido.");
}

// 3. Consultar TODA la información del reporte
// Unimos las tablas para tener nombres de choferes, placas, marcas, etc.
$sql = "SELECT r.*, p.placas, ma.marcas, mo.modelos, c.chofer, t.tipo_trabajo
        FROM reportes r
        JOIN placas p ON r.id_placa = p.id_placas
        JOIN modelos mo ON p.id_modelos = mo.id_modelos
        JOIN marcas ma ON mo.id_marcas = ma.id_marcas
        JOIN choferes c ON r.id_chofer = c.id_chofer
        JOIN tipo_trabajo t ON r.id_tipo_trabajo = t.id_tipo_trabajo
        WHERE r.id = :id";

$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id_reporte]);
$r = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$r) {
    die("Error: El reporte no existe en la base de datos.");
}

// 4. Configurar Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);

// 5. Diseño del HTML (Lo que se verá en el PDF)
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #1e293b; padding-bottom: 10px; margin-bottom: 20px; }
        .title { font-size: 20px; font-weight: bold; color: #1e293b; margin: 0; }
        .correlativo { font-size: 14px; color: #e11d48; font-weight: bold; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f1f5f9; text-align: left; padding: 8px; border: 1px solid #cbd5e1; }
        td { padding: 8px; border: 1px solid #cbd5e1; }
        
        .seccion-titulo { background: #1e293b; color: white; padding: 5px 10px; font-weight: bold; margin-top: 10px; }
        .contenido { padding: 10px; border: 1px solid #cbd5e1; background: #fafafa; min-height: 40px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">SERTRANSFAL</p>
        <p>REPORTE TÉCNICO DE MANTENIMIENTO VEHICULAR</p>
        <span class="correlativo">No. ' . $r['correlativo'] . '</span>
    </div>

    <table>
        <tr>
            <th>Fecha Entrada:</th><td>' . date('d/m/Y H:i', strtotime($r['fecha_entrada'])) . '</td>
            <th>Fecha Salida:</th><td>' . ($r['fecha_salida'] ? date('d/m/Y H:i', strtotime($r['fecha_salida'])) : 'N/A') . '</td>
        </tr>
        <tr>
            <th>Vehículo:</th><td>' . $r['marcas'] . ' ' . $r['modelos'] . '</td>
            <th>Placa:</th><td><b>' . $r['placas'] . '</b></td>
        </tr>
        <tr>
            <th>Chofer:</th><td>' . $r['chofer'] . '</td>
            <th>Tipo Trabajo:</th><td>' . $r['tipo_trabajo'] . '</td>
        </tr>
        <tr>
            <th>KM Actual:</th><td>' . number_format($r['km_actual']) . '</td>
            <th>KM Próximo:</th><td>' . number_format($r['km_prox']) . '</td>
        </tr>
    </table>

    <div class="seccion-titulo">FALLA DETECTADA</div>
    <div class="contenido">' . nl2br($r['falla_detectada']) . '</div>

    <div class="seccion-titulo">TRABAJO REALIZADO</div>
    <div class="contenido">' . nl2br($r['trabajo_realizado']) . '</div>

    <div class="seccion-titulo">REPUESTOS UTILIZADOS</div>
    <div class="contenido">' . nl2br($r['repuestos']) . '</div>

    <div class="seccion-titulo">OBSERVACIONES</div>
    <div class="contenido">' . nl2br($r['observacion']) . '</div>

    <div class="footer">
        Documento generado automáticamente por el Sistema Sertransfal - ' . date('d/m/Y H:i') . '
    </div>
</body>
</html>';

// 6. Renderizar y lanzar
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Attachment => false hace que se abra en el navegador en vez de descargarse directo
$dompdf->stream("Reporte_" . $r['correlativo'] . ".pdf", array("Attachment" => false));