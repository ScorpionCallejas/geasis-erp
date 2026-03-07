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
						<th class="letraPequena grey-text">
							Alumno
						</th>

						<th class="letraPequena grey-text" style="width: 180px;">
							Centro
						</th>

						<th class="letraPequena grey-text">
							Programa
						</th>

						<th class="letraPequena grey-text" style="width: 150px;">
							Grupo
						</th>

						<th class="letraPequena grey-text">
							Acciones avanzadas
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
									<div class="badge bg-light text-dark rounded-pill seleccionAlumnos" id_alu="<?php echo $filaAlumno['id_alu']; ?>" title="Alumno generación: <?php echo $filaAlumno['nom_gen']; ?>">
										<?php echo comprimirTextoVariable($filaAlumno['nom_alu'], 15); ?>
									</div>
								</td>

								<!-- PLANTEL -->
								<td>
									<?php
										$sqlPlantel = "
											SELECT *
											FROM plantel
											WHERE id_cad1 = 1
											ORDER BY nom_pla ASC
										";

										$resultadoTotalPlantel = mysqli_query( $db, $sqlPlantel );
										$totalPlantel = mysqli_num_rows( $resultadoTotalPlantel );

										if ( $totalPlantel > 0 ) {
									?>
											<select class="form-control seleccionPlantel" style="font-size: 9px;" name="id_pla[]" required="" index="<?php echo $i; ?>">
												<?php
													$resultadoPlantel = mysqli_query( $db, $sqlPlantel );
													while($filaPlantel = mysqli_fetch_assoc($resultadoPlantel)){
														$selected = ($filaPlantel['id_pla'] == $plantel) ? 'selected' : '';
												?>
													<option value="<?php echo $filaPlantel['id_pla']; ?>" <?php echo $selected; ?>>
														<?php echo $filaPlantel['nom_pla']; ?>
													</option>
												<?php
													}
												?>
											</select>
									<?php	
										}
									?>
								</td>
								<!-- FIN PLANTEL -->

								<!-- PROGRAMA -->
								<td>
									<select class="form-control seleccionPrograma" style="font-size: 9px;" name="id_ram[]" required="" index="<?php echo $i; ?>">
										<?php
											$sqlPrograma = "
												SELECT r.*, p.nom_pla
												FROM rama r
												INNER JOIN plantel p ON p.id_pla = r.id_pla1
												WHERE p.id_cad1 = 1
												AND r.id_ram NOT IN (
													SELECT id_ram3 
													FROM alu_ram 
													WHERE id_alu1 = '{$filaAlumno['id_alu']}'
												)
												ORDER BY r.id_ram DESC
											";

											$resultadoPrograma = mysqli_query($db, $sqlPrograma);
											while($filaPrograma = mysqli_fetch_assoc($resultadoPrograma)){
										?>
											<option value="<?php echo $filaPrograma['id_ram']; ?>" id_pla="<?php echo $filaPrograma['id_pla1']; ?>" style="display: none;">
												<?php echo $filaPrograma['nom_ram']; ?>
											</option>
										<?php
											}
										?>
									</select>
								</td>
								<!-- FIN PROGRAMA -->

								<!-- GENERACION -->
								<td>
									<select class="form-control seleccionGeneracion" style="font-size: 9px;" name="id_gen[]" required="" index="<?php echo $i; ?>">
										<?php
											$sqlGeneracion = "
												SELECT g.*, r.id_pla1
												FROM generacion g
												INNER JOIN rama r ON r.id_ram = g.id_ram5
												INNER JOIN plantel p ON p.id_pla = r.id_pla1
												WHERE p.id_cad1 = 1
												ORDER BY g.nom_gen DESC
											";

											$resultadoGeneracion = mysqli_query($db, $sqlGeneracion);
											while($filaGeneracion = mysqli_fetch_assoc($resultadoGeneracion)){
										?>
											<option value="<?php echo $filaGeneracion['id_gen']; ?>" id_ram="<?php echo $filaGeneracion['id_ram5']; ?>" style="display: none;">
												<?php echo $filaGeneracion['nom_gen']; ?>
											</option>
										<?php
											}
										?>
									</select>
								</td>
								<!-- FIN GENERACION -->
								
								<!-- ACCIONES AVANZADAS -->
								<td>
									<div class="mb-2">
										<input type="checkbox" class="form-check-input trasladarPagos" id_alu_ram="<?php echo $filaAlumno['id_alu_ram']; ?>" id="trasladarPagos<?php echo $filaAlumno['id_alu_ram']; ?>" checked="checked">
										<label class="form-check-label letraPequena" for="trasladarPagos<?php echo $filaAlumno['id_alu_ram']; ?>" style="font-size: 10px;">
											Trasladar pagos
										</label>
									</div>
									
									<div>
										<input type="checkbox" class="form-check-input eliminarAluRam" id="eliminarAluRam<?php echo $filaAlumno['id_alu_ram']; ?>">
										<label class="form-check-label letraPequena" for="eliminarAluRam<?php echo $filaAlumno['id_alu_ram']; ?>" style="font-size: 10px;">
											Eliminar programa anterior
										</label>
									</div>
								</td>
								<!-- FIN ACCIONES AVANZADAS -->
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
	<button class="btn btn-success btn-rounded waves-effect btn-sm" title="Guardar cambios..." type="button" id="btn_guardar_cambio_plantel">
    	Guardar
  	</button>

	<button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
</div>
<!-- FIN MODAL -->

<!-- 🔥🔥🔥 JS CORREGIDO 🔥🔥🔥 -->
<script>
	console.log('🏢 Script de cambio de plantel cargado');
	
	// DESELECCION DE ALUMNOS
	$(".eliminacionSeleccionAlumnoFinal").on('click', function(event) {
		event.preventDefault();
		var alumnosSeleccionados = $(".seleccionAlumnoFinal").length-1;
		$("#alumnosSeleccionados").text(alumnosSeleccionados);
		
		if ( alumnosSeleccionados < 1 ) {
			$("#modal_cambio_plantel").modal('hide');
		}
	});

	// CAMBIO DE PLANTEL - Filtrar programas por plantel
	$('.seleccionPlantel').on('change', function(event) {
		event.preventDefault();
		
		var index = $(this).attr('index');
		obtener_seleccion_plantel(index);
	});

	// CAMBIO DE PROGRAMA - Filtrar generaciones por programa
	$('.seleccionPrograma').on('change', function(event) {
		event.preventDefault();
		
		var index = $(this).attr('index');
		obtener_seleccion_programa(index);
	});

	// Inicializar filtros al cargar
	setTimeout(function() {
		for(var i = 0; i < $('.seleccionPlantel').length; i++) {
			obtener_seleccion_plantel(i);
		}
		console.log('✅ Filtros inicializados');
	}, 100);

	function obtener_seleccion_plantel(index) {
		var id_pla = $('.seleccionPlantel').eq(index).val();
		var selectPrograma = $('.seleccionPrograma').eq(index);
		
		selectPrograma.find('option').removeAttr('selected');
		
		var total = 0;
		var firstVisible = null;

		selectPrograma.find('option').each(function() {
			var option = $(this);
			
			if (option.attr('id_pla') == id_pla) {
				option.show();
				total++;
				
				if (firstVisible === null) {
					firstVisible = option;
					option.attr('selected', 'selected');
				}
			} else {
				option.hide();
			}
		});

		if (total > 0 && firstVisible) {
			selectPrograma.val(firstVisible.val());
			obtener_seleccion_programa(index);
		} else {
			limpiar_generaciones(index);
		}
	}

	function obtener_seleccion_programa(index) {
		var id_ram = $('.seleccionPrograma').eq(index).val();
		var selectGeneracion = $('.seleccionGeneracion').eq(index);
		
		selectGeneracion.find('option').removeAttr('selected');
		
		var total = 0;
		var firstVisible = null;

		selectGeneracion.find('option').each(function() {
			var option = $(this);
			
			if (option.attr('id_ram') == id_ram) {
				option.show();
				total++;
				
				if (firstVisible === null) {
					firstVisible = option;
					option.attr('selected', 'selected');
					
					// 🔥 GUARDAR PRIMERA GENERACIÓN GLOBALMENTE
					if (index === 0) {
						window.generacionCambioPlantel = option.val();
						console.log('💾 Primera generación guardada:', option.val());
					}
				}
			} else {
				option.hide();
			}
		});

		if (total > 0 && firstVisible) {
			selectGeneracion.val(firstVisible.val());
		}
	}

	function limpiar_generaciones(index) {
		var selectGeneracion = $('.seleccionGeneracion').eq(index);
		selectGeneracion.find('option').hide().removeAttr('selected');
		selectGeneracion.val('');
	}

	// 🔥🔥🔥 VALIDAR Y RECOPILAR DATOS 🔥🔥🔥
	function validarYRecopilarDatos() {
		var totalAlumnos = $('.seleccionAlumnos').length;
		var datosValidos = [];
		
		for(var i = 0; i < totalAlumnos; i++) {
			var id_alu = $('.seleccionAlumnos').eq(i).attr('id_alu');
			var id_pla = $('.seleccionPlantel').eq(i).val();
			var id_ram = $('.seleccionPrograma').eq(i).val();
			var id_gen = $('.seleccionGeneracion').eq(i).val();

			if(!id_pla || !id_ram || !id_gen) {
				swal({
					title: 'Campos incompletos',
					text: 'Complete todos los campos para el alumno ' + (i+1),
					icon: 'warning',
					button: 'Entendido'
				});
				return null;
			}

			var id_alu_ram = $(".trasladarPagos").eq(i).attr('id_alu_ram');
			var trasladarPagos = $(".trasladarPagos")[i].checked ? 'true' : 'false';
			var eliminacionAluRam = $(".eliminarAluRam")[i].checked ? 'true' : 'false';
			
			// 🔥 GUARDAR PRIMERA GENERACIÓN PARA REDIRECCIÓN
			if (i === 0 && id_gen) {
				window.generacionCambioPlantel = id_gen;
				console.log('🎯 Primera generación para redirect:', id_gen);
			}

			datosValidos.push({
				id_alu: id_alu,
				id_pla: id_pla,
				id_ram: id_ram,
				id_gen: id_gen,
				id_alu_ram: id_alu_ram,
				trasladarPagos: trasladarPagos,
				eliminacionAluRam: eliminacionAluRam
			});
		}
		
		return datosValidos;
	}

	// 🔥🔥🔥 FUNCIÓN GLOBAL PARA PROCESAR DATOS 🔥🔥🔥
	window.procesarCambioPlantelDatos = function() {
		console.log('⚡ procesarCambioPlantelDatos() ejecutándose...');
		
		const datosValidados = validarYRecopilarDatos();
		
		if (!datosValidados) {
			console.error('❌ Validación fallida');
			return Promise.reject('Validación fallida');
		}
		
		var procesamientos = 0;
		var totalAlumnos = datosValidados.length;
		var errores = 0;

		console.log('📋 Procesando', totalAlumnos, 'alumnos...');

		return new Promise((resolve, reject) => {
			datosValidados.forEach((datos, index) => {
				$.ajax({
					url: 'server/agregar_alumno_rama.php',
					type: 'POST',
					data: datos,
					success: function(respuesta) {
						console.log('✅ Alumno', datos.id_alu, ':', respuesta);
						procesamientos++;
						
						if(procesamientos >= totalAlumnos) {
							console.log('🏁 Todos procesados. Errores:', errores);
							resolve({ total: totalAlumnos, errores: errores });
						}
					},
					error: function(xhr, status, error) {
						console.error('❌ Error alumno', datos.id_alu, ':', error);
						errores++;
						procesamientos++;
						
						if(procesamientos >= totalAlumnos) {
							console.log('🏁 Todos procesados. Errores:', errores);
							resolve({ total: totalAlumnos, errores: errores });
						}
					}
				});
			});
		});
	};

	console.log('✅ window.procesarCambioPlantelDatos() disponible globalmente');
</script>a