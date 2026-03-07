<?php  
    
	//OBTIENE LA SESION ACTIVA, LA CONEXION, VALIDACION DEL TIPO DE USUARIO, Y DATOS DEL SUPERADMIN
	session_start();
    
    //PATH
    require_once(  __DIR__."/../../includes/conexion.php");


    
	//var_dump($_SESSION['rol']);

	if (isset($_SESSION['rol'])) {
		if ($_SESSION['rol']['tipo'] != "Superadmin") {
			header('Location: cerrar_sesion.php');
		}
	}else{
		header('Location: cerrar_sesion.php');
	}

    // $_SESSION['database'] = 'vacio';

	$datos = $_SESSION['rol'];
    $id = $datos['id'];
    $tipo = $datos['tipo'];
    $nombre = $datos['nombre'];


    $sqlConsultaSuper = "
        SELECT * 
        FROM superadmin
        INNER JOIN cadena ON cadena.id_cad = superadmin.id_cad2
        WHERE id_sup = '$id'
    ";
    
    $resultadoConsultaSuper = mysqli_query($db, $sqlConsultaSuper);
    $filaConsultaSuper = mysqli_fetch_assoc($resultadoConsultaSuper);

    

    $nomResponsable = $filaConsultaSuper['nom_sup'];



    //DATOS GENERALES USUARIO (SUPERADMIN)
    $nombreUsuario = $filaConsultaSuper['nom_sup'];
    $correoUsuario = $filaConsultaSuper['cor_sup'];
    // $fotoUsuario = $filaConsultaSuper['fot_sup'];
    $contrasenaUsuario = $filaConsultaSuper['pas_sup'];

    $cadena = $filaConsultaSuper['id_cad2'];
    $nombreCadena = $filaConsultaSuper['nom_cad'];

    $tipoUsuario = $tipo;
    $mod_sup = 'White';

    //DATOS PLANTEL

    $estilos_modo = array();
    if ( $mod_sup == 'dark' ) {

        $estilos_modo['body'] = 'grey-skin elegant-color';
        $estilos_modo['card'] = 'grey darken-3';
        $estilos_modo['container'] = 'white-text elegant-color';
        $estilos_modo['navbar'] = 'grey darken-3';
        

    } else {

        $estilos_modo['body'] = 'white-skin bg-light';
        $estilos_modo['card'] = 'grey lighten-4';
        $estilos_modo['container'] = 'bg-light';
        $estilos_modo['navbar'] = 'grey lighten-4';
        

    }


    $plantel = 3;

    $sqlPlantel = "
        SELECT *
        FROM plantel
        WHERE id_pla = $plantel
    ";

    $resultadoPlantel = mysqli_query( $db, $sqlPlantel );

    $filaConsultaAdministrador = mysqli_fetch_assoc( $resultadoPlantel );

    $fotoPlantel = $filaConsultaAdministrador['fot_pla'];
    $nombrePlantel = $filaConsultaAdministrador['nom_pla'];
    $esloganPlantel = $filaConsultaAdministrador['esl_pla'];
    $direccionPlantel = $filaConsultaAdministrador['dir_pla'];
    $telefonoPlantel = $filaConsultaAdministrador['tel_pla'];
    $correoPlantel = $filaConsultaAdministrador['cor_pla'];
    $correo2Plantel = $filaConsultaAdministrador['cor2_pla'];
    $ligaPlantel = $filaConsultaAdministrador['lig_pla'];

    $directorPlantel = $filaConsultaAdministrador['jef_pla'];
    $descripcionPlantel = $filaConsultaAdministrador['des_pla'];
    $urlPlantel = $filaConsultaAdministrador['url_pla'];
    $folioPlantel = $filaConsultaAdministrador['fol_pla'];
    $whatsappPlantel = $filaConsultaAdministrador['wha_pla'];
    $smsPlantel = $filaConsultaAdministrador['sms_pla'];
    $emailPlantel = $filaConsultaAdministrador['ema_pla'];
    $fechaPlantel = $filaConsultaAdministrador['fec_pla'];

    $mod_adm = 'dark';


    $fechaHoy = date('Y-m-d');
    $fechaHoraHoy = date('Y-m-d h:i:s');
    // TWILIO
    // ENVIO DE SMS Y WHATSAPPS

    // Required if your environment does not handle autoloading
    // require __DIR__ . '/../../vendor/autoload.php';

    // Use the REST API Client to make requests to the Twilio REST API
    // use Twilio\Rest\Client;

    // Your Account SID and Auth Token from twilio.com/console
    // $sid = 'AC6d688c239da8ab344c11056eb63f553b';
    // $token = '1034d8f22bdc0529bf10b9cbd5b65190';
    // $client = new Client($sid, $token);


    // Required if your environment does not handle autoloading

?>