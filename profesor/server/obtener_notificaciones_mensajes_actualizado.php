<?php
  //ARCHIVO VIA AJAX PARA OBTENER NOTIFICACIONES DE PROFESOR
  //header.php//footer.php
  require('../inc/cabeceras.php');
  require('../inc/funciones.php');
?>

<a class=" nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLinkMensajeria" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      
  Mensajería

  <?php  
    if ( obtenerTotalNotificacionesMensajesServer( $tipoUsuario, $id ) > 0 ) {
  ?>
      <i class="fas fa-envelope   pr-1 animated swing infinite" id="icono_mensajeria"></i>
  <?php
    } else {
  ?>
      <i class="fas fa-envelope   pr-1 " id="icono_mensajeria"></i>
  <?php
    }
  ?>
  <span class="badge badge-danger notification rounded-circle">
  <?php  
    echo obtenerTotalNotificacionesMensajesServer( $tipoUsuario, $id );
  ?>
  </span>
  
</a>

<div class="dropdown-menu grey lighten-2">
  
  <div class="row ">
    <div class="col-md-6 text-center">
      <a href="mensajes.php" class="btn-link grey lighten-2 font-weight-bold grey-text">
        <i class="far fa-envelope-open"></i> Ir a mensajería
      </a>
    </div>

    <div class="col-md-6 text-center">
      <a href="buscador.php" class="btn-link font-weight-bold grey-text  grey lighten-2">
        <i class="fas fa-search"></i> Buscar contacto
      </a>
    </div>
  </div>


  <form class="px-4 py-3 table-wrapper-scroll-y my-custom-scrollbar" id="formularioNotificaciones_mensajeria">

    
    
    <table class="table table-sm" id="tablaValidaciones_mensajeria">
    <!-- NO HAY HEADER DE TABLA -->
      <tbody >
        <div class="accordion" id="accordionExample275_mensajeria">

        </div>
      </tbody>
      
    </table>

    <div id="load_data_message_mensajeria" class="text-center"></div>
  </form>

</div>



<script>
	// MENSAJERIA NOTIFICACIONS
     // TABLA NOTIFICACIONES DE HEADER
    $('#tablaValidaciones_mensajeria').removeClass('bordeGrisTabla');
    
    var limite = 10;
    var inicio = 0;
    var action = 'inactive';
    function obtener_notificaciones_mensajeria(limite, inicio){
        $.ajax({
           url: "server/obtener_notificaciones_mensajes.php",
           method: "POST",
           data: {limite, inicio},
           cache: false,
           success:function(data) {
                // console.log( data );
                $('#accordionExample275_mensajeria').append(data);
                if(data == '')
                {
                 $('#load_data_message_mensajeria').html('<label class="animated fadeInDown letraPequena">¡No hay más registros!</label>');
                 action = 'active';
                }
                else
                {
                 $('#load_data_message_mensajeria').html('<label class="letraPequena"><i class="fas fa-spinner fa-pulse"></i> Cargando...</label>');
                 action = "inactive";
                }
                
            }
        });
    }

    if(action == 'inactive') {
        action = 'active';
        obtener_notificaciones_mensajeria(limite, inicio);
    }
    $('#formularioNotificaciones_mensajeria').scroll(function(){
        if($('#formularioNotificaciones_mensajeria').scrollTop() + $('#formularioNotificaciones_mensajeria').height() >$('#formularioNotificaciones_mensajeria').height() && action == 'inactive') {
            action = 'active';
            inicio = inicio + limite;
            setTimeout(function(){
                obtener_notificaciones_mensajeria(limite, inicio);
            }, 1000);

         
        }
    });
    // FIN MENSAJERIA NOTIFICACIONES


    $( '#dropdownMenuLinkMensajeria' ).on('click', function(event) {
        event.preventDefault();
        /* Act on the event */

        if ( $( '#icono_mensajeria' ).hasClass('fas fa-envelope') ) {
            $( '#icono_mensajeria' ).removeClass( 'fas fa-envelope' ).addClass( 'fas fa-envelope-open' );
        } else {
            $( '#icono_mensajeria' ).removeClass( 'fas fa-envelope-open' ).addClass( 'fas fa-envelope' );
        }
        

        
    });
</script>