<?php  
	
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');
  	


?>



<?php	

	// echo $plantel;

			if ( isset( $_POST['palabra_materia'] ) && $_POST['palabra_materia'] != ''  ) {
				
				$palabra_materia = $_POST['palabra_materia'];

				$sql = "

					SELECT *, CONCAT( nom_mat,' ',nom_ram ) AS materia_programa
					FROM materia
					INNER JOIN rama ON rama.id_ram = materia.id_ram2
					WHERE ( id_pla1 = '$plantel' ) AND ( ( ( nom_mat LIKE '%$palabra_materia%' ) OR ( UPPER( nom_mat ) LIKE UPPER( _utf8 '%$palabra_materia%') COLLATE utf8_general_ci ) ) )

				";

				// echo $sql;

			}


			$resultado = mysqli_query($db, $sql);

			$resultadoTotal2 = mysqli_query( $db, $sql );

			$total2 = mysqli_num_rows( $resultadoTotal2 );

			
			if ( $total2 == 0 ) {
?>
				<!-- SIN RESULTADOS -->
				<div class="row">
					
					<div class="col-md-12 text-center">

						
						<h4>
							Sin resultados...
						</h4>
						
						

					</div>

				</div>


<?php
			} else {
?>


	<?php


				$contador = 1;
				while( $fila = mysqli_fetch_assoc( $resultado ) ){

					$id_mat = $fila['id_mat'];
					
	?>
				<div class="row">
					
					<div class="col-md-12">

						<div class="card mb-3 seleccionMateria waves-effect next-step" style="border-radius: 20px;" id_mat="<?php echo $id_mat; ?>" nom_mat="<?php echo $fila['nom_mat']; ?>" nom_ram="<?php echo $fila['nom_ram']; ?>">
						  	
						  	<div class="card-header white black-text" style="border-radius: 20px;" title="<?php echo $contador.' - Seleccionar '.$fila['nom_mat'].' - '.$fila['nom_ram']; ?>">
						  		
						  		<span>

									<?php
					          			if ( ( isset( $_POST['palabra_materia'] ) ) && ( ( $_POST['palabra_materia'] ) != '' ) ) {

									        $palabra_materia = $_POST['palabra_materia'];
									       	
									       	echo $contador.' - '.obtenerPalabraBuscada( $palabra_materia, comprimirTexto( $fila['nom_mat'] ).' - '.comprimirTexto( $fila['nom_ram'] ) );
									
									    }

					          		?>
								</span>

						  	</div>

						</div>
						
						

					</div>

				</div>

					


	<?php
					$contador++;
				// FIN WHILE
				}
	?>


<?php
			// FIN ELSE
			}
?>


<script>
	$('.seleccionMateria').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		$('.seleccionMateria').removeClass('claseSeleccionPuntado');
		$(this).addClass('claseSeleccionPuntado');

		var nom_mat = $(this).attr('nom_mat');
		var nom_ram = $(this).attr('nom_ram');

		var id_mat = $(this).attr('id_mat');

		$.ajax({
            
            url: 'server/obtener_bloques_materia_buscada.php',
            type: 'POST',
            data: { id_mat },
            success: function( respuesta ){

            	$('#btn_copiar_actividad').attr('disabled', 'disabled');
         
                $('#bloque_destino').text( 'Sin asignar' );
				$('#input_bloque_destino').val( '' );
                
                $('#contenedor_bloques').html( respuesta );
                $('#programa_destino').text( nom_ram );
                $('#materia_destino').text( nom_mat );




            
            }
        
        });

		
	});
</script>