<?php

//conexion a la base de datos pero esto es para traerlos a la tabla que parce una lista
include '../gestionDatos/conexion.php'; 


$sql = "SELECT r.id, r.fecha_entrada, p.placas, c.chofer, r.falla_detectada 
        FROM reportes r
        JOIN placas p ON r.id_placa = p.id_placas
        JOIN choferes c ON r.id_chofer = c.id_chofer
        WHERE r.estado = 0 
        ORDER BY r.fecha_entrada DESC";

$reportes = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<a href="editar_reporte.php?id=<?= $r['id'] ?>">Editar / Finalizar</a>
