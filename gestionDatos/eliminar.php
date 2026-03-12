<?php
include 'conexion.php';

//ESTO ES PARA ELIMINAR UN VEHICULO y choferes 

//eliminar vehiculo de la liista 
$tipo = $_GET['tipo'] ?? null;
$id = $_GET['id'] ?? null;

if ($tipo && $id) {
    try {
        if ($tipo == 'vehiculo') {
            $stmt = $pdo->prepare("DELETE FROM placas WHERE id_placas = ?");
            $stmt->execute([$id]);
        } elseif ($tipo == 'chofer') {
            $stmt = $pdo->prepare("DELETE FROM choferes WHERE id_chofer = ?");
            $stmt->execute([$id]);
        }
        $_SESSION['alerta'] = ['res' => 'ok_del'];
    } catch (Exception $e) {
        $_SESSION['alerta'] = ['res' => 'error_del'];
    }
    $_SESSION['alerta_id'] = uniqid();
    header("Location: GestionDatos.php", true, 303);
} else {
    header("Location: GestionDatos.php", true, 303);
}
?>
