<?php  

	include('inc/header.php');
	

	$id_ent_cop = $_GET['id_ent_cop'];

	//VALIDACION DE ALUMNO DE LA CARRERA
	//PREGUNTANDO SI EL ID_ALU_RAM ESTA ASOCIADO AL ID DEL ALUMNO AL INICIO DE SESION EN CABECERAS Y ADICIONALMENTE EL BLOQUE VINCULADO A LA MATERIA DEL BLOQUE 

	//***PENDIENTE DE VERIFICACION
	$sqlValidacion = "
		SELECT *
        FROM entregable_copia

        INNER JOIN sub_hor ON sub_hor.id_sub_hor = entregable_copia.id_sub_hor3     
       	INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
        INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5
        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
        INNER JOIN rama ON rama.id_ram = materia.id_ram2
        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1

        WHERE id_pro1 = '$id' AND id_ent_cop = '$id_ent_cop'
	";

	$resultadoValidacion = mysqli_query($db, $sqlValidacion);

	// echo $sqlValidacion;
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
	$nom_gru = $filaValidacion['nom_gru'];
	$img_blo = $filaValidacion['img_blo'];
	$id_blo = $filaValidacion['id_blo'];

	$nom_ent= $filaValidacion['nom_ent'];
	$id_mat = $filaValidacion['id_mat'];
	$id_ram = $filaValidacion['id_ram'];
	$id_ent = $filaValidacion['id_ent'];

	$des_ent = $filaValidacion['des_ent'];
	$pun_ent = $filaValidacion['pun_ent'];
	$ini_ent_cop = $filaValidacion['ini_ent_cop'];
	$fin_ent_cop = $filaValidacion['fin_ent_cop'];

	$id_sub_hor = $filaValidacion['id_sub_hor'];


	$id_ent_cop = $filaValidacion['id_ent_cop'];


	//$fechaHoy = date('Y-m-d');

	// VALIDACION DE FECHAS 
	// if ($fechaHoy < $ini_ent_cop || $fechaHoy > $fin_ent_cop) {
	// 	header("location: not_found_404_page.php");
	// }
	
?>






<!-- TITULO -->
<div id="contenedor_fondo_clase" class="row  p-4 clasePadre" style="border-radius: 20px;
	background-image: url('../fondos_clase/<?php echo $img_blo; ?>'); height: 200px; background-position: center; background-repeat: no-repeat; background-size: 100% 100%;   background-attachment: scroll; 

">

	
	<div class="col text-left">
        <span class="tituloPagina animated fadeInUp  badge blue-grey darken-4 hoverable" title="Ramas"><i class="fas fa-bookmark"></i> Tarea: <?php echo $nom_ent; ?></span>
        <br>
        <br>


        <span class="subtituloPagina animated fadeInUp  badge blue-grey darken-4 hoverable" title="Bloque">
            <i class="fas fa-certificate"></i>
            Clase: <?php echo $nom_blo; ?>
        </span>
		
		<br>
		<br>

        <div class=" badge badge-warning animated fadeInUp  text-white">
            <a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
            <i class="fas fa-angle-double-right"></i>
            
			<a class="text-white" href="clases_materia.php?id_sub_hor=<?php echo $id_sub_hor; ?>" title="Vuelve a clases">Clases</a>

			
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="clase_contenido.php?id_sub_hor=<?php echo $id_sub_hor; ?>&id_blo=<?php echo cifradoServer( $id_blo, 'geasis' ); ?>">
				<span class="text-white"><?php echo $nom_blo; ?></span>
				
			</a>

            <i class="fas fa-angle-double-right"></i>
            <a style="color: black;" href="" title="Estás aquí">Tarea</a>


        </div>
    </div>
    
    <div class="col text-right">
        <span class="subtituloPagina animated fadeInUp  badge blue-grey darken-4 hoverable" title="Carrera">
            <i class="fas fa-certificate"></i>
            Programa: <?php echo $nom_ram; ?>
        </span>
            <br>
            <br>

        <span class="subtituloPagina animated fadeInUp  badge blue-grey darken-4 hoverable" title="Grupo">
            <i class="fas fa-certificate"></i>
            Grupo: <?php echo $nom_gru; ?>
        </span>

        <br>
        <br>

        <span class="subtituloPagina animated fadeInUp  badge blue-grey darken-4 hoverable" title="Materia">
            <i class="fas fa-certificate"></i>
            Materia: <?php echo $nom_mat; ?>
        </span> 
        
        
    </div>
	
</div>
<br>
<!-- FIN TITULO -->



<!-- DETALLES DEL ENTREGABLE -->
<div class="jumbotron grey lighten-1">
	<div class="row">
		<div class="col-md-4">
			<div class="jumbotron bg-light mb-3" style="max-width: 20rem;">
					<h4 class="h4 text-center" title="Detalles de la actividad">
						Detalles
					</h4>

					<hr>


					<table class="table table-hover">
						

						<tbody>
							<tr>
								<td class="letraPequena">
									<span>
										<h6 class="h6">
											Puntos: 
										</h6>
									</span>
								</td>

								<td class="letraPequena">
									<h5>
										<span class="badge badge-info">
											<?php echo $pun_ent; ?>
										</span>
									</h5>
								</td>
							</tr>


							<tr>
								<td class="letraPequena">
									<span>
										<h6 class="h6">
											Inicio: 
										</h6>
									</span>
									
								</td>

								<td class="letraPequena">
									<h5>
										<span class="badge badge-info">
											<?php echo fechaFormateadaCompacta($ini_ent_cop); ?>
										</span>
									</h5>
								</td>
							</tr>

							<tr>
								<td class="letraPequena">
									<span>
										<h6 class="h6">
											Fin: 
										</h6>
									</span>
								</td>

								<td class="letraPequena">
									<h5>
										<span class="badge badge-info">
											<?php echo fechaFormateadaCompacta($fin_ent_cop); ?>
										</span>
									</h5>
									
								</td>
							</tr>
						</tbody>
					</table>					
				
	
			</div>	
		</div>


		<!-- CONTENIDO DE ACTIVIDAD -->
		<div class="col-md-8">
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
	
<!-- LISTADO DE TAREAS TOTALES -->
<div class="jumbotron mdb-color  grey lighten-4  black-text mx-2 mb-5" id="contenedor_tareas">


	




</div>
	
	
<!-- FIN LISTADO DE TAREAS TOTALES -->



	<!-- CALIFICACION MODAL -->
    <!-- Side Modal Bottom Right Success-->
    <div class="modal fade" id="sideModalBRSuccess" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" >
	    <div class="modal-dialog  modal-notify modal-info modal-lg" role="document">
	        <!--Content-->
	        <div class="modal-content " id="tamanoModal" >

	            

	            

	            <!--Header-->
	            <div class="modal-header" title="Puedes mover este elemento arrastrándolo">
	                <p class="heading lead">Calificaciones para: <?php echo $nom_ent; ?></p>

	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <span aria-hidden="true" class="white-text">&times;</span>
	                </button>
	            </div>


                <!--Body-->
                <div class="modal-body" id="contenedorModal">
                        
                        
                </div>
                <!-- Fin Body -->

                <!--Footer-->
                <div class="modal-footer justify-content-center">

                    
                </div>


	            
	        </div>
	        <!--/.Content-->
	    </div>
	</div>
	<!-- Side Modal Bottom Right Success-->

	<!-- FIN CALIFICACION MODAL -->
	
	

	<!-- FLOATING BUTTON -->
	<a type="button" class="btn-floating btn-lg  flotante btn-info" data-target="#sideModalBRSuccess" title="Haz click para calificar a tus alumnos" id="btn_modal">
	    <i class="fas fa-award"></i>
	</a>
	<!-- FIN FLOATING BUTTON -->


<?php  

	include('inc/footer.php');

?>


<script>

    $("#sideModalBRSuccess").draggable();
</script>



<script>
	obtenerTareas();
	


    //MODAL
    // OBTENER ALUMNOS ACTIVIDAD

    $('#btn_modal').on('click', function(event) {
        event.preventDefault();
        $('#sideModalBRSuccess').modal('show');

        var id_ent_cop = <?php echo $id_ent_cop; ?>;
        $.ajax({
            url: 'server/obtener_alumnos_entregable.php',
            type: 'POST',
            data: {id_ent_cop},
            success: function(respuesta){
                
                $("#contenedorModal").html(respuesta);
                //console.log(respuesta);
                
            }
        });
    });



    function obtenerTareas(){
    	//TAABLA DE TAREAS
		var id_ent_cop = <?php echo $id_ent_cop; ?>;

		$.ajax({
			url: 'server/obtener_tareas_alumnos.php',
			type: 'POST',
			data: {id_ent_cop},
			success: function(respuesta){
				$("#contenedor_tareas").html(respuesta);

				<?php  
					if ( isset( $_GET['id_alu_ram'] ) && isset( $_GET['tipo'] ) ) {
						$id_alu_ram = $_GET['id_alu_ram'];
						$id_ent_cop = $_GET['id_ent_cop'];

						$sqlValidacion2 = "
							SELECT *
							FROM cal_act
							WHERE id_alu_ram4 = '$id_alu_ram' AND id_ent_cop2 = '$id_ent_cop' AND pun_cal_act IS NULL
						";

						//echo $sqlValidacion2;

						$resultadoValidacion2 = mysqli_query( $db, $sqlValidacion2 );

						if ( $resultadoValidacion2 ) {
							$validacion2 = mysqli_num_rows( $resultadoValidacion2 );

							// echo $validacion2;

							if ( $validacion2 == 1 ) {
				?>
								var elemento = $('#<?php echo $_GET['tipo'].$_GET['id_alu_ram']; ?>'); 
								$('html, body').animate({
				                    scrollTop: elemento.offset().top-90
				                }, 1000);
								elemento.addClass('animated pulse delay-1s light-green accent-1');

								// setTimeout(function(){
								// 	elemento.removeClass('light-green accent-1');
								// }, 5000);

				<?php
							}

						} else {
							echo $sqlValidacion2;
						}
				?>

					

				<?php
					}

				?>
			}
		});
    }

    
    
</script>