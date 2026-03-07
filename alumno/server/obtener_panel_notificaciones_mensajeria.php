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
    if ( obtener_conteo_notificaciones_usuario_server( $id, $tipo ) > 0 ) {
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
    echo obtener_conteo_notificaciones_usuario_server( $id, $tipo );
  ?>
  </span>
  
</a><!-- 



<div class="dropdown-menu grey lighten-2" style="position: absolute; left: -100px;">
  
  <div class="row ">

    <div class="col-md-12 text-center">
      <a href="mensajes2.php" class="btn-link grey lighten-2 font-weight-bold grey-text">
        <i class="far fa-envelope-open"></i> Ir a mensajería
      </a>
    </div>

  </div>

</div> -->