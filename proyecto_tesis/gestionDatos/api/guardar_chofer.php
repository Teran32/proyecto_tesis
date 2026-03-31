<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['chofer'])) {
    $nombre = $_POST['chofer'];
    
    $stmt = $pdo->prepare("INSERT INTO choferes (chofer) VALUES (?)");
    if ($stmt->execute([$nombre])) {
        $_SESSION['alerta'] = ['res' => 'ok'];
    } else {
        $_SESSION['alerta'] = ['res' => 'error'];
    }
    $_SESSION['alerta_id'] = uniqid();
    header("Location: ../GestionDatos.php", true, 303);

    
}


?>