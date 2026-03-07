<?php  
	//ARCHIVO VIA AJAX PARA OBTENER ACTIVIDADES EN TIMELINE
	//materias_horario.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$id_alu_ram = $_POST['id_alu_ram'];

	// echo $id_alu_ram;

?>

<!-- SELECTOR MATERIA HORARIO -->

<?php  
	$sqlHorario = "
	    SELECT *
	    FROM sub_hor
	    INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
	    INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
	    INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
	    INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
	    INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
	    INNER JOIN alu_hor ON alu_hor.id_sub_hor5 = sub_hor.id_sub_hor
	    INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
	    INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
	    WHERE id_alu_ram1 = '$id_alu_ram' AND id_alu = '$id' AND est_sub_hor = 'Activo'
	    GROUP BY id_mat
	    ORDER BY id_mat DESC
	";

	// echo $sqlHorario;


	$resultadoHorario = mysqli_query( $db, $sqlHorario );
?>

<select class="mdb-select md-form colorful-select dropdown-primary" id="selectorMateria">
	<option value="Todos" id="optionTodos">Todas las materias...</option>
	<?php

	  while( $filaMaterias = mysqli_fetch_assoc( $resultadoHorario ) ) {
	?>  
	    <option value="<?php echo $filaMaterias['id_sub_hor']; ?>"><?php echo substr( $filaMaterias["nom_mat"].' / '.$filaMaterias['nom_pro'].' '.$filaMaterias['app_pro'], 0, 35 )."..."; ?></option>
	    
	    
	<?php        
	  }

	?>
</select>
<!-- FIN SELECTOR MATERIA HORARIO -->

<span class="text-danger">Visualiza tus retroalimentaciones con la opción "Lista"</span>

<div class="row">
	<div class="col-md-12 text-left">
		
		<div class="form-check form-check-inline">
		  <input type="radio" class="form-check-input radiosFuncionalidad" id="radiosFuncionalidad3" name="radiosFuncionalidad" value="tabla" checked>
		  <label class="form-check-label" for="radiosFuncionalidad3"><i class="fas fa-list-ol grey-text"></i> Lista</label>
		</div>

		<!-- Material inline 1 -->
		<div class="form-check form-check-inline">
		  <input type="radio" class="form-check-input radiosFuncionalidad" id="radiosFuncionalidad1" name="radiosFuncionalidad" value="actividades" >
		  <label class="form-check-label" for="radiosFuncionalidad1"><i class="fas fa-book grey-text"></i> Línea de tiempo</label>
		</div>


		<div id="contenedor_radios_funcionalidad">
			<!-- Material inline 2 -->
			<div class="form-check form-check-inline">
			  <input type="radio" class="form-check-input radiosFuncionalidad" id="radiosFuncionalidad2" name="radiosFuncionalidad" value="mensajes">
			  <label class="form-check-label" for="radiosFuncionalidad2"><i class="fas fa-envelope grey-text"></i> Mensajería</label>
				
				
			  <input type="radio" class="form-check-input radiosFuncionalidad" id="radiosFuncionalidad4" name="radiosFuncionalidad" value="video">
			  <label class="form-check-label" for="radiosFuncionalidad4"><i class="fas fa-video grey-text"></i> Clase en vivo</label>


			</div>
		</div>
		


	</div>
</div>

<br>




<script>
	$(document).ready(function() {
		

		$( '#selectorMateria' ).materialSelect();

	    obtenerSeleccionMateria();
	    $( '#selectorMateria' ).on('change', function() {
	    	
	    	obtenerSeleccionMateria();
	      
	    });

	    function obtenerSeleccionMateria() {
	        // alert( 'exito' );

	        var materia = $('#selectorMateria option:selected').val();

	        if ( materia != 'Todos' ) {

	        	$( '#contenedor_radios_funcionalidad' ).css({
					display: ''
				});


	        } else {
	        	
	        	$( '#contenedor_radios_funcionalidad' ).css({
					display: 'none'
				});

	        }

	        obtenerSeleccionRadio();

	        
	    }



	    $( '.radiosFuncionalidad' ).on('click', function() {
        	/* Act on the event */
        	
        	obtenerSeleccionRadio();
        });

        function obtenerSeleccionRadio() {

        	var funcionalidad = $( "input[name='radiosFuncionalidad']:checked" ).val();
        	
        	var id_alu_ram = <?php echo $id_alu_ram; ?>;
        	var materia = $('#selectorMateria option:selected').val();

        	if ( ( funcionalidad == 'mensajes' ) && ( materia == 'Todos' ) ) {

        		toastr.error( '¡No hay sala de mensajería para todas las materias!' );
        		$( "#radiosFuncionalidad2" ).prop( "checked", false );
        		$( "#radiosFuncionalidad3" ).prop( "checked", true );

        		$("#contenedor_actividades").html('<h3 class="text-center grey-text"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>');
		        $.ajax({
		          url: 'server/obtener_actividades_materia.php',
		          type: 'POST',
		          data: { materia, id_alu_ram },
		          success: function ( respuesta ) {

		            $("#contenedor_actividades").html( respuesta );

		          }
		        });

        	} else {

        		if ( funcionalidad == 'mensajes' ) {

			        // alert( materia );
			        $("#contenedor_actividades").html('<h3 class="text-center grey-text"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>');
			        $.ajax({
			          url: 'server/obtener_sala_materia.php',
			          type: 'POST',
			          data: { materia, id_alu_ram },
			          success: function ( respuesta ) {

			            $("#contenedor_actividades").html( respuesta );

			          }
			        });

	        	} else if ( funcionalidad == 'actividades' ) {


	        		$("#contenedor_actividades").html('<h3 class="text-center grey-text"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>');
			        $.ajax({
			          url: 'server/obtener_actividades.php',
			          type: 'POST',
			          data: { materia, id_alu_ram },
			          success: function ( respuesta ) {

			            $("#contenedor_actividades").html( respuesta );

			          }
			        });

	        	} else if ( funcionalidad == 'tabla' ) {

	     
	        		$("#contenedor_actividades").html('<h3 class="text-center grey-text"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>');
			        $.ajax({
			          url: 'server/obtener_actividades_materia.php',
			          type: 'POST',
			          data: { materia, id_alu_ram },
			          success: function ( respuesta ) {

			            $("#contenedor_actividades").html( respuesta );

			          }
			        });

	        	} else if ( funcionalidad == 'video' ) {



	        		$("#contenedor_actividades").html('<h3 class="text-center grey-text"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>');
			        $.ajax({
			          url: 'server/obtener_video_clase.php',
			          type: 'POST',
			          data: { materia, id_alu_ram },
			          success: function ( respuesta ) {

			            $("#contenedor_actividades").html( respuesta );

			          }
			        });

	        	}

        	}

        	
        }

	});
	

</script>