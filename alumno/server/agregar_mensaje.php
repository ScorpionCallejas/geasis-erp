<?php 
  //ARCHIVO VIA AJAX PARA MANDAR MSJ A CONTACTO DESDE EL MESSENGER, 
  //mensajes.php
  require('../inc/cabeceras.php');
  require('../inc/funciones.php');

  // RECOMENDACION LEER EL CODIGO PRIMERO Y DESPUES ECHARLE VISTAZO A LAS NOTAS TRATE DE DOCUMENTARLO LO MAS POSIBLE
  //¡¡¡NOTA!!!: $id = 'id_alu' y $tipo = 'Alumno'
  // ¡¡¡NOTA!!! TAMBIEN MENCIONAR QUE FUNCIONA PARA 2 O MAS PARTICIPANTES DE SALA, ASI COMO ENVIO DE ARCHIVOS (EN CASO DE NO SER ALUMNO)
  // SI GUSTAS PUEDES CAMBIARLAS PONIENDOLES DIRECTAMENTE id_alu y tip_alu PARA NO SUFRIR
  // NOTA: USAMOS ESTA FUNCION: obtenerUltimoIdentificadorServer( $tabla, $identificador ) QUE RETORNA EL ULTIMO id DE UNA TABLA CON ESOS 2 VALORES 
  // AQUI ESTA LA SQL POR SI GUSTAS USARLA: 
  /**
      SELECT MAX( $identificador ) AS ultimo 
      FROM $tabla
  */


  // MENSAJE
  $hor_men = date('Y-m-d H:i:s');

  // SE RECIBE id_pro EN id_usuario ASI COMO EL id_sal (CON 2 POSIBLES OPCIONES, O EXISTE Y ES UN ENTERO, O DIRECTAMENTE UN 'Falso')
  if ( isset( $_POST['id_usuario'] ) && ( isset( $_POST['id_sal'] ) && $_POST['id_sal'] == 'Falso' ) ) {
    //  NO EXISTE SALA

    $id_usuario = $_POST['id_usuario'];
    $tipo_usuario = $_POST['tipo_usuario'];

    // SQL ALTA DE SALA
    $sql = "
      
      INSERT INTO sala ( fec_men_sal ) 
      VALUES ( '$hor_men' )

    ";

    // EJECUCION DE QUERY
    $resultado = mysqli_query( $db, $sql );

    // IMPRESION EN CASO DE ERROR
    if ( !$resultado ) {
      
      echo $sql;
    
    }

    // OBTENCION DE ULTIMO ID DE SALA PARA AGREGAR CLAVE FORANEA EN TABLAS SIGUIENTES
    $id_sal = obtenerUltimoIdentificadorServer( 'sala', 'id_sal' );
    // SALA

    // SQL ALTA DE USUARIO PARTICIPANTES DE SALA (EN ESTE CASO ALUMNO Y PROFESOR)
    $sql3 = "
      
      INSERT INTO usuario_sala ( usu_usu_sal, tip_usu_sal, id_sal6 ) 
      VALUES ( '$id', '$tipo', '$id_sal' ), ( '$id_usuario',  '$tipo_usuario', '$id_sal' )

    ";

    $resultado3 = mysqli_query( $db, $sql3 );

    // IMPRESION EN CASO DE ERROR
    if ( !$resultado3 ) {
    
      echo $sql3;
    
    }
    // USUARIOS


    // MENSAJE
    
    // PURGA DEL MENSAJE
    $men_men = filter_var( $_POST['mensaje'], FILTER_SANITIZE_STRING);

    // SQL ALTA DE MENSAJE
    $sql2 = "
      INSERT INTO mensaje ( hor_men, men_men, tip_men, use_men, id_sal4 ) 
      VALUES ( '$hor_men', '$men_men', '$tipo', '$id', '$id_sal' )
    ";

    $resultado2 = mysqli_query( $db, $sql2 );

    // IMPRESION EN CASO DE ERROR
    if ( !$resultado2 ) {
      
      echo $sql2;

    }
    // MENSAJE


    // SQL ESTE UPDATE LO METIMOS PARA QUE EN EL DESPLIEGUE DE SALAS SE ORDENEN POR fec_men_sal DE MAS RECIENTE A MAS ANTIGUO
    $sqlUpdateSala = "
      UPDATE sala
      SET
      fec_men_sal = '$hor_men'
      WHERE
      id_sal = '$id_sal'
    ";

    $resultadoUpdateSala = mysqli_query( $db, $sqlUpdateSala );

    // IMPRESION EN CASO DE ERROR
    if ( !$resultadoUpdateSala ) {
      echo $sqlUpdateSala;
    }


    // SQL PARA GENERAR LA ALTA DE ESTATUS (NOTIFICACION ) A LOS usuario_sala DIFERENTES DE MI 
    // NOTA: ESTE ARCHIVO FUNCIONA PARA SALAS DE 2 O MAS PARTICIPANTES, POR ESO ESTA ASI :d
    $sqlusuarios = "
      SELECT *
      FROM usuario_sala 
      WHERE id_sal6 = '$id_sal' AND ( usu_usu_sal != '$id' OR tip_usu_sal != '$tipo' )
    ";

    // echo $sqlusuarios;

    $resultadoUsuarios = mysqli_query( $db, $sqlusuarios );

    // BUCLE DE LISTADO DE USUARIOS PARTICIPANTES DE LA SALA DIFERENTES DE MI PARA AGREGARLES SU NOTIFICACION
    while( $filausuarios = mysqli_fetch_assoc( $resultadoUsuarios ) ){
      
      $tip_not_men = $filausuarios['tip_usu_sal'];
      $use_not_men = $filausuarios['usu_usu_sal'];

      // OBTENCION DE ULTIMO id_men PARA AGREGAR id_men A estatus_mensaje
      $id_men = obtenerUltimoIdentificadorServer( 'mensaje', 'id_men' );

      // SQL DE ALTA DE estatus_mensaje
      $sqlEstatus = "
        INSERT INTO estatus_mensaje ( use_est_men, tip_est_men, id_men2) 
        VALUES ( '$use_not_men', '$tip_not_men', '$id_men')
      ";

      // echo $sqlEstatus;

      $resultadoEstatus = mysqli_query( $db, $sqlEstatus );

      // IMPRESION DE ERRORES
      if ( !$resultadoEstatus ) {
      
        echo $sqlEstatus;
      
      }

    }

    // ESTATUS

    // RETORNO DE id_sal PARA ACTUALIZAR FUNCIONES DEL FRONTEND
    echo $id_sal;


    // FIN NO EXISTE SALA
  } else {
    // EXISTE SALA 

    // AL EXISTIR LA SALA TENEMOS EL id_sal LISTO
    $id_sal = $_POST['id_sal'];

    // CASO DE ENVIO DE ARCHIVO ( OMITIR ESTA PARTE )
    if ( isset( $_FILES['arc_men']['name'] ) ) {
      

      $sql = "
        INSERT INTO mensaje ( hor_men, tip_men, use_men, id_sal4 ) 
        VALUES ( '$hor_men', '$tipo', '$id', '$id_sal' )
      ";

      // echo $sql;

      $resultado = mysqli_query( $db, $sql );

      $ultimo = obtenerUltimoIdentificadorServer( 'mensaje', 'id_men' );
      // ARCHIVO
    
      $arc_men = $_FILES['arc_men']['name'];
      $archivo = "archivo-00".$ultimo.".".end(explode(".", $arc_men));

      $carpeta_destino = '../../archivos/';
      move_uploaded_file($_FILES['arc_men']['tmp_name'], $carpeta_destino.$archivo);

      //ACTUALIZACION EN EL ALUMNO DE LA FOTO RENOMBRADA Y LA REFERENCIA DE SU IMAGEN
      $sqlUpdate = "UPDATE mensaje SET arc_men = '$archivo' WHERE id_men = '$ultimo'";

      $resultadoUpdate = mysqli_query($db, $sqlUpdate);


      if ( !$resultadoUpdate ) {
      
        echo $sqlUpdate;
      
      }
      // FIN ARCHIVO


    } else {
      // CASO DE ENVIO DE TEXTO

      // MENSAJE
      $men_men = filter_var( $_POST['mensaje'], FILTER_SANITIZE_STRING);

      // SQL ALTA DE MENSAJE
      $sql = "
        INSERT INTO mensaje ( hor_men, men_men, tip_men, use_men, id_sal4 ) 
        VALUES ( '$hor_men', '$men_men', '$tipo', '$id', '$id_sal' )
      ";

      $resultado = mysqli_query( $db, $sql );
    
    }
    

    // EJECUCION CORRECTA DE LA QUERY
    if ( $resultado ) {
      
      // NOTIFICACIONES Y ESTATUS

      // SQL PARA LISTADO DE SALAS ORDENADAS POR TIEMPO
      $sqlUpdateSala = "
        UPDATE sala
        SET
        fec_men_sal = '$hor_men'
        WHERE
        id_sal = '$id_sal'
      ";

      $resultadoUpdateSala = mysqli_query( $db, $sqlUpdateSala );

      // IMPRESION DE ERRORES
      if ( !$resultadoUpdateSala ) {
        echo $sqlUpdateSala;
      }

      // SQL DE LISTADO DE USUARIOS PARA INSERCION DE NOTIFICACION DIFERENTES A MI
      $sqlusuarios = "
        SELECT *
        FROM usuario_sala 
        WHERE id_sal6 = '$id_sal' AND ( usu_usu_sal != '$id' OR tip_usu_sal != '$tipo' )
      ";

      // echo $sqlusuarios;

      $resultadoUsuarios = mysqli_query( $db, $sqlusuarios );
  
      // OBTENCION DE ULTIMO id_men      
      $id_men = obtenerUltimoIdentificadorServer( 'mensaje', 'id_men' );

      // BUCLE DE LISTADO DE USUARIOS PARTICIPANTES DE LA SALA DIFERENTES DE MI PARA AGREGARLES SU NOTIFICACION
      while( $filausuarios = mysqli_fetch_assoc( $resultadoUsuarios ) ){
        
        $tip_not_men = $filausuarios['tip_usu_sal'];
        $use_not_men = $filausuarios['usu_usu_sal'];

        // SQL ALTA DE estatus_mensaje PARA NOTIFICACIONES A USUARIOS DIFERENTES DEL ALUMNO
        $sqlEstatus = "
          INSERT INTO estatus_mensaje ( use_est_men, tip_est_men, id_men2) 
          VALUES ( '$use_not_men', '$tip_not_men', '$id_men')
        ";

        // echo $sqlEstatus;

        $resultadoEstatus = mysqli_query( $db, $sqlEstatus );

        // IMPRESION DE ERRORES
        if ( !$resultadoEstatus ) {
        
          echo $sqlEstatus;
        
        }

      }

      // NOTIFICACIONES Y ESTATUS
      // RETORNO DE id_sal PARA ACTUALIZAR FUNCIONES DE FRONTEND
      echo $id_sal;

    } else {
      
      // IMPRESION DE ERRORES
      echo $sql;
    
    }
    // FIN MENSAJE 
    // FIN EXISTE SALA
  }
  
  //:B

?>