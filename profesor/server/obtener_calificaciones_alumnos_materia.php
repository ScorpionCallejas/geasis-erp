<?php  
	//ARCHIVO VIA AJAX PARA OBTENER CALIFICACIONES DE TODOS LOS ALUMNOS QUE TOMAN LA ASIGNATURA
	//materias_horario.php 
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_sub_hor = $_POST['id_sub_hor'];

	//echo $id_mat1;
	$sqlHorario = "
		SELECT *
		FROM sub_hor
		INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
		INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
		INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
		INNER JOIN alu_hor ON alu_hor.id_sub_hor5 = sub_hor.id_sub_hor
		INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
		INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
		INNER JOIN rama ON rama.id_ram = materia.id_ram2
		WHERE id_pro1 = '$id' AND id_sub_hor5 = '$id_sub_hor' AND est_alu_hor = 'Activo'
		ORDER BY app_alu ASC
	";

	//echo $sqlHorario;
	$resultadoHorario = mysqli_query($db, $sqlHorario);
	
	$resultadoHorarioTitulo = mysqli_query($db, $sqlHorario);
	$filaHorario = mysqli_fetch_assoc($resultadoHorarioTitulo);
	
	// Validar que existan datos
	if (!$filaHorario) {
		echo '<div class="alert alert-warning">No se encontraron datos para esta materia.</div>';
		exit;
	}
	
	$nom_ram = $filaHorario['nom_ram'];
	$id_mat = $filaHorario['id_mat'];
	$nombreMateria = $filaHorario['nom_mat'];
	$eva_ram = $filaHorario['eva_ram'];

	// VALIDACION ACCESO
	$resultadoValidacionAcceso = mysqli_query($db, $sqlHorario);
	$totalValidacion = mysqli_num_rows($resultadoValidacionAcceso);

?>

<div class="row">
	<div class="col-md-12">
		<form id="formularioCalificacion">
			<table class="table table-sm text-center table-hover table-bordered" cellspacing="0" width="100%" id="myTableCalificaciones">
				<thead class="grey lighten-2">
					<tr>
						<th class="letraPequena font-weight-normal">#</th>
						<th class="letraPequena font-weight-normal">Matrícula</th>
						<th class="letraPequena font-weight-normal">Alumno</th>
						
						<!-- EXTRACCION PARA HEADERS DE TABLA DE CANTIDAD DE EVALUACIONES -->
						<?php 
							for($j = 1; $j <= $eva_ram; $j++ ){
						?>
							<th class="letraPequena font-weight-normal">
								<?php echo $j."° Parcial"; ?>
							</th>
						<?php
							}
						?>

						<th class="letraPequena font-weight-normal">Trabajo final</th>
						<th class="letraPequena font-weight-normal">Final</th>
					</tr>
				</thead>

				<tbody>
					<?php
						$contador = 1;
						
						// Reiniciar el resultado para el loop principal
						$resultadoHorario = mysqli_query($db, $sqlHorario);

						while($filaHorario = mysqli_fetch_assoc($resultadoHorario)){
							$id_alu_ram = $filaHorario['id_alu_ram'];
							$id_mat_actual = $filaHorario['id_mat'];

					?>

					<tr>
						<td class="letraPequena font-weight-normal">
							<?php echo $contador; $contador++; ?>
						</td>

						<td class="letraPequena font-weight-bold">
							<?php echo $filaHorario['bol_alu']; ?>
						</td>

						<td class="letraPequena font-weight-normal text-left">
							<?php 
								echo $filaHorario['app_alu']." ".$filaHorario['apm_alu']." ".$filaHorario['nom_alu']; 
								
								// VALIDACION DE ALUMNO A FUSION DE OTRO PLANTEL
								if ( $filaHorario['id_fus2'] != NULL ) {
									$id_fus = $filaHorario['id_fus2'];

									$sqlValidacion = "
										SELECT * 
										FROM sub_hor 
										INNER JOIN calificacion ON calificacion.id_mat4 = sub_hor.id_mat1
										INNER JOIN fusion ON fusion.id_fus = sub_hor.id_fus2 
										WHERE ( id_fus2 = '$id_fus' ) AND ( id_sub_hor_nat IS NOT NULL ) AND ( id_alu_ram2 = '$id_alu_ram' )
									";

									$datos = obtener_datos_consulta( $db, $sqlValidacion );

									if ( $datos['total'] > 0 ) {
										$id_mat_actual = $datos['datos']['id_mat4'];
									}
								}
							?>
						</td>
						
						<?php
							// CONSULTA DE PARCIALES X ALUMNO
							$sqlEvaluacionParcial = "
								SELECT *
								FROM parcial
								WHERE id_alu_ram9 = '$id_alu_ram' AND id_mat3 = '$id_mat_actual'
								ORDER BY id_par ASC
							";

							$resultadoValidacionParciales = mysqli_query($db, $sqlEvaluacionParcial);
							$totalValidacionParciales = mysqli_num_rows($resultadoValidacionParciales);

							// Si no existen parciales, crearlos
							if ($totalValidacionParciales == 0) {
								for($k = 1; $k <= $eva_ram; $k++ ){
									$sqlInsercionParcial = "
										INSERT INTO parcial (id_alu_ram9, id_mat3) VALUES('$id_alu_ram', '$id_mat_actual')
									";
									$resultado1 = mysqli_query($db, $sqlInsercionParcial);
									
									if ( !$resultado1 ) {
										echo "<!-- Error insertando parcial: ".$sqlInsercionParcial." -->";
									}
								}

								// CALIFICACIONES
								$get_validacion = validacion_insert($id_alu_ram, $id_mat_actual);
								
								if ($get_validacion != 'no') {
									$sqlInsercionCalificacion = "
										INSERT INTO calificacion (id_alu_ram2, id_mat4) VALUES('$id_alu_ram', '$id_mat_actual')
									";
									$resultado2 = mysqli_query($db, $sqlInsercionCalificacion);
									
									if ( !$resultado2 ) {
										echo "<!-- Error insertando calificacion: ".$sqlInsercionCalificacion." -->";
									}
								}
							}

							// Consultar parciales nuevamente después de inserción
							$resultadoEvaluacionParcial = mysqli_query($db, $sqlEvaluacionParcial);
							$parciales_array = array();
							
							while ($filaEvaluacionParcial = mysqli_fetch_assoc($resultadoEvaluacionParcial)) {
								$parciales_array[] = $filaEvaluacionParcial;
							}

							// Mostrar campos de parciales
							for($m = 0; $m < $eva_ram; $m++) {
								if(isset($parciales_array[$m])) {
									$parcial = $parciales_array[$m];
						?>
							<td class="letraPequena">
								<input type="hidden" name="id_par[]" value="<?php echo $parcial['id_par']; ?>">
								<input type="number" 
									   min="0" 
									   max="10" 
									   step="0.1" 
									   class="form-control form-control-sm evaluaciones" 
									   name="cal_par[]" 
									   value="<?php echo ($parcial['cal_par'] != NULL) ? $parcial['cal_par'] : ''; ?>"
									   placeholder="0.0">
							</td>
						<?php
								} else {
						?>
							<td class="letraPequena">
								<input type="hidden" name="id_par[]" value="">
								<input type="number" 
									   min="0" 
									   max="10" 
									   step="0.1" 
									   class="form-control form-control-sm evaluaciones" 
									   name="cal_par[]" 
									   placeholder="0.0">
							</td>
						<?php
								}
							}

							// CALIFICACIONES FINALES
							$sqlEvaluacionCalificacion = "
								SELECT *
								FROM calificacion
								WHERE id_alu_ram2 = '$id_alu_ram' AND id_mat4 = '$id_mat_actual'
								LIMIT 1
							";
							
							remover_duplicado_alumno($id_alu_ram, $id_mat_actual);
							$resultadoEvaluacionCalificacion = mysqli_query($db, $sqlEvaluacionCalificacion);
							$filaEvaluacionCalificacion = mysqli_fetch_assoc($resultadoEvaluacionCalificacion);

							if($filaEvaluacionCalificacion) {
						?>
							<td class="letraPequena">
								<input type="hidden" name="id_cal[]" value="<?php echo $filaEvaluacionCalificacion['id_cal']; ?>">
								<input type="number" 
									   min="0" 
									   max="10" 
									   step="0.1" 
									   class="form-control form-control-sm evaluaciones" 
									   name="ext_cal[]" 
									   value="<?php echo ($filaEvaluacionCalificacion['ext_cal'] != null) ? $filaEvaluacionCalificacion['ext_cal'] : ''; ?>"
									   placeholder="0.0">
							</td>

							<td class="letraPequena">
								<input type="number" 
									   min="0" 
									   max="10" 
									   step="0.1" 
									   class="form-control form-control-sm evaluaciones" 
									   name="fin_cal[]" 
									   value="<?php echo ($filaEvaluacionCalificacion['fin_cal'] != NULL) ? $filaEvaluacionCalificacion['fin_cal'] : ''; ?>"
									   placeholder="0.0">
							</td>
						<?php
							} else {
						?>
							<td class="letraPequena">
								<input type="hidden" name="id_cal[]" value="">
								<input type="number" 
									   min="0" 
									   max="10" 
									   step="0.1" 
									   class="form-control form-control-sm evaluaciones" 
									   name="ext_cal[]" 
									   placeholder="0.0">
							</td>

							<td class="letraPequena">
								<input type="number" 
									   min="0" 
									   max="10" 
									   step="0.1" 
									   class="form-control form-control-sm evaluaciones" 
									   name="fin_cal[]" 
									   placeholder="0.0">
							</td>
						<?php
							}
						?>

					</tr>

					<?php
						} // FIN WHILE
					?>
					
				</tbody>

			</table>
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" id="btn_enviar" class="btn btn-rounded btn-sm btn-primary waves-effect">
						<i class="fas fa-save"></i> Guardar Calificaciones
					</button>
				</div>
			</div>
			
		</form>	
		
	</div>
</div>

<script>
$(document).ready(function () {

	$('#myTableCalificaciones').DataTable({
		dom: 'Bfrt',
		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
		"pageLength": -1,
		"scrollX": true,
		"autoWidth": false,
		
		buttons: [
			{
				extend: 'excelHtml5',
				text: '<i class="fas fa-file-excel"></i> Excel',
				className: 'btn btn-success btn-sm',
				exportOptions: {
					columns: ':visible'
				},
			},                  
			{
				extend: 'copyHtml5',
				text: '<i class="fas fa-copy"></i> Copiar',
				className: 'btn btn-info btn-sm',
				exportOptions: {
					columns: ':visible'
				},
			},
			{
				extend: 'print',
				text: '<i class="fas fa-print"></i> Imprimir',
				className: 'btn btn-secondary btn-sm',
				exportOptions: {
					columns: ':visible'
				},
			},
			{
				extend: 'pdf',
				text: '<i class="fas fa-file-pdf"></i> PDF',
				className: 'btn btn-danger btn-sm',
				exportOptions: {
					columns: ':visible'
				},
			},
		],

		"language": {
			"sProcessing":     "Procesando...",
			"sLengthMenu":     "Mostrar _MENU_ registros",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
			"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix":    "",
			"sSearch":         "Buscar:",
			"sUrl":            "",
			"sInfoThousands":  ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
				"sFirst":    "Primero",
				"sLast":     "Último",
				"sNext":     "Siguiente",
				"sPrevious": "Anterior"
			},
			"oAria": {
				"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}
		}
	});

	// Configuración de DataTable wrapper
	$('#myTableCalificaciones_wrapper').find('label').each(function () {
		$(this).parent().append($(this).children());
	});
	
	$('#myTableCalificaciones_wrapper .dataTables_filter').find('input').each(function () {
		$(this).attr("placeholder", "Buscar...");
		$(this).removeClass('form-control-sm');
	});
	
	$('#myTableCalificaciones_wrapper .dataTables_length').addClass('d-flex flex-row');
	$('#myTableCalificaciones_wrapper .dataTables_filter').addClass('md-form');
	$('#myTableCalificaciones_wrapper select').removeClass('custom-select custom-select-sm form-control form-control-sm');
	$('#myTableCalificaciones_wrapper select').addClass('mdb-select');
	$('#myTableCalificaciones_wrapper .mdb-select').materialSelect();
	$('#myTableCalificaciones_wrapper .dataTables_filter').find('label').remove();
	
	// Agregar clases a botones
	var botones = $('#myTableCalificaciones_wrapper .dt-buttons').children().addClass('btn btn-sm waves-effect m-1');

	// Validación de inputs numéricos
	$('.evaluaciones').on('input', function() {
		var valor = parseFloat($(this).val());
		if (valor > 10) {
			$(this).val(10);
		}
		if (valor < 0) {
			$(this).val(0);
		}
	});

});
</script>

<script>
$("#formularioCalificacion").on('submit', function(event) {
	event.preventDefault();

	// Validar que el formulario tenga datos
	var formData = new FormData(this);
	var hasData = false;
	
	for (var pair of formData.entries()) {
		if (pair[1] !== '') {
			hasData = true;
			break;
		}
	}

	if (!hasData) {
		swal("Error", "No hay datos para guardar", "error");
		return;
	}

	// Deshabilitar botón durante el envío
	$('#btn_enviar').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

	$.ajax({
		url: 'server/editar_evaluaciones_alumnos_materia.php',
		type: 'POST',
		data: formData,
		processData: false,
		contentType: false,
		cache: false,
		success: function(respuesta){
			console.log(respuesta);

			$('#btn_enviar').prop('disabled', false).html('<i class="fas fa-save"></i> Guardar Calificaciones');

			if (respuesta.trim() == 'Exito') {
				swal("Guardado correctamente", "Las calificaciones se han actualizado", "success", {
					button: "Aceptar",
				});
			} else {
				swal("Error", "Hubo un problema al guardar: " + respuesta, "error");
			}
		},
		error: function(xhr, status, error) {
			$('#btn_enviar').prop('disabled', false).html('<i class="fas fa-save"></i> Guardar Calificaciones');
			swal("Error", "Error de conexión: " + error, "error");
		}
	});								

});
</script>