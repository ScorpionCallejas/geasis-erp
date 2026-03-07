<?php 

  //mensajes.php
  require('../inc/cabeceras.php');
  require('../inc/funciones.php');


  
?>

<ul class="list-unstyled friend-list">


  <?php
    $sqlSalas = "
      select *, GREATEST (IFNULL(MAX(hor_con1), '0000-00-00 00:00:00'), IFNULL(MAX(hor_con2),'0000-00-00 00:00:00')) AS fecha
      FROM sala
      right JOIN con1 ON con1.id_sal2 = sala.id_sal
      LEFT JOIN con2 ON con2.id_sal3 = sala.id_sal
      WHERE use1_sal = '$id' AND tip1_sal = '$tipo' OR use2_sal = '$id' AND tip2_sal = '$tipo'
      GROUP BY id_sal
      ORDER BY fecha DESC
  
    ";


    $resultadoSalas = mysqli_query($db, $sqlSalas);

  
    while ($datos = mysqli_fetch_assoc($resultadoSalas)) {
  
  ?>

    <?php

        if ($datos['use1_sal'] == $id && $datos['tip1_sal'] == $tipo){

        
          $use2_sal = $datos['use2_sal'];
          $tip2_sal = $datos['tip2_sal'];
          //$men_con2 = $datos['men_con2'];

          if ($tip2_sal == 'Ejecutivo') {
            $sqlDatosSala = "
              SELECT * 
              FROM ejecutivo 
              INNER JOIN empleado ON empleado.id_emp = ejecutivo.id_emp4 
              WHERE id_eje = '$use2_sal'";
            $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
            $datosSala = mysqli_fetch_assoc($resultadoDatosSala);

            $id_sal_temporal = $datos['id_sal'];

            //echo $id_sal_temporal;
            $sqlDatosChat = "
                              SELECT men_con1 as mensaje, hor_con1 AS hor, use1_con1 AS usuario, tip1_con1 AS tipoUsuario, arc_con AS arc_con
                              FROM con1
                              WHERE id_sal2 = '$id_sal_temporal'
                              UNION
                              SELECT men_con2 as mensaje, hor_con2 AS hor, use2_con2 AS usuario, tip2_con2 AS tipoUsuario, arc_con AS arc_con
                              FROM con2
                              WHERE id_sal3 = '$id_sal_temporal'
                              ORDER BY hor DESC
                              LIMIT 1

                              ";

            $resultadoDatosChat = mysqli_query($db, $sqlDatosChat);
            $filaDatosChat = mysqli_fetch_assoc($resultadoDatosChat);


            echo '<li class="white lighten-3 p-2 elementos" id_usuario="'.$use2_sal.'">
                  <a href="#" id="'.$datos['id_sal'].'" soy="'."use1_sal".'" class="d-flex  text-left botonesRespuestaPadre" style="position: relative;">'.obtenerTotalNotificacionesMensajesSalaServer( $id_sal_temporal, $tipoUsuario, $id ).'
                    <img src="'.obtenerValidacionFotoUsuario( $datosSala["fot_emp"] ).'" alt="avatar" class="avatar rounded-circle d-flex align-self-center mr-2 z-depth-1" width="70px" height="80px">
                    <div class="text-small">
                      '.obtenerTipoUsuarioCompleto( $datosSala["tip_eje"] ).' <br><span class="obtenerDatosUsuario" tipo_usuario="'.$datosSala["tip_eje"].'" id_usuario="'.$datosSala["id_eje"].'">'.$datosSala["nom_eje"].'</span>
                      '.( ( $filaDatosChat['arc_con'] != '' )?'<p title="Descargar: '.$filaDatosChat['arc_con'].'">
                              <i class="fas fa-file-download"></i> Descargar
                          </p>'
                        :
                          '<p class="last-message text-muted">
                          
                            '.recortarTexto( $filaDatosChat["mensaje"] ).'
                          </p>'
                        ).'
                    </div>
                    <div class="chat-footer">
                      <!-- <p class="text-smaller text-muted mb-0">Just now</p> -->
                      <!-- <span class="badge badge-danger float-right">1</span> -->
                    </div>
                    <i class="fas fa-times fa-1x red-text botonesRespuesta eliminacionSala" sala="'.$datos['id_sal'].'" title="Eliminar conversación con '.$datosSala["nom_eje"].'"></i>'.obtenerHoraFormateadaMensajeria( $filaDatosChat["hor"] ).'

                  </a>
                </li>';
          }else if ($tip2_sal == 'Admin'){
            $sqlDatosSala = "
              SELECT * 
              FROM admin
              INNER JOIN empleado ON empleado.id_emp = admin.id_emp7  
              WHERE id_adm = '$use2_sal'";
            $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
            $datosSala = mysqli_fetch_assoc($resultadoDatosSala);
            //echo $sqlDatosSala."<br>";


            $id_sal_temporal = $datos['id_sal'];

            // echo 'hello';
            $sqlDatosChat = "
                              SELECT men_con1 as mensaje, hor_con1 AS hor, use1_con1 AS usuario, tip1_con1 AS tipoUsuario, arc_con AS arc_con
                              FROM con1
                              WHERE id_sal2 = '$id_sal_temporal'
                              UNION
                              SELECT men_con2 as mensaje, hor_con2 AS hor, use2_con2 AS usuario, tip2_con2 AS tipoUsuario, arc_con AS arc_con
                              FROM con2
                              WHERE id_sal3 = '$id_sal_temporal'
                              ORDER BY hor DESC
                              LIMIT 1

                              ";

            $resultadoDatosChat = mysqli_query($db, $sqlDatosChat);
            $filaDatosChat = mysqli_fetch_assoc($resultadoDatosChat);

            echo '<li class="white lighten-3 p-2 elementos" id_usuario="'.$use2_sal.'">
                  <a href="#" id="'.$datos['id_sal'].'" soy="'."use1_sal".'" class="d-flex  text-left botonesRespuestaPadre" style="position: relative;">'.obtenerTotalNotificacionesMensajesSalaServer( $id_sal_temporal, $tipoUsuario, $id ).'
                    <img src="'.obtenerValidacionFotoUsuario( $datosSala["fot_emp"] ).'" alt="avatar" class="avatar rounded-circle d-flex align-self-center mr-1 z-depth-1" width="70px" height="80px" height="20%">
                    <div class="text-small">
                      '.obtenerTipoUsuarioCompleto( $datosSala["tip_adm"] ).' <br><span class="obtenerDatosUsuario" tipo_usuario="'.$datosSala["tip_adm"].'" id_usuario="'.$datosSala["id_adm"].'">'.$datosSala["nom_adm"].'</span>
                      '.( ( $filaDatosChat['arc_con'] != '' )?'<p title="Descargar: '.$filaDatosChat['arc_con'].'">
                              <i class="fas fa-file-download"></i> Descargar
                          </p>'
                        :
                          '<p class="last-message text-muted">
                          
                            '.recortarTexto( $filaDatosChat["mensaje"] ).'
                          </p>'
                        ).'
                    </div>
                    <div class="chat-footer">
                      <!-- <p class="text-smaller text-muted mb-0">Just now</p> -->
                      <!-- <span class="badge badge-danger float-right">1</span> -->
                    </div>
                    <i class="fas fa-times fa-1x red-text botonesRespuesta eliminacionSala" sala="'.$datos['id_sal'].'" title="Eliminar conversación con '.$datosSala["nom_adm"].'"></i>'.obtenerHoraFormateadaMensajeria( $filaDatosChat["hor"] ).'
                  </a>
                </li>';


          }else if ($tip2_sal == 'Adminge'){
            $sqlDatosSala = "
              SELECT * 
              FROM adminge
              INNER JOIN empleado ON empleado.id_emp = adminge.id_emp6  
              WHERE id_adg = '$use2_sal'";
            $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
            $datosSala = mysqli_fetch_assoc($resultadoDatosSala);
            //echo $sqlDatosSala."<br>";


            $id_sal_temporal = $datos['id_sal'];

            //echo $id_sal_temporal;
            $sqlDatosChat = "
                              SELECT men_con1 as mensaje, hor_con1 AS hor, use1_con1 AS usuario, tip1_con1 AS tipoUsuario, arc_con AS arc_con
                              FROM con1
                              WHERE id_sal2 = '$id_sal_temporal'
                              UNION
                              SELECT men_con2 as mensaje, hor_con2 AS hor, use2_con2 AS usuario, tip2_con2 AS tipoUsuario, arc_con AS arc_con
                              FROM con2
                              WHERE id_sal3 = '$id_sal_temporal'
                              ORDER BY hor DESC
                              LIMIT 1

                              ";

            $resultadoDatosChat = mysqli_query($db, $sqlDatosChat);
            $filaDatosChat = mysqli_fetch_assoc($resultadoDatosChat);

            echo '<li class="white lighten-3 p-2 elementos" id_usuario="'.$use2_sal.'">
                  <a href="#" id="'.$datos['id_sal'].'" soy="'."use1_sal".'" class="d-flex  text-left botonesRespuestaPadre" style="position: relative;">'.obtenerTotalNotificacionesMensajesSalaServer( $id_sal_temporal, $tipoUsuario, $id ).'
                    <img src="'.obtenerValidacionFotoUsuario( $datosSala["fot_emp"] ).'" alt="avatar" class="avatar rounded-circle d-flex align-self-center mr-1 z-depth-1" width="70px" height="80px">
                    <div class="text-small">
                      '.obtenerTipoUsuarioCompleto( $datosSala["tip_adg"] ).' <br><span class="obtenerDatosUsuario" tipo_usuario="'.$datosSala["tip_adg"].'" id_usuario="'.$datosSala["id_adg"].'">'.$datosSala["nom_adg"].'</span>
                      '.( ( $filaDatosChat['arc_con'] != '' )?'<p title="Descargar: '.$filaDatosChat['arc_con'].'">
                              <i class="fas fa-file-download"></i> Descargar
                          </p>'
                        :
                          '<p class="last-message text-muted">
                          
                            '.recortarTexto( $filaDatosChat["mensaje"] ).'
                          </p>'
                        ).'
                    </div>
                    <div class="chat-footer">
                      <!-- <p class="text-smaller text-muted mb-0">Just now</p> -->
                      <!-- <span class="badge badge-danger float-right">1</span> -->
                    </div>
                    <i class="fas fa-times fa-1x red-text botonesRespuesta eliminacionSala" sala="'.$datos['id_sal'].'" title="Eliminar conversación con '.$datosSala["nom_adg"].'"></i>'.obtenerHoraFormateadaMensajeria( $filaDatosChat["hor"] ).'
                  </a>
                </li>';


          }else if ($tip2_sal == 'Adminco'){
            $sqlDatosSala = "
              SELECT * 
              FROM adminco
              INNER JOIN empleado ON empleado.id_emp = adminco.id_emp5  
              WHERE id_adc = '$use2_sal'";
            $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
            $datosSala = mysqli_fetch_assoc($resultadoDatosSala);
            //echo $sqlDatosSala."<br>";


            $id_sal_temporal = $datos['id_sal'];

            //echo $id_sal_temporal;
            $sqlDatosChat = "
                              SELECT men_con1 as mensaje, hor_con1 AS hor, use1_con1 AS usuario, tip1_con1 AS tipoUsuario, arc_con AS arc_con
                              FROM con1
                              WHERE id_sal2 = '$id_sal_temporal'
                              UNION
                              SELECT men_con2 as mensaje, hor_con2 AS hor, use2_con2 AS usuario, tip2_con2 AS tipoUsuario, arc_con AS arc_con
                              FROM con2
                              WHERE id_sal3 = '$id_sal_temporal'
                              ORDER BY hor DESC
                              LIMIT 1

                              ";

            $resultadoDatosChat = mysqli_query($db, $sqlDatosChat);
            $filaDatosChat = mysqli_fetch_assoc($resultadoDatosChat);

            echo '<li class="white lighten-3 p-2 elementos" id_usuario="'.$use2_sal.'">
                  <a href="#" id="'.$datos['id_sal'].'" soy="'."use1_sal".'" class="d-flex  text-left botonesRespuestaPadre" style="position: relative;">'.obtenerTotalNotificacionesMensajesSalaServer( $id_sal_temporal, $tipoUsuario, $id ).'
                    <img src="'.obtenerValidacionFotoUsuario( $datosSala["fot_emp"] ).'" alt="avatar" class="avatar rounded-circle d-flex align-self-center mr-1 z-depth-1" width="70px" height="80px">
                    <div class="text-small">
                     '.obtenerTipoUsuarioCompleto( $datosSala["tip_adc"] ).' <br><span class="obtenerDatosUsuario" tipo_usuario="'.$datosSala["tip_adc"].'" id_usuario="'.$datosSala["id_adc"].'">'.$datosSala["nom_adc"].'</span>
                      '.( ( $filaDatosChat['arc_con'] != '' )?'<p title="Descargar: '.$filaDatosChat['arc_con'].'">
                              <i class="fas fa-file-download"></i> Descargar
                          </p>'
                        :
                          '<p class="last-message text-muted">
                          
                            '.recortarTexto( $filaDatosChat["mensaje"] ).'
                          </p>'
                        ).'
                    </div>
                    <div class="chat-footer">
                      <!-- <p class="text-smaller text-muted mb-0">Just now</p> -->
                      <!-- <span class="badge badge-danger float-right">1</span> -->
                    </div>

                    <i class="fas fa-times fa-1x red-text botonesRespuesta eliminacionSala" sala="'.$datos['id_sal'].'" title="Eliminar conversación con '.$datosSala["nom_adc"].'"></i>'.obtenerHoraFormateadaMensajeria( $filaDatosChat["hor"] ).'
                  </a>
                </li>';


          }else if ($tip2_sal == 'Profesor'){
            $sqlDatosSala = "
              SELECT * 
              FROM profesor
              INNER JOIN empleado ON empleado.id_emp = profesor.id_emp3  
              WHERE id_pro = '$use2_sal'";
            $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
            $datosSala = mysqli_fetch_assoc($resultadoDatosSala);
            //echo $sqlDatosSala."<br>";


            $id_sal_temporal = $datos['id_sal'];

            //echo $id_sal_temporal;
            $sqlDatosChat = "
                              SELECT men_con1 as mensaje, hor_con1 AS hor, use1_con1 AS usuario, tip1_con1 AS tipoUsuario, arc_con AS arc_con
                              FROM con1
                              WHERE id_sal2 = '$id_sal_temporal'
                              UNION
                              SELECT men_con2 as mensaje, hor_con2 AS hor, use2_con2 AS usuario, tip2_con2 AS tipoUsuario, arc_con AS arc_con
                              FROM con2
                              WHERE id_sal3 = '$id_sal_temporal'
                              ORDER BY hor DESC
                              LIMIT 1

                              ";

            $resultadoDatosChat = mysqli_query($db, $sqlDatosChat);
            $filaDatosChat = mysqli_fetch_assoc($resultadoDatosChat);

            echo '<li class="white lighten-3 p-2 elementos" id_usuario="'.$use2_sal.'">
                  <a href="#" id="'.$datos['id_sal'].'" soy="'."use1_sal".'" class="d-flex  text-left botonesRespuestaPadre" style="position: relative;">'.obtenerTotalNotificacionesMensajesSalaServer( $id_sal_temporal, $tipoUsuario, $id ).'
                    <img src="'.obtenerValidacionFotoUsuario( $datosSala["fot_emp"] ).'" alt="avatar" class="avatar rounded-circle d-flex align-self-center mr-1 z-depth-1" width="70px" height="80px">
                    <div class="text-small">
                      '.obtenerTipoUsuarioCompleto( $datosSala["tip_pro"] ).' <br><span class="obtenerDatosUsuario" tipo_usuario="'.$datosSala["tip_pro"].'" id_usuario="'.$datosSala["id_pro"].'">'.$datosSala["nom_pro"].'</span>
                      '.( ( $filaDatosChat['arc_con'] != '' )?'<p title="Descargar: '.$filaDatosChat['arc_con'].'">
                              <i class="fas fa-file-download"></i> Descargar
                          </p>'
                        :
                          '<p class="last-message text-muted">
                          
                            '.recortarTexto( $filaDatosChat["mensaje"] ).'
                          </p>'
                        ).'
                    </div>
                    <div class="chat-footer">
                      <!-- <p class="text-smaller text-muted mb-0">Just now</p> -->
                      <!-- <span class="badge badge-danger float-right">1</span> -->
                    </div>
                    <i class="fas fa-times fa-1x red-text botonesRespuesta eliminacionSala" sala="'.$datos['id_sal'].'" title="Eliminar conversación con '.$datosSala["nom_pro"].'"></i>'.obtenerHoraFormateadaMensajeria( $filaDatosChat["hor"] ).'
                  </a>
                </li>';


          }else if ($tip2_sal == 'Alumno'){
            $sqlDatosSala = "SELECT * FROM alumno WHERE id_alu = '$use2_sal'";
            $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
            $datosSala = mysqli_fetch_assoc($resultadoDatosSala);
            //echo $sqlDatosSala."<br>";


            $id_sal_temporal = $datos['id_sal'];

            //echo $id_sal_temporal;
            $sqlDatosChat = "
                              SELECT men_con1 as mensaje, hor_con1 AS hor, use1_con1 AS usuario, tip1_con1 AS tipoUsuario, arc_con AS arc_con
                              FROM con1
                              WHERE id_sal2 = '$id_sal_temporal'
                              UNION
                              SELECT men_con2 as mensaje, hor_con2 AS hor, use2_con2 AS usuario, tip2_con2 AS tipoUsuario, arc_con AS arc_con
                              FROM con2
                              WHERE id_sal3 = '$id_sal_temporal'
                              ORDER BY hor DESC
                              LIMIT 1

                              ";

            $resultadoDatosChat = mysqli_query($db, $sqlDatosChat);
            $filaDatosChat = mysqli_fetch_assoc($resultadoDatosChat);

            echo '<li class="white lighten-3 p-2 elementos" id_usuario="'.$use2_sal.'">
                  <a href="#" id="'.$datos['id_sal'].'" soy="'."use1_sal".'" class="d-flex  text-left botonesRespuestaPadre" style="position: relative;">'.obtenerTotalNotificacionesMensajesSalaServer( $id_sal_temporal, $tipoUsuario, $id ).'
                    <img src="'.obtenerValidacionFotoUsuario( $datosSala["fot_alu"] ).'" alt="avatar" class="avatar rounded-circle d-flex align-self-center mr-1 z-depth-1" width="70px" height="80px">
                    <div class="text-small">
                      '.obtenerTipoUsuarioCompletoAlumnoServer( $datosSala["id_alu"] ).' <br><span class="obtenerDatosUsuario" tipo_usuario="'.$datosSala["tip_alu"].'" id_usuario="'.$datosSala["id_alu"].'">'.$datosSala["nom_alu"].'</span>
                      '.( ( $filaDatosChat['arc_con'] != '' )?'<p title="Descargar: '.$filaDatosChat['arc_con'].'">
                              <i class="fas fa-file-download"></i> Descargar
                          </p>'
                        :
                          '<p class="last-message text-muted">
                          
                            '.recortarTexto( $filaDatosChat["mensaje"] ).'
                          </p>'
                        ).'
                    </div>
                    <div class="chat-footer">
                      <!-- <p class="text-smaller text-muted mb-0">Just now</p> -->
                      <!-- <span class="badge badge-danger float-right">1</span> -->
                    </div>

                    <i class="fas fa-times fa-1x red-text botonesRespuesta eliminacionSala" sala="'.$datos['id_sal'].'" title="Eliminar conversación con '.$datosSala["nom_alu"].'"></i>'.obtenerHoraFormateadaMensajeria( $filaDatosChat["hor"] ).'
                  </a>
                </li>';


          } else if ($tip2_sal == 'Cobranza'){
            $sqlDatosSala = "
              SELECT * 
              FROM cobranza
              INNER JOIN empleado ON empleado.id_emp = cobranza.id_emp8  
              WHERE id_cob = '$use2_sal'";
            $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
            $datosSala = mysqli_fetch_assoc($resultadoDatosSala);
            //echo $sqlDatosSala."<br>";


            $id_sal_temporal = $datos['id_sal'];

            //echo $id_sal_temporal;
            $sqlDatosChat = "
                              SELECT men_con1 as mensaje, hor_con1 AS hor, use1_con1 AS usuario, tip1_con1 AS tipoUsuario, arc_con AS arc_con
                              FROM con1
                              WHERE id_sal2 = '$id_sal_temporal'
                              UNION
                              SELECT men_con2 as mensaje, hor_con2 AS hor, use2_con2 AS usuario, tip2_con2 AS tipoUsuario, arc_con AS arc_con
                              FROM con2
                              WHERE id_sal3 = '$id_sal_temporal'
                              ORDER BY hor DESC
                              LIMIT 1

                              ";

            $resultadoDatosChat = mysqli_query($db, $sqlDatosChat);
            $filaDatosChat = mysqli_fetch_assoc($resultadoDatosChat);



            echo '<li class="white lighten-3 p-2 elementos" id_usuario="'.$use2_sal.'">
                  <a href="#" id="'.$datos['id_sal'].'" soy="'."use1_sal".'" class="d-flex  text-left botonesRespuestaPadre" style="position: relative;">'.obtenerTotalNotificacionesMensajesSalaServer( $id_sal_temporal, $tipoUsuario, $id ).'
                    <img src="'.obtenerValidacionFotoUsuario( $datosSala["fot_emp"] ).'" alt="avatar" class="avatar rounded-circle d-flex align-self-center mr-1 z-depth-1" width="70px" height="80px">
                    <div class="text-small">
                      '.obtenerTipoUsuarioCompleto( $datosSala["tip_cob"] ).' <br><span class="obtenerDatosUsuario" tipo_usuario="'.$datosSala["tip_cob"].'" id_usuario="'.$datosSala["id_cob"].'">'.$datosSala["nom_cob"].'</span>
                      '.( ( $filaDatosChat['arc_con'] != '' )?'<p title="Descargar: '.$filaDatosChat['arc_con'].'">
                              <i class="fas fa-file-download"></i> Descargar
                          </p>'
                        :
                          '<p class="last-message text-muted">
                          
                            '.recortarTexto( $filaDatosChat["mensaje"] ).'
                          </p>'
                        ).'
                    </div>
                    <div class="chat-footer">
                      <!-- <p class="text-smaller text-muted mb-0">Just now</p> -->
                      <!-- <span class="badge badge-danger float-right">1</span> -->
                    </div>

                    <i class="fas fa-times fa-1x red-text botonesRespuesta eliminacionSala" sala="'.$datos['id_sal'].'" title="Eliminar conversación con '.$datosSala["nom_cob"].'"></i>'.obtenerHoraFormateadaMensajeria( $filaDatosChat["hor"] ).'
                  </a>
                </li>';


          }

        }else {
          ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

          $use1_sal = $datos['use1_sal'];
          $tip1_sal = $datos['tip1_sal'];
          $id_sal_temporal = $datos['id_sal'];

          //echo $id_sal_temporal;

          if ($tip1_sal == 'Ejecutivo') {
            $sqlDatosSala = "
              SELECT * 
              FROM ejecutivo
              INNER JOIN empleado ON empleado.id_emp = ejecutivo.id_emp4  
              WHERE id_eje = '$use1_sal'";
            $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
            $datosSala = mysqli_fetch_assoc($resultadoDatosSala);

            $id_sal_temporal = $datos['id_sal'];

            //echo $id_sal_temporal;
            $sqlDatosChat = "
                              SELECT men_con1 as mensaje, hor_con1 AS hor, use1_con1 AS usuario, tip1_con1 AS tipoUsuario, arc_con AS arc_con
                              FROM con1
                              WHERE id_sal2 = '$id_sal_temporal'
                              UNION
                              SELECT men_con2 as mensaje, hor_con2 AS hor, use2_con2 AS usuario, tip2_con2 AS tipoUsuario, arc_con AS arc_con
                              FROM con2
                              WHERE id_sal3 = '$id_sal_temporal'
                              ORDER BY hor DESC
                              LIMIT 1

                              ";

            
            $resultadoDatosChat = mysqli_query($db, $sqlDatosChat);
            $filaDatosChat = mysqli_fetch_assoc($resultadoDatosChat);

            echo '<li class="white lighten-3 p-2 elementos" id_usuario="'.$use1_sal.'">
                  <a href="#" id="'.$datos['id_sal'].'" class="d-flex  text-left botonesRespuestaPadre" soy="'."use2_sal".'" style="position: relative;">'.obtenerTotalNotificacionesMensajesSalaServer( $id_sal_temporal, $tipoUsuario, $id ).'
                    <img src="'.obtenerValidacionFotoUsuario( $datosSala["fot_emp"] ).'"  alt="avatar" class="avatar rounded-circle d-flex align-self-center mr-1 z-depth-1" width="70px" height="80px">
                    <div class="text-small">
                      '.obtenerTipoUsuarioCompleto( $datosSala["tip_eje"] ).' <br><span class="obtenerDatosUsuario" tipo_usuario="'.$datosSala["tip_eje"].'" id_usuario="'.$datosSala["id_eje"].'">'.$datosSala["nom_eje"].'</span>
                      '.( ( $filaDatosChat['arc_con'] != '' )?'<p title="Descargar: '.$filaDatosChat['arc_con'].'">
                              <i class="fas fa-file-download"></i> Descargar
                          </p>'
                        :
                          '<p class="last-message text-muted">
                          
                            '.recortarTexto( $filaDatosChat["mensaje"] ).'
                          </p>'
                        ).'
                    </div>
                    <div class="chat-footer">
                      <!-- <p class="text-smaller text-muted mb-0">Just now</p> -->
                      <!-- <span class="badge badge-danger float-right">1</span> -->
                    </div>

                    <i class="fas fa-times fa-1x red-text botonesRespuesta eliminacionSala" sala="'.$datos['id_sal'].'" title="Eliminar conversación con '.$datosSala["nom_eje"].'"></i>'.obtenerHoraFormateadaMensajeria( $filaDatosChat["hor"] ).'
                  </a>
                </li>';
          }else if ($tip1_sal == 'Admin'){
            $sqlDatosSala = "
              SELECT * 
              FROM admin
              INNER JOIN empleado ON empleado.id_emp = admin.id_emp7  
              WHERE id_adm = '$use1_sal'";
            $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
            $datosSala = mysqli_fetch_assoc($resultadoDatosSala);

            $id_sal_temporal = $datos['id_sal'];

            //echo $id_sal_temporal;
            $sqlDatosChat = "
                              SELECT men_con1 as mensaje, hor_con1 AS hor, use1_con1 AS usuario, tip1_con1 AS tipoUsuario, arc_con AS arc_con
                              FROM con1
                              WHERE id_sal2 = '$id_sal_temporal'
                              UNION
                              SELECT men_con2 as mensaje, hor_con2 AS hor, use2_con2 AS usuario, tip2_con2 AS tipoUsuario, arc_con AS arc_con
                              FROM con2
                              WHERE id_sal3 = '$id_sal_temporal'
                              ORDER BY hor DESC
                              LIMIT 1

                              ";

            $resultadoDatosChat = mysqli_query($db, $sqlDatosChat);
            $filaDatosChat = mysqli_fetch_assoc($resultadoDatosChat);

            echo '<li class="white lighten-3 p-2 elementos" id_usuario="'.$use1_sal.'">
                  <a href="#" id="'.$datos['id_sal'].'" class="d-flex  text-left botonesRespuestaPadre" soy="'."use2_sal".'" style="position: relative;">'.obtenerTotalNotificacionesMensajesSalaServer( $id_sal_temporal, $tipoUsuario, $id ).'
                    <img src="'.obtenerValidacionFotoUsuario( $datosSala["fot_emp"] ).'"  alt="avatar" class="avatar rounded-circle d-flex align-self-center mr-1 z-depth-1" width="70px" height="80px">
                    <div class="text-small">
                      '.obtenerTipoUsuarioCompleto( $datosSala["tip_adm"] ).' <br><span class="obtenerDatosUsuario" tipo_usuario="'.$datosSala["tip_adm"].'" id_usuario="'.$datosSala["id_adm"].'">'.$datosSala["nom_adm"].'</span>
                      '.( ( $filaDatosChat['arc_con'] != '' )?'<p title="Descargar: '.$filaDatosChat['arc_con'].'">
                              <i class="fas fa-file-download"></i> Descargar
                          </p>'
                        :
                          '<p class="last-message text-muted">
                          
                            '.recortarTexto( $filaDatosChat["mensaje"] ).'
                          </p>'
                        ).'
                    </div>
                    <div class="chat-footer">
                      <!-- <p class="text-smaller text-muted mb-0">Just now</p> -->
                      <!-- <span class="badge badge-danger float-right">1</span> -->
                    </div>

                    <i class="fas fa-times fa-1x red-text botonesRespuesta eliminacionSala" sala="'.$datos['id_sal'].'" title="Eliminar conversación con '.$datosSala["nom_adm"].'"></i>'.obtenerHoraFormateadaMensajeria( $filaDatosChat["hor"] ).'
                  </a>
                </li>';

          }else if ($tip1_sal == 'Adminge'){
            $sqlDatosSala = "
              SELECT * 
              FROM adminge
              INNER JOIN empleado ON empleado.id_emp = adminge.id_emp6
              WHERE id_adg = '$use1_sal'";
            $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
            $datosSala = mysqli_fetch_assoc($resultadoDatosSala);

            $id_sal_temporal = $datos['id_sal'];

            //echo $id_sal_temporal;
            $sqlDatosChat = "
                              SELECT men_con1 as mensaje, hor_con1 AS hor, use1_con1 AS usuario, tip1_con1 AS tipoUsuario, arc_con AS arc_con
                              FROM con1
                              WHERE id_sal2 = '$id_sal_temporal'
                              UNION
                              SELECT men_con2 as mensaje, hor_con2 AS hor, use2_con2 AS usuario, tip2_con2 AS tipoUsuario, arc_con AS arc_con
                              FROM con2
                              WHERE id_sal3 = '$id_sal_temporal'
                              ORDER BY hor DESC
                              LIMIT 1

                              ";

            $resultadoDatosChat = mysqli_query($db, $sqlDatosChat);
            $filaDatosChat = mysqli_fetch_assoc($resultadoDatosChat);

            echo '<li class="white lighten-3 p-2 elementos" id_usuario="'.$use1_sal.'">
                  <a href="#" id="'.$datos['id_sal'].'" class="d-flex  text-left botonesRespuestaPadre" soy="'."use2_sal".'" style="position: relative;">'.obtenerTotalNotificacionesMensajesSalaServer( $id_sal_temporal, $tipoUsuario, $id ).'
                    <img src="'.obtenerValidacionFotoUsuario( $datosSala["fot_emp"] ).'"  alt="avatar" class="avatar rounded-circle d-flex align-self-center mr-1 z-depth-1" width="70px" height="80px">
                    <div class="text-small">
                      '.obtenerTipoUsuarioCompleto( $datosSala["tip_adg"] ).' <br><span class="obtenerDatosUsuario" tipo_usuario="'.$datosSala["tip_adg"].'" id_usuario="'.$datosSala["id_adg"].'">'.$datosSala["nom_adg"].'</span>
                      '.( ( $filaDatosChat['arc_con'] != '' )?'<p title="Descargar: '.$filaDatosChat['arc_con'].'">
                              <i class="fas fa-file-download"></i> Descargar
                          </p>'
                        :
                          '<p class="last-message text-muted">
                          
                            '.recortarTexto( $filaDatosChat["mensaje"] ).'
                          </p>'
                        ).'
                    </div>
                    <div class="chat-footer">
                      <!-- <p class="text-smaller text-muted mb-0">Just now</p> -->
                      <!-- <span class="badge badge-danger float-right">1</span> -->
                    </div>

                    <i class="fas fa-times fa-1x red-text botonesRespuesta eliminacionSala" sala="'.$datos['id_sal'].'" title="Eliminar conversación con '.$datosSala["nom_adg"].'"></i>'.obtenerHoraFormateadaMensajeria( $filaDatosChat["hor"] ).'
                  </a>
                </li>';

          }else if ($tip1_sal == 'Adminco'){
            $sqlDatosSala = "
              SELECT * 
              FROM adminco
              INNER JOIN empleado ON empleado.id_emp = adminco.id_emp5  
              WHERE id_adc = '$use1_sal'";
            $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
            $datosSala = mysqli_fetch_assoc($resultadoDatosSala);

            $id_sal_temporal = $datos['id_sal'];

            //echo $id_sal_temporal;
            $sqlDatosChat = "
                              SELECT men_con1 as mensaje, hor_con1 AS hor, use1_con1 AS usuario, tip1_con1 AS tipoUsuario, arc_con AS arc_con
                              FROM con1
                              WHERE id_sal2 = '$id_sal_temporal'
                              UNION
                              SELECT men_con2 as mensaje, hor_con2 AS hor, use2_con2 AS usuario, tip2_con2 AS tipoUsuario, arc_con AS arc_con
                              FROM con2
                              WHERE id_sal3 = '$id_sal_temporal'
                              ORDER BY hor DESC
                              LIMIT 1

                              ";

            $resultadoDatosChat = mysqli_query($db, $sqlDatosChat);
            $filaDatosChat = mysqli_fetch_assoc($resultadoDatosChat);

            echo '<li class="white lighten-3 p-2 elementos" id_usuario="'.$use1_sal.'">
                  <a href="#" id="'.$datos['id_sal'].'" class="d-flex  text-left botonesRespuestaPadre" soy="'."use2_sal".'" style="position: relative;">'.obtenerTotalNotificacionesMensajesSalaServer( $id_sal_temporal, $tipoUsuario, $id ).'
                    <img src="'.obtenerValidacionFotoUsuario( $datosSala["fot_emp"] ).'"  alt="avatar" class="avatar rounded-circle d-flex align-self-center mr-1 z-depth-1" width="70px" height="80px">
                    <div class="text-small">
                     '.obtenerTipoUsuarioCompleto( $datosSala["tip_adc"] ).' <br><span class="obtenerDatosUsuario" tipo_usuario="'.$datosSala["tip_adc"].'" id_usuario="'.$datosSala["id_adc"].'">'.$datosSala["nom_adc"].'</span>
                      '.( ( $filaDatosChat['arc_con'] != '' )?'<p title="Descargar: '.$filaDatosChat['arc_con'].'">
                              <i class="fas fa-file-download"></i> Descargar
                          </p>'
                        :
                          '<p class="last-message text-muted">
                          
                            '.recortarTexto( $filaDatosChat["mensaje"] ).'
                          </p>'
                        ).'
                    </div>
                    <div class="chat-footer">
                      <!-- <p class="text-smaller text-muted mb-0">Just now</p> -->
                      <!-- <span class="badge badge-danger float-right">1</span> -->
                    </div>

                    <i class="fas fa-times fa-1x red-text botonesRespuesta eliminacionSala" sala="'.$datos['id_sal'].'" title="Eliminar conversación con '.$datosSala["nom_adc"].'"></i>'.obtenerHoraFormateadaMensajeria( $filaDatosChat["hor"] ).'
                  </a>
                </li>';

          }else if ($tip1_sal == 'Profesor'){
            $sqlDatosSala = "
              SELECT * 
              FROM profesor
              INNER JOIN empleado ON empleado.id_emp = profesor.id_emp3  
              WHERE id_pro = '$use1_sal'";
            $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
            $datosSala = mysqli_fetch_assoc($resultadoDatosSala);

            $id_sal_temporal = $datos['id_sal'];

            //echo $id_sal_temporal;
            $sqlDatosChat = "
                              SELECT men_con1 as mensaje, hor_con1 AS hor, use1_con1 AS usuario, tip1_con1 AS tipoUsuario, arc_con AS arc_con
                              FROM con1
                              WHERE id_sal2 = '$id_sal_temporal'
                              UNION
                              SELECT men_con2 as mensaje, hor_con2 AS hor, use2_con2 AS usuario, tip2_con2 AS tipoUsuario, arc_con AS arc_con
                              FROM con2
                              WHERE id_sal3 = '$id_sal_temporal'
                              ORDER BY hor DESC
                              LIMIT 1

                              ";

            $resultadoDatosChat = mysqli_query($db, $sqlDatosChat);
            $filaDatosChat = mysqli_fetch_assoc($resultadoDatosChat);

            echo '<li class="white lighten-3 p-2 elementos" id_usuario="'.$use1_sal.'">
                  <a href="#" id="'.$datos['id_sal'].'" class="d-flex  text-left botonesRespuestaPadre" soy="'."use2_sal".'" style="position: relative;">'.obtenerTotalNotificacionesMensajesSalaServer( $id_sal_temporal, $tipoUsuario, $id ).'
                    <img src="'.obtenerValidacionFotoUsuario( $datosSala["fot_emp"] ).'"  alt="avatar" class="avatar rounded-circle d-flex align-self-center mr-1 z-depth-1" width="70px" height="80px">
                    <div class="text-small">
                      '.obtenerTipoUsuarioCompleto( $datosSala["tip_pro"] ).' <br><span class="obtenerDatosUsuario" tipo_usuario="'.$datosSala["tip_pro"].'" id_usuario="'.$datosSala["id_pro"].'">'.$datosSala["nom_pro"].'</span>
                      '.( ( $filaDatosChat['arc_con'] != '' )?'<p title="Descargar: '.$filaDatosChat['arc_con'].'">
                              <i class="fas fa-file-download"></i> Descargar
                          </p>'
                        :
                          '<p class="last-message text-muted">
                          
                            '.recortarTexto( $filaDatosChat["mensaje"] ).'
                          </p>'
                        ).'
                    </div>
                    <div class="chat-footer">
                      <!-- <p class="text-smaller text-muted mb-0">Just now</p> -->
                      <!-- <span class="badge badge-danger float-right">1</span> -->
                    </div>

                    <i class="fas fa-times fa-1x red-text botonesRespuesta eliminacionSala" sala="'.$datos['id_sal'].'" title="Eliminar conversación con '.$datosSala["nom_pro"].'"></i>'.obtenerHoraFormateadaMensajeria( $filaDatosChat["hor"] ).'
                  </a>
                </li>';

          }else if ($tip1_sal == 'Alumno'){
            $sqlDatosSala = "SELECT * FROM alumno WHERE id_alu = '$use1_sal'";
            $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
            $datosSala = mysqli_fetch_assoc($resultadoDatosSala);

            $id_sal_temporal = $datos['id_sal'];

            //echo $id_sal_temporal;
            $sqlDatosChat = "
                              SELECT men_con1 as mensaje, hor_con1 AS hor, use1_con1 AS usuario, tip1_con1 AS tipoUsuario, arc_con AS arc_con
                              FROM con1
                              WHERE id_sal2 = '$id_sal_temporal'
                              UNION
                              SELECT men_con2 as mensaje, hor_con2 AS hor, use2_con2 AS usuario, tip2_con2 AS tipoUsuario, arc_con AS arc_con
                              FROM con2
                              WHERE id_sal3 = '$id_sal_temporal'
                              ORDER BY hor DESC
                              LIMIT 1

                              ";

            $resultadoDatosChat = mysqli_query($db, $sqlDatosChat);
            $filaDatosChat = mysqli_fetch_assoc($resultadoDatosChat);

            echo '<li class="white lighten-3 p-2 elementos" id_usuario="'.$use1_sal.'">
                  <a href="#" id="'.$datos['id_sal'].'" class="d-flex  text-left botonesRespuestaPadre" soy="'."use2_sal".'" style="position: relative;">'.obtenerTotalNotificacionesMensajesSalaServer( $id_sal_temporal, $tipoUsuario, $id ).'
                    <img src="'.obtenerValidacionFotoUsuario( $datosSala["fot_alu"] ).'"  alt="avatar" class="avatar rounded-circle d-flex align-self-center mr-1 z-depth-1" width="70px" height="80px">
                    <div class="text-small">
                      '.obtenerTipoUsuarioCompletoAlumnoServer( $datosSala["id_alu"] ).' <br><span class="obtenerDatosUsuario" tipo_usuario="'.$datosSala["tip_alu"].'" id_usuario="'.$datosSala["id_alu"].'">'.$datosSala["nom_alu"].'</span>
                      '.( ( $filaDatosChat['arc_con'] != '' )?'<p title="Descargar: '.$filaDatosChat['arc_con'].'">
                              <i class="fas fa-file-download"></i> Descargar
                          </p>'
                        :
                          '<p class="last-message text-muted">
                          
                            '.recortarTexto( $filaDatosChat["mensaje"] ).'
                          </p>'
                        ).'
                    </div>
                    <div class="chat-footer">
                      <!-- <p class="text-smaller text-muted mb-0">Just now</p> -->
                      <!-- <span class="badge badge-danger float-right">1</span> -->
                    </div>
                    <i class="fas fa-times fa-1x red-text botonesRespuesta eliminacionSala" sala="'.$datos['id_sal'].'" title="Eliminar conversación con '.$datosSala["nom_alu"].'"></i>'.obtenerHoraFormateadaMensajeria( $filaDatosChat["hor"] ).'
                  </a>
                </li>';

          } else if ($tip1_sal == 'Cobranza'){
            $sqlDatosSala = "
              SELECT * 
              FROM cobranza
              INNER JOIN empleado ON empleado.id_emp = cobranza.id_emp8
              WHERE id_cob = '$use1_sal'";
            $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
            $datosSala = mysqli_fetch_assoc($resultadoDatosSala);

            $id_sal_temporal = $datos['id_sal'];

            //echo $id_sal_temporal;
            $sqlDatosChat = "
                              SELECT men_con1 as mensaje, hor_con1 AS hor, use1_con1 AS usuario, tip1_con1 AS tipoUsuario, arc_con AS arc_con
                              FROM con1
                              WHERE id_sal2 = '$id_sal_temporal'
                              UNION
                              SELECT men_con2 as mensaje, hor_con2 AS hor, use2_con2 AS usuario, tip2_con2 AS tipoUsuario, arc_con AS arc_con
                              FROM con2
                              WHERE id_sal3 = '$id_sal_temporal'
                              ORDER BY hor DESC
                              LIMIT 1

                              ";

            $resultadoDatosChat = mysqli_query($db, $sqlDatosChat);
            $filaDatosChat = mysqli_fetch_assoc($resultadoDatosChat);

            echo '<li class="white lighten-3 p-2 elementos" id_usuario="'.$use1_sal.'">
                  <a href="#" id="'.$datos['id_sal'].'" class="d-flex  text-left botonesRespuestaPadre" soy="'."use2_sal".'" style="position: relative;">'.obtenerTotalNotificacionesMensajesSalaServer( $id_sal_temporal, $tipoUsuario, $id ).'
                    <img src="'.obtenerValidacionFotoUsuario( $datosSala["fot_emp"] ).'"  alt="avatar" class="avatar rounded-circle d-flex align-self-center mr-1 z-depth-1" width="70px" height="80px">
                    <div class="text-small">
                      '.obtenerTipoUsuarioCompleto( $datosSala["tip_cob"] ).' <br><span class="obtenerDatosUsuario" tipo_usuario="'.$datosSala["tip_cob"].'" id_usuario="'.$datosSala["id_cob"].'">'.$datosSala["nom_cob"].'</span>
                      '.( ( $filaDatosChat['arc_con'] != '' )?'<p title="Descargar: '.$filaDatosChat['arc_con'].'">
                              <i class="fas fa-file-download"></i> Descargar
                          </p>'
                        :
                          '<p class="last-message text-muted">
                          
                            '.recortarTexto( $filaDatosChat["mensaje"] ).'
                          </p>'
                        ).'
                    </div>
                    <div class="chat-footer">
                      <!-- <p class="text-smaller text-muted mb-0">Just now</p> -->
                      <!-- <span class="badge badge-danger float-right">1</span> -->
                    </div>

                    <i class="fas fa-times fa-1x red-text botonesRespuesta eliminacionSala" sala="'.$datos['id_sal'].'" title="Eliminar conversación con '.$datosSala["nom_cob"].'"></i>'.obtenerHoraFormateadaMensajeria( $filaDatosChat["hor"] ).'
                  </a>
                </li>';

          }

        }
    ?>

    

  <?php    
    }
  ?>

      <hr>

   
  </ul>



<script>
  listarMensajes();
   
    
    function listarMensajes(){
      $('.elementos').on('click', function(event) {
        event.preventDefault();

        burbuja.play();

        // $('#listadoMensajes').html('<div id="overlay" style="height:100%; width:100%; background:rgba(f, f, f); position:fixed; left:0; top:0;"><div class="spinner"></div></div>');


        var variable = $(this).children().attr("soy");
        var id_sal = $(this).children().attr("id");


        var id_usuario = $(this).attr('id_usuario');

        console.log( id_usuario );

        $('#msj').attr({"id_usuario": id_usuario});

        if ( $(this).children().children().hasClass('notificacionPendiente') ) {

          $.ajax({
            url: 'server/eliminacion_notificacion_mensaje.php',
            type: 'POST',
            data: { id_sal },
            success: function( respuesta ){

              // console.log( respuesta );

              if ( respuesta == 'true' ) {

                obtener_panel_notificaciones_mensajeria();

                variable = $('.friend-list').find('.cyan').children().attr('soy');
                validador = $('.friend-list').find('.cyan').children().attr('id');
                
                
                //  console.log( 'validador: '+validador+' - datos.sala: '+datos.sala );
                // if (validador == datos.sala) {

                // console.log('validador');

                cargarMensajes(variable, validador );
                obtener_contactos( validador );
                

              }

            }
          });   

        }





        //console.log(id_sal);

        $.ajaxSetup({"cache": false});

         //var temp = setInterval(function(){cargarMensajes(variable, id_sal)}, 2000);
        //console.log(temp  );
        
        cargarMensajes(variable, id_sal);

      });
    }
</script>



<script>
  //CAMBIOS DE COLOR DE CONTACTOS EN SELECCION

  $(".elementos").on('click', function(event) {
    event.preventDefault();
    // /* Act on the event */
    $('.elementos').removeClass('white lighten-3');
    $('.elementos').removeClass('cyan lighten-5');
    $('.elementos').addClass('white lighten-3');
    $(this).removeClass('white lighten-3');
    $(this).addClass('cyan lighten-5');
  });
</script>



<script>
  //ELIMINACION DE SALA
  $('.eliminacionSala').on('click', function(event) {
    event.preventDefault();

    error.play();
    /* Act on the event */
    var sala = $(this).attr("sala");

    // console.log(SALA);
    swal({
      title: "¿Deseas eliminar esta conversación?",
      text: "¡Una vez confirmes, se perderán todos los mensajes para los dos usuarios!",
      icon: "warning",
      buttons:  {
            cancel: {
              text: "Cancelar",
              value: null,
              visible: true,
              className: "",
              closeModal: true,
            },
            confirm: {
              text: "Confirmar",
              value: true,
              visible: true,
              className: "",
              closeModal: true
            }
          },
      dangerMode: true,
    }).then((willDelete) => {
      if (willDelete) {
        //ELIMINACION ACEPTADA

        $.ajax({
        url: 'server/eliminacion_sala.php',
        type: 'POST',
        data: {sala},
        success: function(respuesta){
          
          if (respuesta == "true") {
            console.log("Exito en consulta");
            swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
            then((value) => {
              obtener_contactos();
              $("#listadoMensajes").html('');
            });
          }else{
            console.log(respuesta);

          }

        }
      });
        
      }
    });
  });


</script>