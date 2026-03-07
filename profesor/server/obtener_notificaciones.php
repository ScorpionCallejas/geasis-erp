<?php
  //ARCHIVO VIA AJAX PARA OBTENER NOTIFICACIONES DE ADMINISTRADOR
  //header.php//footer.php
  require('../inc/cabeceras.php');
  require('../inc/funciones.php');

  $inicio = $_POST['inicio'];
  $limite = $_POST['limite'];
  

  $sql = "
        
    SELECT fec_cal_act AS fecha, nom_for AS actividad, concat_ws(' ',nom_alu, app_alu, apm_alu) AS alumno, id_for_cop AS identificador_copia, fot_alu AS foto, tip_for AS tipo_actividad, nom_sub_hor AS clave, nom_mat AS nom_mat, id_alu_ram AS id_alu_ram, nom_gru AS nom_gru, id_blo AS id_blo, id_sub_hor AS id_sub_hor
    FROM cal_act
    INNER JOIN foro_copia ON foro_copia.id_for_cop = cal_act.id_for_cop2
    INNER JOIN sub_hor ON sub_hor.id_sub_hor = foro_copia.id_sub_hor2
    INNER JOIN foro ON foro.id_for = foro_copia.id_for1
    INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
    INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
    INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
    INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
    INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
    WHERE (fec_cal_act IS NOT null ) AND ( pun_cal_act IS NULL ) AND ( id_pro1 = '$id' )
    GROUP BY identificador_copia, fecha, actividad, alumno, foto, tipo_actividad, clave, nom_mat, id_alu_ram, nom_gru
    UNION
    SELECT fec_cal_act AS fecha, nom_ent AS actividad, concat_ws(' ',nom_alu, app_alu, apm_alu) AS alumno, id_ent_cop AS identificador_copia, fot_alu AS foto, tip_ent AS tipo_actividad, nom_sub_hor AS clave, nom_mat AS nom_mat, id_alu_ram AS id_alu_ram, nom_gru AS nom_gru, id_blo AS id_blo, id_sub_hor AS id_sub_hor
    FROM cal_act
    INNER JOIN entregable_copia ON entregable_copia.id_ent_cop = cal_act.id_ent_cop2
    INNER JOIN sub_hor ON sub_hor.id_sub_hor = entregable_copia.id_sub_hor3
    INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
    INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5
    INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
    INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
    INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
    INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
    WHERE (fec_cal_act IS NOT null ) AND ( pun_cal_act IS NULL ) AND ( id_pro1 = '$id' )
    GROUP BY identificador_copia, fecha, actividad, alumno, foto, tipo_actividad, clave, nom_mat, id_alu_ram, nom_gru
    UNION
    SELECT fec_cal_act AS fecha, nom_exa AS actividad, concat_ws(' ',nom_alu, app_alu, apm_alu) AS alumno, id_exa_cop AS identificador_copia, fot_alu AS foto, tip_exa AS tipo_actividad, nom_sub_hor AS clave, nom_mat AS nom_mat, id_alu_ram AS id_alu_ram, nom_gru AS nom_gru, id_blo AS id_blo, id_sub_hor AS id_sub_hor
    FROM cal_act
    INNER JOIN examen_copia ON examen_copia.id_exa_cop = cal_act.id_exa_cop2
    INNER JOIN sub_hor ON sub_hor.id_sub_hor = examen_copia.id_sub_hor4
    INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
    INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
    INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4
    INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
    INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
    INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
    WHERE (fec_cal_act IS NOT null ) AND ( pun_cal_act IS NULL ) AND ( id_pro1 = '$id' )
    GROUP BY identificador_copia, fecha, actividad, alumno, foto, tipo_actividad, clave, nom_mat, id_alu_ram, nom_gru
    ORDER BY fecha DESC
    LIMIT $inicio, $limite

  ";


  // echo $sql;

  

  $resultado = mysqli_query( $db, $sql );
  $contadorHeader = 0;

  while( $fila = mysqli_fetch_assoc( $resultado ) ){
    // VARIABLES RELEVANTES

    $fecha = $fila['fecha'];
    $actividad = $fila['actividad'];

    $id_blo = $fila['id_blo'];
    $id_sub_hor = $fila['id_sub_hor'];

    $id_alu_ram = $fila['id_alu_ram'];
    $alumno = $fila['alumno'];
    $foto = $fila['foto'];

    $identificador_copia = $fila['identificador_copia'];
    $tipo_actividad = $fila['tipo_actividad'];

    if ( $tipo_actividad == 'Examen' ) {
      $tipo_actividad = 'Cuestionario';
    }

    $clave = $fila['clave'];
    $nom_mat = $fila['nom_mat'];

    $nom_gru = $fila['nom_gru'];


    $notificacion = ( $contadorHeader + 1 )." - Tienes un ".strtolower( $tipo_actividad )." pendiente de revisar ( ".substr( $actividad, 0, 10 )."... ). Del grupo ".$nom_gru." de la asignatura de ".$nom_mat;
?>

    
      <a class="waves-effect  grey lighten-3"
        href="clase_contenido.php?id_sub_hor=<?php echo $id_sub_hor; ?>&id_blo=<?php echo $id_blo; ?>&tipo_actividad=<?php echo $tipo_actividad; ?>&identificador_copia=<?php echo $identificador_copia; ?>&titulo_actividad=<?php echo $actividad; ?>"

        style=" border-radius: 20px;"
      >

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