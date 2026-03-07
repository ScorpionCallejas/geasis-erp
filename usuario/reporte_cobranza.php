<?php  
 
  include('inc/header.php');

?>


<!-- TITULO -->
<div class="row ">
  <div class="col text-left">
      <span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Alumnos">
        <i class="fas fa-bookmark"></i> 
        Cobranza del Plantel
      </span>
      <br>
      <div class=" badge badge-warning animated fadeInUp delay-3s text-white">
        <a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
        <i class="fas fa-angle-double-right"></i>
        <a style="color: black;" href="" title="Estás aquí">Cobranza</a>
      </div>
    </div>
  </div>
  <!-- FIN TITULO -->

  <!-- ALUMNOS -->
  <div class="card">


  <div class="card-body">

    <!-- FILTROS CABECERAS -->
    <div class="row" id="example1">


      <!-- ANNIOS -->
      <div class="col-md-3 list-group-item " posicion="<?php echo ( obtenerIndiceCard( 'car_fec_fil', $tipoUsuario, $id ) != 'false' ) ? obtenerIndiceCard( 'car_fec_fil', $tipoUsuario, $id ) : 0; ?>" card="car_fec_fil">

        <!--Accordion wrapper-->
        <div class="accordion md-accordion" id="accordionEx1" role="tablist" aria-multiselectable="true">

            <div class="card">

              <!-- Card header -->
              <div class="card-header grey darken-1 text-center" role="tab" id="headingTwo1">
                
                  <a data-toggle="collapse" data-parent="#accordionEx1" href="#collapseTwo1"
                    aria-controls="collapseTwo1" acordeon="aco_fec_fil" 
                    <?php  
                      if ( obtenerEstatusAcordeon( 'aco_fec_fil', $tipoUsuario, $id ) != 'falso' ) {
                      // HAY REGISTRO PREVIO
                    ?>
                        aria-expanded="<?php echo obtenerEstatusAcordeon( 'aco_fec_fil', $tipoUsuario, $id); ?>"
                    <?php  
                      if ( obtenerEstatusAcordeon( 'aco_fec_fil', $tipoUsuario, $id ) == 'true' ) {
                    ?>
                        class="acordeon"

                    <?php
                      } else if ( obtenerEstatusAcordeon( 'aco_fec_fil', $tipoUsuario, $id ) == 'false' ) {
                    ?>
                        class="acordeon collapsed"

                    <?php
                      }
                    ?>

                    <?php                   
                      } else {
                      // NO HAY REGISTRO PREVIO
                    ?>
                        aria-expanded="true" class="acordeon"

                    <?php
                      }
                    ?>
                  >

                  <h5 class="letraMediana white-text">
                    Registro por año-mes <i class="fas fa-angle-down rotate-icon"></i>
                  </h5>
                </a>
              </div>

              <!-- Card body -->
              <div id="collapseTwo1" role="tabpanel" aria-labelledby="headingTwo1"
                  data-parent="#accordionEx1"   
                  <?php  
                    if ( obtenerEstatusAcordeon( 'aco_fec_fil', $tipoUsuario, $id ) != 'falso' ) {
                    // HAY REGISTRO PREVIO
                  ?>

                  <?php  
                    if ( obtenerEstatusAcordeon( 'aco_fec_fil', $tipoUsuario, $id ) == 'true' ) {
                  ?>
                      class="collapse show"

                  <?php
                    } else if ( obtenerEstatusAcordeon( 'aco_fec_fil', $tipoUsuario, $id ) == 'false' ) {
                  ?>
                      class="collapse"

                  <?php
                    }
                  ?>

                  <?php                   
                    } else {
                    // NO HAY REGISTRO PREVIO
                  ?>
                      class="collapse show"

                  <?php
                    }
                  ?>
                >
            
            <div class="card-body bg-light scrollspy-example" data-spy="scroll" style=" height: 235px; ">
              
              <div class="row">
                <div class="col-md-12">

                        <div class="form-check">
                          <input type="checkbox" class="form-check-input" id="seleccionTodos">
                          <label class="form-check-label letraPequena font-weight-bold" for="seleccionTodos">
                    Todos
                          </label>
                        </div>
                </div>
              </div>

                      
                  <?php  
                    $sqlAnnios = "
                      SELECT *
                      FROM alumno
                      WHERE id_pla8 = '$plantel'
                      GROUP BY MONTH( ing_alu ), YEAR( ing_alu )
                      ORDER BY ing_alu DESC
                    ";

                    //echo $sqlAnnios;

                    $resultadoAnnios = mysqli_query( $db, $sqlAnnios );
                    $resultadoTotalAnnios = mysqli_query( $db, $sqlAnnios );

                    $contadorAnnios = 1;

                    $totalAnnios = mysqli_num_rows( $resultadoTotalAnnios );

                    // echo $totalAnnios;

                          for ( $i = 0 ; $i < $totalAnnios / 1 ; $i++ ) {
                        ?>
                            <div class="row">
                              <?php  
                                while( $filaAnnios = mysqli_fetch_assoc( $resultadoAnnios ) ){
                              ?>
                            <div class="col-md-12">
                              <div class="form-check">
                      
                      <?php

                                  if ( $contadorAnnios ==  1 ) {
                                ?>
                          <input type="checkbox" class="form-check-input checkboxAnnio seleccionAnniosMeses" id="annio<?php echo $contadorAnnios; ?>" annio="<?php echo obtenerAnnioServer( $filaAnnios['ing_alu'] ); ?>" mes="<?php echo obtenerMesServer( $filaAnnios['ing_alu'] ); ?>" checked>
                                <?php
                                  } else {
                                ?>
                          <input type="checkbox" class="form-check-input checkboxAnnio seleccionAnniosMeses" id="annio<?php echo $contadorAnnios; ?>" annio="<?php echo obtenerAnnioServer( $filaAnnios['ing_alu'] ); ?>" mes="<?php echo obtenerMesServer( $filaAnnios['ing_alu'] ); ?>">


                                <?php
                                  }
                                ?>

                      
                      <label class="form-check-label letraPequena font-weight-bold" for="annio<?php echo $contadorAnnios; ?>">

                      <?php echo obtenerAnnioServer( $filaAnnios['ing_alu'] ).' / '.substr( getMonth( obtenerMesServer( $filaAnnios['ing_alu'] ) ), 0, 3 ); ?>

                      </label>
                    </div>
                            </div>
                              <?php
                                  $contadorAnnios++;
                                }
                              ?>
                              
                            </div>

                  <?php
                          }
                        // FIN for
                        ?>

              
            </div>
            
            </div>
            <!-- <div class="card-header grey darken-1 white-text text-center letraMediana">
              Programas Académicos
            </div> -->
              
            
      
          </div>
      
      </div>
      <!-- FIN Accordion wrapper-->
        
      </div>

      <!-- FIN ANNIOS -->


      <!-- PROGRAMAS -->
      <div class="col-md-3 list-group-item " posicion="<?php echo ( obtenerIndiceCard( 'car_ram_fil', $tipoUsuario, $id ) != 'false' ) ? obtenerIndiceCard( 'car_ram_fil', $tipoUsuario, $id ) : 1; ?>" card="car_ram_fil">
        
        <!--Accordion wrapper-->
        <div class="accordion md-accordion" id="accordionEx2" role="tablist" aria-multiselectable="true">

            <div class="card">

              <!-- Card header -->
              <div class="card-header grey darken-1 text-center " role="tab" id="headingTwo2">
                <a data-toggle="collapse" data-parent="#accordionEx2" href="#collapseTwo2" aria-controls="collapseTwo2"
                acordeon="aco_ram_fil" 
                    <?php  
                      if ( obtenerEstatusAcordeon( 'aco_ram_fil', $tipoUsuario, $id ) != 'falso' ) {
                      // HAY REGISTRO PREVIO
                    ?>
                        aria-expanded="<?php echo obtenerEstatusAcordeon( 'aco_ram_fil', $tipoUsuario, $id); ?>"
                    <?php  
                      if ( obtenerEstatusAcordeon( 'aco_ram_fil', $tipoUsuario, $id ) == 'true' ) {
                    ?>
                        class="acordeon"

                    <?php
                      } else if ( obtenerEstatusAcordeon( 'aco_ram_fil', $tipoUsuario, $id ) == 'false' ) {
                    ?>
                        class="acordeon collapsed"

                    <?php
                      }
                    ?>

                    <?php                   
                      } else {
                      // NO HAY REGISTRO PREVIO
                    ?>
                        aria-expanded="true" class="acordeon"

                    <?php
                      }
                    ?>
                  >

                  <h5 class="letraMediana white-text">
                    Programas Académicos <i class="fas fa-angle-down rotate-icon"></i>
                  </h5>
                </a>
              </div>

              <!-- Card body -->
              <div id="collapseTwo2" role="tabpanel" aria-labelledby="headingTwo2"
                  data-parent="#accordionEx2"     
                  <?php  
                    if ( obtenerEstatusAcordeon( 'aco_ram_fil', $tipoUsuario, $id ) != 'falso' ) {
                    // HAY REGISTRO PREVIO
                  ?>

                  <?php  
                    if ( obtenerEstatusAcordeon( 'aco_ram_fil', $tipoUsuario, $id ) == 'true' ) {
                  ?>
                      class="collapse show"

                  <?php
                    } else if ( obtenerEstatusAcordeon( 'aco_ram_fil', $tipoUsuario, $id ) == 'false' ) {
                  ?>
                      class="collapse"

                  <?php
                    }
                  ?>

                  <?php                   
                    } else {
                    // NO HAY REGISTRO PREVIO
                  ?>
                      class="collapse show"

                  <?php
                    }
                  ?>
                >
            
            <div class="card-body bg-light scrollspy-example" data-spy="scroll" style=" height: 235px; ">

              <?php
                    $sqlProgramas = "
                      SELECT *
                      FROM rama
                      WHERE id_pla1 = '$plantel'
                      ORDER BY id_ram ASC
                    ";

                    $resultadoProgramas = mysqli_query( $db, $sqlProgramas );
                    $resultadoTotalProgramas = mysqli_query( $db, $sqlProgramas );

                    $contadorProgramas = 1;

                    $totalProgramas = mysqli_num_rows( $resultadoTotalProgramas );

                          for ( $i = 0 ; $i < $totalProgramas / 1 ; $i++ ) {
                        ?>
                            <div class="row">
                              <?php  
                                while( $filaProgramas = mysqli_fetch_assoc( $resultadoProgramas ) ){
                              ?>
                            <div class="col-md-12">
                              <div class="form-check">
                                        <input type="checkbox" class="form-check-input checador5" id="programa<?php echo $contadorProgramas; ?>" value="<?php echo $filaProgramas['nom_ram']; ?>" columna="7">
                                        <label class="form-check-label letraPequena font-weight-bold" for="programa<?php echo $contadorProgramas; ?>">
                                      
                                          <?php echo $filaProgramas['nom_ram']; ?>

                                        </label>
                                      </div>
                            </div>
                              <?php
                                  $contadorProgramas++;
                                }
                              ?>
                              
                            </div>

                    <?php
                          }
                        // FIN for
                      ?>


            </div>
            
            </div>
            <!-- <div class="card-header grey darken-1 white-text text-center letraMediana">
              Programas Académicos
            </div> -->
              
            
      
          </div>
      
      </div>
      <!-- FIN Accordion wrapper-->

      </div>
      <!-- FIN PROGRAMAS -->

      <!--GENERACIONES -->
      <div class="col-md-3 list-group-item " posicion="<?php echo ( obtenerIndiceCard( 'car_gen_fil', $tipoUsuario, $id ) != 'false' ) ? obtenerIndiceCard( 'car_gen_fil', $tipoUsuario, $id ) : 2; ?>" card="car_gen_fil">
        <!--Accordion wrapper-->
        <div class="accordion md-accordion" id="accordionEx3" role="tablist" aria-multiselectable="true">

            <div class="card">

              <!-- Card header -->
              <div class="card-header grey darken-1 text-center" role="tab" id="headingTwo3">
                  <a data-toggle="collapse" data-parent="#accordionEx3" href="#collapseTwo3" aria-controls="collapseTwo3  " acordeon="aco_gen_fil" 
                    <?php  
                      if ( obtenerEstatusAcordeon( 'aco_gen_fil', $tipoUsuario, $id ) != 'falso' ) {
                      // HAY REGISTRO PREVIO
                    ?>
                        aria-expanded="<?php echo obtenerEstatusAcordeon( 'aco_gen_fil', $tipoUsuario, $id); ?>"
                    <?php  
                      if ( obtenerEstatusAcordeon( 'aco_gen_fil', $tipoUsuario, $id ) == 'true' ) {
                    ?>
                        class="acordeon"

                    <?php
                      } else if ( obtenerEstatusAcordeon( 'aco_gen_fil', $tipoUsuario, $id ) == 'false' ) {
                    ?>
                        class="acordeon collapsed"

                    <?php
                      }
                    ?>

                    <?php                   
                      } else {
                      // NO HAY REGISTRO PREVIO
                    ?>
                        aria-expanded="true" class="acordeon"

                    <?php
                      }
                    ?>
                  >

                  <h5 class="letraMediana white-text">
                    Generaciones Académicas <i class="fas fa-angle-down rotate-icon"></i>
                  </h5>
                </a>
              </div>

              <!-- Card body -->
              <div id="collapseTwo3" role="tabpanel" aria-labelledby="headingTwo3"
                  data-parent="#accordionEx3"     
                  <?php  
                    if ( obtenerEstatusAcordeon( 'aco_gen_fil', $tipoUsuario, $id ) != 'falso' ) {
                    // HAY REGISTRO PREVIO
                  ?>

                  <?php  
                    if ( obtenerEstatusAcordeon( 'aco_gen_fil', $tipoUsuario, $id ) == 'true' ) {
                  ?>
                      class="collapse show"

                  <?php
                    } else if ( obtenerEstatusAcordeon( 'aco_gen_fil', $tipoUsuario, $id ) == 'false' ) {
                  ?>
                      class="collapse"

                  <?php
                    }
                  ?>

                  <?php                   
                    } else {
                    // NO HAY REGISTRO PREVIO
                  ?>
                      class="collapse show"

                  <?php
                    }
                  ?>
                >
              
              <div class="card-body bg-light scrollspy-example" data-spy="scroll" style=" height: 235px; ">
                
                <?php  
                      $sqlGeneraciones = "
                        SELECT *
                        FROM generacion
                        INNER JOIN rama ON rama.id_ram = generacion.id_ram5
                        WHERE id_pla1 = '$plantel'
                        ORDER BY id_ram DESC
                      ";

                      $resultadoGeneraciones = mysqli_query( $db, $sqlGeneraciones );
                      $resultadoTotalGeneraciones = mysqli_query( $db, $sqlGeneraciones );

                      $contadorGeneraciones = 1;

                      $totalGeneraciones = mysqli_num_rows( $resultadoTotalGeneraciones );

                            for ( $i = 0 ; $i < $totalGeneraciones / 1 ; $i++ ) {
                          ?>
                              <div class="row">
                                <?php  
                                  while( $filaGeneraciones = mysqli_fetch_assoc( $resultadoGeneraciones ) ){
                                ?>
                              <div class="col-md-12">
                                <div class="form-check">
                                      <input type="checkbox" class="form-check-input checador6" id="generacion<?php echo $contadorGeneraciones; ?>" value="<?php echo $filaGeneraciones['nom_gen']; ?>" columna="8">
                                      <label class="form-check-label letraPequena font-weight-bold" for="generacion<?php echo $contadorGeneraciones; ?>">
                                    
                                        <?php echo $filaGeneraciones['nom_gen']; ?>

                                      </label>
                                    </div>
                              </div>
                                <?php
                                    $contadorGeneraciones++;
                                  }
                                ?>
                                
                              </div>

                      <?php
                            }
                          // FIN for
                        ?>

                
              </div>
              
              </div>
              <!-- <div class="card-header grey darken-1 white-text text-center letraMediana">
                Programas Académicos
              </div> -->
                
              
        
            </div>
        
        </div>
        <!-- FIN Accordion wrapper-->
      </div>
      <!-- FIN GENERACIONES -->



      <!--CICLOS -->
      <div class="col-md-3 list-group-item " posicion="<?php echo ( obtenerIndiceCard( 'car_cic_fil', $tipoUsuario, $id ) != 'false' ) ? obtenerIndiceCard( 'car_cic_fil', $tipoUsuario, $id ) : 3; ?>" card="car_cic_fil">
          <!--Accordion wrapper-->
      <div class="accordion md-accordion" id="accordionEx4" role="tablist" aria-multiselectable="true">

          <div class="card">

            <!-- Card header -->
            <div class="card-header grey darken-1 text-center" role="tab" id="headingTwo4">
                <a data-toggle="collapse" data-parent="#accordionEx4" href="#collapseTwo4"
                  aria-controls="collapseTwo4" acordeon="aco_cic_fil" 
                  <?php  
                    if ( obtenerEstatusAcordeon( 'aco_cic_fil', $tipoUsuario, $id ) != 'falso' ) {
                    // HAY REGISTRO PREVIO
                  ?>
                      aria-expanded="<?php echo obtenerEstatusAcordeon( 'aco_cic_fil', $tipoUsuario, $id ); ?>"
                  <?php  
                    if ( obtenerEstatusAcordeon( 'aco_cic_fil', $tipoUsuario, $id ) == 'true' ) {
                  ?>
                      class="acordeon"

                  <?php
                    } else if ( obtenerEstatusAcordeon( 'aco_cic_fil', $tipoUsuario, $id ) == 'false' ) {
                  ?>
                      class="acordeon collapsed"

                  <?php
                    }
                  ?>

                  <?php                   
                    } else {
                    // NO HAY REGISTRO PREVIO
                  ?>
                      aria-expanded="true" class="acordeon"

                  <?php
                    }
                  ?>
                >
                  <h5 class="letraMediana white-text">
                    Ciclos Escolares <i class="fas fa-angle-down rotate-icon"></i>
                  </h5>
                </a>
            </div>

            <!-- Card body -->
            <div id="collapseTwo4" role="tabpanel" aria-labelledby="headingTwo4"
                data-parent="#accordionEx4"       
                <?php  
                  if ( obtenerEstatusAcordeon( 'aco_cic_fil', $tipoUsuario, $id ) != 'falso' ) {
                  // HAY REGISTRO PREVIO
                ?>

                <?php  
                  if ( obtenerEstatusAcordeon( 'aco_cic_fil', $tipoUsuario, $id ) == 'true' ) {
                ?>
                    class="collapse show"

                <?php
                  } else if ( obtenerEstatusAcordeon( 'aco_cic_fil', $tipoUsuario, $id ) == 'false' ) {
                ?>
                    class="collapse"

                <?php
                  }
                ?>

                <?php                   
                  } else {
                  // NO HAY REGISTRO PREVIO
                ?>
                    class="collapse show"

                <?php
                  }
                ?>
              >
              
              <div class="card-body bg-light scrollspy-example" data-spy="scroll" style=" height: 235px; ">
                
                <?php  
                          $sqlCiclos = "
                            SELECT *
                            FROM ciclo
                            INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
                            WHERE id_pla1 = '$plantel'
                            ORDER BY id_ram DESC
                          ";

                          $resultadoCiclos = mysqli_query( $db, $sqlCiclos );
                          $resultadoTotalCiclos = mysqli_query( $db, $sqlCiclos );

                          $contadorCiclos = 1;

                          $totalCiclos = mysqli_num_rows( $resultadoTotalCiclos );

                                for ( $i = 0 ; $i < $totalCiclos / 1 ; $i++ ) {
                              ?>
                                  <div class="row">
                                    <?php  
                                      while( $filaCiclos = mysqli_fetch_assoc( $resultadoCiclos ) ){
                                    ?>
                                  <div class="col-md-12">
                                    <div class="form-check">
                                          <input type="checkbox" class="form-check-input checador7" id="ciclo<?php echo $contadorCiclos; ?>" value="<?php echo $filaCiclos['nom_cic']; ?>" columna="8">
                                          <label class="form-check-label letraPequena font-weight-bold" for="ciclo<?php echo $contadorCiclos; ?>">
                                        
                                            <?php echo $filaCiclos['nom_cic']; ?>

                                          </label>
                                        </div>
                                  </div>
                                    <?php
                                        $contadorCiclos++;
                                      }
                                    ?>
                                    
                                  </div>

                          <?php
                                }
                              // FIN for
                            ?>

                
              </div>
              
              </div>
              <!-- <div class="card-header grey darken-1 white-text text-center letraMediana">
                Programas Académicos
              </div> -->
                
              
        
            </div>
        
        </div>
        <!-- FIN Accordion wrapper-->

          </div>
          <!-- FIN CICLOS -->


          <!-- ROW 2 -->
      <!-- INDICADORES -->
      <div class="col-md-3 list-group-item  text-center" posicion="<?php echo ( obtenerIndiceCard( 'car_dat_fil', $tipoUsuario, $id ) != 'false' ) ? obtenerIndiceCard( 'car_dat_fil', $tipoUsuario, $id ) : 4; ?>" card="car_dat_fil">
        
      <!--Accordion wrapper-->
      <div class="accordion md-accordion" id="accordionEx5" role="tablist" aria-multiselectable="true">

          <div class="card">

            <!-- Card header -->
            <div class="card-header grey darken-1 text-center" role="tab" id="headingTwo5">
                <a data-toggle="collapse" data-parent="#accordionEx5" href="#collapseTwo5" aria-controls="collapseTwo5"
                  acordeon="aco_dat_fil" 
                  <?php  
                    if ( obtenerEstatusAcordeon( 'aco_dat_fil', $tipoUsuario, $id ) != 'falso' ) {
                    // HAY REGISTRO PREVIO
                  ?>
                      aria-expanded="<?php echo obtenerEstatusAcordeon( 'aco_dat_fil', $tipoUsuario, $id ); ?>"
                  <?php  
                    if ( obtenerEstatusAcordeon( 'aco_dat_fil', $tipoUsuario, $id ) == 'true' ) {
                  ?>
                      class="acordeon"

                  <?php
                    } else if ( obtenerEstatusAcordeon( 'aco_dat_fil', $tipoUsuario, $id ) == 'false' ) {
                  ?>
                      class="acordeon collapsed"

                  <?php
                    }
                  ?>

                  <?php                   
                    } else {
                    // NO HAY REGISTRO PREVIO
                  ?>
                      aria-expanded="true" class="acordeon"

                  <?php
                    }
                  ?>
                >
                  <h5 class="letraMediana white-text">
                    Dashboard Alumnos <i class="fas fa-angle-down rotate-icon"></i>
                  </h5>
                </a>
            </div>

            <!-- Card body -->
            <div id="collapseTwo5" role="tabpanel" aria-labelledby="headingTwo5"
                data-parent="#accordionEx5"       
                <?php  
                  if ( obtenerEstatusAcordeon( 'aco_dat_fil', $tipoUsuario, $id ) != 'falso' ) {
                  // HAY REGISTRO PREVIO
                ?>

                <?php  
                  if ( obtenerEstatusAcordeon( 'aco_dat_fil', $tipoUsuario, $id ) == 'true' ) {
                ?>
                    class="collapse show"

                <?php
                  } else if ( obtenerEstatusAcordeon( 'aco_dat_fil', $tipoUsuario, $id ) == 'false' ) {
                ?>
                    class="collapse"

                <?php
                  }
                ?>

                <?php                   
                  } else {
                  // NO HAY REGISTRO PREVIO
                ?>
                    class="collapse show"

                <?php
                  }
                ?>
              >
        
        <div class="card-body bg-light" style=" height: 235px; ">
          
          <div class="row">
  
                <div class="col-md-6">
                  <label class="form-check-label letraPequena font-weight-bold" style="line-height: 100%;">
                    Alumnos totales 
                  </label>
                  <h5>
                    <span class="badge badge-info" id="alumnosTotales"></span>
                  </h5>
                </div>

                <div class="col-md-6">
                  <label class="form-check-label letraPequena font-weight-bold" style="line-height: 100%;">
                    Alumnos activos 
                  </label>
                  <h5>
                    <span class="badge badge-info" id="alumnosActivos"></span>
                  </h5>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <label class="form-check-label letraPequena font-weight-bold" style="line-height: 100%;">
                    Alumnos egresados 
                  </label>
                  <h5>
                    <span class="badge badge-info" id="alumnosEgresados"></span>
                  </h5>
                </div>

                <div class="col-md-6">
                  <label class="form-check-label letraPequena font-weight-bold" style="line-height: 100%;">
                    Alumnos inactivos
                  </label>
                  <h5>
                    <span class="badge badge-info" id="alumnosInactivos"></span>
                  </h5>
                </div>

              </div>

              <div class="row">
                <div class="col-md-6">
                  <label class="form-check-label letraPequena font-weight-bold" style="line-height: 100%;">
                    Alumnos sin adeudos 
                  </label>
                  <h5>
                    <span class="badge badge-info" id="alumnosSinAdeudos"></span>
                  </h5>
                </div>

                <div class="col-md-6">
                  <label class="form-check-label letraPequena font-weight-bold" style="line-height: 100%;">
                    Alumnos con adeudos
                  </label>
                  <h5>
                    <span class="badge badge-info" id="alumnosConAdeudos"></span>
                  </h5>
                </div>

              </div>
          
        </div>
        
        </div>
        <!-- <div class="card-header grey darken-1 white-text text-center letraMediana">
          Programas Académicos
        </div> -->
          
        
  
      </div>
  
  </div>
  <!-- FIN Accordion wrapper-->
  </div>
  <!-- FIN INDICADORES -->
 
  <!-- BOTONES -->
  <div class="col-md-3 list-group-item " posicion="<?php echo ( obtenerIndiceCard( 'car_sel_fil', $tipoUsuario, $id ) != 'false' ) ? obtenerIndiceCard( 'car_sel_fil', $tipoUsuario, $id ) : 5; ?>" card="car_sel_fil">
    <!--Accordion wrapper-->
    <div class="accordion md-accordion" id="accordionEx6" role="tablist" aria-multiselectable="true">

      <div class="card">

        <!-- Card header -->
        <div class="card-header grey darken-1 text-center" role="tab" id="headingTwo6">
            <a data-toggle="collapse" data-parent="#accordionEx6" href="#collapseTwo6"
              aria-controls="collapseTwo6" acordeon="aco_sel_fil" 
              <?php  
                if ( obtenerEstatusAcordeon( 'aco_sel_fil', $tipoUsuario, $id ) != 'falso' ) {
                // HAY REGISTRO PREVIO
              ?>
                  aria-expanded="<?php echo obtenerEstatusAcordeon( 'aco_sel_fil', $tipoUsuario, $id ); ?>"
              <?php  
                if ( obtenerEstatusAcordeon( 'aco_sel_fil', $tipoUsuario, $id ) == 'true' ) {
              ?>
                  class="acordeon"

              <?php
                } else if ( obtenerEstatusAcordeon( 'aco_sel_fil', $tipoUsuario, $id ) == 'false' ) {
              ?>
                  class="acordeon collapsed"

              <?php
                }
              ?>

              <?php                   
                } else {
                // NO HAY REGISTRO PREVIO
              ?>
                  aria-expanded="true" class="acordeon"

              <?php
                }
              ?>
            >
              <h5 class="letraMediana white-text">
                Dashboard Cobranza <i class="fas fa-angle-down rotate-icon"></i>
              </h5>
            </a>
        </div>

        <!-- Card body -->
        <div id="collapseTwo6" role="tabpanel" aria-labelledby="headingTwo6"
            data-parent="#accordionEx6"
            <?php  
              if ( obtenerEstatusAcordeon( 'aco_sel_fil', $tipoUsuario, $id ) != 'falso' ) {
              // HAY REGISTRO PREVIO
            ?>

            <?php  
              if ( obtenerEstatusAcordeon( 'aco_sel_fil', $tipoUsuario, $id ) == 'true' ) {
            ?>
                class="collapse show"

            <?php
              } else if ( obtenerEstatusAcordeon( 'aco_sel_fil', $tipoUsuario, $id ) == 'false' ) {
            ?>
                class="collapse"

            <?php
              }
            ?>

            <?php                   
              } else {
              // NO HAY REGISTRO PREVIO
            ?>
                class="collapse show"

            <?php
              }
            ?>
          > 
        
        <div class="card-body bg-light" style=" height: 235px; ">
          
              <div class="row">
        
                <div class="col-md-6 text-center">
                  <label class="form-check-label letraPequena font-weight-bold" style="line-height: 100%;">
                    Abonado 
                  </label>
                  <h5>
                    <span class="badge badge-info" id="saldoAbonado"></span>
                  </h5>
                </div>

                <div class="col-md-6 text-center">
                  <label class="form-check-label letraPequena font-weight-bold" style="line-height: 100%;">
                    Saldo 
                  </label>
                  <h5>
                    <span class="badge badge-info" id="saldoVencido"></span>
                  </h5>
                </div>
              </div>


              <div class="row">
                <div class="col-md-6 text-center">
                  <label class="form-check-label letraPequena font-weight-bold" style="line-height: 100%;">
                    Total cobros pagados 
                  </label>
                  <h5>
                    <span class="badge badge-info" id="totalPagados"></span>
                  </h5>
                </div>

                <div class="col-md-6 text-center">
                  <label class="form-check-label letraPequena font-weight-bold" style="line-height: 100%;">
                    Total cobros vencidos
                  </label>
                  <h5>
                    <span class="badge badge-info" id="totalVencidos"></span>
                  </h5>
                </div>

              </div>

              <div class="row">
                <div class="col-md-6 text-center">
                  <label class="form-check-label letraPequena font-weight-bold" style="line-height: 100%;">
                    Total condonaciones 
                  </label>
                  <h5>
                    <span class="badge badge-info" id="totalCondonaciones"></span>
                  </h5>
                </div>

                <div class="col-md-6 text-center">
                  <label class="form-check-label letraPequena font-weight-bold" style="line-height: 100%;">
                    Total convenios de fecha
                  </label>
                  <h5>
                    <span class="badge badge-info" id="totalConvenios"></span>
                  </h5>
                </div>

              </div>
          
        </div>
        
        </div>
        <!-- <div class="card-header grey darken-1 white-text text-center letraMediana">
          Programas Académicos
        </div> -->
  
      </div>
  
  </div>
  <!-- FIN Accordion wrapper-->

  </div>
  <!-- FIN BOTONES -->



  <!-- FILTROS 1 -->
  <div class="col-md-3 list-group-item " posicion="<?php echo ( obtenerIndiceCard( 'car_est_fil', $tipoUsuario, $id ) != 'false' ) ? obtenerIndiceCard( 'car_est_fil', $tipoUsuario, $id ) : 6; ?>" card="car_est_fil">
    <!--Accordion wrapper-->
    <div class="accordion md-accordion" id="accordionEx7" role="tablist" aria-multiselectable="true">

      <div class="card">

        <!-- Card header -->
        <div class="card-header grey darken-1 text-center" role="tab" id="headingTwo7">
            <a data-toggle="collapse" data-parent="#accordionEx7" href="#collapseTwo7"
              aria-controls="collapseTwo7" acordeon="aco_est_fil" 
              <?php  
                if ( obtenerEstatusAcordeon( 'aco_est_fil', $tipoUsuario, $id ) != 'falso' ) {
                // HAY REGISTRO PREVIO
              ?>
                  aria-expanded="<?php echo obtenerEstatusAcordeon( 'aco_est_fil', $tipoUsuario, $id ); ?>"
              <?php  
                if ( obtenerEstatusAcordeon( 'aco_est_fil', $tipoUsuario, $id ) == 'true' ) {
              ?>
                  class="acordeon"

              <?php
                } else if ( obtenerEstatusAcordeon( 'aco_est_fil', $tipoUsuario, $id ) == 'false' ) {
              ?>
                  class="acordeon collapsed"

              <?php
                }
              ?>

              <?php                   
                } else {
                // NO HAY REGISTRO PREVIO
              ?>
                  aria-expanded="true" class="acordeon"

              <?php
                }
              ?>
            >
              <h5 class="letraMediana white-text">
                Filtros de Estatus <i class="fas fa-angle-down rotate-icon"></i>
              </h5>
            </a>
        </div>

        <!-- Card body -->
        <div id="collapseTwo7" role="tabpanel" aria-labelledby="headingTwo7"
            data-parent="#accordionEx7"         
            <?php  
              if ( obtenerEstatusAcordeon( 'aco_est_fil', $tipoUsuario, $id ) != 'falso' ) {
              // HAY REGISTRO PREVIO
            ?>

            <?php  
              if ( obtenerEstatusAcordeon( 'aco_est_fil', $tipoUsuario, $id ) == 'true' ) {
            ?>
                class="collapse show"

            <?php
              } else if ( obtenerEstatusAcordeon( 'aco_est_fil', $tipoUsuario, $id ) == 'false' ) {
            ?>
                class="collapse"

            <?php
              }
            ?>

            <?php                   
              } else {
              // NO HAY REGISTRO PREVIO
            ?>
                class="collapse show"

            <?php
              }
            ?>
          >
        
        <div class="card-body bg-light" style=" height: 235px; ">
          
          <div class="row">
  
                <div class="col-md-5 text-left">
                  
                  <label class="form-check-label letraPequena font-weight-bold" style="line-height: 100%;">
                    Estatus Académico 
                  </label>
                  
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input checador1" id="materialChecked2" value="Activo" columna="9">
                    <label class="form-check-label letraPequena font-weight-normal" for="materialChecked2">Activos</label>
                  </div>
                  

                  <div class="form-check">
                    <input type="checkbox" class="form-check-input checador1" id="materialChecked3" value="Inactivo" columna="9">
                    <label class="form-check-label letraPequena font-weight-normal" for="materialChecked3">Inactivos</label>
                  </div>

                  <div class="form-check">
                    <input type="checkbox" class="form-check-input checador1" id="materialChecked4" value="Egresado" columna="9">
                    <label class="form-check-label letraPequena font-weight-normal" for="materialChecked4">Egresados</label>
                  </div>


                  
                  
                
                </div>



                <div class="col-md-7 text-left">
                  
                  <label class="form-check-label letraPequena font-weight-bold" style="line-height: 100%;">
                    Estatus de Pago 
                  </label>

                  <br>

                  <div class="form-check">
                    <input type="checkbox" class="form-check-input checador2" id="materialChecked5" value="Sin adeudo" columna="12">
                    <label class="form-check-label letraPequena font-weight-normal" for="materialChecked5" style="line-height: 0.9rem;">Sin adeudo</label>
                  </div>
                  

                  <div class="form-check">
                    <input type="checkbox" class="form-check-input checador2" id="materialChecked6" value="Con adeudo" columna="12">
                    <label class="form-check-label letraPequena font-weight-normal" for="materialChecked6" style="line-height: 0.9rem;">Con adeudo</label>
                  </div>

                </div>


              </div>


              <div class="row">
                <div class="col-md-12 text-left">
                    <div class="form-check">
                      <input type="checkbox" class="form-check-input checador100" id="materialChecked22" value="Baja Temporal" columna="13">
                      <label class="form-check-label letraPequena font-weight-normal" for="materialChecked22">
                        Baja Temporal
                      </label>
                    </div>
                </div> 
              </div>

              
              <label class="form-check-label letraPequena font-weight-bold" style="line-height: 100%;">
                Subestatus 
              </label>
              <div class="row">
                
                
                <div class="col-md-6 text-left">
                  
                  
                  
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input checador11" id="materialChecked11" value="Alta sin inscribir" columna="3">
                    <label class="form-check-label letraPequena font-weight-normal" for="materialChecked11" style="line-height: 0.9rem;">Alta sin inscribir</label>
                  </div>

                  <div class="form-check">
                    <input type="checkbox" class="form-check-input checador11" id="materialChecked21" value="N" columna="3">
                    <label class="form-check-label letraPequena font-weight-normal" for="materialChecked21">N</label>
                  </div>
                  
                  
                
                </div>



                <div class="col-md-6 text-left">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input checador11" id="materialChecked31" value="R" columna="3">
                    <label class="form-check-label letraPequena font-weight-normal" for="materialChecked31">R</label>
                  </div>

                  <div class="form-check">
                    <input type="checkbox" class="form-check-input checador11" id="materialChecked41" value="REC" columna="3">
                    <label class="form-check-label letraPequena font-weight-normal" for="materialChecked41">REC</label>
                  </div>
                </div>


              </div>
                    
        </div>
        
        </div>
        <!-- <div class="card-header grey darken-1 white-text text-center letraMediana">
          Programas Académicos
        </div> -->
          
        
  
      </div>
  
  </div>
  <!-- FIN Accordion wrapper-->
    
  </div>
  <!-- FIN FILTROS 1 -->

  <!-- FILTROS 2 -->
  <div class="col-md-3 list-group-item " posicion="<?php echo ( obtenerIndiceCard( 'car_bus_fil', $tipoUsuario, $id ) != 'false' ) ? obtenerIndiceCard( 'car_bus_fil', $tipoUsuario, $id ) : 7; ?>" card="car_bus_fil">
    
    <!--Accordion wrapper-->
    <div class="accordion md-accordion" id="accordionEx8" role="tablist" aria-multiselectable="true">

        <div class="card">

          <!-- Card header -->
          <div class="card-header grey darken-1 text-center" role="tab" id="headingTwo8">
              <a data-toggle="collapse" data-parent="#accordionEx8" href="#collapseTwo8"
              aria-controls="collapseTwo8"  acordeon="aco_bus_fil" 
                <?php  
                  if ( obtenerEstatusAcordeon( 'aco_bus_fil', $tipoUsuario, $id ) != 'falso' ) {
                  // HAY REGISTRO PREVIO
                ?>
                    aria-expanded="<?php echo obtenerEstatusAcordeon( 'aco_bus_fil', $tipoUsuario, $id ); ?>"
                <?php  
                  if ( obtenerEstatusAcordeon( 'aco_bus_fil', $tipoUsuario, $id ) == 'true' ) {
                ?>
                    class="acordeon"

                <?php
                  } else if ( obtenerEstatusAcordeon( 'aco_bus_fil', $tipoUsuario, $id ) == 'false' ) {
                ?>
                    class="acordeon collapsed"

                <?php
                  }
                ?>

                <?php                   
                  } else {
                  // NO HAY REGISTRO PREVIO
                ?>
                    aria-expanded="true" class="acordeon"

                <?php
                  }
                ?>
              >
                <h5 class="letraMediana white-text">
                  Búsqueda y filtrado <i class="fas fa-angle-down rotate-icon"></i>
                </h5>
              </a>
          </div>

          <!-- Card body -->
          <div id="collapseTwo8" role="tabpanel" aria-labelledby="headingTwo8"
              data-parent="#accordionEx8"           
              <?php  
                if ( obtenerEstatusAcordeon( 'aco_bus_fil', $tipoUsuario, $id ) != 'falso' ) {
                // HAY REGISTRO PREVIO
              ?>

              <?php  
                if ( obtenerEstatusAcordeon( 'aco_bus_fil', $tipoUsuario, $id ) == 'true' ) {
              ?>
                  class="collapse show"

              <?php
                } else if ( obtenerEstatusAcordeon( 'aco_bus_fil', $tipoUsuario, $id ) == 'false' ) {
              ?>
                  class="collapse"

              <?php
                }
              ?>

              <?php                   
                } else {
                // NO HAY REGISTRO PREVIO
              ?>
                  class="collapse show"

              <?php
                }
              ?>
            >
        
        <div class="card-body bg-light" style=" height: 235px; ">
          
          <div class="row">

                <div class="col-md-1"></div>
                <div class="col-md-10">
            
            <div id="contenedor_buscador" style="position: relative; top: -10%; ">

                  </div>

                  
                  <p class="letraPequena font-weight-bold" style="position: relative; top: -10%; "> Búsqueda por fecha de ingreso</p>
                  
                    
                  <div class="md-form mb-5" style="position: relative; top: -10%; ">
                    <input type="date" id="min-date" class="date-range-filter form-control validate" title="Inicio del Rango" style="font-size:10px;">
                  </div>
                  
                  <div class="md-form mb-5" style="position: relative; top: -20%; ">
                    <input type="date" id="max-date" class="date-range-filter form-control validate" title="Fin del Rango" style="font-size:10px;">
                  </div>

                  

                
                </div> 
                <div class="col-md-1"></div>
              </div>
                    
        </div>
        
        </div>
        <!-- <div class="card-header grey darken-1 white-text text-center letraMediana">
          Programas Académicos
        </div> -->
          
        
  
      </div>
  
  </div>
  <!-- FIN Accordion wrapper-->
    
 
  </div>
  <!-- FIN FILTROS 2 -->
      <!-- FIN ROW2 -->
      


    </div>
    <!-- FIN FILTROS CABECERA -->


    <div id="contenedor_alumnos">
      
    </div>
    
  </div>
</div>
<!-- FIN ALUMNOS -->



<?php  

  include('inc/footer.php');

?>

<script>
  // ALUMNOS


  // SELECCION DE TODOS LOS ANNIOS
  $("#seleccionTodos").on('click', function() {
  //event.preventDefault();
  /* Act on the event */

    //console.log( $(this)[0].checked );

    if ( $(this)[0].checked == true ) {
      // console.log("checkeado");
      $('.seleccionAnniosMeses').prop({checked: true});
      obtenerAnnios();
      
    }else{ 
      
      $('.seleccionAnniosMeses').prop({checked: false});
      obtenerAnnios();

    }

  //$('.seleccionAnniosMeses').prop({checked: false});
  });


  function obtenerAnnios() {


    var annios = [];
    var meses = [];

    for ( var i = 0, j = 0 ; i < $(".checkboxAnnio").length ; i++ ) {

      if ( $(".checkboxAnnio")[i].checked == true ) {
        // alert( "el checkbox numero: "+i+" con valor: "+$('.checkboxAnnio').eq(i).attr("annio")+" esta seleccionado"  );

        annios[j] = $('.checkboxAnnio').eq(i).attr("annio");
        meses[j] = $('.checkboxAnnio').eq(i).attr("mes");
        j++;

      }
    }

    if ( annios.length == 0 ) {

      swal("¡No hay años seleccionados!", "Selecciona al menos un año para continuar", "info", {button: "Aceptar",});
      $("#contenedor_alumnos").html("");

    } else {
      obtener_alumnos( annios, meses );
    }

    
  }


  obtenerAnnios();


  $('.checkboxAnnio').on('click', function() {
    //event.preventDefault();
    /* Act on the event */

    obtenerAnnios();
    

  });




  // var total = obtenerAnnios();

  //alert(total[0]);




  

  function obtener_alumnos( annios, meses ) {
    $("#contenedor_alumnos").html('<h3 class="text-center grey-text"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>');
    $.ajax({
      url: 'server/obtener_pagos_plantel.php',
      type: 'POST',
      data: {annios, meses},
      success: function(respuesta){
        $(".modal-backdrop").removeClass('modal-backdrop');
        $("#contenedor_buscador").html('');
        $("#contenedor_alumnos").html(respuesta);
        
                          
      }
    });
  }
</script>