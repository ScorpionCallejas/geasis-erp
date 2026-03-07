<?php  
	//ARCHIVO VIA AJAX PARA OBTENER DATOS DE EXAMEN
	//clase_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

    $id_exa_cop = $_POST['id_exa_cop'];

    //VALIDACION DE ALUMNO DE LA CARRERA
    //PREGUNTANDO SI EL ID_ALU_RAM ESTA ASOCIADO AL ID DEL ALUMNO AL INICIO DE SESION EN CABECERAS Y ADICIONALMENTE EL BLOQUE VINCULADO A LA MATERIA DEL BLOQUE 

    //***PENDIENTE DE VERIFICACION
    $sqlValidacion = "
        SELECT *
        FROM examen_copia

        INNER JOIN sub_hor ON sub_hor.id_sub_hor = examen_copia.id_sub_hor4     
        INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
        INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
        INNER JOIN rama ON rama.id_ram = materia.id_ram2
        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1

        WHERE id_pro1 = '$id' AND id_exa_cop = '$id_exa_cop'
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
    $nom_gru = $filaValidacion['nom_gru'];
    $img_blo = $filaValidacion['img_blo'];
    $id_blo = $filaValidacion['id_blo'];

    $nom_exa= $filaValidacion['nom_exa'];
    $id_mat = $filaValidacion['id_mat'];
    $id_ram = $filaValidacion['id_ram'];
    $id_exa = $filaValidacion['id_exa'];

    $des_exa = $filaValidacion['des_exa'];
    $pun_exa = $filaValidacion['pun_exa'];
    $ini_exa_cop = $filaValidacion['ini_exa_cop'];
    $fin_exa_cop = $filaValidacion['fin_exa_cop'];

    $id_sub_hor = $filaValidacion['id_sub_hor'];


    $id_exa_cop = $filaValidacion['id_exa_cop'];

    $dur_exa = $filaValidacion['dur_exa'];

    //$fechaHoy = date('Y-m-d');

    // VALIDACION DE FECHAS 
    // if ($fechaHoy < $ini_exa_cop || $fechaHoy > $fin_exa_cop) {
    //  header("location: not_found_404_page.php");
    // }
    
?>
	


	<!--Modal cascading tabs-->
	<div class="modal-c-tabs">

		<!-- Nav tabs -->
		<ul class="nav md-pills nav-justified pills-info" role="tablist">
			<li class="nav-item">
				<a class="nav-link active font-weight-normal waves-effect white btn-block btn-sm btn-rounded border" data-toggle="tab" href="#contenedor_actividad_examen" role="tab" id="btn_modal_actividad_examen">
					Actividad
				</a>
			</li>
			
			<li class="nav-item">
				<a class="nav-link  font-weight-normal waves-effect white btn-block btn-sm btn-rounded border" data-toggle="tab" href="#contenedor_alumnos_actividad_examen" role="tab" id="btn_modal_alumnos_examen">
					Calificar alumnos
				</a>
			</li>
		
		</ul>

		<!-- Tab panels -->
		<div class="tab-content pt-3">
			<!--Panel 1-->
			<div class="tab-pane fade in show active" id="contenedor_actividad_examen" role="tabpanel">
				<!-- ACTIVIDAD -->

				
				<!-- FIN ACTIVIDAD -->
			</div>
			<!--/.Panel 1-->

			<!--Panel 2-->
			<div class="tab-pane fade" id="contenedor_alumnos_actividad_examen" role="tabpanel">
				<!-- ALUMNOS -->


				<!-- FIN ALUMNOS -->
			</div>
			<!--/.Panel 2-->
		</div>

	</div>

    <!-- DATOS ACTIVIDAD -->


<script>
	obtener_actividad_examen();

	function obtener_actividad_examen(){
		var id_exa_cop = <?php echo $id_exa_cop; ?>;

		$.ajax({
	      	url: 'server/obtener_actividad_examen.php',
	      	type: 'POST',
	      	data: { id_exa_cop },
	      	success: function ( respuesta ) {
	        	console.log( respuesta );
	        
	        	$( '#contenedor_actividad_examen' ).html( respuesta );

	      	}

	    });

	}


	function obtener_alumnos_examen(){

		var id_exa_cop = <?php echo $id_exa_cop; ?>;
        $.ajax({
            url: 'server/obtener_alumnos_examen.php',
            type: 'POST',
            data: {id_exa_cop},
            success: function(respuesta){
                $("#contenedor_alumnos_actividad_examen").html(respuesta);
                //console.log(respuesta);

       
            }
        });
	}

    //MODAL
    // OBTENER ALUMNOS ACTIVIDAD

    $('#btn_modal_alumnos_examen').on('click', function(event) {
        event.preventDefault();

        obtener_alumnos_examen();
        
    });


    $('#btn_modal_actividad_examen').on('click', function(event) {
        event.preventDefault();

        obtener_actividad_examen();
        
    });



    
    
</script>