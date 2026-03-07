<?php
/**
 * Script para registrar sesiones de ejecutivos en SICAM
 * Se debe incluir en el proceso de login de ejecutivos
 */

require_once 'conexion.php';

/**
 * Registra una nueva sesión de ejecutivo y actualiza su último acceso
 * @param int $id_ejecutivo ID del ejecutivo que inicia sesión
 * @return bool True si se registró correctamente (ambas operaciones exitosas)
 */
function registrarSesionEjecutivo($id_ejecutivo) {
    global $db; // Usar la conexión PDO global
    
    try {
        // Obtener información adicional de la sesión - COMPATIBLE CON PHP 5.6
        $ip_sesion = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown';
        
        // Si viene de un proxy, obtener la IP real
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_sesion = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['HTTP_X_REAL_IP'])) {
            $ip_sesion = $_SERVER['HTTP_X_REAL_IP'];
        }
        
        // Comenzar transacción para asegurar consistencia
        $db->beginTransaction();
        
        try {
            // 1. Insertar registro de sesión usando PDO
            $sql = "INSERT INTO sesiones_ejecutivo (id_eje, fecha_sesion, ip_sesion, user_agent) 
                    VALUES (:id_ejecutivo, NOW(), :ip_sesion, :user_agent)";
            
            $stmt = $db->prepare($sql);
            $resultado = $stmt->execute([
                ':id_ejecutivo' => $id_ejecutivo,
                ':ip_sesion' => $ip_sesion,
                ':user_agent' => $user_agent
            ]);
            
            if (!$resultado) {
                throw new Exception("Error al insertar sesión: " . implode(', ', $db->errorInfo()));
            }
            
            // 2. Actualizar último acceso del ejecutivo
            $sql_update = "UPDATE ejecutivo SET ult_eje = NOW() WHERE id_eje = :id_ejecutivo";
            $stmt_update = $db->prepare($sql_update);
            $resultado_update = $stmt_update->execute([':id_ejecutivo' => $id_ejecutivo]);
            
            if (!$resultado_update) {
                throw new Exception("Error al actualizar último acceso: " . implode(', ', $db->errorInfo()));
            }
            
            // Confirmar transacción
            $db->commit();
            
            // Log opcional para depuración - MEJORADO CON INTERPOLACIÓN DE STRINGS
            error_log("Sesión registrada y último acceso actualizado para ejecutivo ID: $id_ejecutivo desde IP: $ip_sesion");
            return true;
            
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $db->rollback();
            error_log("Error en transacción de sesión para ejecutivo ID: " . $id_ejecutivo . " - " . $e->getMessage());
            return false;
        }
        
    } catch (Exception $e) {
        error_log("Error en registrarSesionEjecutivo: " . $e->getMessage());
        return false;
    }
}


/**
 * Obtener estadísticas básicas de sesiones
 * @param int $id_ejecutivo ID del ejecutivo (opcional)
 * @param string $fecha_inicio Fecha de inicio (opcional)
 * @param string $fecha_fin Fecha de fin (opcional)
 * @return array Estadísticas de sesiones
 */
function obtenerEstadisticasSesiones($id_ejecutivo = null, $fecha_inicio = null, $fecha_fin = null) {
    global $db; // Usar la conexión PDO global
    
    try {
        $condiciones = [];
        $parametros = [];
        
        if ($id_ejecutivo) {
            $condiciones[] = "se.id_eje = :id_ejecutivo";
            $parametros[':id_ejecutivo'] = $id_ejecutivo;
        }
        
        if ($fecha_inicio) {
            $condiciones[] = "DATE(se.fecha_sesion) >= :fecha_inicio";
            $parametros[':fecha_inicio'] = $fecha_inicio;
        }
        
        if ($fecha_fin) {
            $condiciones[] = "DATE(se.fecha_sesion) <= :fecha_fin";
            $parametros[':fecha_fin'] = $fecha_fin;
        }
        
        $where = empty($condiciones) ? "" : "WHERE " . implode(" AND ", $condiciones);
        
        $sql = "SELECT 
                    COUNT(*) as total_sesiones,
                    COUNT(DISTINCT se.id_eje) as ejecutivos_unicos,
                    COUNT(DISTINCT DATE(se.fecha_sesion)) as dias_activos,
                    MIN(se.fecha_sesion) as primera_sesion,
                    MAX(se.fecha_sesion) as ultima_sesion
                FROM sesiones_ejecutivo se
                INNER JOIN ejecutivo e ON se.id_eje = e.id_eje
                $where";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($parametros);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (Exception $e) {
        error_log("Error en obtenerEstadisticasSesiones: " . $e->getMessage());
        return [
            'total_sesiones' => 0,
            'ejecutivos_unicos' => 0,
            'dias_activos' => 0,
            'primera_sesion' => null,
            'ultima_sesion' => null
        ];
    }
}

/**
 * Obtener información del último acceso de un ejecutivo
 * @param int $id_ejecutivo ID del ejecutivo
 * @return array|false Información del último acceso o false si no existe
 */
function obtenerUltimoAcceso($id_ejecutivo) {
    global $db; // Usar la conexión PDO global
    
    try {
        $sql = "SELECT 
                    e.nom_eje,
                    e.ult_eje as ultimo_acceso,
                    se.ip_sesion,
                    se.user_agent,
                    TIMESTAMPDIFF(MINUTE, e.ult_eje, NOW()) as minutos_desde_ultimo_acceso,
                    TIMESTAMPDIFF(HOUR, e.ult_eje, NOW()) as horas_desde_ultimo_acceso,
                    TIMESTAMPDIFF(DAY, e.ult_eje, NOW()) as dias_desde_ultimo_acceso
                FROM ejecutivo e
                LEFT JOIN sesiones_ejecutivo se ON e.id_eje = se.id_eje 
                    AND se.fecha_sesion = e.ult_eje
                WHERE e.id_eje = :id_ejecutivo
                    AND e.eli_eje = 1";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([':id_ejecutivo' => $id_ejecutivo]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (Exception $e) {
        error_log("Error en obtenerUltimoAcceso: " . $e->getMessage());
        return false;
    }
}

/**
 * Limpiar sesiones antiguas (mantener solo últimos 90 días)
 * Esta función se puede llamar periódicamente para mantener la tabla optimizada
 */
function limpiarSesionesAntiguas() {
    global $db; // Usar la conexión PDO global
    
    try {
        $sql = "DELETE FROM sesiones_ejecutivo 
                WHERE fecha_sesion < DATE_SUB(NOW(), INTERVAL 90 DAY)";
        
        $stmt = $db->prepare($sql);
        $resultado = $stmt->execute();
        
        $eliminadas = $stmt->rowCount();
        error_log("Limpieza de sesiones: $eliminadas registros eliminados");
        
        return $eliminadas;
        
    } catch (Exception $e) {
        error_log("Error en limpiarSesionesAntiguas: " . $e->getMessage());
        return false;
    }
}

// Si se llama directamente con parámetros POST (para AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    header('Content-Type: application/json');
    
    switch ($_POST['accion']) {
        case 'registrar_sesion':
            if (isset($_POST['id_ejecutivo'])) {
                $resultado = registrarSesionEjecutivo($_POST['id_ejecutivo']);
                echo json_encode(['success' => $resultado]);
            } else {
                echo json_encode(['success' => false, 'error' => 'ID ejecutivo requerido']);
            }
            break;
            
        case 'obtener_estadisticas':
            $id_ejecutivo = isset($_POST['id_ejecutivo']) ? $_POST['id_ejecutivo'] : null;
            $fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
            $fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;
            
            $estadisticas = obtenerEstadisticasSesiones($id_ejecutivo, $fecha_inicio, $fecha_fin);
            echo json_encode(['success' => true, 'data' => $estadisticas]);
            break;
            
        case 'obtener_ultimo_acceso':
            if (isset($_POST['id_ejecutivo'])) {
                $ultimo_acceso = obtenerUltimoAcceso($_POST['id_ejecutivo']);
                if ($ultimo_acceso !== false) {
                    echo json_encode(['success' => true, 'data' => $ultimo_acceso]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'No se encontró información del ejecutivo']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'ID ejecutivo requerido']);
            }
            break;
            
        case 'limpiar_antiguas':
            $eliminadas = limpiarSesionesAntiguas();
            echo json_encode(['success' => true, 'eliminadas' => $eliminadas]);
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Acción no válida']);
    }
    exit;
}

// ============================================================================
// MEJORAS AGREGADAS DESDE LA CARPETA INCLUDES - NUEVA CARACTERÍSTICA
// ============================================================================

/*
 * Las siguientes mejoras han sido integradas desde la versión de includes:
 * 
 * 1. SINTAXIS COMPATIBLE CON PHP 5.6:
 *    - Uso del operador ternario isset() para compatibilidad con PHP 5.6
 *    - Sintaxis de arrays corta [] (compatible desde PHP 5.4)
 *    - Interpolación de strings mejorada pero compatible
 * 
 * 2. CORRECCIÓN DE ERRORES:
 *    - Reparado el break faltante en el caso 'obtener_estadisticas'
 *    - Mejores mensajes de log con interpolación de variables
 * 
 * 3. COMPATIBILIDAD GARANTIZADA:
 *    - 100% compatible con PHP 5.6+
 *    - Mantiene la funcionalidad original intacta
 *    - Mejora la legibilidad del código sin romper compatibilidad
 * 
 * Estas mejoras hacen el código más robusto y mantenible sin romper 
 * la funcionalidad existente ni requerir versiones nuevas de PHP.
 */

?>
