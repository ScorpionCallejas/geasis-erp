<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');


	$alumnos = $_POST['alumnos'];

  //var_dump($alumnos);
?>




<!-- MODAL -->
<div class="modal-body" id="contenedor_mensaje_alumnos">
    

 	<!-- ALUMNOS SELECCIONADOS -->
	<div class="row">


		<div class="col-md-12">
			<span class="font-weight-normal">
		
		      <span id="alumnosSeleccionados">
		      	<?php echo sizeof($alumnos); ?>
		      </span> alumnos seleccionados
		    </span>
		</div> 
		

		<div class="progress md-progress" style="height: 20px" id="barra_baja">
		    <div class="progress-bar text-center white-text bg-info" role="progressbar" style="width: 0%; height: 20px;" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" id="barra_estado_mensaje">
		    	
		    
		    </div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12 scrollspy-example" style=" height: 300px;">

			
			<table class="table table-bordered">

				<thead>
					<tr>

						<th class="letraPequena grey-text">
							ALUMNO
						</th>

						<th class="letraPequena grey-text">
							GRUPO
						</th>
						
						<th class="letraPequena grey-text">
							ACCION
						</th>


					</tr>
				</thead>
				<tbody>
					
				
					<?php  
						for( $i = 0; $i < sizeof( $alumnos ); $i++ ){
							$sqlAlumno = "
								SELECT *
								FROM alu_ram
								INNER JOIN generacion ON generacion.id_gen = alu_ram.id_gen1
								INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
								INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
								WHERE id_alu_ram = '$alumnos[$i]'
							";

							//echo $sqlAlumno;

							$resultadoAlumno = mysqli_query($db, $sqlAlumno);

							$filaAlumno = mysqli_fetch_assoc($resultadoAlumno);
							$alumno = $filaAlumno['nom_alu']." ".$filaAlumno['app_alu'];
							$programa = $filaAlumno['nom_ram'];
							$id_ram = $filaAlumno['id_ram'];

							$id_gen = $filaAlumno['id_gen'];
							$id_alu = $filaAlumno['id_alu'];
					?>

							<tr>

								<td>
									
									<div class="badge bg-light text-dark rounded-pill seleccionAlumnoFinalCambioGeneracion" id_alu_ram="<?php echo $filaAlumno['id_alu_ram']; ?>" title="Alumno generación: <?php echo $filaAlumno['nom_gen']; ?>">
										<?php echo comprimirTextoVariable($filaAlumno['nom_alu'], 15); ?>
									</div>
										
								</td>


								<!-- GEN -->
								<td>
									<?php

										$sqlGeneracion = "
											SELECT *
											FROM generacion
											WHERE id_ram5 = '$id_ram'
											ORDER BY id_gen DESC
										";

										// echo $sqlGeneracion;

										$resultadoTotalGeneracion = mysqli_query( $db, $sqlGeneracion );

										$totalGeneracion = mysqli_num_rows( $resultadoTotalGeneracion );

										if ( $totalGeneracion > 0 ) {
									?>
											<!-- SELECT  -->
											<select class="form-control cambioGeneracionesSelect" style="font-size: 9px;" name="id_gen[]" required="">
												<?php

													$resultadoGeneracion = mysqli_query( $db, $sqlGeneracion );

													while($filaGeneracion = mysqli_fetch_assoc($resultadoGeneracion)){


														if ( $id_gen == $filaGeneracion['id_gen'] ) {


												?>
															<option value="<?php echo $filaGeneracion['id_gen']; ?>" selected><?php echo $filaGeneracion['nom_gen']." ( ".fechaFormateadaCompacta2( $filaGeneracion['ini_gen'] )." - ".fechaFormateadaCompacta2( $filaGeneracion['fin_gen'] )." )"; ?></option>

												<?php

														}else{
												?>
															<option value="<?php echo $filaGeneracion['id_gen']; ?>"><?php echo $filaGeneracion['nom_gen']." ( ".fechaFormateadaCompacta2( $filaGeneracion['ini_gen'] )." - ".fechaFormateadaCompacta2( $filaGeneracion['fin_gen'] )." )"; ?></option>
												<?php
														}
												?>
													
												<?php
													}
												?>
											</select>
											<!-- FIN SELECT -->

									<?php	
										}
									?>
								</td>
								<!-- GEN -->


							

								<!-- EJECUTIVO -->
								<td>
						

										


									  	<input type="checkbox" class="form-check-input  cambioGeneracionesCheckbox" id_alu_ram="<?php echo $filaGeneracion['id_alu_ram']; ?>" id="cambioGeneracionesCheckbox<?php echo $filaGeneracion['id_gen']; ?>" checked="checked">
										<label class="form-check-label letraPequena" for="cambioGeneracionesCheckbox<?php echo $filaGeneracion['id_gen']; ?>" style="font-size: 10px;">

											Reingreso
											
																			
										</label>
									<?php



									?>
								</td>
								<!-- EJECUTIVO -->
								
								
							</tr>
							

					<?php
						}
					?>

				</tbody>
			
			</table>

		</div>


		
		
	</div>
	<!-- FIN ALUMNOS SELECCIONADOS -->

</div>

<div class="modal-footer">


	<button class="btn btn-info btn-rounded waves-effect btn-sm" title="Guardar cambios..." type="submit" id="btn_guardar_cambio_generacion">
    	Guardar
  	</button>



	

	  <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>

</div>
<!-- FIN MODAL -->


<!-- JS -->




<script>



	//DESELECCION DE ALUMNOS A INSCRIBIR
	$(".eliminacionSeleccionAlumnoFinal").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var alumnosSeleccionados = $(".seleccionAlumnoFinal").length-1;
		$("#alumnosSeleccionados").text(alumnosSeleccionados);
		
		if ( alumnosSeleccionados < 1 ) {
			$("#modal_mensaje_alumnos").modal('hide');
		}

	});

	
</script>
<!-- FIN JS -->






<script>

	$(".cambioGeneracionesSelect").on('change', function(event) {
		event.preventDefault();
		

	});


	$('#btn_guardar_cambio_generacion').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		obtener_cambio_generacion();

	});
	function obtener_cambio_generacion(){

		var id_alu_ram = [];
		var id_gen = [];
		var estatus = [];

		for( var i = 0; i < $('.cambioGeneracionesCheckbox').length; i++ ){

			id_alu_ram[i] = $('.seleccionAlumnoFinalCambioGeneracion').eq( i ).attr('id_alu_ram');

			id_gen[i] = $('.cambioGeneracionesSelect option:selected').eq( i ).val();


			if ( $(".cambioGeneracionesCheckbox")[i].checked == true ) {
				
				estatus[i] = 'true';
			
			} else {
			
				estatus[i] = 'false';
			
			}
			

		}


		$.ajax({
			url: 'server/editar_generacion_alu_ram.php',
			type: 'POST',
			data: { id_alu_ram, id_gen, estatus },
			success: function( respuesta ){

				console.log( 'res here: '+respuesta );
				$('#modal_cambio_grupo').modal('hide');
				// obtenerAlumnosGeneraciones();
				
				obtenerAjaxAlumno();

			
			}

		});
		
	}
	





</script>