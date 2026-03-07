<?php  

	include('inc/header.php');
	
?>
    
    
    <!-- CONTENIDO -->
    <!-- TITULO -->
    <div class="row ">
        <div class="col text-left">
            <span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Grupos">
                <i class="fas fa-bookmark"></i> Grupos
            </span>
            <br>
            <div class=" badge badge-warning animated fadeInUp delay-3s text-white">
                <a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
                <i class="fas fa-angle-double-right"></i>
                <a style="color: black;" href="" title="Estás aquí">Grupos</a>
            </div>
            
        </div>
        
    </div>
    <!-- FIN TITULO -->

	<div class="row">

        <!-- COL  FILTROS -->
        <div class="col-md-3" style="display: none;" id="contenedor_filtros">

            
            <div class="stickyChida">
                <!-- CARD -->
                <div class="card z-depth-1 " style="border-radius: 20px;">
                    
                    <div class="card-body">
                      
                        <!-- ESTATUS -->
                        <span>
                            Estatus
                        </span>

                        <div>
                            
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input radiosEstatus" id="radioGeneraciones"  value="Vigente" checked="" name="radiosEstatus">
                                <label class="form-check-label letraPequena" for="radioGeneraciones">Vigente</label>
                            </div>


                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input radiosEstatus" id="radioGeneraciones2"  value="Vencido" name="radiosEstatus">
                                <label class="form-check-label letraPequena" for="radioGeneraciones2">Vencido</label>
                            </div>


                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input radiosEstatus" id="radioGeneraciones3"  value="Todos" name="radiosEstatus">
                                <label class="form-check-label letraPequena" for="radioGeneraciones3">Todos</label>
                            </div>

                        </div>
                        <!-- FIN ESTATUS -->

                        <!--  
                        <hr>
                        -->

                        <!-- PLANTELES -->
                        <span style="display: none;">
                            CDE
                        </span>

                        <div class=" scrollspy-example" style=" height: 200px; display: none;">
                            
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
                                    WHERE id_pla = '$plantel'
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

                    </div>
                    
                
                </div>
                <!-- FIN CARD -->
            </div>
            

        </div>
        <!-- FIN COL FILTROS -->

        <!-- DATA -->
        <div class="col-md-12" id="contenedor_datos" >

            <div class="main-card mb-3 card" style="border-radius: 20px;" >
                <div class="card-body">
                    
                    <ul class="nav nav-tabs letraPequena" id="myTab" role="tablist">
                        
                        <li class="nav-item">
                            <a class="nav-link active" href="#tab-eg10-0" data-toggle="tab" role="tab" aria-selected="true">Todos los grupos</a>
                        </li>

                       
                        <li class="nav-item">
                            <a  href="#tab-eg10-3" class="nav-link" id="agregarHorario" data-toggle="tab" role="tab" aria-selected="true">Gestión grupal</a>
                        </li>

                        <li class="nav-item">
                            <a  href="#tab-eg10-4" estatus="Inactivo" class="nav-link" id="btn_filtros" data-toggle="tab" role="tab" aria-selected="true">Filtros</a>
                        </li>



                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-eg10-0" role="tabpanel">
                            <div id="contenedor_horarios"></div>
                        </div>                     
                       
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN DATA -->
    
    </div>


    


<?php  

	include('inc/footer.php');

?>

<!-- FILTROS -->
<script>
    $('#btn_filtros').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */
        var estatus = $(this).attr('estatus');

        if ( estatus == 'Inactivo' ) {

            $('#contenedor_filtros').css('display', '');
            $('#contenedor_datos').removeClass('col-md-12').addClass('col-md-9');
            $('#btn_filtros').removeAttr('estatus').attr('estatus', 'Activo');

        } else if ( estatus == 'Activo' ) {

            $('#contenedor_filtros').css('display', 'none');
            $('#contenedor_datos').removeClass('col-md-9').addClass('col-md-12');
            $('#btn_filtros').removeAttr('estatus').attr('estatus', 'Inactivo');
        }

    });
    
</script>
<!-- FIN FILTROS -->


<script>


    const obtener_horarios = () => {

        estatusCiclo = $(".radiosEstatus:checked").val();

        var id_ram = [];

        for ( var i = 0, j = 0 ; i < $(".checkboxProgramas").length ; i++ ) {

            if ( $(".checkboxProgramas")[i].checked == true ) {
                // alert( "el checkbox numero: "+i+" con valor: "+$('.checkboxProgramas').eq(i).attr("annio")+" esta seleccionado"  );

                id_ram[j] = $('.checkboxProgramas').eq(i).val();

                j++;

            }
        }

        if ( id_ram.length == 0 ) {

            swal("¡No hay programas seleccionados!", "Selecciona al menos uno para continuar", "info", {button: "Aceptar",});
            
            // $("#contenedor_generaciones").html("");
            // $("#contenedor_principal").html("");

        } else {
            
            $.ajax({
                url: 'server/obtener_horarios.php',
                type: 'POST',
                data: { estatusCiclo, id_ram },
                success: function(respuesta){

                    $("#contenedor_horarios").html(respuesta);
                }
            });

        }

        
    }

</script>



<script>
    
    $("#seleccionPlanteles").on('click', function() {
    //event.preventDefault();
    /* Act on the event */

        //console.log( $(this)[0].checked );

        if ( $(this)[0].checked == true ) {
          // console.log("checkeado");
            $('.checkboxPlanteles').prop({checked: true});
            obtenerProgramas( obtener_horarios );
          
        }else{ 
          
            $('.checkboxPlanteles').prop({checked: false});
            obtenerProgramas( obtener_horarios );

        }

    //$('.seleccionAnniosMeses').prop({checked: false});
    });


    function obtenerProgramas( obtener_horarios ) {

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
                url: 'server/obtener_programas_plantel_grupos.php',
                type: 'POST',
                data: { id_pla },
                success: function( respuesta ){

                    $("#contenedor_programas_plantel").html( respuesta );

                    obtener_horarios();
                    
                              
                }

            });

        }

        
    }


    obtenerProgramas( obtener_horarios );


    $('.checkboxPlanteles').on('click', function() {
        //event.preventDefault();
        /* Act on the event */
        obtenerProgramas( obtener_horarios );
        

    });
</script>



<script>


    //INICIALIZACION DE SELECTORES

    $("#agregarHorario").on('click', function(event) {
        event.preventDefault();
        /* Act on the event */

        $("#modalHorario").modal('show');


        var id_ram = $("#selectorRama").val();

        obtener_ciclos( id_ram );

        // SELECTOR RAMA
        $("#selectorRama").on('change', function(event) {
            event.preventDefault();
            /* Act on the event */
            var id_ram = $(this).val();

            
            obtener_ciclos( id_ram );

            //alert(id_ram);
        });


        function obtener_ciclos(id_ram){
            $.ajax({
                url: 'server/obtener_ciclos_rama_horarios.php',
                type: 'POST',
                data: {id_ram},
                success: function(respuesta){
                
                    $("#contenedor_ciclos").html(respuesta);

                }
            });
        }
        //FIN FUNCION obtener_ciclos


        // AGREGAR CICLOS
        $("#btn_agregar_ciclo").on('click', function(event) {
            event.preventDefault();
            /* Act on the event */

            $.ajax({
                url: 'server/obtener_formulario_agregar_ciclo.php',
                type: 'POST',
                data: {id_ram},
                success: function(respuesta){
                
                    $("#contenedor_creacion_horario").html(respuesta);

                }
            });

        });




    });
</script>


<!-- MODAL CONSULTA GRUPO FUSIONADO -->
<div class="modal fade" id="modal_consulta_grupo">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_consulta_grupo"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: grey;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="contenedor_consulta_grupo">
                
            </div>

            <div class="modal-footer text-center">
                <button type="button" class="btn btn-info btn-sm" data-bs-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>
<!-- FIN MODAL CONSULTA GRUPO FUSIONADO -->




<!-- MODAL FORMULARIO GRUPO FUSIONADO -->
<div class="modal fade" id="modal_formulario_grupo_fusionado">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_formulario_grupo_fusionado"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: grey;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <div class="modal-body" id="contenedor_formulario_grupo_fusionado"></div>
            
        </div>
    </div>
</div>
<!-- FIN FORMULARIO GRUPO FUSIONADO -->


<!-- AGREGAR HORARIO MODAL -->

<div class="modal fade" id="modalHorario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

  <div class="modal-dialog modal-lg" role="document">


    <div class="modal-content">
      <div class="modal-header">
        <h4 id="myModalLabel">Gestión grupal</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: grey;">
            <span aria-hidden="true">&times;</span>
        </button>

      </div>
      <div class="modal-body" id="panzaModalHorario">

        <!-- TITULOS -->
        <div class="row letraPequena">

            <!-- PROGRAMAS -->
            <div class="col-md-4">
                
                <div class="row">
                    <div class="col-md-6 text-left">
                        Selecciona programa
                    </div>
                </div>
            </div>
            <!-- FIN PROGRAMAS -->

            <!-- CICLOS -->
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-6 text-left">
                        Selecciona ciclo escolar
                    </div>
                    <div class="col-md-6 text-right">
                        <a class="btn btn-sm btn-info letraPequena" id="btn_agregar_ciclo">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                </div>
                
            </div>
            <!-- FIN CICLOS -->
            
            <!-- GRUPOS -->
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-6 text-left">
                        Selecciona periodo
                    </div>
                    <div class="col-md-6 text-right" id="contenedor_btn_grupo">
                        
                    </div>
                </div>
                
            </div>
            <!-- FIN GRUPOS -->

        </div>
        <!-- FIN TITULOS -->

        <!-- SELECTORES -->
        <div class="row">
            <!-- PROGRAMAS -->
            <div class="col-md-4">
                <?php  
                    
                    $sqlRamas = "
                        SELECT * 
                        FROM rama 
                        INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
                        WHERE id_pla = '$plantel'
                    ";

                    $resultadoRamas = mysqli_query($db, $sqlRamas);
                    
                ?>
                <select id="selectorRama" class="browser-default custom-select letraPequena">
                    <?php
                        $validadorRama = true;
                        while($filaRamas = mysqli_fetch_assoc($resultadoRamas)){
                            if ( $validadorRama == true ) {
                    ?>
                                <option value="<?php echo $filaRamas['id_ram']; ?>" checked><?php echo $filaRamas["nom_ram"]." - ".$filaRamas['mod_ram'].' - '.$filaRamas['nom_pla']; ?></option>

                    <?php
                                $validadorRama = false;
                            }else{
                    ?>
                                <option value="<?php echo $filaRamas['id_ram']; ?>" mod_ram="<?php echo $filaRamas['mod_ram']; ?>"><?php echo $filaRamas["nom_ram"]." - ".$filaRamas['mod_ram'].' - '.$filaRamas['nom_pla']; ?></option>
                    <?php
                            }
                    ?>
                        
                    <?php
                        }
                    ?>
                </select>

            </div>
            <!-- FIN PROGRAMAS -->

            <!-- CICLOS -->
            <div class="col-md-4" id="contenedor_ciclos">
                
            </div>
            <!-- FIN CICLOS -->

            <!-- GRUPOS -->
            <div class="col-md-4" id="contenedor_grupos">
                
            </div>
            <!-- FIN GRUPOS -->
        </div>
        <!-- FIN SELECTORES -->

        <hr>
        
        <!-- CREACION HORARIO -->
        <div class="row">
            <div class="col-md-12"  id="contenedor_creacion_horario">
                
            </div>
        </div>
        <!-- FIN CREACION HORARIO -->
        


      </div>
      

    </div>
  </div>
</div>

<!-- FIN AGREGAR HORARIO MODAL -->

<script>
    $("#titulo_plataforma").html('<?php echo $lugar; ?> - Horarios');
</script>