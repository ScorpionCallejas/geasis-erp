<?php  

	include('inc/header.php');
	
?>

    
    <!-- TITULO -->
    <div class="row ">
        <div class="col text-left">
            <span class="tituloPagina animated fadeInUp badge blue-grey darken-4 hoverable" title="Encuestas">
                <i class="fas fa-bookmark"></i> Encuestas
            </span>
            <br>
            <div class=" badge badge-warning animated fadeInUp text-white">
                <a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
                <i class="fas fa-angle-double-right"></i>
                <a style="color: black;" href="" title="Estás aquí">Encuestas</a>
            </div>
            
        </div>
        
    </div>
    <!-- FIN TITULO -->

    <hr>

    <a href="#" id="btn_agregar_encuesta" class="btn btn-info waves-effect btn-sm btn-rounded">Agregar encuesta</a>

    <div class="row">
            
        <?php  
            $sql = "
                SELECT id_enc, est_enc, nom_enc, des_enc, tie_enc, id_cad5 AS selector_escala, nom_cad AS nombre_escala, ini_enc AS ini_enc, fin_enc AS fin_enc, obtener_participantes_encuesta( id_enc ) AS total
                FROM encuesta
                INNER JOIN cadena ON cadena.id_cad = encuesta.id_cad5
                WHERE id_cad5 = '$cadena'
                UNION
                SELECT id_enc, est_enc, nom_enc, des_enc, tie_enc, id_pla7 AS selector_escala, nom_pla AS nombre_escala, ini_enc AS ini_enc, fin_enc AS fin_enc, obtener_participantes_encuesta( id_enc ) AS total
                FROM encuesta
                INNER JOIN plantel ON plantel.id_pla = encuesta.id_pla7
                WHERE id_cad1 = '$cadena'
                ORDER BY id_enc DESC
            ";

            // echo $sql;

            $resultado = mysqli_query( $db, $sql );

            $contador = 1;

            while( $fila = mysqli_fetch_assoc( $resultado ) ){
                $id_enc = $fila['id_enc'];
        ?>


                <div class="col-md-4 p-2">
                    

                    <div class="card" style="border-radius: 20px; position: relative;">

                        <div class="card-body">
                            <!--  -->
                            <i class="fas fa-clipboard-list fa-2x grey-text"></i>
                    
                                <a href="consulta_encuesta.php?id_enc=<?php echo $fila['id_enc']; ?>" class="btn-link letraGrande">
                                    <?php echo $fila['nom_enc']; ?>
                                </a>
                        
                            <hr>


                            <i class="fas fa-university fa-2x grey-text"></i>
                            <strong class="grey-text">
                                <?php echo $fila['nombre_escala']; ?>
                            </strong>
                            <br>
                            <strong class="letraMediana">
                                Participantes: <span class="letraGrande"><?php echo $fila['total']; ?></span>
                            </strong>
                            <br>
                            <span class="letraPequena">
                                Periodo de aplicación: <?php echo $fila['tie_enc']; ?>
                            </span>
                            <br>

                            <span class="letraPequena">
                                Total preguntas: 
                                <?php
                                    $sqlPreguntas = "
                                        SELECT *
                                        FROM reactivo
                                        WHERE id_enc1 = '$id_enc'
                                    ";  

                                    echo obtener_datos_consulta( $db, $sqlPreguntas )['total'];
                                ?>
                            </span>


                            <br>
                            <span class="letraPequena grey-text">
                                <span class="text-success letraPequena">Activa:</span> <span id="inicio_encuesta<?php echo $fila['id_enc']; ?>"><?php echo fechaFormateadaCompacta2( $fila['ini_enc'] ); ?></span>
                                <span class="text-danger letraPequena">Inactiva:</span>  <span id="fin_encuesta<?php echo $fila['id_enc']; ?>"><?php echo fechaFormateadaCompacta2( $fila['fin_enc'] ); ?></span>
                            </span>

                            <a href="#" class="eliminacionEncuesta text-danger" id_enc="<?php echo $fila['id_enc']; ?>" nom_enc="<?php echo $fila['nom_enc']; ?>" style="position: absolute; top: 10px; right: 20px;" title="Eliminar encuesta...">
                                <i class="fas fa-times"></i>
                            </a>


                            <a href="#" class="copiarEncuesta grey-text btn-link" id_enc="<?php echo $fila['id_enc']; ?>" nom_enc="<?php echo $fila['nom_enc']; ?>" style="position: absolute; top: 10px; right: 40px;" title="Copiar encuesta...">
                                <i class="fas fa-clone"></i> Copiar
                            </a>
                        

                            <?php  
                                if ( $fila['est_enc'] == 'Activo' ) {
                            ?>
                                    
                                <a class="btn btn-sm btn-success switch_encuesta btn-rounded" id_enc="<?php echo $fila['id_enc']; ?>" estatus="<?php echo $fila['est_enc']; ?>" title="Activa/Desactiva la encuesta..." style="position: absolute; bottom: 10px; right: 20px;">
                                    Activa
                                </a>

                            <?php
                                } else if ( $fila['est_enc'] == 'Inactivo' ) {
                            ?>

                                    <a class="btn btn-sm btn-danger switch_encuesta btn-rounded" id_enc="<?php echo $fila['id_enc']; ?>" estatus="<?php echo $fila['est_enc']; ?>" title="Activa/Desactiva la encuesta..." style="position: absolute; bottom: 10px; right: 20px;">
                                        Inactiva
                                    </a>

                            <?php
                                }
                            ?>
                            <!--  -->
                        </div>
                        


                    </div>
                        
                </div>


                <?php
                        if ( $contador % 3 == 0 ) {
                         
                ?>
                        </div>


                        <div class="row">

                <?php
                        }
                ?>

                
                

        <?php
            }
        ?>



    <br>
    <br>
<?php  

	include('inc/footer.php');

?>


<script>
    $('#btn_agregar_encuesta').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */

        $('#modal_encuesta').modal('show');

    });

    setTimeout(function(){
        // 
        $('#formulario_encuesta').on('submit', function(event) {
            event.preventDefault();
            /* Act on the event */

            $("#btn_formulario_encuesta").attr('disabled','disabled').html('<i class="fas fa-cog fa-spin"></i> Guardando...');

            var tie_enc = $('#tie_enc option:selected').val();
            var selector_escala = $('#selector_escala option:selected').val();
            var nom_enc = $('#nom_enc').val();
                
            $.ajax({
                url: 'server/agregar_encuesta.php',
                type: 'POST', 
                data: { nom_enc, tie_enc, selector_escala },

                success: function( respuesta ){
                    
                    console.log( respuesta );

                    window.location.href = 'consulta_encuesta.php?id_enc='+respuesta;


                    
                }
            });

        });




        // SWITCH ENCUESTA

        $(".switch_encuesta").on('click', function(event) {
            event.preventDefault();
            /* Act on the event */
            var elemento = $(this);
            var id_enc = $(this).attr( "id_enc" );
            var estatus = $(this).attr( "estatus" );
            // alert( id_enc );

            $.ajax({
                url: 'server/editar_encuesta.php',
                type: 'POST',
                dataType: 'json',
                data: { id_enc, estatus },

                success: function ( respuesta ) {

                    console.log( respuesta );
                    // if ( respuesta == 'Exito' ) {
                        
                    if ( estatus == 'Activo' ) {

                        elemento.removeClass('btn-success').addClass('btn-danger').removeAttr('estatus').attr( 'estatus', 'Inactivo' ).text('Inactiva');;
                        
                        toastr.error('La encuesta ha sido desactivada :(');

                        $('#inicio_encuesta'+id_enc).text( respuesta.ini_enc );
                        $('#fin_encuesta'+id_enc).text( respuesta.fin_enc );

                        // obtenerAlumnosGeneraciones();

                    } else if ( estatus == 'Inactivo' ) {

                        elemento.removeClass('btn-danger').addClass('btn-success').removeAttr('estatus').attr( 'estatus', 'Activo' ).text('Activa');
                        
                        toastr.success('La encuesta ha sido activada :D');

                        $('#inicio_encuesta'+id_enc).html( respuesta.ini_enc );
                        $('#fin_encuesta'+id_enc).html( respuesta.fin_enc );

                        // obtenerAlumnosGeneraciones();

                    }
                    // }
                }
            });
            
        });
        // FIN SWITCH ENCUESTA



        // ELIMINACION ENCUESTA
        const eliminacion_encuesta = ( id_enc, nom_enc ) => {
            swal({
              title: "¿Deseas eliminar "+nom_enc+"?",
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
                    url: 'server/eliminacion_encuesta.php',
                    type: 'POST',
                    data: { id_enc },
                    success: function(respuesta){
                        
                        console.log( respuesta );
                        swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
                        then((value) => {

                            window.location.reload();
                        
                        });

                    }
                });
                
              }
            });
        }

        $('.eliminacionEncuesta').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */

            var id_enc = $(this).attr("id_enc");
            var nom_enc = $(this).attr("nom_enc");

            eliminacion_encuesta( id_enc, nom_enc );

        });
        // FIN ELIMINACION ENCUESTA


        // COPIAR ENCUESTA
        $('.copiarEncuesta').on('click', function(event) {
            event.preventDefault();
            /* Act on the event */
            var nom_enc = $(this).attr('nom_enc');
            var id_enc = $(this).attr('id_enc');

            swal({
              title: "¿Deseas clonar "+nom_enc+"?",
              text: "¡Una vez aceptes clonarás el contenido de esta encuesta a una nueva y podrás editarla!",
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
                            className: "",
                            closeModal: true
                          }
                        },
              dangerMode: true,
            }).then((willDelete) => {
              if (willDelete) {
                //ELIMINACION ACEPTADA


                $.ajax({
                    url: 'server/obtener_copia_encuesta.php',
                    type: 'POST',
                    data: { id_enc },
                    success: function( id_enc ){

                        // 

                        swal("Encuesta copiada correctamente", "Continuar", "success", {button: "Aceptar",}).
                        then((value) => {
                            
                            window.location.href = 'consulta_encuesta.php?id_enc='+id_enc;  

                        });
                        
                        // 


                    }
                });
                
                
              }
            });

            
            

        });
        // FIN ENCUESTA


        // 
    }, 1000 );

    


</script>

<!-- MODALES -->

<div class="modal fade text-left" id="modal_encuesta">
  <div class="modal-dialog modal-lg" role="document">
    
    <form id="formulario_encuesta">
        
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header text-center">
                <h5 class="modal-title">
                    Formulario encuesta
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
          
            <div class="modal-body mx-3">

                

                <div class="row">

                    <div class="col-md-12">
                        <input type="text" id="nom_enc" name="nom_enc" class="form-control" placeholder="Título de encuesta:" required="">
                    </div>
                    
                </div>

                <hr>

                <div class="row">
                    
                    <div class="col-md-6">
                    
                        <span class="letraPequena grey-text">Selecciona la escala de aplicación:</span>
                        
                        <select id="selector_escala" name="selector_escala" class="browser-default custom-select letraPequena">
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
                        </select>                    
                    
                    </div>

                    <div class="col-md-6">

                        <span class="letraPequena grey-text">Selecciona periodo de aplicación:</span>
                        <select id="tie_enc" name="tie_enc" class="browser-default custom-select letraPequena">
                            <option value="Inicio" selected="">Inicio</option>
                            <option value="Mitad">Mitad</option>
                            <option value="Final">Final</option>
                        </select>

                    </div>
                
                </div>
                

            </div>

            <div class="modal-footer d-flex justify-content-center">
                
                <button class="btn btn-info btn-rounded  btn-sm" title="Guardar cambios..." type="submit" id="btn_formulario_encuesta">
                    Guardar
                </button>

            </div>

        </div>
    </form>

  </div>

</div>

<!-- FIN MODALES -->


<script>
    $("#titulo_plataforma").html('<?php echo $lugar; ?> - Encuestas');
</script>