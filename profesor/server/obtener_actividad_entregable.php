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
	// 	header("location: not_found_404_page.php");
	// }
	
?>



<!-- ACTIVIDAD -->

<div class="row text-center">

	<div class="col-md-4">

		<div class="card " style="border-radius: 20px;">
			<div class="card-body">
				
				<i class="fas fa-check"></i>
				<br>
				<span class="letraMediana font-weight-normal">
					Puntos: <?php echo $pun_ent; ?>
				</span>

			</div>
		</div>
		
		
		
	</div>

	<div class="col-md-4">
		
		<div class="card " style="border-radius: 20px;">
			<div class="card-body">
				
				<i class="far fa-calendar-minus"></i>
				<br>
				<span class="letraMediana font-weight-normal">
					Inicio: <?php echo fechaFormateadaCompacta($ini_ent_cop); ?>
				</span>

			</div>
		</div>

		
		
		
	</div>

	<div class="col-md-4">

		<div class="card " style="border-radius: 20px;">
			<div class="card-body">
				
				<i class="far fa-calendar-plus"></i>
				<br>
				<span class="letraMediana font-weight-normal">
					Fin: <?php echo fechaFormateadaCompacta($fin_ent_cop); ?>
				</span>

			</div>
		</div>
		

		
	</div>

</div>
<!-- FIN DATOS ACTIVIDAD -->

<br>




<div class="row">
    

    <!-- CONTENIDO DE ACTIVIDAD -->
    <div class="col-md-12">
        
        <div class="card grey lighten-5" style="border-radius: 20px;">
        	<div class="card-body" id="contenedor_instrucciones">
        		<?php  
					echo $des_ent;
	            ?>
        	</div>
        </div>

        


    </div>

    
</div>
    

<!-- FIN DETALLES DEL FORO -->

<br>
<!-- FIN DETALLES DEL ENTREGABLE -->
	
<!-- LISTADO DE TAREAS TOTALES -->
<div class="jumbotron" style="border-radius: 20px;"  id="contenedor_tareas">


	




</div>
	
	
<!-- FIN LISTADO DE TAREAS TOTALES -->

	


<script>
	obtenerTareas();



    function obtenerTareas(){
    	//TAABLA DE TAREAS
		var id_ent_cop = <?php echo $id_ent_cop; ?>;

		$.ajax({
			url: 'server/obtener_tareas_alumnos.php',
			type: 'POST',
			data: {id_ent_cop},
			success: function(respuesta){

				$("#contenedor_tareas").html(respuesta);

				// $( $('#myTableTareas_wrapper .dt-buttons').children().eq(0) ).on('click', function(event) {
		  //       	event.preventDefault();
		  //       	/* Act on the event */
		  //       	obtenerTareas();
		  //   		setTimeout(function(){
		  //   			$( $('#myTableTareas_wrapper .dt-buttons').children().eq(0) ).trigger("click");    	
		  //       		console.log( 'click' );
		  //   		}, 200);
		  //       });
				
			}
		});
    }

    
    
</script>


<script>
	setTimeout(function(){
		$('#contenedor_instrucciones img').addClass('img-fluid');
	}, 500 );
	// $('#contenedor_instrucciones')
</script>