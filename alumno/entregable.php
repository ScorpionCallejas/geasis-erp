<?php  

	include('inc/header.php');

	obtenerEstatusPagoAlumnoGlobal( $id );
	
	$id_ent_cop = $_GET['id_ent_cop'];

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
		INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
		INNER JOIN empleado ON empleado.id_emp = profesor.id_emp3
		
		INNER JOIN entregable_copia ON entregable_copia.id_sub_hor3 = sub_hor.id_sub_hor
		INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
		INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5

		INNER JOIN cal_act ON cal_act.id_ent_cop2 = entregable_copia.id_ent_cop
		WHERE id_alu_ram4 = '$id_alu_ram' AND id_alu = '$id' AND id_ent_cop = '$id_ent_cop'
	";

	//echo $sqlValidacion;

	$resultadoValidacion = mysqli_query($db, $sqlValidacion);

	// $totalValidacion = mysqli_num_rows($resultadoValidacion);

	
	// if ($totalValidacion == 0) {
	// 	header('location: not_found_404_page.php');
	// }


	$filaValidacion = mysqli_fetch_assoc($resultadoValidacion);
	$id_blo = $filaValidacion['id_blo'];
	$nom_blo = $filaValidacion['nom_blo'];
	$des_blo = $filaValidacion['des_blo'];
	$con_blo = $filaValidacion['con_blo'];	
	$id_mat6 = $filaValidacion['id_mat6'];
	$nom_mat = $filaValidacion['nom_mat'];
	$nom_ram = $filaValidacion['nom_ram'];


	// PROFESOR
	$nom_pro = $filaValidacion['nom_pro']." ".$filaValidacion['app_pro']; 
	$tip_pro = $filaValidacion['tip_pro'];
	$id_pro = $filaValidacion['id_pro'];
	$fot_emp = $filaValidacion['fot_emp'];


	$id_mat = $filaValidacion['id_mat'];
	$id_ram = $filaValidacion['id_ram'];
	$nom_ent= $filaValidacion['nom_ent'];
	$des_ent = $filaValidacion['des_ent'];
	$pun_ent = $filaValidacion['pun_ent'];
	$ini_ent_cop = $filaValidacion['ini_ent_cop'];
	$fin_ent_cop = $filaValidacion['fin_ent_cop'];
	$nom_ent = $filaValidacion['nom_ent'];
	$id_ent_cop = $filaValidacion['id_ent_cop'];

	//$fechaHoy = date('Y-m-d');

	// VALIDACION DE FECHAS 
	// if ($fechaHoy < $ini_ent_cop || $fechaHoy > $fin_ent_cop) {
	// 	header("location: not_found_404_page.php");
	// }

	// DATOS DE ALUMNO RESPECTO A LA ACTIVIDAD


	if ($filaValidacion['pun_cal_act'] == NULL) {
		$calificacion_actividad = "Pendiente";
	}else{
		$calificacion_actividad = $filaValidacion['pun_cal_act'];
	}

	if ($filaValidacion['ret_cal_act'] == NULL) {
		$retroalimentacion_actividad = "Pendiente";
	}else{
		$retroalimentacion_actividad = $filaValidacion['ret_cal_act'];
	}


	//VALIDACION DE FECHAS 
	$fechaHoy = date('Y-m-d');

	if ( $filaValidacion['fec_cal_act'] == NULL ) {

		if ( $fechaHoy > $filaValidacion['fin_ent_cop'] ) {
		  
			header("location: not_found_404_page.php");
		}

	}


	
?>



<!-- ESTILOS ELIMINACION DE TAREA -->
<style>
	.botonHijo {
	  position: absolute;
	  right: 0%;
	  top: 0%;
	}


	.botonPadre {
	  position: relative;
	}



	/*SMALL CHAT*/
.chat-room.small-chat {
  /* position: fixed; */
  /* bottom: 0; */
  position: fixed;
  right: 0%;
  bottom: 0%;
  z-index: 100;
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
  width: 20rem; }
  .chat-room.small-chat.slim {
  height: 3rem; }
  .chat-room.small-chat.slim .icons .feature {
    display: none; }
  .chat-room.small-chat.slim .my-custom-scrollbar {
    display: none; }
  .chat-room.small-chat.slim .card-footer {
    display: none; }
  .chat-room.small-chat .profile-photo img.avatar {
    height: 2rem;
    width: 2rem; }
  .chat-room.small-chat .profile-photo .state {
    position: relative;
    display: block;
    background-color: #007E33;
    height: .65rem;
    width: .65rem;
    z-index: 2;
    margin-left: 1.35rem;
    left: auto;
    top: -.5rem;
    border-radius: 50%;
    border: .1rem solid #fff; }
  .chat-room.small-chat .profile-photo.message-photo {
    margin-top: 2.7rem; }
  .chat-room.small-chat .heading {
    height: 2.1rem; }
    .chat-room.small-chat .heading .data {
      line-height: 1.5; }
      .chat-room.small-chat .heading .data .name {
        font-size: .8rem; }
      .chat-room.small-chat .heading .data .activity {
        font-size: .75rem; }
  .chat-room.small-chat .icons {
    padding-top: .45rem; }
  .chat-room.small-chat .my-custom-scrollbar {
    position: relative;
    height: 18rem;
    overflow: auto; }
    .chat-room.small-chat .my-custom-scrollbar > .card-body {
      height: 18rem; }
      .chat-room.small-chat .my-custom-scrollbar > .card-body .chat-message .media img {
        width: 3rem; }
      .chat-room.small-chat .my-custom-scrollbar > .card-body .chat-message .media .media-body p {
        font-size: .7rem; }
      .chat-room.small-chat .my-custom-scrollbar > .card-body .chat-message .message-text {
        margin-left: .1rem; }
  .chat-room.small-chat .card-footer .form-control {
    border: none;
    padding: .375rem 0 .43rem 0;
    font-size: .9rem; }
    .chat-room.small-chat .card-footer .form-control:focus {
      box-shadow: none; }

  .bcg-preview {
    height: 535px;
  }
</style>
<!-- FIN ESTILOS DE ELIMINACION DE TAREA -->










<!-- DETALLES DEL ENTRGABLE -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Materias">
			<i class="fas fa-bookmark"></i> 
			Entregable: <?php echo $nom_ent; ?>
		</span>
		<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
		<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
		<i class="fas fa-angle-double-right"></i>

			<a class="text-white" href="materias_horario.php?id_alu_ram=<?php echo $id_alu_ram; ?>" title="Vuelve a Materias">Materias</a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="bloque_contenido.php?id_blo=<?php echo $id_blo."&id_alu_ram=".$id_alu_ram; ?>"title="Vuelve a Bloques">Bloques</a>
		<i class="fas fa-angle-double-right"></i>
		<a style="color: black;" href="" title="Estás aquí">Entregable</a>
		</div>
	</div>
	<div class="col text-right">
		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Rama de Estudio <?php echo $nom_ram; ?>">
			<i class="fas fa-certificate"></i>
			Carrera: <?php echo $nom_ram; ?>
		</span><br><br>

		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Materia en la que te Encuentras <?php echo $nom_mat; ?>">
			<i class="fas fa-angle-right"></i>
			Materia: <?php echo $nom_mat; ?>
		</span><br><br>



		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Bloque en el que te Encuentras <?php echo $nom_blo; ?>">
			<i class="fas fa-angle-right"></i>
			Bloque: <?php echo $nom_blo; ?>
		</span><br>


	</div>
		
</div>

<!-- DETALLES DEL ENTREGABLE -->
<div class="jumbotron grey lighten-1" >
	<div class="row">
		<div class="col-md-4" >
			<div class="jumbotron bg-light mb-3" data-step="1" data-intro="Detalles de la actividad para entregar" data-position='right'>

					<h4 class="h4 text-center" title="Detalles de la actividad">
						Detalles
					</h4>

					<hr>


					<table class="table table-hover table-responsive">
						

						<tbody>
							<tr data-step="2" data-intro="Aquí se define cuánto vale en puntos la actividad" data-position='right'>
								<td>
									<span>
										<h6 class="h6">
											Puntos: 
										</h6>
									</span>
								</td>

								<td>
									<h5>
										<span class="badge badge-info">
											<?php echo $pun_ent; ?>
										</span>
									</h5>
								</td>
							</tr>


							<tr data-step="3" data-intro="Desde cuándo la puedes realizar" data-position='right'>
								<td>
									<span>
										<h6 class="h6">
											Inicio: 
										</h6>
									</span>
									
								</td>

								<td>
									<h5>
										<span class="badge badge-info">
											<?php echo fechaFormateadaCompacta($ini_ent_cop); ?>
										</span>
									</h5>
								</td>
							</tr>

							<tr data-step="4" data-intro="...y la fecha límite para su entrega" data-position='right'>
								<td>
									<span>
										<h6 class="h6">
											Fin: 
										</h6>
									</span>
								</td>

								<td>
									<h5>
										<span class="badge badge-info">
											<?php echo fechaFormateadaCompacta($fin_ent_cop); ?>
										</span>
									</h5>
									
								</td>
							</tr>

							<tr data-step="5" data-intro="Aquí se define si ya la hiciste o no" data-position='right'>
								<td>
									<span>
										<h6 class="h6">
											Estatus: 
										</h6>
									</span>
								</td>

								<td>
									<h5>
										<span class="badge badge-warning">
											<?php 

												if ($filaValidacion['pun_cal_act'] == NULL) {
													echo "Pendiente";
												}else{
													echo "Finalizado";
												}
												
											?>
										</span>
									</h5>
								</td>
							</tr>


							<tr data-step="6" data-intro="Aquí cuándo la hiciste" data-position='right'>
								<td>
									<span>
										<h6 class="h6">
											Fecha de realización:
										</h6>
										 
									</span>
								</td>

								<td>
									<h5>
										<span class="badge badge-warning">
											<?php 

												if ($filaValidacion['fec_cal_act'] == NULL) {
													$fecha_actividad = "Pendiente";
													echo $fecha_actividad;
												}else{
													$fecha_actividad = $filaValidacion['fec_cal_act'];
													echo fechaHoraFormateadaCompacta($fecha_actividad);
												}
												
											?>		
										</span>
									</h5>
								</td>
							</tr>

							<tr data-step="7" data-intro="Y aquí, cuántos puntos te dio el profesor responsable con base en tu desempeño" data-position='right'>
								<td>
									<span>
										<h6 class="h6">
											Puntos Obtenidos:
										</h6> 
									</span>
									
								</td>

								<td>
									<h5>
										<span class="badge badge-warning">
											<?php echo $calificacion_actividad; ?>	
										</span>
									</h5>
								</td>
							</tr>
						</tbody>
					</table>					
				
	
			</div>	



			<!-- RECURSOS TEORICOS JUMBOTRON -->
			<div class="jumbotron mdb-color  bg-light mb-3">
				<h4 class="mb-2 h4 white-text" title="Recursos Teóricos" >Recursos Teóricos</h4>
				<div class="accordion md-accordion accordion-1" id="accordionEx23" role="tablist">

					<!-- VIDEOS -->
					<div class="card">
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
					<div class="card">

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
					<div class="card">

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
		</div>


		<!-- CONTENIDO DE ACTIVIDAD -->
		<div class="col-md-8" data-step="8" data-intro="Las instrucciones de la actividad están aquí" data-position='right'>
			<!-- Jumbotron -->
			<div class="jumbotron mdb-color  grey lighten-4  black-text mx-2 mb-5">
				<?php  

					echo $des_ent;
				?>
			</div>
			<!-- FIN Jumbotron -->
			
		</div>

	
	</div>
	

</div>
<!-- FIN DETALLES DEL ENTREGABLE -->
	
	
		<?php

			$sqlValidacionTarea = "
				SELECT * 
				FROM entregable_copia
				INNER JOIN tarea ON tarea.id_ent_cop1 = entregable_copia.id_ent_cop
				WHERE id_ent_cop = '$id_ent_cop' AND id_alu_ram6 = '$id_alu_ram'
				ORDER BY id_tar DESC
				LIMIT 1
			
			";

			$resultadoValidacionTarea = mysqli_query($db, $sqlValidacionTarea);
			$filaValidacionTarea = mysqli_fetch_assoc($resultadoValidacionTarea);

			$totalTareas = mysqli_num_rows($resultadoValidacionTarea);

			// VALIDACION SI ENTREGO O NO TAREA POR USUARIO
			if ($totalTareas == 1) {
		?>	
			<div class="jumbotron mdb-color  grey lighten-4  black-text mx-2 mb-5 text-center botonPadre" >
				<a href="../uploads/<?php echo $filaValidacionTarea['doc_tar']; ?>" download class="btn-link" title="Descargar: <?php echo $filaValidacionTarea['doc_tar']; ?>">
					<h4>
						<i class="fas fa-file-alt fa-2x"></i>
						<br>
						<?php echo $filaValidacionTarea['doc_tar']; ?>
					</h4>
					
				</a>


				<!-- BOTON ELIMINACION -->
				<div class="waves-effect btn-sm btn-danger btn-floating botonHijo eliminacionTarea" tarea="<?php echo $filaValidacionTarea['id_tar']; ?> ">
					<i class="fas fa-times-circle fa-2x" title="Elimina tu tarea"></i>
				</div>
				<!-- FIN BOTON ELIMINACION -->
			</div>


		<?php
			}else{
		?>

			<!-- DRAG AND DROP FORMULARIO -->
			<div class="jumbotron mdb-color  grey lighten-4  black-text mx-2 mb-5 text-center" data-step="9" data-intro="La tarea su sube aquí, solo un archivo, el sistema acepta diversos formatos como imágenes (jpg, jpeg o png), word, pdf, excel, power point... ¡entre muchos más!" data-position='right'>

				<div class="row">
					<div class="col-md-12">
						<section class="my-5">
							<form id="agregarTareaFormulario" enctype="multipart/form-data" method="POST">
							    						
								<div class="file-upload-wrapper">
						          <div class="input-group mb-3 border border-success">
						            <input type="file" id="doc_tar" name="doc_tar" class="file_upload " placeholder="Sube Archivo"  required="" />
						          </div>
						        </div>

						        <div class="progress md-progress" style="height: 20px">
							        <div class="progress-bar text-center white-text" role="progressbar" style="width: 0%; height: 20px;" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" id="barra_estado_archivo">
							            
							          
							        </div>
							    </div>

						        <button class="btn btn-info white-text" type="submit" title="Subir Archivo" id="btn_enviar" data-step="10" data-intro="Cuando tengas tu tarea lista, arrastra, suelta y presiona este botón para subir" data-position='right'>
						        	<i class="fas fa-upload fa-2x"></i> Subir tarea
						        </button>	

							</form>
						  
						</section>
					</div>
				</div>
			</div>	
			<!-- FIN DRAG AND DROP FORMULARIO -->

		<?php
			}

		?>
	
	

	
<!-- MENSAJES -->
<div class="container mt-5">

  <!-- Grid row -->
  <div class="row d-flex flex-row-reverse">

    <!-- Grid column -->
    <div class="col-md-6 mb-4 d-flex flex-row-reverse">

      <div class="card chat-room small-chat wide" id="myForm">
        <div class="card-header white d-flex justify-content-between p-2">
          <div class="heading d-flex justify-content-start">
            
            <div class="profile-photo">
              <img src="../uploads/<?php echo $fot_emp; ?>" alt="profesor" class="avatar rounded-circle mr-2 ml-0">
         
            </div>
            <div class="data">
              <p class="name mb-0">
              	<strong>
              		<?php echo $nom_pro; ?>
              	</strong>
              </p>
              <p class="activity text-muted mb-0">Profesor</p>
            </div>
          </div>
          <div class="icons grey-text">
            
            <a id="toggle" style="cursor: pointer;">
            	<!-- <i class="fas fa-times "></i> -->
            	<i class="fas fa-window-minimize mr-2"></i>
            </a>
          </div>
        </div>
        <div class="my-custom-scrollbar" id="message">
          <div class="card-body p-3">
            <div class="chat-message">
              
              <div class="media mb-3">
                <img class="d-flex rounded mr-2" src="../uploads/<?php echo $fot_emp; ?>" alt="profesor">
                <div class="media-body">
                  <p class="my-0">Soy tu profesor de <?php echo $nom_mat; ?>, bienvenido a <?php echo $nombrePlantel; ?></p>
                  <p class="mb-0 text-muted">Cualquier duda, mándame un mensaje y a la brevedad te contesto ;)</p>
                </div>
              </div>
				
              
				<div id="contenedor_mensajes">
					
				</div>

    


          
              

              
            </div>
          </div>
        </div>
        <div class="card-footer text-muted white pt-1 pb-2 px-3">
          <input type="text" id="input_mensaje" class="form-control" placeholder="Escribe al profesor...">
         
        </div>
      </div>

    </div>
    <!-- Grid column -->

  </div>
  <!-- Grid row -->

</div>

<!-- FIN MENSAJES -->





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


<?php  

	include('inc/footer.php');

?>

<script>
    $('.file_upload').file_upload();

</script>


<script>

	//FORMULARIO DE CREACION DE ALUMNO
	//CODIGO PARA AGREGAR ALUMNO NUEVO ABRIENDO MODAL

	$('#agregarTareaFormulario').on('submit', function(event) {
		event.preventDefault();

		if ($("#doc_tar")[0].files[0]) {

			var fileName = $("#doc_tar")[0].files[0].name;
			var fileSize = $("#doc_tar")[0].files[0].size;


			var ext = fileName.split('.').pop();

			
			if(ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'doc' || ext == 'docx' || ext == 'ppt' || ext == 'pptx' || ext == 'pdf' || ext == 'xlsx'){
				if (fileSize < 10000000) {
					$("#btn_enviar").removeClass('btn-info').addClass('light-green accent-4').html('<i class="fas fa-cog fa-spin white-text"></i> <span>Cargando...</span>');
					let barra_estado_archivo = $("#barra_estado_archivo");

					//Eliminacion de "Listo"
					barra_estado_archivo.text("");

					//Remueve clase de estatus listo
					barra_estado_archivo.removeClass();

					//Agrega la clase inicial del progress bar
					barra_estado_archivo.addClass('progress-bar text-center white-text');


					var agregarTareaFormulario = new FormData( $('#agregarTareaFormulario')[0] );
					agregarTareaFormulario.append( 'id_pro' ,  '<?php echo $id_pro; ?>' ); 

					$.ajax({

						xhr: function() {
						  
						    var peticion = new window.XMLHttpRequest();

						    peticion.upload.addEventListener("progress", (event)=>{
						    let porcentaje = Math.round((event.loaded / event.total) *100);
						    //console.log(porcentaje);

						    barra_estado_archivo.attr({style: 'width:'+porcentaje+'%; height: 20px;'});
						    barra_estado_archivo.text(porcentaje+'%');

						  });

						  peticion.addEventListener("load", ()=>{
						    barra_estado_archivo.removeClass();
						    barra_estado_archivo.addClass('progress-bar text-center white-text bg-success');
						    barra_estado_archivo.text("Listo");

						    toastr.success('¡Subido Correctamente!');
						  });

						  return peticion;
						  },
						url: 'server/agregar_tarea.php?id_alu_ram=<?php echo $id_alu_ram."&id_ent_cop=".$id_ent_cop; ?>',
						type: 'POST',
						data: agregarTareaFormulario,
						processData: false,
						contentType: false,
						cache: false,
						success: function(respuesta){
							console.log(respuesta);
						  if (respuesta == "Exito") {
						    console.log("Guardado Exitosamente");

						    $("#btn_enviar").html('<i class="fas fa-check white-text"></i> <span>Subida Exitosa</span>');
						    swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",}).
						    then((value) => {
						      window.location.reload();
						    });
						  }
						}
					});
				}else{
					swal ( "¡Archivo inválido!" ,  "¡Te recordamos que el peso no debe exceder los 10MB!" ,  "error" );
				}
				
			}else{
				swal ( "¡Archivo inválido!" ,  "¡Te recordamos que los formatos aceptados son word, excel, power point, pdf, jpeg, jpg o png!" ,  "error" );
			}

		}



		
			
			
		
	});




	//ELIMINACION TAREA
	// ELIMINACION DE COMENTARIOS


	$('.eliminacionTarea').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var tarea = $(this).attr("tarea");
		// console.log(BLOQUE);

		swal({
		  title: "¿Deseas eliminar tu tarea?",
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
				url: 'server/eliminacion_tarea.php',
				type: 'POST',
				data: {tarea},
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


	
</script>


<script>
//MENSAJES



// VALIDACION DE QUE EXISTE LA SALA
//CASO VERDADERO SE CARGAN LOS MENSAJES
// CASO FALSO NO ENTRA A LA CONDICION

var idDestino = <?php echo $id_pro; ?>;
var tipoDestino = 'Profesor';

$.ajax({
	url: 'server/validacion_contacto.php',
	type: 'POST',
	data: {idDestino, tipoDestino},
	success: function(response){
		console.log(response);

		if (!isNaN(response)) {

			cargarMensajes(response);

		}else{
			console.log("la sala no existe");
		}
		

	}
});

//CREACION DE SALA Y ENVIO DE MENSAJES
$("#input_mensaje").on("keypress", function(e) {
	  //const $eTargetVal = $(e.target).val();
	if (e.keyCode === 13 && $(this).val().length > 0) {
		var actividad = "<?php echo $nom_blo." - ".$nom_ent; ?>";
		var mensaje = actividad+"<br>"+$(this).val();
		var idDestino = <?php echo $id_pro; ?>;
		var tipoDestino = 'Profesor';

		$.ajax({
			url: 'server/contacto.php',
			type: 'POST',
			data: {idDestino, tipoDestino, mensaje},
			success: function(response){
				console.log(response);
				if (!isNaN(response)) {

					cargarMensajes(response);

				}else{
					console.log("la sala no existe");
				}
				$('#input_mensaje').val("");
				//toastr.info('¡Enviado correctamente!');

			}
		});

	}else{
		console.log("Mensaje vacio");
	}
});






function cargarMensajes(id_sal){
	var aux = 0;
 	var temporizador = setInterval(function(){
        $.ajax({
          type: "POST",
          url: "server/listar_mensajes_actividad.php",
          data: {id_sal},
          

          success: function(response){
              //console.log(response);
              $('#contenedor_mensajes').html(response);
              $('#contenedor_mensajes p:last-child');
              var mensajes = $('#aux').attr("value");
              

              if (mensajes > aux) {
              	var altura = $("#contenedor_mensajes").prop("scrollHeight");
              	$("#message").scrollTop(altura);

              	aux = mensajes;	
              }


              console.log(id_sal);
              $('.elementos').on('click', function(event) {
                clearInterval(temporizador);

              });

          }

      	});
 	}, 3000);

}

	
</script>


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