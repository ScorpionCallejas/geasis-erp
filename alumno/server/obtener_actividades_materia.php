<?php  
	//ARCHIVO VIA AJAX PARA OBTENER ACTIVIDADES EN TIMELINE
	//materias_horario.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');


	$materia = $_POST['materia'];
	$id_alu_ram = $_POST['id_alu_ram'];

	$fechaHoy = date( 'Y-m-d' );


	if ( $materia == 'Todos' ) {
		
		$sqlActividades = "
		    SELECT id_for_cop AS id, nom_for AS actividad, nom_mat AS materia, pun_for AS puntaje, ini_for_cop AS inicio, fin_for_cop AS fin, tip_for AS tipo, pun_cal_act AS calificacion, ret_cal_act AS retroalimentacion, fec_cal_act AS fecha, nom_blo AS bloque, id_blo AS id_blo, id_cal_act AS id_cal_act, CONCAT( nom_pro, ' ', app_pro ) AS nom_pro, fot_emp AS fot_emp
		    FROM alu_ram
		    INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
		    INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
		    INNER JOIN bloque ON bloque.id_mat6 = sub_hor.id_mat1
		    INNER JOIN materia ON materia.id_mat = bloque.id_mat6
		    INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
		    INNER JOIN empleado ON empleado.id_emp = profesor.id_emp3
		    INNER JOIN foro ON foro.id_blo4 = bloque.id_blo
		    INNER JOIN foro_copia ON foro_copia.id_for1 = foro.id_for
		    INNER JOIN cal_act ON cal_act.id_for_cop2 = foro_copia.id_for_cop
		    WHERE id_alu_ram4 = '$id_alu_ram' AND ini_for_cop <= '$fechaHoy' 
		    GROUP BY id
		    UNION
		    SELECT id_ent_cop AS id, nom_ent AS actividad, nom_mat AS materia, pun_ent AS puntaje, ini_ent_cop AS inicio, fin_ent_cop AS fin, tip_ent AS tipo, pun_cal_act AS calificacion, ret_cal_act AS retroalimentacion, fec_cal_act AS fecha, nom_blo AS bloque, id_blo AS id_blo, id_cal_act AS id_cal_act, CONCAT( nom_pro, ' ', app_pro ) AS nom_pro, fot_emp AS fot_emp
		    FROM alu_ram
		    INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
		    INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
		    INNER JOIN bloque ON bloque.id_mat6 = sub_hor.id_mat1
		    INNER JOIN materia ON materia.id_mat = bloque.id_mat6
		    INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
		    INNER JOIN empleado ON empleado.id_emp = profesor.id_emp3
		    INNER JOIN entregable ON entregable.id_blo5 = bloque.id_blo
		    INNER JOIN entregable_copia ON entregable_copia.id_ent1 = entregable.id_ent
		    INNER JOIN cal_act ON cal_act.id_ent_cop2 = entregable_copia.id_ent_cop
		    WHERE id_alu_ram4 = '$id_alu_ram' AND ini_ent_cop <= '$fechaHoy' 
		    GROUP BY id
		    UNION
		    SELECT id_exa_cop AS id, nom_exa AS actividad, nom_mat AS materia, pun_exa AS puntaje, ini_exa_cop AS inicio, fin_exa_cop AS fin, tip_exa AS tipo, pun_cal_act AS calificacion, ret_cal_act AS retroalimentacion, fec_cal_act AS fecha, nom_blo AS bloque, id_blo AS id_blo, id_cal_act AS id_cal_act, CONCAT( nom_pro, ' ', app_pro ) AS nom_pro, fot_emp AS fot_emp
		    FROM alu_ram
		    INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
		    INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
		    INNER JOIN bloque ON bloque.id_mat6 = sub_hor.id_mat1
		    INNER JOIN materia ON materia.id_mat = bloque.id_mat6
		    INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
		    INNER JOIN empleado ON empleado.id_emp = profesor.id_emp3
		    INNER JOIN examen ON examen.id_blo6 = bloque.id_blo
		    INNER JOIN examen_copia ON examen_copia.id_exa1 = examen.id_exa
		    INNER JOIN cal_act ON cal_act.id_exa_cop2 = examen_copia.id_exa_cop
		    WHERE id_alu_ram4 = '$id_alu_ram' AND ini_exa_cop <= '$fechaHoy'
		    GROUP BY id 
		    ORDER BY inicio DESC

		";
		
	} else {

		$sqlActividades = "
		    SELECT id_for_cop AS id, nom_for AS actividad, nom_mat AS materia, pun_for AS puntaje, ini_for_cop AS inicio, fin_for_cop AS fin, tip_for AS tipo, pun_cal_act AS calificacion, ret_cal_act AS retroalimentacion, fec_cal_act AS fecha, nom_blo AS bloque, id_blo AS id_blo, id_cal_act AS id_cal_act, CONCAT( nom_pro, ' ', app_pro ) AS nom_pro, fot_emp AS fot_emp
		    FROM alu_ram
		    INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
		    INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
		    INNER JOIN bloque ON bloque.id_mat6 = sub_hor.id_mat1
		    INNER JOIN materia ON materia.id_mat = bloque.id_mat6
		    INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
		    INNER JOIN empleado ON empleado.id_emp = profesor.id_emp3
		    INNER JOIN foro ON foro.id_blo4 = bloque.id_blo
		    INNER JOIN foro_copia ON foro_copia.id_for1 = foro.id_for
		    INNER JOIN cal_act ON cal_act.id_for_cop2 = foro_copia.id_for_cop
		    WHERE id_alu_ram4 = '$id_alu_ram' AND ini_for_cop <= '$fechaHoy' AND id_sub_hor = '$materia'
		    GROUP BY id
		    UNION
		    SELECT id_ent_cop AS id, nom_ent AS actividad, nom_mat AS materia, pun_ent AS puntaje, ini_ent_cop AS inicio, fin_ent_cop AS fin, tip_ent AS tipo, pun_cal_act AS calificacion, ret_cal_act AS retroalimentacion, fec_cal_act AS fecha, nom_blo AS bloque, id_blo AS id_blo, id_cal_act AS id_cal_act, CONCAT( nom_pro, ' ', app_pro ) AS nom_pro, fot_emp AS fot_emp
		    FROM alu_ram
		    INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
		    INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
		    INNER JOIN bloque ON bloque.id_mat6 = sub_hor.id_mat1
		    INNER JOIN materia ON materia.id_mat = bloque.id_mat6
		    INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
		    INNER JOIN empleado ON empleado.id_emp = profesor.id_emp3
		    INNER JOIN entregable ON entregable.id_blo5 = bloque.id_blo
		    INNER JOIN entregable_copia ON entregable_copia.id_ent1 = entregable.id_ent
		    INNER JOIN cal_act ON cal_act.id_ent_cop2 = entregable_copia.id_ent_cop
		    WHERE id_alu_ram4 = '$id_alu_ram' AND ini_ent_cop <= '$fechaHoy' AND id_sub_hor = '$materia'
		    GROUP BY id 
		    UNION
		    SELECT id_exa_cop AS id, nom_exa AS actividad, nom_mat AS materia, pun_exa AS puntaje, ini_exa_cop AS inicio, fin_exa_cop AS fin, tip_exa AS tipo, pun_cal_act AS calificacion, ret_cal_act AS retroalimentacion, fec_cal_act AS fecha, nom_blo AS bloque, id_blo AS id_blo, id_cal_act AS id_cal_act, CONCAT( nom_pro, ' ', app_pro ) AS nom_pro, fot_emp AS fot_emp
		    FROM alu_ram
		    INNER JOIN alu_hor ON alu_hor.id_alu_ram1 = alu_ram.id_alu_ram
		    INNER JOIN sub_hor ON sub_hor.id_sub_hor = alu_hor.id_sub_hor5
		    INNER JOIN bloque ON bloque.id_mat6 = sub_hor.id_mat1
		    INNER JOIN materia ON materia.id_mat = bloque.id_mat6
		    INNER JOIN profesor ON profesor.id_pro = sub_hor.id_pro1
		    INNER JOIN empleado ON empleado.id_emp = profesor.id_emp3
		    INNER JOIN examen ON examen.id_blo6 = bloque.id_blo
		    INNER JOIN examen_copia ON examen_copia.id_exa1 = examen.id_exa
		    INNER JOIN cal_act ON cal_act.id_exa_cop2 = examen_copia.id_exa_cop
		    WHERE id_alu_ram4 = '$id_alu_ram' AND ini_exa_cop <= '$fechaHoy' AND id_sub_hor = '$materia'
		    GROUP BY id
		    ORDER BY inicio DESC

		";

	}
  

  // echo $sqlActividades;
  $resultadoActividades = mysqli_query($db, $sqlActividades);
  $resultadoActividadesMateria = mysqli_query( $db, $sqlActividades );

  $filaActividadesMateria = mysqli_fetch_assoc( $resultadoActividadesMateria );


?>
	<div class="row text-center animated fadeInUp delay-1s">
		
		<div class="col-md-3">
			<div class="card text-white bg-info mb-3" style="max-width: 20rem;" title="Actividades Totales">
			  <div class="card-header bg-info">Total Actividades</div>
			  <div class="card-body">
			    <h2 class="card-title"><span id="contenedor_registros"></span></h2>
			  </div>
			</div>
		</div>

		<div class="col-md-3">
			<div class="card text-white bg-info mb-3" style="max-width: 20rem;" title="Puntos Totales">
			  <div class="card-header bg-info">Puntos</div>
			  <div class="card-body">
			    <h2 class="card-title"><span id="puntos"></span></h2>
			  </div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card text-white bg-info mb-3" style="max-width: 20rem;" title="Puntos Acumulados">
			  <div class="card-header bg-info">Puntos obtenidos</div>
			  <div class="card-body">
			    <h2 class="card-title"><span id="puntosObtenidos"></span></h2>
			  </div>
			</div>
		</div>

		<div class="col-md-3">
			
			<div class="card text-white bg-info mb-3" style="max-width: 20rem;" title="Porcentaje de aprovechamiento (Un aproximado de tu evaluación online)">
			  <div class="card-header bg-info">Aprovechamiento</div>
			  <div class="card-body">
			    <h2 class="card-title"><span id="porcentaje"></span></h2>
			  </div>
			</div>

	        
		</div>

		




	</div>



	<div class="row animated fadeInDown delay-2s">
		
		<div class="col-md-3">
			<label for="">Filtrado de actividades por estado</label><br>
			<div class="form-check">
	            <input type="checkbox" class="form-check-input checadorEstatusActividad" id="materialChecked666" value="Pendiente" columna="10">
	            <label class="form-check-label letraMediana font-weight-normal" for="materialChecked666">Pendientes</label>
	        </div>

	        <div class="form-check">
	            <input type="checkbox" class="form-check-input checadorEstatusActividad" id="materialChecked777" value="Vencida" columna="10">
	            <label class="form-check-label letraMediana font-weight-normal" for="materialChecked777">Vencidas</label>
	        </div>

	        <div class="form-check">
	            <input type="checkbox" class="form-check-input checadorEstatusActividad" id="materialChecked888" value="Realizada" columna="10">
	            <label class="form-check-label letraMediana font-weight-normal" for="materialChecked888">Realizadas</label>
	        </div>

	        <div class="form-check">
	            <input type="checkbox" class="form-check-input checadorEstatusActividad" id="materialChecked999" value="Calificada" columna="10">
	            <label class="form-check-label letraMediana font-weight-normal" for="materialChecked999">Calificadas</label>
	        </div>
		</div>

		<div class="col-md-4">
			

			<br>
			<br>

			<div class="form-check form-check-inline" title="Selecciona tipo de filtrado">
			  <input type="radio" class="form-check-input columna" id="inicio" columna="4" name="inlineMaterialRadiosExample" checked>
			  <label class="form-check-label letraMediana font-weight-normal" for="inicio">Inicio</label>
			</div>

			<!-- Material inline 2 -->
			<div class="form-check form-check-inline" title="Selecciona tipo de filtrado">
			  <input type="radio" class="form-check-input columna" id="fin" columna="5" name="inlineMaterialRadiosExample">
			  <label class="form-check-label letraMediana font-weight-normal" for="fin">Fin</label>
			</div>

			<!-- Material inline 3 -->
			<div class="form-check form-check-inline" title="Selecciona tipo de filtrado">
			  <input type="radio" class="form-check-input columna" id="realizacion" columna="11" name="inlineMaterialRadiosExample">
			  <label class="form-check-label letraMediana font-weight-normal" for="realizacion">Realización</label>
			</div>
		</div>

		<div class="col-md-5">
			<label for="">Filtrado de actividades por rango de fechas</label><br>
			<div class="md-form mb-2">
	          <input type="date" id="min-date" class="date-range-filter form-control validate" title="Inicio del Rango" style="font-size: 10px;">
	        </div>

			
	        <div class="md-form mb-2">
	          <input type="date" id="max-date" class="date-range-filter form-control validate" title="Fin del Rango" style="font-size: 10px;">
	        </div>
			
		</div>
	</div>



	

	


	<table id="myTable" class="table table-hover table-sm text-left animated fadeInDown table-responsive table-bordered table-striped" cellspacing="0" width="100%">
		<thead class="grey text-white">
			<tr >
				<th class="letraMediana font-weight-normal">#</th>
				<th class="letraMediana font-weight-normal">Actividad</th>
				<th class="letraMediana font-weight-normal">Materia</th>
				<th class="letraMediana font-weight-normal">Bloque</th>
				<th class="letraMediana font-weight-normal">Inicio</th>
				<th class="letraMediana font-weight-normal">Fin</th>
				<th class="letraMediana font-weight-normal">Tipo</th>
				<th class="letraMediana font-weight-normal">Puntos</th>
				<th class="letraMediana font-weight-normal">Puntos Obtenidos</th>
				<th class="letraMediana font-weight-normal">Retroalimentación</th>
				<th class="letraMediana font-weight-normal">Estatus</th>
				<th class="letraMediana font-weight-normal">Fecha de realización</th>
			</tr>
		</thead>


		<?php 
			$i = 1;
			$fechaHoy = date('Y-m-d');

			while($filaActividades = mysqli_fetch_assoc($resultadoActividades)){

		?>

				<?php  
					if ($filaActividades['fecha'] == NULL) {
								
						if ( $fechaHoy > $filaActividades['fin'] ) {
							// echo "Pendiente";
				?>
						<tr class="text-danger">
				<?php
						} else {
							// echo "Vencida";
				?>
						<tr class="text-warning">
				<?php	
						}

					}else{
						if ( $filaActividades['calificacion'] != NULL ) {
							// echo "Calificada";
				?>
						<tr class="text-success">

				<?php
						} else {
							// echo "Realizada";
				?>
						<tr class="text-info">
								
				<?php
						}
						
					}
				?>
					<td class="letraMediana font-weight-normal"><?php echo $i; $i++;?></td>
			
					<td class="letraMediana font-weight-normal">
						<?php  
							if ($filaActividades['fecha'] == NULL) {
								if ( $fechaHoy > $filaActividades['fin'] ) {
							// echo "Pendiente";
						?>


									<?php  
										if ($filaActividades['tipo'] == 'Foro') {
									?>
											<a href="foro.php?id_for_cop=<?php echo $filaActividades['id']."&id_alu_ram=".$id_alu_ram; ?> " target="_blank" class="btn btn-link" title="Foro: <?php echo $filaActividades['actividad']; ?>" >
												<?php echo $filaActividades['actividad']; ?>
											</a>
									<?php
										}else if($filaActividades['tipo'] == 'Entregable'){
									?>		
											<a href="entregable.php?id_ent_cop=<?php echo $filaActividades['id']."&id_alu_ram=".$id_alu_ram; ?> " target="_blank" class="btn btn-link" title="Entregable: <?php echo $filaActividades['actividad']; ?>" >
												<?php echo $filaActividades['actividad']; ?>
											</a>

									<?php
										}else if($filaActividades['tipo'] == 'Examen'){
									?>
											<a href="examen.php?id_exa_cop=<?php echo $filaActividades['id']."&id_alu_ram=".$id_alu_ram; ?> " target="_blank" class="btn btn-link" title="Examen: <?php echo $filaActividades['actividad']; ?>" >
												<?php echo $filaActividades['actividad']; ?>
											</a>

									<?php
										}

									?>		

						<?php
								} else {
									// echo "Vencida";
						?>
									<?php  
										if ($filaActividades['tipo'] == 'Foro') {
									?>
											<a href="foro.php?id_for_cop=<?php echo $filaActividades['id']."&id_alu_ram=".$id_alu_ram; ?> " target="_blank" class="btn btn-link" title="Foro: <?php echo $filaActividades['actividad']; ?>" data-toggle="popover-hover" data-placement="top" title="popover on top" data-content="<?php echo $nombre; ?>, tienes este <?php echo $filaActividades['tipo']; ?> de la materia de <?php echo $filaActividades['materia']; ?> y tienes hasta el <?php echo fechaFormateadacompacta($filaActividades['fin']); ?> para realizarlo.">
												<?php echo $filaActividades['actividad']; ?>
											</a>
									<?php
										}else if($filaActividades['tipo'] == 'Entregable'){
									?>		
											<a href="entregable.php?id_ent_cop=<?php echo $filaActividades['id']."&id_alu_ram=".$id_alu_ram; ?> " target="_blank" class="btn btn-link" title="Entregable: <?php echo $filaActividades['actividad']; ?>" data-toggle="popover-hover" data-placement="top" title="popover on top" data-content="<?php echo $nombre; ?>, adeudas este <?php echo $filaActividades['tipo']; ?> de la materia de <?php echo $filaActividades['materia']; ?> y tienes hasta el <?php echo fechaFormateadacompacta($filaActividades['fin']); ?> para realizarlo.">
												<?php echo $filaActividades['actividad']; ?>
											</a>

									<?php
										}else if($filaActividades['tipo'] == 'Examen'){
									?>
											<a href="examen.php?id_exa_cop=<?php echo $filaActividades['id']."&id_alu_ram=".$id_alu_ram; ?> " target="_blank" class="btn btn-link" title="Examen: <?php echo $filaActividades['actividad']; ?>" data-toggle="popover-hover" data-placement="top" title="popover on top" data-content="<?php echo $nombre; ?>, tienes este <?php echo $filaActividades['tipo']; ?> de la materia de <?php echo $filaActividades['materia']; ?> y tienes hasta el <?php echo fechaFormateadacompacta($filaActividades['fin']); ?> para realizarlo.">
												<?php echo $filaActividades['actividad']; ?>
											</a>

									<?php
										}

									?>

						<?php	
								}
								
							} else {
						?>
								<?php  
									if ($filaActividades['tipo'] == 'Foro') {
								?>
										<a href="foro.php?id_for_cop=<?php echo $filaActividades['id']."&id_alu_ram=".$id_alu_ram; ?> " target="_blank" class="btn btn-link" title="Foro: <?php echo $filaActividades['actividad']; ?>" >
											<?php echo $filaActividades['actividad']; ?>
										</a>
								<?php
									}else if($filaActividades['tipo'] == 'Entregable'){
								?>		
										<a href="entregable.php?id_ent_cop=<?php echo $filaActividades['id']."&id_alu_ram=".$id_alu_ram; ?> " target="_blank" class="btn btn-link" title="Entregable: <?php echo $filaActividades['actividad']; ?>" >
											<?php echo $filaActividades['actividad']; ?>
										</a>

								<?php
									}else if($filaActividades['tipo'] == 'Examen'){
								?>
										<a href="examen.php?id_exa_cop=<?php echo $filaActividades['id']."&id_alu_ram=".$id_alu_ram; ?> " target="_blank" class="btn btn-link" title="Examen: <?php echo $filaActividades['actividad']; ?>" >
											<?php echo $filaActividades['actividad']; ?>
										</a>

								<?php
									}

								?>
						<?php
							}

						?>
									
						

							
					</td>


					<td class="letraMediana font-weight-normal">
						<?php  

							echo $filaActividades['materia'];
						?>
					</td>


					<td class="letraMediana font-weight-normal">
						<?php  

							echo $filaActividades['bloque'];
						?>
					</td>
					
					<td class="letraMediana font-weight-normal">
						<?php
							$inicio = $filaActividades['inicio']; 
							echo fechaFormateadaCompacta($inicio); 
						?>
							
					</td>
					
					<td class="letraMediana font-weight-normal">
						<?php
							$fin = $filaActividades['fin']; 
							echo fechaFormateadaCompacta($fin); 
						?>
							
					</td>
					
					<td class="letraMediana font-weight-normal"><?php echo $filaActividades['tipo']; ?></td>
					<td class="letraMediana font-weight-normal"><?php echo $filaActividades['puntaje']; ?></td>
					<td class="letraMediana font-weight-normal">
						
						<?php  
							if ($filaActividades['calificacion'] == NULL) {
								echo "Pendiente";
							}else{
								echo $filaActividades['calificacion'];
							}

						?>
					</td>


					<td class="letraMediana font-weight-normal">
						
						<?php  
							if ($filaActividades['retroalimentacion'] == NULL) {
								echo "Pendiente";
							}else{
								echo $filaActividades['retroalimentacion'];
							}

						?>
					</td>


					<td class="letraMediana font-weight-normal">
						
						<?php  
							if ($filaActividades['fecha'] == NULL) {
								
								if ( $fechaHoy > $filaActividades['fin'] ) {
									echo "Vencida";
								} else {
									echo "Pendiente";
								}

							}else{
								if ( $filaActividades['calificacion'] != NULL ) {
									echo "Calificada";
								} else {
									echo "Realizada";	
								}
								
							}

						?>
					</td>

					<td class="letraMediana font-weight-normal">
						
						<?php  
							if ($filaActividades['fecha'] == NULL) {
								echo "Pendiente";
							}else{
								$fechaRealizacion = $filaActividades['fecha'];
								echo fechaFormateadaCompacta($fechaRealizacion); 
							}

						?>
					</td>



				</tr>




		<?php
			}
			// FIN WHILE

		?>
	</table>



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
                    }

            ],

			
			"language": {
                            "sProcessing":     "Procesando...",
                            "sLengthMenu":     "Mostrar _MENU_ registros",
                            "sZeroRecords":    "No se encontraron resultados",
                            "sEmptyTable":     "Ningún dato disponible en esta tabla",
                            "sInfo":           "_TOTAL_",
                            "sInfoEmpty":      "0",
                            "sInfoFiltered":   "",
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





		var table = $('#myTable').DataTable();
		var puntos = table.column( 7, {filter:'applied'} ).data().sum();
		var puntosObtenidos = table.column( 8, {filter:'applied'} ).data().sum();

		$("#contenedor_registros").html($("#myTable_info"));

		if (isNaN(puntosObtenidos)) {

			$('#puntos').text(Math.round(puntos, 2));
			$('#puntosObtenidos').text("Pendiente");	
				
		}else{
			
			$('#puntos').text(Math.round(puntos, 2));	
			$('#puntosObtenidos').text(puntosObtenidos);
			var porcentaje = ((table.column(8, {filter: 'applied'}).data().sum())*100)/(table.column(7, {filter: 'applied'}).data().sum());
			if (isNaN(porcentaje)) {
				$("#porcentaje").text("Nulo");
			}else{
				//console.log(Math.round(porcentaje*100)/100);
	    		$("#porcentaje").text("% "+Math.round(porcentaje*100)/100);
			}
	    	
		}


		//ESTATUS ACADEMICO
	    $('.checadorEstatusActividad').on( 'keyup change', function () {
	      var busqueda = [];
	      for(var i = 0; i < $('.checadorEstatusActividad').length; i++){
	        if($('.checadorEstatusActividad').eq(i).prop("checked") == true){
	          //console.log($('.checador1').eq(i).val());
	          if(busqueda=="")
	          {
	            busqueda=$('.checadorEstatusActividad').eq(i).val();   
	          }
	          else
	          {
	            busqueda = busqueda+'|'+$('.checadorEstatusActividad').eq(i).val();    
	          }
	          
	        }
	      }
	      
	      var columna = $(this).attr("columna");
	      if (busqueda != "") {
	        table
	        .columns( columna )
	        .search( busqueda, true, false)
	        .draw();
	      }else{
	        table
	        .columns( columna )
	        .search('')
	        .draw();
	      }
	  
	    });


		table.on('draw', function(){
	    	$("#puntos").text(Math.round(table.column(7, {filter: 'applied'}).data().sum()), 2);
	    	$("#puntosObtenidos").text(table.column(8, {filter: 'applied'}).data().sum());
	    	var porcentaje = ((table.column(8, {filter: 'applied'}).data().sum())*100)/(table.column(7, {filter: 'applied'}).data().sum());
	    	//console.log(Math.round(porcentaje*100)/100);
	    	if (isNaN(porcentaje)) {
				$("#porcentaje").text("Nulo");
			}else{
				//console.log(Math.round(porcentaje*100)/100);
	    		$("#porcentaje").text("% "+Math.round(porcentaje*100)/100);
			}
	    });

		

		//var columna = 3;

	    // Extend dataTables search
		$.fn.dataTable.ext.search.push(

			
		    function fechas( settings, data, dataIndex ) {
		        var min  = $('#min-date').val();
		        var max  = $('#max-date').val();
		        
		        for(var i = 0; i < $(".columna").length; i++){
		        	if ($(".columna")[i].checked == true) {
		        		var columna = $(".columna").eq(i).attr("columna");
		        	}
		        }
		        //console.log(columna);

				var arregloFechas = moment(data[columna] || 0,"DD/MM/YYYY").format("YYYY-MM-DD"); 
		        // Our date column in the table
		        //console.log(moment(arregloFechas).isValid());


		        if  ( 
		                ( min == "" || max == "" )
		                || 
		                ( moment(arregloFechas).isSameOrAfter(min) && 
		                  moment(arregloFechas).isSameOrBefore(max))
		            )
		        {
		            return true;
		        }
		        return false;
		    }
		);

		// Re-draw the table when the a date range filter changes
		$('.date-range-filter').change( function() {
		    table.draw();

		});

		// Re-draw the table when the radio buttons change
		$('.columna').change( function() {
		    table.draw();

		});


	
	});

	$('[data-toggle="popover-hover"]').popover({
	  html: true,
	  trigger: 'hover',
	  placement: 'bottom',
	  content: function () { return '<img src="' + $(this).data('img') + '" />'; }
	});
</script>