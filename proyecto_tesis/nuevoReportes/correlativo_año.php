<?php
function generarCorrelativo($pdo)
{
    $anio = date("Y");
    $prefijo = "COR-" . $anio . "-";

    $sql = "SELECT MAX(correlativo) as ultimo FROM reportes WHERE correlativo LIKE :prefijo";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['prefijo' => $prefijo . '%']);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado['ultimo']) {

        $numero_str = str_replace($prefijo, "", $resultado['ultimo']);
        $proximo_id = (int) $numero_str + 1;
    } else {
        $proximo_id = 1;
    }

    $numero_con_ceros = str_pad($proximo_id, 3, "0", STR_PAD_LEFT);

    return $prefijo . $numero_con_ceros;
}
?>