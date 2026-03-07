<?php  
		

 	include('inc/header.php');

	$sqlHorario = "
		SELECT *
		FROM sub_hor
		INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
		INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
		INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
		INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
		INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
        WHERE id_pro = '$id' AND est_sub_hor = 'Activo'
		GROUP BY id_sub_hor
		ORDER BY id_mat DESC
	";


	$resultadoHorario = mysqli_query($db, $sqlHorario);
	$resultadoValidacion = mysqli_query($db, $sqlHorario);
	$filaRama = mysqli_fetch_assoc($resultadoValidacion);
	$rama = $filaRama['nom_ram'];

	//echo $sqlHorario;
	
	
?>
<!-- CONTENIDO -->

<!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Materias">
			<i class="fas fa-bookmark"></i> 
			Materias
		</span><br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Materias</a>
		</div>
	</div>
	
</div>
<!-- FIN TITULO -->
	










	<!-- ROW 1 -->
	<div class="row">
		<div class="col-md-3">
			<!-- Jumbotron -->
			<div class="jumbotron text-center grey lighten-1">
				<div class="container">
					
					<hr class="my-4 pb-2">

					<div class="card   grey lighten-1 mb-3 waves-effect hoverable white-text materias" style="max-width: 20rem;" materia="Actividades">
						<div class="card-header  grey darken-1" title="Todas las actividades pertenecientes a tus materias de horario">
							Actividades
						</div>
					 
					</div>


					<div class="card   grey lighten-1 mb-3 waves-effect hoverable white-text materias" style="max-width: 20rem;" materia="Diagrama">
						<div class="card-header  grey darken-1" title="Carga de Actividades">
							Diagrama de Gantt
						</div>
					 
					</div>
					
					


					
					<hr class="my-4 pb-2">
					
					<?php
			    
						$i = 1;

						while($filaMaterias = mysqli_fetch_assoc($resultadoHorario)){

							$id_sub_hor = $filaMaterias['id_sub_hor'];
							$sqlTotalAlumnos = "

								SELECT *
								FROM alu_hor 
								INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
								INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
								INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
								INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
								WHERE id_sub_hor = '$id_sub_hor'

							";

							$resultadoTotalAlumnos = mysqli_query($db, $sqlTotalAlumnos);


							$totalAlumnos = mysqli_num_rows($resultadoTotalAlumnos);

								
							echo '


								<div class="card   grey lighten-1 mb-3 waves-effect materias hoverable white-text" materia="'.$filaMaterias["id_mat"].'" style="max-width: 20rem;" title="'.$filaMaterias['nom_cic'].'" id_sub_hor="'.$filaMaterias['id_sub_hor'].'">
									<div class="card-header  grey darken-1">
										'.$i.' - '.$filaMaterias["nom_mat"]."<br><span class='text-white' style='font-size: 10px;'>".$filaMaterias['nom_gru'].'</span>
										<br>
										<span class="white-text" style="font-size: 10px;">
											'.$totalAlumnos.' alumnos
										</span>
									</div>
								 
								</div>
							';

							$i++;
						}

					?>

					<h4 class="mb-2 h4 white-text" title="Materias que tienes en tu horario">Mis materias</h4>


					


				</div>
			</div>


			

		</div>

		<div class="col-md-9">
			<!-- Jumbotron -->
			<div class="jumbotron mdb-color  grey lighten-4  black-text mx-2 mb-5"  id="fila1Col2">
				<br>



				<svg id="gantt" class=" animated fadeInDown"></svg>
				
				

			</div>
			<!-- Jumbotron -->
		</div>
	</div>
	<!-- FIN ROW 1 -->


	<!-- ROW 2 -->
	<div class="row">

		<div class="col-md-3">
			<!-- Jumbotron -->
			<div class="jumbotron text-center grey lighten-4 text-center" id="fila2Col1">
				
			</div>
			<!-- Fin Jumbotron  -->
		</div>


		<div class="col-md-9">
			<!-- Jumbotron -->
			<div class="jumbotron text-center grey lighten-4" id="fila2Col2">
				
			</div>
			<!-- Fin Jumbotron -->
		</div>
		
	</div>
	<!-- FIN ROW 2 -->




<!-- FIN CONTENIDO -->
<?php

  include('inc/footer.php');

?>


<script>
	
	$(".materias").on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		$('.materias').children().removeClass('grey darken-1');
		$('.materias').children().removeClass('light-green accent-4');
		$('.materias').children().addClass('grey darken-1');
		$(this).children().removeClass('grey darken-1');
		$(this).children().addClass('light-green accent-4');


		var materia = $(this).attr("materia");
		var id_sub_hor = $(this).attr("id_sub_hor");


		if (materia == 'Actividades') {
			$.ajax({
				url: 'server/obtener_actividades_materia.php?id_pro=<?php echo $id; ?>',
				type: 'POST',
				data: {materia},
				success: function(respuesta){
					//console.log(respuesta);
					$("#fila1Col2").html(respuesta);
					
				}
			});

		}else if (materia == 'Diagrama') {
			$.ajax({
				url: 'server/listar_actividades.php?id_pro=<?php echo $id; ?>',
				type: 'POST',
				success: function(respuesta){
					//console.log(respuesta);

					$("#fila1Col2").html('<br><svg id="gantt" class=" animated fadeInDown"></svg>');
					var tasks = respuesta;
					var gantt = new Gantt("#gantt", tasks);

					gantt.change_view_mode('Week');
				}
			});

		}else{
			$.ajax({
				// url: 'server/obtener_bloques_materia.php',
				url: 'server/obtener_seleccion_materia.php',
				type: 'POST',
				data: {id_sub_hor},
				success: function(respuesta){
					//console.log(materia);
					$("#fila1Col2").html(respuesta);
					$("#fila2Col2").html("");
					
				}
			});


			$.ajax({
				url: 'server/obtener_alumnos_materia.php',
				type: 'POST',
				data: {id_sub_hor},
				success: function(respuesta){
					//console.log(materia);
					$("#fila2Col1").html(respuesta);

					// FUNCIONAMIENTO DE FILA DOS
					$(".selectoresElemento").on('click', function(event) {
						event.preventDefault();
						// /* Act on the event */
						$('.selectoresElemento').children().removeClass('grey darken-1');
						$('.selectoresElemento').children().removeClass('light-green accent-4');
						$('.selectoresElemento').children().addClass('grey darken-1');
						$(this).children().removeClass('grey darken-1');
						$(this).children().addClass('light-green accent-4');


						// SE PUEDE HACER CLICK EN ALGUN ALUMNO O EN TODAS LAS ACTIVIDADES
						var id_alu_ram = $(this).attr("id_alu_ram");

						if (id_alu_ram == 'alumnosMateria') {
							//CARGA A TODOS LOS ALUMNOS QUE TOMAN LA ASIGNATURA PARA PODER EVALUARLOS
							$.ajax({
								url: 'server/obtener_calificaciones_alumnos_materia.php',
								type: 'POST',
								data: {materia, id_sub_hor},
								success: function(respuesta3){
									//console.log(respuesta1);
									$("#fila1Col2").html(respuesta3);
								}
							});


						}else{
							//console.log("id_alu_ram");
							var id_alu_ram = $(this).attr("id_alu_ram");
							
							$.ajax({
								url: 'server/obtener_actividades_alumno_materia.php',
								type: 'POST',
								data: {id_sub_hor, id_alu_ram},
								success: function(respuesta1){
									//console.log(respuesta1);
									$("#fila2Col2").html(respuesta1);
								}
							});


							$.ajax({
								url: 'server/obtener_calificaciones_alumno_materia.php',
								type: 'POST',
								data: {id_alu_ram, materia},
								success: function(respuesta2){
									//console.log(respuesta2);
									$("#fila1Col2").html(respuesta2);
									
									$("#formularioCalificacion").on('submit', function(event) {
										event.preventDefault();

										$.ajax({
								                                        
								            url: 'server/editar_evaluaciones_alumnos_materia.php',
								            type: 'POST',
								            data: new FormData(formularioCalificacion),
								            processData: false,
								            contentType: false,
								            cache: false,
								            success: function(respuesta){
								                console.log(respuesta);

								                if (respuesta == 'Exito') {
								                    swal("Guardado correctamente", "Continuar", "success", {button: "Aceptar",});
								                }
								            }
								        });								

									});
								}
							});
						}
						
					});
					
				}
			});
		}
		
	});
</script>


<script>
	$.ajax({
		url: 'server/listar_actividades.php?id_pro=<?php echo $id; ?>',
		type: 'POST',
		success: function(respuesta){
			//console.log(respuesta);

			
			var tasks = respuesta;
			var gantt = new Gantt("#gantt", tasks);



			gantt.change_view_mode('Week');
		}
	});


</script>