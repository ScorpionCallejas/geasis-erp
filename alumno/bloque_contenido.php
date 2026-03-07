<?php  

	include('inc/header.php');

	obtenerEstatusPagoAlumnoGlobal( $id );
	
	$id_blo = $_GET['id_blo'];

	$id_alu_ram = $_GET['id_alu_ram'];

	//VALIDACION DE ALUMNO DE LA CARRERA
	//PREGUNTANDO SI EL ID_ALU_RAM ESTA ASOCIADO AL ID DEL ALUMNO AL INICIO DE SESION EN CABECERAS Y ADICIONALMENTE EL BLOQUE VINCULADO A LA MATERIA DEL BLOQUE

	//***PENDIENTE DE VERIFICACION
	$sqlValidacion = "
		SELECT *
		FROM alumno
		INNER JOIN alu_ram ON alu_ram.id_alu1 = alumno.id_alu
		INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
		INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
		INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
		INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
		INNER JOIN bloque ON bloque.id_mat6 = materia.id_mat

		WHERE id_alu_ram = '$id_alu_ram' AND id_alu = '$id' AND id_blo = '$id_blo'
	";

	$resultadoValidacion = mysqli_query($db, $sqlValidacion);

	$totalValidacion = mysqli_num_rows($resultadoValidacion);

	
	if ($totalValidacion == 0) {
		header('location: not_found_404_page.php');
	}
	$filaValidacion = mysqli_fetch_assoc($resultadoValidacion);

	$nom_blo = $filaValidacion['nom_blo'];
	$des_blo = $filaValidacion['des_blo'];
	$con_blo = $filaValidacion['con_blo'];	
	$id_mat6 = $filaValidacion['id_mat6'];
	$nom_mat = $filaValidacion['nom_mat'];
	$nom_ram = $filaValidacion['nom_ram'];
	$id_mat = $filaValidacion['id_mat'];
	$id_ram = $filaValidacion['id_ram'];

	$id_sub_hor = $filaValidacion['id_sub_hor'];

?>





<!-- TITUTLO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Materias">
			<i class="fas fa-bookmark"></i> 
			Contenido del Bloque
		</span><br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
				<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
				<i class="fas fa-angle-double-right"></i>
				<a class="text-white" href="ramas.php" title="Vuelve a Ramas">Ramas</a>
				<i class="fas fa-angle-double-right"></i>
				<a class="text-white" href="materias_horario.php?id_alu_ram=<?php echo $id_alu_ram; ?>" title="Vuelve a Ramas">Materias</a>
				<i class="fas fa-angle-double-right"></i>
				<a style="color: black;" href="" title="Estás aquí">Materias</a>
			</div>
	</div>
		<div class="col text-right">
			<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Rama de Estudio <?php echo $nom_ram; ?>">
				<i class="fas fa-certificate"></i>
				Carrera: <?php echo $nom_ram; ?>
			</span><br><br>
			<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Nombre de la Materia <?php echo $nom_mat; ?>">
				<i class="fas fa-angle-right"></i>
				Materia: <?php echo $nom_mat; ?>
			</span><br><br>
			<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Nombre del Bloque <?php echo $nom_mat; ?>">
				<i class="fas fa-angle-double-right"></i>
				Bloque: <?php echo $nom_blo; ?>
			</span><br>
		</div>
	</div>
<!-- CONTENIDO -->

<div class="row">

	<div class="col-md-4">
		<!-- RECURSOS TEORICOS JUMBOTRON -->
		<div class="jumbotron mdb-color  grey lighten-1  black-text mx-2 mb-5" data-step="1" data-intro="Visualiza y descarga tus clases aquí" data-position='right'>
			<h4 class="mb-2 h4 white-text" title="Recursos Teóricos" >Recursos Teóricos</h4>
			<div class="accordion md-accordion accordion-1" id="accordionEx23" role="tablist">
	
				<!-- VIDEOS -->
				<div class="card" data-step="2" data-intro="Tienes videos para reforzar tu aprendizaje">
					<?php 

						$sqlVideo = "
							SELECT *
							FROM video
							WHERE id_blo1 = '$id_blo'
						";


						$resultadoVideo = mysqli_query($db, $sqlVideo);

						$resultadoTotalVideos = mysqli_query($db, $sqlVideo);

						$totalVideos = mysqli_num_rows($resultadoTotalVideos);


					?>
					<a class="white-text font-weight-bold" data-toggle="collapse" href="#collapse96" aria-expanded="false" aria-controls="collapse96">
						<div class="card-header blue lighten-3 z-depth-1" role="tab" id="heading96">
							<h5 class="text-uppercase mb-0 py-1">
								
									<i class="fas fa-video fa-2x" title="Videos"></i>

								
							</h5>

							<div class="container text-right">
								<?php  
									echo $totalVideos;
								?> videos
							</div>
						</div>

					</a>
					<div id="collapse96" class="collapse " role="tabpanel" aria-labelledby="heading96"
					  data-parent="#accordionEx23">
						<div class="card-body ">
							<div class="row">
							    <table class="table table-hover table-responsive table-sm animated fadeInDown" cellspacing="0" width="100%">



									<?php 
										$i = 1;
										while($filaVideo = mysqli_fetch_assoc($resultadoVideo)){
									?>
										<tr>
											<td>
												<?php echo $i; $i++;?>	
											</td>
									
									
											<td>
												<a href="" class="btn btn-link  recursoVideo" vid_vid="<?php echo $filaVideo['vid_vid']; ?>" des_vid="<?php echo $filaVideo['des_vid']; ?>" nom_vid="<?php echo $filaVideo['nom_vid']; ?>" url_vid="<?php echo $filaVideo['url_vid']; ?>" title="Video: <?php echo $filaVideo['des_vid']; ?>">
													<?php echo $filaVideo['nom_vid']; ?>
												</a>
												
											</td>


										</tr>


									<?php
										} 

									?>
								</table>
							  
							</div>
						</div>
					</div>
				</div>
				<!-- FIN VIDEOS -->

				<!-- WIKIS -->
				<div class="card" data-step="3" data-intro="Cuentas con documentos llamados 'Wikis' que puedes ver online sin necesidad de descargar">

					<?php  
				    	$sqlWiki = "SELECT * FROM wiki WHERE id_blo2 = '$id_blo'";
				    	$resultadoWiki = mysqli_query($db, $sqlWiki);

				    	$resultadoTotalWiki = mysqli_query($db, $sqlWiki);

				    	$totalWikis = mysqli_num_rows($resultadoTotalWiki);

					?>


					<a class="collapsed font-weight-bold white-text" data-toggle="collapse" href="#collapse97" aria-expanded="false" aria-controls="collapse97">

						<div class="card-header blue lighten-3 z-depth-1" role="tab" id="heading97">
							<h5 class="text-uppercase mb-0 py-1">
								
									<i class="fab fa-wikipedia-w fa-2x" title="Wikis"></i>
								
							</h5>

							<div class="container text-right">
								<?php  
									echo $totalWikis;
								?> wikis
							</div>
						</div>
					</a>
					<div id="collapse97" class="collapse" role="tabpanel" aria-labelledby="heading97"
					  data-parent="#accordionEx23">
					    <div class="card-body">
						    <div class="row">

						    	<table class="table table-hover table-responsive table-sm animated fadeInDown" cellspacing="0" width="100%">
									<?php

								    	$i = 1;
								    	while($filaWiki = mysqli_fetch_assoc($resultadoWiki)){
								    ?>

										<tr>
											<td>
												<?php echo $i; $i++;?>	
											</td>
									
									
											<td>
												<a href="" class="btn btn-link  recursoWiki" id_wik="<?php echo $filaWiki['id_wik']; ?>">
													<?php echo $filaWiki['nom_wik']; ?>
												</a>
												
											</td>


										</tr>


									<?php
										} 
									?>
								</table>
						      
						    </div>
					    </div>
					</div>
				</div>

				<!-- FIN WIKIS -->

				<!-- ARCHIVOS -->
				<div class="card" data-step="4" data-intro="Recuerda que debes bajar archivos para aprender o para resolver actividades y subirlas como tareas">

					<?php 

						$sqlArchivo = "
							SELECT *
							FROM archivo
							WHERE id_blo3 = '$id_blo'
						";


						$resultadoArchivo = mysqli_query($db, $sqlArchivo);

						$resultadoTotalArchivos = mysqli_query($db, $sqlArchivo);

						$totalArchivos = mysqli_num_rows($resultadoTotalArchivos);

					?>
					<a class="collapsed font-weight-bold white-text" data-toggle="collapse" href="#collapse98" aria-expanded="false" aria-controls="collapse98">
						<div class="card-header blue lighten-3 z-depth-1" role="tab" id="heading98">
							<h5 class="text-uppercase mb-0 py-1">
								
									<i class="fas fa-file-alt fa-2x" title="Archivos"></i>
								
							</h5>

							<div class="container text-right">
								<?php  
									echo $totalArchivos;
								?> archivos
							</div>
						</div>
					</a>
					<div id="collapse98" class="collapse" role="tabpanel" aria-labelledby="heading98"
					  data-parent="#accordionEx23">
					    <div class="card-body">
						    <div class="row">
						    	<table class="table table-hover table-responsive table-sm animated fadeInDown" cellspacing="0" width="100%">
									


									<?php 
										$i = 1;
										while($filaArchivo = mysqli_fetch_assoc($resultadoArchivo)){
									?>
										<tr>
											<td>
												<?php echo $i; $i++;?>	
											</td>
									
									
											<td>
												<a href="" class="btn btn-link  recursoArchivo" arc_arc="<?php echo $filaArchivo['arc_arc']; ?>" des_arc="<?php echo $filaArchivo['des_arc']; ?>" nom_arc="<?php echo $filaArchivo['nom_arc']; ?>">
													<?php echo $filaArchivo['nom_arc']; ?>
												</a>
												
											</td>


										</tr>


									<?php
										} 

									?>
								</table>  
						    
						    </div>
					    </div>
					</div>
				</div>

				<!-- FIN ARCHIVOS -->
			</div>
		</div>
		<!-- FIN RECURSOS TEORICOS JUMBOTRON -->



		<!-- ACTIVIDADES JUMBOTRON -->
		<div class="jumbotron mdb-color  grey lighten-1  black-text mx-2 mb-5" data-step="5" data-intro="Una vez que aprendiste con los recursos teóricos, ¡es hora de la práctica!, las actividades hay que hacerlas de acuerdo a las fechas que tienen. ¡OJO!" data-position='right'>
			<h4 class="mb-2 h4 white-text" title="Actividades">Recursos Prácticos</h4>
			<div class="accordion md-accordion accordion-1" id="accordionEx23" role="tablist">
	
				<!-- FOROS -->
				<div class="card" data-step="6" data-intro="Aquí encontrarás tus foros de participación y debate" data-position='right'>

					<?php 

						$sqlForo = "
							SELECT *
							FROM foro
							INNER JOIN foro_copia ON foro_copia.id_for1 = foro.id_for
							INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
							WHERE id_sub_hor2 = '$id_sub_hor' AND id_blo = '$id_blo'
						";


						$resultadoForo = mysqli_query($db, $sqlForo);

						$resultadoTotalForos = mysqli_query($db, $sqlForo);

						$totalForos = mysqli_num_rows($resultadoTotalForos);

					?>

					<a class="white-text font-weight-bold" data-toggle="collapse" href="#collapse99" aria-expanded="false" aria-controls="collapse99">

						<div class="card-header blue lighten-3 z-depth-1" role="tab" id="heading99">
							<h5 class="text-uppercase mb-0 py-1">
								
									<i class="fas fa-comment-dots fa-2x" title="Foros"></i>
								
							</h5>


							<div class="container text-right">
								<?php  
									echo $totalForos;
								?> foros
							</div>
						</div>
					</a>

					<div id="collapse99" class="collapse" role="tabpanel" aria-labelledby="heading99"
					  data-parent="#accordionEx23">
						<div class="card-body">
							<div class="row">
							    <table class="table table-hover table-responsive table-sm animated fadeInDown " cellspacing="0" width="100%">
									

									<?php 
										$i = 1;
										while($filaForo = mysqli_fetch_assoc($resultadoForo)){
									?>
										<tr>
											<td>
												<?php echo $i; $i++;?>	
											</td>
									
									
											<td>
												<a href="foro.php?id_for_cop=<?php echo $filaForo['id_for_cop']."&id_alu_ram=".$id_alu_ram; ?>" class="btn btn-link" title="Foro: <?php echo $filaForo['nom_for']; ?>">
													<?php echo $filaForo['nom_for']; ?>
												</a>
												
											</td>


											<td>

												<a href="foro.php?id_for_cop=<?php echo $filaForo['id_for_cop']."&id_alu_ram=".$id_alu_ram; ?>" class="btn btn-link">
													<?php  

														$inicio = $filaForo['ini_for_cop'];
														$fin = $filaForo['fin_for_cop'];



														echo "Del ".fechaFormateadaCompacta($inicio)." al ".fechaFormateadaCompacta($fin);
													?>
												</a>
												
											</td>



										</tr>


									<?php
										} 

									?>
								</table>
							  
							</div>
						</div>
					</div>
				</div>
				<!-- FIN FOROS -->

				<!-- ENTREGABLE -->
				<div class="card" data-step="7" data-intro="Los entregables son tareas que debes hacer y subir, incluso con una foto directo de tu teléfono" data-position='right'>

					<?php 

						$sqlEntregable = "
							SELECT *
							FROM entregable
							INNER JOIN entregable_copia ON entregable_copia.id_ent1 = entregable.id_ent
							INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5
							WHERE id_sub_hor3 = '$id_sub_hor'  AND id_blo = '$id_blo'
				
						";
						//echo $sqlEntregable;

						$resultadoEntregable = mysqli_query($db, $sqlEntregable);

						$resultadoTotalEntregables = mysqli_query($db, $sqlEntregable);

						$totalEntregables = mysqli_num_rows($resultadoTotalEntregables);

					?>
					<a class="collapsed font-weight-bold white-text" data-toggle="collapse" href="#collapse100" aria-expanded="false" aria-controls="collapse100">

						<div class="card-header blue lighten-3 z-depth-1" role="tab" id="heading100">
							<h5 class="text-uppercase mb-0 py-1">
								
									<i class="fas fa-file fa-2x" title="Entregables"></i>
								
							</h5>


							<div class="container text-right">
								<?php  
									echo $totalEntregables;
								?> entregables
							</div>
						</div>
					</a>


					<div id="collapse100" class="collapse" role="tabpanel" aria-labelledby="heading100"
					  data-parent="#accordionEx23">
					    <div class="card-body">
						    <div class="row">

						    	<table class="table table-hover table-responsive table-sm animated fadeInDown" cellspacing="0" width="100%">


									<?php 
										$i = 1;
										while($filaEntregable = mysqli_fetch_assoc($resultadoEntregable)){
									?>
										<tr>
											<td>
												<?php echo $i; $i++;?>	
											</td>
									
									
											<td>
												<a href="entregable.php?id_ent_cop=<?php echo $filaEntregable['id_ent_cop']."&id_alu_ram=".$id_alu_ram; ?>" class="btn btn-link" title="Entregable: <?php echo $filaEntregable['nom_ent']; ?>">
													<?php echo $filaEntregable['nom_ent']; ?>
												</a>
												
											</td>


											<td>

												<a href="entregable.php?id_ent_cop=<?php echo $filaEntregable['id_ent_cop']."&id_alu_ram=".$id_alu_ram; ?>" class="btn btn-link">
													<?php  

														$inicio = $filaEntregable['ini_ent_cop'];
														$fin = $filaEntregable['fin_ent_cop'];



														echo "Del ".fechaFormateadaCompacta($inicio)." al ".fechaFormateadaCompacta($fin);
													?>
												</a>
												
											</td>


										</tr>


									<?php
										} 

									?>
								</table>
						      
						    </div>
					    </div>
					</div>
				</div>

				<!-- FIN ENTREGABLE -->

				<!-- EXAMEN -->
				<div class="card" data-step="8" data-position='right' data-intro="Y finalmente los examenes de opción múltiple y con temporizador, ¡mucho éxito!" data-position='right'>

					<?php 

						$sqlExamen = "
							SELECT *
							FROM examen
							INNER JOIN examen_copia ON examen_copia.id_exa1 = examen.id_exa
							INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
							WHERE id_sub_hor4 = '$id_sub_hor'  AND id_blo = '$id_blo'
						";


						$resultadoExamen = mysqli_query($db, $sqlExamen);


						$resultadoTotalExamenes = mysqli_query($db, $sqlExamen);

						$totalExamenes = mysqli_num_rows($resultadoTotalExamenes);

					?>
					<a class="collapsed font-weight-bold white-text" data-toggle="collapse" href="#collapse101" aria-expanded="false" aria-controls="collapse101">

						<div class="card-header blue lighten-3 z-depth-1" role="tab" id="heading101">
							<h5 class="text-uppercase mb-0 py-1">
								
									<i class="fas fa-diagnoses fa-2x" title="Exámenes"></i>
								
							</h5>



							<div class="container text-right">
								<?php  
									echo $totalExamenes;
								?> examenes
							</div>
						</div>
					</a>

					
					<div id="collapse101" class="collapse" role="tabpanel" aria-labelledby="heading101"
					  data-parent="#accordionEx23">
					    <div class="card-body">
						    <div class="row">
						    	<table class="table table-hover table-responsive table-sm animated fadeInDown" cellspacing="0" width="100%">
									


									<?php 
										$i = 1;
										while($filaExamen = mysqli_fetch_assoc($resultadoExamen)){
									?>
										<tr>
											<td>
												<?php echo $i; $i++;?>	
											</td>
									
									
											<td>
												<a href="examen.php?id_exa_cop=<?php echo $filaExamen['id_exa_cop']."&id_alu_ram=".$id_alu_ram; ?>" class="btn btn-link" title="Examen: <?php echo $filaExamen['nom_exa']; ?>">
													<?php echo $filaExamen['nom_exa']; ?>
												</a>
												
											</td>


											<td>
												<a href="examen.php?id_exa_cop=<?php echo $filaExamen['id_exa_cop']."&id_alu_ram=".$id_alu_ram; ?>" class="btn btn-link">
													<?php

														$inicio = $filaExamen['ini_exa_cop'];
														$fin = $filaExamen['fin_exa_cop'];


														echo "Del ".fechaFormateadaCompacta($inicio)." al ".fechaFormateadaCompacta($fin);
													?>
												</a>
											</td>


										</tr>


									<?php
										} 

									?>
								</table>  
						    
						    </div>
					    </div>
					</div>
				</div>

				<!-- FIN EXAMEN -->
			</div>
		</div>
		<!-- FIN ACTIVIDADES JUMBOTRON -->

	
	</div>



	<div class="col-md-8">
		<!-- Jumbotron -->
		<div class="jumbotron mdb-color  grey lighten-4  black-text mx-2 mb-5">
		<?php  

			echo $con_blo;
		?>
		</div>
		<!-- FIN Jumbotron -->
	</div>

	


</div>



<!-- VISTAS DE LOS RECURSOS -->

<!-- VISTA DE WIKI -->
<!-- CONTENIDO MODAL AGREGAR WIKI -->
<div class="modal fade text-left " id="modalWiki">
  <div class="modal-dialog modal-lg" role="document">
    
	<form >
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        
	        <h4 class="modal-title w-100 font-weight-bold">
	        	<i class="fab fa-wikipedia-w fa-2x green-text" title="Agregar Wiki" ></i>
	        </h4>

	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">

	      

	        <h3 id="tituloWikiVista"></h3>
	      

	


	         
			<div id="contenidoWikiVista">


			</div>


	      </div>

	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL AGREGAR WIKI -->

<!-- FIN VISTA DE WIKI -->
<!-- FIN WIKI -->


<!-- VIDEO -->

<!-- VISTA VIDEO -->
<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade right" id="modalVideoPlayer" tabindex="-1" role="dialog" aria-labelledby="exampleModalPreviewLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-side modal-top-right" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloVideo"></h5>
        <button id="limpiarVideos" type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="contenidoModalVideo">
      	
      </div>
      <div class="modal-footer">
       	<span id="descripcionVideo"></span>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<!-- FIN  VISTA VIDEO -->

<!-- FIN VIDEO -->


<!-- VISTA ARCHIVO -->
<!-- Modal -->
<div class="modal fade right" id="modalArchivo" tabindex="-1" role="dialog" aria-labelledby="exampleModalPreviewLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-side modal-top-right" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloArchivo"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center" id="contenidoModalArchivo">
 
      </div>
      <div class="modal-footer">
        <span id="descripcionArchivo"></span>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<!-- FIN VISTA ARCHIVO -->


<br>
<!-- FIN CONTENIDO -->
<?php  

	include('inc/footer.php');

?>





<script>
	
	

	$(".recursoWiki").on('click', function(event) {
	    event.preventDefault();
	    /* Act on the event */
	    console.log("click");

	    $("#tituloWikiVista").text("");
	    $("#contenidoWikiVista").html("");

	    var edicionWiki = $(this).attr("id_wik");


	    $.ajax({
			url: 'server/obtener_wiki.php',
			type: 'POST',
			dataType: 'json',
			data: {edicionWiki},
			success: function(datos){
		
				$("#modalWiki").modal('show');
			    $("#tituloWikiVista").text(datos.nom_wik);
			    $("#contenidoWikiVista").html(datos.des_wik);

				

			}
		});	    
	    
	  });



	
</script>


<script>
	//VIDEO

	

	$(".recursoVideo").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		console.log("click");

		$("#contenidoModalVideo").html("");
		var vid_vid = $(this).attr("vid_vid");
		var nom_vid = $(this).attr("nom_vid");
		var des_vid = $(this).attr("des_vid");
		var url_vid = $(this).attr("url_vid");


		//ATRIBUTO A URL DE YOUTUBE PARA INCRUSTAR VIDEOS EN IFRAMES
		url_vid = url_vid.replace("watch?v=", "embed/");

		$("#tituloVideo").text(nom_vid);
		$("#descripcionVideo").text(des_vid);

		
		$("#modalVideoPlayer").modal('show');

		if (url_vid == "") {
			$("#contenidoModalVideo").append(
				'<div class="embed-responsive embed-responsive-16by9 z-depth-1-half">'+
	              '<iframe class="embed-responsive-item" src="../uploads/'+vid_vid+'" allowfullscreen controls autoplay></iframe>'+
	            '</div>'


			);
		}else{
			$("#contenidoModalVideo").append(
				'<div class="embed-responsive embed-responsive-16by9 z-depth-1-half">'+
				  '<iframe class="embed-responsive-item" src="'+url_vid+'" allowfullscreen allow="accelerometer; autoplay; encrypted-media; '+'gyroscope; picture-in-picture"></iframe>'+
				'</div>');

		}
		
	});

	$("#limpiarVideos").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		$("#contenidoModalVideo").html("");
	});


	$("#modalVideoPlayer").draggable();

</script>



<script>
  //ARCHIVO

  $(".recursoArchivo").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */
    console.log("click");

    $("#contenidoModalArchivo").html("");
    var arc_arc = $(this).attr("arc_arc");
    var nom_arc = $(this).attr("nom_arc");
    var des_arc = $(this).attr("des_arc");

    //console.log(des_arc);


    $("#tituloArchivo").text(nom_arc);
    $("#descripcionArchivo").text(des_arc);

    
    $("#modalArchivo").modal('show');
    $("#contenidoModalArchivo").append('<a href="../uploads/'+arc_arc+'" download class="btn-link"><i class="fas fa-file-download fa-2x"></i> Descargar</a>');
  });

  $("#limpiarArchivos").on('click', function(event) {
    event.preventDefault();
    /* Act on the event */
    $("#contenidoModalArchivo").html("");
  });


  $("#modalArchivo").draggable();
</script>