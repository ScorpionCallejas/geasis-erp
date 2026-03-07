<?php  

    //OBTIENE LA SESION ACTIVA, LA CONEXION, VALIDACION DEL TIPO DE USUARIO, Y DATOS DEL EJECUTIVO
    session_start();
    $_SESSION['login'] = true;
     require_once(  __DIR__."/../../includes/conexion.php");
    //var_dump($_SESSION['rol']);

    if (isset($_SESSION['rol'])) {
        if ($_SESSION['rol']['tipo'] != "Profesor") {
            header('Location: cerrar_sesion.php');
        }
    }else{
        header('Location: cerrar_sesion.php');
    }


    $datos = $_SESSION['rol'];
    $id = $datos['id'];
    $tipo = $datos['tipo'];
    $nombre = $datos['nombre'];


    $sqlConsultaProfesor = "
        SELECT * 
        FROM profesor
        INNER JOIN empleado ON empleado.id_emp = profesor.id_emp3
        INNER JOIN plantel ON plantel.id_pla = empleado.id_pla6
        WHERE id_pro = '$id'
    ";

    $resultadoConsultaProfesor = mysqli_query($db, $sqlConsultaProfesor);
    $filaConsultaProfesor = mysqli_fetch_assoc($resultadoConsultaProfesor);


    $nomResponsable = $filaConsultaProfesor['nom_pro']." ".$filaConsultaProfesor['app_pro']." ".$filaConsultaProfesor['apm_pro'];



    //DATOS GENERALES USUARIO (PROFESOR)
    $ingresoUsuario = $filaConsultaProfesor['ing_pro'];
    $nombreUsuario = $filaConsultaProfesor['nom_pro'];
    $appUsuario = $filaConsultaProfesor['app_pro'];
    $apmUsuario = $filaConsultaProfesor['apm_pro'];
    $correoUsuario = $filaConsultaProfesor['cor_pro'];
    $generoUsuario = $filaConsultaProfesor['gen_pro'];
    $telefonoUsuario = $filaConsultaProfesor['tel_pro'];
    $nacimientoUsuario = $filaConsultaProfesor['nac_pro'];
    $fotoUsuario = $filaConsultaProfesor['fot_pro'];
    $direccionUsuario = $filaConsultaProfesor['dir_pro'];
    $cpUsuario = $filaConsultaProfesor['cp_pro'];
    $contrasenaUsuario = $filaConsultaProfesor['pas_pro'];
    $tipoUsuario = $filaConsultaProfesor['tip_pro'];
    $foto = $filaConsultaProfesor['fot_emp'];
    $id_emp = $filaConsultaProfesor['id_emp'];
    $estatusUsuario = $filaConsultaProfesor['est_pro'];

    //DATOS PLANTEL
    $plantel = $filaConsultaProfesor['id_pla2'];
    $fotoPlantel = $filaConsultaProfesor['fot_pla'];
    $nombrePlantel = $filaConsultaProfesor['nom_pla'];
    $esloganPlantel = $filaConsultaProfesor['esl_pla'];
    $direccionPlantel = $filaConsultaProfesor['dir_pla'];
    $telefonoPlantel = $filaConsultaProfesor['tel_pla'];
    $correoPlantel = $filaConsultaProfesor['cor_pla'];
    $directorPlantel = $filaConsultaProfesor['jef_pla'];
    $descripcionPlantel = $filaConsultaProfesor['des_pla'];
    $urlPlantel = $filaConsultaProfesor['url_pla'];

    $nombreCompleto = $filaConsultaProfesor['nom_pro']." ".$filaConsultaProfesor['app_pro']." ".$filaConsultaProfesor['apm_pro'];

    $dominioVideo = 'meet.jit.si/';
    
?>