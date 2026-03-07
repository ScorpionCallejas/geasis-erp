<?php 
  //ARCHIVO VIA AJAX PARA MANDAR MSJ A CONTACTO DESDE EL MESSENGER, 
  //mensajes.php
  require('../inc/cabeceras.php');
  require('../inc/funciones.php');

  $hora = date('Y-m-d H:i:s');

  if ( ( isset( $_POST['variable2'] ) ) && ( isset( $_POST['soy2'] ) ) ) {
    
    $arc_con = $_FILES['arc_con']['name'];

    $soy =  $_POST['soy2'];
    $sala = $_POST['variable2'];

    if ($soy == 'use1_con1') {
        $sqlInsertarMensaje = "INSERT INTO con1(use1_con1, tip1_con1, hor_con1, id_sal2) VALUES('$id', '$tipo', '$hora', '$sala')";

        $resultadoInsertarMensaje = mysqli_query($db, $sqlInsertarMensaje);

        if ($resultadoInsertarMensaje) {

          echo 'Insertado con exito';
        }else{
          echo "error al insertar mensaje:";
          echo $sqlInsertarMensaje;
        }

        $ultimo = obtenerUltimoIdentificadorServer( 'con1', 'id_con1' );

        generarNotificacionMensaje( $sala, $tipoUsuario, $id );

        // ARCHIVO

        $arc_con = $_FILES['arc_con']['name'];
        $archivo = "archivo-00".$ultimo.".".end(explode(".", $arc_con));

        $carpeta_destino = '../../archivos/';
        move_uploaded_file($_FILES['arc_con']['tmp_name'], $carpeta_destino.$archivo);

        //ACTUALIZACION EN EL ALUMNO DE LA FOTO RENOMBRADA Y LA REFERENCIA DE SU IMAGEN
        $sqlUpdate = "UPDATE con1 SET arc_con = '$archivo' WHERE id_con1 = '$ultimo'";

        mysqli_query($db, $sqlUpdate);

        // FIN ARCHIVO

    }else{
        $sqlInsertarMensaje = "INSERT INTO con2(use2_con2, tip2_con2, hor_con2, id_sal3) VALUES('$id', '$tipo', '$hora', '$sala')";

        $resultadoInsertarMensaje = mysqli_query($db, $sqlInsertarMensaje);

        if ($resultadoInsertarMensaje) {
          echo 'Insertado con exito';
        }else{
          echo "error al insertar mensaje: ";
          echo $sqlInsertarMensaje;
        }

        $ultimo = obtenerUltimoIdentificadorServer( 'con2', 'id_con2' );

        generarNotificacionMensaje( $sala, $tipoUsuario, $id );
        // ARCHIVO

        $arc_con = $_FILES['arc_con']['name'];
        $archivo = "archivo-00".$ultimo.".".end(explode(".", $arc_con));

        $carpeta_destino = '../../archivos/';
        move_uploaded_file($_FILES['arc_con']['tmp_name'], $carpeta_destino.$archivo);

        //ACTUALIZACION EN EL ALUMNO DE LA FOTO RENOMBRADA Y LA REFERENCIA DE SU IMAGEN
        $sqlUpdate = "UPDATE con2 SET arc_con = '$archivo' WHERE id_con2 = '$ultimo'";

        mysqli_query($db, $sqlUpdate);

        // FIN ARCHIVO

    }


    

  } else {


    $mensaje = $_POST['msj'];
    $soy =  $_POST['soy'];
    
    $sala = $_POST['id_sal'];

    if ($soy == 'use1_con1') {
        $sqlInsertarMensaje = "INSERT INTO con1(use1_con1, tip1_con1, men_con1, hor_con1, id_sal2) VALUES('$id', '$tipo', '$mensaje', '$hora', '$sala')";

        $resultadoInsertarMensaje = mysqli_query($db, $sqlInsertarMensaje);

        if ($resultadoInsertarMensaje) {


          generarNotificacionMensaje( $sala, $tipoUsuario, $id );

          echo 'Insertado con exito';

        }else{
          echo "error al insertar mensaje: ";
        }

    }else{
        $sqlInsertarMensaje = "INSERT INTO con2(use2_con2, tip2_con2, men_con2, hor_con2, id_sal3) VALUES('$id', '$tipo', '$mensaje', '$hora', '$sala')";

        $resultadoInsertarMensaje = mysqli_query($db, $sqlInsertarMensaje);

        if ($resultadoInsertarMensaje) {


          generarNotificacionMensaje( $sala, $tipoUsuario, $id );
          echo 'Insertado con exito';
        }else{
          echo "error al insertar mensaje: ";
        }

    }

  }
  

  

?>