<?php  
	
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');
  	
	$id_mat = $_POST['id_mat'];

	$sqlMateria = "
		SELECT *
		FROM materia
		INNER JOIN rama ON rama.id_ram = materia.id_ram2
		WHERE id_mat = '$id_mat'
	";

	$resultadoMateria = mysqli_query( $db, $sqlMateria );

	$filaMateria = mysqli_fetch_assoc( $resultadoMateria );

?>


<span class="letraPequena">
	Seleccionaste: <?php echo $filaMateria['nom_mat'].' - '.$filaMateria['nom_ram']; ?>
</span>

<hr>
<!-- CLASES -->

	<?php
		$sqlClases = "
			SELECT *
			FROM bloque
			WHERE id_mat6 = '$id_mat'
		";


		// echo $sqlClases;

		

		$resultadoClases = mysqli_query( $db, $sqlClases );
		
		$contador = 1;
	?>
	
	<div class="row">

	<?php  
		while( $filaClases = mysqli_fetch_assoc( $resultadoClases ) ){
			$id_blo = $filaClases['id_blo'];
	?>

		<div class="col-md-4">
			
			<div class="card waves-effect seleccionBloque next-step" id_blo="<?php echo $id_blo; ?>" nom_blo="<?php echo $filaClases['nom_blo']; ?>" style="border-radius: 10px;">
				

				<?php  
					if ( $filaClases['img_blo'] == NULL ) {
				?>
				

					<div class="card-header border "  style="border-radius: 10px;
						background-image: url('../fondos_clase/img_backtoschool.jpg'); height: 100px; background-position: center; background-repeat: no-repeat; background-size: 100% 100%;   background-attachment: scroll; ">

				<?php	
					} else {
				?>

						<div class="card-header border" style="border-radius: 10px;
						background-image: url('../fondos_clase/<?php echo $filaClases['img_blo']; ?>'); height: 100px; background-position: center; background-repeat: no-repeat; background-size: 100% 100%;   background-attachment: scroll; ">

				<?php
					}

				?>
				
					

					<div class="row">
						
						<div class="col-md-12">

								<span class="font-weight-normal white-text">
									
									<i class="fas fa-circle"></i> <?php echo $filaClases['nom_blo']; ?>
									
									
								</span>							

						</div>

					</div>

				</div>

		      	<div class="card-body text-left">
			        

			        <div class="row">
			        	<div class="col-md-6">
			        		<p class="grey-text font-weight-bold letraMediana">
								<i class="fas fa-circle grey-text"></i>

								<?php
									

									$totalActividades = obtenerTotalActividadesBloqueServer( $id_blo ); 
									echo $totalActividades;
								?> actividades
							
							</p>
			        	</div>

			        	<div class="col-md-6">
			        		<p class="grey-text font-weight-bold letraMediana">
								<i class="fas fa-circle grey-text"></i>

								<?php
									

									$totalRecursosTeoricos = contadorRecursosTeoricosServer( $id_blo ); 
									echo $totalRecursosTeoricos;
								?> recursos teóricos
							
							</p>
			        		
			        	</div>
			        </div>
			        

		      	</div>

		    </div>

			
		</div>

	<?php  
		if ( $contador % 3 == 0 ) {
	?>

		</div>

		<hr>
		<div class="row">
	<?php
		}
	?>


<?php
	$contador++;
	}

?>

<!-- FIN CLASES -->


<script>
	
	$('.seleccionBloque').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		$('.seleccionBloque').removeClass('claseSeleccionPuntado');
		$(this).addClass('claseSeleccionPuntado');


		var id_blo = $(this).attr('id_blo');
		var nom_blo = $(this).attr('nom_blo');

		$('#bloque_destino').text( nom_blo );

		$('#input_bloque_destino').val( id_blo );
		

		if ( $('#input_bloque_destino').val() != '' ) {
			$('#btn_copiar_actividad').removeAttr('disabled', 'disabled');
		}



		
	});
</script>