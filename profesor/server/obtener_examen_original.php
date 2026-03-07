<?php  
	//ARCHIVO VIA AJAX PARA OBTENER EL EXAMEN ORIGINAL
	//examen.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_exa = $_POST['id_exa'];

?>




<?php  
	$sqlPreguntas = "SELECT * FROM pregunta WHERE id_exa2 = '$id_exa'";
	$resultadoPreguntas = mysqli_query($db, $sqlPreguntas);
	$i = 1;
	$j = 1;
	while($filaPreguntas = mysqli_fetch_assoc($resultadoPreguntas)){
?>

		<?php  
			$sqlPreguntas = "SELECT * FROM pregunta WHERE id_exa2 = '$id_exa'";
			$resultadoPreguntas = mysqli_query($db, $sqlPreguntas);
			$i = 1;
			$j = 1;
			while( $filaPreguntas = mysqli_fetch_assoc( $resultadoPreguntas ) ){
		?>

			<div class="card" style="border-radius: 20px;">
				<div class="card-header z-depth-1 bg-white" style="border-radius: 20px;">
					<div class="row p-2  clasePadre">
														
						<div class="claseHijoNumeracion font-weight-bold">
							<div class="claseTextoHijoNumeracion">
								<?php echo $i; ?>
							</div>
								
						</div>

						<div class="col-md-6">
							
							<?php echo $filaPreguntas['pre_pre']; ?>
						</div>

						<div class="col-md-6 text-right">
							<p class="letraMediana grey-text">
					      		<?php echo "Valor: +".$filaPreguntas['pun_pre']; ?>
					      	</p>
							
						</div>
					</div>

					
				</div>

				<div class="body" style="border-radius: 20px;">
					<!-- SECCION DE RESPUESTAS -->
				  	<?php


				  		$id_pre = $filaPreguntas['id_pre'];
				  		$sqlRespuestas = "SELECT * FROM respuesta WHERE id_pre1 = '$id_pre'";


				  		//echo $sqlRespuestas;
				  		$resultadoRespuesta = mysqli_query($db, $sqlRespuestas);
				  		$contadorRespuesta = 1;
				  		while($filaRespuestas = mysqli_fetch_assoc($resultadoRespuesta)){
				  			
				  	?>
						
							<div class="row p-2">
					
								<div class="col-md-1"></div>

							    <!-- Grid column -->
							    <div class="col-md-10">
							    	<div class="card" style="border-radius: 20px;">
										<div class="card-body">

											<div class="row clasePadre">

												<div class="claseHijoNumeracion font-weight-bold">
													<div class="claseTextoHijoNumeracion">
														<?php echo $contadorRespuesta; ?>
													</div>
														
												</div>


												<?php 
													echo $filaRespuestas['res_res']."<br>(".$filaRespuestas['val_res'].")";
													$contadorRespuesta++;
												?> 
											</div>
											
										</div>
										
									</div>
							    </div>
							   	<div class="col-md-1"></div>
							</div>

							



				  	<?php
				  			$j++;

				  		}

				  		$i++;

				  	?>

				  <!-- FIN SECCION DE RESPUESTAS -->
				</div>

				
			</div>
			<br>
			


		<?php

			}

		?>
		
		


<?php

	}

?>