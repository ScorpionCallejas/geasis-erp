<?php
	//ARCHIVO VIA AJAX PARA OBTENER EXAMEN
	//examen.php
	require('../inc/cabeceras.php');

	$id_exa = $_POST['examen'];

	$id_exa_cop2 = $_POST['examenCopia'];
	$id_alu_ram = $_GET['id_alu_ram'];


	// RESTAR INTENTOS
	$sqlUpdateCalificacionActividad = "
		UPDATE cal_act 
		SET 
		int_cal_act = int_cal_act-1 
		WHERE 
		id_exa_cop2 = '$id_exa_cop2' AND id_alu_ram4 = '$id_alu_ram'
	";

	//echo $sqlUpdateCalificacionActividad;

	$resultadoUpdateCalificacionActividad = mysqli_query($db, $sqlUpdateCalificacionActividad);

	if (!$resultadoUpdateCalificacionActividad) {
		
		echo $sqlUpdateCalificacionActividad;

	}
	// FIN RESTAR INTENTOS

	$sqlPreguntas = "SELECT * FROM pregunta WHERE id_exa2 = '$id_exa' ORDER BY RAND()";
	$resultadoPreguntas = mysqli_query($db, $sqlPreguntas);
	$i = 1;
	$j = 1;
	while($filaPreguntas = mysqli_fetch_assoc($resultadoPreguntas)){
?>
	<!-- CARD -->
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
			  

	  	<!-- SECCION DE RESPUESTAS -->
	  	<div class="body" style="border-radius: 20px;">
	  	<?php

	  		$id_pre = $filaPreguntas['id_pre'];
	  		$sqlRespuestas = "SELECT * FROM respuesta WHERE id_pre1 = '$id_pre' ORDER BY RAND()";
	  		$resultadoRespuesta = mysqli_query($db, $sqlRespuestas);
	  		
	  		while($filaRespuestas = mysqli_fetch_assoc($resultadoRespuesta)){
	  			
	  	?>
	  		<div class="row p-2">
							
				<div class="col-md-1"></div>

			    <!-- Grid column -->
			    <div class="col-md-10">
			    	<div class="card" style="border-radius: 20px;">
						<div class="card-body">

							<div class="row clasePadre">

								<div class = "col-md-12">

									<input type="radio" class="form-check-input respuesta" id="materialGroupExample<?php echo $j;?>" 
								  name="groupOfMaterialRadios<?php echo $i;?>" respuesta="<?php echo $filaRespuestas['id_res']; ?>">
								<label class="form-check-label" for="materialGroupExample<?php echo $j; ?>">
								  	<?php 
										echo $filaRespuestas['res_res']; 
									?> 
								</label>
									
								</div>
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

	  	</div>
	  	<!-- FIN SECCION DE RESPUESTAS -->

	</div>
	<!-- FIN CARD -->

	<br>


<?php

	}

?>


<!-- CREACION DEL REGISTRO EN CAL_ACT DEL EXAMEN -->
<?php
	$fecha = date('Y/m/d H:i:s');
	$sqlExamen = "UPDATE cal_act SET pun_cal_act = 0, fec_cal_act = '$fecha' WHERE id_exa_cop2 = '$id_exa_cop2' AND id_alu_ram4 = '$id_alu_ram'";
	$resultadoExamen = mysqli_query($db, $sqlExamen);

	if ( !$resultadoExamen ) {	
		echo "error";
	}
?>
