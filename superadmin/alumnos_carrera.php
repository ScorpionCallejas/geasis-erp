<?php  
 
	include('inc/header.php');
	$id_ram = $_GET['id_ram'];

	$sqlRama = "SELECT * FROM rama WHERE id_ram = '$id_ram'";
	$resultadoRama = mysqli_query($db, $sqlRama);
	$filaRama = mysqli_fetch_assoc($resultadoRama);

	$nom_ram = $filaRama['nom_ram'];
 
	// VARIABLE DE GENERACIONES POR VISTA
	$generacionesPagina = 4;

	// //CONTEO GENERACIONES

	$sqlTotalGeneraciones = "
		SELECT *
		FROM generacion
		WHERE id_ram5 = '$id_ram'
		ORDER BY id_gen DESC
	";
		

	$resultadoTotalGeneraciones = mysqli_query($db, $sqlTotalGeneraciones);

	$totalGeneraciones = mysqli_num_rows($resultadoTotalGeneraciones);

	$numeroPaginas = ceil( $totalGeneraciones/$generacionesPagina );

?>


<!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Alumnos">
			<i class="fas fa-bookmark"></i> 
			Generaciones Académicas
		</span>
		<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="ramas.php" title="Vuelve a Programa">Programas</a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" title="Estás aquí">Alumnos</a>
	</div>
	</div>
	<div class="col text-right">
		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Programa: <?php echo $nom_ram; ?>">
			<i class="fas fa-certificate"></i>
			Programa: <?php echo $nom_ram; ?>
		</span><br>
	</div>
</div>
<!-- FIN TITULO -->



<!-- GENERACIONES -->

<!-- BOTON AGREGAR -->
<div class="card">
	<div class="card-body">
		<div class="row">
			

			<!-- BOTON TODOS LOS ALUMNOS -->
			<!-- COL TODOS LOS ALUMNOS -->
			<div class="col-md-3">
				<!-- CARD TODOS LOS ALUMNOS -->

				<div class="card bg-light mb-3 " style="max-width: 20rem;">
				  <div class="card-header bg-light text-center waves-effect seleccionListadoAlumno">
					<i class="fas fa-user-graduate fa-1x"></i> 
					Todos los Alumnos
				  </div>
				  <div class="card-body text-center">
				  	

				  	<?php
				  		$totalAlumnosRama = conteoEstatusAlumnosRama($id_ram);
				  	?>
					<div class="row">
	        
				        <div class="col-md-6">
				          <label class="form-check-label letraPequena" style="line-height: 100%;">
				            Alumnos totales 
				          </label>
				          <h5>
				            <span class="badge badge-info">
				            	<?php echo $totalAlumnosRama['alumnosTotales']; ?>
				            </span>
				          </h5>
				        </div>

				        <div class="col-md-6">
				          <label class="form-check-label letraPequena" style="line-height: 100%;">
				            Alumnos activos 
				          </label>
				          <h5>
				            <span class="badge badge-info">
				            	<?php echo $totalAlumnosRama['alumnosInscritos']; ?>
				            </span>
				          </h5>
				        </div>
				      </div>

				      <div class="row">
				        <div class="col-md-6">
				          <label class="form-check-label letraPequena" style="line-height: 100%;">
				            Alumnos egresados 
				          </label>
				          <h5>
				            <span class="badge badge-info">
				            	<?php echo $totalAlumnosRama['alumnosEgresados']; ?>
				            </span>
				          </h5>
				        </div>

				        <div class="col-md-6">
				          <label class="form-check-label letraPequena" style="line-height: 100%;">
				            Alumnos inactivos
				          </label>
				          <h5>
				            <span class="badge badge-info">
				            	<?php echo $totalAlumnosRama['alumnosPendientes']; ?>
				            </span>
				          </h5>
				        </div>

				      </div>

				      <div class="row">
				        <div class="col-md-6">
				          <label class="form-check-label letraPequena" style="line-height: 100%;">
				            Alumnos sin adeudos
				          </label>
				          <h5>
				            <span class="badge badge-info">
				            	<?php echo $totalAlumnosRama['alumnosActivos']; ?>
				            </span>
				          </h5>
				        </div>

				        <div class="col-md-6">
				          <label class="form-check-label letraPequena" style="line-height: 100%;">
				            Alumnos con adeudos
				          </label>
				          <h5>
				            <span class="badge badge-info">
				            	<?php echo $totalAlumnosRama['alumnosInactivos']; ?>
				            </span>
				          </h5>
				        </div>

				      </div>

				  	<!-- <h4>
				  		
				  		
				  		
					  	
				  	</h4> -->
				  	
				    
				  </div>
				</div>


				<!-- FIN CARD TODOS LOS ALUMNOS -->


			</div>
			<!-- FIN COL TODOS LOS ALUMNOS -->
			<!-- FIN BOTON TODOS LOS ALUMNOS -->



			<!-- PAGINACION -->
			<div class="col-md-4 text-center">
				<div class="card bg-light mb-3 " style="max-width: 20rem;">
					<div class="card-header bg-light text-center waves-effect">
						Paginación
					</div>
					<div class="card-body text-center">
						<nav id="contenedor_paginacion">
							<ul class="pagination pg-blue pagination-lg">

								<?php  
									for ( $i = 1 ; $i < $numeroPaginas + 1 ; $i++) { 
								?>

										<?php  

											if ($i == 1) {
										?>

											<li class="page-item ">
												<a class="page-link botonesPaginacion bg-info white-text" pagina="<?php echo $i; ?>">
													<?php echo $i; ?>
												</a>
											</li>
										<?php
											}else{
										?>
											<li class="page-item">
												<a class="page-link botonesPaginacion" pagina="<?php echo $i; ?>">
													<?php echo $i; ?>	
												</a>
											</li>

										<?php
											}
										?>
									

								<?php
									}
								?>
							</ul>
						</nav>
					</div>
				</div>
			</div>
			<!-- FIN PAGINACION -->


			<!-- BOTON FLOTANTE AGREGAR GENERACION-->
			<div class="col-md-5 text-right">


						<a class="btn-floating btn-lg btn-info"  title="Agregar generación">
							<i class="fas fa-plus" id="agregarGeneracion">
							</i>
						</a>

			</div>
			<!-- FIN BOTON FLOTANTE AGREGAR GENERACION-->
			

			
		</div>
	</div>
</div>
<!-- FIN BOTON AGREGAR -->

<hr class="my-5">

<!-- CARDS GENERACIONES -->
<div class="card">
	<div class="card-body">
		<div class="row" id="contenedor_generaciones">
		
		
		</div>
	</div>
</div>

<!-- FIN CARDS GENERACIONES -->
<!-- FIN GENERACIONES -->

<hr class="my-5">

<!-- ALUMNOS -->
<div class="card">
	<div class="card-body" id="contenedor_alumnos">
		
	</div>
</div>
<!-- FIN ALUMNOS -->



<?php  
	include('inc/footer.php');
?>


<!-- GENERACIONES JS -->


<!-- PAGINACION -->
<script>
	

	$(".botonesPaginacion").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var paginaFinal = <?php echo $numeroPaginas; ?>;
		//console.log($(this).attr("pagina"));

		$('.botonesPaginacion').removeClass('bg-info white-text');
		$(this).addClass('bg-info white-text');

		var pagina = $(this).attr("pagina");
		$("#contenedor_publicaciones").html('<div style="background: white; z-index: 99999999; width: 100%; height: 100%; position:relative; " id="overlay"> <div class="spinner" style="position: absolute; top: 50%; right: 50%; font-size: 20px;">Cargando...</div></div>');
		obtener_paginacion(pagina);


		$('.page-item').hide();

		if (parseInt(pagina) == 1) {
			console.log('pagina 1');
			$('.page-item').eq(parseInt(pagina)-1).show();
			$('.page-item').eq(parseInt(pagina)).show();
			$('.page-item').eq(parseInt(pagina)+1).show();
			$('.page-item').eq(parseInt(pagina)+2).show();
			$('.page-item').eq(parseInt(pagina)+3).show();
		}else if (parseInt(pagina) == 2) {
			
			$('.page-item').eq(parseInt(pagina)-2).show();
			$('.page-item').eq(parseInt(pagina)-1).show();
			$('.page-item').eq(parseInt(pagina)).show();
			$('.page-item').eq(parseInt(pagina)+1).show();
			$('.page-item').eq(parseInt(pagina)+2).show();
			
			
		}else if( parseInt(pagina) == parseInt(paginaFinal)) {
			console.log("paginafinal");
			$('.page-item').eq(parseInt(pagina)-5).show();
			$('.page-item').eq(parseInt(pagina)-4).show();
			$('.page-item').eq(parseInt(pagina)-3).show();
			$('.page-item').eq(parseInt(pagina)-2).show();
			$('.page-item').eq(parseInt(pagina)-1).show();
			
		}else if( parseInt(pagina) == parseInt(paginaFinal-1)){

			$('.page-item').eq(parseInt(pagina)-4).show();
			$('.page-item').eq(parseInt(pagina)-3).show();
			$('.page-item').eq(parseInt(pagina)-2).show();
			$('.page-item').eq(parseInt(pagina)-1).show();
			$('.page-item').eq(parseInt(pagina)).show();
			

		}else{
			$('.page-item').eq(parseInt(pagina)-3).show();
			$('.page-item').eq(parseInt(pagina)-2).show();
			$('.page-item').eq(parseInt(pagina)-1).show();
			$('.page-item').eq(parseInt(pagina)).show();
			$('.page-item').eq(parseInt(pagina)+1).show();
		}

		
	});

	for(var i = 5; i < $('.page-item').length; i++){
		$('.page-item').eq(i).hide();
	}

	var paginaInicio = 1;
	obtener_paginacion(paginaInicio);


	function obtener_paginacion(pagina){
		
		$.ajax({
			url: 'server/obtener_generaciones_paginadas.php?id_ram=<?php echo $id_ram; ?>',
			type: 'POST',
			data: {pagina},
			success: function(respuesta){
				$("#contenedor_generaciones").html(respuesta);

				

				// SELECCION DE ALUMNOS POR GENERACION O TODOS
				$(".seleccionListadoAlumno").on('click', function(event) {
					event.preventDefault();
					/* Act on the event */

					$("#contenedor_alumnos").html('<h3 class="text-center grey-text"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>');

					$(".seleccionListadoAlumno").removeClass('light-green accent-4 white-text').addClass('bg-light');
					$(this).removeClass('bg-light').addClass('light-green accent-4 white-text');

					var id_gen = $(this).attr("generacion");
					//alert(id_gen);
					
					if ( id_gen != undefined ) {


						$.ajax({
							url: 'server/obtener_alumnos_generacion.php',
							type: 'POST',
							data: {id_gen},
							success: function(respuesta){
								$("#contenedor_alumnos").html(respuesta);

							}
						});
					}else {
						var id_ram = <?php echo $id_ram; ?>;
						$.ajax({
							url: 'server/obtener_alumnos_generacion.php',
							type: 'POST',
							data: {id_ram},
							success: function(respuesta){
								$("#contenedor_alumnos").html(respuesta);

							}
						});
					}
					

				}); 

				// FIN SELECCION

			}
		});
	}
	


	
	
</script>
<!-- FIN PAGINACION -->



<!-- FIN GENERACIONES JS -->