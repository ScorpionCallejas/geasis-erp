<?php  
	//ARCHIVO VIA AJAX PARA OBTENER DATOS DE EXAMEN
	//clase_contenido.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

    $id_ent_cop = $_POST['id_ent_cop'];

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
    //  header("location: not_found_404_page.php");
    // }
    
    
?>
	


	<!--Modal cascading tabs-->
	<div class="modal-c-tabs">

		<!-- Nav tabs -->
		<ul class="nav md-pills nav-justified pills-info" role="tablist">
			<li class="nav-item">
				<a class="nav-link active font-weight-normal waves-effect white btn-block btn-sm btn-rounded border" data-toggle="tab" href="#contenedor_actividad_entregable" role="tab" id="btn_modal_actividad_entregable">
					Actividad
				</a>
			</li>
			
			<li class="nav-item">
				<a class="nav-link  font-weight-normal waves-effect white btn-block btn-sm btn-rounded border" data-toggle="tab" href="#contenedor_alumnos_actividad_entregable" role="tab" id="btn_modal_alumnos_entregable">
					Calificar alumnos
				</a>
			</li>
		
		</ul>

		<!-- Tab panels -->
		<div class="tab-content pt-3">
			<!--Panel 1-->
			<div class="tab-pane fade in show active" id="contenedor_actividad_entregable" role="tabpanel">
				<!-- ACTIVIDAD -->

				
				<!-- FIN ACTIVIDAD -->
			</div>
			<!--/.Panel 1-->

			<!--Panel 2-->
			<div class="tab-pane fade" id="contenedor_alumnos_actividad_entregable" role="tabpanel">
				<!-- ALUMNOS -->


				<!-- FIN ALUMNOS -->
			</div>
			<!--/.Panel 2-->
		</div>

	</div>

    <!-- DATOS ACTIVIDAD -->


<script>
	obtener_actividad_entregable();

	function obtener_actividad_entregable(){
		var id_ent_cop = <?php echo $id_ent_cop; ?>;

		$.ajax({
	      	url: 'server/obtener_actividad_entregable.php',
	      	type: 'POST',
	      	data: { id_ent_cop },
	      	success: function ( respuesta ) {
	        	// console.log( respuesta );
	        
	        	$( '#contenedor_actividad_entregable' ).html( respuesta );

	      	}

	    });

	}


	function obtener_alumnos_entregable(){

		var id_ent_cop = <?php echo $id_ent_cop; ?>;
        $.ajax({
            url: 'server/obtener_alumnos_entregable.php',
            type: 'POST',
            data: {id_ent_cop},
            success: function(respuesta){
                $("#contenedor_alumnos_actividad_entregable").html(respuesta);
                //console.log(respuesta);

       
            }
        });
	}

    //MODAL
    // OBTENER ALUMNOS ACTIVIDAD

    $('#btn_modal_alumnos_entregable').on('click', function(event) {
        event.preventDefault();

        obtener_alumnos_entregable();
        
    });


    $('#btn_modal_actividad_entregable').on('click', function(event) {
        event.preventDefault();

        obtener_actividad_entregable();
        
    });



    
    
</script>