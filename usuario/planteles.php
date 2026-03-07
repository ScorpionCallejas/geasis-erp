<?php  

	include('inc/header.php');
	
?>

<!-- TITULO -->
<div class="row ">
    <div class="col text-left">
        <span class="tituloPagina animated fadeInUp badge blue-grey darken-4 hoverable" title="CDEs">
            <i class="fas fa-bookmark"></i> CDEs
        </span>
        <br>
        <div class=" badge badge-warning animated fadeInUp text-white">
            <a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
            <i class="fas fa-angle-double-right"></i>
            <a style="color: black;" href="" title="Estás aquí">CDEs</a>
        </div>
        
    </div>
    
</div>
<!-- FIN TITULO -->

<hr>

<a href="#" id="btn_plantel" class="btn btn-info waves-effect btn-sm btn-rounded">Agregar CDE</a>


<!-- MODALES -->

<div class="modal fade text-left" id="modal_plantel">
  <div class="modal-dialog modal-lg" role="document">
    
    <form id="formulario_plantel">
        
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header text-center">
                <h5 class="modal-title">
                    Formulario de CDE
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
          
            <div class="modal-body mx-3">

                <!--  -->
                <div class="row">
                    <div class="col-md-12">
                        
                        <div class="card-body">
                            
                            <div class="row">
                            
                                <div class="col">
                                    <input type="text" class="form-control" id="nom_pla" value="" placeholder="CDE:" required="">
                                </div>
                            
                                <div class="col">
                                    <input type="text" class="form-control" id="dir_pla" value="" placeholder="Dirección:" required="">
                                </div>
                            
                            </div>

                            <hr>

                            <div class="row">
                                
                                <div class="col">
                                    
                                    <input type="text" class="form-control" id="jef_pla" value="" placeholder="Responsable:" required="">
                                </div>
                                
                                <div class="col">
                                    <input type="text" class="form-control" id="tel_pla" value="" placeholder="Teléfono:" required="">
                                
                                    <input type="hidden" id="id_pla">
                                </div>
                            </div>
                                
                        
                        </div>

                    </div>
                </div>

                <!--  -->

            </div>

            <div class="modal-footer d-flex justify-content-center">
                
                <button class="btn btn-info btn-rounded  btn-sm" title="Guardar cambios..." type="submit" id="btn_formulario_plantel" estatus="Agregar">
                    Guardar
                </button>

            </div>

        </div>
    </form>

  </div>

</div>

<!-- FIN MODALES -->


<div id="contenedor_datos_planteles">
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
    
    $('#btn_formulario_plantel').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */

        $('#modal_plantel').modal('hide');

        var estatus = $('#btn_formulario_plantel').attr('estatus');

        if ( estatus == 'Agregar' ) {

            agregar_plantel();

        } else if ( estatus == 'Editar' ) {

            agregar_plantel();
        
        }
        
    });


    $('#btn_plantel').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */


        $('#modal_plantel').modal('show');

    });

    const agregar_plantel = () => {

        
        var estatus = $('#btn_formulario_plantel').attr('estatus');
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

                    $('#btn_formulario_plantel').removeAttr('estatus').attr('estatus', 'Agregar');

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

                    $('#btn_formulario_plantel').removeAttr('estatus').attr('estatus', 'Agregar');

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

        $('#btn_formulario_plantel').removeAttr('estatus').attr('estatus', 'Editar');

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


<script>
    $("#titulo_plataforma").html('<?php echo $lugar; ?> - Planteles');
</script>