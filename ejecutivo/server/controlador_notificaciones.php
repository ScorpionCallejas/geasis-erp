<?php
/**
 * CONTROLADOR DE NOTIFICACIONES PUSH
 * ejecutivo/server/controlador_notificaciones.php
 * Gestión completa del historial de notificaciones enviadas a alumnos
 */

// FORZAR JSON HEADER DESDE EL INICIO
header('Content-Type: application/json; charset=utf-8');

require('../inc/cabeceras.php');
require('../inc/funciones.php');

// ==================== OBTENER HISTORIAL DE NOTIFICACIONES ====================
if(isset($_POST['accion']) && $_POST['accion'] === 'obtener_historial') {
    $id_alu = mysqli_real_escape_string($db, $_POST['id_alu']);
    $filtro_estado = isset($_POST['filtro_estado']) ? mysqli_real_escape_string($db, $_POST['filtro_estado']) : '';
    $filtro_dias = isset($_POST['filtro_dias']) ? intval($_POST['filtro_dias']) : 30;
    
    // Validar que el alumno existe
    $sqlValidarAlumno = "SELECT id_alu FROM alumno WHERE id_alu = '$id_alu'";
    $resultValidar = mysqli_query($db, $sqlValidarAlumno);
    
    if(!$resultValidar || mysqli_num_rows($resultValidar) == 0) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'mensaje' => 'Alumno no encontrado'
        ]);
        exit;
    }
    
    // Construir query base
    $sql = "
        SELECT 
            id_not,
            tit_not,
            men_not,
            fec_not,
            est_not,
            DATE_FORMAT(fec_not, '%d/%m/%Y %H:%i') as fecha_formateada
        FROM notificacion
        WHERE id_alu = '$id_alu'
    ";
    
    // Filtro por fecha
    if($filtro_dias > 0) {
        $sql .= " AND fec_not >= DATE_SUB(NOW(), INTERVAL $filtro_dias DAY)";
    }
    
    // Filtro por estado
    if(!empty($filtro_estado)) {
        $sql .= " AND est_not = '$filtro_estado'";
    }
    
    // Ordenar de más reciente a más antigua
    $sql .= " ORDER BY fec_not DESC LIMIT 100";
    
    $resultado = mysqli_query($db, $sql);
    
    if(!$resultado) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error en la consulta: ' . mysqli_error($db)
        ]);
        exit;
    }
    
    $notificaciones = array();
    while($fila = mysqli_fetch_assoc($resultado)) {
        $notificaciones[] = $fila;
    }
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'notificaciones' => $notificaciones,
        'total' => count($notificaciones),
        'filtros_aplicados' => [
            'estado' => $filtro_estado,
            'dias' => $filtro_dias
        ]
    ]);
    exit;
}

// ==================== VER DETALLE COMPLETO DE NOTIFICACIÓN ====================
if(isset($_POST['accion']) && $_POST['accion'] === 'ver_detalle') {
    $id_not = mysqli_real_escape_string($db, $_POST['id_not']);
    
    $sql = "
        SELECT 
            id_not,
            tit_not,
            men_not,
            fec_not,
            est_not,
            id_alu,
            DATE_FORMAT(fec_not, '%d/%m/%Y %H:%i') as fecha_formateada
        FROM notificacion
        WHERE id_not = '$id_not'
    ";
    
    $resultado = mysqli_query($db, $sql);
    
    if(!$resultado || mysqli_num_rows($resultado) == 0) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'mensaje' => 'Notificación no encontrada'
        ]);
        exit;
    }
    
    $notificacion = mysqli_fetch_assoc($resultado);
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'notificacion' => $notificacion
    ]);
    exit;
}

// ==================== MARCAR NOTIFICACIÓN COMO LEÍDA ====================
if(isset($_POST['accion']) && $_POST['accion'] === 'marcar_leida') {
    $id_not = mysqli_real_escape_string($db, $_POST['id_not']);
    
    // Validar que la notificación existe
    $sqlValidar = "SELECT id_not, est_not FROM notificacion WHERE id_not = '$id_not'";
    $resultValidar = mysqli_query($db, $sqlValidar);
    
    if(!$resultValidar || mysqli_num_rows($resultValidar) == 0) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'mensaje' => 'Notificación no encontrada'
        ]);
        exit;
    }
    
    $notifActual = mysqli_fetch_assoc($resultValidar);
    
    // Solo actualizar si no está ya leída
    if($notifActual['est_not'] === 'Leída') {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'mensaje' => 'La notificación ya estaba marcada como leída',
            'ya_leida' => true
        ]);
        exit;
    }
    
    $sqlActualizar = "UPDATE notificacion SET est_not = 'Leída' WHERE id_not = '$id_not'";
    $resultActualizar = mysqli_query($db, $sqlActualizar);
    
    if($resultActualizar) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'mensaje' => 'Notificación marcada como leída',
            'ya_leida' => false
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error al actualizar: ' . mysqli_error($db)
        ]);
    }
    exit;
}

// ==================== REENVIAR NOTIFICACIÓN (OPCIONAL) ====================
if(isset($_POST['accion']) && $_POST['accion'] === 'reenviar') {
    $id_not = mysqli_real_escape_string($db, $_POST['id_not']);
    
    // Obtener datos de la notificación original
    $sqlNotif = "
        SELECT 
            n.id_not,
            n.tit_not,
            n.men_not,
            n.id_alu,
            a.nom_alu,
            a.app_alu
        FROM notificacion n
        INNER JOIN alumno a ON n.id_alu = a.id_alu
        WHERE n.id_not = '$id_not'
    ";
    
    $resultNotif = mysqli_query($db, $sqlNotif);
    
    if(!$resultNotif || mysqli_num_rows($resultNotif) == 0) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'mensaje' => 'Notificación no encontrada'
        ]);
        exit;
    }
    
    $notif = mysqli_fetch_assoc($resultNotif);
    $id_alu = $notif['id_alu'];
    $titulo = $notif['tit_not'];
    $mensaje = $notif['men_not'];
    
    // Verificar que el alumno tenga token
    $sqlToken = "SELECT token FROM alumno_token WHERE alumno = '$id_alu' ORDER BY id DESC LIMIT 1";
    $resultToken = mysqli_query($db, $sqlToken);
    
    if(!$resultToken || mysqli_num_rows($resultToken) == 0) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'mensaje' => 'El alumno no tiene la app instalada'
        ]);
        exit;
    }
    
    // Preparar datos para API
    $data = array(
        'title' => $titulo,
        'description' => $mensaje,
        'json_data' => json_encode(array(
            'tipo' => 'reenvio',
            'id_not_original' => $id_not,
            'timestamp' => time()
        ))
    );
    
    // Llamar a la API de push
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://plataforma.ahjende.com/api/alumno/send_push_notification/' . $id_alu);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'token: 61caf15d6b41de2d046caa5a44bb124f'
    ));
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if($httpCode === 200) {
        // Actualizar estado a 'Enviada' con nueva fecha
        $fechaActual = date('Y-m-d H:i:s');
        $sqlActualizar = "UPDATE notificacion SET est_not = 'Enviada', fec_not = '$fechaActual' WHERE id_not = '$id_not'";
        mysqli_query($db, $sqlActualizar);
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'mensaje' => 'Notificación reenviada exitosamente',
            'alumno' => $notif['nom_alu'] . ' ' . $notif['app_alu']
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error al reenviar notificación',
            'http_code' => $httpCode,
            'response' => $response
        ]);
    }
    exit;
}

// ==================== OBTENER ESTADÍSTICAS RÁPIDAS (OPCIONAL) ====================
if(isset($_POST['accion']) && $_POST['accion'] === 'estadisticas') {
    $id_alu = mysqli_real_escape_string($db, $_POST['id_alu']);
    
    $sqlStats = "
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN est_not = 'Pendiente' THEN 1 ELSE 0 END) as pendientes,
            SUM(CASE WHEN est_not = 'Enviada' THEN 1 ELSE 0 END) as enviadas,
            SUM(CASE WHEN est_not = 'Leída' THEN 1 ELSE 0 END) as leidas
        FROM notificacion
        WHERE id_alu = '$id_alu'
    ";
    
    $resultado = mysqli_query($db, $sqlStats);
    
    if($resultado) {
        $stats = mysqli_fetch_assoc($resultado);
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'estadisticas' => $stats
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error al obtener estadísticas'
        ]);
    }
    exit;
}

// ==================== RESPUESTA PARA REQUESTS NO VÁLIDOS ====================
http_response_code(400);
echo json_encode([
    'success' => false,
    'mensaje' => 'Acción no válida o parámetros faltantes',
    'accion_recibida' => isset($_POST['accion']) ? $_POST['accion'] : 'ninguna'
]);
?>