<?php
  //ARCHIVO VIA AJAX PARA OBTENER NOTIFICACIONES DE ADMINISTRADOR
  //header.php//footer.php
  require('../inc/cabeceras.php');
  require('../inc/funciones.php');
  
  $fechaHoy = date('Y-m-d');

  $sqlNotificacionesCobros = "
        
    SELECT id_for_cop AS identificador_copia, nom_for AS actividad, pun_for AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, tip_for AS tipo_actividad, id_alu_ram AS id_alu_ram, id_for_cop AS id_cop, fec_cal_act AS fecha, nom_mat AS nom_mat, id_blo AS id_blo, id_sub_hor AS id_sub_hor, nom_gru AS nom_gru
    FROM cal_act
    INNER JOIN foro_copia ON foro_copia.id_for_cop = cal_act.id_for_cop2
    INNER JOIN foro ON foro.id_for = foro_copia.id_for1
    INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
    INNER JOIN materia ON materia.id_mat = bloque.id_mat6
    INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
    INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
    INNER JOIN sub_hor ON sub_hor.id_sub_hor = foro_copia.id_sub_hor2
    INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
    WHERE  ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' AND id_alu = '$id' AND fec_cal_act IS NULL
        UNION
    SELECT id_ent_cop AS identificador_copia, nom_ent AS actividad, pun_ent AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, tip_ent AS tipo_actividad, id_alu_ram AS id_alu_ram, id_ent_cop AS id_cop, fec_cal_act AS fecha, nom_mat AS nom_mat, id_blo AS id_blo, id_sub_hor AS id_sub_hor, nom_gru AS nom_gru
    FROM cal_act
    INNER JOIN entregable_copia ON entregable_copia.id_ent_cop = cal_act.id_ent_cop2
    INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
    INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5
    INNER JOIN materia ON materia.id_mat = bloque.id_mat6
    INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
    INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
    INNER JOIN sub_hor ON sub_hor.id_sub_hor = entregable_copia.id_sub_hor3
    INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
    WHERE  ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' AND id_alu = '$id' AND fec_cal_act IS NULL
    UNION
        SELECT id_exa_cop AS identificador_copia, nom_exa AS actividad, pun_exa AS puntaje, ini_cal_act AS inicio, fin_cal_act AS fin, tip_exa AS tipo_actividad, id_alu_ram AS id_alu_ram, id_exa_cop AS id_cop, fec_cal_act AS fecha, nom_mat AS nom_mat, id_blo AS id_blo, id_sub_hor AS id_sub_hor, nom_gru AS nom_gru
    FROM cal_act
    INNER JOIN examen_copia ON examen_copia.id_exa_cop = cal_act.id_exa_cop2
    INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
    INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
    INNER JOIN materia ON materia.id_mat = bloque.id_mat6
    INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
    INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
    INNER JOIN sub_hor ON sub_hor.id_sub_hor = examen_copia.id_sub_hor4
    INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
    WHERE  ini_cal_act <= '$fechaHoy' AND fin_cal_act >= '$fechaHoy' AND id_alu = '$id' AND fec_cal_act IS NULL
    ORDER BY inicio

  ";

    $resultadoNotificacionesCobros = mysqli_query( $db, $sqlNotificacionesCobros );
                    
    if ( $resultadoNotificacionesCobros ) {
      
      $notificacionesCobros = mysqli_num_rows( $resultadoNotificacionesCobros );

      if ( ($notificacionesCobros > 0) && ($notificacionesCobros < 10) ) {
      // SI HAY NOTIFICACIONES
?>
        <a class=" nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      
          Notificaciones
          <i class="fas fa-bell pr-1 animated swing infinite"></i>
          <span class="badge badge-danger notification rounded-circle">
            <?php echo $notificacionesCobros; ?>
          </span>
          
        </a>

        <div class="dropdown-menu grey lighten-2">

          <form class="px-4 py-3 table-wrapper-scroll-y my-custom-scrollbar" id="formularioNotificaciones">
  
            
            
            <table class="table table-sm" id="tablaValidaciones">
            <!-- NO HAY HEADER DE TABLA -->
              <tbody >
                <div class="accordion" id="accordionExample275">

                </div>
              </tbody>
              
            </table>
 
            <div id="load_data_message" class="text-center"></div>
          </form>

        </div>

<?php
      }else if ($notificacionesCobros >=10) {
        ?>
         <a class=" nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?php echo $notificacionesCobros; ?> notificaciones pendientes">
      
          Notificaciones
          <i class="fas fa-bell pr-1 animated swing"></i>
          <span class="badge badge-danger notification rounded-circle">
            +9
          </span>

        </a>

        <div class="dropdown-menu grey lighten-2">

          <form class="px-4 py-3 table-wrapper-scroll-y my-custom-scrollbar" id="formularioNotificaciones" style="height: 210px;">
        
            
            <table class="table table-sm" id="tablaValidaciones">
            <!-- NO HAY HEADER DE TABLA -->
              <tbody >
                <div class="accordion" id="accordionExample275">

                </div>
              </tbody>
              
            </table>
 
            <div id="load_data_message" class="text-center"></div>
          </form>
          
        </div>

  <?php
      } else{
      // NO HAY NOTIFICACIONES
  ?>
        <a class=" nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      
          Notificaciones
          <i class="fas fa-bell pr-1 animated swing"></i>
          <span class="badge badge-danger notification rounded-circle">
            0
          </span>

        </a>

        <div class="dropdown-menu grey lighten-2">

          <form class="px-4 py-3 table-wrapper-scroll-y my-custom-scrollbar" id="formularioNotificaciones" style="height: 210px;">
        
            
            <table class="table table-sm" id="tablaValidaciones">
            <!-- NO HAY HEADER DE TABLA -->
              <tbody >
                <div class="accordion" id="accordionExample275">

                </div>
              </tbody>
              
            </table>
 
            <div id="load_data_message" class="text-center"></div>
          </form>
          
        </div>

  <?php
      }

    }else{
      echo $sqlNotificacionesCobros;
    }
?>


<script>
  // TABLA NOTIFICACIONES DE HEADER
  $('#tablaValidaciones').removeClass('bordeGrisTabla');
  
  var limite = 10;
  var inicio = 0;
  var action = 'inactive';
  function obtener_notificaciones(limite, inicio){
      $.ajax({
         url: "server/obtener_notificaciones.php",
         method: "POST",
         data: {limite, inicio},
         cache: false,
         success:function(data) {
              $('#accordionExample275').append(data);
              if(data == '')
              {
               $('#load_data_message').html('<label class="animated fadeInDown letraPequena">¡No hay más registros!</label>');
               action = 'active';
              }
              else
              {
               $('#load_data_message').html('<label class="letraPequena"><i class="fas fa-spinner fa-pulse"></i> Cargando...</label>');
               action = "inactive";
              }
              


              $(".actividadPendiente").addClass('link-chido');
          }
      });
  }

  if(action == 'inactive') {
      action = 'active';
      obtener_notificaciones(limite, inicio);
  }
  $('#formularioNotificaciones').scroll(function(){
      if($('#formularioNotificaciones').scrollTop() + $('#formularioNotificaciones').height() >$('#formularioNotificaciones').height() && action == 'inactive') {
          action = 'active';
          inicio = inicio + limite;
          setTimeout(function(){
              obtener_notificaciones(limite, inicio);
          }, 1000);

       
      }
  });
</script>

<script>
  // PEGADO DE TOTAL DE NOTIFICACIONES EN BURGER

  var totalNotificaciones = <?php echo $notificacionesCobros; ?>;
  $( '#span_hamburguesa' ).html( totalNotificaciones ).removeAttr( 'title' ).attr( 'Tienes '+totalNotificaciones+' actividades pendientes por revisar' );
  // FIN PEGADO DE TOTAL DE NOTIFICACIONES EN BURGER
</script>