<?php  
  //ARCHIVO VIA AJAX PARA OBTENER ALUMNOS DEL PLANTEL
  //alumnos.php
  require('../inc/cabeceras.php');
  require('../inc/funciones.php');

?>


  <!-- BOTON FLOTANTE AGREGAR ALUMNO-->
  <a class="btn-floating btn-lg  flotante btn-info" id="agregarAlumno"><i class="fas fa-plus" title="Agregar Alumno"></i></a>
  <!-- FIN BOTON FLOTANTE AGREGAR ALUMNO-->

  <a class="btn btn-primary flotante waves-effect btn-sm" style="bottom: 120px;" title="Ver selección de alumnos" id="btn_seleccion_alumnos">
    Selección <span class="badge badge-danger ml-2" id="badge_seleccion_alumnos"></span>
  </a>


<!-- TITULO -->
<!-- <div class="row animated fadeIn">
  <div class="col-md-6 text-left">
    <span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Todos los alumnos del plantel">
      <i class="fas fa-certificate"></i>

      Alumnos del Plantel
      
    </span><br>
  </div>
</div> -->
<!-- FIN TITULO -->


<!-- ROW FILTROS -->
<!-- <div class="row animated fadeIn" >


  


</div> -->
<!-- FIN ROW FILTROS -->

<!-- ROW TABLA -->

<div class="row animated fadeIn">
  
  <div class="col-md-12 text-center">
    <?php

          $annios = $_POST['annios'];
          $meses = $_POST['meses'];

          $cadena = " ( YEAR(ing_alu) = ' $annios[0] ' ) AND ( MONTH( ing_alu ) = ' $meses[0] ' ) ";
          $cadenaAux = "";
          for ( $i = 0 ; $i < sizeof( $annios ) ; $i++ ) { 
            
            if ( $i == 0 ) {
            
              $sqlAlumnos = "
                SELECT * 
                FROM alu_ram 
                INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
                INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
                INNER JOIN generacion ON generacion.id_gen = alu_ram.id_gen1
                WHERE id_pla1 = '$plantel' AND ( YEAR(ing_alu) = ' $annios[$i] ' ) AND ( MONTH(ing_alu) = ' $meses[$i] ' )
                ORDER BY id_alu_ram DESC
              ";
            } else {

              

              
              $cadenaAux = " OR ( YEAR(ing_alu) = ' $annios[$i] ' ) AND ( MONTH(ing_alu) = ' $meses[$i] ' ) ";
              $cadena = $cadena.$cadenaAux;

              if ( $i == ( sizeof( $annios ) - 1 ) ) {
                $sqlAux = "
                  SELECT * 
                  FROM alu_ram 
                  INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
                  INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
                  INNER JOIN generacion ON generacion.id_gen = alu_ram.id_gen1
                  WHERE id_pla8 = '$plantel' AND ( 
                ";


                $sqlAlumnos = $sqlAux.$cadena." ) ORDER BY id_alu_ram DESC";

              }

            }
            


          }

      // echo $sqlAlumnos;
    
      $resultadoAlumnos = mysqli_query($db, $sqlAlumnos);


    ?>
  <table id="myTable" class="table table-hover table-bordered table-sm table-responsive table-striped" cellspacing="0" width="100%">
    <thead class="grey text-white">
      <tr>
        <th class="letraPequena font-weight-normal">#</th>
        <th class="letraPequena font-weight-normal">Selección</th>
        <th class="letraPequena font-weight-normal">Matrícula</th>
        <th class="letraPequena font-weight-normal">Subestatus</th>
        <th class="letraPequena font-weight-normal">Ciclo Escolar</th>
        <th class="letraPequena font-weight-normal">Nombre</th>
        <th class="letraPequena font-weight-normal">Ingreso</th>
        <th class="letraPequena font-weight-normal">Programa</th>
        <th class="letraPequena font-weight-normal">Generación Académica</th>
        <th class="letraPequena font-weight-normal">Estatus Académico</th>
        <th class="letraPequena font-weight-normal">Saldo</th>
        
        <th class="letraPequena font-weight-normal">Carga Académica</th>
        <th class="letraPequena font-weight-normal">Entrega de Documentación</th>
        
        <th class="letraPequena font-weight-normal">Actividad Académica</th>

        <th class="letraPequena font-weight-normal">Acción</th>
        <th class="letraPequena font-weight-normal">Historiales</th>
        <th class="letraPequena font-weight-normal">Estatus de Pago</th>

        <th class="letraPequena font-weight-normal">Baja temporal</th>
      </tr>
    </thead>


    <?php 
      $i = 1;
      while( $filaAlumnos = mysqli_fetch_assoc($resultadoAlumnos) ) {
        $id_ram = $filaAlumnos['id_ram3'];
    ?>
      <!-- DEFINICION DE ESTATUS ACADEMICO Y DE PAGOS -->
      <?php  
        $id_alu_ram = $filaAlumnos['id_alu_ram'];
        $id_alu = $filaAlumnos['id_alu'];
        $estatusAlumno = estatusAlumnoServer($id_alu_ram, $id_ram);
        $estatus_pagos = obtenerEstatusPagoAlumnoServer( $id_alu_ram );
        $nombre_alumno = $filaAlumnos['nom_alu']." ".$filaAlumnos['app_alu']." ".$filaAlumnos['apm_alu'];

        if ($estatusAlumno == 'Egresado') {
      ?>

          <?php
              if( $estatus_pagos == 'Sin adeudo' ){
          ?>
                <tr class="alumnoEgresado alumnoSinAdeudo">
          <?php
              }else if( $estatus_pagos == 'Con adeudo' ){
          ?>
                <tr class="alumnoEgresado alumnoConAdeudo">

          <?php
              }
          ?>
 

      <?php
        }else if( ( $estatusAlumno == 'Activo' ) && ( $filaAlumnos['est_alu'] == 'Activo' ) ){
      ?>
          <?php
              if( $estatus_pagos == 'Sin adeudo' ){
          ?>
                <tr class="alumnoActivo alumnoSinAdeudo">
          <?php
              }else if( $estatus_pagos == 'Con adeudo' ){
          ?>
                <tr class="alumnoActivo alumnoConAdeudo">
          <?php
              }
          ?>
      <?php
        }else if($estatusAlumno == 'Inactivo'){
      ?>
          <?php
              if( $estatus_pagos == 'Sin adeudo' ){
          ?>
                <tr class="alumnoInactivo alumnoSinAdeudo">
          <?php
              }else if( $estatus_pagos == 'Con adeudo' ){
          ?>
                <tr class="alumnoInactivo alumnoConAdeudo">
          <?php
              }
          ?>

      <?php
        }
      ?>
      <!-- FIN DEFINICION DE ESTATUS ACADEMICO Y DE PAGOS -->
      
        <td class="letraPequena font-weight-normal"><?php echo $i; $i++;?></td>
        

        <td class="letraPequena font-weight-normal">
          <input type="checkbox" class="form-check-input seleccionAlumno" id="seleccion<?php echo $i; ?>" value="<?php echo $id_alu_ram; ?>" id_alu_ram="<?php echo $id_alu_ram; ?>" id_ram="<?php echo $id_ram; ?>" estatus_pago="<?php echo $estatus_pagos; ?>" nombre_alumno="<?php echo $nombre_alumno; ?>">
          <label class="form-check-label letraPequena font-weight-normal" for="seleccion<?php echo $i; ?>"></label>
        </td>
        
     
        <td class="letraPequena font-weight-normal"><?php echo $filaAlumnos['bol_alu']; ?></td>
        
        <!-- SUBESTATUS -->
        <td class="letraPequena font-weight-normal">
          <?php
            if ( $filaAlumnos['est1_alu_ram'] == NULL ) {
               echo "Alta sin inscribir";
            } else {
              echo $filaAlumnos['est1_alu_ram'];  
            } 
             
          ?>
        </td>
        <!-- FIN SUBESTATUS -->
        

        <!-- CICLO ESCOLAR -->
        <td class="letraPequena font-weight-normal">
          <?php
            $estatusAcademico = estatusAlumnoServer($id_alu_ram, $id_ram);

            if ( $estatusAcademico == 'Activo' ) {
             
              $sqlCiclo = "
                SELECT *
                FROM sub_hor
                INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
                INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
                INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
                INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
                INNER JOIN alu_hor ON alu_hor.id_sub_hor5 = sub_hor.id_sub_hor
                INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
                INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
                INNER JOIN rama ON rama.id_ram = materia.id_ram2
                WHERE id_alu_ram1 = '$id_alu_ram' AND est_alu_hor = 'Activo'
              ";

              $resultadoCiclo = mysqli_query( $db, $sqlCiclo );

              $filaCiclo = mysqli_fetch_assoc( $resultadoCiclo );

              echo $filaCiclo['nom_cic'];
            
            } else if ( $estatusAcademico == 'Inactivo' ) {
              
              echo "N/A";
            
            }
          ?>
        </td>
        <!-- FIN CICLO ESCOLAR -->

        <td class="letraPequena font-weight-normal">
          <a class="link-chido consultaAlumno text-primary" id_alu_ram="<?php echo $filaAlumnos['id_alu_ram']; ?>" title="Resumen General de <?php echo $filaAlumnos['nom_alu']." ".$filaAlumnos['app_alu']." ".$filaAlumnos['apm_alu']; ?>">
            <?php echo $filaAlumnos['nom_alu']." ".$filaAlumnos['app_alu']." ".$filaAlumnos['apm_alu']; ?>
          </a>
        </td>
      
        <td class="letraPequena font-weight-normal"><?php echo fechaFormateadaCompacta($filaAlumnos['ing_alu']); ?></td>

        <td class="letraPequena font-weight-normal"><?php echo $filaAlumnos['nom_ram']; ?></td>
        <td class="letraPequena font-weight-normal"><?php echo $filaAlumnos['nom_gen']; ?></td>

     
        
          <?php

            
            
            if ( $estatusAcademico == 'Inactivo' ) {
          ?>
              <td class="waves-effect alumnoPendienteMotivo" id_alu_ram="<?php echo $id_alu_ram; ?>">
                
                <a class="chip grey darken-1 text-white waves-effect  letraPequena font-weight-normal" >
                  <?php echo $estatusAcademico; ?>
                </a>
              
              </td>

          <?php
            } else if ( $estatusAcademico == 'Activo' ) {
          ?>  
              <td>

                <a class="chip bg-success text-white letraPequena font-weight-normal">
                  <?php echo $estatusAcademico; ?>
                </a>
              
              </td>
                
          <?php
            } else if ( $estatusAcademico == 'Egresado' ) {
          ?>
              <td>

                <a class="chip info-color letraPequena font-weight-normal text-white">
                  <?php echo $estatusAcademico; ?>
                </a>
              
              </td>
                

          <?php
            }
          ?>
        </td>



        <!-- ADEUDO -->
        <td class="letraPequena font-weight-normal">
          
            <?php
              if ( obtenerSaldoAlumnoFechaHoyServer( $id_alu_ram ) > 0 ) {
            ?>
              <a class="chip bg-danger text-white waves-effect letraPequena font-weight-normal" style="width: 70px;">
                
            <?php
                 echo "$ ".obtenerSaldoAlumnoFechaHoyServer( $id_alu_ram );
              } else {

            ?>
              <a class="chip bg-info text-white waves-effect letraPequena font-weight-normal" style="width: 70px;">
                
            <?php
                echo "$ 0";
              }
            ?>
            </a>
        </td>
        <!-- FIN ADEUDO -->

        
        
        <td class="letraPequena font-weight-normal">
          <?php  
            $totalCarga = estatusAlumnoCargaServer($id_alu_ram, $id_ram);

            if ($totalCarga > 0) {
          ?>
              <span class="chip info-color text-white waves-effect horarioAlumno" id_alu="<?php echo $id_alu; ?>" id_alu_ram="<?php echo $id_alu_ram; ?>" title="Haz clic para ver horario de <?php echo $filaAlumnos['nom_alu']; ?>" id_ram="<?php echo $filaAlumnos['id_ram']; ?>">
                <?php echo estatusAlumnoCargaServer($id_alu_ram, $id_ram); ?>  
              </span>
          <?php
            }else{
          ?>
              <span class="chip grey darken-1 text-white waves-effect" title="Sin carga de materias">
                <?php echo estatusAlumnoCargaServer($id_alu_ram, $id_ram); ?>  
              </span>

          <?php
            }
          ?>
          
        </td>

        <!-- DOCUMENTACION -->
        <td class="letraPequena font-weight-normal">
          
            <?php
              $estatusDocumentacion = obtenerEstatusDocumentacionAlumnoServer( $id_alu_ram );
              echo $estatusDocumentacion;
            ?>
            
        </td>
        <!-- FIN DOCUMENTACION -->

        <!-- ACTIVIDAD ACADEMICA -->
        <td class="letraPequena font-weight-normal text-center">
              
          <?php  
            if ( $estatusAcademico == 'Activo' ) {
          ?>

            <?php
              $estatusActividad = obtenerEstatusActividadAcademicaAlumnoServer( $id_alu_ram );
              if ( $estatusActividad == 'Adeudo' ) {
            ?>
                <span class="chip danger-color text-white letraPequena font-weight-normal waves-effect actividadesAlumno" id_alu_ram="<?php echo $id_alu_ram; ?>" title="Haz clic para conocer la actividad académica de <?php echo $filaAlumnos['nom_alu']; ?> (presenta actividades vencidas)">
                    <?php echo $estatusActividad; ?>
                </span>

            <?php
              } else {
            ?>
                <a class="chip bg-success text-white waves-effect  letraPequena font-weight-normal actividadesAlumno" title="Haz clic para conocer la actividad académica de <?php echo $filaAlumnos['nom_alu']; ?>" id_alu_ram="<?php echo $id_alu_ram; ?>">
                  <?php echo $estatusActividad; ?>
                </a>


            <?php
              }
            ?>
    

          <?php
            } else if ( $estatusAcademico == 'Inactivo' ) {
          ?>
              
                <a class="chip grey darken-1 text-white waves-effect  letraPequena font-weight-normal" >
                  <?php echo $estatusAcademico; ?>
                </a>
              

          <?php
            } else if ( $estatusAcademico == 'Egresado' ) {
          ?>
                <a class="chip info-color letraPequena font-weight-normal text-white ">
                  <?php echo $estatusAcademico; ?>
                </a>              
          <?php
            }
          ?>

        </td>

        <!-- FIN ACTIVIDAD ACADEMICA -->
        

        <!-- BOTONES DE ACCION -->
        <td class="letraPequena font-weight-normal">          

          <a class="chip info-color text-white waves-effect letraPequena font-weight-normal edicion" title="Editar a <?php echo $filaAlumnos['nom_alu']; ?>" edicion="<?php echo $filaAlumnos['id_alu']; ?>" rama="<?php echo $filaAlumnos['id_ram']; ?>">
            Editar
          </a>


          <a class="chip danger-color text-white waves-effect letraPequena font-weight-normal eliminacion" title="Eliminar a <?php echo $filaAlumnos['nom_alu']; ?>" eliminacion="<?php echo $filaAlumnos['id_alu_ram']; ?>" alumno="<?php echo $filaAlumnos['nom_alu'].' '.$filaAlumnos['app_alu'].' '.$filaAlumnos['apm_alu']; ?> ">
            Eliminar
          </a>

        </td>

        <td class="letraPequena font-weight-normal">
          <a href="historial_academico.php?id_alu_ram=<?php echo $filaAlumnos['id_alu_ram']; ?>" class="chip info-color text-white waves-effect letraPequena font-weight-normal" title="Historial Académico de <?php echo $filaAlumnos['nom_alu'].' '.$filaAlumnos['app_alu'].' '.$filaAlumnos['apm_alu']; ?>">
            Académico
          </a>

          <!-- <a href="historial_asistencias.php?id_alu_ram=<?php echo $filaAlumnos['id_alu_ram']; ?>" class="chip info-color text-white waves-effect letraPequena font-weight-normal" title="Historial de Asistencias de <?php echo $filaAlumnos['nom_alu'].' '.$filaAlumnos['app_alu'].' '.$filaAlumnos['apm_alu']; ?>">
            Asistencias
          </a> -->
        </td>
        <!-- FIN BOTONES DE ACCION -->


        <?php 

          if ( $estatus_pagos == 'Sin adeudo' ) {
        ?>
            <td class="letraPequena font-weight-normal">
              <a class="chip bg-success text-white waves-effect letraPequena font-weight-normal" title="El alumno no tiene adeudos" style="width: 80px;">
                <?php echo $estatus_pagos; ?>
              </a>
            </td>
        <?php 
          } else if ( $estatus_pagos == 'Con adeudo' ) {
        ?>
            <td class="letraPequena font-weight-normal">
              <a class="chip grey darken-1 text-white waves-effect letraPequena font-weight-normal" title="El alumno presenta adeudos" style="width: 80px;">
                <?php echo $estatus_pagos; ?>
              </a>
            </td>
        <?php
          }
          
        ?>

        <!-- BAJA TEMPORAL -->
        <td class="letraPequena font-weight-normal">          

          <?php echo $filaAlumnos['est2_alu_ram']; ?>

        </td>
        <!-- FIN BAJA TEMPORAL -->

      </tr>

    <?php
      } 

    ?>
  </table>
    
  </div>
  
</div>
<!--  FIN ROW TABLA-->
<!-- FIN CONTENIDO -->


<!-- CONTENIDO MODAL AGREGAR ALUMNO -->
<div class="modal fade text-left" id="agregarAlumnoModal">
  <div class="modal-dialog cascading-modal" role="document">
    <!--Content-->
    <div class="modal-content">

      <!--Modal cascading tabs-->
      <div class="modal-c-tabs">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs md-tabs tabs-2 grey darken-1 white-text" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#panel7" role="tab" title="Alumno nuevo">
              <i class="fas fa-user-plus mr-1"></i>
              
              Nuevo Alumno
            </a>
          </li>
   
        </ul>

        <!-- Tab panels -->
        <div class="tab-content">
          <!--Panel 7-->
          <div class="tab-pane fade in show active" id="panel7" role="tabpanel">

            <!--Body-->
            <form id="agregarAlumnoFormulario" enctype="multipart/form-data" method="POST">
            <div class="modal-content">
              
              <div class="modal-body mx-3">


                <!-- PROGRAMAS -->
                <div class="row">
                  <!-- PROGRAMAS -->
                  <div class="col-md-12 text-left">
                    <div class="card">
                      <div class="card-header bg-white">
                        <i class="fas fa-graduation-cap grey-text"></i> Elige un programa académico
                      </div>
                      <div class="card-body">


                            
                        <?php  
                          $sqlProgramas = "
                            SELECT *
                            FROM rama
                            WHERE id_pla1 = '$plantel'
                            ORDER BY id_ram ASC
                          ";

                          $resultadoProgramas = mysqli_query( $db, $sqlProgramas );


                          $contadorProgramas = 1;

                     
                            while( $filaProgramas = mysqli_fetch_assoc( $resultadoProgramas ) ){
                          ?>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input programas" id="programaModal<?php echo $contadorProgramas; ?>" value="<?php echo $filaProgramas['id_ram']; ?>" name="id_ram[]">
                                    <label class="form-check-label letraPequena font-weight-normal font-weight-bold" for="programaModal<?php echo $contadorProgramas; ?>">
                                  
                                      <?php echo $filaProgramas['nom_ram']; ?>

                                    </label>
                          
                                </div>
                            

                        <?php
                            $contadorProgramas++;
                          }
                          // FIN while
                        ?>



                      </div>
                    </div>
                  </div>
                  <!-- FIN PROGRAMAS -->
                </div>
                <!-- FIN PROGRAMAS -->

                <br>

                <!-- GENERACIONES -->
                <div class="row">
                  <div class="col-md-12 text-left" id="contenedor_generaciones">
                    
                  </div>
                </div>
                <!-- FIN GENERACIONES -->

                <div class="row">
                  <div class="col md-4">
                    <div class="md-form mb-5">
                        <i class="fas fa-user prefix grey-text  estilo_input"></i>
                        <input type="text" id="nom_alu" name="nom_alu" class="form-control validate estilo_input">
                        <label id="nom_alu"  for="nom_alu" class="estilo_input">Nombre</label>
                      </div>
                  </div>
                  <div class="col md-4">
                    <div class="md-form mb-5">
                      <i class="far fa-address-card prefix grey-text estilo_input"></i>
                      <input type="text" id="app_alu" name="app_alu" class="form-control validate estilo_input">
                      <label class="estilo_input"  for="app_alu">Apellído Paterno</label>
                    </div>
                  </div>
                  <div class="col md-4">
                    <div class="md-form mb-5">
                      <i class="far fa-address-card prefix grey-text estilo_input"></i>
                      <input type="text" id="apm_alu" name="apm_alu" class="form-control validate estilo_input">
                      <label class="estilo_input"  for="apm_alu">Apellído Materno</label>
                    </div>
                  </div>
                </div>


                <div class="row">
                  <div class="col md-12">
                    <div class="alert alert-warning letraMediana text-center" role="alert">
                      <h4 class="alert-heading letraGrande">¡Aviso!</h4>

                      <hr>
                      <p class="mb-0">
                        A partir de ahora, se solicitará el <strong>correo original</strong> del alumno. Para mandarle la dirección web de la plataforma, así como su cuenta de acceso, contraseña y un mensaje de bienvenida ( una vez que el alumno sea dado de alta, ayúdalo verificando el correo en su buzón, porque seguramente lo mandará a spam ).
                      </p>
                    </div>

                    <div class="md-form mb-5">
                      <i class="fas fa-envelope prefix grey-text estilo_input"></i>
                      <input type="text" id="cor1_alu" class="form-control estilo_input" name="cor1_alu">
                      <label for="cor1_alu" class="estilo_input">Correo electrónico original</label>
                    </div>
                  </div>
                </div>


                <div class="row">
                  <div class="col md-12">
                    <div class="form-check" style="left: -5%;">
                        <input type="checkbox" class="form-check-input estilo_input" id="checkboxMatriculaCompuesta" checked>
                        <label class="letraPequena font-weight-normal font-weight-bold active estilo_input" for="checkboxMatriculaCompuesta">
                          Matrícula Compuesta (Formato: "mmyy000000")
                        </label>
                        <div class="md-form mb-5" id="matriculaAlumno">

                          <i class="far fa-address-card prefix grey-text estilo_input"></i>

                          <input type="text" id="bol_alu" name="bol_alu" class="form-control validate estilo_input" value="">
                          <label  for="bol_alu" class="estilo_input">Matrícula</label>
                        </div>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col md-12" style="margin-top: -10%;">
                    <span class="estilo_input" id="output"></span>
                    <div class="md-form mb-5">
                      <i class="fas fa-envelope prefix grey-text estilo_input"></i>
                      <input type="text" id="correo" class="form-control estilo_input" name="correo">
                      <label for="correo" class="estilo_input">Correo electrónico</label>
                    </div>
                  </div>
                </div>


                


                <div class="row">
                  <div class="col md-4">
                    <div class="md-form mb-5">
                      <i class="fas fa-key prefix grey-text estilo_input"></i>
                      <input type="text" id="pas_alu" name="pas_alu" class="form-control validate estilo_input">
                      <label class="estilo_input" for="pas_alu">Contraseña</label>
                    </div>
                  </div>
                  <div class="col md-4">
                    <div class="md-form mb-5">
                      <i class="fas fa-map-marker-alt prefix grey-text estilo_input"></i>
                      <input type="text" id="pro_alu" name="pro_alu" class="form-control validate estilo_input">
                      <label  for="pro_alu" class="estilo_input">Procedencia</label>
                    </div>    
                  </div>  
                </div>
                  
                <div class="row">
                  <div class="col md-4">
                    <div class="md-form mb-5">
                      <i class="fas fa-venus-mars prefix grey-text estilo_input"></i>
                      <input type="text" id="gen_alu" name="gen_alu" class="form-control validate estilo_input">
                      <label class="estilo_input"  for="gen_alu">Género</label>
                    </div>
                  </div>
                  <div class="col md-4">
                    <div class="md-form mb-5">
                      <i class="fas fa-phone prefix grey-text estilo_input"></i>
                      <input type="text" id="tel_alu" name="tel_alu" class="form-control validate estilo_input">
                      <label class="estilo_input"  for="tel_alu">Teléfono</label>
                    </div>
                  </div>
                  <div class="col md-4">
                    <div class="md-form mb-5">
                      <i class="far fa-address-card prefix grey-text estilo_input"></i>
                      <input type="text" id="cur_alu" name="cur_alu" class="form-control validate estilo_input">
                      <label  for="cur_alu" class="estilo_input">CURP</label>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <label for="Ingreso" class="estilo_input">Fecha de Nacimiento</label><br>
                    <div class="md-form mb-2">
                      <i class="far fa-calendar-check prefix grey-text estilo_input"></i>
                      <input type="date" id="nac_alu" name="nac_alu" class="form-control validate estilo_input" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col md-4">
                    <div class="md-form mb-5">
                      <i class="fas fa-percentage prefix grey-text estilo_input"></i>          
                      <input class="estilo_input" type="number" id="bec_alu_ram" class="form-control" name="bec_alu_ram" min="0" max="100" step=".1">
                        <label class="estilo_input" for="bec_alu_ram">Beca Inscripción/Reinscripción</label>
                    </div>
                  </div>
                  <div class="col md-4">
                    <div class="md-form mb-5">
                      <i class="fas fa-percentage prefix grey-text estilo_input"></i>          
                      <input class="estilo_input" type="number" id="bec2_alu_ram" class="form-control" name="bec2_alu_ram" min="0" max="100" step=".1">
                        <label class="estilo_input" for="bec2_alu_ram">Beca <br>Colegiatura</label>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="md-form mb-5">
                      <div class="file-field">
                        <div class="btn btn-info btn-sm float-left">
                          <span>Sube una foto</span>
                          <input type="file" id="fot_alu" name="fot_alu">
                        </div>
                        <div class="file-path-wrapper">
                          <input class="file-path validate" type="text" placeholder="3MB, JPEG, JPG o PNG">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <div class="md-form mb-5">
                      <i class="fas fa-map-marker-alt prefix grey-text estilo_input"></i>
                      <input type="text" id="dir_alu" name="dir_alu" class="form-control validate estilo_input">
                      <label class="estilo_input"  for="dir_alu">Dirección</label>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="md-form mb-5">
                      <i class="fas fa-map-marker-alt prefix grey-text estilo_input"></i>
                      <input type="text" id="cp_alu" name="cp_alu" class="form-control validate estilo_input">
                      <label class="estilo_input"  for="cp_alu">Código Postal</label>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="md-form mb-5">
                      <i class="fas fa-map-marker-alt prefix grey-text estilo_input"></i>
                      <input type="text" id="col_alu" name="col_alu" class="form-control validate estilo_input">
                      <label class="estilo_input" for="col_alu">Colonia</label>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="md-form mb-5">
                      <i class="fas fa-map-marker-alt prefix grey-text estilo_input"></i>
                      <input type="text" id="del_alu" name="del_alu" class="form-control validate estilo_input">
                      <label class="estilo_input"  for="del_alu">Delegación</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="md-form mb-5">
                      <i class="fas fa-map-marker-alt prefix grey-text estilo_input"></i>
                      <input type="text" id="ent_alu" name="ent_alu" class="form-control validate estilo_input">
                      <label class="estilo_input" for="ent_alu">Entidad</label>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                      <div class="md-form mb-5">
                        <i class="fas fa-user-friends prefix grey-text estilo_input"></i>
                        <input type="text" id="tut_alu" name="tut_alu" class="form-control validate estilo_input">
                        <label class="estilo_input" for="tut_alu">Tutor</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="md-form mb-5">
                        <i class="fas fa-phone prefix grey-text estilo_input"></i>
                        <input type="text" id="tel2_alu" name="tel2_alu" class="form-control validate estilo_input">
                        <label class="estilo_input" for="tel2_alu">Contacto de Tutor</label>
                      </div>
                    </div>
                </div>            
              </div>
              <div class="modal-footer d-flex justify-content-center">
                <button class="btn btn-info" type="submit" id="btn_agregar_alumno">Guardar <i class="fas fa-paper-plane-o ml-1"></i></button>
              </div>

              <h3 id="validacionCorreo"></h3><br>

            </div>
        </form>

          </div>
          <!--/.Panel 7-->

          
        </div>

      </div>
    </div>
    <!--/.Content-->
  </div>
</div>
</div>
<!-- FIN CONTENIDO MODAL AGREGAR ALUMNO -->


<!-- CONTENIDO MODAL EDITAR ALUMNO -->
<div class="modal fade text-left" id="editarAlumnoModal" >
  <div class="modal-dialog" role="document">
    
  <form id="editarAlumnoFormulario" enctype="multipart/form-data" method="POST">
      <div class="modal-content">
        <div class="modal-header text-center grey darken-1 white-text">
          <h4 class="modal-title w-100 font-weight-bold">Editar Alumno</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body mx-3">

          

          <div class="row">
            <div class="col-md-4">
              <div class="md-form mb-5">
                <i class="fas fa-user prefix grey-text estilo_input"></i>
                <input type="text" id="nombre" name="nombre" class="form-control validate estilo_input">
                <label class="estilo_input"  for="nombre">Nombre</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="md-form mb-5">
                <i class="far fa-address-card prefix grey-text estilo_input"></i>
                <input type="text" id="apellido1" name="apellido1" class="form-control validate estilo_input">
                <label class="estilo_input" for="apellido1">Apellído Paterno</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="md-form mb-5">
                <i class="far fa-address-card prefix grey-text estilo_input"></i>
                <input type="text" id="apellido2" name="apellido2" class="form-control validate estilo_input">
                <label class="estilo_input"  for="apellido2">Apellído Paterno</label>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col md-12">
              
              <div class="md-form mb-5">
                <i class="fas fa-envelope prefix grey-text estilo_input"></i>
                <input type="text" id="correo1_alumno" class="form-control estilo_input" name="correo1_alumno">
                <label for="correo1_alumno" class="estilo_input">Correo electrónico original</label>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-4">
              <div class="md-form mb-5">
                <i class="far fa-address-card prefix grey-text estilo_input"></i>
                <input type="text" id="boleta" name="boleta" class="form-control validate estilo_input">
                <label class="estilo_input" for="boleta">Matrícula</label>
              </div>
            </div>

            <div class="col-md-4">
                <div class="md-form mb-5">
                  <i class="fas fa-venus-mars prefix grey-text estilo_input"></i>
                  <input type="text" id="genero" name="genero" class="form-control validate estilo_input">
                  <label class="estilo_input" for="genero">Género</label>
                </div>
            </div>
            <div class="col-md-4">
              <div class="md-form mb-5">
                <i class="fas fa-phone prefix grey-text estilo_input"></i>
                <input type="text" id="telefono" name="telefono" class="form-control validate estilo_input">
                <label  for="telefono">Teléfono</label>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="row justify-content-md-center">
              <div class="col-xl-12 text-center">
                <span class="estilo_input"  id="outputEdicion"></span>
              </div>
            </div>
            <div class="col-md-12">   
                <div class="md-form mb-5">
                  <i class="fas fa-envelope prefix grey-text estilo_input"></i>
                  <input type="text" id="correoEdicion" class="form-control estilo_input" name="correoEdicion">
                  <label class="estilo_input" for="correoEdicion">Correo Electrónico</label>
                </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="md-form mb-5">
                <i class="fas fa-key prefix grey-text estilo_input"></i>
                <input type="text" id="password" name="password" class="form-control validate estilo_input">
                <label class="estilo_input" for="password">Contraseña</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="md-form mb-5">
                <i class="far fa-address-card prefix grey-text estilo_input"></i>
                <input type="text" id="curp" name="curp" class="form-control validate estilo_input">
                <label class="estilo_input" for="curp">CURP</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="md-form mb-5">
                <i class="fas fa-map-marker-alt prefix grey-text estilo_input"></i>
                <input type="text" id="procedencia" name="procedencia" class="form-control validate estilo_input">
                <label class="estilo_input" for="procedencia">Procedencia</label>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6"style="top: -3.5vh;">
              <div class="row">
                <label class="estilo_input" style="padding-left: 4vw;" for="Ingreso">Fecha de Nacimiento</label>
              </div>
              <div class="md-form mb-2">
                <i class="far fa-calendar-check prefix grey-text estilo_input"></i>
                <input type="date" id="nacimiento" name="nacimiento" class="form-control validate estilo_input">
              </div>
            </div>
            <div class="col-md-6">
              <div class="md-form mb-5">
                <i class="fas fa-percentage prefix grey-text estilo_input"></i>          
                <input type="number" id="beca_alu_ram" class="form-control estilo_input" name="beca_alu_ram" min="0" max="100" step=".1">
                  <label class="estilo_input" for="beca_alu_ram">Beca Inscripción/Reinscripción</label>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="md-form mb-5">
                <i class="fas fa-sort-numeric-up prefix grey-text estilo_input"></i>
                <input type="number" id="carga" name="carga" class="form-control validate estilo_input">
                <label class="estilo_input" for="carga">Carga Regular</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="md-form mb-5">
                <i class="fas fa-percentage prefix grey-text estilo_input"></i>          
                <input type="number" id="beca2_alu_ram" class="form-control estilo_input" name="beca2_alu_ram" min="0" max="100" step=".1">
                  <label class="estilo_input" for="beca2_alu_ram">Beca Colegiatura</label>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="md-form mb-5">
                <div class="file-field">
                  <div class="btn btn-info btn-sm float-left">
                    <span>Sube una foto</span>
                    <input type="file" id="foto" name="foto">
                  </div>
                  <div class="file-path-wrapper">
                    <input class="file-path validate" type="text" placeholder="3MB, JPEG, JPG o PNG" id="fotoText">
                  </div>
                </div>
              </div>
            </div>
          </div>
          

          <div class="row">
            <div class="col-md-4">
              <div class="md-form mb-5">
                <i class="fas fa-map-marker-alt prefix grey-text estilo_input"></i>
                <input type="text" id="direccion" name="direccion" class="form-control validate estilo_input">
                <label class="estilo_input" for="direccion">Dirección</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="md-form mb-5">
                <i class="fas fa-map-marker-alt prefix grey-text estilo_input"></i>
                <input type="text" id="colonia" name="colonia" class="form-control validate estilo_input">
                <label class="estilo_input" for="colonia">Colonia</label>
              </div>              
            </div>
            <div class="col-md-4">
              <div class="md-form mb-5">
                <i class="fas fa-map-marker-alt prefix grey-text estilo_input"></i>
                <input type="text" id="delegacion" name="delegacion" class="form-control validate estilo_input">
                <label class="estilo_input" for="delegacion">Delegación</label>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="md-form mb-5">
                <i class="fas fa-map-marker-alt prefix grey-text estilo_input"></i>
                <input type="text" id="entidad" name="entidad" class="form-control validate estilo_input">
                <label class="estilo_input" for="entidad">Entidad</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="md-form mb-5">
                <i class="fas fa-map-marker-alt prefix grey-text estilo_input"></i>
                <input type="text" id="codigo" name="codigo" class="form-control validate estilo_input">
                <label class="estilo_input" for="codigo">Código Postal</label>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-6">
              <div class="md-form mb-5">
                <i class="fas fa-user-friends prefix grey-text estilo_input"></i>
                <input type="text" id="tutor" name="tutor" class="form-control validate estilo_input">
                <label class="estilo_input" for="tutor">Tutor</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="md-form mb-5">
                <i class="fas fa-phone prefix grey-text estilo_input"></i>
                <input type="text" id="telefono2" name="telefono2" class="form-control validate estilo_input">
                <label class="estilo_input" for="telefono2">Contacto de Tutor</label>
              </div>
            </div>
          </div>
          



          <div class="md-form mb-5">
            <input type="hidden" id="identificador" name="identificador" class="form-control validate">
          </div>


          <div class="md-form mb-5">
            <input type="hidden" id="identificadorAlumnoRama" name="identificadorAlumnoRama" class="form-control validate">
          </div>


        </div>
        <div class="modal-footer d-flex justify-content-center">
          <button class="btn btn-success" type="submit">Actualizar <i class="fas fa-paper-plane-o ml-1"></i></button>
        </div>

        <h3 id="validacionCorreoEdicion"></h3><br>

      </div>
  </form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL EDITAR ALUMNO -->





<!-- INSCRIPCION MULTIPLE MODAL -->

<!-- Central Modal Small -->
<div class="modal fade" id="modalInscripcion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

  <!-- Change class .modal-sm to change the size of the modal -->
  <div class="modal-dialog modal-fluid" role="document">


    <div class="modal-content">
      <div class="modal-header grey darken-1 white-text text-center">
        <h4 class="modal-title w-100" id="myModalLabel">Inscripción</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="panzaModalInscripcion">
        


        


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Salir</button>
      </div>
    </div>
  </div>
</div>
<!-- Central Modal Small -->

<!-- FIN INSCRIPCION MULTIPLE MODAL -->




<!-- BAJA DE MATERIAS MULTIPLE MODAL -->

<!-- Central Modal Small -->
<div class="modal fade" id="modalBaja" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

  <!-- Change class .modal-sm to change the size of the modal -->
  <div class="modal-dialog modal-lg" role="document">


    <div class="modal-content">
      <div class="modal-header grey darken-1 white-text text-center">
        <h4 class="modal-title w-100">Baja Múltiple de Materias</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="panzaModalBaja">
        

        


      </div>
      <div class="modal-footer" id="footerModalBaja">
        
      </div>
    </div>
  </div>
</div>
<!-- Central Modal Small -->
<!-- FIN BAJA DE MATERIAS MODAL -->



<!-- HORARIO ALUMNO MODAL -->

<!-- Central Modal Small -->
<div class="modal fade" id="modalHorarioAlumno" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

  <!-- Change class .modal-sm to change the size of the modal -->
  <div class="modal-dialog modal-lg" role="document">


    <div class="modal-content">
      <div class="modal-header grey darken-1 white-text text-center">
        <h4 class="modal-title w-100" id="tituloModalHorarioAlumno"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body" id="panzaModalHorarioAlumno">
        
        


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<!-- Central Modal Small -->

<!-- FIN HORARIO ALUMNO MODAL -->



<!-- DOCUMENTACION ALUMNO MODAL -->
<div class="modal fade" id="modalDocumentacionAlumno" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

  <div class="modal-dialog" role="document">


    <div class="modal-content">
      <div class="modal-header grey darken-1 white-text text-center">
        <h6 class="modal-title w-100" id="tituloDocumentacionAlumno"></h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body" id="contenedorDocumentacionAlumno">
        
        

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- FIN DOCUMENTACION ALUMNO MODAL -->

<!-- MOTIVO ALUMNO PENDIENTE MODAL -->
<div class="modal fade" id="modalMotivoAlumno" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

  <div class="modal-dialog" role="document">


    <div class="modal-content">
      <div class="modal-header grey darken-1 white-text text-center">
        <h6 class="modal-title w-100" id="tituloMotivoAlumno"></h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body" id="contenedorMotivoAlumno">
        
        

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- FIN MOTIVO ALUMNO PENDIENTE MODAL -->

<!-- CONSULTA ALUMNO MODAL -->
<div class="modal fade" id="modalConsultaAlumno" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

  <div class="modal-dialog modal-lg" role="document">


    <div class="modal-content">
      <div class="modal-header grey darken-1 white-text text-center">
        <h6 class="modal-title w-100" id="tituloConsultaAlumno">
        </h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body bg-light" id="contenedorConsultaAlumno">
        
        

      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- FIN CONSULTA ALUMNO MODAL -->




<!-- MODAL CONSULTA ACTIVIDADES ALUMNO -->

<div class="modal fade" id="modalActividadesAlumno" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

  <!-- Change class .modal-sm to change the size of the modal -->
  <div class="modal-dialog modal-lg" role="document">


    <div class="modal-content">
      <div class="modal-header grey darken-1 white-text text-center">
        <h5 class="modal-title w-100" id="tituloActividadesAlumno"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body" id="contenedorActividadesAlumno">
        
        


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<!-- fin MODAL CONSULTA ACTIVIDADES ALUMNO -->


<script>
  $(document).ready(function () {



    $('#myTable').DataTable({
      


      
    
      dom: 'Bfrtpli',
      colReorder: true,
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
      "columnDefs": [
      {
          "targets": [ 16, 17 ],
          "visible": false
      }],
            
            
            "pageLength": 50,
            buttons: [

            
                    {
                        extend: 'excelHtml5',
                        messageTop: 'Listado de Alumnos del Plantel',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },                  

                    {
                        
                        extend: 'copyHtml5',
                        messageTop: 'Listado de Alumnos del Plantel',
                        exportOptions: {
                            columns: ':visible'
                        },

                    },

                    {
                        extend: 'print',
                        messageTop: 'Listado de Alumnos del Plantel',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },

                    {
                        extend: 'pdf',
                        pageSize: 'LEGAL',
                        messageTop: 'Listado de Alumnos del Plantel',
                        orientation: 'landscape',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },

            ],

      "language": {
                            "sProcessing":     "Procesando...",
                            "sLengthMenu":     "Mostrar _MENU_ registros",
                            "sZeroRecords":    "No se encontraron resultados",
                            "sEmptyTable":     "Ningún dato disponible en esta tabla",
                            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                            "sInfoPostFix":    "",
                            "sSearch":         "Buscar:",
                            "sUrl":            "",
                            "sInfoThousands":  ",",
                            "sLoadingRecords": "Cargando...",
                            "oPaginate": {
                                "sFirst":    "Primero",
                                "sLast":     "Último",
                                "sNext":     "Siguiente",
                                "sPrevious": "Anterior"
                            },
                            "oAria": {
                                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                            }
                        }
    });
    $('#myTable_wrapper').find('label').each(function () {
      $(this).parent().append($(this).children());
    });
    $('#myTable_wrapper .dataTables_filter').find('input').each(function () {
      $('#myTable_wrapper input').attr("placeholder", "Búsqueda general...").addClass('letraPequena font-weight-normal');
      $('#myTable_wrapper input').removeClass('form-control-sm');
    });
    $('#myTable_wrapper .dataTables_length').addClass('d-flex flex-row');
    $('#myTable_wrapper .dataTables_filter').addClass('md-form');
    $('#myTable_wrapper select').removeClass(
    'custom-select custom-select-sm form-control form-control-sm');
    $('#myTable_wrapper select').addClass('mdb-select');
    $('#myTable_wrapper .mdb-select').materialSelect();
    $('#myTable_wrapper .dataTables_filter').find('label').remove();
    var botonesAlumnos = $('#myTable_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
    //console.log(botones);

    //$("#contenedor_filtros_datatable").append($(".dataTables_length"));

    var table = $('#myTable').DataTable();



    var entrada = $('#myTable_filter');
    
    // DATOS DE DATATABLE LAYOUT

    
    $("#contenedor_buscador").html(entrada);

    // $("#contenedor_botones_datatable").append('<br>').append(botonesAlumnos);

    //alert( table.rows( '.alumnoInscrito' ).count() );
    

    // ESTATUS ACADEMICOY DE PAGOS
    $("#alumnosTotales").text(table.rows().count());
    $("#alumnosActivos").text(table.rows( '.alumnoActivo' ).count());
    $("#alumnosInactivos").text(table.rows( '.alumnoInactivo' ).count());
    $("#alumnosEgresados").text(table.rows( '.alumnoEgresado' ).count());

    $("#alumnosSinAdeudos").text(table.rows( '.alumnoSinAdeudo' ).count());
    $("#alumnosConAdeudos").text(table.rows( '.alumnoConAdeudo' ).count());


    //INDICADORES DINAMICOS
    table.on('draw', function(){
      $("#alumnosTotales").text(table.rows({ filter: 'applied' }).count());
      $("#alumnosActivos").text(table.rows( ['.alumnoActivo'], { filter: 'applied' }).count());
      $("#alumnosInactivos").text(table.rows( ['.alumnoInactivo'], { filter: 'applied' }).count());
      $("#alumnosEgresados").text(table.rows( ['.alumnoEgresado'], { filter: 'applied' }).count()); 

      $("#alumnosSinAdeudos").text(table.rows( ['.alumnoSinAdeudo'], { filter: 'applied' }).count());
      $("#alumnosConAdeudos").text(table.rows( ['.alumnoConAdeudo'], { filter: 'applied' }).count()); 
    });



    var columna = 6;

      // Extend dataTables search
    $.fn.dataTable.ext.search.push(

      
        function fechas( settings, data, dataIndex ) {
            var min  = $('#min-date').val();
            var max  = $('#max-date').val();
            


        var arregloFechas = moment(data[columna] || 0,"DD/MM/YYYY").format("YYYY-MM-DD"); 
            // Our date column in the table
            //console.log(moment(arregloFechas).isValid());


            if  ( 
                    ( min == "" || max == "" )
                    || 
                    ( moment(arregloFechas).isSameOrAfter(min) && 
                      moment(arregloFechas).isSameOrBefore(max))
                )
            {
                return true;
            }
            return false;
        }
    );

    // Re-draw the table when the a date range filter changes
    $('.date-range-filter').change( function() {
        table.draw();

    });

    // Re-draw the table when the radio buttons change
    $('.columna').change( function() {
        table.draw();

    });


    // CHECKBOXES
    //ESTATUS ACADEMICO
    $('.checador1').on( 'keyup change', function () {
      //console.log($(this));
        var busqueda = [];
      for(var i = 0; i < $('.checador1').length; i++){
        if($('.checador1').eq(i).prop("checked") == true){
          //console.log($('.checador1').eq(i).val());
          if(busqueda=="")
          {
            busqueda=$('.checador1').eq(i).val();   
          }
          else
          {
            busqueda = busqueda+'|'+$('.checador1').eq(i).val();    
          }
          
        }
      }
      
        var columna = $(this).attr("columna");
        if (busqueda != "") {
          table
        .columns( columna )
        .search( busqueda, true, false, false)
        .draw();
      }else{
          table
        .columns( columna )
        .search('')
        .draw();
        }
      
    });


    // CHECKBOXES
    //ESTATUS BAJA TEMPORAL
    $('.checador100').on( 'keyup change', function () {
      //console.log($(this));
        var busqueda = [];
      for(var i = 0; i < $('.checador100').length; i++){
        if($('.checador100').eq(i).prop("checked") == true){
          //console.log($('.checador100').eq(i).val());
          if(busqueda=="")
          {
            busqueda=$('.checador100').eq(i).val();   
          }
          else
          {
            busqueda = busqueda+'|'+$('.checador100').eq(i).val();    
          }
          
        }
      }
      
        var columna = $(this).attr("columna");
        if (busqueda != "") {
          table
        .columns( columna )
        .search( busqueda, true, false, false)
        .draw();
      }else{
          table
        .columns( columna )
        .search('')
        .draw();
        }
      
    });


    //ESTATUS DE PAGO
    $('.checador2').on( 'keyup change', function () {
      //console.log($(this));
        var busqueda = [];
      for(var i = 0; i < $('.checador2').length; i++){
        if($('.checador2').eq(i).prop("checked") == true){
          //console.log($('.checador2').eq(i).val());
          if(busqueda=="")
          {
            busqueda=$('.checador2').eq(i).val();   
          }
          else
          {
            busqueda = busqueda+'|'+$('.checador2').eq(i).val();    
          }
          
        }
      }
      
        var columna = $(this).attr("columna");
        if (busqueda != "") {
          table
        .columns( columna )
        .search( busqueda, true, false, false)
        .draw();
      }else{
          table
        .columns( columna )
        .search('')
        .draw();
        }
      
    });


    // CHECKBOX PROGRAMA
    $('.checador5').on( 'keyup change', function () {
      var busqueda = [];
      for(var i = 0; i < $('.checador5').length; i++){
        if($('.checador5').eq(i).prop("checked") == true){
          //console.log($('.checador1').eq(i).val());
          if(busqueda=="")
          {
            busqueda=$('.checador5').eq(i).val();   
          }
          else
          {
            busqueda = busqueda+'|'+$('.checador5').eq(i).val();    
          }
          
        }
      }
      
      var columna = $(this).attr("columna");
      if (busqueda != "") {
          table
          .columns( columna )
          .search( busqueda, true, false)
          .draw();
      }else{
          table
          .columns( columna )
          .search('')
          .draw();
      }
  
    });

    // FIN CHECKBOX PROGRAMA


    // CHECKBOX GENERACION
    $('.checador6').on( 'keyup change', function () {
      var busqueda = [];
      for(var i = 0; i < $('.checador6').length; i++){
        if($('.checador6').eq(i).prop("checked") == true){
          //console.log($('.checador1').eq(i).val());
          if(busqueda=="")
          {
            busqueda=$('.checador6').eq(i).val();   
          }
          else
          {
            busqueda = busqueda+'|'+$('.checador6').eq(i).val();    
          }
          
        }
      }
      
      var columna = $(this).attr("columna");
      if (busqueda != "") {
          table
          .columns( columna )
          .search( busqueda, true, false)
          .draw();
      }else{
          table
          .columns( columna )
          .search('')
          .draw();
      }
  
    });

    // FIN CHECKBOX GENERACION


    // CHECKBOX CICLO
    $('.checador7').on( 'keyup change', function () {
      var busqueda = [];
      for(var i = 0; i < $('.checador7').length; i++){
        if($('.checador7').eq(i).prop("checked") == true){
          //console.log($('.checador1').eq(i).val());
          if(busqueda=="")
          {
            busqueda=$('.checador7').eq(i).val();   
          }
          else
          {
            busqueda = busqueda+'|'+$('.checador7').eq(i).val();    
          }
          
        }
      }
      
      var columna = $(this).attr("columna");
      if (busqueda != "") {
          table
          .columns( columna )
          .search( busqueda, true, false)
          .draw();
      }else{
          table
          .columns( columna )
          .search('')
          .draw();
      }
  
    });

    // FIN CHECKBOX CICLO


    // CHECKBOX SUBESTATUS
    $('.checador11').on( 'keyup change', function () {
      var busqueda = [];
      for(var i = 0; i < $('.checador11').length; i++){
        if($('.checador11').eq(i).prop("checked") == true){
          //console.log($('.checador1').eq(i).val());
          if(busqueda=="")
          {
            busqueda=$('.checador11').eq(i).val();   
          }
          else
          {
            busqueda = busqueda+'|'+$('.checador11').eq(i).val();    
          }
          
        }
      }
      
      var columna = $(this).attr("columna");
      if (busqueda != "") {
          table
          .columns( columna )
          .search( busqueda ? '^'+busqueda+'$' : '', true, false, false)  
          .draw();
      }else{
          table
          .columns( columna )
          .search('')
          .draw();
      }
  
    });

    // FIN CHECKBOX SUBESTATUS

    
  });
</script>


<script>
  //CODIGO PARA TOMAR ID DE IMAGEN Y DESPLEGAR EN MODAL INFO DEL ALUMNO
  $('.imagenes').on('click', function(event) {
      event.preventDefault();

      var imagen = $(this).children().attr("imagen");

      console.log(imagen);
      /* Act on the event */
    });
</script>




<script>
  //VALIDACION EN TEMPO REAL DESDE EL INPUT DEL CORREO
  validacionCorreoTiempoReal();
  function validacionCorreoTiempoReal(){
    $('#correo').keyup(function(event) {
          var correo = $('#correo').val();

          console.log($('#correo').val());

          if (correo != '') {
            $.ajax({
              url: 'server/validacion_correo.php',
              type: 'POST',
              data: {correo},
              success: function(response){
                var respuesta = response; 


                if (respuesta == 'disponible') {
                  
                  $('#output').attr({
                    class: 'text-info'
                  });
                  $('#output').text("¡El correo electrónico está disponible!");

                }else{
                  $('#output').attr({
                    class: 'text-danger'
                  });
                  $('#output').text("¡El correo electrónico está ocupado!");

                }
              }
            })

          }else{
            $('#output').attr({class: 'text-warning'});
            $('#output').text("¡Ingresa un Correo Electrónico!");
          }
          
      });
  }



  validacionCorreoTiempoRealEdicion();
  function validacionCorreoTiempoRealEdicion(){
    $('#correoEdicion').keyup(function(event) {
          var correoEdicion = $('#correoEdicion').val();
          var identificador = $('#identificador').val();
          var tipo = "Alumno";

          console.log($('#correoEdicion').val());

          if (correoEdicion != '') {
            $.ajax({
              url: 'server/validacion_correo.php',
              type: 'POST',
              data: {correoEdicion, identificador, tipo},
              success: function(response){
                var respuesta = response;

                console.log(respuesta);

                if (respuesta == 'disponible') {
                  
                  $('#outputEdicion').attr({
                    class: 'text-info'
                  });
                  $('#outputEdicion').text("¡El correo electrónico está disponible!");

                } else if (respuesta == 'mio') {
                  
                  $('#outputEdicion').attr({
                    class: 'text-warning'
                  });
                  $('#outputEdicion').text("¡El correo electrónico es el mismo!");

                } else{
                  $('#outputEdicion').attr({
                    class: 'text-danger'
                  });
                  $('#outputEdicion').text("¡El correo electrónico está ocupado!");

                }
              }
            })

          }else{
            $('#outputEdicion').attr({class: 'text-warning'});
            $('#outputEdicion').text("¡Ingresa un Correo Electrónico!");
          }
          
      });
  }


</script>

<script>

  //FORMULARIO DE CREACION DE ALUMNO
  //CODIGO PARA AGREGAR ALUMNO NUEVO ABRIENDO MODAL


  $('#agregarAlumno').on('click', function(event) {
    console.log("click agregar");
    event.preventDefault();
    $('#agregarAlumnoModal').modal('show');
    $("#contenedor_generaciones").html("");
    $('#agregarAlumnoFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR

    $('#matriculaAlumno label').addClass('active');
    $('#matriculaAlumno i').addClass('active');
  

    // MATRICULA COMPUESTA
    obtenerMatriculaCompuesta();

    $("#checkboxMatriculaCompuesta").on('change', function(event) {
      event.preventDefault();
      /* Act on the event */

      obtenerMatriculaCompuesta();
      
    });

    function obtenerMatriculaCompuesta(){
      if ($("#checkboxMatriculaCompuesta")[0].checked == true) {
        
        $("#bol_alu").val( '<?php $valor = generarMatriculaCompuestaServer( $plantel ); echo $valor; ?>' ).focus();

        
      }else{
        
        $("#bol_alu").val( '' ).focus();
      }
    }

  });


  $('#agregarAlumnoFormulario').on('submit', function(event) {
    event.preventDefault();
    $("#btn_agregar_alumno").attr('disabled','disabled');

    var correo = $('#correo').val();


    $.ajax({
      url: 'server/validacion_correo.php',
      type: 'POST',
      data: {correo},
      success: function(respuesta){
        if (respuesta == "disponible") {

          $('#validacionCorreo').attr({class: 'text-info text-center'}).text("¡Correcto!");
          if ($("#fot_alu")[0].files[0]) {

            var fileName = $("#fot_alu")[0].files[0].name;
            var fileSize = $("#fot_alu")[0].files[0].size;


            var ext = fileName.split('.').pop();

            
            if(ext == 'jpg' || ext == 'jpeg' || ext == 'png'){
              if (fileSize < 3000000) {

                $.ajax({
            
                  url: 'server/agregar_alumno.php',
                  type: 'POST',
                  data: new FormData(agregarAlumnoFormulario), 
                  processData: false,
                  contentType: false,
                  cache: false,
                  success: function(respuesta){
                  console.log(respuesta);

                    if (respuesta == 'Exito') {
                      swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",}).
                      then((value) => {

                        obtenerAnnios();
                        


                      });
                      
                    }
                  }
                });
              }else{
                swal ( "¡Imagen inválida!" ,  "¡Te recordamos que el peso no debe exceder los 3MB!" ,  "error" )
              }
              
            }else{
              swal ( "¡Imagen inválida!" ,  "¡Te recordamos que los formatos aceptados son jpeg, jpg o png!" ,  "error" )
            }

          }else{

            //VALIDACION SI MANDA FOTO, EN CASO DE MANDAR VALIDA, SI NO, ACCEDE DIRECTAMENTE
            $.ajax({
            
              url: 'server/agregar_alumno.php',
              type: 'POST',
              data: new FormData(agregarAlumnoFormulario), 
              processData: false,
              contentType: false,
              cache: false,
              success: function(respuesta){
                console.log(respuesta);

                if (respuesta == 'Exito') {
                  swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",}).
                  then((value) => {
                    
                    obtenerAnnios();


                  });
                  
                }
              }
            });

          }

          
        }else{

          $('#validacionCorreo').attr({class: 'text-danger text-center'}).text("¡Datos Incorrectos!");

        }
      }
    }); 
  });
  
  
</script>

<script>
  //ELIMINACION
  $('.eliminacion').on('click', function(event) {
    event.preventDefault();
    /* Act on the event */
    var alumno = $(this).attr("eliminacion");
    var nombreAlumno = $(this).attr("alumno");

    // console.log(alumno);

    // VALIDACION PERMISOS

    swal({
      title: "¡Acceso Restringido!",
      icon: "warning",
      text: 'Necesitas permisos de administrador para continuar',
      content: {
        element: "input",
        attributes: {
          placeholder: "Ingresa una contraseña...",
          type: "password",
        },
      },

      button: {
        text: "Validar",
        closeModal: false,
      },
    })
    .then(name => {
      if (name){
        //console.log(name);
        var password = name;
        $.ajax({
                
            url: 'server/validacion_permisos.php',
            type: 'POST',
            data: {password},
            success: function(respuesta){
                //console.log(respuesta);

                if (respuesta == 'True') {

                    swal("Validado correctamente", "Continuar", "success", {button: "Aceptar",}).
                    then((value) => {
                        //
                        //console.log("Existe el password");
                        // CODIGO

                        swal({
                          title: "¿Deseas eliminar a "+nombreAlumno+"?",
                          text: "¡Una vez eliminado se perderán todos los datos relacionados a esa persona!",
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
                            url: 'server/eliminacion_alumno.php',
                            type: 'POST',
                            data: {alumno},
                            success: function(respuesta){
                              
                              if (respuesta == "true") {
                                console.log("Exito en consulta");
                                swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
                                then((value) => {
                                  // window.location.reload();


                                  obtenerAnnios();


                                });
                              }else{
                                console.log(respuesta);

                              }

                            }
                          });
                            
                          }
                        });

                        // FIN CODIGO
                    });
                    
                }else{
                    // LA CONTRASENA NO EXISTE
                    
                    swal({
                      title: "¡Datos incorrectos!",
                      text: 'No existe la contraseña...',
                      icon: "error",
                      button: "Aceptar",
                    });
                    swal.stopLoading();
                    swal.close();

                    
                }
            }
        });

      }else{
        // DATOS VACIOS
        swal({
          title: "¡Datos vacíos!",
          text: 'Necesitas ingresar una contraseña...',
          icon: "error",
          button: "Aceptar",
        });
        swal.stopLoading();
        swal.close();
      }
      
    });

    // FIN VALIDACION PERMISOS

  });


</script>



<script>
  //EDICION DE ALUMNO

  //EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE ALUMNO

  $('.edicion').on('click', function(event){
    event.preventDefault();
    $('#editarAlumnoFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
    var edicionAlumno = $(this).attr("edicion");
    var rama = $(this).attr("rama");
    $('#editarAlumnoFormulario label').addClass('active');
    $('#editarAlumnoFormulario i').addClass('active');

    //console.log(edicionAlumno);

    $.ajax({
      url: 'server/obtener_alumno.php',
      type: 'POST',
      dataType: 'json',
      data: {edicionAlumno, rama},
      success: function(datos){

        console.log(datos);

        $('#editarAlumnoModal').modal('show');
        $('#nombre').attr({value: datos.nom_alu});

        $('#apellido1').attr({value: datos.app_alu});
        $('#apellido2').attr({value: datos.apm_alu});
        
        $('#correo1_alumno').attr({value: datos.cor1_alu});
        $('#boleta').attr({value: datos.bol_alu});
        $('#genero').attr({value: datos.gen_alu});
        $('#telefono').attr({value: datos.tel_alu});
        $('#curp').attr({value: datos.cur_alu});
        $('#procedencia').attr({value: datos.pro_alu});
        $('#correoEdicion').attr({value: datos.cor_alu});
        $('#password').attr({value: datos.pas_alu});
        $('#nacimiento').attr({value: datos.nac_alu});
 
        $('#beca_alu_ram').attr({value: datos.bec_alu_ram*100});
        $('#beca2_alu_ram').attr({value: datos.bec2_alu_ram*100});
        $('#carga').attr({value: datos.car_alu_ram});
        
        $('#foto').attr({value: datos.fot_alu});
        $('#fotoText').attr({value: datos.fot_alu});
        $('#direccion').attr({value: datos.dir_alu});
        $('#codigo').attr({value: datos.cp_alu});
        $('#colonia').attr({value: datos.col_alu});
        $('#delegacion').attr({value: datos.del_alu});
        $('#entidad').attr({value: datos.ent_alu});
        $('#tutor').attr({value: datos.tut_alu});
        $('#telefono2').attr({value: datos.tel2_alu});
        $('#identificador').attr({value: datos.id_alu});
        $('#identificadorAlumnoRama').attr({value: datos.id_alu_ram});
        //AGREGADO O PRESERVACION DE DATOS DEL FORMULARIO DEL ALUMNO
        $('#editarAlumnoFormulario').on('submit', function(event) {
          event.preventDefault();

          var correoEdicion = $('#correoEdicion').val();
          var identificador = $('#identificador').val();
          var tipo = "Alumno";

          $.ajax({
            url: 'server/validacion_correo.php',
            type: 'POST',
            data: {correoEdicion, identificador, tipo},
            success: function(respuesta){
              console.log(respuesta);

              if (respuesta == "disponible" || respuesta == "mio") {

                $('#validacionCorreoEdicion').attr({class: 'text-info text-center'}).text("¡Correcto!");
                    
                    if ($("#foto")[0].files[0]) {

                      var fileName = $("#foto")[0].files[0].name;
                      var fileSize = $("#foto")[0].files[0].size;


                      var ext = fileName.split('.').pop();

                      
                      if(ext == 'jpg' || ext == 'jpeg' || ext == 'png'){
                        if (fileSize < 3000000) {
                          $.ajax({
                      
                            url: 'server/editar_alumno.php',
                            type: 'POST',
                            data: new FormData(editarAlumnoFormulario),
                            processData: false,
                            contentType: false,
                            cache: false,
                            success: function(respuesta){
                            console.log(respuesta);

                   
                                swal("Editado correctamente", "Continuar", "success", {button: "Aceptar",}).
                                then((value) => {
                                  //window.location.reload();
                                  obtenerAnnios();

                                });
                              
                            }
                          });
                        }else{
                          swal ( "¡Imagen inválida!" ,  "¡Te recordamos que el peso no debe exceder los 3MB!" ,  "error" )
                        }
                        
                      }else{
                        swal ( "¡Imagen inválida!" ,  "¡Te recordamos que los formatos aceptados son jpeg, jpg o png!" ,  "error" )
                      }

                    }else{
                      $.ajax({
                      
                        url: 'server/editar_alumno.php',
                        type: 'POST',
                        data: new FormData(editarAlumnoFormulario), 
                        processData: false,
                        contentType: false,
                        cache: false,
                        success: function(respuesta){
                          console.log(respuesta);

                            swal("Editado correctamente", "Continuar", "success", {button: "Aceptar",}).
                            then((value) => {
                              //window.location.reload();
                              obtenerAnnios();
                              //console.log("exito sin foto");
                            });
                            
                          
                        }
                      });

                    }            

                
              }else{

                $('#validacionCorreoEdicion').attr({class: 'text-danger text-center'}).text("¡Datos Incorrectos!");

              }
            }
          });   
        });
      }
    });
  });

  //FIN EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE ALUMNO

 
  
</script>




<script>
  



  //ACCIONES SELECCION MULTIPLE ALUMNOS


  //SELECCION
  $("#seleccionTotal").on('click', function() {
    //event.preventDefault();
    /* Act on the event */

    //console.log( $(this)[0].checked );

    if ( $(this)[0].checked == true ) {
      // console.log("checkeado");
      $('.seleccionAlumno').prop({checked: true});
      contadorSeleccion();
    }else{ 
      $('.seleccionAlumno').prop({checked: false});
      contadorSeleccion();
    }

    //$('.seleccionAlumno').prop({checked: false});
  });





  // PREVIEW SELECCION
  $(".seleccionAlumno").on('click', function() {
    /* Act on the event */
    
    contadorSeleccion();
    
  });

  contadorSeleccion();

  function contadorSeleccion() {

    var contador = 0;
    for( var i = 0 ; i < $(".seleccionAlumno").length ; i++ ){

      if ( $(".seleccionAlumno")[i].checked == true ) {

        contador++;
       
      }

    }

    if ( contador > 0 ) {
      
      $("#btn_seleccion_alumnos").css({
        display: ''
      }).addClass('animated fadeIn');
      
      $("#badge_seleccion_alumnos").text( contador );
    
    } else {
     
      $("#btn_seleccion_alumnos").css({
        display: 'none'
      }).removeClass('animated fadeIn');
    
    }

  }

  $("#btn_seleccion_alumnos").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */

    for( var i = 0, contador = 0, contador2 = 0; i < $(".seleccionAlumno").length ; i++ ){

      if ( $(".seleccionAlumno")[i].checked == true ) {

        //$("#panzaModalInscripcion").append('<br>').append( $(".seleccionAlumno").eq(i).attr("id_alu_ram") );
        toastr.info( $(".seleccionAlumno").eq(i).attr("nombre_alumno") );
        contador++;
       
      }

    }

  });

  // FIN PREVIEW SELECCION


  // INSCRIPCION

  $("#btn_inscripcion").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */
    
    // VARIABLES
    //ARREGLO DE ALUMNOS POR INSCRIBIR
    var alumnosInscripcion = [];
    var alumnosValidador = [];
    var alumnosValidadorAdeudo = [];
    var alumnosDeudores = [];
    var alumnosNombresInscripcion = [];

    
    for( var i = 0, contador = 0, contador2 = 0; i < $(".seleccionAlumno").length ; i++ ){

      if ( $(".seleccionAlumno")[i].checked == true ) {

        //$("#panzaModalInscripcion").append('<br>').append( $(".seleccionAlumno").eq(i).attr("id_alu_ram") );
        alumnosInscripcion[contador] = $(".seleccionAlumno").eq(i).attr("id_alu_ram");
        alumnosValidador[contador] = $(".seleccionAlumno").eq(i).attr("id_ram");
        alumnosValidadorAdeudo[contador] = $(".seleccionAlumno").eq(i).attr("estatus_pago");
        alumnosNombresInscripcion[contador] = $(".seleccionAlumno").eq(i).attr("nombre_alumno");

        contador++;
       
      }

    }





    if ( alumnosInscripcion.length == 0 ) {
      swal("¡No hay alumnos seleccionados!", "Selecciona al menos un alumno para continuar", "info", {button: "Aceptar",});
    }else{

      var validador = true;
      for ( var i = 0 ; i < alumnosValidador.length ; i++ ) {
        for ( var j = 0 ; j < alumnosValidador.length ; j++ ) {

          if ( alumnosValidador[j] != alumnosValidador[i] ) {
                // console.log( alumnosValidador[j] != alumnosValidador[i] );
            validador = false;
            break;
            break;
            break;
          }
        }
      }


      var validadorAdeudo = true;
      for ( var i = 0, j = 0 ; i < alumnosValidadorAdeudo.length ; i++ ) {
        
        if ( ( alumnosValidadorAdeudo[i] == 'Con adeudo' )  && ( validadorAdeudo == true ) ) {
          // console.log( alumnosValidador[j] != alumnosValidador[i] );
          validadorAdeudo = false; 
        }

        if ( alumnosValidadorAdeudo[i] == 'Con adeudo' ) {
          alumnosDeudores[j] = alumnosNombresInscripcion[i];
          j++;
        }
      }

      if ( validador == true ) {


        if ( validadorAdeudo == true ) {

          var id_ram = alumnosValidador[0];
          var validadorGlobal = 1;

          $("#modalInscripcion").modal('show');

          $("#panzaModalInscripcion").html( '<h3 class="text-center grey-text"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>' );

          $.ajax({
            url: 'server/obtener_inscripcion_alumnos.php',
            type: 'POST',
            data: { alumnosInscripcion, id_ram, validadorGlobal },
            success: function( respuesta ) {
              
              $("#panzaModalInscripcion").html(respuesta);
            }
          });

        } else if ( validadorAdeudo == false ) {

          for ( var i = 0 ; i < alumnosDeudores.length ; i++ ) {
            toastr.warning(alumnosDeudores[i]+' presenta adeudo, NO se puede inscribir');
          }
          swal("¡Error en selección de alumnos!", "Para continuar, asegúrate de que los alumnos no adeuden pagos", "info", {button: "Aceptar",});
        }
        


      } else if ( validador == false ) {
        swal("¡Error en selección de alumnos!", "Para continuar, asegúrate de que los alumnos pertenezcan al mismo programa", "info", {button: "Aceptar",});  
      }



      
      
    }

    

  });


  // BAJA
  $("#btn_baja").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */

    // VARIABLES
    //ARREGLO DE ALUMNOS POR INSCRIBIR
    var alumnosInscripcion = [];

    for(var i = 0, contador = 0; i < $(".seleccionAlumno").length; i++){

      if ( $(".seleccionAlumno")[i].checked == true ) {
        //$("#panzaModalInscripcion").append('<br>').append( $(".seleccionAlumno").eq(i).attr("id_alu_ram") );
        alumnosInscripcion[contador] = $(".seleccionAlumno").eq(i).attr("id_alu_ram");
        contador++;
      }

    }

    if ( alumnosInscripcion.length == 0 ) {
      swal("¡No hay alumnos seleccionados!", "Selecciona al menos un alumno para continuar", "info", {button: "Aceptar",});
    }else{

      $("#modalBaja").modal('show');
      var validadorGlobal = 1;

      $.ajax({
        url: 'server/obtener_alumnos_baja_materias.php',
        type: 'POST',
        data: {alumnosInscripcion, validadorGlobal},
        success: function(respuesta){
          $("#panzaModalBaja").html(respuesta);
        }
      });
      
    }
  });

 
</script>


<script>
  //CONSULTA DE HORARIO ALUMNO

  $(".horarioAlumno").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */

    var edicionAlumno = $(this).attr("id_alu");
    var rama = $(this).attr("id_ram");
    var id_alu_ram = $(this).attr("id_alu_ram");

    $.ajax({
      url: 'server/obtener_alumno.php',
      type: 'POST',
      dataType: 'json',
      data: {edicionAlumno, rama},
      success: function(datos){

        $("#modalHorarioAlumno").modal('show');
        $('#tituloModalHorarioAlumno').html('<img src="../uploads/'+datos.fot_alu+'" class="img-fluid avatar rounded-circle" width="40px" height="40px"> '+"Horario de "+datos.nom_alu+" "+datos.app_alu);

        $.ajax({
          url: 'server/obtener_horario_alumno.php',
          type: 'POST',
          data: {id_alu_ram},
          success: function(respuesta){
            $("#panzaModalHorarioAlumno").html(respuesta);
          }
        });
        
      }
    });

  });
</script>

<!-- OBTENER DOCUMENTACION PENDIENTE DE ALUMNO -->
<script>
  $(".obtenerDocumentosPendientes").on('click', function(event) {
    event.preventDefault();
    
    var id_alu_ram = $(this).attr('id_alu_ram');
    
    var btn_documentos = $(this);

    
    $.ajax({
      url: 'server/obtener_documentacion_alumno.php',
      type: 'POST',
      data: {id_alu_ram},
      success: function(respuesta){
        $("#modalDocumentacionAlumno").modal('show');
        $("#contenedorDocumentacionAlumno").html(respuesta);

        // CHECKBOX DE DDOCUMENTACION
        $(".checkboxDocumentacion").on('click', function(event){
            //event.preventDefault();

            var elemento = $( this );
            
            for ( var i = 0, validador = true ; i < $('.checkboxDocumentacion').length ; i++ ) {

                if ( $('.checkboxDocumentacion')[i].checked == false ) {

                    validador = false;
                
                }
            }

            if ( validador == true ) {
              btn_documentos.removeClass('grey darken-1').addClass('success-color').text('Entregados');
            } else { 
              btn_documentos.removeClass('success-color').addClass('grey darken-1').text('Pendiente');
            }

            // alert(validador);
            
            var id_doc_alu_ram = $(this).attr("id_doc_alu_ram");
            var est_doc_alu_ram = $(this).val();
            var nom_doc_ram = $(this).attr("nom_doc_ram");

            $.ajax({
                url: 'server/editar_estatus_documentacion.php',
                type: 'POST',
                data: {id_doc_alu_ram, est_doc_alu_ram},
                success: function(respuesta){

                    console.log( respuesta );
                    if ( est_doc_alu_ram == 'Pendiente' ) {

                        elemento.val( 'Entregado' );
                        toastr.warning('Se ha removido: '+nom_doc_ram);

                    } else if ( est_doc_alu_ram == 'Entregado' ) {
                        elemento.val( 'Pendiente' );
                        toastr.success('Se ha entregado: '+nom_doc_ram);

                    }
                    
                    
                }

            });

        });
        // FIN CHECKBOX DOCUMENTACION
        
      }

    });
    




  });

</script>


<script>
  // MOTIVO ALUMNO PENDIENTE
  $(".alumnoPendienteMotivo").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */

    var id_alu_ram = $(this).attr("id_alu_ram");
    $.ajax({
      url: 'server/obtener_motivo_alumno.php',
      type: 'POST',
      data: { id_alu_ram },
      success: function( respuesta ){
        $("#modalMotivoAlumno").modal( 'show' );
        $("#contenedorMotivoAlumno").html( respuesta );
      }
    });
    

    
  });
  // FIN MOTIVO ALUMNO Pendiente
</script>


<script>
  // MODAL ALUMNO
  $(".consultaAlumno").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */

    var id_alu_ram = $(this).attr("id_alu_ram");
    $.ajax({
      url: 'server/obtener_consulta_alumno.php',
      type: 'POST',
      data: { id_alu_ram },
      success: function( respuesta ){
        $("#modalConsultaAlumno").modal( 'show' );
        $("#contenedorConsultaAlumno").html( respuesta );
      }
    });
    
  });
  // FIN MODAL ALUMNO
</script>

<script>
  // ACTIVIDADES ALUMNO
  $(".actividadesAlumno").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */

    var id_alu_ram = $(this).attr("id_alu_ram");
    
    $.ajax({
      url: 'server/obtener_actividades_alumno.php',
      type: 'POST',
      data: { id_alu_ram },
      success: function( respuesta ){
        $("#modalActividadesAlumno").modal( 'show' );
        $("#contenedorActividadesAlumno").html( respuesta );
      }
    });
    
  });

</script>


<script>

  $(".programas").on('click', function() {
    // event.preventDefault();
    /* Act on the event */

    obtener_generaciones();

    
  });

  function obtener_generaciones(){
    var ramas = [];
    for(var j = 0; j < $(".programas").length; j++){
      if ($(".programas")[j].checked == true) {
        ramas.push($(".programas").eq(j).val());
      }
    }

    // alert( ramas.length );


    $.ajax({
      url: 'server/obtener_generaciones_programa_formulario.php',
      type: 'POST',
      data: { ramas },
      success: function( respuesta ){
        
        $("#contenedor_generaciones").html( respuesta );
      
      }
    });
  }
</script>

<script>
  new Sortable(example1, {
      animation: 150,
      ghostClass: 'blue-background-class',
      // Element dragging ended
      
      onEnd: function (/**Event*/evt) {
        var itemEl = evt.item;  // dragged HTMLElement
        // generarAlerta('hola');

        for( var i = 0; i < $( '.list-group-item' ).length; i++ ){
          
          $( '.list-group-item' ).eq(i).removeAttr( 'posicion' ).attr( 'posicion', i );
          
          var card = $( '.list-group-item' ).eq(i).attr( 'card' );
          var index = $( '.list-group-item' ).eq(i).attr( 'posicion' );

          $.ajax({
            url: 'server/editar_espacio_filtros.php',
            type: 'POST',
            data: { card, index },
            success: function( respuesta ){
              console.log( respuesta );
            }
          });
          
        }




      }

  });


  ordenarMenu();



  function ordenarMenu(){

      
      var $wrapper = $('#example1');

      $wrapper.find('.list-group-item').sort(function(a, b) {
          return +a.getAttribute('posicion') - +b.getAttribute('posicion');
      }).appendTo($wrapper);


  }

  // ACORDEON ESPACIO DE TRABAJO
  $( '.acordeon' ).on( 'click', function( event ){
    event.preventDefault();
    /* Act on the event */

    var estatus = $( this ).attr( 'aria-expanded' );
    var acordeon = $( this ).attr( 'acordeon' );

    // alert( 'estatus:' + estatus + 'acordeon: ' + acordeon);

    if ( estatus == 'false' ) {
      
      estatus = 'true';
    
    } else if ( estatus == 'true' ) {
    
      estatus = 'false';
    
    }

    // alert( estatus );

    $.ajax({
      url: 'server/editar_acordeon_filtros.php',
      type: 'POST',
      data: { estatus, acordeon },
      success: function( respuesta ){
        console.log( respuesta );
      }
    });


  });
  // FIN  ACORDEON ESPACIO DE TRABAJO


</script>