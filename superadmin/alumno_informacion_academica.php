<?php  

	include('inc/header.php');



	$id_alu= $_GET['id_alu'];

	$sqlAlumno = "
		SELECT *
		FROM alumno
		WHERE id_alu = '$id_alu'
	";

	$resultadoAlumno = mysqli_query($db, $sqlAlumno);
	$filaAlumno = mysqli_fetch_assoc($resultadoAlumno);

	//echo $sqlDatos;

	// DATOS ALUMNO
	$nombreAlumno = $filaAlumno['nom_alu']." ".$filaAlumno['app_alu']." ".$filaAlumno['apm_alu'];
	$fotoAlumno = $filaAlumno['fot_alu'];
	$ingresoAlumno = $filaAlumno['ing_alu'];
	$correoAlumno = $filaAlumno['cor_alu'];
	$passwordAlumno = $filaAlumno['pas_alu'];

	$qrAlumno = $filaAlumno['qr_alu'];


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
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Todas tus calificaciones"><i class="fas fa-bookmark"></i> Información Académica de <?php echo $nombreAlumno; ?></span>
		<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="alumnos.php" title="Vuelve a alumnos">Alumnos</a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Historial de Académico</a>
		</div>
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
					<p class="text-info"><?php echo $correoAlumno; ?></p>
					<p class="text-info">Password: <?php echo $passwordAlumno; ?></p>
					<h6 class="card-title">Ingreso: <?php echo fechaFormateadaCompacta($ingresoAlumno); ?></h6>
				    
						
				    </h3>
				    
				    

				  </div>

				</div>
				<!-- Card -->


		</div>
		<!-- FIN DATOS ALUMNO -->
		
		<!-- COL -->
		<div class="col-md-9">

				<table id="myTable" class="table table-hover table-bordered table-sm" cellspacing="0" width="100%">
					<thead class="bg-info text-white">
						<tr>
							<th>#</th>
							<th title="Carrera">Nombre</th>
							<th title="Cuántos periodos dura la carrera">Ciclos</th>
							<th title="Tipo de Duración">Periodos</th>
							<th title="La colegiatura">Costo</th>
							<th title="Cuántas evaluaciones tienes por ciclo">Evaluaciones</th>
							<th title="Tipo de modalidad">Modalidad</th>
							<th title="Estatus del alumno respecto al ciclo">Ciclo</th>
							<th>Acción</th>
						</tr>
					</thead>


					<?php 
						$i = 1;

						$sqlConsultaRamas = "
					    	SELECT * 
					    	FROM alumno
					    	INNER JOIN alu_ram ON alu_ram.id_alu1 = alumno.id_alu
					    	INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
					    	INNER JOIN plantel ON plantel.id_pla = rama.id_pla1 
					    	WHERE id_alu = '$id_alu'";

					    $resultadoConsultaRamas = mysqli_query($db, $sqlConsultaRamas);	
						while($filaRamas = mysqli_fetch_assoc($resultadoConsultaRamas)){

					?>
						<tr>
							<td><?php echo $i; $i++;?></td>
					
							<td><?php echo $filaRamas['nom_ram']; ?></td>
							<td><?php echo $filaRamas['cic_ram']; ?></td>
							<td><?php echo $filaRamas['per_ram']; ?></td>
							<td><?php echo "$ ".$filaRamas['cos_ram']; ?></td>
							<td><?php echo $filaRamas['eva_ram']; ?></td>
							<td><?php echo $filaRamas['mod_ram']; ?></td>
							<td>
								<?php  
									$id_alu_ram = $filaRamas['id_alu_ram'];
									$sqlConsultaAluHor = "SELECT * FROM alu_hor WHERE id_alu_ram1 = '$id_alu_ram'";
									$resultadoAluHor = mysqli_query($db, $sqlConsultaAluHor);
									$totalAluHor = mysqli_num_rows($resultadoAluHor);
									//echo $totalAluHor;
									if($totalAluHor == 0){
								?>
										<span>Pendiente de Inscripción</span>		
								<?php
									}else {
								?>
										<span>Inscrito</span>
								<?php
									}
								?>
								
							</td>
							
							<!-- BOTONES DE ACCION -->
							<td>

								<?php  

											if ($filaRamas['mod_ram'] == 'Online') {
										?>

												<a href="inscribir_horario_online_alumno.php?id_alu_ram=<?php echo $filaRamas['id_alu_ram']; ?>" class="chip info-color text-white" title="Agregar o quitar horario">
													Inscribir
												</a>

										<?php  
											}else{
										?>	
												<a href="inscribir_horario_presencial_alumno.php?id_alu_ram=<?php echo $filaRamas['id_alu_ram']; ?>" class="chip info-color text-white" title="Agregar o quitar horario">
													Inscribir
												</a>

										<?php
											}
										?>	

								<?php  
									//echo $totalAluHor;

									if($totalAluHor == 0){
										// NO EXISTE HORARIO
								?>

										

								<?php
									}else{


										// ESTA INSCRITO
								?>
									<?php  

										if ($filaRamas['mod_ram'] == 'Online') {
									?>

											<a href="horario_online_alumno.php?id_alu_ram=<?php echo $filaRamas['id_alu_ram']; ?>" class="chip info-color text-white" title="Ver mi horario">Ver</a>
				
									<?php
										}else{
									?>
											<a href="horario_presencial_alumno.php?id_alu_ram=<?php echo $filaRamas['id_alu_ram']; ?>" class="chip info-color text-white" title="Ver mi horario">Ver</a>

									<?php
										}
									?>
										
									
								<?php
									}


								?>

								<?php  
									if ( $filaRamas['mod_ram'] == 'Presencial') {
								?>
										<!-- SI MODALIDAD ES PRESENCIAL ENTONCES TIENE ACCESO A SUS ASISTENCIAS, CASO CONTRARIO NO -->
										<a href="historial_asistencias.php?id_alu_ram=<?php echo $filaRamas['id_alu_ram']; ?>" class="chip info-color white-text" title="Historial de Asistencias para <?php echo $filaRamas['nom_ram']; ?>">
											Asistencias
										</a>


										

								<?php	
									}
								?>

								
								



								<a href="historial_academico.php?id_alu_ram=<?php echo $filaRamas['id_alu_ram']; ?>" class="chip info-color text-white" title="Historial Académico para <?php echo $filaRamas['nom_ram']; ?>">
									Historial
								</a>
								
							</td>
							<!-- FIN BOTONES DE ACCION -->

						</tr>


					<?php
						} 

					?>
				</table>

				<br>
			
		</div>
		<!-- FIN COL -->

	</div>
	<!-- FIN ROW -->


	<!-- QR -->
	<div class="row">
		<div class="col-md-4">
			
		</div>

		<div class="col-md-4 ">
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
				<div id="output" title="Código QR de <?php echo $nombreAlumno; ?>"></div>
			</svg>
			
		</div>


		<div class="col-md-4">
			
		</div>
		
	</div>
	<!-- FIN QR -->
        

	
</div>
<!-- Jumbotron -->





<?php  

	include('inc/footer.php');

?>


<script>
	jQuery('#output').qrcode({
		text: "<?php echo $qrAlumno; ?>",
		width: 300,
		height: 300
	});

</script>




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

