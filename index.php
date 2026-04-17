<?php
// Iniciamos la sesión para guardar si el usuario está autenticado
session_start();


// Credenciales válidas (puedes cambiarlas aquí)
$USUARIO_VALIDO   = "admin";
$CONTRASENA_VALIDA = "sertransfal2026";

$error = ""; // Mensaje de error vacío por defecto

// Cuando el usuario envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario   = trim($_POST['usuario'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    if ($usuario === $USUARIO_VALIDO && $contrasena === $CONTRASENA_VALIDA) {

        // Credenciales correctas → guardar en sesión y redirigir
        $_SESSION['autenticado'] = true;
        $_SESSION['usuario']     = $usuario;
        header("Location: InterfazPrincipal.php");
        exit();
    } else {
        // Credenciales incorrectas → mostrar error
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertransfal – Iniciar Sesión</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
</head>

<body>

    <div class="tarjeta">

        <!-- Encabezado con logo de texto -->
        <div class="encabezado">
            <h1>SERTRANSFAL</h1>
            <p>Sistema de Gestión Vehicular</p>
        </div>

        <!-- Mensaje de error (solo aparece si hubo fallo) -->
        <?php if ($error): ?>
            <div class="error">⚠️ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Formulario de login -->
        <form method="POST" action="">

            <div class="grupo">
                <label for="usuario">Usuario</label>
                <input
                    type="text"
                    id="usuario"
                    name="usuario"
                    placeholder="Ingresa tu usuario"
                    autocomplete="username"
                    required>
            </div>

            <div class="grupo">
                <label for="contrasena">Contraseña</label>
                <input
                    type="password"
                    id="contrasena"
                    name="contrasena"
                    placeholder="••••••••"
                    autocomplete="current-password"
                    required>
            </div>

            <button type="submit" class="btn-ingresar">Ingresar</button>

        </form>

        <div class="pie">Sertransfal © 2026</div>
    </div>

</body>
</html>
