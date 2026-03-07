<?php 
  //ARCHIVO VIA AJAX PARA LISTAR MENSAJES DE CONTACTO SELECCIONADO Y ASIGNARLOS DER/IZQ
  //mensajes.php
  require('../inc/cabeceras.php');
  require('../inc/funciones.php');

  //***CODIGO NO REUTILIZABLE POR LA CONDICION DE LA LINEA 35 DONDE SE DEBE ESPECIFICAR LA EXTRACCION DE DATOS DEL USUARIO CON SESION ACTIVA HAY QUE DEFINIR EN LA LINEA 40-41 QUE TIPO DE USUARIO SOY EN LAS DOS TABLAS SEMEJANTES, Y HASTA ABAJO EL USUARIO QUE FALTA ESO ES TODO

  
  
  $id_sal1 = $_POST['id_sal'];


            $sqlSalas = "
            SELECT men_con1 as mensaje, hor_con1 AS hor, use1_con1 AS usuario, tip1_con1 AS tipoUsuario, arc_con AS arc_con
            FROM con1
            WHERE id_sal2 = '$id_sal1'
            UNION
            SELECT men_con2 as mensaje, hor_con2 AS hor, use2_con2 AS usuario, tip2_con2 AS tipoUsuario, arc_con AS arc_con
            FROM con2
            WHERE id_sal3 = '$id_sal1'
            ORDER BY hor
            ";

            //echo $sqlSalas;

            $resultadoSalas = mysqli_query($db, $sqlSalas);

            while ($fila = mysqli_fetch_assoc($resultadoSalas)) {

              $formatoArchivo = obtenerFormatoArchivo( $fila['arc_con'] );

              //$fila['usuario']." ".$fila['tipoUsuario'];
              $usuario = $fila['usuario'];
               //echo "$tipo"."<br>";

              $hor = $fila['hor'];
              $hor = fechaHoraFormateada($hor);

              if ($fila['tipoUsuario'] == $tipo && $fila['usuario'] == $id) {

                //echo "este soy yo"."<br>";
                $sqlDatosSala = " 
                  SELECT * 
                  FROM admin
                  INNER JOIN empleado ON empleado.id_emp = admin.id_emp7 
                  WHERE id_adm = '$usuario'";
                $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
                $datosSala = mysqli_fetch_assoc($resultadoDatosSala);

                //echo $sqlDatosSala;
            ?>


                  <li class="d-flex justify-content-between mb-4">
                    <div class="chat-body white p-3 z-depth-1">
                      <div class="header"><img width="40px" height="40px" src="../uploads/<?php echo $datosSala['fot_emp']; ?>" alt="avatar" class="avatar rounded-circle mr-0 ml-3 z-depth-1">
                        <strong class="primary-font"><?php echo $datosSala['nom_adm']; ?></strong>
                        <small class="pull-right text-muted"><i class="far fa-clock"></i> <?php echo $hor; ?></small>
                      </div>
                      <hr class="w-100">
                      <?php  
                        if ( $fila['arc_con'] != '' ) {
                      ?>

                          <a href="../archivos/<?php echo $fila['arc_con']; ?>" download class="btn-link" title="Descargar: <?php echo $fila['arc_con']; ?>">
                              <?php  
                                if ( $formatoArchivo == 'docx' ) {
                              ?>
                                  <i class="fas fa-file-word fa-1x blue-text"></i> Descargar

                              <?php
                                } else if ( $formatoArchivo == 'pptx' ) {
                              ?>
                                <i class="fas fa-file-powerpoint fa-1x orange-text"></i> Descargar

                              <?php 
                                } else if ( $formatoArchivo == 'pdf' ) {
                              ?>
                                  <i class="fas fa-file-pdf fa-1x red-text"></i> Descargar

                              <?php 
                                } else if ( $formatoArchivo == 'xlxs' ){
                              ?>

                                  <i class="fas fa-file-excel fa-1x green-text"></i> Descargar

                              <?php
                                } else if ( ( $formatoArchivo == 'jpg' ) || ( $formatoArchivo == 'jpeg' ) || $formatoArchivo == 'png' ) {
                              ?>  <!-- 
                                  <img src="../archivos/<?php echo $fila['arc_con']; ?>" class="img-fluid" style="height: 250px; width: 250px;">
                                  <br> -->
                                  <i class="fas fa-image fa-1x orange-text"></i> Descargar
                                  <br>
                                  <a href="../archivos/<?php echo $fila['arc_con']; ?>" data-lightbox="roadtrip">
                                    <img src="../archivos/<?php echo $fila['arc_con']; ?>" class="img-fluid" style="height: 250px; width: 250px;">
                                  </a>

                                  

                              <?php
                                }
                              ?>
                              
                          </a>
                      <?php
                        } else{
                      ?>
                          <p class="mb-0">
                            <?php echo $fila['mensaje']; ?>
                          </p>

                      <?php
                        }
                      ?>
                      
                    </div>
                    <div class="avatar rounded-circle mr-0 ml-3 z-depth-1">
                    </div>
                  </li>

            <?php
                    

                   
              }else if($fila['tipoUsuario'] == $tipo && $fila['usuario'] != $id){
                //echo "es mi tipo pero no mi id"."<br>";
                $sqlDatosSala = "
                  SELECT * 
                  FROM admin 
                  INNER JOIN empleado ON empleado.id_emp = admin.id_emp7 
                  WHERE id_adm = '$usuario'";
                $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
                $datosSala = mysqli_fetch_assoc($resultadoDatosSala);


            ?>

                  <li class="d-flex justify-content-between mb-4">
                    <div class="avatar rounded-circle mr-2 ml-lg-3 ml-0 z-depth-1">
                    </div>
                    <div class="chat-body white p-3 ml-2 z-depth-1">
                      <div class="header"><img width="40px" height="40px" src="../uploads/<?php echo $datosSala['fot_emp']; ?>" alt="avatar" class="avatar rounded-circle mr-2 ml-lg-3 ml-0 z-depth-1">
                        <strong class="primary-font"><?php echo $datosSala['nom_adm']; ?></strong>
                        <small class="pull-right text-muted"><i class="far fa-clock"></i> <?php echo $hor; ?></small>
                      </div>
                      <hr class="w-100">
                      <?php  
                        if ( $fila['arc_con'] != '' ) {
                      ?>
                          <a href="../archivos/<?php echo $fila['arc_con']; ?>" download class="btn-link" title="Descargar: <?php echo $fila['arc_con']; ?>">
                              <?php  
                                if ( $formatoArchivo == 'docx' ) {
                              ?>
                                  <i class="fas fa-file-word fa-1x blue-text"></i> Descargar

                              <?php
                                } else if ( $formatoArchivo == 'pptx' ) {
                              ?>
                                <i class="fas fa-file-powerpoint fa-1x orange-text"></i> Descargar

                              <?php 
                                } else if ( $formatoArchivo == 'pdf' ) {
                              ?>
                                  <i class="fas fa-file-pdf fa-1x red-text"></i> Descargar

                              <?php 
                                } else if ( $formatoArchivo == 'xlxs' ){
                              ?>

                                  <i class="fas fa-file-excel fa-1x green-text"></i> Descargar

                              <?php
                                } else if ( ( $formatoArchivo == 'jpg' ) || ( $formatoArchivo == 'jpeg' ) || $formatoArchivo == 'png' ) {
                              ?>  <!-- 
                                  <img src="../archivos/<?php echo $fila['arc_con']; ?>" class="img-fluid" style="height: 250px; width: 250px;">
                                  <br> -->
                                  <i class="fas fa-image fa-1x orange-text"></i> Descargar
                                  <br>
                                  <a href="../archivos/<?php echo $fila['arc_con']; ?>" data-lightbox="roadtrip">
                                    <img src="../archivos/<?php echo $fila['arc_con']; ?>" class="img-fluid" style="height: 250px; width: 250px;">
                                  </a>

                                  

                              <?php
                                }
                              ?>
                              
                          </a>
                      <?php
                        } else{
                      ?>
                          <p class="mb-0">
                            <?php echo $fila['mensaje']; ?>
                          </p>

                      <?php
                        }
                      ?>
                    </div>
                  </li>

            <?php

               
                

              }else if($fila['tipoUsuario'] == 'Ejecutivo'){
                //echo "Es de tipo usuario"."<br>";

                $sqlDatosSala = " 
                  SELECT * 
                  FROM ejecutivo
                  INNER JOIN empleado ON empleado.id_emp = ejecutivo.id_emp4  
                  WHERE id_eje = '$usuario'";
                $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
                $datosSala = mysqli_fetch_assoc($resultadoDatosSala);

            ?>

                  <li class="d-flex justify-content-between mb-4">
                    <div class="avatar rounded-circle mr-2 ml-lg-3 ml-0 z-depth-1">
                    </div>
                    <div class="chat-body white p-3 ml-2 z-depth-1">
                      <div class="header"><img width="40px" height="40px" src="../uploads/<?php echo $datosSala['fot_emp']; ?>" alt="avatar" class="avatar rounded-circle mr-2 ml-lg-3 ml-0 z-depth-1">
                        <strong class="primary-font"><?php echo $datosSala['nom_eje']; ?></strong>
                        <small class="pull-right text-muted"><i class="far fa-clock"></i> <?php echo $hor; ?></small>
                      </div>
                      <hr class="w-100">
                      <?php  
                        if ( $fila['arc_con'] != '' ) {
                      ?>
                          <a href="../archivos/<?php echo $fila['arc_con']; ?>" download class="btn-link" title="Descargar: <?php echo $fila['arc_con']; ?>">
                              <?php  
                                if ( $formatoArchivo == 'docx' ) {
                              ?>
                                  <i class="fas fa-file-word fa-1x blue-text"></i> Descargar

                              <?php
                                } else if ( $formatoArchivo == 'pptx' ) {
                              ?>
                                <i class="fas fa-file-powerpoint fa-1x orange-text"></i> Descargar

                              <?php 
                                } else if ( $formatoArchivo == 'pdf' ) {
                              ?>
                                  <i class="fas fa-file-pdf fa-1x red-text"></i> Descargar

                              <?php 
                                } else if ( $formatoArchivo == 'xlxs' ){
                              ?>

                                  <i class="fas fa-file-excel fa-1x green-text"></i> Descargar

                              <?php
                                } else if ( ( $formatoArchivo == 'jpg' ) || ( $formatoArchivo == 'jpeg' ) || $formatoArchivo == 'png' ) {
                              ?>  <!-- 
                                  <img src="../archivos/<?php echo $fila['arc_con']; ?>" class="img-fluid" style="height: 250px; width: 250px;">
                                  <br> -->
                                  <i class="fas fa-image fa-1x orange-text"></i> Descargar
                                  <br>
                                  <a href="../archivos/<?php echo $fila['arc_con']; ?>" data-lightbox="roadtrip">
                                    <img src="../archivos/<?php echo $fila['arc_con']; ?>" class="img-fluid" style="height: 250px; width: 250px;">
                                  </a>

                                  

                              <?php
                                }
                              ?>
                              
                          </a>
                      <?php
                        } else{
                      ?>
                          <p class="mb-0">
                            <?php echo $fila['mensaje']; ?>
                          </p>

                      <?php
                        }
                      ?>
                    </div>
                  </li>

            <?php

              }else if($fila['tipoUsuario'] == 'Adminge'){
                //echo "Es de tipo usuario"."<br>";

                $sqlDatosSala = "
                  SELECT * 
                  FROM adminge 
                  INNER JOIN empleado ON empleado.id_emp = adminge.id_emp6 
                  WHERE id_adg = '$usuario'";
                $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
                $datosSala = mysqli_fetch_assoc($resultadoDatosSala);

            ?>

                  <li class="d-flex justify-content-between mb-4">
                    <div class="avatar rounded-circle mr-2 ml-lg-3 ml-0 z-depth-1">
                    </div>
                    <div class="chat-body white p-3 ml-2 z-depth-1">
                      <div class="header"><img width="40px" height="40px" src="../uploads/<?php echo $datosSala['fot_emp']; ?>" alt="avatar" class="avatar rounded-circle mr-2 ml-lg-3 ml-0 z-depth-1">
                        <strong class="primary-font"><?php echo $datosSala['nom_adg']; ?></strong>
                        <small class="pull-right text-muted"><i class="far fa-clock"></i> <?php echo $hor; ?></small>
                      </div>
                      <hr class="w-100">
                      <?php
                        if ( $fila['arc_con'] != '' ) {
                      ?>
                          <a href="../archivos/<?php echo $fila['arc_con']; ?>" download class="btn-link" title="Descargar: <?php echo $fila['arc_con']; ?>">
                              <?php  
                                if ( $formatoArchivo == 'docx' ) {
                              ?>
                                  <i class="fas fa-file-word fa-1x blue-text"></i> Descargar

                              <?php
                                } else if ( $formatoArchivo == 'pptx' ) {
                              ?>
                                <i class="fas fa-file-powerpoint fa-1x orange-text"></i> Descargar

                              <?php 
                                } else if ( $formatoArchivo == 'pdf' ) {
                              ?>
                                  <i class="fas fa-file-pdf fa-1x red-text"></i> Descargar

                              <?php 
                                } else if ( $formatoArchivo == 'xlxs' ){
                              ?>

                                  <i class="fas fa-file-excel fa-1x green-text"></i> Descargar

                              <?php
                                } else if ( ( $formatoArchivo == 'jpg' ) || ( $formatoArchivo == 'jpeg' ) || $formatoArchivo == 'png' ) {
                              ?>  <!-- 
                                  <img src="../archivos/<?php echo $fila['arc_con']; ?>" class="img-fluid" style="height: 250px; width: 250px;">
                                  <br> -->
                                  <i class="fas fa-image fa-1x orange-text"></i> Descargar
                                  <br>
                                  <a href="../archivos/<?php echo $fila['arc_con']; ?>" data-lightbox="roadtrip">
                                    <img src="../archivos/<?php echo $fila['arc_con']; ?>" class="img-fluid" style="height: 250px; width: 250px;">
                                  </a>

                                  

                              <?php
                                }
                              ?>
                              
                          </a>
                      <?php
                        } else{
                      ?>
                          <p class="mb-0">
                            <?php echo $fila['mensaje']; ?>
                          </p>

                      <?php
                        }
                      ?>
                    </div>
                  </li>

            <?php

              }else if($fila['tipoUsuario'] == 'Adminco'){
                //echo "Es de tipo usuario"."<br>";

                $sqlDatosSala = "
                  SELECT * 
                  FROM adminco 
                  INNER JOIN empleado ON empleado.id_emp = adminco.id_emp5 
                  WHERE id_adc = '$usuario'";
                $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
                $datosSala = mysqli_fetch_assoc($resultadoDatosSala);

            ?>


                  <li class="d-flex justify-content-between mb-4">
                    <div class="avatar rounded-circle mr-2 ml-lg-3 ml-0 z-depth-1">
                    </div>
                    <div class="chat-body white p-3 ml-2 z-depth-1">
                      <div class="header"><img width="40px" height="40px" src="../uploads/<?php echo $datosSala['fot_emp']; ?>" alt="avatar" class="avatar rounded-circle mr-2 ml-lg-3 ml-0 z-depth-1">
                        <strong class="primary-font"><?php echo $datosSala['nom_adc']; ?></strong>
                        <small class="pull-right text-muted"><i class="far fa-clock"></i> <?php echo $hor; ?></small>
                      </div>
                      <hr class="w-100">
                      <?php  
                        if ( $fila['arc_con'] != '' ) {
                      ?>
                          <a href="../archivos/<?php echo $fila['arc_con']; ?>" download class="btn-link" title="Descargar: <?php echo $fila['arc_con']; ?>">
                              <?php  
                                if ( $formatoArchivo == 'docx' ) {
                              ?>
                                  <i class="fas fa-file-word fa-1x blue-text"></i> Descargar

                              <?php
                                } else if ( $formatoArchivo == 'pptx' ) {
                              ?>
                                <i class="fas fa-file-powerpoint fa-1x orange-text"></i> Descargar

                              <?php 
                                } else if ( $formatoArchivo == 'pdf' ) {
                              ?>
                                  <i class="fas fa-file-pdf fa-1x red-text"></i> Descargar

                              <?php 
                                } else if ( $formatoArchivo == 'xlxs' ){
                              ?>

                                  <i class="fas fa-file-excel fa-1x green-text"></i> Descargar

                              <?php
                                } else if ( ( $formatoArchivo == 'jpg' ) || ( $formatoArchivo == 'jpeg' ) || $formatoArchivo == 'png' ) {
                              ?>  <!-- 
                                  <img src="../archivos/<?php echo $fila['arc_con']; ?>" class="img-fluid" style="height: 250px; width: 250px;">
                                  <br> -->
                                  <i class="fas fa-image fa-1x orange-text"></i> Descargar
                                  <br>
                                  <a href="../archivos/<?php echo $fila['arc_con']; ?>" data-lightbox="roadtrip">
                                    <img src="../archivos/<?php echo $fila['arc_con']; ?>" class="img-fluid" style="height: 250px; width: 250px;">
                                  </a>

                                  

                              <?php
                                }
                              ?>
                              
                          </a>
                      <?php
                        } else{
                      ?>
                          <p class="mb-0">
                            <?php echo $fila['mensaje']; ?>
                          </p>

                      <?php
                        }
                      ?>

                    </div>
                  </li>

            <?php

              }else if($fila['tipoUsuario'] == 'Cobranza'){
                //echo "Es de tipo usuario"."<br>";

                $sqlDatosSala = "
                  SELECT * 
                  FROM cobranza 
                  INNER JOIN empleado ON empleado.id_emp = cobranza.id_emp8
                  WHERE id_cob = '$usuario'";
                $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
                $datosSala = mysqli_fetch_assoc($resultadoDatosSala);

            ?>

                  <li class="d-flex justify-content-between mb-4">
                    <div class="avatar rounded-circle mr-2 ml-lg-3 ml-0 z-depth-1">
                    </div>
                    <div class="chat-body white p-3 ml-2 z-depth-1">
                      <div class="header"><img width="40px" height="40px" src="../uploads/<?php echo $datosSala['fot_emp']; ?>" alt="avatar" class="avatar rounded-circle mr-2 ml-lg-3 ml-0 z-depth-1">
                        <strong class="primary-font"><?php echo $datosSala['nom_cob']; ?></strong>
                        <small class="pull-right text-muted"><i class="far fa-clock"></i> <?php echo $hor; ?></small>
                      </div>
                      <hr class="w-100">
                      <?php  
                        if ( $fila['arc_con'] != '' ) {
                      ?>
                          <a href="../archivos/<?php echo $fila['arc_con']; ?>" download class="btn-link" title="Descargar: <?php echo $fila['arc_con']; ?>">
                              <?php  
                                if ( $formatoArchivo == 'docx' ) {
                              ?>
                                  <i class="fas fa-file-word fa-1x blue-text"></i> Descargar

                              <?php
                                } else if ( $formatoArchivo == 'pptx' ) {
                              ?>
                                <i class="fas fa-file-powerpoint fa-1x orange-text"></i> Descargar

                              <?php 
                                } else if ( $formatoArchivo == 'pdf' ) {
                              ?>
                                  <i class="fas fa-file-pdf fa-1x red-text"></i> Descargar

                              <?php 
                                } else if ( $formatoArchivo == 'xlxs' ){
                              ?>

                                  <i class="fas fa-file-excel fa-1x green-text"></i> Descargar

                              <?php
                                } else if ( ( $formatoArchivo == 'jpg' ) || ( $formatoArchivo == 'jpeg' ) || $formatoArchivo == 'png' ) {
                              ?>  <!-- 
                                  <img src="../archivos/<?php echo $fila['arc_con']; ?>" class="img-fluid" style="height: 250px; width: 250px;">
                                  <br> -->
                                  <i class="fas fa-image fa-1x orange-text"></i> Descargar
                                  <br>
                                  <a href="../archivos/<?php echo $fila['arc_con']; ?>" data-lightbox="roadtrip">
                                    <img src="../archivos/<?php echo $fila['arc_con']; ?>" class="img-fluid" style="height: 250px; width: 250px;">
                                  </a>

                                  

                              <?php
                                }
                              ?>
                              
                          </a>
                      <?php
                        } else{
                      ?>
                          <p class="mb-0">
                            <?php echo $fila['mensaje']; ?>
                          </p>

                      <?php
                        }
                      ?>
                    </div>
                  </li>

            <?php

              }else if($fila['tipoUsuario'] == 'Profesor'){
                //echo "Es de tipo usuario"."<br>";

                $sqlDatosSala = "
                  SELECT * 
                  FROM profesor 
                  INNER JOIN empleado ON empleado.id_emp = profesor.id_emp3 
                  WHERE id_pro = '$usuario'";
                $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
                $datosSala = mysqli_fetch_assoc($resultadoDatosSala);

            ?>

                  <li class="d-flex justify-content-between mb-4">
                    <div class="avatar rounded-circle mr-2 ml-lg-3 ml-0 z-depth-1">
                    </div>
                    <div class="chat-body white p-3 ml-2 z-depth-1">
                      <div class="header"><img width="40px" height="40px" src="../uploads/<?php echo $datosSala['fot_emp']; ?>" alt="avatar" class="avatar rounded-circle mr-2 ml-lg-3 ml-0 z-depth-1">
                        <strong class="primary-font"><?php echo $datosSala['nom_pro']; ?></strong>
                        <small class="pull-right text-muted"><i class="far fa-clock"></i> <?php echo $hor; ?></small>
                      </div>
                      <hr class="w-100">
                      <?php  
                        if ( $fila['arc_con'] != '' ) {
                      ?>
                          <a href="../archivos/<?php echo $fila['arc_con']; ?>" download class="btn-link" title="Descargar: <?php echo $fila['arc_con']; ?>">
                              <?php  
                                if ( $formatoArchivo == 'docx' ) {
                              ?>
                                  <i class="fas fa-file-word fa-1x blue-text"></i> Descargar

                              <?php
                                } else if ( $formatoArchivo == 'pptx' ) {
                              ?>
                                <i class="fas fa-file-powerpoint fa-1x orange-text"></i> Descargar

                              <?php 
                                } else if ( $formatoArchivo == 'pdf' ) {
                              ?>
                                  <i class="fas fa-file-pdf fa-1x red-text"></i> Descargar

                              <?php 
                                } else if ( $formatoArchivo == 'xlxs' ){
                              ?>

                                  <i class="fas fa-file-excel fa-1x green-text"></i> Descargar

                              <?php
                                } else if ( ( $formatoArchivo == 'jpg' ) || ( $formatoArchivo == 'jpeg' ) || $formatoArchivo == 'png' ) {
                              ?>  <!-- 
                                  <img src="../archivos/<?php echo $fila['arc_con']; ?>" class="img-fluid" style="height: 250px; width: 250px;">
                                  <br> -->
                                  <i class="fas fa-image fa-1x orange-text"></i> Descargar
                                  <br>
                                  <a href="../archivos/<?php echo $fila['arc_con']; ?>" data-lightbox="roadtrip">
                                    <img src="../archivos/<?php echo $fila['arc_con']; ?>" class="img-fluid" style="height: 250px; width: 250px;">
                                  </a>

                                  

                              <?php
                                }
                              ?>
                              
                          </a>
                      <?php
                        } else{
                      ?>
                          <p class="mb-0">
                            <?php echo $fila['mensaje']; ?>
                          </p>

                      <?php
                        }
                      ?>
                    </div>
                  </li>

            <?php

              }else if($fila['tipoUsuario'] == 'Alumno'){
                //echo "Es de tipo usuario"."<br>";

                $sqlDatosSala = "SELECT * FROM alumno WHERE id_alu = '$usuario'";
                $resultadoDatosSala = mysqli_query($db, $sqlDatosSala);
                $datosSala = mysqli_fetch_assoc($resultadoDatosSala);

            ?>

                  <li class="d-flex justify-content-between mb-4">
                    <div class="avatar rounded-circle mr-2 ml-lg-3 ml-0 z-depth-1">
                    </div>
                    <div class="chat-body white p-3 ml-2 z-depth-1">
                      <div class="header"><img width="40px" height="40px" src="../uploads/<?php echo $datosSala['fot_alu']; ?>" alt="avatar" class="avatar rounded-circle mr-2 ml-lg-3 ml-0 z-depth-1">
                        <strong class="primary-font"><?php echo $datosSala['nom_alu']; ?></strong>
                        <small class="pull-right text-muted"><i class="far fa-clock"></i> <?php echo $hor; ?></small>
                      </div>
                      <hr class="w-100">
                      <?php  
                        if ( $fila['arc_con'] != '' ) {
                      ?>
                          <a href="../archivos/<?php echo $fila['arc_con']; ?>" download class="btn-link" title="Descargar: <?php echo $fila['arc_con']; ?>">
                              <?php  
                                if ( $formatoArchivo == 'docx' ) {
                              ?>
                                  <i class="fas fa-file-word fa-1x blue-text"></i> Descargar

                              <?php
                                } else if ( $formatoArchivo == 'pptx' ) {
                              ?>
                                <i class="fas fa-file-powerpoint fa-1x orange-text"></i> Descargar

                              <?php 
                                } else if ( $formatoArchivo == 'pdf' ) {
                              ?>
                                  <i class="fas fa-file-pdf fa-1x red-text"></i> Descargar

                              <?php 
                                } else if ( $formatoArchivo == 'xlxs' ){
                              ?>

                                  <i class="fas fa-file-excel fa-1x green-text"></i> Descargar

                              <?php
                                } else if ( ( $formatoArchivo == 'jpg' ) || ( $formatoArchivo == 'jpeg' ) || $formatoArchivo == 'png' ) {
                              ?>  <!-- 
                                  <img src="../archivos/<?php echo $fila['arc_con']; ?>" class="img-fluid" style="height: 250px; width: 250px;">
                                  <br> -->
                                  <i class="fas fa-image fa-1x orange-text"></i> Descargar
                                  <br>
                                  <a href="../archivos/<?php echo $fila['arc_con']; ?>" data-lightbox="roadtrip">
                                    <img src="../archivos/<?php echo $fila['arc_con']; ?>" class="img-fluid" style="height: 250px; width: 250px;">
                                  </a>

                                  

                              <?php
                                }
                              ?>
                              
                          </a>
                      <?php
                        } else{
                      ?>
                          <p class="mb-0">
                            <?php echo $fila['mensaje']; ?>
                          </p>

                      <?php
                        }
                      ?>
                    </div>
                  </li>

            <?php

              }

            } 



            $mensajes = mysqli_num_rows($resultadoSalas);

            echo '<span id="aux" value="'.$mensajes.'"></span>';

          ?>