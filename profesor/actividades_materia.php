<?php  

	include( 'inc/header.php' );
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



<!-- NAVEGACION INTERNA -->
<?php  
	echo obtenerNavegacionGrupo( $id_sub_hor, $id );
?>
<!-- FIN NAVEGACION INTERNA -->


<style>
	.claseHijoNumeracion {
		position: absolute;
		left: -1px;
		top: -20px;
		background-color: lightgray;
		border-radius: 50%;
		height: 25px;
		width: 25px;
		z-index: 99;
	}

	.claseTextoHijoNumeracion{

		font-size: 18px;
		color: white;
		text-align: center;

	}



	.clasePadre {
		position: relative;
	}
</style>
<!-- TITULO -->
<div class="row ">
	<div class="col-md-6 text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Video-clase">
			<i class="fas fa-bookmark"></i> 
			Actividades de <?php echo $filaMateria['nom_mat']; ?>
		</span>
		
		<br>
		
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Actividades</a>
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

	$sqlMaterias = "
	    SELECT *
	    FROM sub_hor
	    INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
	    INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
	    INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
	    INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
	    INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
	    WHERE id_sub_hor = '$id_sub_hor'
	";

	$resultadoDatosHorario = mysqli_query( $db, $sqlMaterias );

	$filaDatosHorario = mysqli_fetch_assoc( $resultadoDatosHorario );

	// DATOS RAMA
	$nom_ram = $filaDatosHorario['nom_ram'];
	$mod_ram = $filaDatosHorario['mod_ram'];
	$gra_ram = $filaDatosHorario['gra_ram'];
	$per_ram = $filaDatosHorario['per_ram'];
	$cic_ram = $filaDatosHorario['cic_ram'];

	// DATOS CICLO ESCOLAR
	$nom_cic = $filaDatosHorario['nom_cic'];
	$ins_cic = $filaDatosHorario['ins_cic'];
	$ini_cic = $filaDatosHorario['ini_cic'];
	$cor_cic = $filaDatosHorario['cor_cic'];
	$fin_cic = $filaDatosHorario['fin_cic'];


?>


<style>

	.claseHijoIzquierda {
		position: absolute;
		left: 15px;
		bottom: -40px;
		background-color: lightgray;
		border-radius: 5px;
		height: 40px;
		width: 100px;
		z-index: 5;
		padding: 5px;
	}

	.claseHijoDerecha {
		position: absolute;
		right: 15px;
		bottom: -40px;
		background-color: lightgray;
		border-radius: 5px;
		height: 40px;
		width: 100px;
		z-index: 5;
		padding: 5px;
	}

	.clasePadre {
		position: relative;
	}


	.claseHijoTabla {
	    position: absolute;
	    right: -13px;
	    bottom: -20px;
	    border-radius: 5px;
	    height: 40px;
	    width: 150px;
	    padding: 5px;
	}

	.clasePadreTabla {
		position: relative;
	}

</style>

<!-- MODAL -->
<div class="modal fade" id="modal_edicion_actividad" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
	      	<form id="formulario_edicion_actividad">	
	      		<div class="modal-header">
		        	<h5 class="modal-title" id="titulo_edicion_actividad"></h5>
		        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          	<span aria-hidden="true">&times;</span>
		        	</button>
	      		</div>
		      	<div class="modal-body">
		      		<!-- FORMULARIO -->
		      		<input type="hidden" id="id_actividad" name="id_actividad">
		      		<input type="hidden" id="tipo_actividad" name="tipo_actividad">
		      		<div class="row">
		      			<div class="col-md-12">
		      				<a href="#" class="letraPequena text-primary btn-link" target="_blank" id="liga_actividad">*Haz click aquí para ir a la actividad :D</a>
		      			</div>
		      		</div>
		      		<div class="row">

		      			<!--  -->
		      			<div class="col-md-6">

							<p class="letraPequena grey-text">
					      		* Asigna una fecha de inicio
					      	</p>

							<div class="md-form mb-2">

								<i class="fas fa-minus-circle prefix grey-text"></i>
								<input type="date" id="inicio_actividad" name="inicio_actividad" min="0" step="1" class="form-control validate" value="">
								
							</div>
					    
					  	</div>
					  	
					  	<div class="col-md-6">

					  		<p class="letraPequena grey-text">
					      		* Asigna una fecha de vencimiento
					      	</p>
					        
					        <div class="md-form mb-2">
						        <i class="fas fa-plus-circle prefix grey-text"></i> 
						        <input type="date" id="fin_actividad" name="fin_actividad" class="form-control validate" value="">
					        </div>
					    
					  	</div>
		      			<!--  -->
		      		</div>
		      		<!-- FIN FORMULARIO -->
		      	</div>

		      	<div class="modal-footer">
		        	<button class="btn btn-info white-text btn-rounded btn-sm" type="submit" title="Guardar" id="btn_edicion_actividad">
			        	Guardar
			        </button>
			      	
			      	<a class="btn grey white-text btn-rounded waves-effect btn-sm" title="Salir..." data-dismiss="modal">
			            Cancelar
			        </a>
		      	</div>
		    </form>
    	</div>
  	</div>
</div>
<!-- FIN MODAL -->

<!-- DATOS PROGRAMA Y CICLO -->
<div class="row">
  
  	<div class="col-md-4 text-left">
	    <div class="card">
	      <div class="card-header bg-white">
	        Semana
			<?php  
				$fechaHoy = date( 'Y-m-d' );

				$diferenciaDias = obtenerDiferenciaFechas( $fechaHoy, $ini_cic );

				echo floor( $diferenciaDias / 7 );

				$diasCiclo = obtenerDiferenciaFechas( $fin_cic, $ini_cic );

				$porcentajeAvance = floor( ( ( $diferenciaDias * 100 ) / $diasCiclo ) );

				// echo $porcentajeAvance;
			?>
	      </div>
	      <div class="card-body">
	      

	          <label class="letraMediana">
	          Inicio: <?php echo mb_strtolower( obtenerFechaGuapa( $ini_cic ) ); ?>
	          <br>
	          Finaliza: <?php echo mb_strtolower( obtenerFechaGuapa( $fin_cic ) ); ?>
	          <br>
	          <?php echo $diferenciaDias; ?> días transcurridos
	          <br>
	          Duración del ciclo escolar de <?php echo $diasCiclo; ?> días
	          <br>
	          Semana <?php echo floor( $diferenciaDias / 7 )." de  ".floor( $diasCiclo / 7 )." semanas"; ?> 
	        </label>
	      </div>
	    </div>
	</div>

	  <div class="col-md-4 text-left">
	    <div class="card">
	      <div class="card-header bg-white">
	        Datos del Ciclo Escolar
	      </div>
	      <div class="card-body">
	      

	          <label class="letraMediana">
	          <?php echo $nom_cic; ?>
	          <br>
	          Inscripción: <?php echo fechaFormateadaCompacta($ins_cic); ?>
	          <br>
	          Inicio: <?php echo fechaFormateadaCompacta($ini_cic); ?>
	          <br>
	          Corte: <?php echo fechaFormateadaCompacta($cor_cic); ?>
	          <br>
	          Fin: <?php echo fechaFormateadaCompacta($fin_cic); ?>
	        </label>
	      </div>
	    </div>
	  </div>

	<div class="col-md-4 text-left">
	    <div class="card">
	      <div class="card-header bg-white">
	        Datos del Programa
	      </div>
	      <div class="card-body">
	        <label class="letraMediana">
	          Programa: <?php echo $nom_ram; ?>
	          <br>
	          Modalidad: <?php echo $mod_ram; ?>
	          <br>
	          Nivel Educativo: <?php echo $gra_ram; ?>
	          <br>
	          Tipo de Periodo: <?php echo $per_ram; ?>
	          <br>
	          Cantidad de Periodos: <?php echo $cic_ram; ?>

	        </label>

	      
	      </div>
	    </div>
	</div>

  	

</div>
<!-- FIN DATOS PROGRAMA Y CICLO -->

<br>

<!-- BARRA -->
<div class="row">

	<div class="col-md-12 clasePadre">
		
		<div class="progress md-progress" style="height: 20px" id="barra_video">
		    
		    <div class="progress-bar text-center white-text" role="progressbar" style="height: 20px; " aria-valuenow="" aria-valuemin="0" aria-valuemax="100" id="barra_estado" title="Esta barra representa el avance del ciclo escolar">
		    	
		    </div>
			
			
		</div>

		<p class="claseHijoIzquierda letraMediana font-weight-bold">
			Inicio de ciclo
			<br>
			<?php echo fechaFormateadaCompacta($ini_cic); ?>
		</p>


		<p class="claseHijoDerecha letraMediana font-weight-bold">
			Fin de ciclo
			<br>
			<?php echo fechaFormateadaCompacta($fin_cic); ?>
		</p>
	
	</div>

</div>
<!-- FIN BARRA -->


<br>
<br>


<!-- DESCRIPCION -->
<p class="note note-info">
	<!-- <button type="button" class="close" aria-label="Close">
	  <span aria-hidden="true">×</span>
	</button> -->
	<strong>Nota</strong>

	<h4><span class="badge badge-warning">¡Ya puedes editar las fechas aquí mismo, haz clic sobre ellas!</span></h4>
 
	<br>
	En esta sección se relacionan alumnos con actividades. Puedes verificar el desempeño académico de forma cronológica de izquierda a derecha. 
	<br>
	La columna de 'Alumno', así como las de 'Puntos obtenidos' y 'Promedio final' estarán siempre fijas o podrás navegar 
	horizontalmente para consultar o editar algunos elementos.

	<br>
	<br>

	Asimismo, si deseas hacer alguna modificación en la actividad o la calificación del alumno, haz click sobre la celda ( alumno-actividad ) y se desplegará un menú que te permitirá realizarlo. Recuerda que tus actividades tienen un puntaje que no
	debes exceder.

	<br>
	<br>

	Si tienes dudas o sugerencias, favor de comunicarlo al soporte técnico de inmediato.
</p>
<!-- FIN DESCRIPCION -->

<br>
<br>

<!-- LISTADO ALUMNOS -->
<div class="row">
	
	<div class="col-md-12" id="contenedor_actividades_materia">
		
		
	</div>
</div>
<!-- FIN LISTADO ALUMNOS -->


<?php 

    include( 'inc/footer.php' );

?>


<script>

	obtener_actividades_materia();

	function obtener_actividades_materia(){
		$( '#contenedor_actividades_materia' ).html( '<h3 class="text-center grey-text"><i class="fas fa-cog fa-spin"></i> Cargando...</h3>' );

		var id_sub_hor = <?php echo $id_sub_hor; ?>;

		$.ajax({
			url: 'server/obtener_tabla_actividades_materia.php',
			type: 'POST',
			data: { id_sub_hor },
			success: function( respuesta ){

				$( '#contenedor_actividades_materia' ).html( respuesta );
			
			}
		});
		
	}
</script>

<script>
    
    var r = 254;
    var porcentajeAvance = 0;
    var limite = <?php echo $porcentajeAvance; ?>;
    iniciarCambioBarra( r, porcentajeAvance, limite );




    function iniciarCambioBarra( r, porcentajeAvance ){
        if( r > 50 || porcentajeAvance < limite ) {
            setTimeout(function(){
                r = r - 2;
                $( '#barra_estado' ).css({
                    background: 'rgb( '+r+', 255, 50)',
                    width : porcentajeAvance+'%'
                }).text( porcentajeAvance+'%' );

                if ( porcentajeAvance < limite ) {
                    porcentajeAvance++;
                }
                
                iniciarCambioBarra( r, porcentajeAvance, limite );
            }, 50 );
        }
    }
    
</script>