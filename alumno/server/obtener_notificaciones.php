<?php
  //ARCHIVO VIA AJAX PARA OBTENER NOTIFICACIONES DE ADMINISTRADOR
  //header.php//footer.php
  require('../inc/cabeceras.php');
  require('../inc/funciones.php');

  $inicio = $_POST['inicio'];
  $limite = $_POST['limite'];
  

  $fechaHoy = date('Y-m-d');

  $sql = "
    SELECT id_pro_alu_ram AS identificador_copia, nom_pro AS actividad, pun_pro AS puntaje, ini_pro_alu_ram AS inicio, fin_pro_alu_ram AS fin, 'Trabajo' AS tipo_actividad, id_alu_ram AS id_alu_ram, id_pro_alu_ram AS id_cop, fec_pro_alu_ram AS fecha, 'N/A' AS nom_mat, 'N/A' AS id_blo, 'N/A' AS id_sub_hor, nom_gru AS nom_gru
    FROM proyecto_alu_ram
    INNER JOIN proyecto ON proyecto.id_pro = proyecto_alu_ram.id_pro1
    INNER JOIN alu_ram ON alu_ram.id_alu_ram = proyecto_alu_ram.id_alu_ram15
    INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
    INNER JOIN grupo ON grupo.id_gru = proyecto.id_gru2
    WHERE  ini_pro_alu_ram <= '$fechaHoy' AND fin_pro_alu_ram >= '$fechaHoy' AND id_alu = '$id' AND fec_pro_alu_ram IS NULL
        UNION

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
    ORDER BY inicio DESC
    LIMIT $inicio, $limite

  ";


  // echo $sql;

  

  $resultado = mysqli_query( $db, $sql );
  $contadorHeader = 0;

  $reemplazoAcentos = array(    
   "'"=>'`', '"'=>'`' 
  );

  while( $fila = mysqli_fetch_assoc( $resultado ) ){
    // VARIABLES RELEVANTES

    $fecha = $fila['fecha'];

    $actividad = strtr( $fila['actividad'], $reemplazoAcentos );

    $id_blo = $fila['id_blo'];
    $id_sub_hor = $fila['id_sub_hor'];

    $identificador_copia = $fila['identificador_copia'];
    $tipo_actividad = $fila['tipo_actividad'];

    $nom_mat = $fila['nom_mat'];

    $nom_gru = $fila['nom_gru'];

    $id_alu_ram = $fila['id_alu_ram'];


    if ( $tipo_actividad == 'Trabajo' ) {
      
      $notificacion = "Tienes un ".mb_strtolower( 'trabajo especial' )." pendiente por realizar y entregar presencialmente en tu plantel ( ".substr( $actividad, 0, 20 )."... ).";

    } else {

      if ( $tipo_actividad == 'Examen' ) {
        $tipo_actividad = 'Cuestionario';
      }

      $notificacion = "Tienes un ".mb_strtolower( $tipo_actividad )." pendiente de revisar ( ".substr( $actividad, 0, 20 )."... ). De la asignatura de ".$nom_mat;

    }
    


    
?>
      

      <?php  
        if ( $tipo_actividad == 'Trabajo' ) {

      ?>
          <a class="waves-effect  grey lighten-3"
            href="trabajos_especiales.php?id_alu_ram=<?php echo $id_alu_ram; ?>&id_pro_alu_ram=<?php echo $identificador_copia; ?>"

            style=" border-radius: 20px;"
          >

      <?php
        } else {
      ?>

          <a class="waves-effect  grey lighten-3"
            href="clase_contenido.php?id_sub_hor=<?php echo $id_sub_hor; ?>&id_blo=<?php echo $id_blo; ?>&tipo_actividad=<?php echo $tipo_actividad; ?>&identificador_copia=<?php echo $identificador_copia; ?>&titulo_actividad=<?php echo $actividad; ?>&id_alu_ram=<?php echo $id_alu_ram; ?>"

            style=" border-radius: 20px;"
          >

      <?php
        }
      ?>
    
      

          <div class="card grey lighten-3 p-1 waves-effect" title="<?php echo $notificacion; ?>" style="height: 85px; border-radius: 20px;">    
              <div class="row p-1">
                <div class="col-md-2 text-right" style="position: relative;">
                  <br>

                  <?php  
                    if ( $tipo_actividad == 'Foro' ) {
                  ?>
                      <i class="fas fa-comment-dots fa-2x" style="position: absolute; top: 25%; right: -15%; color: #bdbdbd;"></i>

                  <?php
                    } else if ( $tipo_actividad == 'Entregable' ) {
                  ?>
                      <i class="fas fa-file-alt fa-2x" style="position: absolute; top: 25%; right: -15%; color: #bdbdbd;"></i>
                  <?php
                    } else if ( $tipo_actividad == 'Examen' ) {
                  ?>
                      <i class="fas fa-diagnoses fa-2x" style="position: absolute; top: 25%; right: -15%; color: #bdbdbd;"></i>
                  <?php
                    } else if ( $tipo_actividad == 'Trabajo' ) {
                  ?>

                      <i class="fas fa-star fa-2x text-warning" style="position: absolute; top: 25%; right: -15%; color: #bdbdbd;"></i>

                  <?php
                    }
                  ?>
                  
                </div>

                <div class="col-md-10">
                    <span  style="font-size: 11px; color: #616161; line-height: 1.6;" class="p-1 font-weight-normal btn-link">
                      <?php echo substr( $notificacion, 0, 110 )."..."; ?>
                    </span>
                    
                  
                </div>
              </div>
          </div>
        
      </a>
    
<?php
    $contadorHeader++;
  // FIN WHILE
  }

?>