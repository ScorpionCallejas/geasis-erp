<?php  
		

  include('inc/header.php');
?>
<!-- CONTENIDO -->
<?php 
	$id_alu_ram= $_GET['id_alu_ram'];
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
		WHERE id_alu_ram1 = '$id_alu_ram'
		GROUP BY id_sub_hor
		";

	//echo $sqlHorario;
	$resultadoHorario = mysqli_query($db, $sqlHorario);
	

	$resultadoHorarioTitulo = mysqli_query($db, $sqlHorario);
	$filaHorario = mysqli_fetch_assoc($resultadoHorarioTitulo);
	$nom_ram = $filaHorario['nom_ram'];


	// VALIDACCION ACCESO
	$resultadoValidacionAcceso = mysqli_query($db, $sqlHorario);
	$totalValidacion = mysqli_num_rows($resultadoValidacionAcceso);

	
	if ($totalValidacion == 0) {
		header('location: not_found_404_page.php');
	}



	//DATOS CARRERA
	$nombreRama = $filaHorario['nom_ram'];

	// DATOS ALUMNO
	$nombreAlumno = $filaHorario['nom_alu']." ".$filaHorario['app_alu']." ".$filaHorario['apm_alu'];
	$fotoAlumno = $filaHorario['fot_alu'];
	$ingresoAlumno = $filaHorario['ing_alu'];
	$qrAlumno = $filaHorario['qr_alu'];

	
?>
<!-- 

<style>
	.botonHijo {
		position: absolute;
		right: 5%;
		top: 5%; 
	}

	.botonPadre {
		position: relative;
	}
</style>
 -->
 <!-- TITULO -->

<div class="row ">
	<div class="col-md-3  text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Ramas"><i class="fas fa-bookmark"></i> Horario</span>
		<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="ramas.php" title="Vuelve a Ramas">Ramas</a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Horario</a>
		</div>


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
				<h5 class="card-title blue-text"><?php echo $nombreRama; ?></h5>
				<h6 class="card-title">Ingreso: <?php echo fechaFormateadaCompacta($ingresoAlumno); ?></h6>
			    
					
			    </h3>
			    
			    

			  </div>

			</div>
		<!-- Card -->		
	</div>


	<div class="col-md-3">
		
	</div>

	<div class="col-md-3">
		
	</div>

	<div class="col-md-3 text-right">
		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Horario de <?php echo $nom_ram; ?>">
			<i class="fas fa-certificate"></i>
			Carrera: <?php echo $nom_ram; ?>
		</span>	
	</div>
</div>
<!-- FIN TITULO -->
	<div class="row">

		<div class="col-md-12">
			<table class="table table-sm text-center table-hover" cellspacing="0" width="99%" id="myTable">
				<thead class="grey lighten-2">
					<tr>
						<th>#</th>
						<th>Profesor</th>
						<th>Materia</th>
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
								<?php echo $filaHorario['nom_pro']; ?>
							</td>


							<td>
								<?php echo $filaHorario['nom_mat']; ?>
							</td>

							
						</tr>


					<?php

						}
						//FIN WHILE
					?>
					
					
	
					
				</tbody>

			</table>
			
		</div>
		
			


	</div>


<!-- FIN CONTENIDO -->
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