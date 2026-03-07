<?php  

	include('inc/header.php');
	
?>

	<div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">

                        <div class="page-title-icon">
                            <i class="pe-7s-culture icon-gradient bg-premium-dark"></i>
                        </div>
                        
                        <div>
                            Centros de Desarrollo Empresarial
                            <div class="page-title-subheading">Listado de CDEs de <?php echo $nombreCadena; ?></div>
                        </div>


                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    
                    <div class="card-body">
                        
                        <div class="input-group">
                            
                            <a href="#" class="btn btn-primary" id="btn_planteles" estatus="Agregar">
                                <i class="fas fa-lock"></i> 
                                Guardar CDE
                            </a>

                            <input type="text" class="form-control" id="nom_pla" value="" placeholder="CDE:" required="">

                            <input type="text" class="form-control" id="dir_pla" value="" placeholder="Dirección:" required="">

                            <input type="text" class="form-control" id="jef_pla" value="" placeholder="Responsable:" required="">

                            <input type="text" class="form-control" id="tel_pla" value="" placeholder="Teléfono:" required="">
                            
                            <input type="hidden" id="id_pla">

                        </div>
                    
                    </div>

                </div>
            </div>

            <hr>

            <div id="contenedor_datos_planteles">
            </div>

        </div>
        
    </div>


<?php  

	include('inc/footer.php');

?>

<script>
    function obtener_datos_planteles(){
        $.ajax({
            url: 'server/obtener_datos_planteles.php',
            type: 'POST',
            success: function( respuesta ){
                $('#contenedor_datos_planteles').html( respuesta );
            }
        });
        
    }


    obtener_datos_planteles();

</script>

<script>
    
    $('#btn_planteles').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */

        
        var estatus = $('#btn_planteles').attr('estatus');

        if ( estatus == 'Agregar' ) {

            obtener_validacion_superadmin( agregar_plantel );

        } else if ( estatus == 'Editar' ) {

            agregar_plantel();
        
        }
        
    });


    const agregar_plantel = () => {

        var estatus = $('#btn_planteles').attr('estatus');
        var nom_pla = $('#nom_pla').val();
        var dir_pla = $('#dir_pla').val();
        var jef_pla = $('#jef_pla').val();
        var tel_pla = $('#tel_pla').val();

        if ( estatus == 'Agregar' ) {
            
            $.ajax({
                url: 'server/agregar_plantel.php',
                type: 'POST',
                data: { nom_pla, dir_pla, jef_pla, tel_pla },
                success: function( respuesta ){
                    console.log( respuesta );

                    $('#nom_pla').val('');
                    $('#dir_pla').val('');
                    $('#jef_pla').val('');
                    $('#tel_pla').val('');

                    $('#btn_planteles').removeAttr('estatus').attr('estatus', 'Agregar');

                    obtener_datos_planteles();
                }
            });

        } else if ( estatus == 'Editar' ) {

            var id_pla = $('#id_pla').val();

            $.ajax({
                url: 'server/editar_plantel.php',
                type: 'POST',
                data: { id_pla, nom_pla, dir_pla, jef_pla, tel_pla },
                success: function( respuesta ){
                    console.log( respuesta );

                    $('#nom_pla').val('');
                    $('#dir_pla').val('');
                    $('#jef_pla').val('');
                    $('#id_pla').val('');
                    $('#tel_pla').val('');

                    $('#btn_planteles').removeAttr('estatus').attr('estatus', 'Agregar');

                    obtener_datos_planteles();
                }
            });
        }

        

        
    }
</script>

<script>
    const eliminacion_plantel = ( id_pla, nom_pla ) => {
        swal({
          title: "¿Deseas eliminar "+nom_pla+"?",
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
                url: 'server/eliminacion_plantel.php',
                type: 'POST',
                data: {id_pla},
                success: function(respuesta){
                    
                    console.log( respuesta );
                    if (respuesta == "Exito") {
                        console.log("Exito en consulta");
                        swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
                        then((value) => {

                          obtener_datos_planteles();
                        
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

    const editar_plantel = ( id_pla ) => {

        $('#btn_planteles').removeAttr('estatus').attr('estatus', 'Editar');

        $.ajax({
            url: 'server/obtener_plantel.php',
            type: 'POST',
            dataType: 'json',
            data: { id_pla },
            success: function(datos){

                $('#id_pla').val( id_pla );

                $('#nom_pla').val( datos.nom_pla );
                $('#dir_pla').val( datos.dir_pla );
                $('#jef_pla').val( datos.jef_pla );
                $('#tel_pla').val( datos.tel_pla );

            }

        });
    }
    
</script>