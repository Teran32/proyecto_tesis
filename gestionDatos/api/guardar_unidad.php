<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_modelo = $_POST['id_modelo'];
    $placa = strtoupper(trim($_POST['placa']));

    try {
        // 1. VALIDACIÓN
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM placas WHERE placas = ?");
        $stmtCheck->execute([$placa]);
        $existePlaca = $stmtCheck->fetchColumn();

        if ($existePlaca > 0) {
            $_SESSION['alerta'] = ['res' => 'duplicado', 'placa' => $placa];
            $_SESSION['alerta_id'] = uniqid(); // Nuevo ID para esta alerta específica
            header("Location: ../GestionDatos.php", true, 303);
            exit();
        }

        // 2. INSERCIÓN
        $stmtP = $pdo->prepare("INSERT INTO placas (id_modelos, placas) VALUES (?, ?)");
        $stmtP->execute([$id_modelo, $placa]);

        $_SESSION['alerta'] = ['res' => 'ok'];
        $_SESSION['alerta_id'] = uniqid();
        header("Location: ../GestionDatos.php", true, 303);
        
    } catch (Exception $e) {
        $_SESSION['alerta'] = ['res' => 'error', 'msg' => $e->getMessage()];
        header("Location: ../GestionDatos.php");
    }
}
?>