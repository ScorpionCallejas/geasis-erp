<?php 
  //ARCHIVO VIA AJAX PARA MANDAR MSJ A CONTACTO DESDE EL MESSENGER, 
  //mensajes.php
  require('../inc/cabeceras.php');
  require('../inc/funciones.php');

  

  // MENSAJE
  $hor_men = date('Y-m-d H:i:s');


  if ( isset( $_POST['id_usuario'] ) && ( isset( $_POST['id_sal'] ) && $_POST['id_sal'] == 'Falso' ) ) {
    //  NO EXISTE SALA


    $id_usuario = $_POST['id_usuario'];
    $tipo_usuario = $_POST['tipo_usuario'];

    // SALA
    $sql = "
      
      INSERT INTO sala ( fec_men_sal ) 
      VALUES ( '$hor_men' )

    ";

    $resultado = mysqli_query( $db, $sql );

    if ( !$resultado ) {
      
      echo $sql;
    
    }

    $id_sal = obtenerUltimoIdentificadorServer( 'sala', 'id_sal' );
    // SALA

    // USUARIOS
    $sql3 = "
      
      INSERT INTO usuario_sala ( usu_usu_sal, tip_usu_sal, id_sal6 ) 
      VALUES ( '$id', '$tipo', '$id_sal' ), ( '$id_usuario',  '$tipo_usuario', '$id_sal' )

    ";

    $resultado3 = mysqli_query( $db, $sql3 );

    if ( !$resultado3 ) {
    
      echo $sql3;
    
    }
    // USUARIOS


    // MENSAJE
    

    $men_men = filter_var( $_POST['mensaje'], FILTER_SANITIZE_STRING);

    $sql2 = "
      INSERT INTO mensaje ( hor_men, men_men, tip_men, use_men, id_sal4 ) 
      VALUES ( '$hor_men', '$men_men', '$tipo', '$id', '$id_sal' )
    ";

    $resultado2 = mysqli_query( $db, $sql2 );

    if ( !$resultado2 ) {
      
      echo $sql2;

    }
    // MENSAJE


    // NOTIFICACIONES Y ESTATUS
    $sqlUpdateSala = "
      UPDATE sala
      SET
      fec_men_sal = '$hor_men'
      WHERE
      id_sal = '$id_sal'
    ";

    $resultadoUpdateSala = mysqli_query( $db, $sqlUpdateSala );

    if ( !$resultadoUpdateSala ) {
      echo $sqlUpdateSala;
    }


    $sqlusuarios = "
      SELECT *
      FROM usuario_sala 
      WHERE id_sal6 = '$id_sal' AND ( usu_usu_sal != '$id' OR tip_usu_sal != '$tipo' )
    ";

    // echo $sqlusuarios;

    $resultadoUsuarios = mysqli_query( $db, $sqlusuarios );

    while( $filausuarios = mysqli_fetch_assoc( $resultadoUsuarios ) ){
      
      $tip_not_men = $filausuarios['tip_usu_sal'];
      $use_not_men = $filausuarios['usu_usu_sal'];

      $sql2 = "
        INSERT INTO notificacion_mensaje ( est_not_men, tip_not_men, use_not_men, id_sal5 ) 
        VALUES ( 'Pendiente', '$tip_not_men', '$use_not_men', '$id_sal' )
      ";

      $resultado2 = mysqli_query( $db, $sql2 );

      if ( !$resultado2 ) {
      
        echo $sql2;
      
      }


      $id_men = obtenerUltimoIdentificadorServer( 'mensaje', 'id_men' );

      $sqlEstatus = "
        INSERT INTO estatus_mensaje ( use_est_men, tip_est_men, id_men2) 
        VALUES ( '$use_not_men', '$tip_not_men', '$id_men')
      ";

      // echo $sqlEstatus;

      $resultadoEstatus = mysqli_query( $db, $sqlEstatus );

      if ( !$resultadoEstatus ) {
      
        echo $sqlEstatus;
      
      }

    }

    // NOTIFICACIONES Y ESTATUS


    echo $id_sal;


    // FIN NO EXISTE SALA
  } else {
    // EXISTE SALA 

    // echo 'jaja';

    $id_sal = $_POST['id_sal'];

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

      $men_men = filter_var( $_POST['mensaje'], FILTER_SANITIZE_STRING);

      $sql = "
        INSERT INTO mensaje ( hor_men, men_men, tip_men, use_men, id_sal4 ) 
        VALUES ( '$hor_men', '$men_men', '$tipo', '$id', '$id_sal' )
      ";

      $resultado = mysqli_query( $db, $sql );
    
    }
    

    

    if ( $resultado ) {
      
      // NOTIFICACIONES Y ESTATUS
      $sqlUpdateSala = "
        UPDATE sala
        SET
        fec_men_sal = '$hor_men'
        WHERE
        id_sal = '$id_sal'
      ";

      $resultadoUpdateSala = mysqli_query( $db, $sqlUpdateSala );

      if ( !$resultadoUpdateSala ) {
        echo $sqlUpdateSala;
      }


      $sqlusuarios = "
        SELECT *
        FROM usuario_sala 
        WHERE id_sal6 = '$id_sal' AND ( usu_usu_sal != '$id' OR tip_usu_sal != '$tipo' )
      ";

      // echo $sqlusuarios;

      $resultadoUsuarios = mysqli_query( $db, $sqlusuarios );
      
      $id_men = obtenerUltimoIdentificadorServer( 'mensaje', 'id_men' );

      while( $filausuarios = mysqli_fetch_assoc( $resultadoUsuarios ) ){
        
        $tip_not_men = $filausuarios['tip_usu_sal'];
        $use_not_men = $filausuarios['usu_usu_sal'];

        $sql2 = "
          INSERT INTO notificacion_mensaje ( est_not_men, tip_not_men, use_not_men, id_sal5 ) 
          VALUES ( 'Pendiente', '$tip_not_men', '$use_not_men', '$id_sal' )
        ";

        $resultado2 = mysqli_query( $db, $sql2 );

        if ( !$resultado2 ) {
        
          echo $sql2;
        
        }


        $sqlEstatus = "
          INSERT INTO estatus_mensaje ( use_est_men, tip_est_men, id_men2) 
          VALUES ( '$use_not_men', '$tip_not_men', '$id_men')
        ";

        // echo $sqlEstatus;

        $resultadoEstatus = mysqli_query( $db, $sqlEstatus );

        if ( !$resultadoEstatus ) {
        
          echo $sqlEstatus;
        
        }

      }

      // NOTIFICACIONES Y ESTATUS

      echo $id_sal;

    } else {
      
      echo $sql;
    
    }
    // FIN MENSAJE 
    // FIN EXISTE SALA
  }
  
  

  

?>