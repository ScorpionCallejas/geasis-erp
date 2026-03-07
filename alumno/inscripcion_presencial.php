<?php  

	include('inc/header.php');
	$id_alu_ram = $_GET['id_alu_ram'];

	$sql = "
		SELECT *
		FROM alu_ram
		INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
		WHERE id_alu_ram = '$id_alu_ram'
	";

	$resultado = mysqli_query( $db, $sql );

	$fila = mysqli_fetch_assoc( $resultado );

	$id_ram = $fila['id_ram'];
	


?>
<!-- CONTENIDO -->
<!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Ramas"><i class="fas fa-bookmark"></i> Inscrpción</span>
		<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a class="text-white" href="ramas.php" title="Vuelve a Ramas">Ramas</a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Inscrpción</a>
		</div>		
	</div>
</div>
<!-- FIN TITULO -->


<!-- ROW TABLA -->
<br>
	<ul class="stepper horizontal horizontal-fix text-center" id="horizontal-stepper-fix" >
	  <!-- PASO 1 -->
	  <li class="step active">
	    <div class="step-title waves-effect waves-dark" title="Selección de Ciclo Escolar">Paso 1</div>
	    <div class="step-new-content ">
	      <div class="row">
			<div class="col-md-4"></div>
	      	<div class="col-md-4 text-center">
	      		<!-- Jumbotron -->
	      		<div class="jumbotron text-center grey lighten-1">
					<h2 class="card-title h2 white-text">Selecciona un ciclo escolar</h2>
					<hr class="my-4 pb-2">
					<?php 
						$i = 1;
						$fechaHoy = date( 'Y-m-d' );

						$sqlConsultaCiclos = "
					    	SELECT * 
					    	FROM ciclo
					    	INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
					    	WHERE id_ram = '$id_ram' AND fin_cic > '$fechaHoy'
					    	ORDER BY id_cic DESC
					    ";

					    //echo $sqlConsultaCiclos;
					    $resultadoConsultaCiclos = mysqli_query($db, $sqlConsultaCiclos);	
						while($filaCiclos = mysqli_fetch_assoc($resultadoConsultaCiclos)){

					?>	

						<div class="card grey lighten-1 mb-3 waves-effect ciclos next-step hoverable white-text" ciclo="<?php echo $filaCiclos['id_cic']; ?> " data-feedback="someFunction22" style="min-width: 20rem;">
						  <div class="card-header  grey darken-1">
						  	<?php echo $i; $i++;?> - <?php echo $filaCiclos['nom_cic']; ?>
						  </div>
						  <div class="card-body ">
						    <p class="card-text white-text">
						    	<small>
						    		<strong>Inscripción:</strong> <?php echo fechaFormateadaCompacta($filaCiclos['ins_cic']); ?><br>
						    		<strong>Inicia:</strong> <?php echo fechaFormateadaCompacta($filaCiclos['ini_cic']); ?><br>
						    		<strong>Corte:</strong> <?php echo fechaFormateadaCompacta($filaCiclos['cor_cic']); ?><br>
						    		<strong>Finaliza:</strong> <?php echo fechaFormateadaCompacta($filaCiclos['fin_cic']); ?>
								</small>
						    </p>
						  </div>
						</div>

		
					
						<hr class="my-4 pb-2">

					<?php
						} 

					?>
				

				</div>
				<!-- Fin Jumbotron -->
	       
	      	</div>
	      </div>
	    </div>
	  </li>


	  <!-- FIN PASO 1 -->


	  <!-- PASO 2 -->
	  <li class="step" id="paso2">
	    <div class="step-title waves-effect waves-dark" title="Selección de Horarios">Paso 2</div>
	    <div class="step-new-content" id="step2" >

      		<div class="row">
      			<div class="col-md-4" id="gruposCiclo">
      			</div>
      				<div class="col-md-8 text-center" id="horariosGrupo">
      		<!-- CONTENIDO DE TABLA CUANDO SELECCIONEN GRUPO -->
      				</div>
      		</div>
      		<div class="row">
	      		<div class="col-12 ml-auto">
	      			
	      			<!-- Jumbotron -->
	      				<div class="jumbotron text-center">
							<h2 class="card-title h2 grey-text animated"><i class="far fa-calendar-check fa-2x"></i> Mi horario</h2>
							<hr class="my-4 pb-2">
							<table class="table table-sm text-center table-hover animated fadeInDown" cellspacing="0" 		width="99%" id="horarioAlumno">
								<thead class="grey lighten-2">
									<tr>
							
										<th>Profesor</th>
										<th>Materia</th>
										<th>Lunes</th>
										<th>Martes</th>
										<th>Miercoles</th>
										<th>Jueves</th>
										<th>Viernes</th>
										<th>Sabado</th>
										<th>Domingo</th>
									</tr>
									<tbody id="panzaHorarioAlumno">

										
									</tbody>
								</thead>
							</table>

							<hr class="my-4 pb-2">
							<div id="contenedor_btn_siguiente">
								
							</div>
						</div>
				<!-- Fin Jumbotron -->
	      		</div>
      		</div>
	 
	    </div>
	  </li>
	  <!-- FIN PASO 2 -->



	  <!-- PASO 3 -->
	  <li class="step">
	    <div class="step-title waves-effect waves-dark" title="Confirmación de Horario">Paso 3</div>
	    <div class="step-new-content">
	      	<div class="row">
		      		<div class="col-12 ml-auto">
		      			
		      			<!-- Jumbotron -->
		      				<div class="jumbotron text-center">
								<h2 class="card-title h2 grey-text animated"><i class="far fa-calendar-check fa-2x"></i> Mi horario</h2>
								<hr class="my-4 pb-2">
								<table class="table table-sm text-center table-hover animated fadeInDown" cellspacing="0" 		width="99%" id="horarioAlumnoFinal">
									<thead class="grey lighten-2">
										<tr>
								
											<th>Profesor</th>
											<th>Materia</th>
											<th>Lunes</th>
											<th>Martes</th>
											<th>Miercoles</th>
											<th>Jueves</th>
											<th>Viernes</th>
											<th>Sabado</th>
											<th>Domingo</th>
										</tr>
										<tbody id="panzaHorarioAlumnoFinal">

											
										</tbody>
									</thead>
								</table>

								<hr class="my-4 pb-2">
								<div id="contenedor_btn_final">
							
									
								</div>
							</div>
					<!-- Fin Jumbotron -->
		      		</div>
	      	</div>
	    </div>
	  </li>
	  <!-- FIN PASO 3 -->
	</ul>
		
		


<!--  FIN ROW TABLA-->
<!-- FIN CONTENIDO -->

<div class="row">
	
</div>


<?php  

	include('inc/footer.php');

?>

	
<script>

	function panzaVacia(){
		var validador=true;
		if ($('#panzaHorarioAlumno').is(':empty')) {
			console.log("vacio");
			$("#contenedor_btn_siguiente").html("");
		}else{
			console.log("no vacio");
			$("#contenedor_btn_siguiente").html('<div class="card light-green accent-4 mb-3 waves-effect grupos hoverable white-text text-center" style="max-width: 20rem;"><div class="card-header light-green accent-4 next-step" data-feedback="someFunction22" id="btn_siguiente">Continuar</div></div>');

			
			$("#btn_siguiente").on('click', function(event) {
				event.preventDefault(); 
				$("#panzaHorarioAlumnoFinal").html($("#panzaHorarioAlumno").children().clone());
				$("#panzaHorarioAlumnoFinal .removerHorario").remove();
				$("#contenedor_btn_final").html('<a class="btn btn-lg btn-info white-text waves-effect" id="btn_finalizar"><strong>Finalizar</strong></a>');
				
				$("#btn_finalizar").on('click', function(event) {
					event.preventDefault();
					/* Act on the event */
					$("#btn_finalizar").attr('disabled','disabled');
					swal({
					  title: "Confirmación de horario",
					  text: "¡Una vez guardado no podrás hacer cambios!",
					  icon: "warning",
					  buttons: 	{
								  cancel: {
								    text: "Cancelar",
								    value: null,
								    visible: true,
								    closeModal: true,
								    className: "btn-danger waves-effect"
								  },
								  confirm: {
								    text: "Confirmar",
								    value: true,
								    visible: true,
								    closeModal: true,
								    className: "btn-info waves-effect"
								  }
								},
					  dangerMode: true,
					}).then((willDelete) => {
					  if (willDelete) {
					    //VALIDACION ACEPTADA
					    if(validador==true)
					    {
						    var sub_hor = [];
						    for(var i = 0; i < $("#panzaHorarioAlumnoFinal .filasHorario").length; i++){
						    	sub_hor[i] = $("#panzaHorarioAlumnoFinal .filasHorario").eq(i).attr("sub_hor");
						    	//console.log($(".filasHorario").eq(i).attr("sub_hor"));
						    }



						    $.ajax({
						    	url: 'server/agregar_horario.php?id_alu_ram=<?php echo $id_alu_ram; ?>',
						    	type: 'POST',
						    	data: {sub_hor},
						    	beforeSend: function(){

									$("#btn_finalizar").removeClass('btn-info').addClass('light-green accent-4').html('<i class="fas fa-cog fa-spin white-text"></i> <span>Cargando...</span>');
								},
						    	success: function(respuesta){
						    		console.log(respuesta);

						    		if (respuesta!= "Error") {
						    			$("#btn_finalizar").html('<i class="fas fa-check white-text"></i> <span>Inscripción Exitosa</span>');

							    		swal("Inscripción Exitosa", "Continuar", "success", {button: "Aceptar",}).
										then((value) => {
										  window.location = "horario.php?id_alu_ram="+respuesta;
										});
						    		}else{
						    			console.log("error");
						    		}
						    		
						    	}
						    });					    


						    //console.log("acepto");
					    }
					    
					  }else{

					  	console.log("no acepto");
					  }
					});
				});
			});
		}
	}
	
	$(".ciclos").on('click', function(event) {
		event.preventDefault();
		// /* Act on the event */
		$('.ciclos').removeClass('grey lighten-1');
		$('.ciclos').removeClass('light-green accent-4');
		$('.ciclos').addClass('grey lighten-1');
		$(this).removeClass('grey lighten-1');
		$(this).addClass('light-green accent-4');

		var ciclo = $(this).attr("ciclo");
		$("#panzaHorarioAlumno").html("");
		$("#contenedor_btn_siguiente").html("")
		$("#panzaHorarioAlumnoFinal").html("");
		$("#contenedor_btn_final").html("");
		;

		$.ajax({
			url: 'server/obtener_grupos_ciclo.php',
			type: 'POST',
			data: {ciclo},
			success: function(respuesta){
				//console.log(respuesta);
				$("#gruposCiclo").html(respuesta);

				 setTimeout(function(){
				 	$("#horizontal-stepper-fix").css({
				 	height: '2000'
				 });
				 }, 1000);

				$('.grupos').on('click', function(event) {
					event.preventDefault();
					/* Act on the event */
					//console.log($(this).attr("grupo"));

					$('.grupos').children().removeClass('grey darken-1');
					$('.grupos').children().removeClass('light-green accent-4');
					$('.grupos').children().addClass('grey darken-1');
					$(this).children().removeClass('grey darken-1');
					$(this).children().addClass('light-green accent-4');
					var grupo = $(this).attr("grupo");

					$.ajax({
						url: 'server/obtener_horarios_grupo.php',
						type: 'POST',
						data: {grupo},
						beforeSend: function(){
							$("#horariosGrupo").html('<div id="overlay" style="height:100%; width:100%; background:rgba(f, f, f); position:fixed; left:0; top:0;"><div class="spinner"></div></div>');
						},
						success: function(respuesta){
							//console.log();
							$("#horariosGrupo").html(respuesta);

							$('.sub_hor').on('click', function(event) {
								event.preventDefault();
								/* Act on the event */

								$(this).removeClass('btn-info').addClass('btn-danger removerHorario');
								$(this).children().removeClass('fas fa-plus-circle fa-2x').addClass('fas fa-times-circle fa-2x').removeAttr().attr({title: "Quita este horario"});
								$("#panzaHorarioAlumno").append($(this).parent().parent());

								$('.removerHorario').on('click', function(event) {
									event.preventDefault();
									/* Act on the event */

									$(this).parent().parent().remove();

									panzaVacia();

								});

								panzaVacia();

							});
						}
					});
					
				});

			}
		})
		

	});

	$(document).ready(function () {
		$('.stepper').mdbStepper();
		
		//EVENTO QUE EXPANDE EL HEIGHT DEL SEGUNDO PASO
		$("#paso2").on('click', function(event) {
			event.preventDefault();
			/* Act on the event */

			setTimeout(function(){
			 	$("#horizontal-stepper-fix").css({
				 	height: '2000'
				 });
			}, 1000);
		});
	});

	function someFunction22() {
		setTimeout(function () {
			$('#horizontal-stepper-fix').nextStep();
		}, 2000);
	}
</script>