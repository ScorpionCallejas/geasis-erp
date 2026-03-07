<?php  

	include('inc/header.php');


?>


<style>
	.cabeceraTabla th{
		font-size: 12px;
	}

	.cuerpoTabla td{
		font-size: 12px;
	}
	.dropdown-toggle::after {
   content: none !important;
}
</style>


<!-- BOTON FLOTANTE AGREGAR RAMA-->
<a class="btn-floating btn-lg  flotante btn-info"><i class="fas fa-plus" title="Agregar Programa" id="agregarRama"></i></a>
<!-- FIN BOTON FLOTANTE AGREGAR RAMA-->

<!-- CONTENIDO -->
<!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Ramas"><i class="fas fa-bookmark"></i> Programas</span>
		<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Programas</a>
		</div>
		
	</div>
	
</div>
<!-- FIN TITULO -->



<!-- INDICADORES -->
<div class="row justify-content-center">


	<!-- TOTAL ALUMNOS -->
	<div class="col-md-2 text-center">
		<div class="card text-white bg-info mb-3" style="max-width: 20rem;">
			<div class="card-header bg-info">
				Alumnos Totales
			</div>
			<div class="card-body">
				<h2 class="card-title text-center">
					<span id="alumnosTotales">
					
					</span>
				</h2>
			</div>
		</div>
	</div>
	<!-- FIN TOTAL ALUMNOS -->

	<!-- TOTAL ALUMNOS INSCRITOS A CICLO ESCOLAR -->
	<div class="col-md-2 text-center">
		<div class="card text-white bg-info mb-3" style="max-width: 20rem;" title="Alumnos que ya se inscribieron a un ciclo escolar">
			<div class="card-header bg-info">
				Alumnos Activos
			</div>
			<div class="card-body text-center">
				<h2 class="card-title">
					<span id="alumnosInscritos">

		
					</span>
				</h2>
			</div>
		</div>
	</div>

	<!-- FIN TOTAL ALUMNOS INSCRITOS A CICLO ESCOLAR -->


	

	<div class="col-md-2 text-center">
		<div class="card text-white bg-info mb-3" style="max-width: 20rem;">
			<div class="card-header bg-info" title="Alumnos que aun no se inscriben">
				Alumnos Inactivos
			</div>
			<div class="card-body text-center">
				<h2 class="card-title">
					<span id="alumnosPendientes">
					
					</span>
				</h2>
			</div>
		</div>
	</div>

	
	<div class="col-md-2 text-center">
		<div class="card text-white bg-info mb-3" style="max-width: 20rem;">
			<div class="card-header bg-info" title="Total de alumnos egresados">
				Alumnos Egresados
			</div>
			<div class="card-body text-center">
				<h2 class="card-title">
					<span id="alumnosEgresados">
					</span>
				</h2>
			</div>
		</div>
	</div>

</div>
<!-- FIN INDICADORES -->


<!-- ROW TABLA -->

<div class="row">
	
	<div class="col-md-12 justify-content-center">
	<?php  
		$sqlRamas = "SELECT * FROM rama WHERE id_pla1 = '$plantel' ORDER BY id_ram DESC";
		$resultadoRamas = mysqli_query($db, $sqlRamas);
	?>
	<table id="myTable" class="table table-hover table-striped table-sm" cellspacing="0" width="100%">
		<thead class="bg-info text-white">
			<tr>
				<th class="letraPequena font-weight-normal">#</th>
				<th class="letraPequena font-weight-normal">Nombre</th>
				<th class="letraPequena font-weight-normal">Ciclos</th>
				<th class="letraPequena font-weight-normal">Periodos</th>
				<th class="letraPequena font-weight-normal">Costo</th>
				<th class="letraPequena font-weight-normal">Parciales</th>
				<th class="letraPequena font-weight-normal">Modalidad</th>
				<th class="letraPequena font-weight-normal">Nivel Educativo</th>
				<th class="letraPequena font-weight-normal">Alumnos</th>
				<th class="letraPequena font-weight-normal">Activos</th>
				<th class="letraPequena font-weight-normal">Inactivos</th>
				<th class="letraPequena font-weight-normal">Egresados</th>
				<th class="letraPequena font-weight-normal">Acción</th>
			</tr>
		</thead>


		<?php 
			$i = 1;
			while($filaRamas = mysqli_fetch_assoc($resultadoRamas)){

		?>
			<tr>
				<td class="letraPequena font-weight-normal"><?php echo $i; $i++;?></td>
		
				<td class="letraPequena font-weight-normal"><?php echo $filaRamas['nom_ram']; ?></td>
				<td class="letraPequena font-weight-normal"><?php echo $filaRamas['cic_ram']; ?></td>
				<td class="letraPequena font-weight-normal"><?php echo $filaRamas['per_ram']; ?></td>
				<td class="letraPequena font-weight-normal"><?php echo "$ ".$filaRamas['cos_ram']; ?></td>
				<td class="letraPequena font-weight-normal"><?php echo $filaRamas['eva_ram']; ?></td>
				<td class="letraPequena font-weight-normal"><?php echo $filaRamas['mod_ram']; ?></td>
				<td class="letraPequena font-weight-normal"><?php echo $filaRamas['gra_ram']; ?></td>
				
				<!-- TOTAL ALUMNOS -->
				<td class="letraPequena font-weight-normal">
					<?php  

						$id_ram = $filaRamas['id_ram'];

						$sqlTotal = "
							SELECT *
							FROM alumno
							INNER JOIN alu_ram ON alu_ram.id_alu1 = alumno.id_alu
							WHERE id_ram3 = '$id_ram'
						";


						$resultadoTotal = mysqli_query($db, $sqlTotal);

						$total = mysqli_num_rows($resultadoTotal);

						echo $total;
					?>
				</td>
				<!-- FIN TOTAL ALUMNOS -->


				<!-- ALUMNOS INSCRITOS -->
				<td class="letraPequena font-weight-normal">
					<?php  

						$conteoAlumnosInscritosHorario = 0;
						$conteoAlumnosNoInscritosHorario = 0;
						$sqlAlumnos = "
							SELECT * 
							FROM alu_ram 
							INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
							INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
							WHERE id_ram3 = '$id_ram'
							ORDER BY id_alu_ram DESC
						";

						$resultadoAlumnosInscritos = mysqli_query($db, $sqlAlumnos);

						while($filaAlumnosInscritos = mysqli_fetch_assoc($resultadoAlumnosInscritos)){
							$id_alu_ram = $filaAlumnosInscritos['id_alu_ram'];
							
							$sqlAlumnosInscritosHorario = "
								SELECT * 
								FROM alu_hor 
								WHERE id_alu_ram1 = '$id_alu_ram' AND est_alu_hor = 'Activo'
							";

							$resultadoAlumnosInscritosHorario = mysqli_query($db, $sqlAlumnosInscritosHorario);

							$totalAlumnosInscritosHorario = mysqli_num_rows($resultadoAlumnosInscritosHorario);

							if ($totalAlumnosInscritosHorario > 0) {
								$conteoAlumnosInscritosHorario = $conteoAlumnosInscritosHorario + 1;
							}else{
								$conteoAlumnosNoInscritosHorario = $conteoAlumnosNoInscritosHorario + 1;
							}

						}			

						echo $conteoAlumnosInscritosHorario;
						 

					?>
				</td>
				<!-- FIN ALUMNOS INSCRITOS -->



				<!-- ALUMNOS PENDIENTES -->
				<td class="letraPequena font-weight-normal">
					<?php

						$totalAlumnosEgresados = 0;
						$resultadoAlumnosEgresados = mysqli_query($db, $sqlAlumnos);

						while($filaAlumnosEgresados = mysqli_fetch_assoc($resultadoAlumnosEgresados)){

							$id_alu_ram =  $filaAlumnosEgresados['id_alu_ram'];

							$sqlValidacionAlumnoEgresado = "
								SELECT *
								FROM materia
								INNER JOIN rama ON rama.id_ram = materia.id_ram2
								WHERE id_ram = '$id_ram' 
							";

							//echo $sqlValidacionAlumnoEgresado;

							$resultadoValidacionAlumnoEgresado = mysqli_query($db, $sqlValidacionAlumnoEgresado);

							$resultadoTotalMaterias = mysqli_query($db, $sqlValidacionAlumnoEgresado);

							$totalMaterias = mysqli_num_rows($resultadoTotalMaterias);


							if ($resultadoValidacionAlumnoEgresado) {
								
								$sqlValidacionCalificacionAprobatoria = "
									SELECT *
									FROM calificacion 
									WHERE id_alu_ram2 = '$id_alu_ram' AND fin_cal >= 6
								";

								$resultadoValidacionCalificacionAprobatoria = mysqli_query($db, $sqlValidacionCalificacionAprobatoria);

								$validacionCalificacionAprobatoria = mysqli_num_rows($resultadoValidacionCalificacionAprobatoria);


								if ($totalMaterias == $validacionCalificacionAprobatoria) {
									$totalAlumnosEgresados = $totalAlumnosEgresados + 1;
								}


							}
						 
						}
					?>


					<?php
						echo $conteoAlumnosNoInscritosHorario-$totalAlumnosEgresados;
					?>
				</td>
				<!-- FIN ALUMNOS PENDIENTES -->
				
				<!-- ALUMNOS EGRESADOS -->
				<td class="letraPequena font-weight-normal">
					<?php  
						echo $totalAlumnosEgresados;
					?>
				</td>
				<!-- FIN ALUMNOS EGRESADOS -->

				


				<!-- BOTONES DE ACCION -->
				<td class="d-flex flex-column text-center">				

					<div class="dropdown">
					  <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu5" data-toggle="dropdown"
					    aria-haspopup="true" aria-expanded="false">
					    <i class="fas fa-caret-down"></i>
					  </button>
					  <div class="dropdown-menu" aria-labelledby="dropdownMenu5">
					    <a class="dropdown-item letraPequena font-weight-normal" href="materias.php?id_ram=<?php echo $filaRamas['id_ram']; ?>" title="Materias de <?php echo $filaRamas['nom_ram']; ?>">Materias</a>
			
						<a class="dropdown-item letraPequena font-weight-normal" href="ciclos.php?id_ram=<?php echo $filaRamas['id_ram']; ?>" title="Ciclos de <?php echo $filaRamas['nom_ram']; ?>">Ciclos</a>

						<a class="dropdown-item letraPequena font-weight-normal" href="actividades_programa.php?id_ram=<?php echo $filaRamas['id_ram']; ?>" title="Actividades de <?php echo $filaRamas['nom_ram']; ?>">Actividades</a>

						<a class="dropdown-item letraPequena font-weight-normal" href="recursos_programa.php?id_ram=<?php echo $filaRamas['id_ram']; ?>" title="Recursos de <?php echo $filaRamas['nom_ram']; ?>">Recursos teóricos</a>

						
						<a class="dropdown-item letraPequena font-weight-normal" href="documentacion_rama.php?id_ram=<?php echo $filaRamas['id_ram']; ?>" title="Documentación de <?php echo $filaRamas['nom_ram']; ?>">
							Documentación
							</a>
						<a class="dropdown-item letraPequena font-weight-normal edicion" title="Editar <?php echo $filaRamas['nom_ram']; ?>" edicion="<?php echo $filaRamas['id_ram']; ?>">Editar</a>
						<a class="dropdown-item letraPequena font-weight-normal eliminacion" title="Eliminar <?php echo $filaRamas['nom_ram']; ?>" eliminacion="<?php echo $filaRamas['id_ram']; ?>" rama="<?php echo $filaRamas['nom_ram']; ?> ">Eliminar</a>


					  </div>
					</div>
					
					
				</td>
				<!-- FIN BOTONES DE ACCION -->

			</tr>


		<?php
			} 

		?>
	</table>
		
	</div>
	
</div>
<!--  FIN ROW TABLA-->
<!-- FIN CONTENIDO -->


<!-- CONTENIDO MODAL AGREGAR RAMA -->
<div class="modal fade text-left" id="agregarRamaModal">
  <div class="modal-dialog" role="document">
    
	<form id="agregarRamaFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Nuevo Programa</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">
	        
	        <div class="md-form mb-5">
	          <i class="fas fa-info prefix grey-text"></i>
	          <input type="text" id="nom_ram" name="nom_ram" class="form-control validate">
	          <label  for="nom_ram">Nombre</label>
	        </div>

	        <div class="md-form mb-5">
	          <i class="fas fa-calendar-week prefix grey-text"></i>
	          <input type="number" id="cic_ram" name="cic_ram" class="form-control validate">
	          <label  for="cic_ram">Ciclos</label>
	        </div>

	        <div class="md-form mb-5">
	          <i class="fas fa-align-left prefix grey-text"></i>
	          <input type="text" id="per_ram" name="per_ram" class="form-control validate">
	          <label  for="per_ram">Periodos</label>
	        </div>
			
			

			<div class="md-form mb-5">
	          <i class="fas fa-money-check-alt prefix grey-text"></i>
	          <input type="number" id="cos_ram" name="cos_ram" class="form-control validate">
	          <label  for="cos_ram">Costo</label>
	        </div>
	      	

	      	<div class="md-form mb-5">
	          <i class="fas fa-book-open prefix grey-text"></i>
	          <input type="number" id="eva_ram" name="eva_ram" class="form-control validate">
	          <label  for="eva_ram">Parciales</label>
	        </div>


	        <div class="md-form mb-3">
				<i class="fas fa-network-wired prefix grey-text"></i>
				<label  for="form29">Modalidad</label>
				<br>
				<!-- Group of material radios - option 1 -->
				<select class="mdb-select md-form colorful-select dropdown-primary" id="mod_ram" name="mod_ram">
				  	<option value="Online" selected>Online</option>
				  	<option value="Presencial">Presencial</option>
				</select>
			</div>

			<br>

			<div class="md-form mb-5">
	          <i class="fas fa-sort-amount-up prefix grey-text"></i>
	          <input type="text" id="gra_ram" name="gra_ram" class="form-control validate">
	          <label  for="gra_ram">Nivel Educativo</label>
	        </div>


	        <div class="md-form mb-5">
	        	<i class="fas fa-hand-holding-usd prefix grey-text"></i>
	          	<input type="number" id="pag_ram" name="pag_ram" class="form-control validate" min="0">
	          	<label  for="pag_ram">Cantidad de pagos</label>
	        </div>


	        <hr>

	        <p class="grey-text letraPequena">
	        	*Comisión para sistema de compensación residual(SCR)
	        </p>
	        <div class="md-form mb-5">
	        	<i class="fas fa-dollar-sign prefix grey-text"></i>
	          <input type="text" id="com_ram" name="com_ram" class="form-control validate">
	          <label  for="com_ram">Comisión para área comercial</label>
	        </div>

			

	      </div>
	      <div class="modal-footer d-flex justify-content-center">
	        <button class="btn btn-info" type="submit">Guardar <i class="fas fa-paper-plane-o ml-1"></i></button>
	      </div>

	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL AGREGAR RAMA -->

<!-- CONTENIDO MODAL EDITAR RAMA -->
<div class="modal fade text-left" id="editarRamaModal" >
  <div class="modal-dialog" role="document">
    
	<form id="editarRamaFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold">Editar Programa</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">
	        <div class="md-form mb-5">
	          <i class="fas fa-user prefix grey-text"></i>
	          <input type="text" id="nombre" name="nombre" class="form-control validate">
	          <label  for="form34">Nombre</label>
	        </div>

	        <div class="md-form mb-5">
	          <i class="fas fa-calendar-week prefix grey-text"></i>
	          <input type="number" id="ciclo" name="ciclo" class="form-control validate">
	          <label  for="form29">Ciclos</label>
	        </div>

	        <div class="md-form mb-5">
	          <i class="fas fa-align-left prefix grey-text"></i>
	          <input type="text" id="periodo" name="periodo" class="form-control validate">
	          <label  for="form29">Periodos</label>
	        </div>
			
			

			<div class="md-form mb-5">
	          <i class="fas fa-money-check-alt prefix grey-text"></i>
	          <input type="number" id="costo" name="costo" class="form-control validate">
	          <label  for="form29">Costo</label>
	        </div>
	      	

	      	<div class="md-form mb-5">
	          <i class="fas fa-book-open prefix grey-text"></i>
	          <input type="number" id="evaluacion" name="evaluacion" class="form-control validate">
	          <label  for="form29">Parciales</label>
	        </div>


	        <div class="md-form mb-5">
	          <i class="fas fa-network-wired prefix grey-text"></i>
	          <input type="text" id="modalidad" name="modalidad" class="form-control validate">
	          <label  for="form29">Modalidad</label>
	        </div>

	        <br>

	        <div class="md-form mb-5">
	          <i class="fas fa-sort-amount-up prefix grey-text"></i>
	          <input type="text" id="nivelEducativo" name="nivelEducativo" class="form-control validate">
	          <label  for="form34">Nivel Educativo</label>
	        </div>



	        <div class="md-form mb-5">
	        	<i class="fas fa-hand-holding-usd prefix grey-text"></i>
	          	<input type="number" id="pagosRama" name="pagosRama" class="form-control validate" min="0">
	          	<label  for="pagosRama">Cantidad de pagos</label>
	        </div>

	        <hr>


	        <p class="grey-text letraPequena">
	        	*Comisión para sistema de compensación residual(SCR)
	        </p>
	        <div class="md-form mb-5">
	        	<i class="fas fa-dollar-sign prefix grey-text"></i>
	          <input type="text" id="comision" name="comision" class="form-control validate">
	          <label  for="comision">Comisión para área comercial</label>
	        </div>

	        <div class="md-form mb-5">
	          <input type="hidden" id="identificador" name="identificador" class="form-control validate">
	         
	        </div>


	      </div>
	      <div class="modal-footer d-flex justify-content-center">
	        <button class="btn btn-success" type="submit">Actualizar <i class="fas fa-paper-plane-o ml-1"></i></button>
	      </div>


	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL EDITAR RAMA -->




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

		var table = $('#myTable').DataTable();

		

		$("#alumnosTotales").text(table.column( 8, {filter: 'applied'}).data().sum());
		$("#alumnosInscritos").text(table.column( 9, {filter: 'applied'} ).data().sum());
		$("#alumnosPendientes").text(table.column( 10, {filter: 'applied'}).data().sum());
		$("#alumnosEgresados").text(table.column( 11, {filter: 'applied'} ).data().sum());

		$("#alumnosActivos").text(table.column( 9, {filter: 'applied'}).data().sum());
		$("#alumnosInactivos").text(table.column( 10, {filter: 'applied'} ).data().sum());


		table.on('draw', function(){
			$("#alumnosTotales").text(table.column( 8, {filter: 'applied'}).data().sum());
			$("#alumnosInscritos").text(table.column( 9, {filter: 'applied'} ).data().sum());
			$("#alumnosPendientes").text(table.column( 10, {filter: 'applied'}).data().sum());
			$("#alumnosEgresados").text(table.column( 11, {filter: 'applied'} ).data().sum());
			$("#alumnosActivos").text(table.column( 9, {filter: 'applied'}).data().sum());
			$("#alumnosInactivos").text(table.column( 10, {filter: 'applied'} ).data().sum());
	    	
	    });



	
	});
</script>


<script>
	//CODIGO PARA TOMAR ID DE IMAGEN Y DESPLEGAR EN MODAL INFO DEL RAMA
	$('.imagenes').on('click', function(event) {
			event.preventDefault();

			var imagen = $(this).children().attr("imagen");

			console.log(imagen);
			/* Act on the event */
		});
</script>

<script>

	//FORMULARIO DE CREACION DE RAMA
	//CODIGO PARA AGREGAR RAMA NUEVO ABRIENDO MODAL+SELECT
	$('#agregarRama').on('click', function(event) {
		event.preventDefault();
		
		$('#agregarRamaModal').modal('show');
		
		$('#agregarRamaFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR

		$('#mod_ram').materialSelect();
	});


	$('#agregarRamaFormulario').on('submit', function(event) {
		event.preventDefault();
			
		$.ajax({
		
			url: 'server/agregar_rama.php',
			type: 'POST',
			data: new FormData(agregarRamaFormulario), 
			processData: false,
			contentType: false,
			cache: false,
			success: function(respuesta){
				console.log(respuesta);

				if (respuesta == 'Exito') {
					swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",}).
					then((value) => {
					  window.location.reload();
					});
					
				}
			}
		});
	});

	
</script>



<script>
	//ELIMINACION DE RAMA
	$('.eliminacion').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		var rama = $(this).attr("eliminacion");
		var nombreRama = $(this).attr("rama");


		swal({
	      title: "¡Acceso Restringido!",
		  icon: "warning",
		  text: 'Necesitas permisos de administrador para continuar',
		  content: {
		    element: "input",
		    attributes: {
		      placeholder: "Ingresa una contraseña...",
		      type: "password",
		    },
		  },

		  button: {
		    text: "Validar",
		    closeModal: false,
		  },
		})
		.then(name => {
		  if (name){
		  	//console.log(name);
		  	var password = name;
		  	$.ajax({
					
				url: 'server/validacion_permisos.php',
				type: 'POST',
				data: {password},
				success: function(respuesta){
					console.log(respuesta);

					if (respuesta == 'True') {
						swal("Validado correctamente", "Continuar", "success", {button: "Aceptar",}).
						then((value) => {
						  //
						  console.log("Existe el password");
						  swal({
							  title: "¿Deseas eliminar "+nombreRama+"?",
							  text: "¡Una vez eliminado se perderán todos los datos relacionados a ese registro!",
							  icon: "warning",
							  buttons: 	{
										  cancel: {
										    text: "Cancelar",
										    value: null,
										    visible: true,
										    className: "",
										    closeModal: true,
										  },
										  confirm: {
										    text: "Confirmar",
										    value: true,
										    visible: true,
										    className: "",
										    closeModal: true
										  }
										},
							  dangerMode: true,
							}).then((willDelete) => {
							  if (willDelete) {
							    //ELIMINACION ACEPTADA

							    $.ajax({
									url: 'server/eliminacion_rama.php',
									type: 'POST',
									data: {rama},
									success: function(respuesta){
										
										if (respuesta == "true") {
											console.log("Exito en consulta");
											swal("Eliminado correctamente", "Continuar", "success", {button: "Aceptar",}).
											then((value) => {
											  window.location.reload();
											});
										}else{
											console.log(respuesta);

										}

									}
								});
							    
							  }
							});



						});
						
					}else{
						// LA CONTRASENA NO EXISTE
						swal({
						  title: "¡Datos incorrectos!",
						  text: 'No existe la contraseña...',
						  icon: "error",
						  button: "Aceptar",
						});
					  	swal.stopLoading();
					    swal.close();
					}
				}
			});


		  }else{
		  	// DATOS VACIOS
		  	swal({
			  title: "¡Datos vacíos!",
			  text: 'Necesitas ingresar una contraseña...',
			  icon: "error",
			  button: "Aceptar",
			});
		  	swal.stopLoading();
		    swal.close();
		  }
		 
		  
		});
	});


</script>


<script>
	//EDICION DE RAMA

	//EDICION Y ENVIO  DE DATOS DEL FORMULARIO DE EDICION DE RAMA

	$('.edicion').on('click', function(){
		$('#editarRamaFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
		var edicionRama = $(this).attr("edicion");
		$('#editarRamaFormulario label').addClass('active');
		$('#editarRamaFormulario i').addClass('active');

		//console.log(edicionRama);


		$.ajax({
			url: 'server/obtener_rama.php',
			type: 'POST',
			dataType: 'json',
			data: {edicionRama},
			success: function(datos){

				$('#editarRamaModal').modal('show');
				$('#nombre').attr({value: datos.nom_ram});
				$('#ciclo').attr({value: datos.cic_ram});
				$('#periodo').attr({value: datos.per_ram});
				$('#costo').attr({value: datos.cos_ram});
				$('#evaluacion').attr({value: datos.eva_ram});
				$('#modalidad').attr({value: datos.mod_ram});
				$('#nivelEducativo').attr({value: datos.gra_ram});
				$('#pagosRama').attr({value: datos.pag_ram});
				$('#identificador').attr({value: datos.id_ram});
				$('#comision').attr({value: datos.com_ram});

				console.log( datos.com_ram );

				//AGREGADO O PRESERVACION DE DATOS DEL FORMULARIO DEL RAMA
				$('#editarRamaFormulario').on('submit', function(event) {
					event.preventDefault();

		
					$.ajax({
					
						url: 'server/editar_rama.php',
						type: 'POST',
						data: new FormData(editarRamaFormulario), 
						processData: false,
						contentType: false,
						cache: false,
						success: function(respuesta){
							console.log(respuesta);

							if (respuesta == 'Exito') {
								swal("Editado correctamente", "Continuar", "success", {button: "Aceptar",}).
								then((value) => {
								  window.location.reload();
								});
								
							}
						}
					});

				});				
			}
		});
	});

	//FIN EDICION Y ENVIO  DE DATOS DEL FORMULARIO DEEDICION DE RAMA
</script>