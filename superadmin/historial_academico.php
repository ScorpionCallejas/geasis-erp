<?php  

	include('inc/header.php');



	$id_alu_ram= $_GET['id_alu_ram'];
	$sqlDatos = "
		SELECT *
		FROM materia
		INNER JOIN calificacion ON calificacion.id_mat4 = materia.id_mat
		INNER JOIN alu_ram ON alu_ram.id_alu_ram = calificacion.id_alu_ram2
		INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
		INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
		WHERE id_alu_ram = '$id_alu_ram'
		ORDER BY cic_mat ASC
	
	";

	$resultadoDatos = mysqli_query($db, $sqlDatos);
	$fila_rama = mysqli_fetch_assoc($resultadoDatos);
	$nom_ram = $fila_rama['nom_ram'];
	$id_ram = $fila_rama['id_ram'];
	//echo $sqlDatos;

	// DATOS ALUMNO
	$nombreAlumno = $fila_rama['nom_alu']." ".$fila_rama['app_alu']." ".$fila_rama['apm_alu'];
	$fotoAlumno = $fila_rama['fot_alu'];
	$ingresoAlumno = $fila_rama['ing_alu'];

	$totalDatos = mysqli_num_rows($resultadoDatos);

	// if ($totalDatos == 0) {
	// 	header('location: not_found_404_page.php');
	// }


	$resultadoDatosAsistencia = mysqli_query($db, $sqlDatos);



?>
<style>
	#datosAlumno{
	  position: -webkit-sticky;
	  position: sticky;
	  top: 50px;

	}
</style>
      
 <!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Todas tus calificaciones"><i class="fas fa-bookmark"></i> Historial Académico</span>
		<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="ramas.php" title="Vuelve a Productos">Programas</a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="alumnos.php" title="Vuelve a Alumnos">Alumnos</a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Historial de Académico</a>
		</div>
	</div>
	<div class="col text-right">
		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Calendario de <?php echo $nom_ram; ?>">
			<i class="fas fa-certificate"></i>
			Producto: <?php echo $nom_ram; ?>
		</span>	
	</div>
</div>
<!-- FIN TITULO -->

<!-- Jumbotron -->
<div class="jumbotron text-center mdb-color  grey lighten-4 mx-2 mb-5">
	
	<!-- ROW -->
	<div class="row">
		<!-- DATOS ALUMNO -->
		<div class="col-md-3">
			<!-- Card -->
				<div class="card testimonial-card" id="datosAlumno">

				  <!-- Background color -->
				  <div class="card-up  bg-info"></div>

				  <!-- Avatar -->
				  <div class="avatar mx-auto white file-upload-wrapper view modalFoto">
				  	<div class="view overlay">
				    	<img src="../uploads/<?php echo $fotoAlumno; ?>" class="rounded-circle modalFoto" style="border-radius: 50%;" alt="woman avatar" id="avatar">
				    </div>
				  </div>

				  <!-- Content -->
				  <div class="card-body">
				    <!-- Name -->
					<h4 class="card-title"><?php echo $nombreAlumno; ?></h4>
					<h6 class="card-title">Ingreso: <?php echo fechaFormateadaCompacta($ingresoAlumno); ?></h6>
				    <h3 class="card-title">Promedio
				    	<br>
				    	<span class="text-warning">
				    		<?php
								$sqlPromedioFinal = "
									SELECT avg(fin_cal) AS promedio
									FROM calificacion 
									WHERE id_alu_ram2 = '$id_alu_ram'
								";  

								$resultadoPromedioFinal = mysqli_query($db, $sqlPromedioFinal);

								$filaPromedioFinal = mysqli_fetch_assoc($resultadoPromedioFinal);

								echo round($filaPromedioFinal['promedio'], 2);


							?>
				    	</span>
						
				    </h3>
				    
				    

				  </div>

				</div>
				<!-- Card -->


		</div>
		<!-- FIN DATOS ALUMNO -->
		
		<!-- COL -->
		<div class="col-md-9">

				<table id="myTable" class="table table-hover animated fadeInDown" cellspacing="0" width="99%">
					<thead>
						<tr>
							<th>#</th>
							<th>Materia</th>
							<th>Nivel</th>
							<th>Extra</th>
							<th>Final</th>
						</tr>
					</thead>


					<?php 
						$i = 1;
						while($filaDatos = mysqli_fetch_assoc($resultadoDatosAsistencia)){
							$id_mat = $filaDatos['id_mat'];
					?>
						<tr>
							<td>
								<?php echo $i; $i++;?>
									
							</td>
					
							<td>
								<?php  

									echo $filaDatos['nom_mat'];
								?>	
							</td>


							<td>
								<?php  
									echo $filaDatos['cic_mat'];
								?>
							</td>
							
							<td>
								
								<a href="#">
									<?php
										if ( $filaDatos['ext_cal'] == NULL) {
										 	echo "Pendiente";
										}else{
											echo $filaDatos['ext_cal']; 
										} 
										
									?>
								</a>
								
									
							</td>

							<td>
								
								<a href="#">
									<?php
										if ( $filaDatos['fin_cal'] == NULL) {
										 	echo "Pendiente";
										}else{
											echo $filaDatos['fin_cal']; 
										} 
										
									?>
								</a>
								
									
							</td>


							
						</tr>


					<?php
						} 

					?>
				</table>

				<?php 
				
					$sqlEvaluacion = "
						SELECT *
						FROM materia
						INNER JOIN calificacion ON calificacion.id_mat4 = materia.id_mat
						INNER JOIN alu_ram ON alu_ram.id_alu_ram = calificacion.id_alu_ram2
						INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
						INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
						WHERE id_alu_ram = '$id_alu_ram'
						ORDER BY cic_mat ASC
					";

					//echo $sqlEvaluacion;
					$resultadoEvaluacion = mysqli_query($db, $sqlEvaluacion);
					

					$resultadoEvaluacionTitulo = mysqli_query($db, $sqlEvaluacion);
					$filaEvaluacion = mysqli_fetch_assoc($resultadoEvaluacionTitulo);
					$nom_ram = $filaEvaluacion['nom_ram'];
					$eva_ram = $filaEvaluacion['eva_ram'];



					// VALIDACCION ACCESO
					$resultadoValidacionAcceso = mysqli_query($db, $sqlEvaluacion);
					$totalValidacion = mysqli_num_rows($resultadoValidacionAcceso);

				?>
				
				<br>
				<br>

				<form id="formularioCalificacion">
					<table class="table table-sm text-center table-hover animated fadeInDown delay-1s" cellspacing="0" width="99%" id="myTableCalificaciones">
						<thead>
							<tr>
								<th>#</th>
								<th>Materia</th>
								<th>Nivel</th>
								<th>Extra</th>
								<th>Final</th>
							</tr>
						</thead>

						<tbody >
							
								<?php
									$i = 1;

									while($filaEvaluacion = mysqli_fetch_assoc($resultadoEvaluacion)){

								?>

									<tr>
										<td>
											<?php echo $i; $i++;  ?>
										</td>

										<td>
											<?php echo $filaEvaluacion['nom_mat']; ?>
										</td>

										<td>
											<?php echo $filaEvaluacion['cic_mat']; ?>
										</td>
										

										<?php  
											$id_mat = $filaEvaluacion['id_mat'];
											$sqlEvaluacionCalificacion = "
												SELECT *
												FROM calificacion
												WHERE id_alu_ram2 = '$id_alu_ram' AND id_mat4 = '$id_mat'
											";

											$resultadoEvaluacionCalificacion = mysqli_query($db, $sqlEvaluacionCalificacion);

											while ($filaEvaluacionCalificacion = mysqli_fetch_assoc($resultadoEvaluacionCalificacion)) {
										?>
											<td>

		                                        <input type="hidden" name="id_cal[]" value="<?php echo $filaEvaluacionCalificacion['id_cal']; ?> ">
		                                        <?php
		                                        
		                                            if ($filaEvaluacionCalificacion['ext_cal'] == NULL) {
		                                        ?>
		                                            
		                                            <input type="number" min="0" step=".1" class="form-control evaluaciones" name="ext_cal[]">
		                                        <?php
		                                            }else{
		                                        ?>
		                                            
		                                            <input type="number"  min="0" step=".1" class="form-control evaluaciones" name="ext_cal[]" value="<?php echo $filaEvaluacionCalificacion['ext_cal']; ?>">

		                                        <?php
		                                            }
		                                        
		                                        ?>
		                                    </td>

		                                    <td>
		                                        <?php
		                                        
		                                            if ($filaEvaluacionCalificacion['fin_cal'] == NULL) {
		                                        ?>
		                                 
		                                            <input type="number" min="0" step=".1" class="form-control evaluaciones" name="fin_cal[]">
		                                        <?php
		                                            }else{
		                                        ?>
		                                           
		                                            <input type="number"  min="0" step=".1" class="form-control evaluaciones" name="fin_cal[]" value="<?php echo $filaEvaluacionCalificacion['fin_cal']; ?>">

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
		<!-- FIN COL -->

	</div>
	<!-- FIN ROW -->
	
</div>
<!-- Jumbotron -->





<?php  

	include('inc/footer.php');

?>
<script>
	$(document).ready(function () {


		$('#myTable').DataTable({
			
		
			dom: 'Bfrtlip',
            
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
		$('#myTable_wrapper').find('label').each(function () {
			$(this).parent().append($(this).children());
		});
		$('#myTable_wrapper .dataTables_filter').find('input').each(function () {
			$('#myTable_wrapper input').attr("placeholder", "Buscar...");
			$('#myTable_wrapper input').removeClass('form-control-sm');
		});
		$('#myTable_wrapper .dataTables_length').addClass('d-flex flex-row');
		$('#myTable_wrapper .dataTables_filter').addClass('md-form');
		$('#myTable_wrapper select').removeClass(
		'custom-select custom-select-sm form-control form-control-sm');
		$('#myTable_wrapper select').addClass('mdb-select');
		$('#myTable_wrapper .mdb-select').materialSelect();
		$('#myTable_wrapper .dataTables_filter').find('label').remove();
		var botones = $('#myTable_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
		//console.log(botones);

	
	});
</script>


<!-- EVALUACIONES -->


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


<script>
	$("#formularioCalificacion").on('submit', function(event) {
		event.preventDefault();

		$.ajax({
                                        
            url: 'server/editar_evaluacion_alumno.php?id_alu_ram=<?php echo $id_alu_ram; ?>',
            type: 'POST',
            data: new FormData(formularioCalificacion),
            processData: false,
            contentType: false,
            cache: false,
            success: function(respuesta){
                console.log(respuesta);

                if (respuesta == 'Exito') {
                    swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).then((value) => {
					  window.location.reload();
					});
                }
            }
        });								

	});
</script>