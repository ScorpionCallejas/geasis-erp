<?php  
	//OBTIENE LA SESION ACTIVA, LA CONEXION, VALIDACION DEL TIPO DE USUARIO, Y DATOS DEL ADMIN
	session_start();
    
    // VALIDAR SESION Y REDIRECCIONAMIENTO
    $datos = $_SESSION['rol'];
    //PATH
    require_once(  __DIR__."/../../includes/conexion.php");


	if (!isset($_SESSION['rol'])) {
		
		header('Location: cerrar_sesion.php');
	}

    $id = $datos['id'];
    $tipo = $datos['tipo'];
    $nombre = $datos['nombre'];
    $id_pla = $datos['id_pla'];

    //DATOS PLANTEL

    if ( $tipo == 'Super' ) {
        
        $sqlConsulta = "
            SELECT * 
            FROM usuario 
            INNER JOIN cadena ON cadena.id_cad = usuario.id_cad5
            WHERE id_usu = '$id'
        ";

        $resultadoConsulta = mysqli_query($db, $sqlConsulta);
        $filaConsulta = mysqli_fetch_assoc($resultadoConsulta);

        $lugar = $filaConsulta['nom_cad'];

        // $nom_cad = $filaConsulta['nom_cad'];
        $cadena = $filaConsulta['id_cad5'];

    } else {

        $sqlConsulta = "
            SELECT * 
            FROM usuario 
            INNER JOIN plantel ON plantel.id_pla = usuario.id_pla14
            WHERE id_usu = '$id'
        ";

        $resultadoConsulta = mysqli_query($db, $sqlConsulta);
        $filaConsulta = mysqli_fetch_assoc($resultadoConsulta);    
        
        $lugar = $filaConsulta['nom_pla'];

        $cadena = $filaConsulta['id_cad5'];
        $plantel = $filaConsulta['id_pla14'];
        
        $folioPlantel = $filaConsulta['fol_pla'];
        $fotoPlantel = $filaConsulta['fot_pla'];
        
        $nombrePlantel = $filaConsulta['nom_pla'];

        $esloganPlantel = $filaConsulta['esl_pla'];
        $direccionPlantel = $filaConsulta['dir_pla'];
        $telefonoPlantel = $filaConsulta['tel_pla'];
        $ligaPlantel = $filaConsulta['lig_pla'];

    }


    $foto = $filaConsulta['fot_usu'];

    $tipoUsuario = $tipo;


    $fechaHoy = date('Y-m-d');
    $fechaHoraHoy = date('Y-m-d h:i:s');
    

    //DATOS GENERALES USUARIO (ADMIN)
    $nomResponsable = $filaConsulta['nom_usu']; 
    $mod_adm = 'White';
    
    $estilos_modo = array();
    if ( $mod_adm == 'dark' ) {

        $estilos_modo['body'] = 'grey-skin elegant-color';
        $estilos_modo['card'] = 'grey darken-3';
        $estilos_modo['container'] = 'white-text elegant-color';
        $estilos_modo['navbar'] = 'grey darken-3';
        

    } else {

        $estilos_modo['body'] = 'white-skin bg-light';
        $estilos_modo['card'] = 'grey lighten-4';
        $estilos_modo['container'] = 'bg-light';
        $estilos_modo['navbar'] = 'grey lighten-3';
        

    }

?>