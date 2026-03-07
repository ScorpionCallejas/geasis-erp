<?php
/**
 * GET ESTATUS NOTIFICACIONES
 * Endpoint para obtener el estatus actual de las notificaciones enviadas
 * Se consulta cada 5 segundos desde el frontend
 * 
 * COMPATIBLE: PHP 5.6 + MySQL + mysqli_query
 */

require('../inc/cabeceras.php');
require('../inc/funciones.php');

// Headers para JSON
header('Content-Type: application/json; charset=utf-8');

// Validar que vengan los datos
if (!isset($_POST['alumnos']) || !isset($_POST['plantel'])) {
    echo json_encode(array(
        'success' => false,
        'error' => 'Faltan parametros requeridos'
    ));
    exit;
}

// Decodificar array de alumnos
$alumnosIds = json_decode($_POST['alumnos'], true);
$plantel = mysqli_real_escape_string($db, $_POST['plantel']);

if (empty($alumnosIds)) {
    echo json_encode(array(
        'success' => false,
        'error' => 'Array de alumnos vacio'
    ));
    exit;
}

// Sanitizar IDs de alumnos y crear IN clause
$alumnosIdsLimpios = array();
foreach ($alumnosIds as $id) {
    $alumnosIdsLimpios[] = intval($id);
}
$idsString = implode(',', $alumnosIdsLimpios);

// Query corregida - AGREGADOS tit_not y men_not
$sql = "
    SELECT 
        n.id_not,
        n.id_alu,
        n.est_not,
        n.fec_not,
        n.tit_not,
        n.men_not,
        a.nom_alu,
        a.app_alu,
        a.apm_alu
    FROM notificacion n
    INNER JOIN alumno a ON n.id_alu = a.id_alu
    WHERE n.id_alu IN ($idsString)
    ORDER BY n.fec_not DESC
";

$resultado = mysqli_query($db, $sql);

if (!$resultado) {
    echo json_encode(array(
        'success' => false,
        'error' => 'Error en consulta: ' . mysqli_error($db)
    ));
    exit;
}

// Procesar resultados
$notificaciones = array();
$alumnosEncontrados = array();

if (mysqli_num_rows($resultado) > 0) {
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $id_alu = $fila['id_alu'];
        
        // Solo tomar la notificacion mas reciente de cada alumno
        if (!isset($alumnosEncontrados[$id_alu])) {
            // Comprimir el mensaje a 50 caracteres
            $mensaje_completo = $fila['men_not'];
            $mensaje_corto = strlen($mensaje_completo) > 50 
                ? substr($mensaje_completo, 0, 50) . '...' 
                : $mensaje_completo;
            
            // Comprimir el título a 30 caracteres
            $titulo_completo = $fila['tit_not'];
            $titulo_corto = strlen($titulo_completo) > 30 
                ? substr($titulo_completo, 0, 30) . '...' 
                : $titulo_completo;
            
            $notificaciones[] = array(
                'id_not' => $fila['id_not'],
                'id_alu' => $id_alu,
                'est_not' => $fila['est_not'], // 'Pendiente' | 'Enviada' | 'Leida'
                'fec_not' => $fila['fec_not'],
                'titulo' => $titulo_corto,
                'titulo_completo' => $titulo_completo,
                'mensaje' => $mensaje_corto,
                'mensaje_completo' => $mensaje_completo,
                'nombre_completo' => trim($fila['nom_alu'] . ' ' . $fila['app_alu'] . ' ' . $fila['apm_alu'])
            );
            
            $alumnosEncontrados[$id_alu] = true;
        }
    }
}

// Respuesta exitosa
echo json_encode(array(
    'success' => true,
    'notificaciones' => $notificaciones,
    'total' => count($notificaciones),
    'timestamp' => date('Y-m-d H:i:s'),
    'query' => $sql  // ← PARA DEBUG
));
?>