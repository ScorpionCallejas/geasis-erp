<?php  
	//ARCHIVO VIDEO-CLASE
	//header.php

	if ( isset( $_GET['validador'] ) ) {
		// EXISTE VALIDADOR
		require('inc/cabeceras.php');
		require('inc/funciones.php');
		require_once(  __DIR__."/../includes/links_estilos.php");
	
		// FIN NO EXISTE VALUDADOR
	} else {
		// NO EXISTE VALIDADOR
		include('inc/header.php');

		// FIN NO EXISTE VALIDADOR
	}
	

	$id_sub_hor = $_GET['id_sub_hor'];

	$sqlMateria = "
		SELECT *
		FROM sub_hor
		INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
		INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
		WHERE id_sub_hor = '$id_sub_hor' AND id_pro1 = '$id'
	";

	$resultadoValidacion = mysqli_query( $db, $sqlMateria );

	$validacion = mysqli_num_rows( $resultadoValidacion );

	if ( $validacion == 0 ) {
	
		header('location: not_found_404_page.php');
	
	}

	$resultadoMateria = mysqli_query( $db, $sqlMateria );

	$filaMateria = mysqli_fetch_assoc( $resultadoMateria );



?>

<!-- CONTENIDO -->
<style>
	#mapid { 
		height: 400px; 
		width: 100%;
	}

</style>

<?php  
	if ( isset( $_GET['validador'] ) ) {
		// EXISTE VALIDADOR
?>
		<style>
			body{
				background: #212121;
			}
		</style>



<?php
		// FIN NO EXISTE VALUDADOR
	} else {
		// NO EXISTE VALIDADOR
?>
		
		<!-- NAVEGACION INTERNA -->
		<?php  
			echo obtenerNavegacionGrupo( $id_sub_hor, $id );
		?>
		<!-- FIN NAVEGACION INTERNA -->
		
		<!-- TITULO -->
		<div class="row ">
			<div class="col-md-6 text-left">
				<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Video-clase">
					<i class="fas fa-bookmark"></i> 
					Video-clase de <?php echo $filaMateria['nom_mat']; ?>
				</span>
				
				<br>
				
				<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
					<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
					<i class="fas fa-angle-double-right"></i>
					<a style="color: black;" href="" title="Estás aquí">Video-clase</a>
				</div>
			</div>


			<div class="col-md-6 text-right">
				<span class="subtituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Grupo">
					<i class="fas fa-circle"></i>
					<?php echo $filaMateria['nom_gru']; ?>
				</span>
			</div>
			
		</div>
		<!-- FIN TITULO -->

<?php
		// FIN NO EXISTE VALIDADOR
	}
	
?>


<!-- PIZARRA -->
<a class="btn grey darken-2 white-text waves-effect" title="Abre una pizarra para interactuar en tiempo real con tu clase. ¡Recuerda solicitar a tus alumnos que también la abran!" id="btn_pizarra" href="#" style="
	position: fixed;
	width: 200px;
    right: -1%;
    top: 8%;
    z-index: 100;"
>
	<i class="fas fa-chalkboard"></i>
	Pizarra <span class="badge badge-danger" >¡Nuevo!</span>
</a>
<!-- FIN PIZARRA -->

<!-- MAPA -->
<a class="btn grey darken-2 white-text waves-effect" title="Abre un mapa para interactuar con tu clase. ¡Recuerda solicitar a tus alumnos que también lo abran!" id="btn_mapa" href="#" style="
	position: fixed;
	width: 200px;
    right: -1%;
    top: 15%;
    z-index: 100;"
>
	<i class="fas fa-globe"></i>
	Mapa <span class="badge badge-danger" >¡Nuevo!</span>
</a>
<!-- FIN MAPA -->

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


<!-- MODAL PIZARRA -->
<!-- FONDO CLASE -->
<div class="modal fade text-left " id="modal_pizarra">
  	<div class="modal-dialog modal-fluid" role="document">
    
  
      	<div class="modal-content">
	        <div class="modal-header text-center">
	          
	          	<h4 class="modal-title w-100 white-text" id="titulo_modal_pizarra">
	        		Pizarra de <?php echo $filaMateria['nom_mat']; ?>
	          	</h4>

	          	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            	<span aria-hidden="true">&times;</span>
	          	</button>
	        </div>

	        <div class="modal-body mx-3">

	        	<iframe src="https://wbo.ophir.dev/boards/pizarra_<?php echo $id_sub_hor; ?>" frameborder="0" style="height: 700px; width: 100%;"></iframe>
	          


	        </div>

	        <div class="modal-footer d-flex justify-content-center">
	          	<a class="btn grey white-text btn-rounded waves-effect btn-sm" title="Salir..." data-dismiss="modal">
		            Cancelar
		        </a>
	        </div>

	      </div>


  		</div>
	</div>
</div>

<!-- FIN FONDO CLASE -->

<!-- FIN MODAL PIZARRA -->




<!-- MODAL MAPA -->
<!-- FONDO CLASE -->
<div class="modal fade text-left " id="modal_mapa">
  	<div class="modal-dialog modal-fluid" role="document">
    
  
      	<div class="modal-content">
	        <div class="modal-header text-center">
	          
	          	<h4 class="modal-title w-100 white-text">
	        		Mapa Global
	          	</h4>

	          	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            	<span aria-hidden="true">&times;</span>
	          	</button>
	        </div>

	        <div class="modal-body mx-3">

	        	 <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d27182948.693331305!2d-114.95046106691929!3d19.794726619536608!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x84043a3b88685353%3A0xed64b4be6b099811!2zTcOpeGljbw!5e1!3m2!1ses!2smx!4v1594354393111!5m2!1ses!2smx" width="100%" height="500" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>


	        </div>

	        <div class="modal-footer d-flex justify-content-center">
	          	<a class="btn grey white-text btn-rounded waves-effect btn-sm" title="Salir..." data-dismiss="modal">
		            Cancelar
		        </a>
	        </div>

	      </div>


  		</div>
	</div>
</div>

<!-- FIN FONDO CLASE -->

<!-- FIN MODAL MAPA -->

<?php 

	include('inc/footer.php');

?>

<script>

  	var id_sub_hor = <?php echo $id_sub_hor; ?>;

	<?php  
		$servicio = "jitsi";

		if ( $servicio == "jitsi" ) {
	?>

			function obtenerServicio1( id_sub_hor ){

				$.ajax({
			      	url: '../profesor/server/obtener_servicio2_materia.php',
			      	type: 'POST',
			      	data: {id_sub_hor},
			      	success: function(respuesta){
			        	$("#servicio1").html(respuesta);
			      	}
			    });
			}


			function obtenerServicio2( id_sub_hor ){
				$.ajax({
			      	url: '../profesor/server/obtener_servicio1_materia.php',
			      	type: 'POST',
			      	data: {id_sub_hor},
			      	success: function(respuesta){
			        	$("#servicio2").html(respuesta);
			      	}
			    });
			}

	<?php
		} else {
	?>

			function obtenerServicio1( id_sub_hor ){

				$.ajax({
			      	url: '../profesor/server/obtener_servicio1_materia.php',
			      	type: 'POST',
			      	data: {id_sub_hor},
			      	success: function(respuesta){
			        	$("#servicio1").html(respuesta);
			      	}
			    });
			}


			function obtenerServicio2( id_sub_hor ){
				$.ajax({
			      	url: '../profesor/server/obtener_servicio2_materia.php',
			      	type: 'POST',
			      	data: {id_sub_hor},
			      	success: function(respuesta){
			        	$("#servicio2").html(respuesta);


			      	}
			    });
			}

	<?php
		}
	?>


	obtenerServicio1( id_sub_hor );

	$("#btn_servicio1").on('click', function(event) {
	    event.preventDefault();
	    /* Act on the event */

	    obtenerServicio1( id_sub_hor );


	});


	



	$("#btn_servicio2").on('click', function(event) {
	    event.preventDefault();
	    /* Act on the event */

	    obtenerServicio2( id_sub_hor );

	    
	});

</script>


<script>

	setTimeout(function(){
		$( '#mainContainer' ).removeClass('black-text').addClass('white-text grey darken-3');
		$( '#mainNabvar' ).removeClass('grey').addClass('grey darken-3');
		$( '#mainBody' ).removeClass('grey lighten-3').addClass('elegant-color');
	}, 1000 );
	
</script>


<script>
	$( '#btn_pizarra' ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		$( '#modal_pizarra' ).modal( 'show' );
		
		generarAlerta( 'Invita a tus alumnos a la pizarra' );


	});


	$( '#btn_mapa' ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		$( '#modal_mapa' ).modal( 'show' );
		
		generarAlerta( 'Invita a tus alumnos a abrir el mapa' );

		var map = L.map('mapid').setView([51.505, -0.09], 13);

		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
		}).addTo(map);

		L.marker([51.5, -0.09]).addTo(map)
		    .bindPopup('A pretty CSS3 popup.<br> Easily customizable.')
		    .openPopup();



	});
</script>


<script>
	generarAlerta( 'Recuerda usar la pizarra:)' );
</script>