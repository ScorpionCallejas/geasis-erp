<?php  
	//ARCHIVO VIDEO-CLASE
	//header.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_sub_hor = $_POST['materia'];
	$id_alu_ram = $_POST['id_alu_ram'];

	$sqlMateria = "
		SELECT *
		FROM sub_hor
		INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
		WHERE id_sub_hor = '$id_sub_hor'
	";


	// echo $sqlMateria;

	$resultadoMateria = mysqli_query( $db, $sqlMateria );

	$filaMateria = mysqli_fetch_assoc( $resultadoMateria );



?>

<!-- CONTENIDO -->


<!-- PILLS SERVICIOS VIDEO -->
<ul class="nav nav-tabs nav-justified md-tabs grey darken-3 animated fadeInUp delay-1s" id="myTabJust" role="tablist">
  	
  	<li class="nav-item" title="Servicio 1 de video-clase">
    	<a class="nav-link active" id="btn_servicio1" data-toggle="tab" href="#servicio1" role="tab" aria-controls="servicio1"
      	aria-selected="true">
      		<i class="fas fa-video"></i> 
      		Servicio 1
      	</a>
  
  	</li>

  	<li class="nav-item" title="Servicio 2 de video-clase">
    	<a class="nav-link" id="btn_servicio2" data-toggle="tab" href="#servicio2" role="tab" aria-controls="servicio2"
      		aria-selected="false">
      		<i class="fas fa-video"></i> 
      		Servicio 2
      	</a>
  	</li>

</ul>
<!-- FIN PILLS SERVICIOS VIDEO -->


<!-- CONTENEDORES VIDEO -->
<div class="tab-content elegant-color animated fadeInDown delay-1s" id="myTabContentJust">
	
	<div class="tab-pane fade show active" id="servicio1" role="tabpanel" aria-labelledby="home-tab-just">
	</div>

	<div class="tab-pane fade" id="servicio2" role="tabpanel" aria-labelledby="profile-tab-just">
	</div>

</div>
<!-- FIN CONTENEDORES VIDEO -->



<script>

  	var id_sub_hor = <?php echo $id_sub_hor; ?>;
  	var id_alu_ram = <?php echo $id_alu_ram; ?>;

	<?php  
		$servicio = "jitsi";

		if ( $servicio == "jitsi" ) {
	?>

			function obtenerServicio1( id_sub_hor, id_alu_ram ){

				$.ajax({
			      	url: '../alumno/server/obtener_servicio2_materia.php',
			      	type: 'POST',
			      	data: {id_sub_hor, id_alu_ram},
			      	success: function(respuesta){
			        	$("#servicio1").html(respuesta);
			      	}
			    });
			}


			function obtenerServicio2( id_sub_hor, id_alu_ram ){
				$.ajax({
			      	url: '../alumno/server/obtener_servicio1_materia.php',
			      	type: 'POST',
			      	data: {id_sub_hor, id_alu_ram},
			      	success: function(respuesta){
			        	$("#servicio2").html(respuesta);
			      	}
			    });
			}

	<?php
		} else {
	?>

			function obtenerServicio1( id_sub_hor, id_alu_ram ){

				$.ajax({
			      	url: '../alumno/server/obtener_servicio1_materia.php',
			      	type: 'POST',
			      	data: {id_sub_hor, id_alu_ram},
			      	success: function(respuesta){
			        	$("#servicio1").html(respuesta);
			      	}
			    });
			}


			function obtenerServicio2( id_sub_hor, id_alu_ram ){
				$.ajax({
			      	url: '../alumno/server/obtener_servicio2_materia.php',
			      	type: 'POST',
			      	data: {id_sub_hor, id_alu_ram},
			      	success: function(respuesta){
			        	$("#servicio2").html(respuesta);
			      	}
			    });
			}

	<?php
		}
	?>


	obtenerServicio1( id_sub_hor, id_alu_ram );

	$("#btn_servicio1").on('click', function(event) {
	    event.preventDefault();
	    /* Act on the event */

	    obtenerServicio1( id_sub_hor, id_alu_ram );


	});





	$("#btn_servicio2").on('click', function(event) {
	    event.preventDefault();
	    /* Act on the event */

	    obtenerServicio2( id_sub_hor, id_alu_ram );

	    
	});

</script>