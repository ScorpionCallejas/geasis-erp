<?php
	require('../inc/cabeceras.php');

	$tipo_usuario = $_POST['tipo_usuario'];
	$id_usuario = $_POST['id_usuario'];
	$nombre_usuario = $_POST['nombre_usuario'];
	$id_sal = $_POST['id_sal'];
?>

<div class="justify-self-end align-items-center flex-row d-flex">
					
	<!-- <a href="#"><i class="far fa-smile text-muted px-3" style="font-size:1.5rem;"></i></a> -->
	<input type="text" id="mensaje_contacto_envio_mensaje" placeholder="Escribe un mensaje para <?php echo $nombre_usuario; ?>..." class="flex-grow-1 border-0 px-3 py-2 my-3 rounded shadow-sm">

</div>


<script>
	$("#mensaje_contacto_envio_mensaje").focus();
</script>

<script>
	$('#btn_enviar_buscar_contacto').removeAttr('disabled');
</script>


<script>
	
	$('#btn_enviar_buscar_contacto').off('click');
	 
	$('#btn_enviar_buscar_contacto').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		agregar_mensaje_buscador();

	});


	function agregar_mensaje_buscador(){
			
		var mensaje = $('#mensaje_contacto_envio_mensaje').val();
		var tipo_usuario = '<?php echo $tipo_usuario; ?>';
		var id_usuario = '<?php echo $id_usuario; ?>';
		var id_sal = '<?php echo $id_sal; ?>';

		if ( mensaje != '' ) {

			$.ajax({
				url: 'server/agregar_mensaje.php',
				type: 'POST',
				data: { id_sal, mensaje, tipo_usuario, id_usuario  },
				success: function( respuesta ){

					console.log( respuesta );

					var id_sal = respuesta;
					// obtener_mensajes_sala( id_sal );
					$('#mensaje_contacto_envio_mensaje').val('').focus();
					obtener_contactos_mensajeria();

					

					$("#modal_buscar_contacto").modal('hide');

					$("#contenedor_buscar_contacto").html( '' );

					$("#palabra").val('');


					obtener_socket_salas( id_sal );
					// obtener_scroll();

					// var datos = {
					    
					//     modulo : 'Mensaje',
					//     id_sal : id_sal

					// };

					// socket.send( JSON.stringify( datos ) );
					
					// obtener_datos_sala( id_sal );
				}
			});
			

    	}
        

	}
</script>