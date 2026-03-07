<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$alumnos = $_POST['alumnos'];
?>

<!-- MODAL -->
<div class="modal-body">
    
 	<!-- ALUMNOS SELECCIONADOS -->
	<div class="row">
		<div class="col-md-12">
			<span class="font-weight-normal">
		      <span id="alumnosSeleccionados">
		      	<?php echo sizeof($alumnos); ?>
		      </span> alumnos seleccionados
		    </span>
		</div> 
	</div>

	<div class="row">
		<div class="col-md-12 scrollspy-example" style=" height: 300px;">
			
			<table class="table table-bordered">
				<thead>
					<tr>
						<th class="letraPequena grey-text">Alumno</th>
						<th class="letraPequena grey-text">Programa</th>
						<th class="letraPequena grey-text" style="width: 150px;">Grupo</th>
						<th class="letraPequena grey-text">Opciones avanzadas</th>
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

							$resultadoAlumno = mysqli_query($db, $sqlAlumno);
							$filaAlumno = mysqli_fetch_assoc($resultadoAlumno);
							
							$id_alu = $filaAlumno['id_alu'];
					?>
							<tr>
								<td>
									<div class="badge bg-light text-dark rounded-pill seleccionAlumnos" title="Alumno generación: <?php echo $filaAlumno['nom_gen']; ?>">
										<?php echo comprimirTextoVariable($filaAlumno['nom_alu'], 15); ?>
									</div>
								</td>

								<!-- PROGRAMA -->
								<td>
									<?php
										$sqlPrograma = "
											SELECT *
											FROM rama
											WHERE id_pla1 = '$plantel' AND ( id_ram NOT IN ( SELECT id_ram3 FROM alu_ram WHERE id_alu1 = $id_alu ) )
											GROUP BY id_ram
											ORDER BY id_ram DESC
										";

										$resultadoTotalPrograma = mysqli_query( $db, $sqlPrograma );
										$totalPrograma = mysqli_num_rows( $resultadoTotalPrograma );

										if ( $totalPrograma > 0 ) {
									?>
											<select class="form-control seleccionPrograma" style="font-size: 9px;" name="id_ram[]" required="" index="<?php echo $i; ?>">
												<?php
													$resultadoPrograma = mysqli_query( $db, $sqlPrograma );
													$contadorPrograma = 0;
													while($filaPrograma = mysqli_fetch_assoc($resultadoPrograma)){
												?>
															<option value="<?php echo $filaPrograma['id_ram']; ?>" <?php echo ($contadorPrograma == 0) ? 'selected' : ''; ?>>
																<?php echo $filaPrograma['nom_ram']; ?>
															</option>
												<?php
														$contadorPrograma++;
													}
												?>
											</select>
									<?php	
										}
									?>
								</td>

								<!-- GENERACION -->
								<td>
									<div class="contenedor_generacion_programa_alumnos"></div>
								</td>

								<!-- PAGOS -->
								<td>
									<div class="mb-2">
										<input type="checkbox" class="form-check-input trasladarPagos" 
										       id_alu_ram="<?php echo $filaAlumno['id_alu_ram']; ?>" 
										       id="trasladarPagos<?php echo $filaAlumno['id_alu_ram']; ?>" 
										       checked="checked">
										<label class="form-check-label letraPequena" 
										       for="trasladarPagos<?php echo $filaAlumno['id_alu_ram']; ?>" 
										       style="font-size: 10px;">
											Trasladar pagos
										</label>
									</div>
									
									<div>
										<input type="checkbox" class="form-check-input eliminarAluRam" 
										       id="eliminarAluRam<?php echo $filaAlumno['id_alu_ram']; ?>">
										<label class="form-check-label letraPequena" 
										       for="eliminarAluRam<?php echo $filaAlumno['id_alu_ram']; ?>" 
										       style="font-size: 10px;">
											Eliminar programa anterior
										</label>
									</div>
								</td>
							</tr>
					<?php
						}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="modal-footer">
	<button class="btn btn-info btn-rounded waves-effect btn-sm" title="Guardar cambios..." type="submit" id="btn_guardar_agregar_programa">
    	Guardar
  	</button>
	<button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
</div>

<!-- JS -->
<script>
	// Deselección de alumnos
	$(".eliminacionSeleccionAlumnoFinal").on('click', function(event) {
		event.preventDefault();
		var alumnosSeleccionados = $(".seleccionAlumnoFinal").length-1;
		$("#alumnosSeleccionados").text(alumnosSeleccionados);
		
		if ( alumnosSeleccionados < 1 ) {
			$("#modal_mensaje_alumnos").modal('hide');
		}
	});
</script>

<script>
	// Inicializar generaciones al cargar
	for( var i = 0; i < $('.seleccionPrograma').length; i++ ){
		var id_ram = $('.seleccionPrograma').eq(i).val();
		var index = $('.seleccionPrograma').eq(i).attr('index');
		obtener_generacion_programa2( id_ram, index );
	}

	// Cambio de programa
	$('.seleccionPrograma').on('change', function(event) {
		event.preventDefault();
		var id_ram = $(this).val();
		var index = $(this).attr('index');
		obtener_generacion_programa2( id_ram, index );
	});

	function obtener_generacion_programa2( id_ram, index ){
		$.ajax({
			url: 'server/obtener_generacion_programa_alumnos.php',
			type: 'POST',
			data: {id_ram},
			success: function( respuesta ){
				console.log( respuesta );
				$('.contenedor_generacion_programa_alumnos').eq( index ).html( respuesta );
			}
		});
	}
</script>

<script>
	$('#btn_guardar_agregar_programa').on('click', function(event) {
		event.preventDefault();

		$("#btn_guardar_agregar_programa").attr('disabled','disabled').html('<i class="fas fa-cog fa-spin"></i> Guardando...');

		for( var i = 0; i < $('.seleccionAlumnos').length; i++ ){
			var id_ram = $('.seleccionPrograma').eq( i ).val();
			var id_gen = $('.seleccionGeneracion').eq( i ).val();
			
			// SIEMPRE mandamos id_alu_ram
			var id_alu_ram = $(".trasladarPagos").eq( i ).attr('id_alu_ram');
			
			// Verificar si trasladar pagos
			var trasladarPagos = $(".trasladarPagos")[i].checked ? 'true' : 'false';
			
			// Verificar si eliminar programa anterior
			var eliminacionAluRam = $(".eliminarAluRam")[i].checked ? 'true' : 'false';

			agregar_alumno_programa( id_ram, id_gen, trasladarPagos, id_alu_ram, eliminacionAluRam );
		}

		$("#btn_guardar_agregar_programa").removeAttr('disabled').html('Guardar');
		$('#modal_agregar_programa').modal('hide');
		$("#contenedor_seleccion_alumnos").html('');
		obtenerAjaxAlumno();
	});

	function contadorAlumnosSeleccionados2(){
        if ( $('.pegadoSeleccionAlumno').length > 0 ) {
            $('#contador_alumnos_seleccionados').text( $('.pegadoSeleccionAlumno').length );
        } else {
            $('#contador_alumnos_seleccionados').text('');
        }
    }

	function agregar_alumno_programa( id_ram, id_gen, trasladarPagos, id_alu_ram, eliminacionAluRam ){
		$.ajax({
			url: 'server/agregar_alumno_rama.php',
			type: 'POST',
			data: { id_ram, id_gen, trasladarPagos, id_alu_ram, eliminacion_alu_ram: eliminacionAluRam },
			success: function( respuesta ){
				console.log( respuesta );
			}
		});
	}
</script>