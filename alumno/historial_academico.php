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
		WHERE id_alu_ram = '$id_alu_ram' AND id_alu = '$id'
		GROUP BY id_mat ORDER BY cic_mat ASC
	
	";

	$resultadoDatos = mysqli_query($db, $sqlDatos);
	$fila_rama = mysqli_fetch_assoc($resultadoDatos);
	$nom_ram = $fila_rama['nom_ram'];
	//echo $sqlDatos;


	$totalDatos = mysqli_num_rows($resultadoDatos);

	// if ($totalDatos == 0) {
	// 	header('location: not_found_404_page.php');
	// }


	$resultadoDatosAsistencia = mysqli_query($db, $sqlDatos);


?>
      
 <!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Todas tus calificaciones"><i class="fas fa-bookmark"></i>Calificaciones finales</span>
		<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="ramas.php" title="Vuelve a Ramas">Ramas</a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Calificaciones Finales</a>
		</div>		
	</div>
	<div class="col text-right">
		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Calendario de <?php echo $nom_ram; ?>">
			<i class="fas fa-certificate"></i>
			Carrera: <?php echo $nom_ram; ?>
		</span>	
		
		<br>
		<br>
		<span>
			Promedio
		</span>
		

		<h2 class="warning-text">
			<span class="badge badge-warning">
				<?php echo obtenerEvaluacion( $id_alu_ram ); ?>
			</span>
			
		</h2>
	</div>
</div>
<!-- FIN TITULO -->

<!-- Jumbotron -->
<div class="jumbotron text-center mdb-color  grey lighten-4  black-text mx-2 mb-5">
	
	<div class="row">
		<div class="col-md-6">
		
			<table id="myTable" class="table table-hover animated fadeInDown">
				<thead class="bg-info text-white">
					<tr>
						<th>#</th>
						<th>Materia</th>
						<th>Nivel</th>
						
						<th>Final</th>
						<th>Extra</th>
						
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
									if ( $filaDatos['fin_cal'] == NULL) {
									 	echo "Pendiente";
									}else{
										echo $filaDatos['fin_cal']; 
									} 
									
								?>
							</a>
							
								
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


						
					</tr>


				<?php
					} 

				?>
			</table>
		</div>

		<div class="col-md-6" id="datos">
			
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