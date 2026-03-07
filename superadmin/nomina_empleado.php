<?php  

	include('inc/header.php');

	// if (!$_GET) {
	// 	header
	// }
	$id_emp = $_GET['id_emp'];

	$sqlEmpleado = "

		SELECT *
		FROM empleado
		WHERE id_emp = '$id_emp'
	";

	$resultadoEmpleado = mysqli_query($db, $sqlEmpleado);

	$filaEmpleado = mysqli_fetch_assoc($resultadoEmpleado);

	$nombreEmpleado = $filaEmpleado['nom_emp']." ".$filaEmpleado['app_emp']." ".$filaEmpleado['apm_emp'];
	$tipoEmpleado = $filaEmpleado['tip_emp'];

	$correoEmpleado = $filaEmpleado['cor_emp'];
	$telefonoEmpleado = $filaEmpleado['tel_emp'];

	$ingresoEmpleado = $filaEmpleado['ing_emp'];

	$nacimientoEmpleado = $filaEmpleado['nac_emp'];
	$fotoEmpleado = $filaEmpleado['fot_emp'];

?>
<!-- ESTILOS -->
<style>
	#detalles_empleado{
	  position: -webkit-sticky;
	  position: sticky;
	  top: 50px;

	}
</style>
<!-- FIN ESTILOS -->
<!-------------------------------------------titulo del empleado ----------------------------------------------->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Listado de los Empleados en el Plantel">
			<i class="fas fa-bookmark"></i> 
			Nómina del Empleado
		</span>
	</div>
</div>
<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
	<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
	<i class="fas fa-angle-double-right"></i>
	<a href="empleados.php" title="Vuelve a los Empleados"><span class="text-white">Empleados</span></a>
	<i class="fas fa-angle-double-right"></i>
	<a style="color: black;" href="" title="Estás aquí">Nómina del Empleado</a>
</div>

	<!-- ROW -->
	<div class="row">

		<!-- COL-4 -->
		<div class="col-md-4" >

			<!-- Rotating card -->
			<div class="card-wrapper" id="detalles_empleado">
			  <div id="card-1" class="card card-rotating text-center">

			    <!-- Front Side -->
			    <div class="face front">
					<!-- Card -->
					<div class="card testimonial-card">

					  <!-- Background color -->
					  <div class="card-up  grey"></div>

					  <!-- Avatar -->
					  <div class="avatar mx-auto white file-upload-wrapper view modalFoto" title="Foto de <?php echo $nombreEmpleado; ?>">
					  	
					    	<img src="../uploads/<?php echo $fotoEmpleado; ?>" class="rounded-circle modalFoto" style="border-radius: 50%;" alt="woman avatar" id="avatar">
					    	
					  </div>
						 

						<!-- Content -->
						<div class="card-body">
							<!-- Name -->
							<h4 class="card-title"><?php echo $nombreEmpleado; ?></h4>
							<h5 class="card-title">Tipo: <?php echo $tipoEmpleado; ?></h5>
							
							<hr>

							<div class="row">
								<div class="col">

									<div class="card grey lighten-1 mb-3 waves-effect hoverable white-text selectores" style="max-width: 20rem;" id="conceptoEmpleado">
										<div class="card-header  grey darken-1" title="Percepciones y Deducciones">
											Conceptos
										</div>
									 
									</div>


									<div class="card   grey lighten-1 mb-3 waves-effect hoverable white-text selectores" style="max-width: 20rem;" id="nominaEmpleado" title="Genera los pagos de nómina para <?php echo $nombreEmpleado; ?>">
										<div class="card-header  grey darken-1" title="Todas las actividades pertenecientes a tus materias de horario">
											Nómina
										</div>
									 
									</div>
									
								</div>
							</div>

							<hr>

							<a class="rotate-btn" data-card="card-1" title="Haz click para voltear"><i class="fas fa-redo-alt"></i> 
								Ir Detrás
							</a>
	
							<hr>
							<br>

						</div>

					</div>
					<!-- Card -->
			      
			    </div>
			    <!-- Front Side -->

			    <!-- Back Side -->
			    <div class="face back">
			    	<!-- Card -->
					<div class="card testimonial-card" >

					  <!-- Background color -->
					  <div class="grey white-text p-4">
					  	<h4 class="card-title text-center">Datos Personales</h4>
					  </div>

					  <!-- Avatar -->
				

					  <!-- Content -->
					  <div class="card-body">
					    <!-- DATOS -->

					    
			                <ul class="list-group list-group-flush text-justify">
			                  <ul class="list-group">
			                    <li class="list-group-item">
			                      <a class="text-white btn-floating bg-info btn-sm  bg-info"><i class="fas fa-envelope"></i></a> Correo: <?php echo $correoEmpleado; ?>
			                    </li>


			                    <li class="list-group-item">
			                      <a class="text-white btn-floating bg-info btn-sm  bg-info">
			                      	<i class="fas fa-phone"></i>
			                      </a> Teléfono: <?php echo $telefonoEmpleado; ?>
			                    </li>


			                    <li class="list-group-item">
			                      <a class="text-white btn-floating bg-info btn-sm  bg-info"><i class="far fa-calendar-check"></i></a> Nacimiento: <?php echo fechaFormateadaCompacta($nacimientoEmpleado); ?>
			                    </li>


			                    <li class="list-group-item">
			                      <a class="text-white btn-floating bg-info btn-sm  bg-info"><i class="fas fa-map-marker"></i></a> 
			                      Ingreso: <?php echo fechaFormateadaCompacta($ingresoEmpleado); ?>
			                    </li>
			                   
			                  </ul>
			                </ul>

			            <br>
						
		            	<a class="rotate-btn" data-card="card-1" title="Haz click para voltear"><i class="fas fa-undo"></i> Ir al frente</a>

		            	<br>

		            	<hr>
					    
					    <br>
					  </div>

					</div>
					<!-- Card -->
			      
			    </div>
			    <!-- Back Side -->

			  </div>
			</div>
			<!-- Rotating card -->		
	    </div>
	    <!-- FIN COL-4 -->


	    <!-- COL-8 -->
	    <div class="col-md-8">

	    	<!-- JUMBOTRON -->
	    	<div class="jumbotron black-text mx-2 mb-5 grey lighten-4" id="fila1Col2">


	    	</div>
	    	<!-- FIN JUMBOTRON -->
	    </div>
	    <!-- FIN COL-8 -->
	</div>
	<!-- FIN ROW -->

	<br>

	<!-- ROW 2 -->
	<div class="row">
		<!-- COL 12 -->
		<div class="col-md-12">

			<!-- JUMBOTRON -->
	    	<div class="jumbotron black-text mx-2 mb-5 grey lighten-4" id="fila2Col1">


	    	</div>
	    	<!-- FIN JUMBOTRON -->
			
		</div>

		<!-- FIN COL 12 -->
	</div>
	<!-- FIN ROW 2 -->

<?php  

	include('inc/footer.php');

?>


<script>
	//CONCEPTOS
	$("#conceptoEmpleado").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		$('.selectores').children().removeClass('grey lighten-1');
		$('.selectores').children().removeClass('light-green accent-4');
		$('.selectores').children().addClass('grey lighten-1');
		$(this).children().removeClass('grey lighten-1');
		$(this).children().addClass('light-green accent-4');

		var id_emp = <?php echo $id_emp; ?>;

		$.ajax({
			url: 'server/obtener_conceptos_empleado.php',
			type: 'POST',
			data: {id_emp},
 			success: function(respuesta){
 				//console.log(respuesta);
 				$("#fila1Col2").html(respuesta);

			}
		});
	});


	// NOMINAS
	$("#nominaEmpleado").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		$('.selectores').children().removeClass('grey lighten-1');
		$('.selectores').children().removeClass('light-green accent-4');
		$('.selectores').children().addClass('grey lighten-1');
		$(this).children().removeClass('grey lighten-1');
		$(this).children().addClass('light-green accent-4');
	
		var id_emp = <?php echo $id_emp; ?>;

		$.ajax({
			url: 'server/obtener_nominas_empleado.php',
			type: 'POST',
			data: {id_emp},
 			success: function(respuesta){
 				//console.log(respuesta);
 				$("#fila1Col2").html(respuesta);
 				$("#fila2Col1").html("");

			}
		});
		
	});
</script>

