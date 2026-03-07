<?php
// ====================================
// 🎯 CONTROLADOR DE CARRUSEL - ALUMNO
// ====================================
// Versión: PHP 5.6
// Propósito: Servir carruseles al alumno según jerarquía (Generación → Plantel → Cadena)
// ====================================

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// 🔒 INCLUDE CABECERAS (ya tiene sesión iniciada y $db)
require('../inc/cabeceras.php');

// ====================================
// 🛠️ FUNCIONES AUXILIARES
// ====================================

/**
 * Sanitiza input para prevenir inyección SQL
 */
function sanitize_input($data, $db) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($db, $data);
}

/**
 * Formatea un registro de carrusel al formato esperado por el frontend
 */
function formatear_carrusel($row) {
    return array(
        'id' => (string)$row['id_car'],
        'img_car' => $row['img_car'],
        'tit_car' => !empty($row['tit_car']) ? $row['tit_car'] : null,
        'des_car' => !empty($row['des_car']) ? $row['des_car'] : null,
        'url_car' => !empty($row['url_car']) ? $row['url_car'] : null
    );
}

/**
 * Log de debugging (solo en desarrollo)
 */
function debug_log($data, $label = 'DEBUG') {
    error_log("[$label] " . json_encode($data, JSON_UNESCAPED_UNICODE));
}

// ====================================
// 🔐 VALIDACIONES INICIALES
// ====================================

// Validar que la sesión esté activa y tengamos el alumno_rama
if (!isset($alumno_rama) || empty($alumno_rama)) {
    echo json_encode(array(
        'success' => false,
        'mensaje' => 'Sesión no válida o alumno no identificado'
    ));
    exit;
}

// Validar que se especificó una acción
if (!isset($_POST['accion'])) {
    echo json_encode(array(
        'success' => false,
        'mensaje' => 'No se especificó la acción'
    ));
    exit;
}

$accion = sanitize_input($_POST['accion'], $db);

debug_log(array('accion' => $accion, 'alumno_rama' => $alumno_rama), 'REQUEST_CARRUSEL');

// ====================================
// 🎯 SWITCH DE ACCIONES
// ====================================

switch ($accion) {
    
    // ========================================
    // 📋 OBTENER CARRUSELES DEL ALUMNO
    // ========================================
    case 'obtener_carruseles_alumno':
        
        $carruseles = array();
        $ids_procesados = array();
        
        // ----------------------------------------
        // NIVEL 1: CARRUSELES DE MI GENERACIÓN
        // ----------------------------------------
        $sql_generacion = "
            SELECT DISTINCT
                carrusel.id_car,
                carrusel.tit_car,
                carrusel.des_car,
                carrusel.url_car,
                carrusel.img_car,
                carrusel.fec_car
            FROM carrusel
            INNER JOIN generacion ON generacion.id_gen = carrusel.id_gen33
            INNER JOIN alu_ram ON alu_ram.id_gen1 = generacion.id_gen
            WHERE alu_ram.id_alu_ram = '$alumno_rama'
            AND carrusel.est_car = 'Activo'
            ORDER BY carrusel.fec_car DESC
        ";
        
        debug_log(array('query' => 'GENERACION'), 'QUERY_NIVEL_1');
        
        $resultado_gen = mysqli_query($db, $sql_generacion);
        
        if (!$resultado_gen) {
            debug_log(array('error' => mysqli_error($db)), 'MYSQL_ERROR_GEN');
        } else {
            while ($fila = mysqli_fetch_assoc($resultado_gen)) {
                if (!in_array($fila['id_car'], $ids_procesados)) {
                    $carruseles[] = formatear_carrusel($fila);
                    $ids_procesados[] = $fila['id_car'];
                }
            }
            debug_log(array('total_generacion' => count($carruseles)), 'RESULTADO_NIVEL_1');
        }
        
        // ----------------------------------------
        // NIVEL 2: CARRUSELES DE MI PLANTEL
        // ----------------------------------------
        $sql_plantel = "
            SELECT DISTINCT
                carrusel.id_car,
                carrusel.tit_car,
                carrusel.des_car,
                carrusel.url_car,
                carrusel.img_car,
                carrusel.fec_car
            FROM carrusel
            INNER JOIN alumno ON alumno.id_pla8 = carrusel.id_pla33
            INNER JOIN alu_ram ON alu_ram.id_alu1 = alumno.id_alu
            WHERE alu_ram.id_alu_ram = '$alumno_rama'
            AND carrusel.est_car = 'Activo'
            AND carrusel.id_gen33 IS NULL
            AND carrusel.id_ram33 IS NULL
            ORDER BY carrusel.fec_car DESC
        ";
        
        debug_log(array('query' => 'PLANTEL'), 'QUERY_NIVEL_2');
        
        $resultado_pla = mysqli_query($db, $sql_plantel);
        
        if (!$resultado_pla) {
            debug_log(array('error' => mysqli_error($db)), 'MYSQL_ERROR_PLA');
        } else {
            while ($fila = mysqli_fetch_assoc($resultado_pla)) {
                if (!in_array($fila['id_car'], $ids_procesados)) {
                    $carruseles[] = formatear_carrusel($fila);
                    $ids_procesados[] = $fila['id_car'];
                }
            }
            debug_log(array('total_con_plantel' => count($carruseles)), 'RESULTADO_NIVEL_2');
        }
        
        // ----------------------------------------
        // NIVEL 3: CARRUSELES DE MI CADENA
        // ----------------------------------------
        $sql_cadena = "
            SELECT DISTINCT
                carrusel.id_car,
                carrusel.tit_car,
                carrusel.des_car,
                carrusel.url_car,
                carrusel.img_car,
                carrusel.fec_car
            FROM carrusel
            INNER JOIN plantel ON plantel.id_cad1 = carrusel.id_cad33
            INNER JOIN rama ON rama.id_pla1 = plantel.id_pla
            INNER JOIN alu_ram ON alu_ram.id_ram3 = rama.id_ram
            WHERE alu_ram.id_alu_ram = '$alumno_rama'
            AND carrusel.est_car = 'Activo'
            AND carrusel.id_gen33 IS NULL
            AND carrusel.id_ram33 IS NULL
            AND carrusel.id_pla33 IS NULL
            ORDER BY carrusel.fec_car DESC
        ";
        
        debug_log(array('query' => 'CADENA'), 'QUERY_NIVEL_3');
        
        $resultado_cad = mysqli_query($db, $sql_cadena);
        
        if (!$resultado_cad) {
            debug_log(array('error' => mysqli_error($db)), 'MYSQL_ERROR_CAD');
        } else {
            while ($fila = mysqli_fetch_assoc($resultado_cad)) {
                if (!in_array($fila['id_car'], $ids_procesados)) {
                    $carruseles[] = formatear_carrusel($fila);
                    $ids_procesados[] = $fila['id_car'];
                }
            }
            debug_log(array('total_final' => count($carruseles)), 'RESULTADO_NIVEL_3');
        }
        
        // ----------------------------------------
        // RESPUESTA FINAL
        // ----------------------------------------
        if (count($carruseles) > 0) {
            debug_log(array('success' => true, 'total' => count($carruseles)), 'RESPONSE_SUCCESS');
            echo json_encode(array(
                'success' => true,
                'carruseles' => $carruseles,
                'total' => count($carruseles)
            ));
        } else {
            debug_log(array('success' => true, 'sin_carruseles' => true), 'RESPONSE_EMPTY');
            echo json_encode(array(
                'success' => true,
                'carruseles' => array(),
                'total' => 0
            ));
        }
        
        break;
    
    // ========================================
    // ❌ ACCIÓN NO VÁLIDA
    // ========================================
    default:
        echo json_encode(array(
            'success' => false,
            'mensaje' => 'Acción no válida: ' . $accion
        ));
        break;
}

?>