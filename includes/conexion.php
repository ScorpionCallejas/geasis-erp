<?php
	$db = new mysqli("72.62.162.127", "sicam_user", "sicam_pass", "ericorps_gea", 3306);
	$db->set_charset('utf8mb4');

	$socket = 'wss://socket.ahjende.com/wss/?encoding=text';

// ============================================================================
// CONFIGURACIÓN AVANZADA DE TIMEOUTS
// ============================================================================
if (defined('MYSQLI_OPT_CONNECT_TIMEOUT')) {
    $db->options(MYSQLI_OPT_CONNECT_TIMEOUT, 30);
}

if (defined('MYSQLI_OPT_READ_TIMEOUT')) {
    $db->options(MYSQLI_OPT_READ_TIMEOUT, 60);
}

// ============================================================================
// VALIDACIÓN DE CONEXIÓN
// ============================================================================
if ($db->connect_error) {
    error_log("SICAM DB ERROR: Conexión fallida - Error code: " . $db->connect_errno);
    
    if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] !== 'localhost') {
        die("Error de conexión al sistema. Por favor, contacte al administrador.");
    } else {
        die("Error de conexión: " . $db->connect_error);
    }
}

// ============================================================================
// CONFIGURACIÓN DE CHARSET PARA EMOJIS
// ============================================================================
if (!$db->set_charset("utf8mb4")) {
    error_log("SICAM DB WARNING: Error al establecer charset UTF8MB4 - " . $db->error);
    if (!$db->set_charset("utf8")) {
        error_log("SICAM DB WARNING: Error al establecer charset UTF-8 - " . $db->error);
    }
}

// Variable global para parámetros de conexión
$GLOBALS['db_params'] = array(
    'host' => "localhost",
    'user' => "ericorps_10", 
    'pass' => "Anjunabeats10",
    'database' => "ericorps_gea",
    'port' => 3306
);

?>