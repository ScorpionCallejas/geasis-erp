<?php
//OBTIENE LA SESION ACTIVA, LA CONEXION, VALIDACION DEL TIPO DE USUARIO, Y DATOS DEL ALUMNO
// header('Content-Type: text/html; charset=utf-8');

// if (!headers_sent()) {
    // las cabeceras ya se han enviado, no intentar añadir una nueva
    session_start();
// }

// ini_set('date.timezone','America/Tijuana');

$_SESSION['login'] = true;

//PATH
require_once(__DIR__."/../../includes/conexion.php");
//var_dump($_SESSION['rol']);

if (isset($_SESSION['rol'])) {
    if ($_SESSION['rol']['tipo'] != "Alumno") {
        header('Location: cerrar_sesion.php');
    }
} else {
    header('Location: cerrar_sesion.php');
}

$datos = $_SESSION['rol'];
$id = $datos['id'];
$tipo = $datos['tipo'];
$nombre = $datos['nombre'];
$foto = $datos['foto'];

$sqlConsultaAlumno = "
    SELECT * 
    FROM alumno
    INNER JOIN alu_ram ON alu_ram.id_alu1 = alumno.id_alu
    INNER JOIN generacion ON generacion.id_gen = alu_ram.id_gen1
    INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
    INNER JOIN plantel ON plantel.id_pla = rama.id_pla1 
    WHERE id_alu = '$id'";

$resultadoConsultaAlumno = mysqli_query($db, $sqlConsultaAlumno);
$filaConsultaAlumno = mysqli_fetch_assoc($resultadoConsultaAlumno);

//DATOS PLANTEL
$plantel = $filaConsultaAlumno['id_pla'];
$nombrePlantel = $filaConsultaAlumno['nom_pla'];
$descripcionPlantel = $filaConsultaAlumno['des_pla'];
$fotoPlantel = $filaConsultaAlumno['fot_pla'];
$esloganPlantel = $filaConsultaAlumno['esl_pla'];
$direccionPlantel = $filaConsultaAlumno['dir_pla'];
$directorPlantel = $filaConsultaAlumno['jef_pla'];
$urlPlantel = $filaConsultaAlumno['url_pla'];
$telefonoPlantel = $filaConsultaAlumno['tel_pla'];
$correoPlantel = $filaConsultaAlumno['cor_pla'];

$folioPlantel = $filaConsultaAlumno['fol_pla'];
$whatsappPlantel = $filaConsultaAlumno['wha_pla'];
$smsPlantel = $filaConsultaAlumno['sms_pla'];
$emailPlantel = $filaConsultaAlumno['ema_pla'];

$correo = $filaConsultaAlumno['cor_alu'];
$nombreCompleto = $filaConsultaAlumno['app_alu']." ".$filaConsultaAlumno['apm_alu']." ".$filaConsultaAlumno['nom_alu'];

// DATOS USUARIO
$correoUsuario = $filaConsultaAlumno['cor_alu'];
$contrasenaUsuario = $filaConsultaAlumno['pas_alu'];
$fotoUsuario = $filaConsultaAlumno['fot_alu'];
$generoUsuario = $filaConsultaAlumno['gen_alu'];
$telefonoUsuario = $filaConsultaAlumno['tel_alu'];
$direccionUsuario = $filaConsultaAlumno['dir_alu'];
$cpUsuario = $filaConsultaAlumno['cp_alu'];
$tipoUsuario = $filaConsultaAlumno['tip_alu'];
$ingresoUsuario = $filaConsultaAlumno['ing_alu'];
$nacimientoUsuario = $filaConsultaAlumno['nac_alu'];
$videosAlumno = $filaConsultaAlumno['vid_alu'];
$nombreUsuario = $nombreCompleto;
$estatus2Alumno = $filaConsultaAlumno['est2_alu'];
$estatus = $filaConsultaAlumno['est_alu'];

$cor1_alu = $filaConsultaAlumno['cor1_alu'];
$coloniaUsuario = $filaConsultaAlumno['col_alu'];
$delegacionUsuario = $filaConsultaAlumno['del_alu'];
$entidadUsuario = $filaConsultaAlumno['ent_alu'];
$presentacion = $filaConsultaAlumno['presentacion'];

$cadena = $filaConsultaAlumno['id_cad1'];
$alumno_rama = $filaConsultaAlumno['id_alu_ram'];

$idGeneracion = $filaConsultaAlumno['id_gen1'];
$inicioGeneracion = $filaConsultaAlumno['ini_gen'];

// ================================
// VALIDACIÓN DE PAGOS VENCIDOS
// ================================

// Obtener fecha actual
$fechaActual = date('Y-m-d');

// Consultar pagos vencidos y pendientes del alumno
$sqlPagosVencidos = "
    SELECT 
        COUNT(*) as total_vencidos,
        SUM(mon_pag) as monto_total_vencido
    FROM pago 
    WHERE id_alu_ram10 = '$alumno_rama'
    AND est_pag = 'Pendiente'
    AND fin_pag < '$fechaActual'
    AND fin_pag IS NOT NULL
";

$resultadoPagosVencidos = mysqli_query($db, $sqlPagosVencidos);
$filaPagosVencidos = mysqli_fetch_assoc($resultadoPagosVencidos);

// Variables de control de pagos
$tiene_pagos_vencidos = ($filaPagosVencidos['total_vencidos'] > 0);
$total_pagos_vencidos = $filaPagosVencidos['total_vencidos'];
$monto_total_vencido = $filaPagosVencidos['monto_total_vencido'];

// Array estructurado de pagos que debe (vencidos)
$pagos_debe = [];
if ($tiene_pagos_vencidos) {
    $sqlDetallePagosVencidos = "
        SELECT 
            id_pag,
            tip_pag,
            mon_pag,
            fin_pag
        FROM pago 
        WHERE id_alu_ram10 = '$alumno_rama'
        AND est_pag = 'Pendiente'
        AND fin_pag < '$fechaActual'
        AND fin_pag IS NOT NULL
        ORDER BY fin_pag ASC
    ";
    
    $resultadoDetallePagos = mysqli_query($db, $sqlDetallePagosVencidos);
    while ($filaDetalle = mysqli_fetch_assoc($resultadoDetallePagos)) {
        
        // Tipificar el concepto del pago
        $concepto_tipificado = '';
        switch($filaDetalle['tip_pag']) {
            case 'Inscripción':
                $concepto_tipificado = 'INSCRIPCIÓN';
                break;
            case 'Colegiatura':
                $concepto_tipificado = 'COLEGIATURA';
                break;
            case 'Reinscripción':
                $concepto_tipificado = 'REINSCRIPCIÓN';
                break;
            case 'Otros':
                $concepto_tipificado = 'TRÁMITE';
                break;
            default:
                $concepto_tipificado = strtoupper($filaDetalle['tip_pag']);
        }
        
        $pagos_debe[] = [
            'id_pago' => $filaDetalle['id_pag'],
            'concepto' => $concepto_tipificado,
            'monto' => $filaDetalle['mon_pag'],
            'fecha_vencimiento' => $filaDetalle['fin_pag'],
            'fecha_vencimiento_formateada' => date('d-M-Y', strtotime($filaDetalle['fin_pag'])),
            'dias_vencido' => floor((strtotime($fechaActual) - strtotime($filaDetalle['fin_pag'])) / (60 * 60 * 24))
        ];
    }
    
    // Formatear fechas en español
    foreach ($pagos_debe as &$pago) {
        $pago['fecha_vencimiento_formateada'] = str_replace(
            ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'],
            $pago['fecha_vencimiento_formateada']
        );
    }
}

// Variable unificada de bloqueo de acceso
$acceso_bloqueado = false;
$motivo_bloqueo = '';
$monto_adeudo = 0;

// // Verificar todas las condiciones de bloqueo
if ($estatus == 'Inactivo') {
    $acceso_bloqueado = true;
    $motivo_bloqueo = 'cuenta_desactivada';
} 
// elseif ($tiene_pagos_vencidos) {
//     $acceso_bloqueado = true;
//     $motivo_bloqueo = 'pagos_vencidos';
//     $monto_adeudo = $monto_total_vencido;
// }

?>