<?php  

	require('../inc/cabeceras.php');
 	require('../inc/funciones.php');

 	$id_sal = $_POST['id_sal'];

 	$sqlUsuarios = "
 		SELECT *
 		FROM usuario_sala
 		WHERE id_sal6 = '$id_sal' AND ( usu_usu_sal != '$id' AND tip_usu_sal != '$tipo' )
 		ORDER BY tip_usu_sal ASC
 	";

 	$resultadoUsuarios = mysqli_query( $db, $sqlUsuarios );

 	// echo $sqlUsuarios;
?>
		
<table class="table table-hover">
	<tbody>
		<!--  -->
		<?php
			$i = 1;
		 	while( $filaUsuarios = mysqli_fetch_assoc( $resultadoUsuarios ) ){


		 		$datosContactoUltimoMensaje = obtener_datos_contacto_mensajeria_server( $filaUsuarios['tip_usu_sal'], $filaUsuarios['usu_usu_sal'] );

		 		$usuario_tipo = $datosContactoUltimoMensaje['tipo'];
		        $usuario_nombre = $datosContactoUltimoMensaje['nombre'];


		        $existencia_sala = obtener_existencia_sala_server( $id, $tipo, $filaUsuarios['usu_usu_sal'], $filaUsuarios['tip_usu_sal'] );

		        // echo 'existencia_sala: '.$existencia_sala.' // id: '.$id.'// tipo: '.$tipo.' // id2: '.$filaUsuarios['usu_usu_sal'].' // tipo2: '.$filaUsuarios['tip_usu_sal']."<hr>";

		        // echo $existencia_sala;


		        if ( $usuario_tipo == 'Admin' ) {

		          $usuario_tipo = 'Directivo😎';
		        
		        } else if ( $usuario_tipo == 'Profesor' ) {
		          
		          $usuario_tipo = 'Capacitador👨‍🏫';

		        } else if ( $usuario_tipo == 'Adminge' ) {
		          
		          $usuario_tipo = 'Coordinador🎓';

		        }
		?>
				

				<tr>
				
					<td>
						<div class="badge badge-warning badge-pill font-weight-normal" ><?php echo $usuario_tipo; ?></div>
						<br>
						<?php echo $usuario_nombre; ?>
						

					</td>

					<td>
						<div class="row">
							<div class="col-md-12">
								<input type="text" id="mensaje_contacto_envio_mensaje<?php echo $i; ?>" placeholder="Mensaje para <?php echo $usuario_nombre; ?>..." class="form-control letraPequena">

								
							</div>

						</div>
					</td>

					<td>
						<button class="btn btn-info white-text waves-effect btn-sm" type="submit" id="btn_enviar_buscar_contacto<?php echo $i; ?>" tipo_usuario="<?php echo $filaUsuarios['tip_usu_sal']; ?>" id_usuario="<?php echo $filaUsuarios['usu_usu_sal']; ?>" id_sal="<?php echo $existencia_sala; ?>">
		                    Enviar
		                </button>
					</td>

				</tr>
				

				<script>
					$('#btn_enviar_buscar_contacto<?php echo $i; ?>').on('click', function(event) {
						event.preventDefault();
						/* Act on the event */

						console.log( 'seleccion_usuario' );
						var id_usuario = $(this).attr('id_usuario');
						var tipo_usuario = $(this).attr('tipo_usuario');
						var id_sal = $(this).attr('id_sal');


						var mensaje = $('#mensaje_contacto_envio_mensaje<?php echo $i; ?>').val();

						$.ajax({
							url: 'server/agregar_mensaje.php',
							type: 'POST',
							data: { id_sal, mensaje, tipo_usuario, id_usuario  },
							success: function( respuesta ){

								console.log( respuesta );

								var id_sal = respuesta;
								// obtener_mensajes_sala( id_sal );
								obtener_contactos_mensajeria2( 10, 0 );
								obtener_consulta_usuarios( <?php echo $id_sal; ?> );

							}
						});
	
					});




				</script>
		<?php
				$i++;
		 	}
		?>
		<!--  -->
	</tbody>
	
</table>