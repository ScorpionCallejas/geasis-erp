<?php  
	//ARCHIVO VIA AJAX PARA OBTENER HORARIO DE ALUMNO
  	//alumnos_carrera.php//server/obtener_alumnos_generacion.php		

	require('../inc/cabeceras.php');
	require('../inc/funciones.php');
?>


<?php 
	$id_alu_ram = $_POST['id_alu_ram'];

	$sqlHorario = "
		SELECT *
		FROM sub_hor
		INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
		INNER JOIN materia ON materia.id_mat = sub_hor.id_mat1
		INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
		INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
		INNER JOIN alu_hor ON alu_hor.id_sub_hor5 = sub_hor.id_sub_hor
		INNER JOIN alu_ram ON alu_ram.id_alu_ram = alu_hor.id_alu_ram1
		INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
		INNER JOIN rama ON rama.id_ram = materia.id_ram2
		WHERE id_alu_ram1 = '$id_alu_ram' AND est_alu_hor = 'Activo'
	";

	//echo $sqlHorario;
	$resultadoHorario = mysqli_query($db, $sqlHorario);
	$resultadoHorarioEvaluacion = mysqli_query($db, $sqlHorario);
	

	$resultadoHorarioTitulo = mysqli_query($db, $sqlHorario);
	$filaHorario = mysqli_fetch_assoc( $resultadoHorarioTitulo );
	$nom_ram = $filaHorario['nom_ram'];


	// VALIDACCION ACCESO
	$resultadoValidacionAcceso = mysqli_query($db, $sqlHorario);
	$totalValidacion = mysqli_num_rows($resultadoValidacionAcceso);




	// DATOS RAMA
	$nom_ram = $filaHorario['nom_ram'];
	$mod_ram = $filaHorario['mod_ram'];
	$gra_ram = $filaHorario['gra_ram'];
	$per_ram = $filaHorario['per_ram'];
	$cic_ram = $filaHorario['cic_ram'];

	// DATOS CICLO ESCOLAR
	$nom_cic = $filaHorario['nom_cic'];
	$nom_gru = $filaHorario['nom_gru'];
	$ins_cic = $filaHorario['ins_cic'];
	$ini_cic = $filaHorario['ini_cic'];
	$cor_cic = $filaHorario['cor_cic'];
	$fin_cic = $filaHorario['fin_cic'];


	// DATOS ALUMNO
	$nombreAlumno = $filaHorario['nom_alu']." ".$filaHorario['app_alu']." ".$filaHorario['apm_alu'];
	$fotoAlumno = $filaHorario['fot_alu'];
	$ingresoAlumno = $filaHorario['ing_alu'];
	$qrAlumno = $filaHorario['qr_alu'];

	
?>
<!-- CONTENIDO -->

	<!-- DATOS CICLO ESCOLAR -->
	<div class="row">
		<div class="col-md-6 text-left">
			<div class="card" style="border-radius: 20px;">
				<div class="card-body">
					<label class="letraPequena">
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

		<div class="col-md-6 text-left">
			<div class="card" style="border-radius: 20px;">
				<div class="card-body">
				

				  	<label class="letraPequena">
						<?php echo $nom_cic.' --- '.$nom_gru; ?>
						<br>
						Inscripción: <?php echo fechaFormateadaCompacta2($ins_cic); ?>
						<br>
						Inicio: <?php echo fechaFormateadaCompacta2($ini_cic); ?>
						<br>
						Corte: <?php echo fechaFormateadaCompacta2($cor_cic); ?>
						<br>
						Fin: <?php echo fechaFormateadaCompacta2($fin_cic); ?>
					</label>
				</div>
			</div>
		</div>

	</div>
	<!-- FIN DATOS CICLO ESCOLAR -->

	<!-- TABLA -->

	<div class="table-responsive">
		
	
		<table class="table table-sm text-center table-hover" cellspacing="0" width="99%" id="myTableHorarioAlumno">
			<thead>
				<tr>
					<th class="letraPequena font-weight-normal">#</th>
					
					<th class="letraPequena font-weight-normal">Clave Grupal</th>
					<th class="letraPequena font-weight-normal">Profesor</th>
					<th class="letraPequena font-weight-normal">Materia</th>
					<th class="letraPequena font-weight-normal">Salón</th>
					<th class="letraPequena font-weight-normal">Lunes</th>
					<th class="letraPequena font-weight-normal">Martes</th>
					<th class="letraPequena font-weight-normal">Miercoles</th>
					<th class="letraPequena font-weight-normal">Jueves</th>
					<th class="letraPequena font-weight-normal">Viernes</th>
					<th class="letraPequena font-weight-normal">Sabado</th>
					<th class="letraPequena font-weight-normal">Domingo</th>
				</tr>
			</thead>

			<tbody>

				<?php
					$i = 1;

					while($filaHorario = mysqli_fetch_assoc($resultadoHorario)){
						$id_sub_hor = $filaHorario['id_sub_hor'];
				?>

					<tr style="height: 60px;">
						<td class="letraPequena font-weight-normal">
							<?php echo $i; $i++;  ?>
						</td>

						
						<td class="letraPequena font-weight-normal">
							<?php echo $filaHorario['nom_sub_hor'].' - '.$filaHorario['id_sub_hor']; ?>
						</td>
						


						<td class="letraPequena font-weight-normal">
							<?php echo $filaHorario['nom_pro']." ".$filaHorario['app_pro']; ?>
						</td>


						<td class="letraPequena font-weight-normal">
							<?php echo $filaHorario['nom_mat']; ?>
						</td>


						<td class="letraPequena font-weight-normal">
							<?php  

								$sqlSalon = "
									SELECT *
									FROM salon
									INNER JOIN sub_hor ON sub_hor.id_sal1 = salon.id_sal
									WHERE id_sub_hor = '$id_sub_hor'
								";

								$resultadoSalon = mysqli_query( $db, $sqlSalon );


								if ( $resultadoSalon ) {
									
									$totalSalon = mysqli_num_rows( $resultadoSalon );

									if ( $totalSalon > 0 ) {
										
										$resultadoSalon2 = mysqli_query( $db, $sqlSalon );

										$filaSalon = mysqli_fetch_assoc( $resultadoSalon2 );

										echo $filaSalon['nom_sal'];


									} else {
										echo "N/A";
									}

								} else {

									echo $sqlSalon;
								
								}
							?>			
						</td>


						


						<?php
							$id_sub_hor = $filaHorario['id_sub_hor'];
							
							//LUNES
							$sqlSubHorLunes = "
								SELECT *
						    	FROM sub_hor
						    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
								WHERE dia_hor = 'Lunes' AND id_sub_hor1 = '$id_sub_hor';
							";

							//echo $sqlSubHor;
							$resultadoSubHorLunes = mysqli_query($db, $sqlSubHorLunes);

							$filasLunes = mysqli_num_rows($resultadoSubHorLunes);

							if ($filasLunes == 0) {
						?>	
							<td class="letraPequena font-weight-normal">--</td>

						<?php
							}else{
								while($filaSubHorLunes = mysqli_fetch_assoc($resultadoSubHorLunes)){
								
								?>
									<td class="letraPequena font-weight-normal">
										<?php 
											echo $filaSubHorLunes['ini_hor']."-".$filaSubHorLunes['fin_hor']; 
										?>
										
									</td>
						

						<?php
								}
							}
								
							//MARTES
							$sqlSubHorMartes = "
								SELECT *
						    	FROM sub_hor
						    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
								WHERE dia_hor = 'Martes' AND id_sub_hor1 = '$id_sub_hor';
							";

							//echo $sqlSubHor;
							$resultadoSubHorMartes = mysqli_query($db, $sqlSubHorMartes);

							$filasMartes = mysqli_num_rows($resultadoSubHorMartes);

							if ($filasMartes == 0) {
						?>	
							<td class="letraPequena font-weight-normal">--</td>

						<?php
							}else{
								while($filaSubHorMartes = mysqli_fetch_assoc($resultadoSubHorMartes)){
								
								?>
										<td class="letraPequena font-weight-normal">
											<?php 
												echo $filaSubHorMartes['ini_hor']."-".$filaSubHorMartes['fin_hor']; 
											?>
											
										</td>
						

						<?php
								}
							}

							//MIERCOLES
							$sqlSubHorMiercoles = "
								SELECT *
						    	FROM sub_hor
						    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
								WHERE dia_hor = 'Miércoles' AND id_sub_hor1 = '$id_sub_hor';
							";

							//echo $sqlSubHor;
							$resultadoSubHorMiercoles = mysqli_query($db, $sqlSubHorMiercoles);

							$filasMiercoles = mysqli_num_rows($resultadoSubHorMiercoles);

							if ($filasMiercoles == 0) {
						?>	
							<td class="letraPequena font-weight-normal">--</td>

						<?php
							}else{
								while($filaSubHorMiercoles = mysqli_fetch_assoc($resultadoSubHorMiercoles)){
								
								?>
										<td class="letraPequena font-weight-normal">
											<?php 
												echo $filaSubHorMiercoles['ini_hor']."-".$filaSubHorMiercoles['fin_hor']; 
											?>
											
										</td>
						

						<?php
								}
							}

							//JUEVES
							$sqlSubHorJueves = "
								SELECT *
						    	FROM sub_hor
						    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
								WHERE dia_hor = 'Jueves' AND id_sub_hor1 = '$id_sub_hor';
							";

							//echo $sqlSubHor;
							$resultadoSubHorJueves = mysqli_query($db, $sqlSubHorJueves);

							$filasJueves = mysqli_num_rows($resultadoSubHorJueves);

							if ($filasJueves == 0) {
						?>	
							<td class="letraPequena font-weight-normal">--</td>

						<?php
							}else{
								while($filaSubHorJueves = mysqli_fetch_assoc($resultadoSubHorJueves)){
								
								?>
										<td class="letraPequena font-weight-normal">
											<?php 
												echo $filaSubHorJueves['ini_hor']."-".$filaSubHorJueves['fin_hor']; 
											?>
											
										</td>
						

						<?php
								}
							}


							//VIERNES
							$sqlSubHorViernes = "
								SELECT *
						    	FROM sub_hor
						    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
								WHERE dia_hor = 'Viernes' AND id_sub_hor1 = '$id_sub_hor';
							";

							//echo $sqlSubHor;
							$resultadoSubHorViernes = mysqli_query($db, $sqlSubHorViernes);

							$filasViernes = mysqli_num_rows($resultadoSubHorViernes);

							if ($filasViernes == 0) {
						?>	
							<td class="letraPequena font-weight-normal">--</td>

						<?php
							}else{
								while($filaSubHorViernes = mysqli_fetch_assoc($resultadoSubHorViernes)){
								
								?>
										<td class="letraPequena font-weight-normal">
											<?php 
												echo $filaSubHorViernes['ini_hor']."-".$filaSubHorViernes['fin_hor']; 
											?>
											
										</td>

						<?php
								}
							}


							//SABADO
							$sqlSubHorSabado = "
								SELECT *
						    	FROM sub_hor
						    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
								WHERE dia_hor = 'Sábado' AND id_sub_hor1 = '$id_sub_hor';
							";

							//echo $sqlSubHor;
							$resultadoSubHorSabado = mysqli_query($db, $sqlSubHorSabado);

							$filasSabado = mysqli_num_rows($resultadoSubHorSabado);

							if ($filasSabado == 0) {
						?>	
							<td class="letraPequena font-weight-normal">--</td>

						<?php
							}else{
								while($filaSubHorSabado = mysqli_fetch_assoc($resultadoSubHorSabado)){
								
								?>
										<td class="letraPequena font-weight-normal">
											<?php 
												echo $filaSubHorSabado['ini_hor']."-".$filaSubHorSabado['fin_hor']; 
											?>
											
										</td>
						

						<?php
								}
							}
								

							//DOMINGO
							$sqlSubHorDomingo = "
								SELECT *
						    	FROM sub_hor
						    	INNER JOIN horario ON sub_hor.id_sub_hor = horario.id_sub_hor1
								WHERE dia_hor = 'Domingo' AND id_sub_hor1 = '$id_sub_hor';
							";

							//echo $sqlSubHor;
							$resultadoSubHorDomingo = mysqli_query($db, $sqlSubHorDomingo);

							$filasDomingo = mysqli_num_rows($resultadoSubHorDomingo);

							if ($filasDomingo == 0) {
						?>	
							<td class="letraPequena font-weight-normal">--</td>

						<?php
							}else{
								while($filaSubHorDomingo = mysqli_fetch_assoc($resultadoSubHorDomingo)){
								
								?>
										<td class="letraPequena font-weight-normal">
											<?php 
												echo $filaSubHorDomingo['ini_hor']."-".$filaSubHorDomingo['fin_hor']; 
											?>
											
										</td>
						

						<?php
								}
							}
								
				
						?>

					</tr>


				<?php

					}
					//FIN WHILE
				?>
				
				
			</tbody>

		</table>
		<!-- FIN TABLA -->
	</div>

<script>

	$('#myTableHorarioAlumno').DataTable().destroy();

	$('#myTableHorarioAlumno').DataTable({
		
	
		dom: 'Bft',
        pageLength: -1,
        buttons: [

            {
                extend: 'excelHtml5',
               	messageTop: 'Horario alumno',
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
	$('#myTableHorarioAlumno_wrapper').find('label').each(function () {
		$(this).parent().append($(this).children());
	});
	$('#myTableHorarioAlumno_wrapper .dataTables_filter').find('input').each(function () {
		$('#myTableHorarioAlumno_wrapper input').attr("placeholder", "Buscar...");
		$('#myTableHorarioAlumno_wrapper input').removeClass('form-control-sm');
	});
	$('#myTableHorarioAlumno_wrapper .dataTables_length').addClass('d-flex flex-row');
	$('#myTableHorarioAlumno_wrapper .dataTables_filter').addClass('md-form');
	$('#myTableHorarioAlumno_wrapper select').removeClass(
	'custom-select custom-select-sm form-control form-control-sm');
	$('#myTableHorarioAlumno_wrapper select').addClass('mdb-select');
	$('#myTableHorarioAlumno_wrapper .mdb-select').materialSelect();
	$('#myTableHorarioAlumno_wrapper .dataTables_filter').find('label').remove();
	var botones = $('#myTableHorarioAlumno_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
	//console.log(botones);
</script>