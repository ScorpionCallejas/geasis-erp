<?php
// ============================================================================
// OBTIENE LA SESION ACTIVA, LA CONEXION, VALIDACION DEL TIPO DE USUARIO, Y DATOS DEL ADMIN
// ============================================================================
session_start();
$_SESSION['login'] = true;

// PATH
require_once(__DIR__ . "/../../includes/conexion.php");

// var_dump($_SESSION['rol']);

$current_page = basename($_SERVER['PHP_SELF']);

// Si estamos en la página de cerrar sesión, NO validar ni redirigir
if ($current_page === 'cerrar_sesion.php') {
    return; // Salir temprano sin validaciones
}

// ============================================================================
// VALIDACIÓN DE SESIÓN Y TIPO DE USUARIO
// ============================================================================
if (isset($_SESSION['rol'])) {
    if ($_SESSION['rol']['tipo'] != "Ejecutivo") {
        header('Location: cerrar_sesion.php');
        exit();
    }
} else {
    header('Location: cerrar_sesion.php');
    exit();
}

// ============================================================================
// DATOS BÁSICOS DE SESIÓN
// ============================================================================
$datos = $_SESSION['rol'];
$id = $datos['id'];
$tipo = $datos['tipo'];
$nombre = $datos['nombre'];

// ============================================================================
// CONSULTA PRINCIPAL: EJECUTIVO + PLANTEL
// ============================================================================
$sqlConsultaEjecutivo = "
        SELECT * 
        FROM ejecutivo
        INNER JOIN plantel ON plantel.id_pla = ejecutivo.id_pla
        WHERE id_eje = '$id'
    ";

$resultadoConsultaEjecutivo = mysqli_query($db, $sqlConsultaEjecutivo);
$filaConsultaEjecutivo = mysqli_fetch_assoc($resultadoConsultaEjecutivo);

$nomResponsable = $filaConsultaEjecutivo['nom_eje'] . " " . $filaConsultaEjecutivo['app_eje'] . " " . $filaConsultaEjecutivo['apm_eje'];

// ============================================================================
// DATOS GENERALES USUARIO (EJECUTIVO)
// ============================================================================
$ingresoUsuario = $filaConsultaEjecutivo['ing_eje'];
$nombreUsuario = $filaConsultaEjecutivo['nom_eje'];
$appUsuario = $filaConsultaEjecutivo['app_eje'];
$apmUsuario = $filaConsultaEjecutivo['apm_eje'];
$correoUsuario = $filaConsultaEjecutivo['cor_eje'];
$generoUsuario = $filaConsultaEjecutivo['gen_eje'];
$telefonoUsuario = $filaConsultaEjecutivo['tel_eje'];
$nacimientoUsuario = $filaConsultaEjecutivo['nac_eje'];
$fotoUsuario = $filaConsultaEjecutivo['fot_eje'];
$direccionUsuario = $filaConsultaEjecutivo['dir_eje'];
$cpUsuario = $filaConsultaEjecutivo['cp_eje'];
$contrasenaUsuario = $filaConsultaEjecutivo['pas_eje'];
$descripcionUsuario = $filaConsultaEjecutivo['des_eje'];
$tipoUsuario = $filaConsultaEjecutivo['tip_eje'];
$estatusUsuario = $filaConsultaEjecutivo['est_eje'];
$eli_eje = $filaConsultaEjecutivo['eli_eje'];
$permisos = $filaConsultaEjecutivo['per_eje'];
$permisosCobranza = $filaConsultaEjecutivo['cob_eje'];

$cuentaUsuario = $filaConsultaEjecutivo['cue_eje'];
$bancoUsuario = $filaConsultaEjecutivo['ban_eje'];

$usuario = $filaConsultaEjecutivo['usu_eje'];

$rangoUsuario = $filaConsultaEjecutivo['ran_eje'];
$switch_ejecutivo = $filaConsultaEjecutivo['swi_eje'];

if ($switch_ejecutivo == 'dark') {
    $switch_ejecutivo_tabla = 'dark-table';
} else {
    $switch_ejecutivo_tabla = '';
}

$foto = $filaConsultaEjecutivo['fot_eje'];
$id_eje = $filaConsultaEjecutivo['id_eje'];

// ============================================================================
// DATOS PLANTEL
// ============================================================================
$plantel = $filaConsultaEjecutivo['id_pla'];
$fotoPlantel = $filaConsultaEjecutivo['fot_pla'];
$nombrePlantel = $filaConsultaEjecutivo['nom_pla'];
$esloganPlantel = $filaConsultaEjecutivo['esl_pla'];
$folioPlantel = $filaConsultaEjecutivo['fol_pla'];
$descripcionPlantel = $filaConsultaEjecutivo['des_pla'];
$direccionPlantel = $filaConsultaEjecutivo['dir_pla'];
$directorPlantel = $filaConsultaEjecutivo['jef_pla'];
$urlPlantel = $filaConsultaEjecutivo['url_pla'];
$telefonoPlantel = $filaConsultaEjecutivo['tel_pla'];
$correoPlantel = $filaConsultaEjecutivo['cor_pla'];
$identificadorCadena = $filaConsultaEjecutivo['id_cad1'];
$cuentaPlantel = $filaConsultaEjecutivo['cue_pla'];

$cadena = $identificadorCadena;

$correo2Plantel = $filaConsultaEjecutivo['cor2_pla'];
$ligaPlantel = $filaConsultaEjecutivo['lig_pla'];

$nombreCompleto = $filaConsultaEjecutivo['nom_eje'] . " " . $filaConsultaEjecutivo['app_eje'] . " " . $filaConsultaEjecutivo['apm_eje'];

$fechaHoy = date('Y-m-d');

// ============================================================================
// 🔐 VARIABLES PARA SISTEMA DE SEGURIDAD DE CONTRASEÑAS
// ============================================================================
$ult_cam_pas_eje = $filaConsultaEjecutivo['ult_cam_pas_eje'];
$req_cam_pas_eje = $filaConsultaEjecutivo['req_cam_pas_eje'];

// ============================================================================
// 🔥 VALIDACIÓN DE CAMBIO DE CONTRASEÑA
// ============================================================================

// CONDICIÓN 1: Forzado por admin (req_cam_pas_eje = 1)
if ($req_cam_pas_eje == 1) {
    $_SESSION['requiere_cambio'] = false;
    $_SESSION['motivo_cambio'] = 'La administración ha solicitado que cambies tu contraseña por seguridad';
}

// CONDICIÓN 2: Si han pasado más de 45 días desde el último cambio
if ($ult_cam_pas_eje) {
    $diasTranscurridos = (strtotime($fechaHoy) - strtotime($ult_cam_pas_eje)) / 86400;

    if ($diasTranscurridos > 300) {
        $_SESSION['requiere_cambio'] = false;
        $_SESSION['motivo_cambio'] = 'Han pasado más de 45 días desde tu último cambio de contraseña';
    }
}

// CONDICIÓN 3: Si NO tiene fecha de último cambio (NULL o vacío)
if ($ult_cam_pas_eje === NULL || $ult_cam_pas_eje === '') {
    $_SESSION['requiere_cambio'] = false;
    $_SESSION['motivo_cambio'] = 'Es necesario establecer una nueva contraseña segura';
}

// ============================================================================
// 🚨 REDIRECCIÓN FORZADA A cambio_password.php
// ============================================================================
$currentPage = basename($_SERVER['PHP_SELF']);

if (isset($_SESSION['requiere_cambio']) && $_SESSION['requiere_cambio'] === true) {
    // Excepciones: No redirigir si ya está en estas páginas
    if ($currentPage !== 'cambio_password.php' && $currentPage !== 'cerrar_sesion.php') {
        header('Location: cambio_password.php');
        exit();
    }
}
