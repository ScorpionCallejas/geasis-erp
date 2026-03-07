<?php
require('../inc/cabeceras.php');
require('../inc/funciones.php');

$accion = $_POST['accion'];

// OBTENER SEGUIMIENTOS (ORIGINAL - SIN CAMBIOS)
if ($accion == 'obtener') {
    $id_alu_ram = $_POST['id_alu_ram'];
    
    $sql = "
        SELECT 
            id_obs_alu_ram,
            obs_obs_alu_ram,
            res_obs_alu_ram,
            DATE_FORMAT(fec_obs_alu_ram, '%d/%m/%y %H:%i') as fecha_formateada
        FROM observacion_alu_ram
        WHERE id_alu_ram16 = '$id_alu_ram'
        ORDER BY id_obs_alu_ram DESC
    ";
    
    $resultado = mysqli_query($db, $sql);
    $datos = [];
    
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $datos[] = $fila;
    }
    
    echo json_encode(['success' => true, 'datos' => $datos]);
    exit;
}

// CREAR SEGUIMIENTO (ORIGINAL - SIN CAMBIOS)
if ($accion == 'crear') {
    $id_alu_ram = $_POST['id_alu_ram'];
    $obs_obs_alu_ram = $_POST['obs_obs_alu_ram'];
    $res_obs_alu_ram = $nombreCompleto;
    
    $sql = "
        INSERT INTO observacion_alu_ram (obs_obs_alu_ram, id_alu_ram16, res_obs_alu_ram)
        VALUES ('$obs_obs_alu_ram', '$id_alu_ram', '$res_obs_alu_ram')
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'mensaje' => mysqli_error($db)]);
    }
    exit;
}

// ELIMINAR SEGUIMIENTO (ORIGINAL - SIN CAMBIOS)
if ($accion == 'eliminar') {
    $id_obs_alu_ram = $_POST['id_obs_alu_ram'];
    
    $sql = "DELETE FROM observacion_alu_ram WHERE id_obs_alu_ram = '$id_obs_alu_ram'";
    
    $resultado = mysqli_query($db, $sql);
    
    if ($resultado) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'mensaje' => mysqli_error($db)]);
    }
    exit;
}

// ========================================
// 🔥 NUEVA ACCIÓN: FEED GLOBAL DE ACTIVIDAD
// ========================================
if ($accion == 'obtener_feed_global') {
    $ultimo_id = isset($_POST['ultimo_id']) ? intval($_POST['ultimo_id']) : 0;
    $limite = isset($_POST['limite']) ? intval($_POST['limite']) : 20;
    $id_pla_filtro = isset($_POST['id_pla']) ? intval($_POST['id_pla']) : 0;
    
    // OBTENER PLANTELES DEL EJECUTIVO
    $plantelesEjecutivo = array();
    
    $sqlPlantelesEje = "
        SELECT DISTINCT p.id_pla 
        FROM plantel p
        INNER JOIN planteles_ejecutivo pe ON p.id_pla = pe.id_pla
        WHERE pe.id_eje = '$id'
    ";
    $resultadoPlantelesEje = mysqli_query($db, $sqlPlantelesEje);
    
    if(mysqli_num_rows($resultadoPlantelesEje) > 0) {
        while($fila = mysqli_fetch_assoc($resultadoPlantelesEje)) {
            $plantelesEjecutivo[] = $fila['id_pla'];
        }
    } else {
        // Fallback: plantel por defecto del ejecutivo
        $sqlPlantelDefault = "
            SELECT id_pla 
            FROM ejecutivo 
            WHERE id_eje = '$id'
        ";
        $resultadoDefault = mysqli_query($db, $sqlPlantelDefault);
        if($fila = mysqli_fetch_assoc($resultadoDefault)) {
            $plantelesEjecutivo[] = $fila['id_pla'];
        }
    }
    
    // CONSTRUIR CONDICIÓN DE FILTRO
    $condicionPlantel = "";
    if($id_pla_filtro > 0) {
        $condicionPlantel = "AND a.id_pla8 = $id_pla_filtro";
    } else if(count($plantelesEjecutivo) > 0) {
        $idsPlanteles = implode(',', $plantelesEjecutivo);
        $condicionPlantel = "AND a.id_pla8 IN ($idsPlanteles)";
    }
    
    // QUERY PRINCIPAL
    $sql = "
        SELECT 
            o.id_obs_alu_ram,
            o.obs_obs_alu_ram,
            o.res_obs_alu_ram,
            DATE_FORMAT(o.fec_obs_alu_ram, '%H:%i:%s') AS hora,
            DATE_FORMAT(o.fec_obs_alu_ram, '%d/%m/%Y') AS fecha,
            UNIX_TIMESTAMP(o.fec_obs_alu_ram) AS timestamp,
            CONCAT(a.nom_alu, ' ', a.app_alu, ' ', COALESCE(a.apm_alu, '')) AS nombre_alumno,
            p.nom_pla AS plantel,
            p.id_pla AS id_plantel
        FROM observacion_alu_ram o
        INNER JOIN alu_ram ar ON o.id_alu_ram16 = ar.id_alu_ram
        INNER JOIN alumno a ON ar.id_alu1 = a.id_alu
        INNER JOIN plantel p ON a.id_pla8 = p.id_pla
        WHERE o.id_obs_alu_ram > $ultimo_id
        $condicionPlantel
        ORDER BY o.id_obs_alu_ram DESC
        LIMIT $limite
    ";
    
    $resultado = mysqli_query($db, $sql);
    $logs = array();
    
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $logs[] = $fila;
    }
    
    // CONTAR TOTAL DE OBSERVACIONES DEL DÍA
    $sqlCount = "
        SELECT COUNT(*) as total
        FROM observacion_alu_ram o
        INNER JOIN alu_ram ar ON o.id_alu_ram16 = ar.id_alu_ram
        INNER JOIN alumno a ON ar.id_alu1 = a.id_alu
        WHERE DATE(o.fec_obs_alu_ram) = CURDATE()
        $condicionPlantel
    ";
    $resCount = mysqli_query($db, $sqlCount);
    $totalHoy = intval(mysqli_fetch_assoc($resCount)['total']);
    
    echo json_encode([
        'success' => true,
        'logs' => $logs,
        'nuevos' => count($logs),
        'total_hoy' => $totalHoy
    ]);
    exit;
}

echo json_encode(['success' => false, 'mensaje' => 'Acción no válida']);
?>