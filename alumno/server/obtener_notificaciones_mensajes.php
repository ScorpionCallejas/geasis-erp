<?php
  //ARCHIVO VIA AJAX PARA OBTENER NOTIFICACIONES DE ADMINISTRADOR
  //header.php//footer.php
  require('../inc/cabeceras.php');
  require('../inc/funciones.php');

  $inicio = $_POST['inicio'];
  $limite = $_POST['limite'];
  

  $sql = "
        
    SELECT *
    FROM notificacion_mensaje
    INNER JOIN sala ON sala.id_sal = notificacion_mensaje.id_sal5
    WHERE ( tip_not_men = '$tipoUsuario' ) AND ( est_not_men = 'Pendiente' ) AND ( use_not_men = '$id' )
    GROUP BY id_sal
    ORDER BY fec_not_men DESC
    LIMIT $inicio, $limite
    
  ";



  // echo $sql;

  

  $resultado = mysqli_query( $db, $sql );
  $contadorHeader = 0;

  while( $fila = mysqli_fetch_assoc( $resultado ) ){
    // VARIABLES RELEVANTES

    // echo $fila['fec_not_men'];
    $id_sal = $fila['id_sal'];

    if ( $fila['id_sub_hor6'] == NULL ) {
      
      $datos = obtenerDatosUsuarioSalaServer( $id_sal, $tipoUsuario, $id );
      $usuarioEmisor = obtenerNombreUsuarioServer( $datos['tipo_usuario'], $datos['id_usuario'] );
?>
      
      <a class="waves-effect  grey lighten-3"
        href="mensajes.php?id_sal=<?php echo $id_sal; ?>"

        style=" border-radius: 20px;"
      >

          <div class="card grey lighten-3 p-1 waves-effect" title="Haz click para revisar el mensaje" style="height: 85px; border-radius: 20px;">    
              <div class="row p-1">
                <div class="col-md-2 text-right" style="position: relative;">
                  
                  <i class="fas fa-envelope fa-2x" style="position: absolute; top: 25%; right: -15%; color: #bdbdbd;"></i>
                  
                  
                </div>

                <div class="col-md-10">
                    <span  style="font-size: 11px; color: #616161; line-height: 1.6;" class="p-1 font-weight-normal btn-link">
                      Tienes mensajes nuevos sin revisar del <?php echo mb_strtolower( $datos['tipo_usuario'] ); ?> <?php
                        echo obtenerNombreUsuarioServer( $datos['tipo_usuario'], $datos['id_usuario'] )." registrado el ".
                        fechaFormateadaCompacta( $fila['fec_not_men'] );
                      ?>
                    </span>

                    
                  
                </div>
              </div>
          </div>
        
      </a>

<?php
    
    } else {

      $id_sub_hor = $fila['id_sub_hor6'];
      $datos = obtenerDatosGrupalesServer( $id_sub_hor );

?>

      <a class="waves-effect  grey lighten-3"
        href="mensajeria_materia.php?id_sub_hor=<?php echo $id_sub_hor; ?>&id_sal=<?php echo $id_sal; ?>"
        style=" border-radius: 20px;"
      >

          <div class="card grey lighten-3 p-1 waves-effect" title="Haz click para revisar el mensaje" style="height: 85px; border-radius: 20px;">    
              <div class="row p-1">
                <div class="col-md-2 text-right" style="position: relative;">
                  
                  <i class="fas fa-envelope fa-2x" style="position: absolute; top: 25%; right: -15%; color: #bdbdbd;"></i>
                  
                  
                </div>

                <div class="col-md-10">
                    <span  style="font-size: 11px; color: #616161; line-height: 1.6;" class="p-1 font-weight-normal btn-link">
                      Tienes mensajes nuevos sin revisar del grupo de <?php echo mb_strtolower( $datos['nom_mat'] )." de la materia de ".$datos['nom_mat'].". Registrado el ".fechaFormateadaCompacta( $fila['fec_not_men'] ); ?>.
                    </span>

                    
                  
                </div>
              </div>
          </div>
        
      </a>

<?php
    }
?>

    
      
    
<?php
    $contadorHeader++;
  // FIN WHILE
  }

?>