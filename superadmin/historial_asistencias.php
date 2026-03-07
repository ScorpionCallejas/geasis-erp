<?php  

	include('inc/header.php');



	$id_alu_ram = $_GET['id_alu_ram'];
	$sqlDatos = "
		SELECT *
		FROM rama
		INNER JOIN materia ON materia.id_ram2 = rama.id_ram
		INNER JOIN alu_ram ON alu_ram.id_ram3 = rama.id_ram
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

	if ($totalDatos == 0) {
		header('location: not_found_404_page.php');
	}


	$resultadoDatosAsistencia = mysqli_query($db, $sqlDatos);


	// GENERADOR DE FALTAS
	generadorFaltas($id_alu_ram);

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
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Ramas"><i class="fas fa-bookmark"></i> Detalles de Asistencia</span>
		<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="ramas.php" title="Vuelve a Productos">Productos</a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="alumnos_carrera.php?id_ram=<?php echo $id_ram; ?>" title="Vuelve a Alumnos">Alumnos</a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Asistencia</a>
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
<div class="jumbotron text-center mdb-color  grey lighten-4  black-text mx-2 mb-5">
	
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
		<div class="col-md-6">
			<span class="animated fadeInDown ">
				<small>
					Haz clic sobre el número de asistencias o faltas para conocer las fechas
				</small>
			</span>
			<table id="myTable" class="table table-hover animated fadeInDown">
				<thead class="bg-info text-white">
					<tr>
						<th>#</th>
						<th>Materia</th>
						<th>Nivel</th>
						
						<th>Asistencias</th>
						<th>Faltas</th>
						<th>Total</th>
						
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
							
							<a href="#" class="asistencias text-primary" materia="<?php echo $id_mat; ?>" nom_mat="<?php echo $filaDatos['nom_mat']; ?>" title="Haz clic sobre el número de asistencias o faltas para conocer las fechas">
								<?php 

									
									$sqlTotalAsistencia = "
										SELECT COUNT(tip_asi) AS totalAsistencia
										FROM asistencia
										INNER JOIN materia ON materia.id_mat = asistencia.id_mat5
										WHERE id_alu_ram3 = '$id_alu_ram' AND tip_asi = 'Asistencia' AND id_mat5 = '$id_mat' 
									";

									$resultadoTotalAsistencia = mysqli_query($db, $sqlTotalAsistencia);
									$filaTotalAsistencia = mysqli_fetch_assoc($resultadoTotalAsistencia);

									echo $filaTotalAsistencia['totalAsistencia'];

								?>
							</a>
							
								
						</td>
						
						<td>

							<a href="#" class="faltas text-primary" materia="<?php echo $id_mat; ?>" nom_mat="<?php echo $filaDatos['nom_mat']; ?>" title="Haz clic sobre el número de asistencias o faltas para conocer las fechas">
								<?php 

									$id_mat = $filaDatos['id_mat'];
									$sqlTotalFalta = "
										SELECT COUNT(tip_asi) AS totalFalta
										FROM asistencia
										INNER JOIN materia ON materia.id_mat = asistencia.id_mat5
										WHERE id_alu_ram3 = '$id_alu_ram' AND tip_asi = 'Falta' AND id_mat5 = '$id_mat' 
									";

									$resultadoTotalFalta = mysqli_query($db, $sqlTotalFalta);
									$filaTotalFalta = mysqli_fetch_assoc($resultadoTotalFalta);

									echo $filaTotalFalta['totalFalta'];

								?>
							</a>
							

							
						</td>


						<td>
							
							<?php
								//CONSULTA TOTAL ASISTENCIAS 
								echo $filaTotalAsistencia['totalAsistencia'] + $filaTotalFalta['totalFalta'];
							?>
								
						</td>

						
					</tr>


				<?php
					} 

				?>
			</table>
		</div>
		<div class="col-md-3" id="datos">
			
		</div>
	</div>
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


<script>
	//FALTAS
	$(".faltas").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */


		var id_alu_ram = <?php echo $id_alu_ram; ?>;
		var id_mat = $(this).attr("materia");
		var nom_mat = $(this).attr("nom_mat");

		$.ajax({
			url: 'server/obtener_faltas.php',
			type: 'POST',
			data: {id_alu_ram, id_mat},
			success: function(respuesta){
				console.log(respuesta);

				$("#datos").html(respuesta);
				$("#titulo").html("Faltas de "+nom_mat);
			}
		});
	});


	//ASISTENCIAS
	$(".asistencias").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */


		var id_alu_ram = <?php echo $id_alu_ram; ?>;
		var id_mat = $(this).attr("materia");
		var nom_mat = $(this).attr("nom_mat");

		$.ajax({
			url: 'server/obtener_asistencias.php',
			type: 'POST',
			data: {id_alu_ram, id_mat},
			success: function(respuesta){
				//console.log(respuesta);

				$("#datos").html(respuesta);
				$("#titulo").html("Asistencias de "+nom_mat);
			}
		});
	});

	
	
</script>