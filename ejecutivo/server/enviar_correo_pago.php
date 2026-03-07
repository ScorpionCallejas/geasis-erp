<?php
/**
 * ENVÍO DE CORREO PARA PAGOS YA PROCESADOS
 * server/enviar_correo_pago.php
 * Extrae solo la lógica de envío de correo del pago
 */

require('../inc/cabeceras.php');
require('../inc/funciones.php');

// Validar que se recibió el ID del pago
if (!isset($_POST['id_pag']) || empty($_POST['id_pag'])) {
    echo json_encode([
        'success' => false,
        'mensaje' => 'ID de pago requerido',
        'error_detalle' => 'Parámetro id_pag no proporcionado'
    ]);
    exit;
}

$id_pag = $_POST['id_pag'];

try {
    // Verificar que el pago existe y está pagado
    $sqlVerificarPago = "
        SELECT id_pag, est_pag, con_pag 
        FROM pago 
        WHERE id_pag = '$id_pag' AND est_pag = 'Pagado'
    ";
    
    $datoPago = obtener_datos_consulta($db, $sqlVerificarPago)['datos'];
    
    if (!$datoPago) {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Pago no encontrado o no está procesado',
            'error_detalle' => 'El pago debe estar en estado "Pagado" para enviar correo'
        ]);
        exit;
    }

    // Obtener datos del alumno asociado al pago
    $sqlAlumno = "
        SELECT 
            a.cor1_alu,
            CONCAT(a.nom_alu, ' ', a.app_alu, ' ', a.apm_alu) as nombre_completo
        FROM pago p
        JOIN alu_ram ar ON p.id_alu_ram10 = ar.id_alu_ram
        JOIN alumno a ON ar.id_alu1 = a.id_alu
        WHERE p.id_pag = '$id_pag'
    ";
    
    $datosAlumno = obtener_datos_consulta($db, $sqlAlumno)['datos'];
    
    if (!$datosAlumno) {
        echo json_encode([
            'success' => false,
            'mensaje' => 'No se encontraron datos del alumno',
            'error_detalle' => 'No se pudo obtener información del alumno asociado al pago'
        ]);
        exit;
    }

    // Validar que el alumno tiene correo válido
    $correoAlumno = $datosAlumno['cor1_alu'];
    
    if (empty($correoAlumno) || !filter_var($correoAlumno, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Correo del alumno inválido',
            'error_detalle' => 'El alumno no tiene un correo electrónico válido registrado',
            'correo_verificado' => $correoAlumno ?: 'VACÍO'
        ]);
        exit;
    }

    // Intentar enviar el correo
    $correoEnviado = enviar_correo_ticket_pago($id_pag, $db);
    
    if ($correoEnviado) {
        // Éxito al enviar
        error_log("✅ Correo de comprobante enviado exitosamente para pago ID: $id_pag a $correoAlumno");
        
        echo json_encode([
            'success' => true,
            'mensaje' => 'Correo enviado exitosamente',
            'correo_destinatario' => $correoAlumno,
            'nombre_alumno' => $datosAlumno['nombre_completo'],
            'concepto_pago' => $datoPago['con_pag']
        ]);
        
    } else {
        // Error al enviar
        error_log("❌ Error al enviar correo de comprobante para pago ID: $id_pag a $correoAlumno");
        
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error al enviar el correo',
            'error_detalle' => 'La función de envío retornó false',
            'correo_destinatario' => $correoAlumno
        ]);
    }

} catch (Exception $e) {
    // Manejo de errores generales
    error_log("💥 Excepción al enviar correo para pago ID: $id_pag - " . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'mensaje' => 'Error interno del servidor',
        'error_detalle' => 'Se produjo una excepción durante el procesamiento'
    ]);
}
?>