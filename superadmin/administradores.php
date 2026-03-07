<?php  

	include('inc/header.php');
	
    $id_pla = $_GET['id_pla'];

    $datos_plantel = obtener_datos_plantel( $id_pla );

    if ( !isset( $_GET ) ) {
        header('location: planteles.php');
    }
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
                            Administradores de <?php echo $datos_plantel['nom_pla']; ?>
                            <div class="page-title-subheading">Listado de administradores</div>
                        </div>


                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    
                    <div class="card-body">
                        
                        <div class="input-group">
                            
                            <a href="#" class="btn btn-primary" id="btn_administradores" estatus="Agregar">
                                <i class="fas fa-lock"></i> 
                                Guardar administrador
                            </a>

                            <input type="text" class="form-control" id="nom_adm" value="" placeholder="Nombre:" required="">

                            <input type="text" class="form-control" id="cor_adm" value="" placeholder="Correo:" required="">

                            <input type="text" class="form-control" id="pas_adm" value="" placeholder="Contraseña:" required="">
                            
                            <input type="hidden" id="id_adm">

                        </div>
                    
                    </div>

                </div>
            </div>

            <hr>

            <div id="contenedor_datos_administradores">
            </div>

        </div>
        
    </div>


<?php  

	include('inc/footer.php');

?>

<script>
    function obtener_datos_administradores(){

        var id_pla = <?php echo $id_pla; ?>;
        $.ajax({
            url: 'server/obtener_datos_administradores.php',
            type: 'POST',
            data: { id_pla },
            success: function( respuesta ){
                $('#contenedor_datos_administradores').html( respuesta );
            }
        });
        
    }


    obtener_datos_administradores();

</script>

<script>
    
    $('#btn_administradores').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */

        
        var estatus = $('#btn_administradores').attr('estatus');

        if ( estatus == 'Agregar' ) {

            obtener_validacion_superadmin( agregar_administrador );

        } else if ( estatus == 'Editar' ) {

            agregar_administrador();
        
        }
        
    });


    const agregar_administrador = () => {

        var estatus = $('#btn_administradores').attr('estatus');
        var nom_adm = $('#nom_adm').val();
        var cor_adm = $('#cor_adm').val();
        var pas_adm = $('#pas_adm').val();

        if ( estatus == 'Agregar' ) {
            
            $.ajax({
                url: 'server/agregar_administrador.php',
                type: 'POST',
                data: { nom_adm, cor_adm, pas_adm },
                success: function( respuesta ){
                    console.log( respuesta );

                    $('#nom_adm').val('');
                    $('#cor_adm').val('');
                    $('#pas_adm').val('');

                    $('#btn_administradores').removeAttr('estatus').attr('estatus', 'Agregar');

                    obtener_datos_administradores();
                }
            });

        } else if ( estatus == 'Editar' ) {

            var id_adm = $('#id_adm').val();

            $.ajax({
                url: 'server/editar_administrador.php',
                type: 'POST',
                data: { id_adm, nom_adm, cor_adm, pas_adm },
                success: function( respuesta ){
                    console.log( respuesta );

                    $('#nom_adm').val('');
                    $('#cor_adm').val('');
                    $('#pas_adm').val('');
                    $('#id_adm').val('');

                    $('#btn_administradores').removeAttr('estatus').attr('estatus', 'Agregar');

                    obtener_datos_administradores();
                }
            });
        }

        

        
    }
</script>

<script>
    const eliminacion_administrador = ( id_adm, nom_adm ) => {
        swal({
          title: "¿Deseas eliminar "+nom_adm+"?",
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
                url: 'server/eliminacion_administrador.php',
                type: 'POST',
                data: {id_adm},
                success: function(respuesta){
                    
                    console.log( respuesta );
                    if (respuesta == "Exito") {
                        console.log("Exito en consulta");
                        swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
                        then((value) => {

                          obtener_datos_administradores();
                        
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

    const editar_administrador = ( id_adm ) => {

        $('#btn_administradores').removeAttr('estatus').attr('estatus', 'Editar');

        $.ajax({
            url: 'server/obtener_administrador.php',
            type: 'POST',
            dataType: 'json',
            data: { id_adm },
            success: function(datos){

                $('#id_adm').val( id_adm );

                $('#nom_adm').val( datos.nom_adm );
                $('#cor_adm').val( datos.cor_adm );
                $('#pas_adm').val( datos.pas_adm );

            }

        });
    }
    
</script>