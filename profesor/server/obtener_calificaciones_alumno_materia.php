<?php  
	//ARCHIVO VIA AJAX PARA OBTENER CALIFICACIONES DE ALUMNO
	//materias_horario.php 
	require('../inc/cabeceras.php');
?>
<!-- CONTENIDO  -->
<?php 
	$id_alu_ram = $_POST['id_alu_ram'];
	$id_mat1 = $_POST['materia'];
	
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
		WHERE id_alu_ram1 = '$id_alu_ram' AND id_pro1 = '$id' AND id_mat1 = '$id_mat1'
		GROUP BY id_sub_hor
	";

	//echo $sqlHorario;
	$resultadoHorario = mysqli_query($db, $sqlHorario);
	

	$resultadoHorarioTitulo = mysqli_query($db, $sqlHorario);
	$filaHorario = mysqli_fetch_assoc($resultadoHorarioTitulo);
	$nom_ram = $filaHorario['nom_ram'];
	$eva_ram = $filaHorario['eva_ram'];

	$nombreAlumno = $filaHorario['nom_alu']." ".$filaHorario['app_alu']." ".$filaHorario['apm_alu'];
	$nombreMateria = $filaHorario['nom_mat'];


	// VALIDACCION ACCESO
	$resultadoValidacionAcceso = mysqli_query($db, $sqlHorario);
	$totalValidacion = mysqli_num_rows($resultadoValidacionAcceso);

?>

	<div class="row">

		<div class="col-md-12">
			<h4>Alumno: <?php echo $nombreAlumno; ?></h4>
			<h5>Materia: <?php echo $nombreMateria; ?></h5>
			<form id="formularioCalificacion">
			<table class="table table-sm text-center table-hover animated fadeInDown delay-1s" cellspacing="0" width="99%" id="myTableCalificaciones">
				<thead class="grey lighten-2">
					<tr>
						<th>#</th>
						<th>Materia</th>
						
						<!-- EXTRACCION PARA HEADERS DE TABLA DE CANTIDAD DE EVALUACIONES DIRECTAMENTE DE LA TABLA rama -->
						<?php 
						
							for($i = 1; $i <= $eva_ram; $i++ ){
						?>
							<th>
								<?php echo $i.""; ?> Parcial
							</th>
						<?php
							}

						?>

						<th>Trabajo final</th>
						<th>Final</th>
					</tr>
				</thead>

				<tbody >
					
						<?php
							$i = 1;

							while($filaHorario = mysqli_fetch_assoc($resultadoHorario)){

						?>

							<tr>
								<td>
									<?php echo $i; $i++;  ?>
								</td>

								<td>
									<?php echo $filaHorario['nom_mat']; ?>
								</td>
								
								<?php  
									$id_mat = $filaHorario['id_mat'];
									$sqlEvaluacionParcial = "
										SELECT *
										FROM parcial
										WHERE id_alu_ram9 = '$id_alu_ram' AND id_mat3 = '$id_mat'
									";


									$resultadoValidacionParciales = mysqli_query($db, $sqlEvaluacionParcial);

									$totalValidacionParciales = mysqli_num_rows($resultadoValidacionParciales);

									if ($totalValidacionParciales == 0) {
								?>
										<td>
											--
										</td>
								<?php
									}else{

										$resultadoEvaluacionParcial = mysqli_query($db, $sqlEvaluacionParcial);

											while ($filaEvaluacionParcial = mysqli_fetch_assoc($resultadoEvaluacionParcial)) {
										?>
											<td>
												<?php
		                                        
		                                            if ($filaEvaluacionParcial['cal_par'] == NULL) {
		                                        ?>
		                                            <input type="hidden" name="id_par[]" value="<?php echo $filaEvaluacionParcial['id_par']; ?>">
		                                            <input type="number" min="0" step=".1" class="form-control evaluaciones" name="cal_par[]">
		                                        <?php
		                                            }else{
		                                        ?>
		                                            <input type="hidden" name="id_par[]" value="<?php echo $filaEvaluacionParcial['id_par']; ?> ">
		                                            <input type="number"  min="0" step=".1" class="form-control evaluaciones" name="cal_par[]" value="<?php echo $filaEvaluacionParcial['cal_par']; ?>">

		                                        <?php
		                                            }
		                                        
		                                        ?>

												
											</td>
										<?php
											}
									}
								?>	

								<?php  
									$id_mat = $filaHorario['id_mat'];
									$sqlEvaluacionCalificacion = "
										SELECT *
										FROM calificacion
										WHERE id_alu_ram2 = '$id_alu_ram' AND id_mat4 = '$id_mat'
									";

									$resultadoEvaluacionCalificacion = mysqli_query($db, $sqlEvaluacionCalificacion);

									while ($filaEvaluacionCalificacion = mysqli_fetch_assoc($resultadoEvaluacionCalificacion)) {
								?>
									<td>

                                        <input type="hidden" name="id_cal" value="<?php echo $filaEvaluacionCalificacion['id_cal']; ?> ">
                                        <?php
                                        
                                            if ($filaEvaluacionCalificacion['ext_cal'] == NULL) {
                                        ?>
                                            
                                            <input type="number" min="0" step=".1" class="form-control evaluaciones" name="ext_cal">
                                        <?php
                                            }else{
                                        ?>
                                            
                                            <input type="number"  min="0" step=".1" class="form-control evaluaciones" name="ext_cal" value="<?php echo $filaEvaluacionCalificacion['ext_cal']; ?>">

                                        <?php
                                            }
                                        
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        
                                            if ($filaEvaluacionCalificacion['fin_cal'] == NULL) {
                                        ?>
                                 
                                            <input type="number" min="0" step=".1" class="form-control evaluaciones" name="fin_cal">
                                        <?php
                                            }else{
                                        ?>
                                           
                                            <input type="number"  min="0" step=".1" class="form-control evaluaciones" name="fin_cal" value="<?php echo $filaEvaluacionCalificacion['fin_cal']; ?>">

                                        <?php
                                            }
                                        
                                        ?>
                                    </td>
								<?php
									}

								?>

							</tr>


						<?php

							}
							//FIN WHILE
						?>
					
						
	
					
				</tbody>

			</table>
				<button type="submit" id="btn_enviar" class="btn btn-primary waves-effect">Guardar</button>
			</form>	
			
		</div>
		
		
	</div>


<!-- FIN CONTENIDO -->

<script>
	$(document).ready(function () {

		$('#myTableCalificaciones').DataTable({
			
		
			dom: 'frtlip',
            
            buttons: [

            
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },                  

                    {
                        
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':visible'
                        },

                    },

                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },

                    {
                        extend: 'pdf',
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

		$('#myTableCalificaciones_wrapper').find('label').each(function () {
			$(this).parent().append($(this).children());
		});
		$('#myTableCalificaciones_wrapper .dataTables_filter').find('input').each(function () {

			$('#myTableCalificaciones_wrapper #myTableCalificaciones_filter input').attr("placeholder", "Buscar...");

			// $('#myTableCalificaciones_wrapper input evaluaciones').attr("placeholder", "Buscar...");
			$('#myTableCalificaciones_wrapper input').removeClass('form-control-sm');
		});
		$('#myTableCalificaciones_wrapper .dataTables_length').addClass('d-flex flex-row');
		$('#myTableCalificaciones_wrapper .dataTables_filter').addClass('md-form');
		$('#myTableCalificaciones_wrapper select').removeClass(
		'custom-select custom-select-sm form-control form-control-sm');
		$('#myTableCalificaciones_wrapper select').addClass('mdb-select');
		$('#myTableCalificaciones_wrapper .mdb-select').materialSelect();
		$('#myTableCalificaciones_wrapper .dataTables_filter').find('label').remove();
		var botones = $('#myTableCalificaciones_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
		//console.log(botones);



	
	});
</script>