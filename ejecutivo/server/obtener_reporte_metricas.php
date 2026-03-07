<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$inicio = $_POST['inicio'];
	$fin = $_POST['fin'];
	$filtros = $_POST['filtros'];
?>

<h3>
    REPORTE MÉTRICAS
</h3>

<span class="letraPequena grey-text"><?php echo obtenerTituloReporte($inicio, $fin); ?></span>

<style>
	.table td, .table th {
		padding: 0;
	}
</style>

<hr>

<div class="row">
    <div class="col md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="tabla_metricas">
                
				<thead>
					<tr style="background: black; font-weight: bold; color: white;">
						<th class="text-center">#</th>
						<th class="text-center">CONSULTOR</th>
						<th class="text-center">RANGO</th>
						<?php
							$porcentaje1 = 0;
							$porcentaje2 = 0;

							$indicadores = $filtros["indicadores"];
							foreach ($indicadores as $indicador) {
								if( $indicador == 'CIT AGENDADAS' ){
									echo "<th class='text-center'>$indicador</th>";

								}else if( $indicador == 'CIT TOTALES' ){
									$porcentaje1++;
									echo "<th class='text-center'>$indicador</th>";

								}else if( $indicador == 'CIT EFECTIVAS' ){
									$porcentaje1++;
									$porcentaje2++;
									echo "<th class='text-center'>$indicador</th>";
									if( $porcentaje1 == 2 ){
										echo "<th class='text-center'>% EFECT CIT</th>";
									}
								}else if( $indicador == 'REGISTROS' ){
									$porcentaje2++;
									echo "<th class='text-center'>$indicador</th>";
									if( $porcentaje2 == 2 ){
										echo "<th class='text-center'>% EFECT REG</th>";
									}
								}
							}

							
						?>
					</tr>
				</thead>


				<!--  -->
				<!--  -->
				<tbody>
					<?php
					// Inicializar el array para los totales generales
					$totalesGenerales = array(
						'agendadas' => 0,
						'totales' => 0,
						'efectivas' => 0,
						'registros' => 0
					);

					$cdeArray = $filtros['cde'];

					$cdeValues = implode(',', array_map(function($value) {
						return "'" . $value . "'";
					}, $cdeArray));

					$sqlPlanteles = "
						SELECT *
						FROM plantel
						WHERE id_pla IN ($cdeValues)
					";
					$resultadoPlanteles = mysqli_query( $db, $sqlPlanteles );

					while( $filaPlanteles = mysqli_fetch_assoc( $resultadoPlanteles ) ){
						$id_pla = $filaPlanteles['id_pla'];

						$totalesPlantel = array();
						$totalesPlantel['agendadas'] = 0;
						$totalesPlantel['totales'] = 0;
						$totalesPlantel['efectivas'] = 0;
						$totalesPlantel['registros'] = 0;
					?>
						<tr style="background: grey; color: black; font-weight: bold;">
							<td>--</td>
							<td>🕋 <?php echo $filaPlanteles['nom_pla']; ?></td>
							<td>--</td>
							<?php
								$porcentaje1 = 0;
								$porcentaje2 = 0;
								$indicadores = $filtros["indicadores"];
								foreach ($indicadores as $indicador) {
									echo "<td class='text-center'>--</td>";
									if ($indicador == 'CIT TOTALES' || $indicador == 'CIT EFECTIVAS') {
										$porcentaje1++;
										if ($porcentaje1 == 2) {
											echo "<td class='text-center'>--</td>"; // Para % EFECT 1
										}
									}
									if ($indicador == 'CIT EFECTIVAS' || $indicador == 'REGISTROS') {
										$porcentaje2++;
										if ($porcentaje2 == 2) {
											echo "<td class='text-center'>--</td>"; // Para % EFECT 2
										}
									}
								}
							?>
						</tr>

						<?php
							$rangoMap = array(
								'CONSULTOR JR' => 'Asesor',
								'CONSULTOR MASTER' => 'LC',
								'EJECUTIVO' => 'GR',
								'SALES MANAGER' => 'GC'
							);
							
							$rangosSeleccionados = array();
							$permisosSeleccionados = array();
							$incluirPermisos = true; // Bandera para controlar si se incluyen los permisos
							
							// Procesar filtros de rango
							if (isset($filtros['rango'])) {
								foreach ($filtros['rango'] as $rangoSeleccionado) {
									if (array_key_exists($rangoSeleccionado, $rangoMap)) {
										$rangosSeleccionados[] = "'" . $rangoMap[$rangoSeleccionado] . "'";
									}
								}
							}
							
							// Procesar filtros de permisos
							if (isset($filtros['permisos'])) {
								foreach ($filtros['permisos'] as $permiso) {
									if ($permiso === "AHJ ENDE") {
										$permisosSeleccionados[] = "'2'";
									} elseif ($permiso === "CDE") {
										$permisosSeleccionados[] = "'1'";
									} elseif ($permiso === "N/A") {
										// Si el permiso es "N/A", no se incluirán filtros de permisos
										$incluirPermisos = false;
										break; // Salimos del bucle al encontrar "N/A"
									}
								}
							} else {
								$incluirPermisos = false; // No se incluyen permisos si no se seleccionan
							}
							
							// Construcción de la cláusula WHERE
							$whereClause = "id_pla = '$id_pla' AND eli_eje = 'Activo' AND tip_eje = 'Ejecutivo'";
							
							// Incluir el filtro de rangos si existen rangos seleccionados
							if (count($rangosSeleccionados) > 0) {
								$rangosQuery = implode(', ', $rangosSeleccionados);
								$whereClause .= " AND ran_eje IN ($rangosQuery)";
							}
							
							// Si la bandera para incluir permisos es true y hay permisos seleccionados, se incluyen en el WHERE
							if ($incluirPermisos && count($permisosSeleccionados) > 0) {
								$permisosQuery = implode(', ', $permisosSeleccionados);
								$whereClause .= " AND per_eje IN ($permisosQuery)";
							}
							
							// Si no se seleccionan permisos (N/A), no se agrega ningún filtro para permisos
							// Si se selecciona algún permiso distinto de N/A, se añadirá a la consulta
							
							$sqlEjecutivos = "
								SELECT *
								FROM ejecutivo
								WHERE $whereClause
							";			
							
							$contador = 1;
							$resultadoEjecutivos = mysqli_query($db, $sqlEjecutivos);
							while ($filaEjecutivos = mysqli_fetch_assoc($resultadoEjecutivos)) {
								$id_eje = $filaEjecutivos['id_eje'];
						?>
								<tr style="color: black; background: #EAEEF1;" class="fila-ejecutivo">
									<td><?php echo $contador; ?></td>
									<td>
										<span title="<?php echo $filaEjecutivos['nom_eje']; ?>" class="<?php if ($filaEjecutivos['est_eje'] == 'Inactivo') echo 'text-danger'; ?>">
											<?php echo obtenerPrimerasDosPalabras($filaEjecutivos['nom_eje']); ?>
										</span>
									</td>
									<td>
										<span class="<?php if ($filaEjecutivos['est_eje'] == 'Inactivo') echo 'text-danger'; ?> badge rounded-pill badge-outline-<?php 
												echo $filaEjecutivos['ran_eje'] == 'GC' ? 'dark' : 
													($filaEjecutivos['ran_eje'] == 'GR' ? 'success' : 'primary'); 
											?>">
											<?php echo obtener_rango_usuario($filaEjecutivos['ran_eje']); ?>
										</span>

										<?php
											echo ($filaEjecutivos['per_eje'] == 1) ? '<span class="badge bg-success">Permisos CDE</span>' : 
											(($filaEjecutivos['per_eje'] == 2) ? '<span class="badge bg-success">Permisos AHJ ENDE</span>' : '');
										?>
										
									</td>

									<?php
										$porcentaje1 = 0;
										$porcentaje2 = 0;
										// 
										foreach ($indicadores as $indicador) {
											if ($indicador == 'CIT AGENDADAS') {
												$sql = "SELECT obtener_citas_agendadas_ejecutivo($id_eje, '$inicio', '$fin') AS citas_agendadas";
												$datos = obtener_datos_consulta($db, $sql);
												$citas_agendadas = $datos['datos']['citas_agendadas'];
												$totalesPlantel['agendadas'] += $citas_agendadas;
												echo "<td class='text-center'>".$citas_agendadas."</td>";
											
											} else if ($indicador == 'CIT TOTALES') {
												$sql = "SELECT obtener_citas_ejecutivo($id_eje, '$inicio', '$fin') AS citas_totales";
												$datos = obtener_datos_consulta($db, $sql);
												$citas_totales = $datos['datos']['citas_totales'];
												$totalesPlantel['totales'] += $citas_totales;
												echo "<td class='text-center'>".$citas_totales."</td>";
												
												$porcentaje1++;
												
											} else if ($indicador == 'CIT EFECTIVAS') {
												$sql = "SELECT obtener_citas_efectivas_ejecutivo($id_eje, '$inicio', '$fin') AS citas_efectivas";
												$datos = obtener_datos_consulta($db, $sql);
												$citas_efectivas = $datos['datos']['citas_efectivas'];
												$totalesPlantel['efectivas'] += $citas_efectivas;
												echo "<td class='text-center'>".$citas_efectivas."</td>";
											
												$porcentaje1++;
												$porcentaje2++;
												
												if ($porcentaje1 == 2) {
													// Verificar que citas_totales no sea cero para evitar división por cero
													if ($citas_totales > 0) {
														$efectividad1 = ($citas_efectivas / $citas_totales) * 100;
													} else {
														$efectividad1 = 0;
													}
													echo "<td class='text-center' style='background-color: " . getColorByValue($efectividad1) . "'>" . number_format($efectividad1, 2) . "%</td>";

												}
											
											} else if ($indicador == 'REGISTROS') {
												$sql = "SELECT obtener_registros_ejecutivo($id_eje, '$inicio', '$fin') AS registros";
												$datos = obtener_datos_consulta($db, $sql);
												$registros = $datos['datos']['registros'];
												$totalesPlantel['registros'] += $registros;
												echo "<td class='text-center'>".$registros."</td>";
											
												$porcentaje2++;
												
												if ($porcentaje2 == 2) {
													// Verificar que citas_efectivas no sea cero para evitar división por cero
													if ($citas_efectivas > 0) {
														$efectividad2 = ($registros / $citas_efectivas) * 100;
													} else {
														$efectividad2 = 0;
													}
													echo "<td class='text-center' style='background-color: " . getColorByValue($efectividad2) . "'>" . number_format($efectividad2, 2) . "%</td>";

												}
											}
										}
										
										// 
										
										
									?>
								</tr>
						<?php
								$contador++;
							}
						?>

						<!-- FILA TOTALES POR PLANTEL -->
						<tr style="color: black; background: #d3d3d3; font-weight: bold;" class="fila-ejecutivo">
							<td></td>
							<td></td>
							<td>TOTALES</td>

							<?php
								// 
								$porcentaje1 = 0;
								$porcentaje2 = 0;

								foreach ($indicadores as $indicador) {
									if ($indicador == 'CIT AGENDADAS') {
										echo "<td class='text-center'>" . $totalesPlantel['agendadas'] . "</td>";
										$totalesGenerales['agendadas'] += $totalesPlantel['agendadas'];

									} else if ($indicador == 'CIT TOTALES') {
										echo "<td class='text-center'>" . $totalesPlantel['totales'] . "</td>";
										$totalesGenerales['totales'] += $totalesPlantel['totales'];

										$porcentaje1++;

									} else if ($indicador == 'CIT EFECTIVAS') {
										echo "<td class='text-center'>" . $totalesPlantel['efectivas'] . "</td>";
										$totalesGenerales['efectivas'] += $totalesPlantel['efectivas'];

										$porcentaje1++;
										$porcentaje2++;

										if ($porcentaje1 == 2) {
											// Verificar que el total no sea cero para evitar división por cero
											if ($totalesPlantel['totales'] > 0) {
												$efectividad1 = ($totalesPlantel['efectivas'] / $totalesPlantel['totales']) * 100;
											} else {
												$efectividad1 = 0; // O cualquier valor predeterminado que desees
											}
											echo "<td class='text-center' style='background-color: " . getColorByValue($efectividad1) . "'>" . number_format($efectividad1, 2) . "%</td>";
										}
										

									} else if ($indicador == 'REGISTROS') {
										echo "<td class='text-center'>" . $totalesPlantel['registros'] . "</td>";
										$totalesGenerales['registros'] += $totalesPlantel['registros'];

										$porcentaje2++;

										if ($porcentaje2 == 2) {
											// Verificar que el número de citas efectivas no sea cero para evitar división por cero
											if ($totalesPlantel['efectivas'] > 0) {
												$efectividad2 = ($totalesPlantel['registros'] / $totalesPlantel['efectivas']) * 100;
											} else {
												$efectividad2 = 0; // O cualquier valor predeterminado que desees
											}
											echo "<td class='text-center' style='background-color: " . getColorByValue($efectividad2) . "'>" . number_format($efectividad2, 2) . "%</td>";
										}
										
									}
								}

								// 	
							?>
						</tr>
					<?php
					}
					?>

					<!-- FILA TOTALES GENERALES -->
					<tr style="background: black; color: white; font-weight: bold;">
						
						<td></td>
						<td></td>
						<td>TOTALES GENERALES</td>
						<?php
							$porcentaje1 = 0;
							$porcentaje2 = 0;
							
							foreach ($indicadores as $indicador) {
								if ($indicador == 'CIT AGENDADAS') {
									echo "<td class='text-center'>" . $totalesGenerales['agendadas'] . "</td>";
							
								} else if ($indicador == 'CIT TOTALES') {
									echo "<td class='text-center'>" . $totalesGenerales['totales'] . "</td>";
							
									$porcentaje1++;
							
								} else if ($indicador == 'CIT EFECTIVAS') {
									echo "<td class='text-center'>" . $totalesGenerales['efectivas'] . "</td>";
							
									$porcentaje1++;
									$porcentaje2++;
							
									if ($porcentaje1 == 2) {
										// Verificar que no sea cero para evitar división por cero
										$efectividad1 = $totalesGenerales['totales'] > 0 ? 
											($totalesGenerales['efectivas'] / $totalesGenerales['totales']) * 100 : 0;
										echo "<td class='text-center' style='color: black; background-color: " . getColorByValue($efectividad1) . "'>" . number_format($efectividad1, 2) . "%</td>";
									}
							
								} else if ($indicador == 'REGISTROS') {
									echo "<td class='text-center'>" . $totalesGenerales['registros'] . "</td>";
							
									$porcentaje2++;
							
									if ($porcentaje2 == 2) {
										// Verificar que no sea cero para evitar división por cero
										$efectividad2 = $totalesGenerales['efectivas'] > 0 ? 
											($totalesGenerales['registros'] / $totalesGenerales['efectivas']) * 100 : 0;
										echo "<td class='text-center' style='color: black; background-color: " . getColorByValue($efectividad2) . "'>" . number_format($efectividad2, 2) . "%</td>";
									}
								}
							}
							
						?>
					</tr>
				</tbody>

				<!--  -->
				<!--  -->

				
                
            </table>
        </div>
    </div>
</div>

<script src="../js/jszip.min.js"></script>
<script src="../js/pdfmake.min.js"></script>
<script src="../js/vfs_fonts.js"></script>
<script src="../js/buttons.html5.min.js"></script>
<script src="../js/buttons.print.min.js"></script>
<script src="../js/buttons.colVis.min.js"></script>

<script>
	$('#tabla_metricas').DataTable({
		paging: false,
		searching: true,
		ordering: false,
		dom: 'Bfrtip',
		buttons: [
			{
				extend: 'excelHtml5',
				title: 'REPORTE MÉTRICAS',
				className: 'btn-sm btn-success'
			},
		],
		language: {
			search: 'Buscar'
		},
		info: false
	});
</script>