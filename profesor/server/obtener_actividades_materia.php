<?php  
	//ARCHIVO VIA AJAX PARA OBTENER ACTIVIDADES
	//materias_horario.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');



	$sqlActividades = "
		SELECT id_for_cop AS id, nom_for AS actividad, nom_mat AS materia, pun_for AS puntaje, ini_for_cop AS inicio, fin_for_cop AS fin, tip_for AS tipo, nom_pro AS profesor, nom_blo AS bloque, nom_gru AS grupo, nom_cic AS ciclo, nom_ram AS carrera, nom_sub_hor AS nom_sub_hor
		FROM sub_hor
		INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
		INNER JOIN foro_copia ON foro_copia.id_sub_hor2 = sub_hor.id_sub_hor
		INNER JOIN foro ON foro.id_for = foro_copia.id_for1
		INNER JOIN bloque ON bloque.id_blo = foro.id_blo4
		INNER JOIN materia ON materia.id_mat = bloque.id_mat6
		INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
		INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
		INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
		WHERE id_pro1 = '$id'
		UNION
		SELECT id_ent_cop AS id, nom_ent AS actividad, nom_mat AS materia, pun_ent AS puntaje, ini_ent_cop AS inicio, fin_ent_cop AS fin, tip_ent AS tipo, nom_pro AS profesor, nom_blo AS bloque, nom_gru AS grupo, nom_cic AS ciclo, nom_ram AS carrera, nom_sub_hor AS nom_sub_hor
		FROM sub_hor
		INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
		INNER JOIN entregable_copia ON entregable_copia.id_sub_hor3 = sub_hor.id_sub_hor
		INNER JOIN entregable ON entregable.id_ent = entregable_copia.id_ent1
		INNER JOIN bloque ON bloque.id_blo = entregable.id_blo5
		INNER JOIN materia ON materia.id_mat = bloque.id_mat6
		INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
		INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
		INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
		WHERE id_pro1 = '$id'
		UNION
		SELECT id_exa_cop AS id, nom_exa AS actividad, nom_mat AS materia, pun_exa AS puntaje, ini_exa_cop AS inicio, fin_exa_cop AS fin, tip_exa AS tipo, nom_pro AS profesor, nom_blo AS bloque, nom_gru AS grupo, nom_cic AS ciclo, nom_ram AS carrera, nom_sub_hor AS nom_sub_hor
		FROM sub_hor
		INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
		INNER JOIN examen_copia ON examen_copia.id_sub_hor4 = sub_hor.id_sub_hor
		INNER JOIN examen ON examen.id_exa = examen_copia.id_exa1
		INNER JOIN bloque ON bloque.id_blo = examen.id_blo6
		INNER JOIN materia ON materia.id_mat = bloque.id_mat6
		INNER JOIN grupo ON grupo.id_gru = sub_hor.id_gru1
		INNER JOIN ciclo ON ciclo.id_cic = grupo.id_cic1
		INNER JOIN rama ON rama.id_ram = ciclo.id_ram1
		WHERE id_pro1 = '$id'
		ORDER BY inicio ASC

	";
	$resultadoActividades = mysqli_query($db, $sqlActividades);
	//echo $sqlActividades;

	$resultadoTotalActividades = mysqli_query($db, $sqlActividades);


	$totalActividades = mysqli_num_rows($resultadoTotalActividades);


?>


	<div class="card text-white bg-info mb-3" style="max-width: 20rem;">
	  <div class="card-header bg-info text-center">Total Actividades</div>
	  <div class="card-body">
	    <h2 class="card-title text-center">
	    	<span id="puntos">
	    		<?php echo $totalActividades; ?>
	    	</span>
	    </h2>
	  </div>
	</div>


	<table id="myTableActividades" class="table table-hover table-sm text-left animated fadeInDown table-responsive" cellspacing="0" width="100%">
		<thead class="bg-info text-white">
			<tr>
				<th class="letraPequena">#</th>
				<th class="letraPequena">Actividad</th>
				<th class="letraPequena">Carrera</th>
				<th class="letraPequena">Ciclo</th>
				<th class="letraPequena">Grupo</th>
				<th class="letraPequena">Clave</th>
				<th class="letraPequena">Materia</th>
				<th class="letraPequena">Bloque</th>
				<th class="letraPequena">Inicio</th>
				<th class="letraPequena">Fin</th>
				<th class="letraPequena">Tipo</th>
				<th class="letraPequena">Puntos</th>
				<th class="letraPequena">Participación</th>
			</tr>
		</thead>


		<?php 
			$i = 1;
			while($filaActividades = mysqli_fetch_assoc($resultadoActividades)){

		?>
			<tr>
				<td class="letraPequena"><?php echo $i; $i++;?></td>
		
				<td class="letraPequena">
					
					<?php  
						if ($filaActividades['tipo'] == 'Foro') {
					?>
							<a href="foro.php?id_for_cop=<?php echo $filaActividades['id']; ?> " target="_blank" class="btn btn-link letraPequena" title="Foro: <?php echo $filaActividades['actividad']; ?>">
								<?php echo $filaActividades['actividad']; ?>
							</a>
					<?php
						}else if($filaActividades['tipo'] == 'Entregable'){
					?>		
							<a href="entregable.php?id_ent_cop=<?php echo $filaActividades['id']; ?> " target="_blank" class="btn btn-link letraPequena" title="Entregable: <?php echo $filaActividades['actividad']; ?>">
								<?php echo $filaActividades['actividad']; ?>
							</a>

					<?php
						}else if($filaActividades['tipo'] == 'Examen'){
					?>
							<a href="examen.php?id_exa_cop=<?php echo $filaActividades['id']; ?> " target="_blank" class="btn btn-link letraPequena" title="Examen: <?php echo $filaActividades['actividad']; ?>">
								<?php echo $filaActividades['actividad']; ?>
							</a>

					<?php
						}

					?>					
					

						
				</td>

				<td class="letraPequena">
					<?php  

						echo $filaActividades['carrera'];
					?>
				</td>

				<td class="letraPequena">
					<?php  

						echo $filaActividades['ciclo'];
					?>
				</td>

				<td class="letraPequena">
					<?php  

						echo $filaActividades['grupo'];
					?>
				</td>

				<td class="letraPequena">
					<?php  

						echo $filaActividades['nom_sub_hor'];
					?>
				</td>


				<td class="letraPequena">
					<?php  

						echo $filaActividades['materia'];
					?>
				</td>


				<td class="letraPequena">
					<?php
						

						echo $filaActividades['bloque'];
					?>
				</td>
				
				<td class="letraPequena">
					<?php
						$inicio = $filaActividades['inicio']; 
						echo fechaFormateadaCompacta($inicio); 
					?>
						
				</td>
				
				<td class="letraPequena">
					<?php
						$fin = $filaActividades['fin']; 
						echo fechaFormateadaCompacta($fin); 
					?>
						
				</td>
				
				<td class="letraPequena"><?php echo $filaActividades['tipo']; ?></td>
				<td class="letraPequena"><?php echo $filaActividades['puntaje']; ?></td>
				
				<td class="letraPequena">
					<!-- INDICADOR DE PARTICIPACION PORCENTUAL -->
					<?php  

						$identificador = $filaActividades['id'];
						if ($filaActividades['tipo'] == 'Foro') {

							$sqlForosAlumnosParticipantes = "
								SELECT * 
								FROM cal_act 
								INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4 
								WHERE id_for_cop2 = '$identificador' AND fec_cal_act != 'NULL'

							";

							$resultadoAlumnosParticipantes = mysqli_query($db, $sqlForosAlumnosParticipantes);

							$alumnosParticipantes = mysqli_num_rows($resultadoAlumnosParticipantes);



							$sqlForosAlumnos = "
								SELECT * 
								FROM cal_act 
								INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4 
								WHERE id_for_cop2 = '$identificador'

							";

							$resultadoAlumnos = mysqli_query($db, $sqlForosAlumnos);

							$alumnos = mysqli_num_rows($resultadoAlumnos);
							if($alumnos>0){
								echo round(100*($alumnosParticipantes/$alumnos), 2)."%";
								}
								else{
									echo "0,00%";
								}




					?>
							

							
					<?php
						}else if($filaActividades['tipo'] == 'Entregable'){
							$sqlEntregablesAlumnosParticipantes = "
								SELECT * 
								FROM cal_act 
								INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4 
								WHERE id_ent_cop2 = '$identificador' AND fec_cal_act != 'NULL'

							";

							$resultadoAlumnosParticipantes = mysqli_query($db, $sqlEntregablesAlumnosParticipantes);

							$alumnosParticipantes = mysqli_num_rows($resultadoAlumnosParticipantes);



							$sqlEntregablesAlumnos = "
								SELECT * 
								FROM cal_act 
								INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4 
								WHERE id_ent_cop2 = '$identificador'

							";

							$resultadoAlumnos = mysqli_query($db, $sqlEntregablesAlumnos);

							$alumnos = mysqli_num_rows($resultadoAlumnos);
							if ($alumnos>0) {
								echo round(100*($alumnosParticipantes/$alumnos), 2)."%";
							}
							else{
								echo "0,00%";
							}
							
					?>		
							

					<?php
						}else if($filaActividades['tipo'] == 'Examen'){
							$sqlExamenesAlumnosParticipantes = "
								SELECT * 
								FROM cal_act 
								INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4 
								WHERE id_exa_cop2 = '$identificador' AND fec_cal_act != 'NULL'

							";

							$resultadoAlumnosParticipantes = mysqli_query($db, $sqlExamenesAlumnosParticipantes);

							$alumnosParticipantes = mysqli_num_rows($resultadoAlumnosParticipantes);



							$sqlExamenesAlumnos = "
								SELECT * 
								FROM cal_act 
								INNER JOIN alu_ram ON alu_ram.id_alu_ram = cal_act.id_alu_ram4 
								WHERE id_exa_cop2 = '$identificador'

							";

							$resultadoAlumnos = mysqli_query($db, $sqlExamenesAlumnos);

							$alumnos = mysqli_num_rows($resultadoAlumnos);
							if ($alumnos>0) {
								echo "%".round(100*($alumnosParticipantes/$alumnos), 2);
							}
							else{
								echo "0,00%";
							}
							
					?>
							

					<?php
						}

					?>					
					

						
				</td>

			</tr>


		<?php
			} 

		?>
	</table>



<script>
	$(document).ready(function () {
		$.fn.dataTable.ext.search.pop();

		
		$('#myTableActividades').DataTable({
			
		
			dom: 'frtlip',
            
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
		$('#myTableActividades_wrapper').find('label').each(function () {
			$(this).parent().append($(this).children());
		});
		$('#myTableActividades_wrapper .dataTables_filter').find('input').each(function () {
			$('#myTableActividades_wrapper input').attr("placeholder", "Buscar...");
			$('#myTableActividades_wrapper input').removeClass('form-control-sm');
		});
		$('#myTableActividades_wrapper .dataTables_length').addClass('d-flex flex-row');
		$('#myTableActividades_wrapper .dataTables_filter').addClass('md-form');
		$('#myTableActividades_wrapper select').removeClass(
		'custom-select custom-select-sm form-control form-control-sm');
		$('#myTableActividades_wrapper select').addClass('mdb-select');
		$('#myTableActividades_wrapper .mdb-select').materialSelect();
		$('#myTableActividades_wrapper .dataTables_filter').find('label').remove();
		var botones = $('#myTableActividades_wrapper .dt-buttons').children().addClass('btn btn-info btn-sm waves-effect');
		//console.log(botones);

	
	});
</script>


<script>
	// var table = $('#myTableActividades').DataTable();
	// var puntos = table.column( 6, {filter:'applied'} ).data().sum();
	// var puntosObtenidos = table.column( 7 ).data().sum();



	// $('#puntos').text(puntos);
	// $('#puntosObtenidos').text(puntosObtenidos);		


	// table.on('draw', function(){
 //    	$("#puntos").text(table.column(6,{filter: 'applied'}).data().sum());
 //    	$("#puntosObtenidos").text(table.column(7,{filter: 'applied'}).data().sum());
    	
 //    });
</script>