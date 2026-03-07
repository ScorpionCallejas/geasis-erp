<?php  
		

  include('inc/header.php');

  obtenerEstatusPagoAlumnoGlobal( $id );

  $id_alu_ram= $_GET['id_alu_ram'];

  $sqlValidacionModalidad = "
		SELECT *
		FROM alu_ram
		INNER JOIN rama ON rama.id_ram = alu_ram.id_ram3
		WHERE id_alu_ram = '$id_alu_ram'
 	";

 	$resultadoValidacionModalidad = mysqli_query($db, $sqlValidacionModalidad);

 	$filaValidacionModalidad = mysqli_fetch_assoc($resultadoValidacionModalidad);

 	if ($filaValidacionModalidad['mod_ram'] == 'Online') {
 		//echo "Online";
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
			WHERE id_alu_ram1 = '$id_alu_ram' AND id_alu = '$id' AND est_alu_hor = 'Activo'
			GROUP BY id_sub_hor
			ORDER BY id_mat DESC
		";






 	}else if($filaValidacionModalidad['mod_ram'] == 'Presencial'){
 		//echo "Presencial";

 		$sqlHorario = "
			SELECT *
			FROM sub_hor
			INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
			INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
			INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
			INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
			INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
			INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
			INNER JOIN alu_hor ON alu_hor.id_sub_hor5 = sub_hor.id_sub_hor
			INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
			INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
			WHERE id_alu_ram1 = '$id_alu_ram' AND id_alu = '$id' AND est_alu_hor = 'Activo'
			GROUP BY id_sub_hor
			ORDER BY id_mat DESC
		";
 	}

	//echo $sqlHorario;
	$resultadoHorario = mysqli_query($db, $sqlHorario);
	

	$resultadoHorarioTitulo = mysqli_query($db, $sqlHorario);
	$filaHorario = mysqli_fetch_assoc($resultadoHorarioTitulo);
	$nom_ram = $filaHorario['nom_ram'];


	// VALIDACCION ACCESO
	$resultadoValidacionAcceso = mysqli_query($db, $sqlHorario);
	$totalValidacion = mysqli_num_rows($resultadoValidacionAcceso);

	
	if ($totalValidacion == 0) {
		header('location: not_found_404_page.php');
	}

	$sqlMaterias = "
	    SELECT *
	    FROM alu_hor
	    INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
	    INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
	    INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
	    INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
	    INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
	    WHERE id_alu_ram = '$id_alu_ram'
	    GROUP BY id_cic
	";


	// echo $sqlMaterias;

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

	.claseHijoClaseMateria {
	  position: absolute;
	  right: 0px;
	  top: 0px;
	  z-index: 1;
	}

	.clasePadreClaseMateria {
	  position: relative;
	}

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

</style>
 <!-- TITULO -->
<div class="row ">
	<div class="col text-left">
		<span class="tituloPagina animated fadeInUp delay-2s badge blue-grey darken-4 hoverable" title="Calificaciones"><i class="fas fa-bookmark"></i> Calificaciones</span>
		<br>
		<div class=" badge badge-warning animated fadeInUp delay-3s text-white">
			<a href="index.php" title="Vuelve al inicio"><span class="text-white">Inicio</span></a>
			<i class="fas fa-angle-double-right"></i>
			<a style="color: black;" href="" title="Estás aquí">Calificaciones</a>
		</div>		
	</div>
	<div class="col text-right">
		<span class="subtituloPagina animated fadeInUp delay-4s badge blue-grey darken-4 hoverable" title="Horario de <?php echo $nom_ram; ?>">
			<i class="fas fa-certificate"></i>
			Programa: <?php echo $nom_ram; ?>
		</span>	
	</div>
</div>
<!-- FIN TITULO -->




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

		<p class="claseHijoIzquierda letraPequena font-weight-normal">
			Inicio de ciclo
			<br>
			<?php echo fechaFormateadaCompacta($ini_cic); ?>
		</p>


		<p class="claseHijoDerecha letraPequena font-weight-normal">
			Fin de ciclo
			<br>
			<?php echo fechaFormateadaCompacta($fin_cic); ?>
		</p>
	
	</div>

</div>
<!-- FIN BARRA -->


<br>
<br>

	<div class="row">

		<div class="col-md-12">
			<table class="table table-sm text-center table-hover" cellspacing="0" width="99%" id="myTable">
				<thead class="grey lighten-2">
					<tr>
						<th class="font-weight-normal">#</th>
						<th class="font-weight-normal">Profesor</th>
						<th class="font-weight-normal">Materia</th>
						
						

						<th class="font-weight-normal">Final</th>
					</tr>
				</thead>

				<tbody >

					<?php
						$i = 1;

						while($filaHorario = mysqli_fetch_assoc($resultadoHorario)){

					?>

						<tr>
							<td class="font-weight-normal">
								<?php echo $i; $i++;  ?>
							</td>


							<td class="font-weight-normal">
								<?php echo $filaHorario['nom_pro']; ?>
							</td>


							<td class="font-weight-normal">
								<?php echo $filaHorario['nom_mat']; ?>
							</td>


							<?php
					
								$id_mat = $filaHorario['id_mat'];
								$sqlEvaluacionCalificacion = "
									SELECT *
									FROM calificacion
									WHERE id_alu_ram2 = '$id_alu_ram' AND id_mat4 = '$id_mat'
								";

								$resultadoEvaluacionCalificacion = mysqli_query($db, $sqlEvaluacionCalificacion);

								while ($filaEvaluacionCalificacion = mysqli_fetch_assoc($resultadoEvaluacionCalificacion)) {
							?>


								<td class="font-weight-normal">
									<?php  
										if ($filaEvaluacionCalificacion['fin_cal'] == NULL) {
											echo "Pendiente";
										}else{
											echo $filaEvaluacionCalificacion['fin_cal'];
										}
									?>
								</td>
							<?php
								}

							?>

							

						</tr>


					<?php

						}
						//FIN WHILE
					?>
					
					
	
					
				</tbody>

			</table>
			
		</div>
		
			


	</div>


<!-- FIN CONTENIDO -->
<?php  

  include('inc/footer.php');

?>

<script>
	$(document).ready(function () {


		$('#myTable').DataTable({
			
		
			dom: 'Bfrtlip',
            
            buttons: [

            
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },                  

                    {
                        
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':visible'
                        },

                    },

                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },

                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },

            ],

			"language": {
                            "sProcessing":     "Procesando...",
                            "sLengthMenu":     "Mostrar _MENU_ registros",
                            "sZeroRecords":    "No se encontraron resultados",
                            "sEmptyTable":     "Ningún dato disponible en esta tabla",
                            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                            "sInfoPostFix":    "",
                            "sSearch":         "Buscar:",
                            "sUrl":            "",
                            "sInfoThousands":  ",",
                            "sLoadingRecords": "Cargando...",
                            "oPaginate": {
                                "sFirst":    "Primero",
                                "sLast":     "Último",
                                "sNext":     "Siguiente",
                                "sPrevious": "Anterior"
                            },
                            "oAria": {
                                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                            }
                        }
		});
		$('#myTable_wrapper').find('label').each(function () {
			$(this).parent().append($(this).children());
		});
		$('#myTable_wrapper .dataTables_filter').find('input').each(function () {
			$('#myTable_wrapper input').attr("placeholder", "Buscar...");
			$('#myTable_wrapper input').removeClass('form-control-sm');
		});
		$('#myTable_wrapper .dataTables_length').addClass('d-flex flex-row');
		$('#myTable_wrapper .dataTables_filter').addClass('md-form');
		$('#myTable_wrapper select').removeClass(
		'custom-select custom-select-sm form-control form-control-sm');
		$('#myTable_wrapper select').addClass('mdb-select');
		$('#myTable_wrapper .mdb-select').materialSelect();
		$('#myTable_wrapper .dataTables_filter').find('label').remove();
		var botones = $('#myTable_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
		//console.log(botones);

	
	});
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

<script>
	toastr.info('Semana <?php $fechaHoy=date( 'Y-m-d' );$diferenciaDias=obtenerDiferenciaFechas( $fechaHoy, $ini_cic );echo floor( $diferenciaDias / 7 );$diasCiclo=obtenerDiferenciaFechas( $fin_cic, $ini_cic );$porcentajeAvance=floor( ( ( $diferenciaDias * 100 ) / $diasCiclo ) );// echo $porcentajeAvance;?> de trabajo');
</script>