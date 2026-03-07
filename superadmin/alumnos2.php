<?php  

	include('inc/header.php');
	
?>

	<div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">



                            <i class="pe-7s-study icon-gradient bg-premium-dark"></i>
                        </div>
                        <div>
                            ALUMNOS
                            <div class="page-title-subheading">Alumnos de <?php echo $nombreCadena; ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div>

                <div class="row">

                	<!-- COL  FILTROS -->
                    <div class="col-md-3" id="contenedor_col-3">

                        
                        <div class="stickyChida">
                            <!-- CARD -->
                            <div class="card z-depth-1 scrollspy-example " style="border-radius: 20px;">
                                
                                <div class="card-body">

                                    <div class=" scrollspy-example" style=" height: 200px; display: none;">
                                        <div class="row">
                                            <div class="col-md-12" id="contenedor_seleccion_alumnos">
                                                
                                            </div>


                                            <div>
                                                <div class="form-check"> <input type="checkbox" class="form-check-input" id="checkbox_generacion_pagos" value="0"> <label class="form-check-label" for="checkbox_generacion_pagos"> </label></div>
                                            </div>

                                        </div>
                                    </div>
                                    
                                    <span class="">
                                        Tipo de visualización
                                    </span>


                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input radiosVisualizacion" id="radioGeneraciones" name="radiosVisualizacion" value="Generaciones" checked="">
                                                <label class="form-check-label letraPequena" for="radioGeneraciones">Filtros</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input radiosVisualizacion" id="radioAlumnos" name="radiosVisualizacion" value="Alumnos" >
                                                <label class="form-check-label letraPequena" for="radioAlumnos">Alumnos</label>
                                            </div>
                                        </div>
                                    </div>


                                    <hr>


                                    <!-- PLANTELES -->
                                    <span>
                                        CDE
                                    </span>

                                    <div class=" scrollspy-example" style=" height: 200px;">
                                        
                                        <div class="row">
                                            <div class="col-md-12">

                                                <input type="checkbox" class="form-check-input" id="seleccionPlanteles" checked="checked">
                                                <label class="form-check-label letraPequena" for="seleccionPlanteles" style="font-size: 10px;">
                                                    Todo
                                                </label>
                                                
                                            </div>
                                        </div>


                                        <?php
                                            $sqlPlantel = "
                                                SELECT *
                                                FROM plantel
                                                WHERE id_cad1 = '$cadena'
                                                ORDER BY nom_pla DESC
                                            ";

                                            // echo $sqlPlantel;

                                            $resultadoPlantel = mysqli_query( $db, $sqlPlantel );
                                            $resultadoTotalPlantel = mysqli_query( $db, $sqlPlantel );

                                            $contadorPlantel = 1;

                                            $totalPlantel = mysqli_num_rows( $resultadoTotalPlantel );

                                                    for ( $i = 0 ; $i < $totalPlantel / 1 ; $i++ ) {
                                        ?>
                                                      <div class="row">
                                        <?php  
                                                          while( $filaPlantel = mysqli_fetch_assoc( $resultadoPlantel ) ){
                                        ?>
                                                              <div class="col-md-12">
                                                                
                                                                    <input type="checkbox" class="form-check-input checkboxPlanteles" id="plantel<?php echo $contadorPlantel; ?>" value="<?php echo $filaPlantel['id_pla']; ?>" checked="checked">
                                                                    <label class="form-check-label letraPequena" for="plantel<?php echo $contadorPlantel; ?>" style="font-size: 10px;">

                                                                        <?php echo $filaPlantel['nom_pla']; ?>

                                                                    </label>

                                                              </div>
                                        <?php
                                                            $contadorPlantel++;
                                                          }
                                        ?>
                                                        
                                                      </div>

                                        <?php
                                                    }
                                                  // FIN for
                                        ?>

                                    </div>
                                    <!-- FIN PLANTELES -->

                                    <hr>
                                    
                                    <!-- PROGRAMAS -->
                                    <span>
                                        Programas
                                    </span>

                                    <div id="contenedor_programas_plantel">
                                        
                                    </div>
                                    <!-- FIN PROGRAMAS -->

                                    <hr>

                                    <!-- GENERACIONES -->
                                    <span>
                                        Grupos
                                    </span>

                                    <div  id="contenedor_generaciones">
                                        
                                        
                                        
                                    </div>

                                    <!-- FIN GENERACIONES -->

                                   

                                </div>
                                
                            
                            </div>
                            <!-- FIN CARD -->
                        </div>
                        

                    </div>
                    <!-- FIN COL FILTROS -->

                    <!-- CONTENEDOR PRINCIPAL -->
                    <div class="col-md-9" id="contenedor_principal" style="display: ;">

           
                            


                                <form id="formulario_alumno">
                                    <div class="row p-2">
                                        <div class="col-md-12">

                                            <!-- Material input -->
                                            <div class="md-form">
                                                
                                                <input type="text" id="palabra" class="form-control letraGrande" autocomplete="off" placeholder="Buscar...">
                                                                                        
                                            </div>

                                        </div>


                                        <div class="col-md-4" style="display: none;">
                                            
                                            <!-- FECHAS -->
                                            <div class="md-form">
                                                
                                                <div class="row">
                                                    
                                                

                                                    <div class="col-md-12">
                                                        <span class="letraPequena">
                                                            Inicio del rango
                                                        </span>
                                                        <br>
                                                        <input type="date" id="inicio" class="date-range-filter form-control validate grey-text" title="Inicio del Rango" style="font-size:10px;">
                                                    
                                                    </div>
                                                
                                                </div>


                                            </div>
                                            <!-- FIN FECHAS -->
                                        </div>

                                        <div class="col-md-4" style="display: none;">
                                            
                                            <!-- FECHAS -->
                                            <div class="md-form">
                                                
                            

                                                <div class="row">
                                                    

                                                    <div class="col-md-12">
                                                        <span class="letraPequena">
                                                            Fin del rango
                                                        </span>

                                                        <br>
                                                        <input type="date" id="fin" class="date-range-filter form-control validate grey-text" title="Fin del Rango" style="font-size:10px;">
                                                    </div>
                                                
                                                </div>

                                            </div>
                                            <!-- FIN FECHAS -->
                                        </div>
                                    
                                    </div>

                                </form>

                                <hr>

                                <div class="row">
                                    <div class="col-md-12">
                                        
                                        <a href="#" class="btn-link black-text waves-effect" id="btn_generacion">
                                            <h5>
                                                <i class="fas fa-plus"></i> Crear nuevo grupo
                                            </h5>
                                        </a>
                                        
                                        
                                    </div>
                                </div>

                                <hr>

                                <div id="contenedor_visualizacion">
                                    
                                </div>


                                <div id="contenedor_visualizacion2">

                                    <div id="contenedor_visualizacion4">
                                        
                                    </div>

                                    <!-- BOTONES ACCION SELECCION -->                   
                                    <div class="row" id="contenedor_botones_accion">
                                          
                                    </div>
                                    <!-- FIN BOTONES ACCION SELECCION -->

                                    <div id="contenedor_visualizacion3">
                                        
                                    </div>
                                    
                                </div>



                                <div id="contenedor_visualizacion5">
                                    <div id="contenedor_visualizacion6">
                                        
                                    </div>

                                    <div id="contenedor_visualizacion7">
                                        
                                    </div>
                                </div>

                          
                  
                    </div>
                    <!-- FIN CONTENEDOR PRINCIPAL -->

	            </div>
                
            </div>
        </div>
        
    </div>


<?php  

	include('inc/footer.php');

?>

<script>
    
    $("#seleccionPlanteles").on('click', function() {
    //event.preventDefault();
    /* Act on the event */

        //console.log( $(this)[0].checked );

        if ( $(this)[0].checked == true ) {
          // console.log("checkeado");
            $('.checkboxPlanteles').prop({checked: true});
            obtenerProgramas();
          
        }else{ 
          
            $('.checkboxPlanteles').prop({checked: false});
            obtenerProgramas();

        }

    //$('.seleccionAnniosMeses').prop({checked: false});
    });


    function obtenerProgramas() {

        var id_pla = [];

        for ( var i = 0, j = 0 ; i < $(".checkboxPlanteles").length ; i++ ) {

            if ( $(".checkboxPlanteles")[i].checked == true ) {
                // alert( "el checkbox numero: "+i+" con valor: "+$('.checkboxPlanteles').eq(i).attr("annio")+" esta seleccionado"  );

                id_pla[j] = $('.checkboxPlanteles').eq(i).val();

                j++;

            }
        }

        if ( id_pla.length == 0 ) {

            swal("¡No hay planteles seleccionados!", "Selecciona al menos uno para continuar", "info", {button: "Aceptar",});
            
            // $("#contenedor_generaciones").html("");
            // $("#contenedor_principal").html("");

        } else {
            
            $.ajax({
                url: 'server/obtener_programas_plantel.php',
                type: 'POST',
                data: { id_pla },
                success: function( respuesta ){

                    $("#contenedor_programas_plantel").html( respuesta );
                              
                }

            });

        }

        
    }


    obtenerProgramas();


    $('.checkboxPlanteles').on('click', function() {
        //event.preventDefault();
        /* Act on the event */
        obtenerProgramas();
        

    });
</script>


<!-- GENERACIONES -->
<script>
    $('#btn_generacion').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */

        $('#modal_generacion').modal('show');
        
        obtener_clave_generacion();
        
        setTimeout(function(){
            
            $('#nom_gen').focus();

        }, 500 );
        
    });






    
    function obtener_clave_generacion(){
        
        var id_ram = $('input[name="id_ram"]:checked' ).val();

        $.ajax({
            url: 'server/obtener_clave_generacion.php',
            type: 'POST',
            data: { id_ram },
            success: function( respuesta ){

                

                setTimeout(function(){

                    var fecha1 = moment( $('#ini_gen').val() );
                    var fecha2 = moment( $('#fin_gen').val() );

                    var dias = fecha2.diff( fecha1, 'days');

                    var meses = Math.round( dias/30 );

                    if ( meses < 0 ) {
                        meses = 'N/A';
                    } else {
                        meses = meses+' meses';
                    }

                    // $('#nom_gen').focus();
                    $('#modal_generacion_titulo').text( respuesta+' ( '+meses+' )' );
                    $('#nom_gen').val( respuesta );


                
                }, 200 );

            }
        });
        


    }



</script>


<script>

    setTimeout(function(){
        $('.programasGeneracion').on('change', function(event) {
            event.preventDefault();
            /* Act on the event */

            console.log("cambio");
            obtener_clave_generacion();
        
        });




        $('#modal_generacion_formulario').on('submit', function(event) {
            event.preventDefault();
            /* Act on the event */
            $("#btn_submit_generacion").attr('disabled','disabled').html('<i class="fas fa-cog fa-spin"></i> Guardando...');

            
            var modal_generacion_formulario = new FormData( $('#modal_generacion_formulario')[0] );
            
            $.ajax({
            
                url: 'server/agregar_generacion.php',
                type: 'POST',
                data: modal_generacion_formulario, 
                processData: false,
                contentType: false,
                cache: false,
                success: function(respuesta){
                    
                    console.log(respuesta);

                    swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",}).
                    then((value) => {

                        $("#btn_submit_generacion").removeAttr( 'disabled' ).html( 'Guardar');

                        $('#modal_generacion').modal('hide');
                        // obtenerAlumnosGeneraciones();
                        obtenerGeneraciones();
                    });


                    

                }
            });

        });
        


        // FIN BUSCADOR RAMAS EN MODAL GENERACIONES
    }, 1000);
     
</script>


<!-- FIN GENERACIONES -->
<!-- AGREGAR -->
<div class="modal fade text-left" id="modal_generacion">
  <div class="modal-dialog modal-lg" role="document">
    
    <form id="modal_generacion_formulario" enctype="multipart/form-data">
        <div class="modal-content <?php echo $estilos_modo['container']; ?>" style="border-radius: 20px;">
          <div class="modal-header text-center">
            <h5 class="modal-title"><i class="fas fa-graduation-cap"></i> Grupo: <span id="modal_generacion_titulo"></span></h5>
        
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          
            <div class="modal-body mx-3">

                <span class="grey-text">
                    Programa académico
                </span>

                <hr>

                <div  style=" height: 200px; overflow-y: scroll;">

                  <?php  
                    $sqlProgramas = "
                      SELECT *
                      FROM rama
                      INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
                      WHERE id_cad1 = '$cadena'
                      ORDER BY id_ram ASC
                    ";

                    $resultadoProgramas = mysqli_query( $db, $sqlProgramas );


                    $contadorProgramas = 1;

               
                      while( $filaProgramas = mysqli_fetch_assoc( $resultadoProgramas ) ){
                    ?>

                      <?php  
                        if ( $contadorProgramas == 1 ) {
                      ?>


                              <input type="radio" class="form-check-input programasGeneracion" nom_ram="<?php echo $filaProgramas['nom_ram']; ?>" id="programaGeneracionModal<?php echo $contadorProgramas; ?>" value="<?php echo $filaProgramas['id_ram']; ?>" name="id_ram" checked>
                              <label class="form-check-label font-weight-normal" for="programaGeneracionModal<?php echo $contadorProgramas; ?>">
                            
                                <?php echo $filaProgramas['nom_ram']; ?>
                                <br>
                                <span class="letraPequena grey-text"><?php echo $filaProgramas['nom_pla']; ?></span>
                              </label>
                    

                      <?php
                        } else {
                      ?>
                              <input type="radio" class="form-check-input programasGeneracion" nom_ram="<?php echo $filaProgramas['nom_ram']; ?>" id="programaGeneracionModal<?php echo $contadorProgramas; ?>" value="<?php echo $filaProgramas['id_ram']; ?>" name="id_ram">
                              <label class="form-check-label font-weight-normal" for="programaGeneracionModal<?php echo $contadorProgramas; ?>">
                            
                                <?php echo $filaProgramas['nom_ram']; ?>
                                <br>
                                <span class="letraPequena grey-text"><?php echo $filaProgramas['nom_pla']; ?></span>

                              </label>
                    

                      <?php
                        }
                      ?>
                      
                      <hr>

                  <?php
                      $contadorProgramas++;
                    }
                    // FIN while
                  ?>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <div class="md-form">
                            <input type="text" id="nom_gen" name="nom_gen" class="form-control validate">
                            <label  for="nom_gen">Grupo</label>
                        </div>
                    </div>

                    
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="md-form">
                            <span class="letraMediana grey-text">
                                Inicia
                            </span>
                            <br>
                            <input type="date"  id="ini_gen" name="ini_gen" class="form-control validate programasGeneracion" value="<?php echo date( 'Y-m-d' ); ?>">
                        </div>
                        
                    </div>

                    <div class="col-md-6">
                        <div class="md-form">
                            <span class="letraMediana grey-text">
                                Termina
                            </span>
                            <br>
                            <input type="date" id="fin_gen" name="fin_gen" class="form-control validate programasGeneracion" value="<?php echo gmdate( 'Y-m-d', strtotime ( '+ 120 day' , strtotime ( date( 'Y-m-d' ) ) ) ); ?>">
                        </div>
                        
                    </div>
                </div>


                
            </div>

        <div class="modal-footer d-flex justify-content-center bg-light">
            
            <button class="btn btn-info btn-rounded waves-effect btn-sm" title="Guardar cambios..." type="submit" id="btn_submit_generacion">
                Guardar
            </button>


        </div>

        </div>
    </form>

  </div>
</div>
<!-- FIN AGREGAR -->
<!-- FIN MODAL GENERACION -->



<!-- MODAL GENERACION EDICION-->

<!-- EDICION -->
<div class="modal fade text-left" id="modal_generacion_edicion">
  <div class="modal-dialog modal-lg" role="document">
    
    <form id="modal_generacion_formulario_edicion" enctype="multipart/form-data">
        <div class="modal-content <?php echo $estilos_modo['container']; ?>" style="border-radius: 20px;">
          <div class="modal-header text-center">
            <h5 class="modal-title"><i class="fas fa-graduation-cap"></i> Grupo: <span id="modal_generacion_titulo_edicion"></span></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          
            <div class="modal-body mx-3">

                <input type="hidden" id="id_gen_edicion" name="id_gen_edicion">

                <div class="row">
                    <div class="col-md-12">
                        <div class="md-form">
                            <input type="text" id="nom_gen_edicion" name="nom_gen_edicion" class="form-control validate programasGeneracion_edicion">
                            <label  for="nom_gen_edicion">Grupo</label>
                        </div>
                    </div>

                    
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="md-form">
                            <span class="letraMediana grey-text">
                                Inicia
                            </span>
                            <br>
                            <input type="date"  id="ini_gen_edicion" name="ini_gen_edicion" class="form-control validate programasGeneracion_edicion" value="<?php echo date( 'Y-m-d' ); ?>">
                        </div>
                        
                    </div>

                    <div class="col-md-6">
                        <div class="md-form">
                            <span class="letraMediana grey-text">
                                Termina
                            </span>
                            <br>
                            <input type="date" id="fin_gen_edicion" name="fin_gen_edicion" class="form-control validate programasGeneracion_edicion" value="<?php echo gmdate( 'Y-m-d', strtotime ( '+ 120 day' , strtotime ( date( 'Y-m-d' ) ) ) ); ?>">
                        </div>
                        
                    </div>
                </div>

            </div>

        <div class="modal-footer d-flex justify-content-center">
            
            <button class="btn btn-info btn-rounded waves-effect btn-sm" title="Guardar cambios..." type="submit" id="btn_submit_generacion_edicion">
                Guardar
            </button>

        </div>

        </div>
    </form>

  </div>
</div>
<!-- FIN EDICION -->
<!-- FIN MODAL EDICION GENERACION -->