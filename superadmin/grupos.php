<?php  

	include('inc/header.php');
	
?>
    
    
    

	<div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">



                            <i class="pe-7s-users icon-gradient bg-premium-dark"></i>
                        </div>
                        <div>
                            GRUPOS
                            <div class="page-title-subheading">Grupos de <?php echo $nombreCadena; ?></div>
                        </div>
                    </div>
                </div>
            </div>


            <nav class="" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="active breadcrumb-item" aria-current="page">Grupos</li>
                </ol>
            </nav>

            <div class="main-card">


                <div class="row">


                    <!-- COL  FILTROS -->
                    <div class="col-md-3" style="display: none;" id="contenedor_filtros">

                        
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
                                   
                                    <span>
                                        Filtros
                                    </span>

                                    <hr>

                                    <!-- ESTATUS -->
                                    <span>
                                        Estatus
                                    </span>

                                    <div class=" scrollspy-example" style=" height: 40px;">
                                        
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

                                </div>
                                
                            
                            </div>
                            <!-- FIN CARD -->
                        </div>
                        

                    </div>
                    <!-- FIN COL FILTROS -->

                    <!-- DATA -->
                    <div class="col-md-12" id="contenedor_datos">

                        <div class="main-card mb-3 card" >
                            <div class="card-body">
                                
                                <ul class="nav nav-tabs">
                                    
                                    <li class="nav-item">
                                        <a data-bs-toggle="tab" href="#tab-eg10-0" class="active nav-link">Todos los grupos</a>
                                    </li>
                                    
                                    <li class="nav-item">
                                        <a data-bs-toggle="tab" href="#tab-eg10-1" class="nav-link">Grupos fusionados</a>
                                    </li>

                                    <li class="nav-item">
                                        <a data-bs-toggle="tab" href="#tab-eg10-2" class="nav-link" id="btn_fusion_grupal_nueva">Fusión grupal nueva</a>
                                    </li>

                                    <li class="nav-item">
                                        <a data-bs-toggle="tab" href="#tab-eg10-3" class="nav-link" id="btn_fusion_grupal_iniciados">Fusión grupal iniciados</a>
                                    </li>


                                    <li class="nav-item">
                                        <a data-bs-toggle="tab" href="#tab-eg10-3" class="nav-link" id="agregarHorario">Gestión grupal</a>
                                    </li>

                                    <li class="nav-item">
                                        <a data-bs-toggle="tab" href="#tab-eg10-4" estatus="Inactivo" class="nav-link" id="btn_filtros">Filtros</a>
                                    </li>



                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab-eg10-0" role="tabpanel">
                                        <div id="contenedor_horarios"></div>
                                    </div>


                                    <div class="tab-pane" id="tab-eg10-1" role="tabpanel">
                                        
                                        <div id="contenedor_grupos_fusionados"></div>

                                    </div>

                                 
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FIN DATA -->
                
                </div>
                

                
                
            </div>
        </div>
        
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


        $('#contenedor_horarios').html('<h3 class="text-center grey-text" style="position: static; right: 45%; top: 50%; z-index: 99999;"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>');

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



    // obtener_grupos_fusionados();
    const obtener_grupos_fusionados = () => {

        $('#contenedor_grupos_fusionados').html('<h3 class="text-center grey-text" style="position: static; right: 45%; top: 50%; z-index: 99999;"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>');

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
                url: 'server/obtener_grupos_fusionados.php',
                type: 'POST',
                data: { estatusCiclo, id_ram },
                success: function(respuesta){

                    $("#contenedor_grupos_fusionados").html( respuesta );
                }
            });

        }


        
    }


    function obtener_fusion_grupal_nueva(){
        $.ajax({
            url: 'server/obtener_fusion_grupal_nueva.php',
            type: 'POST',
            success: function( respuesta ){

                $('#titulo_formulario_grupo_fusionado').text( 'Fusión grupal nueva' );
                $('#modal_formulario_grupo_fusionado').modal('show');
                $('#contenedor_formulario_grupo_fusionado').html( respuesta );
            }
        });
    }


    $('#btn_fusion_grupal_nueva').on('click', function(event) {
        event.preventDefault();

        obtener_fusion_grupal_nueva();
        

    });


    function obtener_fusion_grupal_iniciados(){
        $.ajax({
            url: 'server/obtener_fusion_grupal_iniciados.php',
            type: 'POST',
            success: function( respuesta ){

                $('#titulo_formulario_grupo_fusionado').text( 'Fusión grupal nueva' );
                $('#modal_formulario_grupo_fusionado').modal('show');
                $('#contenedor_formulario_grupo_fusionado').html( respuesta );
            }
        });
    }


    $('#btn_fusion_grupal_iniciados').on('click', function(event) {
        event.preventDefault();

        obtener_fusion_grupal_iniciados();
        

    });
</script>



<script>
    
    $("#seleccionPlanteles").on('click', function() {
    //event.preventDefault();
    /* Act on the event */

        //console.log( $(this)[0].checked );

        if ( $(this)[0].checked == true ) {
          // console.log("checkeado");
            $('.checkboxPlanteles').prop({checked: true});
            obtenerProgramas( obtener_horarios, obtener_grupos_fusionados );
          
        }else{ 
          
            $('.checkboxPlanteles').prop({checked: false});
            obtenerProgramas( obtener_horarios, obtener_grupos_fusionados );

        }

    //$('.seleccionAnniosMeses').prop({checked: false});
    });


    function obtenerProgramas( obtener_horarios, obtener_grupos_fusionados ) {

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
                    obtener_grupos_fusionados();
                              
                }

            });

        }

        
    }


    obtenerProgramas( obtener_horarios, obtener_grupos_fusionados );


    $('.checkboxPlanteles').on('click', function() {
        //event.preventDefault();
        /* Act on the event */
        obtenerProgramas( obtener_horarios, obtener_grupos_fusionados );
        

    });
</script>


<script>
    const eliminacion_fusion = ( id_fus, nom_fus ) => {
        swal({
          title: "¿Deseas eliminar "+nom_fus+"?",
          text: "¡Una vez eliminado se perderán todos los datos relacionados a ese registro!",
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
                url: 'server/eliminacion_fusion.php',
                type: 'POST',
                data: { id_fus },
                success: function(respuesta){
                    
                    console.log( respuesta );
                    if (respuesta == "Exito") {
                        console.log("Exito en consulta");
                        swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
                        then((value) => {

                          obtener_grupos_fusionados();
                          obtener_horarios();
                        
                        });

                    }else{
                        
                        console.log(respuesta);

                    }

                }
            });
            
          }
        });
    }
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
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
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
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
                            Nuevo ciclo
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
                        WHERE id_cad1 = '$cadena'
                    ";

                    $resultadoRamas = mysqli_query($db, $sqlRamas);
                    
                ?>
                <select id="selectorRama" class="form-control letraPequena">
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