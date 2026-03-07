<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$palabra = $_POST['palabra'];
	$tipo_usuario = $_POST['tipo_usuario'];

	// echo $tipo_usuario;
	if ( $tipo_usuario == 'alumno' ) {

		$sqlUsuario = "

			SELECT concat_ws(' ',nom_alu, app_alu, apm_alu) AS nombre_usuario, 'Alumno' AS tipo_usuario, id_alu AS id_usuario
			FROM $tipo_usuario
		";

		$sqlUsuario .= "

			WHERE (id_pla8 = '$plantel') AND 
			( 
				( bol_alu LIKE '%$palabra%' ) OR 
				( UPPER( concat_ws(' ',nom_alu, app_alu, apm_alu) ) LIKE UPPER( _utf8 '%$palabra%') COLLATE utf8_general_ci ) 
			) 
		";


		

	} else if ( $tipo_usuario == 'profesor' ) {
		
		$sqlUsuario = "

			SELECT concat_ws(' ',nom_pro, app_pro, apm_pro) AS nombre_usuario, 'Profesor' AS tipo_usuario, id_pro AS id_usuario
			FROM $tipo_usuario
		";

		$sqlUsuario .= "

			WHERE (id_pla2 = '$plantel') AND 
			(  
				( UPPER( concat_ws(' ',nom_pro, app_pro, apm_pro) ) LIKE UPPER( _utf8 '%$palabra%') COLLATE utf8_general_ci ) 
			) 
		";


	} else if ( $tipo_usuario == 'admin' ) {
		
		$sqlUsuario = "

			SELECT concat_ws(' ',nom_adm, app_adm, apm_adm) AS nombre_usuario, 'Admin' AS tipo_usuario, id_adm AS id_usuario
			FROM $tipo_usuario
		";

		$sqlUsuario .= "

			WHERE (id_pla3 = '$plantel') AND 
			(  
				( UPPER( concat_ws(' ',nom_adm, app_adm, apm_adm) ) LIKE UPPER( _utf8 '%$palabra%') COLLATE utf8_general_ci ) 
			) 
		";

		$sqlUsuario .= " UNION ";


		$sqlUsuario .= "

			SELECT concat_ws(' ',nom_adg, app_adg, apm_adg) AS nombre_usuario, 'Adminge' AS tipo_usuario, id_adg AS id_usuario
			FROM adminge
		";

		$sqlUsuario .= "

			WHERE (id_pla4 = '$plantel') AND 
			(  
				( UPPER( concat_ws(' ',nom_adg, app_adg, apm_adg) ) LIKE UPPER( _utf8 '%$palabra%') COLLATE utf8_general_ci ) 
			) 
		";


	}


?>


<style>
	tr{
		cursor: pointer;
	}

	td:hover {background-color:#0099CC;}

</style>

<table class="table table-bordered table-sm">
	<tbody>
		
	
		<?php
			// echo $sqlUsuario;

			$resultadoUsuario = mysqli_query( $db, $sqlUsuario );

			while( $filaUsuario = mysqli_fetch_assoc( $resultadoUsuario ) ){

				$existencia_sala = obtener_existencia_sala_server( $id, $tipo, $filaUsuario['id_usuario'], $filaUsuario['tipo_usuario'] );
		?>
		
				<tr class="white" >
					<td class="seleccion_usuario" tipo_usuario="<?php echo $filaUsuario['tipo_usuario']; ?>" nombre_usuario="<?php echo $filaUsuario['nombre_usuario']; ?>" id_usuario="<?php echo $filaUsuario['id_usuario']; ?>" id_sal="<?php echo $existencia_sala; ?>">

						<?php
		        			if ( stripos($filaUsuario['nombre_usuario'], $palabra) !== false ) {
							    //echo 'hay coincidencia';
							    $first_pos = stripos($filaUsuario['nombre_usuario'], $palabra);
							    $last_pos = strlen ($palabra) + $first_pos - 1;
							    $longitudCadena = strlen($filaUsuario['nombre_usuario']);
							    // echo "primera: ".$first_pos."<br>"."ultima: ".$last_pos."<br>"."cadena original: ".$longitudCadena;
							    
						?>
								<?php  
				        			if ($first_pos == 0) {
				        		?>
										<?php echo "<strong class='bg-info'>".substr($filaUsuario['nombre_usuario'], $first_pos, $last_pos+1)."</strong>".substr($filaUsuario['nombre_usuario'], $last_pos+1, $longitudCadena); ?>		
						        		

				        		<?php
				        			}else {
				        		?>
										<?php
											//echo "primera: ".$first_pos."<br>"."ultima: ".$last_pos."<br>"."cadena original: ".$longitudCadena;
											echo substr($filaUsuario['nombre_usuario'], 0, $first_pos)."<strong class='bg-info'>".substr($filaUsuario['nombre_usuario'], $first_pos, $last_pos-$first_pos+1)."</strong>".substr($filaUsuario['nombre_usuario'], $last_pos+1, $longitudCadena-$last_pos);
										?>


				        		<?php
				        			// substr($filaUsuario['nom_alu'], 0, $first_pos)."<strong class='bg-info'>".
				        				
				        			}
				        		?>


						<?php

							}else{
								//echo "no hay coincidencia";

								echo $filaUsuario['nombre_usuario'];
							}

		        		?>
					</td>
				</tr>
				
		<?php
			}
		?>
	</tbody>
</table>

<script>
	$('.seleccion_usuario').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		console.log( 'seleccion_usuario' );
		var id_usuario = $(this).attr('id_usuario');
		var tipo_usuario = $(this).attr('tipo_usuario');
		var nombre_usuario = $(this).attr('nombre_usuario');
		var id_sal = $(this).attr('id_sal');

		$.ajax({
			url: 'server/obtener_contacto_envio_mensaje.php',
			type: 'POST',
			// cache: false,
			data: { id_sal, id_usuario, tipo_usuario, nombre_usuario },
			success: function( respuesta ){

				$("#contenedor_buscar_contacto").html( respuesta );
				// console.log( respuesta );

			}
		});
		

	});
</script>

<script>
	$('#btn_enviar_buscar_contacto').attr('disabled', 'disabled');
</script>