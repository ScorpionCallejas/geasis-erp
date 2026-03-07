<?php
/**
 * SISTEMA SICAM - CONFIGURACIÓN DE CONEXIÓN A BASE DE DATOS
 * Archivo de conexión principal del sistema con configuración de WebSocket
 * 
 * @version 1.0
 * @php_version 5.6+
 */

// ============================================================================
// CONFIGURACIÓN DE ERRORES PHP
// ============================================================================
// Deshabilitar la visualización de errores para evitar contaminar respuestas JSON
ini_set('display_errors', '0');
// Habilitar el registro de errores en log
ini_set('log_errors', '1');

// ============================================================================
// CONFIGURACIÓN DE CONEXIÓN REMOTA - SERVIDOR PRINCIPAL
// ============================================================================

// Parámetros de conexión del servidor remoto
$host = "49.12.79.33";
$port = 3306;
$user = "ericorps_10";
$pass = "Anjunabeats10";
$database = "sicam_ambiente_1";

// ============================================================================
// ESTABLECIMIENTO DE CONEXIÓN PRINCIPAL
// ============================================================================
$db = new mysqli();

// ============================================================================
// CONFIGURACIÓN DE TIMEOUTS (ANTES DE LA CONEXIÓN)
// ============================================================================
// Configurar timeout de conexión (30 segundos)
$db->options(MYSQLI_OPT_CONNECT_TIMEOUT, 30);

// Configurar timeout de lectura (60 segundos) - Solo si está disponible en PHP 5.6
if (defined('MYSQLI_OPT_READ_TIMEOUT')) {
    $db->options(MYSQLI_OPT_READ_TIMEOUT, 60);
}

// Realizar la conexión con los parámetros configurados
$db->real_connect($host, $user, $pass, $database, $port);

// ============================================================================
// VALIDACIÓN DE CONEXIÓN A BASE DE DATOS
// ============================================================================
if ($db->connect_error) {
    // Log del error para debugging
    error_log("SICAM DB ERROR: Conexión fallida - " . $db->connect_error);
    
    // Mensaje de error para el usuario (sin exponer detalles técnicos)
    die("Error de conexión al sistema. Por favor, contacte al administrador.");
}

// ============================================================================
// CONFIGURACIÓN DE CHARSET PARA CARACTERES ESPECIALES Y EMOJIS
// ============================================================================
if (!$db->set_charset("utf8mb4")) {
    error_log("SICAM DB WARNING: Error al establecer charset UTF8MB4 - " . $db->error);
    // Fallback a utf8 si utf8mb4 no está disponible
    if (!$db->set_charset("utf8")) {
        error_log("SICAM DB WARNING: Error al establecer charset UTF-8 - " . $db->error);
    }
}

// ============================================================================
// CONFIGURACIÓN DEL WEBSOCKET
// Configuración para comunicación en tiempo real del sistema
// ============================================================================
$socket = 'wss://socket.ahjende.com/wss/?encoding=text';

// ============================================================================
// LOG DE CONEXIÓN EXITOSA (OPCIONAL - PARA DEBUGGING)
// ============================================================================
// Descomentare la siguiente línea solo para debugging
// error_log("SICAM DB: Conexión establecida exitosamente a " . $database);

// ============================================================================
// CONFIGURACIÓN ADICIONAL DE MYSQL (OPCIONAL)
// ============================================================================
// Configurar timezone si es necesario
// $db->query("SET time_zone = '-06:00'");

// Configurar modo SQL (opcional, para compatibilidad)
// $db->query("SET sql_mode = ''");

// ============================================================================
// FUNCIÓN DE RECONEXIÓN AUTOMÁTICA
// ============================================================================
function verificarYReconectar(&$db, $host, $user, $pass, $database, $port) {
    // Verificar si la conexión está viva
    if (!$db->ping()) {
        // Intentar reconectar
        $db->close();
        $db = new mysqli($host, $user, $pass, $database, $port);
        
        if ($db->connect_error) {
            error_log("SICAM DB ERROR: Reconexión fallida - " . $db->connect_error);
            return false;
        }
        
        // Reconfigurar charset con soporte para emojis
        if (!$db->set_charset("utf8mb4")) {
            error_log("SICAM DB WARNING: Error al establecer charset UTF8MB4 en reconexión - " . $db->error);
            // Fallback a utf8 si utf8mb4 no está disponible
            if (!$db->set_charset("utf8")) {
                error_log("SICAM DB WARNING: Error al establecer charset UTF-8 en reconexión - " . $db->error);
            }
        }
        
        error_log("SICAM DB: Reconexión exitosa a " . $database);
        return true;
    }
    return true;
}

// Variable global para almacenar parámetros de conexión (para reconexión)
$GLOBALS['db_params'] = array(
    'host' => $host,
    'user' => $user,
    'pass' => $pass,
    'database' => $database,
    'port' => $port
);

?>