<?php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	$inicio = $_POST['inicio'];
	$fin = $_POST['fin'];

	// echo '$inicio: '.$inicio;
	// echo '$fin: '.$fin;
	$id_eje = $_POST['id_eje'];
	//fechaDia( $fecha );
	$escala = $_POST['escala'];

	// echo 'inicio: '.$inicio.'---- fin: '.$fin;
?>

<style>
	.custom-cell{
		background-color: yellow;
	}

</style>

<div class="controls">
	<button id="export-file" class="btn btn-success btn-sm letraPequena"><i class="fas fa-file-excel"></i> excel</button>
</div>
<div id="data-sheet" class="hot" data-theme="dark"></div>

<script type="text/javascript">

	moment.locale('es');
	toastr.options = {
		"closeButton": false,
		"debug": false,
		"newestOnTop": false,
		"progressBar": false,
		"positionClass": "toast-bottom-center",
		"preventDuplicates": false,
		"onclick": null,
		"showDuration": "300",
		"hideDuration": "1000",
		"timeOut": "5000",
		"extendedTimeOut": "1000",
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut"
	}

	var container = document.querySelector('#data-sheet');

	// PASO 1
	// CAMBIO DE HEADERS
	var colHeaders = [ '<?php echo strtoupper(fechaFormateadaCompacta2($inicio)); ?>', 'HORARIO', 'FECHA', 'CDE AGENDO', 'CDE CONSULTOR', 'CDE DESTINO', 'AGENDO', 'EJECUTIVO', 'CONSULTOR', 'TIPO DE CITA', 'NOMBRE', 'EDAD', 'NUMERO', 'MODALIDAD', 'MERCADO', 'ESTATUS', 'CALIDAD DE CITA', 'OBSERVACIONES', 'FOLIO', 'ID_ALU', 'BOOL1', 'BOOL2', 'CAM_CIT' ];
	// PASO 2 
	// *RELLENO DE COLS VACIAS (ES DINÁMICO NO LE MUEVAS, CABRON :D)
	var data = Array(0).fill(0).map(() => new Array(colHeaders.length).fill(""));

	
<?php
	
	$startTime = strtotime('9:00 AM');
	$endTime = strtotime('8:00 PM');
	$timeIncrement = 60 * 60;

	$data = []; // Para almacenar todos los datos incluyendo horarios y citas
	
	while ($startTime <= $endTime) {
		$currentHour = date('H:i', $startTime);
		$nextHour = date('H:i', $startTime + $timeIncrement);

		// PASO 3
		// *RELLENO DE COLS VACIAS EN HORARIOS
		// *RELLENO DE COLS VACIAS EN HORARIOS
		$data[] = [horaFormateadaCompacta2($currentHour), "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", 	"--", "--", "--", "--"];
		
		// //////////////
		
		// PASO 4 
		// *ADICIÓN DE NUEVA COLUMNA EN SQL PERO ES OPCIONAL 

		if(isset($_POST['palabra']) && !empty($_POST['palabra'])) {
			$palabra = $_POST['palabra'];
			
			// Validación de permisos igual que en el flujo normal
			$sqlPlanteles = "
				SELECT *
				FROM planteles_ejecutivo
				INNER JOIN plantel ON plantel.id_pla = planteles_ejecutivo.id_pla
				WHERE id_eje = '$id'
			";
		
			$totalValidacion = obtener_datos_consulta($db, $sqlPlanteles)['total'];
		
			if($totalValidacion == 0) {
				$sql = "
					SELECT *,
					(IF((SELECT COUNT(id_alu) FROM alumno WHERE id_cit1 = cita.id_cit) > 0, 'true', 'false')) AS res,
					ejecutivo.nom_eje AS nom_eje,
					ejecutivo_cerrador.nom_eje AS nom_eje_cerrador,
					ejecutivo_agendo.nom_eje AS nom_eje_agendo
					FROM cita
					INNER JOIN ejecutivo ON ejecutivo.id_eje = cita.id_eje3
					LEFT JOIN ejecutivo AS ejecutivo_cerrador ON ejecutivo_cerrador.id_eje = cita.id_eje_cerrador
					LEFT JOIN ejecutivo AS ejecutivo_agendo ON ejecutivo_agendo.id_eje = cita.id_eje_agendo
					WHERE cla_cit = 'Cita' AND ejecutivo.id_pla = '$plantel'
					AND hor_cit >= '$currentHour' AND hor_cit < '$nextHour'
					AND (cita.id_cit LIKE '%$palabra%' 
						OR LOWER(cita.nom_cit) LIKE LOWER('%$palabra%')  
						OR cita.tel_cit LIKE '%$palabra%')
					AND YEAR(cit_cit) >= 2024
				";
			} else {
				$resultadoPlanteles = mysqli_query($db, $sqlPlanteles);
		
				$contador = 0;
				$sqlEjecutivos = '';
				while($filaPlanteles = mysqli_fetch_assoc($resultadoPlanteles)) {
					if ($contador > 0) {
						$sqlEjecutivos .= ' OR ';
					}
					$sqlEjecutivos .= '(ejecutivo.id_pla = '.$filaPlanteles['id_pla'].')';
					$contador++;
				}
		
				$sqlEjecutivos = $sqlEjecutivos." ) ";
		
				$sql = "
					SELECT *,
					(IF((SELECT COUNT(id_alu) FROM alumno WHERE id_cit1 = cita.id_cit) > 0, 'true', 'false')) AS res,
					ejecutivo.nom_eje AS nom_eje,
					ejecutivo_cerrador.nom_eje AS nom_eje_cerrador,
					ejecutivo_agendo.nom_eje AS nom_eje_agendo
					FROM cita
					INNER JOIN ejecutivo ON ejecutivo.id_eje = cita.id_eje3
					LEFT JOIN ejecutivo AS ejecutivo_cerrador ON ejecutivo_cerrador.id_eje = cita.id_eje_cerrador
					LEFT JOIN ejecutivo AS ejecutivo_agendo ON ejecutivo_agendo.id_eje = cita.id_eje_agendo
					WHERE cla_cit = 'Cita' AND (
				";
				$sql .= $sqlEjecutivos;
		
				$sql .= " AND hor_cit >= '$currentHour' AND hor_cit < '$nextHour'
					AND YEAR(cit_cit) >= 2024
					AND (cita.id_cit LIKE '%$palabra%' 
						OR LOWER(cita.nom_cit) LIKE LOWER('%$palabra%')  
						OR cita.tel_cit LIKE '%$palabra%')";
			}
		} else {
			// 
			if($escala == 'plantel') {
				$id_pla = $_POST['id_pla'];
			
				$sqlEjecutivosPlantel = "
					SELECT * FROM ejecutivo WHERE id_pla = '$id_pla' AND eli_eje = 'Activo' AND tip_eje = 'Ejecutivo'
				";
			
				$resultadoEjecutivosPlantel = mysqli_query($db, $sqlEjecutivosPlantel);
			
				$contador = 0;
				$sqlEjecutivosAdicion = 'AND ( ';
				while($filaEjecutivosPlantel = mysqli_fetch_assoc($resultadoEjecutivosPlantel)) {
					if ($contador > 0) {
						$sqlEjecutivosAdicion .= ' OR ';
					}
					$sqlEjecutivosAdicion .= 'id_eje3 = '.$filaEjecutivosPlantel['id_eje'];
					$contador++;
				}
				$sqlEjecutivosAdicion = $sqlEjecutivosAdicion." ) ";
			
				$sql = "
					SELECT *,
					(IF((SELECT COUNT(id_alu) FROM alumno WHERE id_cit1 = cita.id_cit) > 0, 'true', 'false')) AS res,
					ejecutivo.nom_eje AS nom_eje,
					ejecutivo_cerrador.nom_eje AS nom_eje_cerrador,
					ejecutivo_agendo.nom_eje AS nom_eje_agendo
					FROM cita
					INNER JOIN ejecutivo ON ejecutivo.id_eje = cita.id_eje3
					LEFT JOIN ejecutivo AS ejecutivo_cerrador ON ejecutivo_cerrador.id_eje = cita.id_eje_cerrador
					LEFT JOIN ejecutivo AS ejecutivo_agendo ON ejecutivo_agendo.id_eje = cita.id_eje_agendo
					WHERE cla_cit = 'Cita' AND DATE(cit_cit) BETWEEN DATE('$inicio') AND DATE('$fin')
				";
				$sql .= $sqlEjecutivosAdicion;
				
				$sql .= " AND hor_cit >= '$currentHour' AND hor_cit < '$nextHour'";
			} else {
				if ( $id_eje == 'Todos' ) {
					$sqlPlanteles = "
						SELECT *
						FROM planteles_ejecutivo
						INNER JOIN plantel ON plantel.id_pla = planteles_ejecutivo.id_pla
						WHERE id_eje = '$id'
					";
			
					$totalValidacion = obtener_datos_consulta( $db, $sqlPlanteles )['total'];
			
					if( $totalValidacion == 0 ){
						$sql = "
							SELECT *,
							(IF((SELECT COUNT(id_alu) FROM alumno WHERE id_cit1 = cita.id_cit) > 0, 'true', 'false')) AS res,
							ejecutivo.nom_eje AS nom_eje,
							ejecutivo_cerrador.nom_eje AS nom_eje_cerrador,
							ejecutivo_agendo.nom_eje AS nom_eje_agendo
							FROM cita
							INNER JOIN ejecutivo ON ejecutivo.id_eje = cita.id_eje3
							LEFT JOIN ejecutivo AS ejecutivo_cerrador ON ejecutivo_cerrador.id_eje = cita.id_eje_cerrador
							LEFT JOIN ejecutivo AS ejecutivo_agendo ON ejecutivo_agendo.id_eje = cita.id_eje_agendo
							WHERE cla_cit = 'Cita' AND ejecutivo.id_pla = '$plantel' 
							AND DATE(cit_cit) BETWEEN DATE('$inicio') AND DATE('$fin') 
							AND hor_cit >= '$currentHour' AND hor_cit < '$nextHour'
						";
					} else {
						$resultadoPlanteles = mysqli_query( $db, $sqlPlanteles );
			
						$contador = 0;
						$sqlEjecutivos = '';
						while($filaPlanteles = mysqli_fetch_assoc($resultadoPlanteles)) {
							if ($contador > 0) {
								$sqlEjecutivos .= ' OR ';
							}
							$sqlEjecutivos .= '(ejecutivo.id_pla = '.$filaPlanteles['id_pla'].')';
							$contador++;
						}
			
						$sqlEjecutivos = $sqlEjecutivos." ) ";
			
						$sql = "
							SELECT *,
							(IF((SELECT COUNT(id_alu) FROM alumno WHERE id_cit1 = cita.id_cit) > 0, 'true', 'false')) AS res,
							ejecutivo.nom_eje AS nom_eje,
							ejecutivo_cerrador.nom_eje AS nom_eje_cerrador,
							ejecutivo_agendo.nom_eje AS nom_eje_agendo
							FROM cita
							INNER JOIN ejecutivo ON ejecutivo.id_eje = cita.id_eje3
							LEFT JOIN ejecutivo AS ejecutivo_cerrador ON ejecutivo_cerrador.id_eje = cita.id_eje_cerrador
							LEFT JOIN ejecutivo AS ejecutivo_agendo ON ejecutivo_agendo.id_eje = cita.id_eje_agendo
							WHERE cla_cit = 'Cita' AND DATE(cit_cit) BETWEEN DATE('$inicio') AND DATE('$fin') AND (
						";
						$sql .= $sqlEjecutivos;
			
						$sql .= " AND hor_cit >= '$currentHour' AND hor_cit < '$nextHour'";
					}
			
				} else {

					// echo 'individual';
					$sql = "
						SELECT *,
						(IF((SELECT COUNT(id_alu) FROM alumno WHERE id_cit1 = cita.id_cit) > 0, 'true', 'false')) AS res,
						ejecutivo.nom_eje AS nom_eje,
						ejecutivo_cerrador.nom_eje AS nom_eje_cerrador,
						ejecutivo_agendo.nom_eje AS nom_eje_agendo
						FROM cita
						INNER JOIN ejecutivo ON ejecutivo.id_eje = cita.id_eje3
						LEFT JOIN ejecutivo AS ejecutivo_cerrador ON ejecutivo_cerrador.id_eje = cita.id_eje_cerrador
						LEFT JOIN ejecutivo AS ejecutivo_agendo ON ejecutivo_agendo.id_eje = cita.id_eje_agendo
						WHERE cla_cit = 'Cita' AND ( id_eje3 = '$id_eje' OR id_eje_agendo = '$id_eje' )
						AND DATE(cit_cit) BETWEEN DATE('$inicio') AND DATE('$fin') 
						AND hor_cit >= '$currentHour' AND hor_cit < '$nextHour'
					";
				}
			}
			// 
		}

		
		// Agregar ORDER BY al final
		$sql .= " ORDER BY hor_cit ASC";
		
		$citas = [];
		
		if( $rangoUsuario == 'TL' ){
			$escala = 'estructura';
		}

		if( ( $escala != '' && $escala == 'estructura' ) ){

			// echo 'entré escala';
			$citas = obtener_tabla_estructura_citas($id_eje, $inicio, $fin, $currentHour, $nextHour, $db, $citas);
		}

		// echo $citas;
		$resultado = mysqli_query($db, $sql);
		

		while ($fila = mysqli_fetch_assoc($resultado)) {
			$citas[] = $fila;
		}

		// 
		if (!empty($citas)) {
			foreach ($citas as $fila) {
				// VALIDACION 
				$id_cit = $fila['id_cit'];
				$validadorRegistro = 'false';
				$id_alu_ram_aux = 'false';
		
				// VALIDACION EXISTENCIA id_alu PERO NO id_alu_ram
				if($fila['res'] == 'true') {
					$sqlAlumnoRama = "
						SELECT *
						FROM alu_ram
						INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
						WHERE id_cit1 = $id_cit
					";
					$datosAlumnoRama = obtener_datos_consulta($db, $sqlAlumnoRama);
					
					if($datosAlumnoRama['total'] > 0) {
						$validadorRegistro = 'true';
						$id_alu_ram_aux = $datosAlumnoRama['datos']['id_alu_ram'];
					}
				}
				// F VALIDACION EXISTENCIA id_alu PERO NO id_alu_ram
				
				// F VALIDACION
				
				// Obtener los planteles usando la función MySQL
				$plantel_agendo = "--";
				$plantel_consultor = "--";
				$plantel_destino = "--";
		
				if(isset($fila['id_eje_agendo']) && !empty($fila['id_eje_agendo'])) {
					$query_agendo = "SELECT obtener_plantel_ejecutivo({$fila['id_eje_agendo']}) as plantel_agendo";
					$result_agendo = mysqli_query($db, $query_agendo);
					if($result_agendo && mysqli_num_rows($result_agendo) > 0) {
						$plantel_row = mysqli_fetch_assoc($result_agendo);
						$plantel_agendo = $plantel_row['plantel_agendo'];
					}
				}
		
				if(isset($fila['id_eje3']) && !empty($fila['id_eje3'])) {
					$query_consultor = "SELECT obtener_plantel_ejecutivo({$fila['id_eje3']}) as plantel_consultor";
					$result_consultor = mysqli_query($db, $query_consultor);
					if($result_consultor && mysqli_num_rows($result_consultor) > 0) {
						$plantel_row = mysqli_fetch_assoc($result_consultor);
						$plantel_consultor = $plantel_row['plantel_consultor'];
					}
				}
		
				// Obtener el plantel de destino si la cita está asociada a un alumno
				if(isset($fila['id_cit']) && !empty($fila['id_cit'])) {
					$query_destino = "SELECT obtener_plantel_destino_alumno({$fila['id_cit']}) as plantel_destino";
					$result_destino = mysqli_query($db, $query_destino);
					if($result_destino && mysqli_num_rows($result_destino) > 0) {
						$plantel_row = mysqli_fetch_assoc($result_destino);
						if(!empty($plantel_row['plantel_destino'])) {
							$plantel_destino = $plantel_row['plantel_destino'];
						}
					}
				}
		
				$data[] = [
					"--", // 0
					horaFormateadaCompacta2($fila['hor_cit']), // 1
					fechaFormateadaCompacta($fila['cit_cit']), // 2
					$plantel_agendo, // nueva columna CDE AGENDO
					$plantel_consultor, // nueva columna CDE CONSULTOR
					$plantel_destino, // nueva columna CDE DESTINO
					isset($fila['nom_eje_agendo']) ? $fila['nom_eje_agendo'] : null, // ahora es 6
					isset($fila['nom_eje_cerrador']) ? $fila['nom_eje_cerrador'] : null, // ahora es 7
					isset($fila['nom_eje']) ? $fila['nom_eje'] : null, // ahora es 8
					isset($fila['tip_cit']) ? $fila['tip_cit'] : null, // ahora es 9
					$fila['nom_cit'], // ahora es 10
					isset($fila['eda_cit']) ? $fila['eda_cit'] : null, // ahora es 11
					isset($fila['tel_cit']) ? $fila['tel_cit'] : null, // ahora es 12
					isset($fila['pro_cit']) ? $fila['pro_cit'] : null, // ahora es 13
					isset($fila['can_cit']) ? $fila['can_cit'] : null, // ahora es 14
					$fila['est_cit'], // ahora es 15
					isset($fila['efe_cit']) ? $fila['efe_cit'] : null, // ahora es 16
					isset($fila['obs_cit']) ? $fila['obs_cit'] : null, // ahora es 17
					$fila['id_cit'], // ahora es 18
					$fila['res'], // ahora es 19
					$validadorRegistro, // ahora es 20
					$id_alu_ram_aux, // ahora es 21
					isset($fila['cam_cit']) ? $fila['cam_cit'] : null, // ahora es 22
					"--" // ahora es 23
				];
			} // Cerrar el foreach después de procesar todas las citas
			
			// Añadir las filas adicionales después del foreach, no dentro de él
			$data[] = ["--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", horaFormateadaCompacta2($currentHour), "--", "--"];
			$data[] = ["--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", horaFormateadaCompacta2($currentHour), "--", "--"];
		} else {
			// RELLENO DE FILAS VACÍAS
			for ($i = 0; $i < 4; $i++) {
				$data[] = ["--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", horaFormateadaCompacta2($currentHour), "--", "--"];
			}
		}
		// 

		// Incrementar startTime para el próximo intervalo
		$startTime += $timeIncrement;

	}

	$json_data = json_encode($data);
?>
	var data = <?php echo $json_data; ?>

	var hot;  // Declaración al inicio del script o función

	// var dropdownSource = [
	// 	{ id: 1, label: 'Secundaria' },
	// 	{ id: 2, label: 'Preparatoria' },
	// 	{ id: 3, label: 'Otro' }
	// ];

	// PASO 7
	//* EN CASO DE SER DROPDOWN/SELECTOR SE DEBE ADICIONAR NUEVA VARIABLE DE LA SIGUIENTE FORMA


	// PERMISOS DROPDOWN


	<?php 
		if( $permisos == 1 ){
	?>

	<?php
		} else if( $permisos == 2 ){
	?>

	<?php
		} else {
	?>

	<?php
		}
 	?>

	///
	<?php 
	if ($permisos == 1) {
	?>
		var dropdownEjecutivo = [
			<?php
			$sqlCerrador = "
				SELECT *
				FROM ejecutivo
				WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_eje != 2311
				ORDER BY nom_eje ASC
			";

			$resultadoCerrador = mysqli_query($db, $sqlCerrador);

			while ($filaCerrador = mysqli_fetch_assoc($resultadoCerrador)) {
				echo '{ label: "' . $filaCerrador['nom_eje'] . '", value: ' . $filaCerrador['id_eje'] . ' },';
			}
			?>
		];
	<?php
	} else if ($permisos == 2) {
		// Obtener los planteles asociados al ejecutivo
		$sqlPlanteles = "
			SELECT id_pla
			FROM planteles_ejecutivo
			WHERE id_eje = $id
		";
		$resultadoPlanteles = mysqli_query($db, $sqlPlanteles);

		$plantelesAsociados = [];
		while ($filaPlantel = mysqli_fetch_assoc($resultadoPlanteles)) {
			$plantelesAsociados[] = $filaPlantel['id_pla'];
		}

		// Si hay planteles asociados, los usamos en la consulta
		if (!empty($plantelesAsociados)) {
			$plantelesList = implode(",", $plantelesAsociados);
			$sqlCerrador = "
				SELECT *
				FROM ejecutivo
				WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_pla IN ($plantelesList) AND id_eje != 2311
				ORDER BY nom_eje ASC
			";
		} else {
			// Si no hay planteles asociados, no se mostrarán ejecutivos
			$sqlCerrador = "SELECT * FROM ejecutivo WHERE 1 = 0";
		}
	?>
		var dropdownEjecutivo = [
			<?php
			$resultadoCerrador = mysqli_query($db, $sqlCerrador);

			while ($filaCerrador = mysqli_fetch_assoc($resultadoCerrador)) {
				echo '{ label: "' . $filaCerrador['nom_eje'] . '", value: ' . $filaCerrador['id_eje'] . ' },';
			}
			?>
		];
	<?php
	} else {
	?>
		var dropdownEjecutivo = [
			<?php
			$sqlCerrador = "
				SELECT *
				FROM ejecutivo
				WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_eje != 2311
				ORDER BY nom_eje ASC
			";

			$resultadoCerrador = mysqli_query($db, $sqlCerrador);

			while ($filaCerrador = mysqli_fetch_assoc($resultadoCerrador)) {
				echo '{ label: "' . $filaCerrador['nom_eje'] . '", value: ' . $filaCerrador['id_eje'] . ' },';
			}
			?>
		];
	<?php
	}
	?>

	<?php 
	if ($permisos == 1) {
	?>
		var dropdownCerrador = [
			<?php
			$sqlCerrador = "
				SELECT *
				FROM ejecutivo
				WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_eje != 2311
				ORDER BY nom_eje ASC
			";

			$resultadoCerrador = mysqli_query($db, $sqlCerrador);

			while ($filaCerrador = mysqli_fetch_assoc($resultadoCerrador)) {
				echo '{ label: "' . $filaCerrador['nom_eje'] . '", value: ' . $filaCerrador['id_eje'] . ' },';
			}
			?>
		];
	<?php
	} else if ($permisos == 2) {
		// Obtener los planteles asociados al ejecutivo
		$sqlPlanteles = "
			SELECT id_pla
			FROM planteles_ejecutivo
			WHERE id_eje = $id
		";
		$resultadoPlanteles = mysqli_query($db, $sqlPlanteles);

		$plantelesAsociados = [];
		while ($filaPlantel = mysqli_fetch_assoc($resultadoPlanteles)) {
			$plantelesAsociados[] = $filaPlantel['id_pla'];
		}

		// Si hay planteles asociados, los usamos en la consulta
		if (!empty($plantelesAsociados)) {
			$plantelesList = implode(",", $plantelesAsociados);
			$sqlCerrador = "
				SELECT *
				FROM ejecutivo
				WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_pla IN ($plantelesList) AND id_eje != 2311
				ORDER BY nom_eje ASC
			";
		} else {
			// Si no hay planteles asociados, no se mostrarán ejecutivos
			$sqlCerrador = "SELECT * FROM ejecutivo WHERE 1 = 0";
		}
	?>
		var dropdownCerrador = [
			<?php
			$resultadoCerrador = mysqli_query($db, $sqlCerrador);

			while ($filaCerrador = mysqli_fetch_assoc($resultadoCerrador)) {
				echo '{ label: "' . $filaCerrador['nom_eje'] . '", value: ' . $filaCerrador['id_eje'] . ' },';
			}
			?>
		];
	<?php
	} else {
	?>
		var dropdownCerrador = [
			<?php
			$sqlCerrador = "
				SELECT *
				FROM ejecutivo
				WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_eje != 2311
				ORDER BY nom_eje ASC
			";

			$resultadoCerrador = mysqli_query($db, $sqlCerrador);

			while ($filaCerrador = mysqli_fetch_assoc($resultadoCerrador)) {
				echo '{ label: "' . $filaCerrador['nom_eje'] . '", value: ' . $filaCerrador['id_eje'] . ' },';
			}
			?>
		];
	<?php
	}
	?>

	<?php 
	if ($permisos == 1) {
	?>
		var dropdownAgendo = [
			<?php
			$sqlCerrador = "
				SELECT *
				FROM ejecutivo
				WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_eje != 2311
				ORDER BY nom_eje ASC
			";

			$resultadoCerrador = mysqli_query($db, $sqlCerrador);

			while ($filaCerrador = mysqli_fetch_assoc($resultadoCerrador)) {
				echo '{ label: "' . $filaCerrador['nom_eje'] . '", value: ' . $filaCerrador['id_eje'] . ' },';
			}
			?>
		];
	<?php
	} else if ($permisos == 2) {
		// Obtener los planteles asociados al ejecutivo
		$sqlPlanteles = "
			SELECT id_pla
			FROM planteles_ejecutivo
			WHERE id_eje = $id
		";
		$resultadoPlanteles = mysqli_query($db, $sqlPlanteles);

		$plantelesAsociados = [];
		while ($filaPlantel = mysqli_fetch_assoc($resultadoPlanteles)) {
			$plantelesAsociados[] = $filaPlantel['id_pla'];
		}

		// Si hay planteles asociados, los usamos en la consulta
		if (!empty($plantelesAsociados)) {
			$plantelesList = implode(",", $plantelesAsociados);
			$sqlCerrador = "
				SELECT *
				FROM ejecutivo
				WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_pla IN ($plantelesList) AND id_eje != 2311
				ORDER BY nom_eje ASC
			";
		} else {
			// Si no hay planteles asociados, no se mostrarán ejecutivos
			$sqlCerrador = "SELECT * FROM ejecutivo WHERE 1 = 0";
		}
	?>
		var dropdownAgendo = [
			<?php
			$resultadoCerrador = mysqli_query($db, $sqlCerrador);

			while ($filaCerrador = mysqli_fetch_assoc($resultadoCerrador)) {
				echo '{ label: "' . $filaCerrador['nom_eje'] . '", value: ' . $filaCerrador['id_eje'] . ' },';
			}
			?>
		];
	<?php
	} else {
	?>
		var dropdownAgendo = [
			<?php
			$sqlCerrador = "
				SELECT *
				FROM ejecutivo
				WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_eje != 2311
				ORDER BY nom_eje ASC
			";

			$resultadoCerrador = mysqli_query($db, $sqlCerrador);

			while ($filaCerrador = mysqli_fetch_assoc($resultadoCerrador)) {
				echo '{ label: "' . $filaCerrador['nom_eje'] . '", value: ' . $filaCerrador['id_eje'] . ' },';
			}
			?>
		];
	<?php
	}
	?>
	///

	var editRow = null;  // Variable global para la fila editable


	if (hot) {
	    hot.destroy();
	}

	function firstColumnRenderer2(instance, td, row, col, prop, value, cellProperties) {
		Handsontable.renderers.TextRenderer.apply(this, arguments);
	  	td.style.backgroundColor = '#CFE2F2'; // Cambia el color de fondo
	}

	function firstColumnRenderer(instance, td, row, col, prop, value, cellProperties) {
		Handsontable.renderers.TextRenderer.apply(this, arguments);
		td.style.backgroundColor = '#E3E6E7'; // Mantiene el color de fondo existente

		if (col === 18 && !isNaN(value) && value !== null && value !== '') {
			const id_cit = instance.getDataAtCell(row, 18); // Ahora el id_cit está en la columna 18
			td.innerHTML = '<a title="APLICAR BECA" target="_blank" href="https://plataforma.ahjende.com/programa_oportunidades.php?id_cit=' + id_cit + '" style="color: blue; text-decoration: underline; cursor: pointer;">' + value + '</a>';
		}
	}

	var colorRegistro = '#00FFFF';
	var errorNotificado = {}; // Objeto para rastrear errores notificados
	hot = new Handsontable(container, {
		language: 'es-MX',
		data,

		// PASO 18
		// PINTADO DE COLUMNAS Y VALIDACION DE EXISTENCI DE ALUMNO, RECORRER SEGÚN EL CASO
		
		//
		cells: function deshabilitarFila(row, col) {
			var cellProperties = {};

			// Validador para filas sin id_alu_ram (id_alu_ram_aux en la columna 21, antes 17)
			if (this.instance.getDataAtRowProp(row, 21) === 'false' && this.instance.getDataAtRowProp(row, 15) === 'REGISTRO' && this.instance.getDataAtRowProp(row, 19) == 'true') {
				// Pintar la fila de rojo
				cellProperties.renderer = function(instance, td, row, col, prop, value, cellProperties) {
					Handsontable.renderers.TextRenderer.apply(this, arguments);
					td.style.color = '#ff0000';  // Texto en rojo
					td.style.backgroundColor = '#FFC0CB'; // Fondo rosa
				};

				// Mostrar un mensaje de error con toastr si no se ha notificado antes
				var nombre = this.instance.getDataAtRowProp(row, 10); // antes 7
				var folio = this.instance.getDataAtRowProp(row, 18); // antes 15
				var errorKey = nombre + '-' + folio;
				colorRegistro = '#00FFFF';

				if (!errorNotificado[errorKey]) {
					toastr.error('ERROR AL REGISTRAR CITA PARA NOMBRE: ' + nombre + ' --- FOLIO: ' + folio + '<br>¡VUELVE A REGISTRAR!');
					errorNotificado[errorKey] = true;
				}
			} else {
				// Pintar de azul si el registro es válido
				if (this.instance.getDataAtRowProp(row, 15) === 'REGISTRO' && this.instance.getDataAtRowProp(row, 19) == 'true') {
					if (row === editRow) {
						cellProperties.readOnly = false;  // Si es la fila que queremos editar
					} else {
						cellProperties.readOnly = true;  // Para todas las demás filas REGISTRO
					}
					cellProperties.renderer = function(instance, td, row, col, prop, value, cellProperties) {
						Handsontable.renderers.TextRenderer.apply(this, arguments);
						td.style.backgroundColor = '#00FFFF';
					};
				}
			}

			// Validar si `cam_cit` es 0 y hacer la columna 16 (antes 13) (efe_cit) readOnly
			if (col === 16) {
				
				var cam_cit = this.instance.getDataAtRowProp(row, 22); // Obtener el valor de cam_cit (antes 19)
				if (cam_cit == 0) {
					cellProperties.readOnly = true; // Deshabilitar la edición si cam_cit es 0
				}

				// Aplicar el renderer para columna 16 (antes 13)
				cellProperties.renderer = function(instance, td, row, col, prop, value, cellProperties) {
					Handsontable.renderers.TextRenderer.apply(this, arguments);

					// Establecer estilos según el valor de efe_cit
					switch (value) {
						case 'CITA EFECTIVA':
							td.style.backgroundColor = '#FFC0CB'; // Fondo rosa
							td.style.color = '#FF0000'; // Texto rojo
							break;
						case 'CITA NO EFECTIVA':
							td.style.backgroundColor = '#FF6666'; // Fondo rojo claro
							td.style.color = '#FFFFFF'; // Texto blanco
							break;
						default:
							// Restablecer estilos si es necesario
							td.style.backgroundColor = '';
							td.style.color = '';
							break;
					}
				};
			}

			// Otros renderers según las condiciones
			if (col === 0) {
				cellProperties.renderer = firstColumnRenderer2;
			} else if (col === 18 <?php echo ( ( $rangoUsuario != 'GC' && $rangoUsuario != 'TL' ) && $permisos != 1 && $permisos != 2  )? ' || col === 8': ''; ?> <?php echo ( $rangoUsuario != 'GC' || $rangoUsuario != 'DC'  )? ' || col === 15': ''; ?> <?php echo ( $rangoUsuario != 'DM' && $rangoUsuario != 'GC' && $rangoUsuario != 'DC' ) ? ' || col === 6': ''; ?> ) { 
				cellProperties.renderer = firstColumnRenderer;
			}

			// Configuración de estilos para la columna 15 (antes 12) (estatus de la cita)
			if (col === 15) {
				cellProperties.renderer = function(instance, td, row, col, prop, value, cellProperties) {
					Handsontable.renderers.TextRenderer.apply(this, arguments);

					// Establecer colores según el estatus de la cita
					// Obtenemos el status correspondiente al valor
					const status = statusConfig.getStatusByLabel(value);
					// Aplicamos el color si existe el status, sino aplicamos el estilo default
					td.style.backgroundColor = status ? status.color : '';
				};
			}

			return cellProperties;
		},

		//
		
		height: 'auto',
		width: '100%',

		// PASO 17
		// OCULTAMIENTO DE COLUMNAS, RECORRER SEGÚN EL CASO
		hiddenColumns: {
			columns: [ 19, 20, 21, 22 ], // Actualizados los índices para reflejar las nuevas posiciones
			indicators: false // Esto oculta el indicador de columnas ocultas
		},
		stretchH: 'all',
		colHeaders: colHeaders,
		rowHeaders: true,

		manualColumnResize: true,
		autoWrapCol: true,

		rowHeaders: true,
		minRows: 20,
    	minSpareRows: 1,
		licenseKey: 'non-commercial-and-evaluation',
		afterChange: function(changes, source) {
			// console.log("Cambio detectado");
			if (source === 'loadData' || source === 'populateFromArray') {
				// Si la fuente es loadData o populateFromArray, no hacer nada
				return;
			}
			if (changes) {
				changes.forEach(([row, prop, oldValue, newValue]) => {
					// Ignora los cambios en las columnas 0 y 2
					// POR EJEMPLO ID Y FEC CAPTURA QUE SON DATOS FIJOS
					// if (prop === 0 || prop === 1) {
					// 	return;
					// }

					if (row >= hot.countRows() - hot.getSettings().minSpareRows) {
						// Si es una fila nueva
						let rowData = hot.getDataAtRow(row);
						adicionarFila(rowData);
					} else {
						// Si es una fila existente
						guardarCelda(hot, row, prop, newValue);
						//toastr.success('Cambios guardados');
					}
				});
			}
			//toastr.success('Cambios guardados');
			// if (changes) {
			// 	changes.forEach(function([row, col, oldValue, newValue]) {
			// 		console.log(`Cambio en la fila ${row}, columna ${col}. Antiguo: ${oldValue}, Nuevo: ${newValue}`);
			// 		if (col === 5) { // Suponiendo que el dropdown está en la columna 5
			// 			var selectedID = dropdownSource.find(item => item.label === newValue)?.value;
			// 			console.log('Nuevo ID seleccionado:', selectedID);
			// 		}
			// 	});
			// }
		},


		filters: true,
		dropdownMenu: ['filter_by_condition', 'filter_by_value', 'filter_action_bar'],
		height: 'auto',
		
		columnSorting: true,

		allowRemoveRow: true,

		contextMenu: {
			items: {
				"row_above": {
					name: 'Insertar fila arriba',
					disabled: function() {
					// Deshabilitar cuando la primera fila está seleccionada
					return this.getSelectedLast() && this.getSelectedLast()[0] === 0;
					}
				},
				"row_below": {
					name: 'Insertar fila debajo'
				},
				"copy": {
					name: 'Copiar'
				},

				"editar_cita": {
					name: function() {
						// Obtenemos la fila actualmente seleccionada
						var selected = this.getSelected();
						if (selected) {
							var row = selected[0][0];
							// Si esta fila es la que está en edición, mostramos "BLOQUEAR"
							return row === editRow ? 'BLOQUEAR CITA' : 'EDITAR CITA';
						}
						return 'EDITAR CITA';
					},
					callback: function() {
						var selected = this.getSelected();
						if (selected) {
							var row = selected[0][0];
							if (row === editRow) {
								// Si ya estaba en edición, la bloqueamos
								editRow = null;
								toastr.info('Cita bloqueada');
							} else {
								// Si no estaba en edición y es válida para editar
								if (this.getDataAtCell(row, 15) === 'REGISTRO' && 
									this.getDataAtCell(row, 19) === 'true') {
									editRow = row;
									toastr.success('Cita habilitada para edición');
								} else {
									toastr.error('Solo se pueden editar citas en estado REGISTRO');
								}
							}
							this.render();
						}
					}
				},

				<?php 
					if( $rangoUsuario == 'GC' || $rangoUsuario == 'DC' ){
				?>
						"custom_option": {
							name: 'ELIMINAR REGISTRO',
							callback: function() {
								try {
									// Obtener la selección actual
									var selection = this.getSelected();
									if (selection && selection.length > 0) {
										// La selección es un array, tomamos el primer elemento
										var selectedRow = selection[0][0];
										// Verificar si la fila seleccionada es válida
										if (selectedRow !== undefined && selectedRow >= 0) {
											var value = this.getDataAtCell(selectedRow, 18); // Actualizado de 15 a 18

											var est_cit = this.getDataAtCell(selectedRow, 15); // Actualizado de 12 a 15
											// console.log("Valor de la primera columna:", value);
											// Verificar si el valor es numérico
											if ( !isNaN(value) && est_cit == 'REGISTRO' ) {
												// Aquí puedes hacer lo que necesites con el valor obtenido
												// Por ejemplo, podrías mostrar el valor en un alert
												// alert("Valor: " + value);
												// CODIGO
												swal({
													title: "Continuar",
													text: "¿Estás seguro que deseas eliminar el registro vinculado a esta cita?",
													icon: "warning",
													buttons: ["Cancelar", "Aceptar"],
													dangerMode: true,
												})
												.then((willContinue) => {
													if (willContinue) {
														// Aquí puedes hacer lo que necesites con el valor obtenido
														// Por ejemplo, podrías mostrar el valor en un alert
														var id_cit = value;
														swal("Continuar", "Confirmado correctamente", "success").then((confirmation) =>{
															
															$.ajax({
																type: "POST",
																dataType: 'json',
																url: "server/controlador_alumno.php",
																data: { id_cit },
																success: function(response) {
																	console.log( response );
																	obtener_datos();
																},
															});
															
														});
														
													} else {
														console.log("Operación cancelada");
													}
												});

												// F CODIGO

											} else {
												toastr.error('NO ES REGISTRO O NO HAY CITA :/');
											}
										} else {
											console.log("No se pudo obtener una fila válida");
										}
									} else {
										console.log("No hay selección");
									}
								} catch (error) {
									console.error("Se produjo un error:", error);
								}
							}
						}
				<?php
					}
				?>
				
			}
		},


		// 
		// PASO 8
		// TIPADO DE LA COLUMNA EN HANSOTABLE 
		columns: [
			{
				//0
				// PROGRAMACIÓN CITA
				readOnly: true,
			},
			{
				//1
				// HORARIO
				type: 'time',
				timeFormat: 'hh:mm A',
				correctFormat: true
			},
			{
				//2
				// FECHA
				type: 'date',
				dateFormat: 'DD/MM/YYYY',
				correctFormat: true,
				datePickerConfig: {
					// First day of the week (0: Sunday, 1: Monday, etc)
					firstDay: 0,
					showWeekNumber: true
					// Se ha eliminado la función disableDayFn
				}
			},
			{
				//3
				// CDE AGENDO
				readOnly: true,
			},
			{
				//4
				// CDE CONSULTOR
				readOnly: true,
			},
			{
				//5
				// CDE DESTINO
				readOnly: true,
			},

			<?php 
				if ( $rangoUsuario != 'GC' && $rangoUsuario != 'DC' && $rangoUsuario != 'DM' ) {
			?>
					{
						//6
						// AGENDO
						readOnly: true,
					},
			<?php
				} else {
			?>
					{
						//6
						// AGENDO
						type: 'dropdown',
						source: dropdownAgendo.map(item => item.label)
					},

			<?php
				}
			?>
			
			{
				//7
				// EJECUTIVO
				type: 'dropdown',
				source: dropdownCerrador.map(item => item.label)
			},


			<?php 
				if( $rangoUsuario == 'GC' || $rangoUsuario == 'TL' || $permisos == 1 || $permisos == 2 ){
			?>
					{
						//8
						// CONSULTOR
						type: 'dropdown',
						source: dropdownEjecutivo.map(item => item.label)
					},

			<?php
				} else {
			?>
					{
						//8
						// CONSULTOR
						readOnly: true,
					},

			<?php
				}
			?>
			
			{
				//9
				// TIPO DE CITA
				type: 'dropdown',
				source: ['Videoconferencia', 'Presencial', 'Llamada', 'Mensaje']  //
			},
			{
				//10
				// NOMBRE
			},

			{
				//11
				// EDAD
			},

			{
				//12
				// NUMERO
			},
			{
				//13
				// MODALIDAD
				type: 'dropdown',
				source: ['PREPA-EMPRENDE', 'DIPLOMADO', 'LICENCIATURA', 'BACH-NEGOCIOS']  //
			},
			{
				//14
				// MERCADO
				type: 'dropdown',
				source: ["Facebook", 'FANPAGE', "PAUTA ORGÁNICA", "PAUTA AHJ", "TIKTOK", "Mercado natural", "Mercado frío", "Referidos", "Rezagados", "Módulo", "Re matriculación", "Volantes", "Marketing", "PP"]
			},
			{
				//15
				// ESTATUS
				type: 'dropdown',
				source: statusConfig.getActiveStatuses().map(status => status.label)
			},

			{
				//16
				// CALIDAD DE CITA
				type: 'dropdown',
				source: ['CITA EFECTIVA', 'CITA NO EFECTIVA']
			},

			{
				//17
				// OBSERVACIONES
				width: 500, 
				wordWrap: false,
			},
			{
				//18
				// FOLIO
				readOnly: true,
			},
			{
				//19
				// ID_ALU
				readOnly: true,
			},
			{
				//20
				// BOOL1
				readOnly: true,
			},
			{
				//21
				// BOOL2
				readOnly: true,
			},
			{
				//22
				// CAM_CIT
				readOnly: true,
			},
		],
		// 
		
	});

	

	

	$('#badges-container').html(statusConfig.generateStatusBadgesHTML());
	obtenerConteosEstatus( hot );
	
	// PASO 9
	// RECORRER SEGÚN SEA EL CASO LAS COLUMNAS 
	function obtenerConteosEstatus(hot) {
		// Inicializar el objeto de conteos usando los estatus activos
		const posiblesEstados = statusConfig.getActiveStatuses().reduce((acc, status) => {
			acc[status.label] = 0;
			return acc;
		}, {});

		// Obtener datos de la columna de estatus (ahora es la columna 15)
		const statusColumnData = hot.getDataAtCol(15);

		// Contar ocurrencias
		statusColumnData.forEach(function(status) {
			if (status && posiblesEstados.hasOwnProperty(status)) {
				posiblesEstados[status]++;
			}
		});

		// Conteo de "Cita Efectiva" desde la columna 16 (antes era 13)
		const citaEfectivaColumnData = hot.getDataAtCol(16);
		const conteoCitaEfectiva = citaEfectivaColumnData.filter(status => 
			status === 'CITA EFECTIVA'
		).length;
		const conteoRegistros = statusColumnData.filter(status => 
			status === 'REGISTRO'
		).length;

		// Calcular total
		const conteoTotal = Object.values(posiblesEstados).reduce((total, current) => 
			total + current, 0
		);

		// Actualizar elementos HTML dinámicamente basado en los IDs de estatus
		statusConfig.getActiveStatuses().forEach(status => {
			const elementId = `conteo_${status.id}`;
			$(`#${elementId}`).text(posiblesEstados[status.label]);
		});

		// Actualizar totales
		$('#conteo_total').text(conteoTotal);
		$('#total_cita_efectiva').text(conteoCitaEfectiva);
		$('#conteo_registros').text(conteoRegistros);
	}

	function actualizarBarra() {
		// Obtén los datos de las columnas.
		var total = parseInt($('#conteo_total').text(), 10);
		var citaEfectiva = parseInt($('#total_cita_efectiva').text(), 10);
		var registros = parseInt($('#conteo_registros').text(), 10);

		// Asegúrate de que los valores sean números válidos.
		total = isNaN(total) ? 0 : total;
		citaEfectiva = isNaN(citaEfectiva) ? 0 : citaEfectiva;
		registros = isNaN(registros) ? 0 : registros;

		// Calcula los porcentajes.
		var porcentajeCitaEfectiva = total > 0 ? Math.floor((citaEfectiva / total) * 100) : 0;
		var porcentajeRegistros = total > 0 ? Math.floor((registros / citaEfectiva) * 100) : 0;

		// Imprime los valores en consola para verificar.
		console.log('Total:', total);
		console.log('Cita Efectiva:', citaEfectiva);
		console.log('Registros:', registros);
		console.log('Porcentaje Cita Efectiva:', porcentajeCitaEfectiva);
		console.log('Porcentaje Registros:', porcentajeRegistros);

		// Actualiza las barras.
		$('#barra_total').css('width', '100%');
		$('#barra_cita_efectiva').css('width', porcentajeCitaEfectiva + '%');
		$('#barra_registros').css('width', porcentajeRegistros + '%');

		// Actualiza los textos dentro de las barras.
		$('#texto_total').text('100%');
		$('#texto_cita_efectiva').text(porcentajeCitaEfectiva + '%');
		$('#texto_registros').text(porcentajeRegistros + '%');
	}
    // Llama a la función para probarla
    actualizarBarra();

	container.classList.add('dark-mode');

	var exportPlugin = hot.getPlugin('exportFile');

	var button = document.querySelector('#export-file');

	button.addEventListener('click', () => {
	  exportPlugin.downloadFile('csv', {
	    bom: false,
	    columnDelimiter: ',',
	    columnHeaders: false,
	    exportHiddenColumns: true,
	    exportHiddenRows: true,
	    fileExtension: 'csv',
	    filename: 'Reporte de citas',
	    mimeType: 'text/csv',
	    rowDelimiter: '\r\n',
	    rowHeaders: true,
	    //columns: [0, 1, 2, 3]  // Aquí especificas las columnas que deseas exportar
	  });
	});

	// CONTEO EN FILTROS
	hot.addHook('afterFilter', function() {
		// console.log('Se aplicó un filtro en Handsontable.');
		obtenerConteosEstatus( hot );
	});
	// F CONTEO EN FILTROS

	function adicionarFila(rowData) {

		// alert("Datos de la fila: " + JSON.stringify(rowData));

	    // $.ajax({
	    //     type: "POST",
	    //     url: "adicionar_fila.php",
	    //     data: { data: rowData },
	    //     success: function(response) {
	    //         console.log("Fila añadida con éxito!");
	    //     },
	    //     error: function(error) {
	    //         console.log("Hubo un problema al añadir la fila.");
	    //     }
	    // });
	}

	
	function obtenerCeldaSeleccionada() {
		const selected = hot.getSelectedLast();
		if (selected) {
			const [startRow, startCol, endRow, endCol] = selected;
			console.log('Celda seleccionada:', startRow, startCol);
			return [startRow, startCol]; // Devolver las coordenadas
		}
		return null;
	}

	celdaSeleccionada = [];

	// hot.selectCell(celdaSeleccionada[0], celdaSeleccionada[1]);
	hot.selectCell( <?php if( isset( $_POST['fila'] ) ){ echo $_POST['fila']; } else { echo 0; } ?>,  <?php if( isset( $_POST['columna'] ) ){ echo $_POST['columna']; } else { echo 0; } ?> );
	
	function guardarCelda(hot, row, column, value) {
    // console.log('guardarCelda');

		// PASO 10
		// YA CASI TERMINA LA PUTIZA :3 SE DEBE REMOVER EL INDICE DE id, nombre Y telefono SEGÚN EL CASO
		var id = hot.getDataAtCell(row, 18);         // Actualizado de 15 a 18
		var nombre = hot.getDataAtCell(row, 10);     // Actualizado de 7 a 10
		var telefono = hot.getDataAtCell(row, 12);   // Actualizado de 9 a 12
		var cam_cit = hot.getDataAtCell(row, 22);    // Actualizado de 19 a 22

		

    	var id_eje = $('#selector_ejecutivo option:selected').val();

    	//alert( id_eje );
    	var valor = value;

		// if( column == 8 ){
		// 	valor = validarNumeroTelefonico( valor );
		// }

		// if( column == 1 && value == '' ){
		// 	var valor = hot.getDataAtCell(row, 16);
		// }

		// '18:00:00'

		// console.log('value: '+valor);

		// PASO 11
		// *SI SE ADICIONÓ NUEVO DROPDOWN, ES NECESARIO CAPTAR EL VALOR TAL CUAL DESCRIBE EL CÓDIGO DEBAJO
		var id_eje_agendo;
		if (column === 3) {
			
			var dropdownValue = hot.getDataAtCell(row, column); // Obtener el valor del dropdown
			var selectedOption = dropdownAgendo.find(function(option) {
				return option.label === dropdownValue;
			});
			id_eje_agendo = selectedOption ? selectedOption.value : null;

			valor = id_eje_agendo;

			console.log('agendo');
			console.log( 'id_eje_agendo: '+id_eje_agendo );
		}

		var id_eje_cerrador;
		if (column === 4) {
			
			var dropdownValue = hot.getDataAtCell(row, column); // Obtener el valor del dropdown
			var selectedOption = dropdownCerrador.find(function(option) {
				return option.label === dropdownValue;
			});
			id_eje_cerrador = selectedOption ? selectedOption.value : null;

			//console.log( 'id_eje_cerrador'+id_eje_cerrador );
			valor = id_eje_cerrador;

			// console.log('atendio/cerro');
			// console.log( 'id_eje_cerrador: '+id_eje_cerrador );
		}

		<?php 
			if( $rangoUsuario == 'GC' || $permisos == 1 || $permisos == 2 ){
		?>
				var id_eje_ejecutivo;
				if (column === 5) {
					
					var dropdownValue = hot.getDataAtCell(row, column); // Obtener el valor del dropdown
					var selectedOption = dropdownCerrador.find(function(option) {
						return option.label === dropdownValue;
					});
					id_eje_ejecutivo = selectedOption ? selectedOption.value : null;

					//console.log( 'id_eje_cerrador'+id_eje_cerrador );
					valor = id_eje_ejecutivo;

					// console.log('atendio/cerro');
					// console.log( 'id_eje_cerrador: '+id_eje_cerrador );
				}
		<?php
			}
		?>
		

		// PASO 12
		// CAMBIAR INDICE DE CONTEOS SEGÚN SEA EL CASO
		if( column == 15 ){  // Actualizado de 12 a 15
			obtenerConteosEstatus( hot );
		}

    	//alert("ID: " + id + "\nFila: " + row + "\nColumna: " + column + "\nValor: " + value);
    	if ( id == "" || id == null || id == undefined || id == '--' ) {
    		// ALTA
    		//alert("no hay datos en esta fila");

    		var accion = "Alta";
    		var campo = obtenerCampoValor( column );
			
			console.log('alta');
			// console.log( 'ALTA ---- campo: '+campo+' valor: '+valor );

			// PASO 13
			// *SI LA COLUMNA SERÁ MENOR QUE LA INDICE 1, DADO QUE SE MOVERÁ LA 1, HAY QUE CAMBIARLA
			if ( ( column == 1 && validarFormatoHora(value) ) || ( column != 1 ) ) {
				// VALIDADOR DADO QUE LA COLUMNA DE HORARIO REALIZA DOBLE ESCRITURA EN LA CAPTACIÓN DE HORARIO PARA CORRGIR AL FORMATO DEFINIDO Y SE GENERA DOBLE REGISTRO
				// ADD
				if(valor){

					console.log('ajax alta');
					if( column == 1 ){
						var data_json = { campo, valor, accion, id_eje };
					} else {
						var hor_cit = hot.getDataAtCell(row, 17);
						var data_json = { campo, valor, accion, id_eje, hor_cit };
						console.log('hor_cit :D');
					}

					// SELECCION DE INTERSECCION
					celdaSeleccionada = obtenerCeldaSeleccionada();
					// F SELECCION DE INTERSECCION	
					$.ajax({
						url: 'server/controlador_cita2.php',
						type: 'POST',
						data: data_json,
						dataType: 'json',
						success: function( data ){
						//success

							console.log( 'response: '+data );

							// PASO 14
							// // CAMBIAR LOS ÍNDICES SEGÚN SEA EL CASO DE LA IMPRESIÓN DE DATOS Y TENER CUIDADO CON MOVER LOS ANTERIORES AL NUEVO
							hot.setDataAtCell(row, 18, data.id_cit); // Folio de la cita (antes 15)
							hot.setDataAtCell(row, 0, '--');
							hot.setDataAtCell(row, 1, data.hor_cit);
							hot.setDataAtCell(row, 2, data.cit_cit); // Fecha formateada de la cita

							hot.setDataAtCell(row, 3, data.cde_agendo || '--'); // Nueva columna CDE AGENDO
							hot.setDataAtCell(row, 4, data.cde_consultor || '--'); // Nueva columna CDE CONSULTOR
							hot.setDataAtCell(row, 5, data.cde_destino || '--'); // Nueva columna CDE DESTINO

							hot.setDataAtCell(row, 6, data.nom_eje_agendo); // Nombre del ejecutivo que agendó (antes 3)
							hot.setDataAtCell(row, 7, data.nom_eje_cerrador); // Nombre del ejecutivo cerrador (antes 4)
							hot.setDataAtCell(row, 8, data.nom_eje); // Nombre del consultor (antes 5)

							hot.setDataAtCell(row, 9, data.tip_cit); // Tipo de cita (antes 6)

							hot.setDataAtCell(row, 10, data.nom_cit); // Nombre de la cita (antes 7)
							hot.setDataAtCell(row, 11, data.eda_cit); // Edad (antes 8)

							hot.setDataAtCell(row, 12, data.tel_cit); // Teléfono de la cita (antes 9)

							hot.setDataAtCell(row, 13, data.pro_cit); // Modalidad de interés (antes 10)
							hot.setDataAtCell(row, 14, data.can_cit); // Mercado de citas (antes 11)
							hot.setDataAtCell(row, 15, data.est_cit); // Estatus (antes 12)

							hot.setDataAtCell(row, 16, data.efe_cit); // Efectividad de la cita (antes 13)

							hot.setDataAtCell(row, 17, data.obs_cit); // Observaciones (antes 14)

							hot.setDataAtCell(row, 22, data.cam_cit); // CAM_CIT (antes 19)

							//console.log('cambios cita :3'+data.cam_cit);
							
							obtenerConteosEstatus( hot );
							// hot.alter('insert_row', 0);

							
							// alert( data.sql );
							// SELECCION DE CELDA
							//hot.selectCell(1, 2);

						}

					});

				}
				//
			}

    	} else {

    		//UPDATE
			//console.log( ':D -- COLUMN: '+column );

    		var accion = "Cambio";

    		var campo = obtenerCampoValor( column );
    		var id_cit = id;


			// console.log('edicion columna: '+column);
			// console.log('edicion campo: '+campo);
			// if( column != 1 && column != 13 ){
				//
				// --
				// REGISTRO
				// VALIDACION 
				if ( campo == 'est_cit' && valor == 'REGISTRO' ) {
					hot.deselectCell();
					
					// CODE GOES HERE
					// 
					$.ajax({
						url: 'server/controlador_cita2.php',
						type: 'POST',
						data: { campo, valor, accion, id_cit },
						dataType: 'json',
						success: function( data ){
							console.log( 'edicion :DDDD ---->'+data );
							
							// // PASO 15
							// // *SI LA COLUMNA(S) SON ANTERIORES A LA 1 Y 2 HAY QUE RECORRER ESTOS ÍNDICES
							if (column == 2 && valor) {
								// Convertir la cadena a objeto Date
								const partes = valor.split('/');
								const dia = parseInt(partes[0], 10);
								const mes = parseInt(partes[1], 10) - 1; // Restamos 1 porque los meses van de 0 a 11 en JavaScript
								const anio = parseInt(partes[2], 10);
								const fecha = new Date(anio, mes, dia);

								// Comparar con la fecha actual
								const hoy = new Date();
								if (fecha.getDate() !== hoy.getDate() || fecha.getMonth() !== hoy.getMonth() || fecha.getFullYear() !== hoy.getFullYear()) {
									toastr.info('Cita para otro día');
									obtener_datos();

								} else {
									toastr.success('Cita para hoy');
								}

								toastr.success('FOLIO: '+id_cit);
							}
							// PASO 16
							// MISMO CASO PREVIO

							console.log(data.resultado+'resultadoooo :d');
							if ( data.resultado == 'false' ) {
								console.log('DELETEEE!!');
								hot.setDataAtCell(row, 0, '');
								hot.setDataAtCell(row, 8, ''); // Actualizado de 5 a 8
								hot.setDataAtCell(row, 17, ''); // Actualizado de 14 a 17

							}   
							//console.log(data.resultado);
							obtenerConteosEstatus( hot );
							//obtener_conteos_citas();  
						}
					});
					// 

					$('#modal_registro').modal('show');

					$('#id_cit').val(id);
					obtenerNombreDescompuesto( nombre );
					$('#tel_alu').val(telefono);
					obtenerCorreoCompuesto();
					obtener_colegiatura_grupo();
					// F CODE GOES HERE
					
					// //bloquearFila( hot, row );
				// F REGISTRO
				} else {
				// NO REGISTRO
					// 
					$.ajax({
						url: 'server/controlador_cita2.php',
						type: 'POST',
						data: { campo, valor, accion, id_cit },
						dataType: 'json',
						success: function(data) {
							console.log('edicion :DDDD ---->' + data);

							// Actualización de cam_cit en la tabla
							if (column == 16) { // Actualizado de 13 a 16
								var cam_cit = hot.getDataAtCell(row, 22); // Actualizado de 19 a 22
								
								if (cam_cit > 0) {
									hot.setDataAtCell(row, 22, cam_cit - 1); // Decrementar cam_cit en la tabla
									cam_cit -= 1; // Actualizar variable local para la verificación siguiente
								}

								// Verificar si cam_cit es 0 y hacer la columna 16 readOnly
								if (cam_cit === 0) {
									hot.getCellMeta(row, 16).readOnly = true; // Hacer la columna 16 readOnly
									hot.render(); // Volver a renderizar la tabla para aplicar el cambio visual
									toastr.info('RECUERDA QUE YA NO PODRÁS CAMBIAR LA CALIDAD DE LA CITA');
								}
							}

							// Resto del código para manejar otras columnas y respuestas del servidor
							console.log(data.resultado + 'resultadoooo :d');
							if (data.resultado == 'false') {
								console.log('DELETEEE!!');
								hot.setDataAtCell(row, 0, '');
								hot.setDataAtCell(row, 8, ''); // Actualizado de 5 a 8
								hot.setDataAtCell(row, 17, ''); // Actualizado de 14 a 17
							}

							obtenerConteosEstatus(hot);
						}
					});

					// 

				}
				// F NO REGISTRO
				// --

				
				//

			// }
    		
    	}

		function obtenerNombreDescompuesto(nombreCompleto) {
			// Limpiar espacios en blanco y verificar si el nombre completo está vacío
			if (!nombreCompleto || nombreCompleto.trim() === '') {
				$('#nom_alu').val('Vacío');
				$('#app_alu').val('Vacío');
				$('#apm_alu').val('Vacío');
				return;
			}
			// Separar el nombre por espacios
			var partes = nombreCompleto.trim().split(' ');

			// Asignar valores por defecto
			var nom_alu = partes.length > 0 ? partes.shift() : 'Vacío'; // Primer elemento para el nombre
			var app_alu = partes.length > 0 ? partes.shift() : 'Vacío'; // Segundo elemento para el apellido paterno
			var apm_alu = partes.length > 0 ? partes.join(' ') : 'Vacío'; // El resto para el apellido materno

			// Actualizar los valores en los campos correspondientes
			$('#nom_alu').val(nom_alu);
			$('#app_alu').val(app_alu);
			$('#apm_alu').val(apm_alu);
		}

		// PASO 17
		// RECORRER SEGÚN EL CASO
		function obtenerCampoValor(column) {
			let columnName;
			if (column == 1) {
				columnName = "hor_cit";
			} else if (column == 2) {
				columnName = "cit_cit";
			} else if (column == 3) {
				columnName = "cde_agendo";  // Nueva columna
			} else if (column == 4) {
				columnName = "cde_consultor";  // Nueva columna
			} else if (column == 5) {
				columnName = "cde_destino";  // Nueva columna
			} else if (column == 6) {
				columnName = "id_eje_agendo";
			} else if (column == 7) {
				columnName = "id_eje_cerrador";
			} else if (column == 8) {
				columnName = "id_eje_ejecutivo";
			} else if (column == 9) {
				columnName = "tip_cit";
			} else if (column == 10) {
				columnName = "nom_cit";
			} else if (column == 11) {
				columnName = "eda_cit";
			} else if (column == 12) {
				columnName = "tel_cit";
			} else if (column == 13) {
				columnName = "pro_cit";
			} else if (column == 14) {
				columnName = "can_cit";
			} else if (column == 15) {
				columnName = "est_cit";
			} else if (column == 16) {
				columnName = "efe_cit";
			} else if (column == 17) {
				columnName = "obs_cit";
			}
			return columnName;
		}

		//toastr.success('Cambios guardados');

	}


	function bloquearFila(hot, row) {
	    // Obtén la cantidad de columnas en la tabla
	    var colCount = hot.countCols();

	    // Recorre todas las columnas de la fila específica y establece la propiedad readOnly
	    for (var i = 0; i < colCount; i++) {
	        hot.setCellMeta(row, i, 'readOnly', true);
	    }

	    // Rerenderiza la tabla para aplicar los cambios de metadatos
	    hot.render();
	}
	
</script>


<script type="text/javascript">
    $('#correo').on('keyup', function(event) {
        /* Act on the event */

        var correo = $('#correo').val();
        validacionCorreoTiempoReal( correo );

    });


    function obtenerCorreoCompuesto(){

        let correo = $('#correo').val(
            remove_accents( $('#nom_alu').val().trim().split(' ')[0].toLowerCase() ) + '-' + 
            remove_accents( $('#app_alu').val().trim().replace(' ', '').toLowerCase() ) + '@<?php echo $folioPlantel; ?>.com' 
        );

		validacionCorreoTiempoReal( correo );
		
    }

    correo = $('#correo').val();
    validacionCorreoTiempoReal( correo );

    function validacionCorreoTiempoReal( correo ){
        //console.log( correo );

        if (correo != '') {
          $.ajax({
            url: 'server/validacion_correo.php',
            type: 'POST',
            data: { correo },
            success: function(response){
               //console.log(response);
              var respuesta = response; 


              if (respuesta == 'disponible') {
                
                $('#output').attr({
                  class: 'text-info letraPequena font-weight-normal'
                });
                $('#output').text("¡El correo electrónico está disponible!");

              }else{
                // correo = correo+'1';
                
                correo = correo.substring(0, correo.indexOf("@"))+'1';

                correo = correo+'@<?php echo $folioPlantel; ?>.com';
                $('#correo').val( correo );

                validacionCorreoTiempoReal( correo ); 
                
                // $('#output').attr({
                //   class: 'text-danger letraPequena font-weight-normal'
                // });
                // $('#output').text("¡El correo electrónico está ocupado!");

              }
            }
          })

        }
              
    }


    $('.correoCompuesto').on('keyup', function(event) {
        /* Act on the event */

         var correo = obtenerCorreoCompuesto();
         validacionCorreoTiempoReal( correo );
        
    });
	

	function validarFormatoHora(value) {
        var regex = /^(0?[1-9]|1[0-2]):[0-5][0-9] (AM|PM)$/i;
        return regex.test(value);
    }


    function remove_accents(str){
        const map = {
          '-' : ' ',
          'a' : 'á|à|ã|â|ä|À|Á|Ã|Â|Ä',
          'e' : 'é|è|ê|ë|É|È|Ê|Ë',
          'i' : 'í|ì|î|ï|Í|Ì|Î|Ï',
          'o' : 'ó|ò|ô|õ|ö|Ó|Ò|Ô|Õ|Ö',
          'u' : 'ú|ù|û|ü|Ú|Ù|Û|Ü',
          'c' : 'ç|Ç',
          'n' : 'ñ|Ñ'
        };

        for (var pattern in map) {
          str = str.replace(new RegExp(map[pattern], 'g'), pattern);
        }

        return str;
    }


	// function validarNumeroTelefonico(numero) {
	// 	// Verificar si el número es null o undefined
	// 	if (numero === null || numero === undefined) {
	// 		return "";
	// 	}
		
	// 	// Reemplazar caracteres no numéricos por vacío
	// 	var numeroLimpio = numero.toString().replace(/\D/g, '');

	// 	// Si el número limpio tiene exactamente 10 dígitos, devolverlo
	// 	if (numeroLimpio.length === 10) {
	// 		return numeroLimpio;
	// 	}

	// 	// Si no cumple con la longitud de 10 dígitos, retornar una cadena vacía
	// 	return "";
	// }
	// $('input').keyup(function() {
    //     // Convierte el valor del input a mayúsculas y asigna de nuevo
    //     $(this).val($(this).val().toUpperCase());
    // });

</script>