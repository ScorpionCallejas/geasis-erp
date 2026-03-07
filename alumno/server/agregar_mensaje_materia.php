<?php 
  //ARCHIVO VIA AJAX PARA MANDAR MSJ A CONTACTO DESDE EL MESSENGER, 
  //mensajes.php
  require('../inc/cabeceras.php');
  require('../inc/funciones.php');
  //***CODIGO COMPLETAMENTE REUTILIZABLE 
  
  $hora = date('Y-m-d H:i:s');
  $sala = $_POST['id_sal'];
  
  if ( isset( $_FILES['arc_men']['name'] ) ) {
    $arc_men = $_FILES['arc_men']['name'];


    $sql = "INSERT INTO mensaje(use_men, tip_men, hor_men, id_sal4) VALUES('$id', '$tipo', '$hora', '$sala')";

    $resultado = mysqli_query($db, $sql);

    if ( $resultado ) {

      $ultimo = obtenerUltimoIdentificadorServer( 'mensaje', 'id_men' );

      generarNotificacionMensaje( $sala, $tipoUsuario, $id );

      // ARCHIVO
      $arc_men = $_FILES['arc_men']['name'];
      $archivo = "archivo-00".$ultimo.".".end(explode(".", $arc_men));

      $carpeta_destino = '../../archivos/';
      move_uploaded_file($_FILES['arc_men']['tmp_name'], $carpeta_destino.$archivo);

      //ACTUALIZACION EN EL ALUMNO DE LA FOTO RENOMBRADA Y LA REFERENCIA DE SU IMAGEN
      $sqlUpdate = "UPDATE mensaje SET arc_men = '$archivo' WHERE id_men = '$ultimo'";

      $resultado2 = mysqli_query($db, $sqlUpdate);
      if ( !$resultado2 ) {
        echo $sqlUpdate;
      }
    } else {
      
      echo $sql;
    
    }
    

    // FIN ARCHIVO
  } else {
    $mensaje = $_POST['msj'];
    // echo "mensaje: ".$mensaje."<br>";
    // echo "soy: ".$soy."<br>";
    // echo "id_sal: ".$sala."<br>";

    $sql = "INSERT INTO mensaje(use_men, tip_men, men_men, hor_men, id_sal4) VALUES('$id', '$tipo', '$mensaje', '$hora', '$sala')";

    $resultado = mysqli_query($db, $sql);

    if ( $resultado ) {
      echo 'Insertado con exito';
      
      generarNotificacionMensaje( $sala, $tipoUsuario, $id );

    }else{
      echo "error al insertar mensaje: ";
    }

  }
  

?>