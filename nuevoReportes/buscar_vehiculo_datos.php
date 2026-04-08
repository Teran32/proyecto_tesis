<?php
include '../gestionDatos/conexion.php';
header('Content-Type: application/json');

$tipo = $_GET['tipo'] ?? 'placa';
$valor = $_GET['valor'] ?? '';

try {
    if ($tipo == 'placa') {
        // Rellenado total: Obtenemos los IDs de todo el árbol
        $sql = "SELECT p.id_placas, ma.id_marcas, ma.marcas, mo.id_modelos, mo.modelos 
                FROM placas p
                JOIN modelos mo ON p.id_modelos = mo.id_modelos
                JOIN marcas ma ON mo.id_marcas = ma.id_marcas
                WHERE p.id_placas = ? LIMIT 1";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$valor]);
        $v = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => !!$v,
            'modo' => 'rellenar',
            'id_placa' => $v['id_placas'] ?? null,
            'id_marca' => $v['id_marcas'] ?? null,
            'id_modelo' => $v['id_modelos'] ?? null,
            'marca' => $v['marcas'] ?? null,
            'modelo' => $v['modelos'] ?? null
        ]);

    } else {
        // Filtrado: Obtenemos las listas dependientes
        $modelos = [];
        $placas = [];
        $id_marca_perteneciente = null;

        if ($tipo == 'marca') {
            // Si elijo marca, busco sus modelos y sus placas
            $sql = "SELECT p.id_placas, p.placas as nro, mo.id_modelos, mo.modelos 
                    FROM modelos mo
                    LEFT JOIN placas p ON p.id_modelos = mo.id_modelos
                    WHERE mo.id_marcas = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$valor]);
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($res as $r) {
                $modelos[$r['id_modelos']] = $r['modelos'];
                if($r['id_placas']) $placas[$r['id_placas']] = $r['nro'];
            }
        } 
        elseif ($tipo == 'modelo') {
            // Si elijo modelo, busco su marca (para rellenar) y sus placas (para filtrar)
            $sql = "SELECT p.id_placas, p.placas as nro, mo.id_marcas 
                    FROM modelos mo
                    LEFT JOIN placas p ON p.id_modelos = mo.id_modelos
                    WHERE mo.id_modelos = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$valor]);
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($res as $r) {
                $id_marca_perteneciente = $r['id_marcas'];
                if($r['id_placas']) $placas[$r['id_placas']] = $r['nro'];
            }
        }

        echo json_encode([
            'success' => true,
            'modo' => 'filtrar',
            'modelos' => $modelos,
            'placas' => $placas,
            'id_marca' => $id_marca_perteneciente // Solo viene si elegimos modelo
        ]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}