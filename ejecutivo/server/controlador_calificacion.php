<?php
/**
 * CONTROLADOR DE CALIFICACIONES
 * server/controlador_calificacion.php
 * 
 * Acciones: obtener, guardar
 * VERSIÓN 1.0
 */

header('Content-Type: application/json; charset=utf-8');

require('../inc/cabeceras.php');
require('../inc/funciones.php');

// Respuesta por defecto
$response = ['success' => false, 'mensaje' => 'Acción no especificada'];

// Obtener acción
$accion = isset($_POST['accion']) ? $_POST['accion'] : '';
$id_alu_ram = isset($_POST['id_alu_ram']) ? mysqli_real_escape_string($db, $_POST['id_alu_ram']) : '';

// Validar id_alu_ram
if (empty($id_alu_ram)) {
    echo json_encode(['success' => false, 'mensaje' => 'ID de alumno no proporcionado']);
    exit;
}

switch ($accion) {
    
    // ==================== OBTENER CALIFICACIONES ====================
    case 'obtener':
        
        $sql = "
            SELECT 
                c.id_cal,
                c.id_mat4,
                c.id_alu_ram2,
                c.ext_cal,
                c.fin_cal,
                m.id_mat,
                m.nom_mat,
                m.cic_mat
            FROM calificacion c
            INNER JOIN materia m ON m.id_mat = c.id_mat4
            WHERE c.id_alu_ram2 = '$id_alu_ram'
            ORDER BY m.cic_mat ASC, m.nom_mat ASC
        ";
        
        $resultado = mysqli_query($db, $sql);
        
        if (!$resultado) {
            echo json_encode([
                'success' => false, 
                'mensaje' => 'Error en consulta: ' . mysqli_error($db)
            ]);
            exit;
        }
        
        $calificaciones = [];
        
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $calificaciones[] = [
                'id_cal' => $fila['id_cal'],
                'id_mat' => $fila['id_mat'],
                'nom_mat' => $fila['nom_mat'],
                'cic_mat' => $fila['cic_mat'],
                'ext_cal' => $fila['ext_cal'],
                'fin_cal' => $fila['fin_cal']
            ];
        }
        
        $response = [
            'success' => true,
            'calificaciones' => $calificaciones,
            'total' => count($calificaciones)
        ];
        
        break;
    
    // ==================== GUARDAR CALIFICACIONES ====================
    case 'guardar':
        
        $cambios_json = isset($_POST['cambios']) ? $_POST['cambios'] : '';
        
        if (empty($cambios_json)) {
            echo json_encode(['success' => false, 'mensaje' => 'No hay cambios para guardar']);
            exit;
        }
        
        $cambios = json_decode($cambios_json, true);
        
        if (!is_array($cambios) || empty($cambios)) {
            echo json_encode(['success' => false, 'mensaje' => 'Formato de cambios inválido']);
            exit;
        }
        
        $errores = [];
        $actualizados = 0;
        
        foreach ($cambios as $id_cal => $campos) {
            $id_cal = mysqli_real_escape_string($db, $id_cal);
            
            // Construir SET dinámico
            $sets = [];
            
            if (isset($campos['ext_cal'])) {
                $ext_cal = $campos['ext_cal'] === '' ? 'NULL' : "'" . mysqli_real_escape_string($db, $campos['ext_cal']) . "'";
                $sets[] = "ext_cal = $ext_cal";
            }
            
            if (isset($campos['fin_cal'])) {
                $fin_cal = $campos['fin_cal'] === '' ? 'NULL' : "'" . mysqli_real_escape_string($db, $campos['fin_cal']) . "'";
                $sets[] = "fin_cal = $fin_cal";
            }
            
            if (!empty($sets)) {
                $sql_update = "UPDATE calificacion SET " . implode(', ', $sets) . " WHERE id_cal = '$id_cal'";
                
                if (mysqli_query($db, $sql_update)) {
                    $actualizados++;
                } else {
                    $errores[] = "Error en id_cal $id_cal: " . mysqli_error($db);
                }
            }
        }
        
        if (count($errores) > 0) {
            $response = [
                'success' => false,
                'mensaje' => 'Algunos cambios no se guardaron: ' . implode(', ', $errores),
                'actualizados' => $actualizados,
                'errores' => count($errores)
            ];
        } else {
            $response = [
                'success' => true,
                'mensaje' => 'Calificaciones actualizadas correctamente',
                'actualizados' => $actualizados
            ];
        }
        
        break;
    
    default:
        $response = ['success' => false, 'mensaje' => 'Acción no reconocida: ' . $accion];
        break;
}

echo json_encode($response);


?>