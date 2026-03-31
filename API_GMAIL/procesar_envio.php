<?php
// procesar_envio.php
require_once '../config_privado.php'; // Aquí están tus llaves seguras

if (isset($_POST['repuestos'])) {
    $lista = $_POST['repuestos'];
    $url = 'https://api.elasticemail.com/v2/email/send';

    $cuerpoHTML = "
    <div style='font-family: Arial, sans-serif; border: 1px solid #ddd; padding: 20px; border-radius: 10px;'>
        <h2 style='color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px;'>
            📦 Nueva Solicitud de Repuestos
        </h2>
        <p><strong>Sertransfal C.A.</strong></p>
        <div style='background-color: #f9f9f9; padding: 15px; border-left: 5px solid #3498db;'>
            <p style='margin: 0; font-weight: bold;'>Lista de Repuestos Solicitados:</p>
            <p style='white-space: pre-line; color: #555;'>" . $lista . "</p>
        </div>
        <p style='font-size: 12px; color: #888; margin-top: 20px;'>
            Este es un correo automático generado desde el módulo de Taller.
        </p>
    </div>
";

$postData = [
    'apikey' => ELASTIC_API_KEY,
    'subject' => "Solicitud de Repuestos - Sertransfal",
    'from' => ELASTIC_EMAIL_SENDER,
    'to' => 'juannqsp32@gmail.com', 
    'bodyHtml' => $cuerpoHTML,
    'isTransactional' => true
];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    echo $response; 
    echo "Respuesta de la API: " . $response;
}
?>