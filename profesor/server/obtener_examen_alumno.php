<?php  
	//ARCHIVO VIA AJAX PARA OBTENER LAS RESPUESTAS QUE DIO EL ALUMNO EN EL EXAMEN
	//materias_horario.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_alu_ram = $_POST['id_alu_ram'];
	$id_exa = $_POST['id_exa'];

	$datos = obtenerDatosAlumnoProgramaServer( $id_alu_ram );
	$alumno = $datos['app_alu'].' '.$datos['nom_alu'];
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
														<?php 
															echo $contadorRespuesta; 
															$contadorRespuesta++;
														?>
													</div>
														
												</div>


												<?php 
													$id_res = $filaRespuestas['id_res'];
													$sqlValidacionRespuestaAlumno = "

														SELECT * 
														FROM respuesta 
														INNER JOIN respuesta_alumno ON respuesta_alumno.id_res1 = respuesta.id_res
														WHERE id_res1 = '$id_res' AND id_alu_ram8 = '$id_alu_ram'
													";

													//echo $sqlValidacionRespuestaAlumno;

													$resultadoValidacionRespuestaAlumno = mysqli_query($db, $sqlValidacionRespuestaAlumno);

													$totalValidacionRespuestaAlumno = mysqli_num_rows($resultadoValidacionRespuestaAlumno);
													//echo $totalValidacionRespuestaAlumno;
													if ($totalValidacionRespuestaAlumno == 1) {
												?>

														<?php 

															if ($filaRespuestas['val_res'] == 'Verdadero') {
														?>
																<span class="light-green accent-4 rounded waves-effect">
																	<?php 
																		echo $filaRespuestas['res_res']."<br>(".$filaRespuestas['val_res'].")";
																		
																	?> 
																</span>

														<?php
															}else{
														?>

																<span class="red rounded waves-effect">
																	<?php 
																		echo $filaRespuestas['res_res']."<br>(".$filaRespuestas['val_res'].")";
																		
																	?> 
																</span>
														<?php
															}
														?>
														
												<?php
													}else{
												?>
														
														<span>
															<?php 
																echo $filaRespuestas['res_res']."<br>(".$filaRespuestas['val_res'].")"; 
															?> 
														</span>
												<?php
													}
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

<script>
	generarAlerta( 'Respuestas de <?php echo $alumno; ?>' );
</script>