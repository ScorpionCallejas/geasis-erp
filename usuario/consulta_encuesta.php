<?php  

    include('inc/header.php');
    $id_enc = $_GET['id_enc'];

    $sql = "
        SELECT *
        FROM encuesta
        WHERE id_enc = '$id_enc'
    ";

    $datos = obtener_datos_consulta( $db, $sql )['datos'];
?>
    <!-- FUENTES -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap');

        .fuente_encuestas {
            font-family: 'Roboto Condensed', sans-serif;
        }

        .fixed-width-table {
            table-layout: fixed;
            width: 100%;
        }
          
        .fixed-width-table th,
        .fixed-width-table td {
            text-align: left;
            padding: 8px;
            white-space: normal;
            word-wrap: break-word;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
    <!-- FIN FUENTES -->

    <!-- TITULO -->
    <div class="row ">
        <div class="col text-left">
            <span class="tituloPagina animated fadeInUp badge blue-grey darken-4 hoverable" title="Consulta de encuesta">
                <i class="fas fa-bookmark"></i> Consulta de encuesta
            </span>
            <br>
            <div class=" badge badge-warning animated fadeInUp text-white">
                <a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
                <i class="fas fa-angle-double-right"></i>
                <a href="encuestas.php" title="Encuestas"><span class="text-white">Encuestas</span></a>
                <i class="fas fa-angle-double-right"></i>
                <a style="color: black;" href="" title="Estás aquí"><?php echo $datos['nom_enc']; ?></a>
            </div>
            
        </div>
        
    </div>
    <!-- FIN TITULO -->

    <hr>

    <form id="formulario_encuesta">
        
        <div>

            <input type="hidden" id="id_enc" name="id_enc" value="<?php echo $id_enc; ?>">

            <span class="letraPequena grey-text">Título de la encuesta:</span>
            <input type="text" class="form-control" value="<?php echo $datos['nom_enc']; ?>" autofocus="" id="nom_enc" name="nom_enc">
                
            <hr>
            <span class="letraPequena grey-text">Instrucciones de la encuesta:</span>

            <textarea class="form-control letraPequena" value="<?php echo $datos['des_enc']; ?>" id="des_enc" name="des_enc" rows="3"><?php echo $datos['des_enc']; ?></textarea>

            <hr>

            <div class="row">
                
                <div class="col-md-6">
                
                    <span class="letraPequena grey-text">Selecciona la escala de aplicación:</span>
                    
                    <select id="selector_escala" name="selector_escala" class="browser-default custom-select letraPequena">

                        <?php  
                            if ( $datos['id_cad5'] != NULL ) {
                        ?>

                                <option value="Cadena" selected="">Cadena <?php echo $lugar; ?></option>
                                <?php  
                                    $sqlPlanteles = "
                                        SELECT *
                                        FROM plantel
                                        WHERE id_cad1 = '$cadena'
                                    ";

                                    $resultadoPlanteles = mysqli_query( $db, $sqlPlanteles );

                                    while( $filaPlanteles = mysqli_fetch_assoc( $resultadoPlanteles ) ){
                                ?>
                                        <option value="<?php echo $filaPlanteles['id_pla']; ?>">
                                            <?php echo $filaPlanteles['nom_pla']; ?>    
                                        </option>
                                <?php
                                    }
                                ?>
                        <?php
                            } else {
                        ?>
                                <option value="Cadena">Cadena <?php echo $lugar; ?></option>
                                <?php  
                                    $sqlPlanteles = "
                                        SELECT *
                                        FROM plantel
                                        WHERE id_cad1 = '$cadena'
                                    ";

                                    $resultadoPlanteles = mysqli_query( $db, $sqlPlanteles );

                                    while( $filaPlanteles = mysqli_fetch_assoc( $resultadoPlanteles ) ){
                                ?>

                                        <?php  
                                            if ( $datos['id_pla7'] == $filaPlanteles['id_pla'] ) {
                                        ?>
                                                <option selected="" value="<?php echo $filaPlanteles['id_pla']; ?>">
                                        <?php
                                            } else {
                                        ?>
                                                <option value="<?php echo $filaPlanteles['id_pla']; ?>">
                                        
                                        <?php
                                            }
                                        ?>
                                            <?php echo $filaPlanteles['nom_pla']; ?>    
                                        </option>
                                <?php
                                    }
                                ?>

                        <?php
                            }
                        ?>
                        

                        
                    </select>                    
                
                </div>

                <div class="col-md-6">

                    <span class="letraPequena grey-text">Selecciona periodo de aplicación:</span>
                    <select id="tie_enc" name="tie_enc" class="browser-default custom-select letraPequena">
                      
                        <option value="Inicio" <?php echo ( $datos['tie_enc'] == 'Inicio' )? 'selected=""': ''; ?>>Inicio</option>
                        <option value="Mitad" <?php echo ( $datos['tie_enc'] == 'Mitad' )? 'selected=""': ''; ?>>Mitad</option>
                        <option value="Final" <?php echo ( $datos['tie_enc'] == 'Final' )? 'selected=""': ''; ?>>Final</option>

                    </select>

                </div>
            
            </div>

            <hr>


            <button class="btn btn-info waves-effect btn-sm btn-rounded letraGrande" title="Guardar cambios..." type="submit" id="btn_formulario_reactivo" >
                Guardar encuesta
            </button>

            <hr>


            <a href="#" id="btn_agregar_reactivo" class="btn btn-info waves-effect btn-sm btn-rounded letraPequena" style="position: fixed; right: 5%; bottom: 10%; z-index: 99;">AGREGAR ELEMENTO</a>
            
            <div class="row">

                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div id="contenedor_reactivos" class="fuente_encuestas"></div>
                </div>
                <div class="col-md-2"></div>
            </div>



        </div>
        
    </form>




<?php  

    include('inc/footer.php');

?>


<script>

    setTimeout(function(){

        var id_enc = <?php echo $id_enc; ?>;

        obtener_encuesta_titulo();

        $('#nom_enc').on('keyup', function(event) {
            event.preventDefault();
            /* Act on the event */
            obtener_encuesta_titulo();
        });

        function obtener_encuesta_titulo(){
            setTimeout(function(){
                $('#contenedor_encuesta_titulo').html( $('#nom_enc').val() );
            }, 300);
        }


        //////////////////////////////////////////////////////////////////////////
        obtener_encuesta_instrucciones();

        $('#des_enc').on('keyup', function(event) {
            event.preventDefault();
            /* Act on the event */
            obtener_encuesta_instrucciones();
        });

        function obtener_encuesta_instrucciones(){
            setTimeout(function(){
                $('#contenedor_encuesta_instrucciones').html( $('#des_enc').val() );
            }, 300);
        }
        ////////////////////////////////////////////////////

        $('#formulario_encuesta').on('submit', function(event) {
            event.preventDefault();
            /* Act on the event */

            $("#btn_formulario_encuesta").attr('disabled','disabled').html('<i class="fas fa-cog fa-spin"></i> Guardando...');

            var tie_enc = $('#tie_enc option:selected').val();
            var selector_escala = $('#selector_escala option:selected').val();
            var nom_enc = $('#nom_enc').val();
            var des_enc = $('#des_enc').val();

            var id_enc = $('#id_enc').val();

            $.ajax({
                url: 'server/editar_encuesta.php',
                type: 'POST',
                data: { id_enc, tie_enc, selector_escala, nom_enc, des_enc },
                success: function( respuesta ){
                    console.log( respuesta );

                    $("#btn_formulario_encuesta").removeAttr('disabled').html('Guardar');
                    toastr.success('Cambios guardados ;D');
                }
            });

        });
        

        

        obtener_reactivos();

        // DESPLIEGUE REACTIVOS
        function obtener_reactivos(){

            var id_enc = <?php echo $id_enc; ?>;

            $.ajax({
                url: 'server/obtener_reactivos.php',
                type: 'POST',
                data: { id_enc },
                success: function( respuesta ){
                    $('#contenedor_reactivos').html( respuesta );
                }
            });
            
        }
        // FIN DESPLIEGUE REACTIVOS
        $('#btn_agregar_reactivo').on('click', function(event) {
            event.preventDefault();
            /* Act on the event */

            $('#modal_reactivo').modal('show');

        });

        $('#formulario_reactivo').on('submit', function(event) {
            event.preventDefault();
            /* Act on the event */

            $("#btn_formulario_reactivo").attr('disabled','disabled').html('<i class="fas fa-cog fa-spin"></i> Guardando...');

            var tip_rea = $('#tip_rea option:selected').val();
            var rea_rea = $('#rea_rea').val();
            var id_enc = <?php echo $id_enc; ?>;

            var for_rea = $('#for_rea').val();

            // alert(for_rea);
            if ( tip_rea == 'Organigrama' ) {
                for_rea = 'Organigrama';
            } else {

                if ( for_rea == undefined ) {
                    for_rea = '';
                }    
            }
            

            // alert(for_rea);
                
            $.ajax({
                url: 'server/agregar_reactivo.php',
                type: 'POST', 
                data: { rea_rea, tip_rea, id_enc, for_rea },

                success: function( respuesta ){
                    
                    console.log( respuesta );
                    $("#btn_formulario_reactivo").removeAttr('disabled').html('Guardar');
                    $("#rea_rea").val("");
                    $('#modal_reactivo').modal('hide');

                    obtener_reactivos();
                    obtener_formulario_reactivo( id_enc );

                }
            });

        });

        $('#tip_rea').on('change', function(event) {
            /* Act on the event */

            if ( ( $('#tip_rea option:selected').val() == 'Tabla' ) || ( $('#tip_rea option:selected').val() == 'Profesores' ) ) {
                 // alert('tabla');
                obtener_formulario_reactivo( id_enc );
                
            } else {
                $('#contenedor_agrupacion').html('');
            }

        });


        function obtener_formulario_reactivo( id_enc ){
            //alert('funcs');
            $.ajax({
                url: 'server/obtener_formulario_reactivo.php',
                type: 'POST', 
                data: { id_enc },

                success: function( respuesta ){
                    
                    // console.log( respuesta );
                    $('#contenedor_agrupacion').html( respuesta );

                }
            });
            
        }

    }, 300);

</script>


<script>
    obtener_tabla_encuesta();
    function obtener_tabla_encuesta(){
        var id_enc = <?php echo $id_enc; ?>;

        $.ajax({
            url: 'server/obtener_tabla_encuesta.php',
            type: 'POST',
            data: { id_enc },
            success: function( respuesta ){
                $('#contenedor_tabla_encuesta').html( respuesta );
            }
        });
    }
</script>


<div class="modal fade text-left" id="modal_reactivo">
  <div class="modal-dialog modal-lg" role="document">
    
    <form id="formulario_reactivo">
        
        <div class="modal-content " style="border-radius: 20px;">
            <div class="modal-header text-center">
                <h5 class="modal-title">
                    Formulario
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
          
            <div class="modal-body mx-3">

                <div class="row">

                    <div class="col-md-12">
                        <input type="text" id="rea_rea" name="rea_rea" class="form-control letraPequena" placeholder="Agregar:" required="">
                    </div>
                    
                </div>

                <hr>

                <div class="row">
                    
                    <div class="col-md-12">

                        <span class="letraPequena grey-text">Selecciona el elemento:</span>
                        <select id="tip_rea" name="tip_rea" class="browser-default custom-select letraPequena">
                            <option value="Cerrado" selected="">Cerrada</option>
                            <option value="Multiple">Multiple</option>
                            <option value="Tabla">Tabla</option>
                            <option value="Organigrama">Organigrama</option>
                            <option value="Profesores">Profesores</option>
                            <option value="Abierto">Abierta</option>
                            <option value="Texto">Texto</option>
                        </select>

                    </div>
                
                </div>
                
                <hr>

                <div class="row">   
                    <div class="col-md-12" id="contenedor_agrupacion">
                        
                    </div>
                </div>          

            </div>

            <div class="modal-footer d-flex justify-content-center">
                
                <button class="btn btn-info btn-rounded  btn-sm" title="Guardar cambios..." type="submit" id="btn_formulario_reactivo">
                    Guardar
                </button>

            </div>

        </div>
    </form>

  </div>

</div>



<div class="modal fade text-left" id="modal_opcion">
  <div class="modal-dialog modal-lg" role="document">
    
    <form id="formulario_opcion">
        
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header text-center">
                <h5 class="modal-title" id="titulo_reactivo">
                    Formulario respuesta
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
          
            <div class="modal-body mx-3">

                <div class="row">

                    <div class="col-md-12">

                        <input type="hidden" id="id_rea">
                        <input type="hidden" id="forma_reactivo">
                        <input type="text" id="opc_opc" name="opc_opc" class="form-control letraPequena" placeholder="Respuesta:" required="">
                    </div>
                    
                </div>

            </div>

            <div class="modal-footer d-flex justify-content-center">
                
                <button class="btn btn-info btn-rounded  btn-sm" title="Guardar cambios..." type="submit" id="btn_formulario_opcion">
                    Guardar
                </button>

            </div>

        </div>
    </form>

  </div>

</div>