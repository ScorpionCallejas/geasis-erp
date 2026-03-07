<?php  
	//ARCHIVO VIA AJAX PARA OBTENER DATOS DE EXAMEN
	//clase_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

    $id_for_cop = $_POST['id_for_cop'];

    //VALIDACION DE ALUMNO DE LA CARRERA
    //PREGUNTANDO SI EL ID_ALU_RAM ESTA ASOCIADO AL ID DEL ALUMNO AL INICIO DE SESION EN CABECERAS Y ADICIONALMENTE EL BLOQUE VINCULADO A LA MATERIA DEL BLOQUE 

    //***PENDIENTE DE VERIFICACION
    $sqlValidacion = "
        SELECT *
        FROM foro_copia

        INNER JOIN sub_hor ON sub_hor.id_sub_hor = foro_copia.id_sub_hor2     
        INNER JOIN foro ON foro.id_for = foro_copia.id_for1
        INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
        INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
        INNER JOIN rama ON rama.id_ram = materia.id_ram2
        INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1

        WHERE id_pro1 = '$id' AND id_for_cop = '$id_for_cop'
    ";

    $resultadoValidacion = mysqli_query($db, $sqlValidacion);

    // echo $sqlValidacion;
    $totalValidacion = mysqli_num_rows($resultadoValidacion);

    
 
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

    $nom_for= $filaValidacion['nom_for'];
    $id_mat = $filaValidacion['id_mat'];
    $id_ram = $filaValidacion['id_ram'];
    $id_for = $filaValidacion['id_for'];

    $des_for = $filaValidacion['des_for'];
    $pun_for = $filaValidacion['pun_for'];
    $ini_for_cop = $filaValidacion['ini_for_cop'];
    $fin_for_cop = $filaValidacion['fin_for_cop'];

    $id_sub_hor = $filaValidacion['id_sub_hor'];


    $id_for_cop = $filaValidacion['id_for_cop'];


    //$fechaHoy = date('Y-m-d');

    // VALIDACION DE FECHAS 
    // if ($fechaHoy < $ini_for_cop || $fechaHoy > $fin_for_cop) {
    //  header("location: not_found_404_page.php");
    // }
    
?>
	


	<!--Modal cascading tabs-->
	<div class="modal-c-tabs">

		<!-- Nav tabs -->
		<ul class="nav md-pills nav-justified pills-info" role="tablist">
			<li class="nav-item">
				<a class="nav-link active font-weight-normal waves-effect white btn-block btn-sm btn-rounded border" data-toggle="tab" href="#contenedor_actividad_foro" role="tab" id="btn_modal_actividad_foro">
					Actividad
				</a>
			</li>
			
			<li class="nav-item">
				<a class="nav-link  font-weight-normal waves-effect white btn-block btn-sm btn-rounded border" data-toggle="tab" href="#contenedor_alumnos_actividad_foro" role="tab" id="btn_modal_alumnos_foro">
					Calificar alumnos
				</a>
			</li>
		
		</ul>

		<!-- Tab panels -->
		<div class="tab-content pt-3">
			<!--Panel 1-->
			<div class="tab-pane fade in show active" id="contenedor_actividad_foro" role="tabpanel">
				<!-- ACTIVIDAD -->

				
				<!-- FIN ACTIVIDAD -->
			</div>
			<!--/.Panel 1-->

			<!--Panel 2-->
			<div class="tab-pane fade" id="contenedor_alumnos_actividad_foro" role="tabpanel">
				<!-- ALUMNOS -->


				<!-- FIN ALUMNOS -->
			</div>
			<!--/.Panel 2-->
		</div>

	</div>

    <!-- DATOS ACTIVIDAD -->


<script>
	obtener_actividad_foro();

	function obtener_actividad_foro(){
		var id_for_cop = <?php echo $id_for_cop; ?>;

		$.ajax({
	      	url: 'server/obtener_actividad_foro.php',
	      	type: 'POST',
	      	data: { id_for_cop },
	      	success: function ( respuesta ) {
	        	console.log( respuesta );
	        
	        	$( '#contenedor_actividad_foro' ).html( respuesta );

	      	}

	    });

	}


	function obtener_alumnos_foro(){

		var id_for_cop = <?php echo $id_for_cop; ?>;
        $.ajax({
            url: 'server/obtener_alumnos_foro.php',
            type: 'POST',
            data: {id_for_cop},
            success: function(respuesta){
                $("#contenedor_alumnos_actividad_foro").html(respuesta);
                //// console.log(respuesta);

       
            }
        });
	}

    //MODAL
    // OBTENER ALUMNOS ACTIVIDAD

    $('#btn_modal_alumnos_foro').on('click', function(event) {
        event.preventDefault();

        obtener_alumnos_foro();
        
    });


    $('#btn_modal_actividad_foro').on('click', function(event) {
        event.preventDefault();

        obtener_actividad_foro();
        
    });



    
    
</script>