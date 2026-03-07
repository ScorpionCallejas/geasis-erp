<?php  

	include('inc/header.php');
	
?>

	<div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">

                        <div class="page-title-icon">
                            <i class="pe-7s-user icon-gradient bg-premium-dark"></i>
                        </div>
                        
                        <div>
                            Usuarios
                            <div class="page-title-subheading">Listado de Usuarios</div>
                        </div>


                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    
                    <div class="card-body">
                        
                        <div class="input-group">
                            
                            <a href="#" class="btn btn-primary" id="btn_usuarios" estatus="Agregar">
                                <i class="fas fa-lock"></i> 
                                Guardar usuario
                            </a>

                            <input type="text" class="form-control" id="nom_usu" value="" placeholder="Nombre:" required="">

                            <input type="text" class="form-control" id="cor_usu" value="" placeholder="Correo:" required="">

                            <input type="text" class="form-control" id="pas_usu" value="" placeholder="Contraseña:" required="">
                            
                            <input type="hidden" id="id_usu">

                        </div>

                        <br>

                        <div class="input-group">
                            
                            <select class="form-control" id="selector_usuario">
                                <option value="Super" selected="">Super-administrador</option>
                                <option value="Admin">Coordinador de CDE</option>
                                <option value="Adminge">Coordinador académico</option>
                                <option value="Caja">Servicios empresariales</option>
                            </select>


                            <select class="form-control" id="selector_plantel">

                                <?php  
                                    $sqlPlanteles = "
                                        SELECT *
                                        FROM plantel
                                        WHERE id_cad1 = '$cadena'              
                                    ";

                                    // echo $sqlPlanteles;

                                    $resultadoPlanteles = mysqli_query( $db, $sqlPlanteles );
                                    $contador = 1;
                                    while( $filaPlanteles = mysqli_fetch_assoc( $resultadoPlanteles ) ){
                                ?>
                                        <?php  
                                            if ( $contador == 1 ) {
                                        ?>
                                                <option value="Cadena" selected="">Cadena</option>
                                        <?php
                                            } else {
                                        ?>
                                                <option value="<?php echo $filaPlanteles['id_pla']; ?>"><?php echo $filaPlanteles['nom_pla']; ?></option>
                                        <?php
                                            }
                                        ?>

                                <?php
                                        $contador++;
                                    }
                                ?>
                            </select>

                        </div>
                    
                    </div>

                </div>
            </div>

            <hr>

            <div id="contenedor_datos_usuarios">
            </div>

        </div>
        
    </div>


<?php  

	include('inc/footer.php');

?>

<script>
    function obtener_datos_usuarios(){
        $.ajax({
            url: 'server/obtener_datos_usuarios.php',
            type: 'POST',
            success: function( respuesta ){
                $('#contenedor_datos_usuarios').html( respuesta );
            }
        });
        
    }


    obtener_datos_usuarios();

</script>

<script>
    
    $('#btn_usuarios').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */

        if ( $('#nom_usu').val() != '' && $('#cor_usu').val() != ''  && $('#pas_usu').val() != '' ) {
            
            var estatus = $('#btn_usuarios').attr('estatus');

            if ( estatus == 'Agregar' ) {

                obtener_validacion_superadmin( agregar_usuario );

            } else if ( estatus == 'Editar' ) {

                agregar_usuario();
            
            }

        } else {

            toastr.error('¡Faltan datos!');
            $("#nom_usu").focus();
        }
        
        
    });


    const agregar_usuario = () => {

        var estatus = $('#btn_usuarios').attr('estatus');
        var nom_usu = $('#nom_usu').val();
        var cor_usu = $('#cor_usu').val();
        var pas_usu = $('#pas_usu').val();

        var tip_usu = $('#selector_usuario option:selected').val();
        var selector_plantel = $('#selector_plantel option:selected').val();

        if ( estatus == 'Agregar' ) {
            
            $.ajax({
                url: 'server/agregar_usuario.php',
                type: 'POST',
                data: { nom_usu, cor_usu, pas_usu, tip_usu, selector_plantel },
                success: function( respuesta ){
                    console.log( respuesta );

                    $('#nom_usu').val('');
                    $('#cor_usu').val('');
                    $('#pas_usu').val('');

                    $('#btn_usuarios').removeAttr('estatus').attr('estatus', 'Agregar');

                    obtener_datos_usuarios();
                }
            });

        } else if ( estatus == 'Editar' ) {

            var id_usu = $('#id_usu').val();

            $.ajax({
                url: 'server/editar_usuario.php',
                type: 'POST',
                data: { id_usu, nom_usu, cor_usu, pas_usu, tip_usu, selector_plantel },
                success: function( respuesta ){
                    console.log( respuesta );

                    $('#nom_usu').val('');
                    $('#cor_usu').val('');
                    $('#pas_usu').val('');
                    $('#id_usu').val('');

                    $('#btn_usuarios').removeAttr('estatus').attr('estatus', 'Agregar');

                    obtener_datos_usuarios();
                }
            });
        }

    
        
    }
</script>

<script>
    const eliminacion_usuario = ( id_usu, nom_usu ) => {
        swal({
          title: "¿Deseas eliminar "+nom_usu+"?",
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
                url: 'server/eliminacion_usuario.php',
                type: 'POST',
                data: { id_usu },
                success: function(respuesta){
                    
                    console.log( respuesta );
                    if (respuesta == "Exito") {
                        console.log("Exito en consulta");
                        swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
                        then((value) => {

                          obtener_datos_usuarios();
                        
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

    const editar_usuario = ( id_usu ) => {

        $('#btn_usuarios').removeAttr('estatus').attr('estatus', 'Editar');

        $.ajax({
            url: 'server/obtener_usuario.php',
            type: 'POST',
            dataType: 'json',
            data: { id_usu },
            success: function(datos){

                $('#id_usu').val( id_usu );

                $('#nom_usu').val( datos.nom_usu );
                $('#cor_usu').val( datos.cor_usu );
                $('#pas_usu').val( datos.pas_usu );

                // 

                if ( datos.tip_usu == 'Super' ) {

                    $("#selector_plantel option[value='Cadena']").prop("selected", true);

                } else {
                
                    $("#selector_plantel option[value="+datos.id_pla14+"]").prop("selected", true);
                
                }
                
                $("#selector_usuario option[value="+datos.tip_usu+"]").prop("selected", true);
                // 

            }

        });
    }
    
</script>