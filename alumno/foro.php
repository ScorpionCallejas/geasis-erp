<?php  

	include('inc/header.php');

	obtenerEstatusPagoAlumnoGlobal( $id );
	
	$id_for_cop = $_GET['id_for_cop'];

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

		INNER JOIN foro_copia ON foro_copia.id_sub_hor2 = sub_hor.id_sub_hor
		INNER JOIN foro ON foro.id_for = foro_copia.id_for1
		INNER JOIN bloque ON bloque.id_blo = foro.id_blo4

		INNER JOIN cal_act ON cal_act.id_for_cop2 = foro_copia.id_for_cop
		WHERE id_alu_ram4 = '$id_alu_ram' AND id_alu = '$id' AND id_for_cop = '$id_for_cop'
	";


	//echo $sqlValidacion;

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
	$des_for = $filaValidacion['des_for'];
	$pun_for = $filaValidacion['pun_for'];
	$ini_for_cop = $filaValidacion['ini_for_cop'];
	$fin_for_cop = $filaValidacion['fin_for_cop'];
	$nom_for = $filaValidacion['nom_for'];

	// PROFESOR
	$nom_pro = $filaValidacion['nom_pro']." ".$filaValidacion['app_pro']; 
	$tip_pro = $filaValidacion['tip_pro'];
	$id_pro = $filaValidacion['id_pro'];
	$fot_emp = $filaValidacion['fot_emp'];

	$id_for_cop = $filaValidacion['id_for_cop'];


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

		if ( $fechaHoy > $filaValidacion['fin_for_cop'] ) {
		  
			header("location: not_found_404_page.php");

		}

	}


	
?>



<!-- ESTILOS ELIMINACION DE COMENTARIO Y REPLICA -->
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
<!-- FIN ESTILOS DE ELIMINACION DE COMENTARIO Y REPLICA -->
 <!-- TITULO -->
<div class="row ">

	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Ramas"><i class="fas fa-bookmark"></i> Foro: <?php echo $nom_for ?></span>
		<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>

			<a class="text-white" href="materias_horario.php?id_alu_ram=<?php echo $id_alu_ram; ?>" title="Vuelve a Materias">Materias</a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Foro</a>
		</div>		
	</div>
	<div class="col text-right">
		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Calendario de <?php echo $nom_ram; ?>">
			<i class="fas fa-certificate"></i>
			Carrera: <?php echo $nom_ram; ?></span>
			<br><br>
			<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Materia en la que te Encuentras <?php echo $nom_mat; ?>">
				<i class="fas fa-angle-double-right"></i>
				Materia: <?php echo $nom_mat; ?>
			</span>

			<br><br>
			<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Bloque en el que te Encuentras <?php echo $nom_blo; ?>">
			<i class="fas fa-angle-right"></i>
			Bloque: <?php echo $nom_blo; ?>
			</span>	
	</div>
</div>
<!-- FIN TITULO -->

<!-- DETALLES DEL FORO -->
<div class="jumbotron grey lighten-1">
	<div class="row">
		<div class="col-md-4">
			<div class="jumbotron bg-light mb-3" data-step="1" data-intro="Detalles de la actividad tipo foro" data-position='right'>
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
											<?php echo $pun_for; ?>
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
											<?php echo fechaFormateadaCompacta($ini_for_cop); ?>
										</span>
									</h5>
								</td>
							</tr>

							<tr data-step="4" data-intro="...y la fecha límite para su realización" data-position='right'>
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
											<?php echo fechaFormateadaCompacta($fin_for_cop); ?>
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


							<tr  data-step="6" data-intro="Aquí cuándo la hiciste" data-position='right'>
								<td title="Fecha en la que realizaste la actividad">
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
		</div>


		<!-- CONTENIDO DE ACTIVIDAD -->
		<div class="col-md-8" data-step="8" data-intro="Las instrucciones de la actividad están aquí" data-position='right'>
			<!-- Jumbotron -->
			<div class="jumbotron mdb-color  grey lighten-4  black-text mx-2 mb-5" >
				<?php  

					echo $des_for;
				?>
			</div>
			<!-- FIN Jumbotron -->
			
		</div>

	
	</div>
	

</div>
<!-- FIN DETALLES DEL FORO -->
	


	<!-- CAJA DE COMENTARIOS Y REPLICAS -->


	<div class="jumbotron grey lighten-4">
		<div class="row">

			<div class="col-md-12">
				<section class="my-5 grey lighten-4 p-4" data-step="9" data-intro="Aquí tendrás que dejar una opinión bien argumentada que satisfaga los criterios del profesor en las instrucciones que dejó" data-position='right'>

				  <div class="card-header border-0 font-weight-bold bg-info">Mi comentario</div>

				  <div class="d-md-flex flex-md-fill px-1">
				    <div class="d-flex justify-content-center mr-md-5 mt-md-5 mt-4">
				      <img class="card-img-64 z-depth-1 rounded-circle" src="../uploads/<?php echo $foto; ?>"
				        alt="avatar">
				    </div>
				    <div class="md-form w-100">

				      <textarea class="form-control md-textarea pt-0" id="comentario" rows="5" placeholder="Deja un comentario"></textarea>
				    </div>
				  </div>
				  <div class="text-center" data-step="10" data-intro="Cuando estés listo presiona el botón para enviar tu comentario" data-position='right'>
				    <button class="btn btn-default btn-rounded btn-md" id="btn_enviar">Enviar</button>
				  </div>

				</section>
			</div>
			<div class="col-md-12">
				<section class="my-5 grey lighten-4 p-4" data-step="11" data-intro="Cuando hayas mandado tu comentario acá podrás visualizarlo, borrarlo o replicar a otros que ya comentaron haciendo click sobre la flecha que gira, ¡mucho éxito!" data-position='right'>
					
			
					<?php  

						$sqlComentarios = "
							SELECT * 
							FROM comentario
							INNER JOIN alu_ram ON alu_ram.id_alu_ram = comentario.id_alu_ram5
							INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1 
							WHERE id_for_cop1 = '$id_for_cop' 
							ORDER BY id_com DESC
						";
						$resultadoComentarios = mysqli_query($db, $sqlComentarios);

						$totalComentarios = mysqli_num_rows($resultadoComentarios);

					?>
					<!-- Card header -->
					<!-- TOTAL COMENTARIOS -->
					<div class="card-header border-0 font-weight-bold bg-info">
						Total comentarios: <?php echo $totalComentarios; ?>
					</div>
					<!-- FIN TOTAL COMENTARIOS -->


					<?php

						while ($filaComentarios = mysqli_fetch_assoc($resultadoComentarios)) {
					?>
							<div class="media d-block d-md-flex mt-4">
								<!-- FOTO ALUMNO -->
								<img class="card-img-64 rounded-circle z-depth-1 d-flex mx-auto mb-3" src="../uploads/<?php echo $filaComentarios['fot_alu']; ?>" alt="Generic placeholder image">
								<!-- FIN FOTO ALUMNO -->



								<div class="media-body text-center text-md-left ml-md-3 ml-0">
									
									
									

									<!-- COMENTARIO -->
									<div class="  grey lighten-2 p-4 botonPadre" style="border-radius: 50px;">
										<!-- NOMBRE ALUMNO -->
										<h5 class="font-weight-bold mt-0">
											<a class="text-default" href="#">
												<?php  
													echo $filaComentarios['nom_alu']." ".$filaComentarios['app_alu']." ".$filaComentarios['apm_alu'];
												?>
											</a>
											<a href="#" class="pull-right text-default replica" comentario="<?php echo $filaComentarios['id_com']; ?>" alumno="<?php echo $filaComentarios['nom_alu']." ".$filaComentarios['app_alu']; ?>" title="Agrega una réplica">
											  <i class="fas fa-reply"></i>
											</a>

											<br>

									    	<span style="font-size: 14px; color: grey;">
									    		<?php
									    			$fechaComentario = $filaComentarios['fec_com']; 
									    			echo fechaHoraFormateada($fechaComentario); 
									    		?>	
									    	
									    	</span>
										</h5>
										<!-- FIN NOMBRE ALUMNO -->
										
										<span>
											<?php echo $filaComentarios['com_com']; ?>
										</span>
										

										<?php 

											if ($filaComentarios['id_alu_ram'] == $id_alu_ram) {
										?>
												<!-- BOTON ELIMINACION -->
												<div class="waves-effect btn-sm btn-danger btn-floating botonHijo  eliminacionComentario" comentario="<?php echo $filaComentarios['id_com']; ?> ">
													<i class="fas fa-times-circle fa-2x" title="Elimina tu comentario"></i>
												</div>
												<!-- FIN BOTON ELIMINACION -->
										<?php
											}
										?>
										
									</div>
									<!-- FIN COMENTARIO -->
								
									
									<?php  

										$id_com = $filaComentarios['id_com'];
										$sqlReplicas = "
											SELECT * 
											FROM replica
											INNER JOIN comentario ON comentario.id_com = replica.id_com1
											INNER JOIN alu_ram ON alu_ram.id_alu_ram = replica.id_alu_ram7
											INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1 
											WHERE id_com1 = '$id_com'
											ORDER BY id_rep ASC
										";

										$resultadoReplicas = mysqli_query($db, $sqlReplicas);

										while ($filaReplicas = mysqli_fetch_assoc($resultadoReplicas)) {
									?>		

										<!-- REPLICA -->
										<div class="media d-block d-md-flex mt-4">
											<!-- FOTO ALUMNO -->
												<img class="img-replica rounded-circle z-depth-1 d-flex mx-auto mb-3" src="../uploads/<?php echo $filaReplicas['fot_alu']; ?>" alt="Generic placeholder image">
											<!-- FIN FOTO ALUMNO -->
											<div class="media-body text-center text-md-left ml-md-3 ml-0">
												
												<div class="  grey lighten-2 p-4 botonPadre" style="border-radius: 50px;">
												
													<h5 class="font-weight-bold mt-0">
														<!-- NOMBRE ALUMNO -->
												    	<a class="text-default" href="">
												    		<?php  
												    			echo $filaReplicas['nom_alu']." ".$filaReplicas['app_alu']." ".$filaReplicas['apm_alu'];
												    		?>
												    	</a>
												    	<!-- FIN NOMBRE ALUMNO -->


												    	<a href="#" class="pull-right text-default replica" comentario="<?php echo $filaComentarios['id_com']; ?> " alumno="<?php echo $filaReplicas['nom_alu']." ".$filaReplicas['app_alu']; ?>" title="Agrega una réplica">
												      		<i class="fas fa-reply"></i>
												    	</a>
												    	<br>

												    	<span style="font-size: 14px; color: grey;">
												    		<?php
												    			$fechaReplica = $filaReplicas['fec_rep']; 
												    			echo fechaHoraFormateada($fechaReplica); 
												    		?>	
												    	
												    	</span>
													</h5>
													<!-- REPLICA -->
													<span>
														<?php  

															echo $filaReplicas['rep_rep'];
														?>
													</span>
													<!-- FIN REPLICA -->


													<?php 

														if ($filaReplicas['id_alu_ram'] == $id_alu_ram) {
													?>
															<!-- BOTON ELIMINACION -->
															<div class="waves-effect btn-sm btn-danger btn-floating botonHijo eliminacionReplica" replica="<?php echo $filaReplicas['id_rep']; ?> ">
																<i class="fas fa-times-circle fa-2x" title="Elimina tu comentario"></i>
															</div>
															<!-- FIN BOTON ELIMINACION -->
													<?php
														}
													?>
												</div>

												
											</div>
										</div>
									<!-- FIN REPLICA -->
									<?php
										}

									?>
										
									

								</div>
							</div>

					


					<?php		
						}


					?>

				  	

					

					<br>
					<br>
				</section>
			</div>
		</div>

	</div>
	
	<!-- FIN CAJA DE COMENTARIOS Y REPLICAS -->


<!-- MODAL REPLICA -->
<!-- CONTENIDO MODAL AGREGAR REPLICA -->
<div class="modal fade text-left" id="agregarReplicaModal">
  <div class="modal-dialog" role="document">
    
	<form id="agregarReplicaFormulario" enctype="multipart/form-data" method="POST">
	    <div class="modal-content">
	      <div class="modal-header text-center">
	        <h4 class="modal-title w-100 font-weight-bold" id="tituloModalReplica"></h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body mx-3">



			
			<div class="md-form mb-5">
	          <i class="fab fa-replyd prefix grey-text" title="Genera una réplica argumentada"></i>
	          <textarea class="form-control md-textarea pt-0" id="rep_rep" name="rep_rep"  rows="5" placeholder="Deja una réplica" required=""></textarea>
	        </div>


	        <input type="hidden" value="" name="id_com" id="id_com">
	       



	      </div>

	      <div class="modal-footer d-flex justify-content-center">
	        <button class="btn btn-info" type="submit">Replicar <i class="fas fa-paper-plane-o ml-1"></i></button>
	      </div>

	    </div>
	</form>

  </div>
</div>
<!-- FIN CONTENIDO MODAL AGREGAR REPLICA -->
<!-- FIN MODAL REPLICA -->

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
<?php  

	include('inc/footer.php');

?>



<script>
	//AGREGADO DE COMENTARIOS

	$("#btn_enviar").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var comentario = $("#comentario").val();

		var id_pro = '<?php echo $id_pro; ?>';

		if (comentario != "") {
			var id_alu_ram5 = <?php echo  $id_alu_ram; ?>;
			var id_for_cop1 = <?php echo $id_for_cop; ?> 
			$.ajax({
				url: 'server/agregar_comentario.php',
				type: 'POST',
				data: {comentario, id_alu_ram5, id_for_cop1, id_pro},
				success: function(respuesta){
					swal("Agregado correctamente", "Continuar", "success", {button: "Aceptar",}).
					then((value) => {
					  window.location.reload();
					});

				}
			});
		}else{
			swal("¡Faltan Datos!", "Comentario vacío, asegúrate de proporcionar una opinión", "warning", {button: "Aceptar",});
		}
		
	});



	// AGREGADO DE REPLICAS




	// ELIMINACION DE COMENTARIOS


	$('.eliminacionComentario').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var comentario = $(this).attr("comentario");
		// console.log(BLOQUE);

		swal({
		  title: "¿Deseas eliminar tu comentario?",
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
				url: 'server/eliminacion_comentario.php',
				type: 'POST',
				data: {comentario},
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


	// ELIMINACION DE REPLICAS

	$('.eliminacionReplica').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var replica = $(this).attr("replica");
		// console.log(BLOQUE);

		swal({
		  title: "¿Deseas eliminar tu replica?",
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
				url: 'server/eliminacion_replica.php',
				type: 'POST',
				data: {replica},
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

	//FORMULARIO DE CREACION DE REPLICA A COMENTARIO Y A REPLICA
	//CODIGO PARA AGREGAR REPLICA NUEVO ABRIENDO MODAL
	$('.replica').on('click', function(event) {
		event.preventDefault();
		$('#agregarReplicaModal').modal('show');
		$('#agregarReplicaFormulario').trigger("reset"); //BORRADO DE INPUTS CON DISPARADOR
		var comentario = $(this).attr("comentario");
		$("#id_com").val(comentario);

		
		var alumno = $(this).attr("alumno");
		$("#tituloModalReplica").html("Replica a "+alumno);//SABER A QUIEN REPLICA EN PURA MODAL


	});


	$('#agregarReplicaFormulario').on('submit', function(event) {
		event.preventDefault();

		var agregarReplicaFormulario = new FormData( $('#agregarReplicaFormulario')[0] );
		agregarReplicaFormulario.append( 'id_pro' ,  '<?php echo $id_pro; ?>' );
			
		$.ajax({
		
			url: 'server/agregar_replica.php?id_alu_ram=<?php echo $id_alu_ram."&id_for_cop=".$id_for_cop; ?>',
			type: 'POST',
			data: agregarReplicaFormulario, 
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
		var actividad = "<?php echo $nom_blo." - ".$nom_for; ?>";
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