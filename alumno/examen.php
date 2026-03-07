<?php  
 
	include('inc/header.php');
	
	obtenerEstatusPagoAlumnoGlobal( $id );

	$id_exa_cop = $_GET['id_exa_cop'];

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
		
		INNER JOIN examen_copia ON examen_copia.id_sub_hor4 = sub_hor.id_sub_hor
		INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
		INNER JOIN bloque ON bloque.id_blo = examen.id_blo6

		INNER JOIN cal_act ON cal_act.id_exa_cop2 = examen_copia.id_exa_cop
		WHERE id_alu_ram4 = '$id_alu_ram' AND id_alu = '$id' AND id_exa_cop = '$id_exa_cop'
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

	// PROFESOR
	$nom_pro = $filaValidacion['nom_pro']." ".$filaValidacion['app_pro']; 
	$tip_pro = $filaValidacion['tip_pro'];
	$id_pro = $filaValidacion['id_pro'];
	$fot_emp = $filaValidacion['fot_emp'];


	$nom_ram = $filaValidacion['nom_ram'];
	$nom_exa= $filaValidacion['nom_exa'];
	$id_exa = $filaValidacion['id_exa'];
	$id_mat = $filaValidacion['id_mat'];
	$id_ram = $filaValidacion['id_ram'];
	$des_exa = $filaValidacion['des_exa'];
	$pun_exa = $filaValidacion['pun_exa'];
	$ini_exa_cop = $filaValidacion['ini_exa_cop'];
	$fin_exa_cop = $filaValidacion['fin_exa_cop'];


	$id_exa_cop = $filaValidacion['id_exa_cop'];

	$dur_exa = $filaValidacion['dur_exa'];


	if ($filaValidacion['pun_cal_act'] == NULL) {
		//echo "Pendiente";
		$estatus_actividad = "Pendiente";
	}else{
		//echo "Finalizado";
		$estatus_actividad = "Finalizado";
	}



	$fechaHoy = date('Y-m-d');


	if ( $filaValidacion['fec_cal_act'] == NULL ) {

		if ( $fechaHoy > $filaValidacion['fin_exa_cop'] ) {
		  
		  	header("location: not_found_404_page.php");
		}
	}


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
	
?>



<!-- ESTILOS -->
<style>
	#timer {
	  position: -webkit-sticky;
	  position: sticky;
	  top: 0;
	  zoom: .7;
   	  -moz-transform: scale(0.5)

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
<!-- FIN ESTILOS -->



 <!-- TITULO -->
<div class="row ">

	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Ramas"><i class="fas fa-bookmark"></i> Examen: <?php echo $nom_exa; ?></span>
		<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
		
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="materias_horario.php?id_alu_ram=<?php echo $id_alu_ram; ?>" title="Vuelve a Materias">Materias</a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Examen</a>
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





<!-- DETALLES DEL EXAMEN -->
<div class="jumbotron grey lighten-1">
	<div class="row">
		<div class="col-md-4">
			<div class="jumbotron bg-light mb-3" data-step="1" data-intro="Detalles de la actividad examen" data-position='right'>
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
											<?php echo $pun_exa; ?>
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
											<?php echo fechaFormateadaCompacta($ini_exa_cop); ?>
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
											<?php echo fechaFormateadaCompacta($fin_exa_cop); ?>
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
													$estatus_actividad = "Finalizado";
												}
												
											?>	
										</span>
									</h5>
								</td>
							</tr>


							<tr data-step="6" data-intro="Aquí cuándo la hiciste" data-position='right'>
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

							<tr data-step="7" data-intro="Y aquí, cuántos puntos obtuviste con base en tu desempeño" data-position='right'>
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
			<div class="jumbotron mdb-color  grey lighten-4  black-text mx-2 mb-5">
				<?php  

					echo $des_exa;
				?>
			</div>
			<!-- FIN Jumbotron -->
			
		</div>

	
	</div>
	

</div>
<!-- FIN DETALLES DEL EXAMEN -->


<?php 


	if ($estatus_actividad == 'Finalizado') {


	//include('inc/footer.php');

?>


<!-- EXAMEN Y TIMER -->
<!-- EXAMEN FINALIZADO -->

<div class="jumbotron grey lighten-4">
	<div class="row">

		<?php 
			$sqlTotalPreguntas = "
				SELECT * 
				FROM pregunta
				INNER JOIN examen ON examen.id_exa = pregunta.id_exa2
				WHERE id_exa = '$id_exa'

				";

			$resultadoTotalPreguntas = mysqli_query($db, $sqlTotalPreguntas);

			$totalPreguntas = mysqli_num_rows($resultadoTotalPreguntas);
		?>
		<div class="col-md-4">
			<div class="jumbotron mdb-color text-center grey lighten-4  black-text mx-2 mb-5" style="max-width: 20rem;">
				<h4>
					<strong>
						Detalles del Examen	
					</strong>
				</h4>
				<hr>
				<h5>Tiempo: <?php echo $dur_exa; ?> minutos</h5>
				<h5>Valor: <?php echo $pun_exa; ?> puntos</h5>
				<h5>Total: <?php echo $totalPreguntas; ?> preguntas</h5>
				

				<?php  
					$sqlAciertos = "
						SELECT COUNT(id_res1) AS correctas
						FROM pregunta
						INNER JOIN respuesta ON respuesta.id_pre1 = pregunta.id_pre
						INNER JOIN respuesta_alumno ON respuesta_alumno.id_res1 = respuesta.id_res
						INNER JOIN alu_ram ON alu_ram.id_alu_ram = respuesta_alumno.id_alu_ram8
						WHERE id_alu_ram = '$id_alu_ram' AND val_res = 'Verdadero' AND id_exa2 = '$id_exa'
					";


					$resultadoAciertos = mysqli_query($db, $sqlAciertos);

					if (!$resultadoAciertos) {
						echo $sqlAciertos;
					}else{
						$filaAciertos = mysqli_fetch_assoc($resultadoAciertos);
					}

				?>
				<h5>

					
					Aciertos: <?php echo round($filaAciertos['correctas'])."/".$totalPreguntas; ?>
				</h5>
				
				

			</div>

			
		</div>


		<div class="col-md-8 text-center " id="contenedor_examen">

			<?php  
				$sqlPreguntas = "SELECT * FROM pregunta WHERE id_exa2 = '$id_exa'";
				$resultadoPreguntas = mysqli_query($db, $sqlPreguntas);
				$i = 1;
				$j = 1;
				while($filaPreguntas = mysqli_fetch_assoc($resultadoPreguntas)){
			?>
				<!-- Jumbotron -->
				<div class="jumbotron text-center mdb-color blue-grey lighten-1 white-text mx-2 mb-5 hoverable">
				
					


				  <!-- Title -->
				  <h4 class="card-title h4">
				  	<?php echo $i.".- ".$filaPreguntas['pre_pre']; ?>
				  </h4>

				  <!-- Grid row -->
				  <div class="row d-flex justify-content-center">


				    <!-- Grid column -->
				    <div class="col-xl-7 pb-2">

				      <p class="card-text text-warning">
				      	Valor del reactivo: <?php echo $filaPreguntas['pun_pre']; ?> puntos
				      </p>

				    </div>
				    <!-- Grid column -->

				  </div>
				  <!-- Grid row -->

				  <hr class="my-4 rgba-white-light">
				  

				  <!-- SECCION DE RESPUESTAS -->
				  <div class="pt-2">
				  	<?php


				  		$id_pre = $filaPreguntas['id_pre'];
				  		$sqlRespuestas = "SELECT * FROM respuesta WHERE id_pre1 = '$id_pre'";


				  		//echo $sqlRespuestas;
				  		$resultadoRespuesta = mysqli_query($db, $sqlRespuestas);
				  		
				  		while($filaRespuestas = mysqli_fetch_assoc($resultadoRespuesta)){
				  			
				  	?>
				  		<div class="form-check form-check-inline">
							
							<?php 
								$id_res = $filaRespuestas['id_res'];
								$sqlValidacionRespuestaAlumno = "

									SELECT * 
									FROM respuesta 
									INNER JOIN respuesta_alumno ON respuesta_alumno.id_res1 = respuesta.id_res
									WHERE id_res1 = '$id_res' AND id_alu_ram8 = '$id_alu_ram'
								";

								//echo $sqlValidacionRespuestaAlumno;

								$resultadoValidacionRespuestaAlumno = mysqli_query($db, $sqlValidacionRespuestaAlumno);

								$totalValidacionRespuestaAlumno = mysqli_num_rows($resultadoValidacionRespuestaAlumno);
								//echo $totalValidacionRespuestaAlumno;
								if ($totalValidacionRespuestaAlumno == 1) {
							?>

									<?php 

										if ($filaRespuestas['val_res'] == 'Verdadero') {
									?>
											<label class="form-check-label light-green accent-4 rounded waves-effect" for="materialGroupExample<?php echo $j; ?>">
												<?php 
													echo $filaRespuestas['res_res']." (".$filaRespuestas['val_res'].")";
													
												?> 
											</label>

									<?php
										}else{
									?>

											<label class="form-check-label red rounded waves-effect" for="materialGroupExample<?php echo $j; ?>">
												<?php 
													echo $filaRespuestas['res_res']." (".$filaRespuestas['val_res'].")";
													
												?> 
											</label>
									<?php
										}
									?>
									
							<?php
								}else{
							?>
									
									<label class="form-check-label" for="materialGroupExample<?php echo $j; ?>">
										<?php 
											echo $filaRespuestas['res_res']." (".$filaRespuestas['val_res'].")"; 
										?> 
									</label>
							<?php
								}
							?>
							
						</div>

						<br>
						<br>


				  	<?php
				  			$j++;

				  		}

				  		$i++;

				  	?>

				  </div>
				  <!-- FIN SECCION DE RESPUESTAS -->

				</div>
				<!-- Jumbotron -->


			<?php

				}

			?>
			
			
		</div>

		
	</div>
</div>



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



<!-- FIN EXAMEN Y TIMER -->

<?php  

	include('inc/footer.php');

?>


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
		var actividad = "<?php echo $nom_blo." - ".$nom_exa; ?>";
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

<?php
	}else{
		// NO HA HECHO EL EXAMEN
?>



<!-- EXAMEN Y TIMER -->

<div class="jumbotron grey lighten-4">
	<div class="row">

		<?php 
			$sqlTotalPreguntas = "
				SELECT * 
				FROM pregunta
				INNER JOIN examen ON examen.id_exa = pregunta.id_exa2
				WHERE id_exa = '$id_exa'

				";

			$resultadoTotalPreguntas = mysqli_query($db, $sqlTotalPreguntas);

			$totalPreguntas = mysqli_num_rows($resultadoTotalPreguntas);
		?>
		<div class="col-md-4">
			<div class="jumbotron mdb-color text-center grey lighten-4  black-text mx-2 mb-5" data-step="9" data-intro="Aquí se hallan los detalles del examen, cuánto dura, cuántas preguntas hay y cuánto vale en puntos" data-position='right'>
				<h4>
					<strong>
						Detalles del Examen	
					</strong>
				</h4>
				<hr>
				<h5>Tiempo: <?php echo $dur_exa; ?> minutos</h5>
				<h5>Valor: <?php echo $pun_exa; ?> puntos</h5>
				<h5>Total: <?php echo $totalPreguntas; ?> preguntas</h5>
				
				

			</div>

			<div class="jumbotron text-center mdb-color blue-grey lighten-1 white-text mx-2 mb-5 hoverable " id="timer" data-step="10" data-intro="Este es el temporizador que contará en cuenta regresiva y cuando llegue a cero hasta donde contestaste se te evaluará" data-position='right'>


				<div id="clock" style="margin:2em;" title="Cuando llegue a cero, no podrás hacer cambios y hasta donde hayas llegado serás evaluado"></div>

				<button class="btn btn-warning" title="Presiona este botón para comenzar el examen" id="btn_comenzar" data-step="11" data-intro="Cuando presiones este botón se aplicará el examen y empezará a correr el tiempo, ¡así que asegúrate de estar listo y mucho éxito!" data-position='right'>
					Comenzar
				</button>
			</div>
			
		</div>


		<div class="col-md-8 text-center " id="contenedor_examen">
			
			
		</div>

		
	</div>
</div>


<!-- FIN EXAMEN Y TIMER -->




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
	


<script type="text/javascript">
	var clock;
	var duracion = <?php echo $dur_exa; ?>;
		
	$(document).ready(function() {
		
		clock = $('#clock').FlipClock(duracion*60, {
	        clockFace: 'MinuteCounter',
	        countdown: true,
	        autoStart: false,
	        language:'es-es',
	        callbacks: {
	        	start: function() {
	        		$('.message').html('The clock has started!');
	        	},
	        	stop: function() {
	        		 window.location.reload();
	        	}
	        }
	    });

	    $('#btn_comenzar').click(function(e) {
	    	e.preventDefault();
	    	swal({
			  title: "¿Seguro que deseas comenzar el examen?",
			  text: "¡Una vez confirmado, comenzará a correr el tiempo!",
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
			}).then((confirmacion) => {
			  if (confirmacion) {
			    //CODIGO A REALIZAR
			    clock.start();
			    $('#btn_comenzar').html('Finalizar').removeAttr('id').attr({"id": "btn_finalizar"});

		    	var examen = <?php echo $id_exa; ?>;
		    	var examenCopia = <?php echo $id_exa_cop; ?>;
		    	var id_pro = <?php echo $id_pro; ?>;
		    	$.ajax({
		    		url: 'server/obtener_examen.php?id_alu_ram=<?php echo $id_alu_ram; ?>',
		    		type: 'POST',
		    		data: {examen, examenCopia, id_pro},
		    		success: function(respuesta){


		    			$("#contenedor_examen").append(respuesta);

		    			$(".respuesta").on('change', function(event) {
							event.preventDefault();
							/* Act on the event */
							var respuesta =  $(this).attr("respuesta");
							console.log(respuesta);

							$.ajax({
								url: 'server/editar_examen.php?id_alu_ram=<?php echo $id_alu_ram; ?>&id_exa_cop=<?php echo $id_exa_cop; ?>',
								type: 'POST',
								data: {respuesta},
								success: function(respuesta){
									console.log(respuesta);

								}		    				
							});
							


						});

		    // 			//STICKY EFFECT
						// $(".sticky").sticky({
						// 	topSpacing: 90, 
						// 	zIndex: 2, 
						// 	stopper: "#stop",
						// 	zoom: 0.5
						// });
						$("#btn_finalizar").on('click', function(event) {
							event.preventDefault();
							/* Act on the event */

							swal({
							  title: "¿Seguro que deseas finalizar el examen?",
							  text: "¡Una vez confirmado, NO podrás hacer cambios después!",
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
							}).then((confirmacion) => {
							  if (confirmacion) {
							    //CODIGO A REALIZAR
							    window.location.reload();
							    
							  }
							});
							
						});
						
		    			
		    		}
		    	});
			    
			  }
			});
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
		var actividad = "<?php echo $nom_blo." - ".$nom_exa; ?>";
		var mensaje = "<b>"+actividad+"</b><br>"+$(this).val();
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
<?php
	}
?>