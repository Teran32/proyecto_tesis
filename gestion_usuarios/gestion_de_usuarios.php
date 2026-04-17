<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios | Sertransfal</title>
    <link rel="stylesheet" href="gestion_usuarios.css">
</head>
<body>
    <div class="container">
        <header class="main-header">
            <h1>Administración de Usuarios</h1>
            <p>Panel de control para gestión de acceso y roles del personal.</p>
            <button id="btn_volver" class="btn_volver">volver</button>
        </header>

        <section class="form-section">
            <form action="controlador/agregar_usuario.php" method="POST" class="user-form">
                <div class="form-group">
                    <label for="nombre">Nombre Completo</label>
                    <input type="text" id="nombre" name="nombre" required placeholder="Ej: Juan Teran">
                </div>
                <div class="form-group">
                    <label for="usuario">Nombre de Usuario</label>
                    <input type="text" id="usuario" name="usuario" required placeholder="jteran32">
                </div>
                <div class="form-group">
                    <label for="rol">Rol de Sistema</label>
                    <select id="rol" name="rol" required>
                        <option value="" disabled selected>Seleccione un rol</option>
                        <option value="admin">Administrador (Acceso Total)</option>
                        <option value="usuario">Usuario (Consulta y Reportes)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn-primary">Registrar Usuario</button>
            </form>
        </section>

        <section class="table-section">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>01</td>
                        <td>Administrador Sistema</td>
                        <td>admin_sertransfal</td>
                        <td><span class="badge admin">Administrador</span></td>
                        <td>Activo</td>
                        <td>
                            <a href="#" class="action-link">Editar</a>
                            <a href="#" class="action-link delete">Inhabilitar</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>

<script>
    const volver = document.querySelector('.btn_volver');
    volver.addEventListener('click', () => {
        window.location.href = '../InterfazPrincipal.php';
    });
</script>