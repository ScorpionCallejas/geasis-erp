<?php  
  //ARCHIVO VIA AJAX PARA OTENER DATOS SALA
  //mensajes.php
  require('../inc/cabeceras.php');
  require('../inc/funciones.php');


  $id_sal6 = $_POST['id_sal'];
  $datos = array();

  // RECEPTOR
    $sqlusuarios = "
      SELECT *
      FROM usuario_sala 
      WHERE id_sal6 = '$id_sal6' AND ( usu_usu_sal != '$id' OR tip_usu_sal != '$tipo' )
      LIMIT 1
    ";

    // echo $sqlusuarios;

    $resultadoUsuarios = mysqli_query( $db, $sqlusuarios );

    $resultadoUsuariosTotal = mysqli_query( $db, $sqlusuarios );

    $totalUsuarios = mysqli_num_rows( $resultadoUsuariosTotal );


    if ( $totalUsuarios == 1 ) {
      
      while( $filausuarios = mysqli_fetch_assoc( $resultadoUsuarios ) ){
      
        $tip_not_men = $filausuarios['tip_usu_sal'];
        $use_not_men = $filausuarios['usu_usu_sal'];


        $datosContactoUltimoMensaje2 = obtener_datos_contacto_mensajeria_server( $tip_not_men, $use_not_men );

      }

    } else {

      $sala_nombre = 'Grupal';

    }
    
    // FIN RECEPTOR

    $sqlUltimoMensaje = "
      SELECT * FROM mensaje WHERE id_sal4 = '$id_sal6' ORDER BY hor_men DESC LIMIT 1
    ";

    $datosUltimoMensaje = obtener_datos_consulta( $db, $sqlUltimoMensaje );
    
    $tipo_contacto = $datosUltimoMensaje['datos']['tip_men'];
    $id_contacto = $datosUltimoMensaje['datos']['use_men'];
    // $hor_men = fechaFormateadaCompacta2( $datosUltimoMensaje['datos']['hor_men'] );

    // echo $tipo_contacto.' '.$id_contacto;
    $datos['notificacion'] = obtener_conteo_notificaciones_sala_server( $id_sal6, $id, $tipo );
    $datosContactoUltimoMensaje = obtener_datos_contacto_mensajeria_server( $tipo_contacto, $id_contacto );
  
  
  $datos['receptor'] = $datosContactoUltimoMensaje2;
  $datos['emisor'] = $datosContactoUltimoMensaje;

  $datos['mensaje']['men_men'] = $datosUltimoMensaje['datos']['men_men'];
  $datos['mensaje']['hor_men'] = fechaFormateadaCompacta2( $datosUltimoMensaje['datos']['hor_men'] );

  echo json_encode( $datos );

  // echo 'hello';

?>