<?php
include '../conexion.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo = $_POST['tipo'] ?? '';
    
    try {
        if ($tipo === 'vehiculo') {
            $id = $_POST['id_placas'] ?? null;
            $placa = strtoupper(trim($_POST['placa']));
            $id_modelos = $_POST['id_modelos'] ?? null;
            $nombre_modelo = trim($_POST['nombre_modelo']);
            $id_marcas = $_POST['id_marcas'] ?? null;
            $nombre_marca = trim($_POST['nombre_marca']);
            
            if ($id && $placa && $id_modelos && $nombre_modelo && $id_marcas && $nombre_marca) {
                // Verificar placa duplicada en otro registro
                $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM placas WHERE placas = ? AND id_placas != ?");
                $stmtCheck->execute([$placa, $id]);
                if ($stmtCheck->fetchColumn() > 0) {
                    echo json_encode(['success' => false, 'msg' => 'La placa ya está registrada en otro vehículo']);
                    exit;
                }
                
                $pdo->beginTransaction();
                // 1. Modificar Placa
                $stmt = $pdo->prepare("UPDATE placas SET placas = ? WHERE id_placas = ?");
                $stmt->execute([$placa, $id]);
                // 2. Modificar Modelo
                $stmt2 = $pdo->prepare("UPDATE modelos SET modelos = ? WHERE id_modelos = ?");
                $stmt2->execute([$nombre_modelo, $id_modelos]);
                // 3. Modificar Marca
                $stmt3 = $pdo->prepare("UPDATE marcas SET marcas = ? WHERE id_marcas = ?");
                $stmt3->execute([$nombre_marca, $id_marcas]);
                $pdo->commit();
                
                $_SESSION['alerta'] = ['res' => 'ok'];
                $_SESSION['alerta_id'] = uniqid();
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'msg' => 'Faltan datos requeridos para actualizar']);
            }
        } elseif ($tipo === 'chofer') {
            $id = $_POST['id_chofer'] ?? null;
            $nombre = trim($_POST['nombre'] ?? '');
            
            if ($id && $nombre) {
                $stmt = $pdo->prepare("UPDATE choferes SET chofer = ? WHERE id_chofer = ?");
                $stmt->execute([$nombre, $id]);
                
                $_SESSION['alerta'] = ['res' => 'ok'];
                $_SESSION['alerta_id'] = uniqid();
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'msg' => 'Faltan datos requeridos']);
            }
        } elseif ($tipo === 'marca') {
            $id = $_POST['id_marca'] ?? null;
            $nombre = trim($_POST['nombre'] ?? '');
            
            if ($id && $nombre) {
                $stmt = $pdo->prepare("UPDATE marcas SET marcas = ? WHERE id_marcas = ?");
                $stmt->execute([$nombre, $id]);
                
                $_SESSION['alerta'] = ['res' => 'ok'];
                $_SESSION['alerta_id'] = uniqid();
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'msg' => 'Faltan datos requeridos']);
            }
        } elseif ($tipo === 'modelo') {
            $id = $_POST['id_modelo'] ?? null;
            $nombre = trim($_POST['nombre'] ?? '');
            $id_marca = $_POST['id_marca'] ?? null;
            
            if ($id && $nombre && $id_marca) {
                $stmt = $pdo->prepare("UPDATE modelos SET modelos = ?, id_marcas = ? WHERE id_modelos = ?");
                $stmt->execute([$nombre, $id_marca, $id]);
                
                $_SESSION['alerta'] = ['res' => 'ok'];
                $_SESSION['alerta_id'] = uniqid();
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'msg' => 'Faltan datos requeridos']);
            }
        } else {
            echo json_encode(['success' => false, 'msg' => 'Tipo no válido']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'msg' => $e->getMessage()]);
    }
}
?>
