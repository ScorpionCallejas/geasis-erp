<?php

  require('../inc/cabeceras.php');
  require('../inc/funciones.php');
?>

<style>
  .my-custom-scrollbar-1 {
    position: relative;
    height: 20vh;
    overflow: auto;
    max-width: 100%;
  }
</style>
<a class=" nav-link dropdown-toggle" href="mensajes.php" target="blank" role="button">
                      
  Mensajería

  <?php  
    $notificaciones = obtener_conteo_notificaciones_usuario_server( $id, $tipo );
    if ( $notificaciones > 0 ) {
  ?>
      <i class="fas fa-envelope   pr-1 animated swing infinite" id="icono_mensajeria"></i>
  <?php
    } else {
  ?>
      <i class="fas fa-envelope   pr-1 " id="icono_mensajeria"></i>
  <?php
    }
  ?>
  <span class="badge badge-danger notification rounded-circle">
  <?php  
    echo $notificaciones;
  ?>
  </span>
  
</a>