<?php

	include('inc/header.php');

	$id_alu_ram = $_GET['id_alu_ram'];

	$sqlPrograma = "
		SELECT *
		FROM alu_ram
		INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
		WHERE id_alu_ram = '$id_alu_ram'
	";

	$resultadoPrograma = mysqli_query( $db, $sqlPrograma );

	$filaPrograma = mysqli_fetch_assoc( $resultadoPrograma );

	if ( $filaPrograma['mod_ram'] == 'Online' ) {
		
		$sqlActividades = "
			SELECT *
			FROM sub_hor
			INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
			INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
			INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
			INNER JOIN alu_hor ON alu_hor.id_sub_hor5 = sub_hor.id_sub_hor
			INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
			INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
			INNER JOIN rama ON rama.id_ram = materia.id_ram2
			WHERE id_alu_ram1 = '$id_alu_ram' AND id_alu = '$id' AND est_alu_hor = 'Activo'
			GROUP BY id_sub_hor
		";

	} else if ( $filaPrograma['mod_ram'] == 'Presencial' ) {

		$sqlActividades = "
			SELECT *
			FROM sub_hor
			INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
			INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
			INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
			INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
			INNER JOIN alu_hor ON alu_hor.id_sub_hor5 = sub_hor.id_sub_hor
			INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
			INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
			INNER JOIN rama ON rama.id_ram = materia.id_ram2
			WHERE id_alu_ram1 = '$id_alu_ram' AND id_alu = '$id' AND est_alu_hor = 'Activo'
			GROUP BY id_sub_hor
		";	
	}

	

	// echo $sqlActividades;
	$resultadoActividades = mysqli_query($db, $sqlActividades);
	

	$resultadoActividadesTitulo = mysqli_query($db, $sqlActividades);
	$filaActividades = mysqli_fetch_assoc($resultadoActividadesTitulo);
	$nom_ram = $filaActividades['nom_ram'];


	// VALIDACCION ACCESO
	$resultadoValidacionAcceso = mysqli_query($db, $sqlActividades);
	$totalValidacion = mysqli_num_rows($resultadoValidacionAcceso);

	
	if ($totalValidacion == 0) {
		header('location: not_found_404_page.php');
	}

?>


 <!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Ramas"><i class="fas fa-bookmark"></i> Historial de actividades</span>
		<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Historial de actividades</a>
		</div>		
	</div>
	<div class="col text-right">
		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Actividades de <?php echo $nom_ram; ?>">
			<i class="fas fa-certificate"></i>
			Programa: <?php echo $nom_ram; ?>
		</span>
	</div>
</div>
<!-- FIN TITULO -->


<div id="contenedor_tabla_historial_actividades">
	
</div>



	<!-- MODAL OBTENER ACTIVIDAD -->
	<div class="modal fade text-left " id="modal_obtener_actividad">
	  <div class="modal-dialog modal-lg" role="document">
	    
	      <div class="modal-content">
	        <div class="modal-header text-center grey darken-1 white-text">
	          
	          	<h4 class="modal-title w-100 white-text" id="titulo_modal_obtener_actividad">
		        </h4>

	          	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            	<span aria-hidden="true">&times;</span>
	          	</button>
	        </div>
	        
	        <div class="modal-body mx-3" id="contenedor_modal_obtener_actividad">


	        </div>

	        <div class="modal-footer d-flex justify-content-center">
		      	
		      	<a class="btn grey white-text btn-rounded waves-effect btn-sm" title="Salir..." data-dismiss="modal">
		            Cancelar
		        </a>

	        
	        </div>

	      </div>

	  </div>
	</div>
	<!-- FIN MODAL OBTENER ACTIVIDAD -->



<br>
<br>


<?php  

	include('inc/footer.php');

?>
<script>
	obtenerTablaHistorialActividades();
	
	function obtenerTablaHistorialActividades(){
		var id_alu_ram = parseInt( '<?php echo $id_alu_ram; ?>' );

		$.ajax({
			url: 'server/obtener_tabla_historial_actividades.php',
			type: 'POST',
			data: { id_alu_ram },
			success: function( respuesta ){
				$( '#contenedor_tabla_historial_actividades' ).html( respuesta );
			}
		})
	}

	// ✅ AGREGA ESTA FUNCIÓN AQUÍ
	function obtener_controlador_examen( id_exa_cop ){
		var id_alu_ram = '<?php echo (int)$id_alu_ram; ?>';

		$.ajax({
			url: 'server/obtener_controlador_examen.php',
			type: 'POST',
			data: { id_exa_cop, id_alu_ram },
			success: function ( respuesta ) {
				$( '#modal_obtener_actividad' ).modal( 'show' );
				$( '#contenedor_modal_obtener_actividad' ).html( respuesta );
			}
		});
	}

	// ✅ Y TAMBIÉN NECESITAS ESTAS DOS (si no las tienes)
	function obtener_controlador_foro( id_for_cop ){
		var id_alu_ram = '<?php echo (int)$id_alu_ram; ?>';

		$.ajax({
			url: 'server/obtener_controlador_foro.php',
			type: 'POST',
			data: { id_for_cop, id_alu_ram },
			success: function ( respuesta ) {
				$( '#modal_obtener_actividad' ).modal( 'show' );
				$( '#contenedor_modal_obtener_actividad' ).html( respuesta );
			}
		});
	}

	function obtener_controlador_entregable( id_ent_cop ){
		var id_alu_ram = '<?php echo (int)$id_alu_ram; ?>';

		$.ajax({
			url: 'server/obtener_controlador_entregable.php',
			type: 'POST',
			data: { id_ent_cop, id_alu_ram },
			success: function ( respuesta ) {
				$( '#modal_obtener_actividad' ).modal( 'show' );
				$( '#contenedor_modal_obtener_actividad' ).html( respuesta );
			}
		});
	}
</script>