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
                            Super-Administradores
                            <div class="page-title-subheading">Listado de Super-Administradores</div>
                        </div>


                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    
                    <div class="card-body">
                        
                        <div class="input-group">
                            
                            <a href="#" class="btn btn-primary" id="btn_superadministradores" estatus="Agregar">
                                <i class="fas fa-lock"></i> 
                                Guardar superadministrador
                            </a>

                            <input type="text" class="form-control" id="nom_sup" value="" placeholder="Nombre:" required="">

                            <input type="text" class="form-control" id="cor_sup" value="" placeholder="Correo:" required="">

                            <input type="text" class="form-control" id="pas_sup" value="" placeholder="Contraseña:" required="">
                            
                            <input type="hidden" id="id_sup">

                        </div>
                    
                    </div>

                </div>
            </div>

            <hr>

            <div id="contenedor_datos_superadministradores">
            </div>

        </div>
        
    </div>


<?php  

	include('inc/footer.php');

?>

<script>
    function obtener_datos_superadministradores(){
        $.ajax({
            url: 'server/obtener_datos_superadministradores.php',
            type: 'POST',
            success: function( respuesta ){
                $('#contenedor_datos_superadministradores').html( respuesta );
            }
        });
        
    }


    obtener_datos_superadministradores();

</script>

<script>
    
    $('#btn_superadministradores').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */

        
        var estatus = $('#btn_superadministradores').attr('estatus');

        if ( estatus == 'Agregar' ) {

            obtener_validacion_superadmin( agregar_superadministrador );

        } else if ( estatus == 'Editar' ) {

            agregar_superadministrador();
        
        }
        
    });


    const agregar_superadministrador = () => {

        var estatus = $('#btn_superadministradores').attr('estatus');
        var nom_sup = $('#nom_sup').val();
        var cor_sup = $('#cor_sup').val();
        var pas_sup = $('#pas_sup').val();

        if ( estatus == 'Agregar' ) {
            
            $.ajax({
                url: 'server/agregar_superadministrador.php',
                type: 'POST',
                data: { nom_sup, cor_sup, pas_sup },
                success: function( respuesta ){
                    console.log( respuesta );

                    $('#nom_sup').val('');
                    $('#cor_sup').val('');
                    $('#pas_sup').val('');

                    $('#btn_superadministradores').removeAttr('estatus').attr('estatus', 'Agregar');

                    obtener_datos_superadministradores();
                }
            });

        } else if ( estatus == 'Editar' ) {

            var id_sup = $('#id_sup').val();

            $.ajax({
                url: 'server/editar_superadministrador.php',
                type: 'POST',
                data: { id_sup, nom_sup, cor_sup, pas_sup },
                success: function( respuesta ){
                    console.log( respuesta );

                    $('#nom_sup').val('');
                    $('#cor_sup').val('');
                    $('#pas_sup').val('');
                    $('#id_sup').val('');

                    $('#btn_superadministradores').removeAttr('estatus').attr('estatus', 'Agregar');

                    obtener_datos_superadministradores();
                }
            });
        }

        

        
    }
</script>

<script>
    const eliminacion_superadministrador = ( id_sup, nom_sup ) => {
        swal({
          title: "¿Deseas eliminar "+nom_sup+"?",
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
                url: 'server/eliminacion_superadministrador.php',
                type: 'POST',
                data: {id_sup},
                success: function(respuesta){
                    
                    console.log( respuesta );
                    if (respuesta == "Exito") {
                        console.log("Exito en consulta");
                        swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
                        then((value) => {

                          obtener_datos_superadministradores();
                        
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

    const editar_superadministrador = ( id_sup ) => {

        $('#btn_superadministradores').removeAttr('estatus').attr('estatus', 'Editar');

        $.ajax({
            url: 'server/obtener_superadministrador.php',
            type: 'POST',
            dataType: 'json',
            data: { id_sup },
            success: function(datos){

                $('#id_sup').val( id_sup );

                $('#nom_sup').val( datos.nom_sup );
                $('#cor_sup').val( datos.cor_sup );
                $('#pas_sup').val( datos.pas_sup );

            }

        });
    }
    
</script>