<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre_completo']);
    $cedula = strtoupper(trim($_POST['cedula']));

    if (!empty($nombre) && !empty($cedula)) {
        try {
            $sql = "INSERT INTO choferes (nombre_completo, cedula) VALUES (?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $cedula]);
            
            // Regresa a la página de gestión con éxito
            header("Location: GestionDatos.php?status=chofer_ok");
        } catch (PDOException $e) {
            // Error si la cédula ya existe
            header("Location: GestionDatos.php?status=error_cedula");
        }
    }
}
?>