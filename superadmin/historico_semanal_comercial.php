<?php  

    include 'inc/header.php';

    $id_eje = $_GET['id_eje'];

    $datos = obtenerDatosEjecutivo( $id_eje );

    $datosSrc = obtener_datos_scr( $id_eje );

    $mes = date('n');
    $annio = date('Y');

    $datosSemanas = array();
    $datosRegistros = array();
    $datosActivos = array();
    $datosComision = array();



   
?>
    
<div class="row ">
    <div class="col text-left">
        <span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Historico semanal">
            <i class="fas fa-bookmark"></i> 
            Histórico semanal
        </span>
    </div>
</div>

<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
    <a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
    <i class="fas fa-angle-double-right"></i>
    <a style="color: white;" href="ejecutivos.php">Ejecutivos</a>
    <i class="fas fa-angle-double-right"></i>
    <a style="color: black;" href="#" >Histórico semanal</a>
    
</div>

<br>
    
    <span class="badge badge-pill badge-dark font-weight-normal">

        <?php 
            $datosSemanal = obtener_datos_scr_semanal( $id_eje, obtener_fechas_semana_pasadas( $id_eje )['fin'], obtener_fechas_semana_pasadas( $id_eje )['inicio'] );
            echo $datosSemanal['estatus']; 
        
        ?>


    </span>

    <div class="card" style="border-radius: 20px;" >
        <div class="body">

            <div class="row">

                <div class="col-md-8">


                    <?php  
                        if ( $datos['fot_emp'] == '' ) {
                    ?>
                            <img src="../img/usuario.jpg"  class="rounded-circle img-fluid p-2" style=" width: 80px; height: 80px;">
                    <?php
                        } else {
                    ?>
                            <img src="../uploads/<?php echo $datos['fot_emp']; ?>" class="rounded-circle img-fluid p-2" style=" width: 80px; height: 80px;">
                    <?php
                        }
                    ?>

                    


                    <span>
                        <?php
                            echo $datos['nom_eje'].' '.$datos['app_eje'].' '.$datos['apm_eje'];
                        ?>
                    </span>

                    <br>
                    <span class="letraPequena grey-text p-3">
                        Ingreso <?php echo fechaFormateadaCompacta2( $datos['ing_eje'] ); ?>  
                    </span>


                    <span>
                        Total activos: <?php echo $datos['totalPresentados']; ?>
                        Total iniciados: <?php echo $datos['totalIniciados']; ?>
                        PROMEDIO: <?php echo $datos['promedioPresentados']; ?>
                    </span>
                    
                    
                    
                </div>
                

                <div class="col-md-4 text-right" style="position: relative;">

                    <div id="contenedor_btn_comision">
                        
                    
                    <?php
                        $mesHoy = date('n');

                        $fechaHoy = date('Y-m-d');
                    
                        $inicio = '';
                        $fin = '';
                        $periodo = 6;
                        $periodicidad = $periodo+1;
                        $lunes = date("j");
                     
                        $i = 0;

                        $semanas = obtenerDiferenciaFechasSemanas( $fechaHoy, date('Y').'-01-01' );
                        
                        $validador_comision = "SELECT * FROM comision WHERE MONTH(fec_com) = $mes AND YEAR( fec_com ) = $annio AND  id_eje4 = '$id_eje'";

                        // echo $validador_comision;
                        $resultado_validador = mysqli_query( $db, $validador_comision );
                        $validador = mysqli_num_rows( $resultado_validador );

                        if ( $validador > 0 ) {
                        
                            $resultado4 = mysqli_query( $db, $validador_comision );
                            $fila_comision = mysqli_fetch_assoc( $resultado4 );

                            $est_com = $fila_comision['est_com'];

                            if ( $est_com == 'Pagada' ) {
                    ?>
                                <a href="#" class="btn btn-success btn-sm btn-rounded disabled" >Comisión <?php echo getMonthLower( $mesHoy ); ?> pagada $<?php echo $fila_comision['tot_com']; ?></a>

                                <br>

                                <a href="#" id="btn_bloqueado" class="btn grey darken-1 white-text btn-sm btn-rounded">Por cobrar comisión <?php echo getMonthLower( ++$mesHoy ); ?> <?php echo $datosSrc['comision']; ?></a>


                    <?php    
                            
                            } else if ( $est_com == 'Pendiente' ) {
                    ?>

                                <a href="#" class="btn btn-warning btn-sm btn-rounded" >Comisión pendiente <?php echo $datosSrc['comision']; ?></a>
                    <?php  
                            
                            } 
                    ?>



                    <?php
                        } else {
                    ?>


                            <?php  
                                if ( $datosSemanal['estatus'] == 'Consultor' ) {
                            ?>
                                    <?php  

                                        if ( date('j') == 6  ) {
                                    ?>
                                        <a href="#" class="btn btn-info btn-sm btn-rounded" id="btn_comision">Cobrar comisión <?php echo $datosSemanal['comision']; ?></a>
                                    
                                    <?php
                                        } else {
                                    ?>
                                        
                                        <a href="#" id="btn_bloqueado" class="btn grey darken-1 white-text btn-sm btn-rounded">Por cobrar comisión <?php echo $datosSemanal['comision']; ?></a>

                                    <?php
                                        }
                                    ?>

                            <?php
                                } else {

                                    // echo "algo";
                            ?> 

                                    <a href="#" class="btn grey darken-1 white-text btn-sm btn-rounded">
                                        N/A
                                    </a>

                            <?php
                                }
                            ?>
                            

                    <?php
                        }
                    
                    ?>

                    </div>

                    <br>
                    <a id="btn_historial_comision" href="#" class="blue-text btn-link" style=" position: absolute; bottom: 10px; right: 40px;">Consultar pagos de comisión</a>
                </div>
                
            </div>
            
        </div>
    </div>


    <hr>

    


    <div class="card" style="border-radius: 20px; display: none;" >
        <div class="card-body">
            <div class="row">
                <div class="col-md-1">
                    
                </div>


                <div class="col-md-10">
                    <!-- chart -->
                    <canvas id="lineChart"></canvas>
                    <!-- fin chart  -->
                </div>

                <div class="col-md-1">
                    
                </div>
            </div>
            

        </div>
    </div>


    <hr>
    


    
    
    <div class="accordion md-accordion accordion-blocks" id="accordionEx78" role="tablist"
    aria-multiselectable="true">
    
    <?php
        do{


            
            if ( $i == 0 ) {

                if ( $lunes != 6 ) {
                  //echo 'if';
                  $domingo_proximo =  $fechaHoy;
                  $lunes_proximo = date("N");
                  $lunes_proximo = $lunes_proximo-1;
                  $inicio = date('Y-m-d', strtotime($fechaHoy));
                  $fin = date('Y-m-d', strtotime($fechaHoy. " - $lunes_proximo days"));

                  $semanas = $semanas + 1;

                } else {
                  //echo 'else';

                    if ( $lunes == 6 ) {
                        $domingo_proximo =  $fechaHoy;
                        $lunes_proximo = date("N");
                        $lunes_proximo = $lunes_proximo-1;
                        $inicio = date('Y-m-d', strtotime($fechaHoy));
                        $fin = date('Y-m-d', strtotime($fechaHoy. " - $lunes_proximo days"));
                    
                    } else {


                        $domingo_proximo = date("N"); //domingo = 7
                        $lunes_proximo = $domingo_proximo + $periodo; //lunes proximo= 7+6 = 13;
                        $inicio = date('Y-m-d', strtotime($fechaHoy. " - $domingo_proximo days"));//inicio = (4 de abril del 2021)
                        $fin = date('Y-m-d', strtotime($fechaHoy. " - $lunes_proximo days")); //fin = (29 de mayo del 2021)

                    }

                }
            

            } else {

       
                $inicio = date('Y-m-d', strtotime($fin. " - 1 days"));
                $fin = date('Y-m-d', strtotime($fin. " - $periodicidad days"));
                

            }
    ?>


    <?php
            // echo $inicio;
            if ( $fin < date('Y').'-01-01' ) {
                // echo 'ok';
                break; break; break;
            } else {
                $datosScrSemanal = obtener_datos_scr_semanal( $id_eje, $inicio, $fin );
    ?>

                <!-- Accordion card -->
        <div class="card" style="border-radius: 20px;">

            <!-- Card header -->
            <div class="card-header" role="tab" id="headingUnfiled<?php echo $i; ?>">
                <div class="row">



                    <div class="col-md-5 col-sm-5" style="position: relative; left: 40px;">

                        <?php  
                            if ( $datosScrSemanal['estatus'] == 'Consultor' ) {
                        ?>
                            <a class="btn-floating btn-sm btn-success" style="position: absolute; top: -10px; left: -40px;"></a>
                        <?php
                            } else {
                        ?>

                            <a class="btn-floating btn-sm btn-danger" style="position: absolute; top: -10px; left: -40px;"></a>

                        <?php
                            }
                        ?>
                        

                        <h6>
                            <?php echo fechaFormateadaCompacta2( $fin ).' al '.fechaFormateadaCompacta2( $inicio ); ?>
                        </h6>

                        <span class="grey-text">
                            Total: <?php echo $datosScrSemanal['alumnosTotales']; ?>
                            <?php  
                                $datosRegistros[$i] = $datosScrSemanal['alumnosTotales'];
                            ?>
                            <br>

                            Alumnos iniciados: <?php echo $datosScrSemanal['alumnosAuxTotales']; ?>
                            <br>
                            Alumnos activos: <?php echo $datosScrSemanal['alumnosActivos']; ?>
                            <?php  
                                $datosActivos[$i] = $datosScrSemanal['alumnosActivos'];
                            ?>
                            <br>
                            Presentación: <?php echo $datosScrSemanal['porcentaje']; ?>%
                            <br>
                            <?php  

                                if ( $datosScrSemanal['alumnosTotales'] >= 4 ) {
                                    echo "Comisión: ".$datosScrSemanal['comision'];
                                    $datosComisión[$i] = $datosScrSemanal['comision'];
                                } else {
                                    echo "Comisión: N/A";
                                }
                            ?>


                        </span>
                    </div>

                    <div class="col-md-5 col-sm-5">
                    </div>


                    <div class="col-md-2 col-sm-2">
                        <!-- Heading -->
                        

                        <a data-toggle="collapse" data-parent="#accordionEx78" href="#collapseUnfiled<?php echo $i; ?>" aria-expanded="true"
                            aria-controls="collapseUnfiled<?php echo $i; ?>">
                            <h5 class="mt-1 mb-0">
                                Semana <?php echo $semanas; ?>
                                <?php
                                    $datosSemanas[$i] = 'Semana '.$semanas;  
                                    // $datosSemanas[$i] = 'Semana '.$semanas.' ( '.fechaFormateadaCompacta2( $fin ).' al '.fechaFormateadaCompacta2( $inicio ).' )'; 
                                ?>
                                <i class="fas fa-angle-down rotate-icon"></i>
                            </h5>
                        </a>
                    </div>


                </div>


            </div>

            <!-- Card body -->
            <div id="collapseUnfiled<?php echo $i; ?>" class='<?php if ( $i == 0 ) { echo "collapse show"; } else { echo "collapse"; } ?>' role="tabpanel" aria-labelledby="headingUnfiled<?php echo $i; ?>"
              data-parent="#accordionEx78">
                <div class="card-body">

                    <?php
                        

                        $sql = "
                            SELECT *
                            FROM vista_alumnos
                            WHERE ( ing_alu BETWEEN '$fin' AND '$inicio' ) AND ( id_eje3 = '$id_eje' )
                        ";

                        // echo $sql;

                        $resultado = mysqli_query( $db, $sql );

                        $resultado2 = mysqli_query( $db, $sql );
                        $total = mysqli_num_rows( $resultado2 );

                        while( $fila = mysqli_fetch_assoc( $resultado ) ){
                    ?>

                        <div class="card" style="border-radius: 20px;">
                            <div class="card-body" >
                                <div class="row">
                                    
                                    <div class="col-md-12">
                                        
                                        <div class="table-responsive">

                                            <table class="table table-hover">
                                                
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <?php 

                                                                echo obtenerBadgeEstatusEjecutivo( $fila['estatus_general'] ); 

                                                            ?>
                                                            <br>
                                                            <span class="letraPequena">
                                                                Ingreso: <?php echo fechaFormateadaCompacta2($fila['ing_alu']); ?>
                                                                <br>
                                                                Inicio: <?php echo fechaFormateadaCompacta2($fila['ini_gen']); ?>
                                                                <br>
                                                                Fin: <?php echo fechaFormateadaCompacta2($fila['fin_gen']); ?>
                                                            </span>

                                                            
                                                        </td>



                                                        
                                                        <td class="text-center">
                                                            <br>
                                                            <?php  
                                                                if ( $fila['fot_alu'] == '' ) {
                                                            ?>
                                                                    <img src="../img/usuario.jpg" class="rounded-circle img-fluid" style="border-style: solid; width: 50px; height: 50px;">
                                                            <?php
                                                                } else {
                                                            ?>
                                                                    <img src="../uploads/<?php echo $fila['fot_alu']; ?>" class="rounded-circle img-fluid" style="border-style: solid; width: 50px; height: 50px;">
                                                            <?php
                                                                }
                                                            ?>

                                                            <br>
                                                            
                                                            <a href="cobranza_alumno.php?id_alu_ram=<?php echo $fila['id_alu_ram']; ?>" class="btn-link text-primary" target="_blank">
                                                                <?php echo $fila['nom_alu']; ?>
                                                            </a>

                                                            <br>

                                                            <a href="cobranza_alumno.php?id_alu_ram=<?php echo $fila['id_alu_ram']; ?>" class="btn-link text-primary" target="_blank">
                                                                Ir a pagos
                                                            </a>
                                                            
                                                            <br>

                                                            <a href="#" class="btn-link text-danger eliminacionAlumno" eliminacion="<?php echo $fila['id_alu_ram']; ?>" alumno="<?php echo $fila['nom_alu']; ?>">
                                                              Eliminar
                                                            </a>
                                                                                                            
                                                        </td>
                                                        

                                                        <td>
                                                            <br>

                                                        
                                                            <?php echo $fila['nom_ram']; ?>
                                    
                                                            
                                                        </td>

                                                        <td>
                                                            <br>

                                                            <?php echo $fila['nom_gen']; ?>                                    
                                                            
                                                        </td>

                                                        <td>
                                                            <br>
                                                            <strong>
                                                                <?php echo $fila['tel_alu']; ?>
                                                            </strong>
                                    
                                                        </td>



                                                        <td>
                                                            <br>
                                                            <strong>
                                                                <?php echo $fila['tel_alu']; ?>
                                                            </strong>
                                    
                                                        </td>


                                                        <td>
                                                            <br>
                                                            <strong>
                                                                <?php
                                                                    $id_alu = $fila['id_alu'];

                                                                    $sqlBeca = "
                                                                        SELECT *
                                                                        FROM cita
                                                                        INNER JOIN alumno ON alumno.id_cit1 = cita.id_cit
                                                                        WHERE id_alu = '$id_alu'
                                                                    ";

                                                                    $resultadoBeca = mysqli_query( $db, $sqlBeca );

                                                                    $filaBeca = mysqli_fetch_assoc( $resultadoBeca );

                                                                    if ( $filaBeca['bec_cit'] != 'Pendiente' ) {
                                                                        echo 'Beca: '.$filaBeca['bec_cit'];
                                                                    } else {
                                                                        echo 'Beca: N/A';
                                                                    }
                                                                    
                                                                ?>
                                                            </strong>
                                    
                                                        </td>


                                                        <td>
                                                            <br>
                                                            <?php  
                                                                if ( $datosSrc['estatus'] == 'Consultor' ) {
                                                            ?>

                                                                    <h3 class="text-success">
                                                                        <?php echo "$".$fila['com_ram']; ?>
                                                                    </h3>   

                                                            <?php
                                                                } else {
                                                            ?>
                                                                    <h3 class="grey-text">
                                                                        N/A
                                                                    </h3>

                                                            <?php
                                                                }
                                                            ?>
                                                            
                                                        </td>

                                                    </tr>
                                                </tbody>

                                            </table>    
                                        
                                        </div>
                                        
                                        
                                        
                                    </div>


                                   

                                    

                                </div>
                            </div>
                        </div>

                    <?php
                        }


                        $i++;
                        $semanas--;
                    ?>

                </div>
            </div>
        </div>


    <?php
            }

            // $fecha = new DateTime("$inicio");
            // $semanas = $fecha->format('W');
            

            
    ?>


        

    <?php
            
        }while( (date('Y').'-01-01' < $fin) );
    ?>




    </div>
    <!--/.Accordion wrapper-->


    <!-- MODAL HISTORIAL COMISIONES -->
    <div class="modal fade text-left " id="modal_historial_comisiones">
        <div class="modal-dialog modal-lg" role="document">
        
      
            <div class="modal-content" style="border-radius: 20px;">
                
                <div class="modal-header text-center">
                  
                    <h4 class="modal-title w-100">
                        Historial de pagos de comisión
                    </h4>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>

                <div class="modal-body mx-3" id="contenedor_historial_comisiones">

                    


                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <a class="btn grey white-text btn-rounded waves-effect btn-sm" title="Salir..." data-dismiss="modal">
                        Cancelar
                    </a>
                </div>

              </div>


            </div>
        </div>
    </div>

    <!-- FIN MODAL HISTORIAL COMISIONES -->


<?php  

  include 'inc/footer.php';

?>


<script>
    $('#btn_bloqueado').on('click',function(event) {
        event.preventDefault();
        /* Act on the event */

        swal ( "¡Acción invalida!" ,  "¡Los pagos de comisión solo se solicitan al inicio de cada mes!" ,  "error" );

    });



    $('#btn_comision').on('click',function(event) {
        event.preventDefault();
        /* Act on the event */

        var nombreEmpleado = '<?php echo $datos['nom_eje'].' '.$datos['app_eje']; ?>';


        swal({
          title: "¿Deseas comisionar a "+nombreEmpleado+"?",
          text: "Podrás visualizar su historial de pagos de comisión después",
          icon: "info",
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
                        className: "btn-info",
                        closeModal: true
                      }
                    },
          dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
            //ELIMINACION ACEPTADA

                var total = parseFloat(<?php echo $datosSrc['comisionNumerica']; ?>);
                var id_eje = parseInt(<?php echo $id_eje; ?>);

                $.ajax({
                    url: 'server/agregar_comision.php',
                    type: 'POST',
                    data: { total, id_eje },
                    success: function( res ){

                        console.log( res );
                        
                        if ( res == 'Exito' ) {
                            
                            swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",});

                            $('#contenedor_btn_comision').html('<a href="#" class="btn btn-warning btn-sm btn-rounded" >Comisión pendiente <?php echo $datosSrc["comision"]; ?></a>');

                        } else {

                           console.log( res );

                        }
                        
                    }
                });

            
            }
        });
        
        

        
    });
</script>


<!-- chart -->
<script>

    var ctxL = document.getElementById("lineChart").getContext('2d');
    var myLineChart = new Chart(ctxL, {
    type: 'line',
    data: {
        <?php 
            echo 'labels: ['; 
            echo obtenerSemanasEjecutivo( $datosSemanas );
            echo '],';
              echo 'datasets: [';
              echo    '{
                    label: "Registros",
                    data: [ ';
              echo obtenerRegistrosEjecutivo( $datosRegistros );
              echo '],
                    backgroundColor: [
                      "rgba(200, 200, 200, .6)",
                    ],
                    borderColor: [
                      "rgba(200, 200, 200, .6)",
                    ],
                    borderWidth: 2
                  },';

              echo    '{
                    label: "Activos",
                    data: [ ';
              echo obtenerActivosEjecutivo( $datosActivos );
              echo '],
                    backgroundColor: [
                      "rgba(76, 175, 80, .8)",
                    ],
                    borderColor: [
                      "rgba(96, 195, 100, .8)",
                    ],
                    borderWidth: 2
                  },';


              echo ']';
        ?>
    },
    options: {
    responsive: true
    }
    });
</script>
<!-- fin chart -->


<script>
    $('#btn_historial_comision').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */

        var id_eje = <?php echo $id_eje; ?>;


        $('#modal_historial_comisiones').modal('show');

        $.ajax({
            
            url: 'server/obtener_historial_comisiones.php',
            type: 'POST',
            data: { id_eje },
            success: function( respuesta ){

                $('#contenedor_historial_comisiones').html( respuesta );

            }

        });
        


        
    });
</script>


<script>
    $('.eliminacionAlumno').on('click', function(event) {
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


                                      window.location.reload();


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
    // FIN ELIMINACION
</script>