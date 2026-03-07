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

    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">

                        <div class="page-title-icon">
                            <i class="pe-7s-note2 icon-gradient bg-premium-dark"></i>
                        </div>
                        
                        <div>
                            <h4>
                                <?php echo $datos['nom_enc']; ?>
                            </h4>

                            <div class="page-title-subheading">Resultados de encuestas</div>
                        </div>


                    </div>
                </div>
            </div>


            <nav class="" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="encuestas.php">Encuestas</a></li>
                    <li class="active breadcrumb-item" aria-current="encuestas.php"><?php echo $datos['nom_enc']; ?></li>
                </ol>
            </nav>

            <hr>

            <form id="formulario_encuesta">
                
                <div>

                    <input type="hidden" id="id_enc" name="id_enc" value="<?php echo $id_enc; ?>">

                    <span class="letraPequena grey-text">Título de la encuesta:</span>
                    <input type="text" class="form-control" value="<?php echo $datos['nom_enc']; ?>" autofocus="" id="nom_enc" name="nom_enc">
                        
                    <hr>
                    <span class="letraPequena grey-text">Instrucciones de la encuesta:</span>
                    <input type="text" class="form-control" value="<?php echo $datos['des_enc']; ?>" id="des_enc" name="des_enc">

                    <hr>

                    <div class="row">
                        
                        <div class="col-md-6">
                        
                            <span class="letraPequena grey-text">Selecciona la escala de aplicación:</span>
                            
                            <select id="selector_escala" name="selector_escala" class="form-control letraPequena">

                                <?php  
                                    if ( $datos['id_cad5'] != NULL ) {
                                ?>

                                        <option value="Cadena" selected="">Cadena <?php echo $nombreCadena; ?></option>
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
                                        <option value="Cadena">Cadena <?php echo $nombreCadena; ?></option>
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
                            <select id="tie_enc" name="tie_enc" class="form-control letraPequena">
                              
                                <option value="Inicio" <?php echo ( $datos['tie_enc'] == 'Inicio' )? 'selected=""': ''; ?>>Inicio</option>
                                <option value="Mitad" <?php echo ( $datos['tie_enc'] == 'Mitad' )? 'selected=""': ''; ?>>Mitad</option>
                                <option value="Final" <?php echo ( $datos['tie_enc'] == 'Final' )? 'selected=""': ''; ?>>Final</option>

                            </select>

                        </div>
                    
                    </div>

                    <hr>


                    <button class="btn btn-info btn-rounded  btn-block" title="Guardar cambios..." type="submit" id="btn_formulario_reactivo" >
                        Guardar
                    </button>

                    <hr>


                    <a href="#" id="btn_agregar_reactivo" class="btn btn-link text-primary">Agregar pregunta</a>
                    
                    <div id="contenedor_reactivos"></div>



                </div>
                
            </form>
            

        </div>
        
        <br>
        <br>
        <br>
        
    </div>




<?php  

    include('inc/footer.php');

?>


<script>
    setTimeout(function(){

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
                
            $.ajax({
                url: 'server/agregar_reactivo.php',
                type: 'POST', 
                data: { rea_rea, tip_rea, id_enc },

                success: function( respuesta ){
                    
                    console.log( respuesta );
                    $("#btn_formulario_reactivo").removeAttr('disabled').html('Guardar');
                    $('#modal_reactivo').modal('hide');

                    obtener_reactivos();

                }
            });

        });

    }, 500 ); 
</script>



<div class="modal fade text-left" id="modal_reactivo">
  <div class="modal-dialog modal-lg" role="document">
    
    <form id="formulario_reactivo">
        
        <div class="modal-content " style="border-radius: 20px;">
            <div class="modal-header text-center">
                <h5 class="modal-title">
                    Formulario pregunta
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
          
            <div class="modal-body mx-3">

                <div class="row">

                    <div class="col-md-12">
                        <input type="text" id="rea_rea" name="rea_rea" class="form-control letraPequena" placeholder="Pregunta:" required="">
                    </div>
                    
                </div>

                <hr>

                <div class="row">
                    
                    <div class="col-md-12">

                        <span class="letraPequena grey-text">Selecciona si es abierta o cerrada:</span>
                        <select id="tip_rea" name="tip_rea" class="form-control letraPequena">
                            <option value="Cerrado" selected="">Cerrada</option>
                            <option value="Abierto">Abierta</option>
                        </select>

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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
          
            <div class="modal-body mx-3">

                <div class="row">

                    <div class="col-md-12">

                        <input type="hidden" id="id_rea">
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