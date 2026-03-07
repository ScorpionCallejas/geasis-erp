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


<div class="row">
	<div class="col">
		<div class="controls">
			<button id="export-file" class="btn btn-success btn-sm letraPequena"><i class="fas fa-file-excel"></i> EXCEL</button>
		</div>	
	</div>
	
	<?php
	// OBTENER PLANTELES DEL EJECUTIVO PRIMERO PARA VALIDAR
	$plantelesEjecutivo = array();
	
	// USAR $id (variable de sesión)
	$id_ejecutivo_sesion = $id;
	
	// Verificar si tiene planteles específicos asignados
	$sqlPlantelesEjecutivo = "
		SELECT DISTINCT p.id_pla, p.nom_pla, p.id_cad1
		FROM plantel p
		INNER JOIN planteles_ejecutivo pe ON p.id_pla = pe.id_pla
		WHERE pe.id_eje = '$id_ejecutivo_sesion'
		ORDER BY p.nom_pla
	";
	$resultadoPlantelesEje = mysqli_query($db, $sqlPlantelesEjecutivo);
	
	if(mysqli_num_rows($resultadoPlantelesEje) > 0) {
		// Tiene planteles específicos
		while($filaPlantel = mysqli_fetch_assoc($resultadoPlantelesEje)) {
			$plantelesEjecutivo[] = $filaPlantel;
		}
	} else {
		// Usar plantel por defecto del ejecutivo
		$sqlPlantelDefault = "
			SELECT p.id_pla, p.nom_pla, p.id_cad1
			FROM plantel p
			INNER JOIN ejecutivo e ON p.id_pla = e.id_pla
			WHERE e.id_eje = '$id_ejecutivo_sesion'
		";
		$resultadoDefault = mysqli_query($db, $sqlPlantelDefault);
		if(mysqli_num_rows($resultadoDefault) > 0) {
			while($filaPlantel = mysqli_fetch_assoc($resultadoDefault)) {
				$plantelesEjecutivo[] = $filaPlantel;
			}
		}
	}
	
	// SOLO MOSTRAR EL DROPDOWN SI TIENE PLANTELES
	if(count($plantelesEjecutivo) > 0):
	?>
		<div class="col" style="text-align: right;">
			<!-- 🆕 DROPDOWN INICIOS DE PLANTELES -->
			<div class="dropdown">
				<button type="button" 
					class="btn btn-info btn-sm letraPequena dropdown-toggle waves-effect" 
					data-bs-toggle="dropdown" 
					aria-expanded="false"
					style="padding-left: 12px; padding-right: 12px;">
					<i class="fas fa-calendar-alt"></i> INICIOS <i class="fas fa-caret-down ms-1"></i>
				</button>
				
				<ul class="dropdown-menu dropdown-menu-catalogo" style="max-height: 400px; overflow-y: auto;">
					<li><h6 class="dropdown-header" style="font-size: 9px; font-weight: 700; color: #495057;">📅 CALENDARIO DE INICIOS</h6></li>
					
					<?php
					// MOSTRAR OPCIONES COMO HIPERVÍNCULOS
					foreach($plantelesEjecutivo as $plantel1) {
						echo '<li><a class="dropdown-item item-catalogo" href="reporte_inicios_pdf.php?id_pla='.$plantel1['id_pla'].'" target="_blank">
							<span class="concepto-nombre">'.strtoupper($plantel1['nom_pla']).'</span>
							<span class="concepto-monto"><i class="fas fa-file-pdf text-danger"></i></span>
						</a></li>';
					}
					?>
				</ul>
			</div>
		</div>
	<?php endif; ?>
</div>




<div id="data-sheet" class="hot" data-theme="dark"></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/canvas-confetti/1.9.3/confetti.min.js"></script>

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
	
	// 🔴 VERIFICAR CITAS SIN TIPIFICAR
	<?php
		// Buscar citas sin tipificar (tip_cit vacío o null)
		$sqlSinTipificar = "
			SELECT COUNT(*) as total
			FROM cita 
			WHERE (tip_cit IS NULL OR tip_cit = '' OR TRIM(tip_cit) = '')
			AND cit_cit BETWEEN '$inicio' AND '$fin'
			AND cla_cit = 'Cita'
			AND est_cit != 'REGISTRO'
		";
		$resultadoSinTipificar = mysqli_query($db, $sqlSinTipificar);
		if($resultadoSinTipificar) {
			$filaSinTip = mysqli_fetch_assoc($resultadoSinTipificar);
			$totalSinTipificar = $filaSinTip['total'];
			echo "var totalCitasSinTipificar = ".$totalSinTipificar.";\n";
		} else {
			echo "var totalCitasSinTipificar = 0;\n";
		}
	?>
	
	// Mostrar toast danger si hay citas sin tipificar
	if(totalCitasSinTipificar > 0) {
		setTimeout(function() {
			toastr.error('⚠️ HAY ' + totalCitasSinTipificar + ' CITA(S) SIN TIPIFICAR', 'ATENCIÓN', {
				timeOut: 8000,
				closeButton: true,
				progressBar: true,
				positionClass: "toast-top-right"
			});
		}, 500);
	}

	// PASO 1
	// CAMBIO DE HEADERS
	var colHeaders = [ '<?php echo strtoupper(fechaFormateadaCompacta2($inicio)); ?>', 'HORARIO', 'FECHA', 'AGENDO', 'EJECUTIVO', 'CONSULTOR', 'TIPO DE CITA', 'NOMBRE', 'EDAD', 'NUMERO', 'MODALIDAD', 'MERCADO', 'ESTATUS', 'CALIDAD DE CITA', 'URL', 'OBSERVACIONES', 'FOLIO', 'ID_ALU', 'BOOL1', 'BOOL2', 'CAM_CIT', 'LIG_STR_CIT' ];

	// PASO 2 
	// *RELLENO DE COLS VACIAS (ES DINÁMICO NO LE MUEVAS, CABRON :D)
	var data = Array(0).fill(0).map(() => new Array(colHeaders.length).fill(""));

	
<?php
	
	$startTime = strtotime('8:00 AM');
	$endTime = strtotime('10:00 PM');
	
	$timeIncrement = 60 * 60;

	$data = []; // Para almacenar todos los datos incluyendo horarios y citas
	
	while ($startTime <= $endTime) {
		$currentHour = date('H:i', $startTime);
		$nextHour = date('H:i', $startTime + $timeIncrement);

		// PASO 3
		// *RELLENO DE COLS VACIAS EN HORARIOS
		$data[] = [horaFormateadaCompacta2($currentHour), "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", horaFormateadaCompacta2($currentHour), "--", "--", null];
		
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
					cita.url_cit,
					(IF((SELECT COUNT(id_alu) FROM alumno WHERE id_cit1 = cita.id_cit) > 0, 'true', 'false')) AS res,
					ejecutivo.nom_eje AS nom_eje,
					ejecutivo_cerrador.nom_eje AS nom_eje_cerrador,
					ejecutivo_agendo.nom_eje AS nom_eje_agendo,
					cita.lig_str_cit
					FROM cita
					INNER JOIN ejecutivo ON ejecutivo.id_eje = cita.id_eje3
					LEFT JOIN ejecutivo AS ejecutivo_cerrador ON ejecutivo_cerrador.id_eje = cita.id_eje_cerrador
					LEFT JOIN ejecutivo AS ejecutivo_agendo ON ejecutivo_agendo.id_eje = cita.id_eje_agendo
					WHERE cla_cit = 'Cita' 
					AND id_eje3 IN (
						SELECT id_eje FROM ejecutivo 
						WHERE usu_eje IS NULL 
						AND tip_eje = 'Ejecutivo'
					) AND ejecutivo.id_pla = '$plantel'
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
					cita.url_cit,
					(IF((SELECT COUNT(id_alu) FROM alumno WHERE id_cit1 = cita.id_cit) > 0, 'true', 'false')) AS res,
					ejecutivo.nom_eje AS nom_eje,
					ejecutivo_cerrador.nom_eje AS nom_eje_cerrador,
					ejecutivo_agendo.nom_eje AS nom_eje_agendo,
					cita.lig_str_cit
					FROM cita
					INNER JOIN ejecutivo ON ejecutivo.id_eje = cita.id_eje3
					LEFT JOIN ejecutivo AS ejecutivo_cerrador ON ejecutivo_cerrador.id_eje = cita.id_eje_cerrador
					LEFT JOIN ejecutivo AS ejecutivo_agendo ON ejecutivo_agendo.id_eje = cita.id_eje_agendo
					WHERE cla_cit = 'Cita' 
					AND id_eje3 IN (
						SELECT id_eje FROM ejecutivo 
						WHERE usu_eje IS NULL 
						AND tip_eje = 'Ejecutivo'
					) AND (
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
					cita.url_cit,
					(IF((SELECT COUNT(id_alu) FROM alumno WHERE id_cit1 = cita.id_cit) > 0, 'true', 'false')) AS res,
					ejecutivo.nom_eje AS nom_eje,
					ejecutivo_cerrador.nom_eje AS nom_eje_cerrador,
					ejecutivo_agendo.nom_eje AS nom_eje_agendo,
					cita.lig_str_cit
					FROM cita
					INNER JOIN ejecutivo ON ejecutivo.id_eje = cita.id_eje3
					LEFT JOIN ejecutivo AS ejecutivo_cerrador ON ejecutivo_cerrador.id_eje = cita.id_eje_cerrador
					LEFT JOIN ejecutivo AS ejecutivo_agendo ON ejecutivo_agendo.id_eje = cita.id_eje_agendo
					WHERE cla_cit = 'Cita' 
					AND id_eje3 IN (
						SELECT id_eje FROM ejecutivo 
						WHERE usu_eje IS NULL 
						AND tip_eje = 'Ejecutivo'
					) AND DATE(cit_cit) BETWEEN DATE('$inicio') AND DATE('$fin')
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
							cita.url_cit,
							(IF((SELECT COUNT(id_alu) FROM alumno WHERE id_cit1 = cita.id_cit) > 0, 'true', 'false')) AS res,
							ejecutivo.nom_eje AS nom_eje,
							ejecutivo_cerrador.nom_eje AS nom_eje_cerrador,
							ejecutivo_agendo.nom_eje AS nom_eje_agendo,
							cita.lig_str_cit
							FROM cita
							INNER JOIN ejecutivo ON ejecutivo.id_eje = cita.id_eje3
							LEFT JOIN ejecutivo AS ejecutivo_cerrador ON ejecutivo_cerrador.id_eje = cita.id_eje_cerrador
							LEFT JOIN ejecutivo AS ejecutivo_agendo ON ejecutivo_agendo.id_eje = cita.id_eje_agendo
							WHERE cla_cit = 'Cita' 
							AND id_eje3 IN (
								SELECT id_eje FROM ejecutivo 
								WHERE usu_eje IS NULL 
								AND tip_eje = 'Ejecutivo'
							) AND ejecutivo.id_pla = '$plantel' 
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
							cita.url_cit,
							(IF((SELECT COUNT(id_alu) FROM alumno WHERE id_cit1 = cita.id_cit) > 0, 'true', 'false')) AS res,
							ejecutivo.nom_eje AS nom_eje,
							ejecutivo_cerrador.nom_eje AS nom_eje_cerrador,
							ejecutivo_agendo.nom_eje AS nom_eje_agendo,
							cita.lig_str_cit
							FROM cita
							INNER JOIN ejecutivo ON ejecutivo.id_eje = cita.id_eje3
							LEFT JOIN ejecutivo AS ejecutivo_cerrador ON ejecutivo_cerrador.id_eje = cita.id_eje_cerrador
							LEFT JOIN ejecutivo AS ejecutivo_agendo ON ejecutivo_agendo.id_eje = cita.id_eje_agendo
							WHERE cla_cit = 'Cita' 
							AND id_eje3 IN (
								SELECT id_eje FROM ejecutivo 
								WHERE usu_eje IS NULL 
								AND tip_eje = 'Ejecutivo'
							) AND DATE(cit_cit) BETWEEN DATE('$inicio') AND DATE('$fin') AND (
						";
						$sql .= $sqlEjecutivos;
			
						$sql .= " AND hor_cit >= '$currentHour' AND hor_cit < '$nextHour'";
					}
			
				} else {
					$sql = "
						SELECT *,
						cita.url_cit,
						(IF((SELECT COUNT(id_alu) FROM alumno WHERE id_cit1 = cita.id_cit) > 0, 'true', 'false')) AS res,
						ejecutivo.nom_eje AS nom_eje,
						ejecutivo_cerrador.nom_eje AS nom_eje_cerrador,
						ejecutivo_agendo.nom_eje AS nom_eje_agendo,
						cita.lig_str_cit
						FROM cita
						INNER JOIN ejecutivo ON ejecutivo.id_eje = cita.id_eje3
						LEFT JOIN ejecutivo AS ejecutivo_cerrador ON ejecutivo_cerrador.id_eje = cita.id_eje_cerrador
						LEFT JOIN ejecutivo AS ejecutivo_agendo ON ejecutivo_agendo.id_eje = cita.id_eje_agendo
						WHERE cla_cit = 'Cita' 
						AND id_eje3 IN (
							SELECT id_eje FROM ejecutivo 
							WHERE usu_eje IS NULL 
							AND tip_eje = 'Ejecutivo'
						) AND id_eje3 = '$id_eje' 
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

		if (!empty($citas)) {
			foreach ($citas as $fila) {
				// VALIDACION 
				$id_cit = $fila['id_cit'];
				$validadorRegistro = 'false';
				$id_alu_ram_aux = 'false';

				// VALIDACION EXISTENCIA id_alu PERO NO id_alu_ram
				if( $fila['res'] == 'true' ){
					
					$sqlAlumnoRama = "
						SELECT *
						FROM alu_ram
						INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
						WHERE id_cit1 = $id_cit
					";
					$datosAlumnoRama = obtener_datos_consulta( $db, $sqlAlumnoRama );
					

					if( $datosAlumnoRama['total'] > 0 ){
						$validadorRegistro = 'true';
						$id_alu_ram_aux = $datosAlumnoRama['datos']['id_alu_ram'];
					}
				}
				// F VALIDACION EXISTENCIA id_alu PERO NO id_alu_ram
				
				
				// F VALIDACION
				$cambioCitasEstatico = 1000;

				$data[] = [
					"--", // 0
					horaFormateadaCompacta2($fila['hor_cit']), // 1
					fechaFormateadaCompacta($fila['cit_cit']), // 2
					isset($fila['nom_eje_agendo']) ? $fila['nom_eje_agendo'] : null, // 3
					isset($fila['nom_eje_cerrador']) ? $fila['nom_eje_cerrador'] : null, // 4
					isset($fila['nom_eje']) ? $fila['nom_eje'] : null, // 5
					isset($fila['tip_cit']) ? $fila['tip_cit'] : null, // 6
					$fila['nom_cit'], // 7
					isset($fila['eda_cit']) ? $fila['eda_cit'] : null, // 8
					isset($fila['tel_cit']) ? $fila['tel_cit'] : null, // 9
					isset($fila['pro_cit']) ? $fila['pro_cit'] : null, // 10
					isset($fila['can_cit']) ? $fila['can_cit'] : null, // 11
					$fila['est_cit'], // 12
					isset($fila['efe_cit']) ? $fila['efe_cit'] : null, // 13
					isset($fila['url_cit']) ? $fila['url_cit'] : null, // 14 - NUEVA URL
					isset($fila['obs_cit']) ? $fila['obs_cit'] : null, // 15
					$fila['id_cit'], // 16
					$fila['res'], // 17
					$validadorRegistro, // 18
					$id_alu_ram_aux, // 19
					$cambioCitasEstatico, // 20
					isset($fila['lig_str_cit']) ? $fila['lig_str_cit'] : null, // 21 - NUEVO
					"--" // 22
				];


				
			}
			$data[] = ["--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", horaFormateadaCompacta2($currentHour), "--", "--", null];

		} else {
			// RELLENO DE FILAS VACÍAS
			for ($i = 0; $i < 4; $i++) {
				$data[] = ["--", "--", "--", "--", "--", "--",  "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", horaFormateadaCompacta2($currentHour), "--", "--", null];
			}
		}

		// Incrementar startTime para el próximo intervalo
		$startTime += $timeIncrement;

	}

	$json_data = json_encode($data);

	// Obtener todos los colores de las celdas para las citas del rango actual
	$colores_celdas = [];

	// Primero recolectamos todos los id_cit del array $data
	$ids_citas = [];
	foreach ($data as $fila) {
		if (isset($fila[16]) && is_numeric($fila[16]) && $fila[16] > 0) {
			$ids_citas[] = intval($fila[16]);
		}
	}

	// Si hay citas, consultamos sus colores
	if (!empty($ids_citas)) {
		$ids_string = implode(',', array_unique($ids_citas));
		
		$sqlColores = "
			SELECT id_cit5, campo_cita, color_celda 
			FROM color_cita 
			WHERE id_cit5 IN ($ids_string)
		";
		
		$resultadoColores = mysqli_query($db, $sqlColores);
		
		if ($resultadoColores) {
			while ($filaColor = mysqli_fetch_assoc($resultadoColores)) {
				$id_cit = $filaColor['id_cit5'];
				$campo = $filaColor['campo_cita'];
				$color = $filaColor['color_celda'];
				
				if (!isset($colores_celdas[$id_cit])) {
					$colores_celdas[$id_cit] = [];
				}
				$colores_celdas[$id_cit][$campo] = $color;
			}
		}
	}

	// Convertir a JSON para JavaScript
	$json_colores = json_encode($colores_celdas);
?>
	var data = <?php echo $json_data; ?>

	// Definir los colores CSS pasteles
	if (typeof window.coloresPastel === 'undefined') {
		window.coloresPastel = {
			'verde_pastel': '#D4F4DD',
			'azul_pastel': '#E3F2FD', 
			'naranja_pastel': '#FFE0B2'
		};
	}

	// Variable para almacenar colores de celdas
	var coloresCeldas = <?php echo $json_colores; ?>;

	// CSS que necesitas agregar
	if (typeof window.colorMenuStylesLoaded === 'undefined') {
		window.colorMenuStylesLoaded = true;
		const colorMenuStyles = `
			<style>
			.color-circle {
				display: inline-block;
				width: 16px;
				height: 16px;
				border-radius: 50%;
				margin-right: 8px;
				border: 2px solid #333;
				vertical-align: middle;
			}
			.color-circle.verde { background-color: #D4F4DD; }
			.color-circle.azul { background-color: #E3F2FD; }
			.color-circle.naranja { background-color: #FFE0B2; }
			.color-circle.quitar { 
				background: linear-gradient(135deg, transparent 40%, red 40%, red 60%, transparent 60%);
				border-color: red;
			}
		</style>`;

		// Agregar los estilos al head
		document.head.insertAdjacentHTML('beforeend', colorMenuStyles);
	}

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
				WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND usu_eje IS NULL AND id_pla = $plantel AND id_eje != 2311
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
				WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND usu_eje IS NULL AND id_pla IN ($plantelesList) AND id_eje != 2311
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
				WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND usu_eje IS NULL AND id_pla = $plantel AND id_eje != 2311
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
				WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND usu_eje IS NULL AND id_pla = $plantel AND id_eje != 2311
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
				WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND usu_eje IS NULL AND id_pla IN ($plantelesList) AND id_eje != 2311
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
				WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND usu_eje IS NULL AND id_pla = $plantel AND id_eje != 2311
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
				WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND usu_eje IS NULL AND id_pla = $plantel AND id_eje != 2311
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
				WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND usu_eje IS NULL AND id_pla IN ($plantelesList) AND id_eje != 2311
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
				WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND usu_eje IS NULL AND id_pla = $plantel AND id_eje != 2311
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

	// FUNCIÓN DE VALIDACIÓN URL - DEBE IR AQUÍ ANTES DE LOS RENDERERS
	function esURLValida(url) {
		if (!url || url === '--' || url === '' || url === null) return false;
		const regex = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/i;
		return regex.test(url);
	}

	if (hot) {
	    hot.destroy();
	}

	function firstColumnRenderer2(instance, td, row, col, prop, value, cellProperties) {
		Handsontable.renderers.TextRenderer.apply(this, arguments);
		td.style.backgroundColor = '#CFE2F2'; // Cambia el color de fondo
		
		// Verificar si hay una cita en esta fila (validando que tenga un id_cit)
		const id_cit = instance.getDataAtCell(row, 16); // id_cit está en la columna 16
		const estatus = instance.getDataAtCell(row, 12); // Estatus de la cita
		const lig_str_cit = instance.getDataAtCell(row, 21); // Liga de pago en columna 21
		
		// Solo mostrar el enlace si: hay id_cit válido, estatus no es "REGISTRO" y lig_str_cit es 1
		if (id_cit && id_cit !== '--' && id_cit !== "" && !isNaN(id_cit) && 
			estatus && estatus !== "REGISTRO" && estatus !== "--" && 
			lig_str_cit == 1) {
			td.innerHTML = '<a href="javascript:void(0);" onclick="abrirModalLigaPago(' + id_cit + ')" style="color: blue; text-decoration: underline; cursor: pointer;"><i class="fas fa-link"></i> LIGA DE PAGO</a>';
		}
	}


	function abrirModalLigaPago(id_cit) {
		// Buscar la fila que corresponde a este id_cit para obtener la modalidad
		var modalidad = null;
		var totalRows = hot.countRows();
		
		for (var i = 0; i < totalRows; i++) {
			if (hot.getDataAtCell(i, 16) == id_cit) {
				modalidad = hot.getDataAtCell(i, 10); // Modalidad está en columna 10
				break;
			}
		}
		
		// Si no hay modalidad, usar una por defecto o vacía
		if (!modalidad || modalidad === '--' || modalidad === '' || modalidad === null) {
			modalidad = 'DIPLOMADO'; // O la que consideres por defecto
		}
		
		// Limpiar contenedor
		$('#contenedor_modal_liga').html('');
		
		// Cargar contenido dinámicamente
		$.ajax({
			url: 'server/obtener_formulario_liga.php',
			type: 'POST',
			data: { 
				id_cit: id_cit,
				modalidad: modalidad
			},
			success: function(respuesta) {
				$('#contenedor_modal_liga').html(respuesta);
				$('#modalLigaPago').modal('show');
			},
			error: function() {
				toastr.error('Error al cargar el formulario de liga de pago');
			}
		});
	}

	function firstColumnRenderer(instance, td, row, col, prop, value, cellProperties) {
		Handsontable.renderers.TextRenderer.apply(this, arguments);
		td.style.backgroundColor = '#E3E6E7'; // Mantiene el color de fondo existente

		if (col === 16 && !isNaN(value) && value !== null && value !== '') {
			const id_cit = instance.getDataAtCell(row, 16); // Asumiendo que el id_cit está en la columna 16
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
		
		// ----------
		cells: function deshabilitarFila(row, col) {
			var cellProperties = {};

			// PASO 1: CONFIGURACIONES ESPECÍFICAS POR COLUMNA (ALTA PRIORIDAD)
			
			// Configuración para la columna URL (14) - DEBE IR PRIMERO
			if (col === 14) {
				cellProperties.renderer = function urlRenderer(instance, td, row, col, prop, value, cellProperties) {
					if (esURLValida(value)) {
						td.innerHTML = `<a href="${value.toLowerCase()}" target="_blank" onclick="event.stopPropagation();" style="color: blue; text-decoration: none; line-height: 1; text-transform: lowercase; cursor: pointer;">${value.toLowerCase()}</a>`;
						
						// Agregar hover al enlace
						const link = td.querySelector('a');
						if (link) {
							link.onmouseover = function() {
								this.style.textDecoration = 'underline';
							};
							link.onmouseout = function() {
								this.style.textDecoration = 'none';
							};
						}
					} else {
						Handsontable.renderers.TextRenderer.apply(this, arguments);
					}
				};
				return cellProperties; // Salir temprano para evitar sobrescritura
			}

			// Configuración para columna 0
			if (col === 0) {
				cellProperties.renderer = firstColumnRenderer2;
				return cellProperties;
			}

			// Configuración para columna 13 (efe_cit)
			if (col === 13) {
				var cam_cit = 999; // Obtener el valor de cam_cit
				if (cam_cit == 0) {
					cellProperties.readOnly = true;
				}

				cellProperties.renderer = function(instance, td, row, col, prop, value, cellProperties) {
					Handsontable.renderers.TextRenderer.apply(this, arguments);

					switch (value) {
						case 'CITA EFECTIVA':
							td.style.backgroundColor = '#FFC0CB';
							td.style.color = '#FF0000';
							break;
						case 'CITA NO EFECTIVA':
							td.style.backgroundColor = '#FF6666';
							td.style.color = '#FFFFFF';
							break;
						default:
							td.style.backgroundColor = '';
							td.style.color = '';
							break;
					}
				};
				return cellProperties;
			}

			// PASO 2: VALIDACIONES DE FILAS COMPLETAS (MEDIA PRIORIDAD)
			
			// Validador para filas sin id_alu_ram (error de registro)
			if (this.instance.getDataAtRowProp(row, 18) === 'false' && 
				this.instance.getDataAtRowProp(row, 12) === 'REGISTRO' && 
				this.instance.getDataAtRowProp(row, 17) == 'true') {
				
				cellProperties.renderer = function(instance, td, row, col, prop, value, cellProperties) {
					Handsontable.renderers.TextRenderer.apply(this, arguments);
					td.style.color = '#ff0000';
					td.style.backgroundColor = '#FFC0CB';
				};

				var nombre = this.instance.getDataAtRowProp(row, 7);
				var folio = this.instance.getDataAtRowProp(row, 16);
				var errorKey = nombre + '-' + folio;

				if (!errorNotificado[errorKey]) {
					toastr.error('ERROR AL REGISTRAR CITA PARA NOMBRE: ' + nombre + ' --- FOLIO: ' + folio + '<br>¡VUELVE A REGISTRAR!');
					errorNotificado[errorKey] = true;
				}
				return cellProperties;
			}

			// Validador para registros válidos (color azul)
			if (this.instance.getDataAtRowProp(row, 12) === 'REGISTRO' && 
				this.instance.getDataAtRowProp(row, 17) == 'true') {
				
				if (row === editRow) {
					cellProperties.readOnly = false;
				} else {
					cellProperties.readOnly = true;
				}
				
				cellProperties.renderer = function(instance, td, row, col, prop, value, cellProperties) {
					Handsontable.renderers.TextRenderer.apply(this, arguments);
					td.style.backgroundColor = '#00FFFF';
				};
				return cellProperties;
			}

			// 🔴 VALIDADOR PARA CITAS AGENDADAS VENCIDAS (MÁS DE 30 MIN DESPUÉS DEL HORARIO)
			var estatusCita = this.instance.getDataAtRowProp(row, 12); // Columna ESTATUS (12)
			var horarioCita = this.instance.getDataAtRowProp(row, 1); // Columna HORARIO (1)
			
			// Solo aplicar si NO es REGISTRO y el estatus es CITA AGENDADA
			if (estatusCita === 'CITA AGENDADA' && horarioCita && horarioCita !== '--') {
				// Obtener la hora actual
				var ahora = new Date();
				
				// Parsear el horario de la cita (formato: "HH:MM AM/PM")
				var horaParts = horarioCita.match(/(\d{1,2}):(\d{2})\s*(AM|PM)/i);
				
				if (horaParts) {
					var hora = parseInt(horaParts[1]);
					var minutos = parseInt(horaParts[2]);
					var periodo = horaParts[3].toUpperCase();
					
					// Convertir a formato 24 horas
					if (periodo === 'PM' && hora !== 12) {
						hora += 12;
					} else if (periodo === 'AM' && hora === 12) {
						hora = 0;
					}
					
					// Crear objeto Date con la hora de la cita
					var horaCita = new Date();
					horaCita.setHours(hora, minutos, 0, 0);
					
					// Calcular diferencia en minutos
					var diferenciaMs = ahora - horaCita;
					var diferenciaMinutos = Math.floor(diferenciaMs / (1000 * 60));
					
					// Si han pasado más de 30 minutos, aplicar FONDO ROJO COMPLETO (como REGISTRO)
					if (diferenciaMinutos > 30) {
						cellProperties.renderer = function(instance, td, row, col, prop, value, cellProperties) {
							Handsontable.renderers.TextRenderer.apply(this, arguments);
							td.style.backgroundColor = '#FF6666'; // Fondo rojo como REGISTRO
							td.style.color = '#FFFFFF'; // Texto blanco
						};
						return cellProperties;
					}
				}
			}

			// PASO 3: CONFIGURACIONES ESPECIALES POR COLUMNA (MEDIA PRIORIDAD)
			
			// Configuración para columna 12 (estatus de la cita)
			if (col === 12) {
				cellProperties.renderer = function(instance, td, row, col, prop, value, cellProperties) {
					Handsontable.renderers.TextRenderer.apply(this, arguments);
					const status = statusConfig.getStatusByLabel(value);
					td.style.backgroundColor = status ? status.color : '';
				};
				return cellProperties;
			}

			// Otros renderers según las condiciones (columnas 16, 5, 3)
			if (col === 16 <?php echo ( ( $rangoUsuario != 'GC' && $rangoUsuario != 'TL' ) && $permisos != 1 && $permisos != 2  )? ' || col === 5': ''; ?> <?php echo ( $rangoUsuario != 'GC' || $rangoUsuario != 'DC'  )? ' || col === 12': ''; ?> <?php echo ( $rangoUsuario != 'DM' && $rangoUsuario != 'GC' && $rangoUsuario != 'DC' ) ? ' || col === 3': ''; ?>) { 
				cellProperties.renderer = firstColumnRenderer;
				return cellProperties;
			}

			// PASO 4: SISTEMA DE COLORES PERSONALIZADOS (BAJA PRIORIDAD)
			
			var id_cit = this.instance.getDataAtCell(row, 16);
			var campo = obtenerCampoValor(col);

			// Verificar si hay color personalizado
			var tieneColorPersonalizado = id_cit && campo && coloresCeldas[id_cit] && coloresCeldas[id_cit][campo];

			if (tieneColorPersonalizado) {
				var colorPersonalizado = coloresCeldas[id_cit][campo];
				
				// Solo aplicar el color si no hay otros estilos de sistema con mayor prioridad
				var est_cit = this.instance.getDataAtCell(row, 12);
				var tieneRegistro = (est_cit === 'REGISTRO' && this.instance.getDataAtCell(row, 17) == 'true');
				var tieneErrorRegistro = (this.instance.getDataAtRowProp(row, 18) === 'false' && est_cit === 'REGISTRO' && this.instance.getDataAtRowProp(row, 17) == 'true');
				
				// No aplicar color personalizado si tiene estilos del sistema
				if (!tieneRegistro && !tieneErrorRegistro && col !== 12 && col !== 13 && col !== 14) {
					cellProperties.renderer = function(instance, td, row, col, prop, value, cellProperties) {
						Handsontable.renderers.TextRenderer.apply(this, arguments);
						td.style.backgroundColor = window.coloresPastel[colorPersonalizado];
					};
				}
			} else {
				// Limpiar colores previos si no hay color personalizado
				if (id_cit && campo && col !== 12 && col !== 13 && col !== 14) {
					var est_cit = this.instance.getDataAtCell(row, 12);
					var tieneRegistro = (est_cit === 'REGISTRO' && this.instance.getDataAtCell(row, 17) == 'true');
					var tieneErrorRegistro = (this.instance.getDataAtRowProp(row, 18) === 'false' && est_cit === 'REGISTRO' && this.instance.getDataAtRowProp(row, 17) == 'true');
					
					// Solo limpiar si no tiene estilos del sistema
					if (!tieneRegistro && !tieneErrorRegistro) {
						cellProperties.renderer = function(instance, td, row, col, prop, value, cellProperties) {
							Handsontable.renderers.TextRenderer.apply(this, arguments);
							td.style.backgroundColor = '';
						};
					}
				}
			}

			return cellProperties;
		},

		// ----------
		
		height: 'auto',
		width: '100%',

		// PASO 17
		// OCULTAMIENTO DE COLUMNAS, RECORRER SEGÚN EL CASO
		hiddenColumns: {
			columns: [ 17, 18, 19, 20, 21 ],
			indicators: false
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
		// ------------------
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
								if (this.getDataAtCell(row, 12) === 'REGISTRO' && 
									this.getDataAtCell(row, 17) === 'true') {
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
				"liga_pago": {
					name: function() {
						var selected = this.getSelected();
						if (selected) {
							var row = selected[0][0];
							var lig_str_cit = this.getDataAtCell(row, 21);
							return lig_str_cit == 1 ? 'OCULTAR LIGA DE PAGO' : 'MOSTRAR LIGA DE PAGO';
						}
						return 'LIGA DE PAGO';
					},
					callback: function() {
						var selected = this.getSelected();
						if (selected) {
							var row = selected[0][0];
							var id_cit = this.getDataAtCell(row, 16);
							var estatus = this.getDataAtCell(row, 12);
							var lig_str_cit = this.getDataAtCell(row, 21);
							
							if (id_cit && id_cit !== '--' && id_cit !== "" && !isNaN(id_cit) && 
								estatus && estatus !== "REGISTRO" && estatus !== "--") {
								
								var nuevoValor = lig_str_cit == 1 ? 0 : 1;
								activarDesactivarLigaPago(id_cit, nuevoValor, row);
								
							} else {
								toastr.error('No se puede activar liga de pago para esta cita');
							}
						}
					}
				},

				// NUEVA OPCIÓN: LIGA DE PREINSCRIPCIÓN
				"liga_preinscripcion": {
					name: 'PDF - SOLICITUD DE BECA',
					callback: function() {
						var selected = this.getSelected();
						if (selected) {
							var row = selected[0][0];
							var id_cit = this.getDataAtCell(row, 16);
							var estatus = this.getDataAtCell(row, 12);
							
							if (id_cit && id_cit !== '--' && id_cit !== "" && !isNaN(id_cit) && 
								estatus && estatus !== "--") {
								
								// Abrir la liga en una nueva ventana
								var url = 'solicitud_preinscripcion.php?id_cit=' + id_cit;
								window.open(url, '_blank');
								
							} else {
								toastr.error('No se puede abrir solicitud de beca para esta cita');
							}
						}
					}
				},

				// SEPARATOR
				"sep1": Handsontable.plugins.ContextMenu.SEPARATOR,

				"verde_pastel": {
					name: '<span class="color-circle verde"></span>Verde Pastel',
					disabled: function() {
						var selected = this.getSelected();
						if (selected) {
							var row = selected[0][0];
							var col = selected[0][1];
							var id_cit = this.getDataAtCell(row, 16);
							return !id_cit || isNaN(id_cit) || col === 12 || col === 13;
						}
						return true;
					},
					callback: function() {
						aplicarColorCelda('verde_pastel');
					}
				},

				"azul_pastel": {
					name: '<span class="color-circle azul"></span>Azul Pastel',
					disabled: function() {
						var selected = this.getSelected();
						if (selected) {
							var row = selected[0][0];
							var col = selected[0][1];
							var id_cit = this.getDataAtCell(row, 16);
							return !id_cit || isNaN(id_cit) || col === 12 || col === 13;
						}
						return true;
					},
					callback: function() {
						aplicarColorCelda('azul_pastel');
					}
				},

				"naranja_pastel": {
					name: '<span class="color-circle naranja"></span>Naranja Pastel',
					disabled: function() {
						var selected = this.getSelected();
						if (selected) {
							var row = selected[0][0];
							var col = selected[0][1];
							var id_cit = this.getDataAtCell(row, 16);
							return !id_cit || isNaN(id_cit) || col === 12 || col === 13;
						}
						return true;
					},
					callback: function() {
						aplicarColorCelda('naranja_pastel');
					}
				},

				"quitar_color": {
					name: '<span class="color-circle quitar"></span>Quitar Color',
					disabled: function() {
						var selected = this.getSelected();
						if (selected) {
							var row = selected[0][0];
							var col = selected[0][1];
							var id_cit = this.getDataAtCell(row, 16);
							return !id_cit || isNaN(id_cit) || col === 12 || col === 13;
						}
						return true;
					},
					callback: function() {
						aplicarColorCelda('quitar');
					}
				},

				// SEPARATOR ANTES DE ELIMINAR
				"sep2": Handsontable.plugins.ContextMenu.SEPARATOR,

				<?php 
					if( $rangoUsuario == 'GC' || $rangoUsuario == 'DC' || $rangoUsuario == 'DM' ){
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
											var value = this.getDataAtCell(selectedRow, 16);

											var est_cit = this.getDataAtCell(selectedRow, 12);
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
		// ------------------
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

			<?php 
				if ( $rangoUsuario != 'GC' && $rangoUsuario != 'DC' && $rangoUsuario != 'DM' ) {
			?>
					{
						readOnly: true,
					},
			<?php
				} else {
			?>
					{
						// Columna para id_eje_agendo
						type: 'dropdown',
						source: dropdownAgendo.map(item => item.label)
					},

			<?php
				}
			?>
			
			{
				// EJECUTIVO
				type: 'dropdown',
				source: dropdownCerrador.map(item => item.label)
			},


			<?php 
				if( $rangoUsuario == 'GC' || $rangoUsuario == 'TL' || $permisos == 1 || $permisos == 2 ){
			?>
					{
						// 5
						// CONSULTOR
						type: 'dropdown',
						source: dropdownEjecutivo.map(item => item.label)
					},

			<?php
				} else {
			?>
					{
						// 5
						// EJECUTIVO
						readOnly: true,
					},

			<?php
				}
			?>
			
			{
				//6
				// TIPO DE CITA
				type: 'dropdown',
				source: ['Videoconferencia', 'Presencial', 'Llamada', 'Mensaje']  //
			},
			{
				//7
				// NOMBRE
			},

			{
				//8
				// EDAD
			},

			{
				//9
				// NUMERO
			},
			{
				//10
				// MODALIDAD
				type: 'dropdown',
				source: ['PREPA-1-MES', 'PREPA-6-MESES', 'PREPA-EMPRENDE', 'SEMINARIO', 'DIPLOMADO', 'PROYECTO', 'EXAMEN UNICO', 'LICENCIATURA', 'BACH-NEGOCIOS']  //
			},
			{
				//11
				// MERCADO
				type: 'dropdown',
				source: ["Facebook", 'FANPAGE', "PAUTA ORGÁNICA", "PAUTA AHJ", "TIKTOK", "INSTAGRAM", "Aborde", "Formulario", "Mercado natural", "Mercado frío", "Referidos", "Rezagados", "Módulo", "Re matriculación", "Volantes", "Marketing", "PP"]
			},
			{
				//12
				// ESTATUS
				type: 'dropdown',
				source: statusConfig.getActiveStatuses().map(status => status.label)
			},

			{
				//13!!!!!!
				// ESTATUS
				type: 'dropdown',
				source: ['CITA EFECTIVA', 'CITA NO EFECTIVA']
			},

			{
				//14
				// URL MEET - NUEVA COLUMNA
				width: 300, 
				wordWrap: false,
			},

			{
				//15
				// OBSERVACIONES
				width: 500, 
				wordWrap: false,
			},
			{
				//14
				// FOLIO
				readOnly: true,
			},
			{
				//15
				// ID_ALU
				readOnly: true,
			},
			{
				//16
				// BOOL1
				readOnly: true,
			},
			{
				//17
				// BOOL2
				readOnly: true,
			},
			{
				//19
				// BOOL3
				readOnly: true,
			},

			{
				//20
				// LIG_STR_CIT
				readOnly: true,
			},

		],
		
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

		// Obtener datos de la columna de estatus
		const statusColumnData = hot.getDataAtCol(12);

		// Contar ocurrencias
		statusColumnData.forEach(function(status) {
			if (status && posiblesEstados.hasOwnProperty(status)) {
				posiblesEstados[status]++;
			}
		});

		// Conteo de "Cita Efectiva" desde la columna 13
		const citaEfectivaColumnData = hot.getDataAtCol(13);
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
		var id = hot.getDataAtCell(row, 16);
    	var nombre = hot.getDataAtCell(row, 7); 
    	var telefono = hot.getDataAtCell(row, 9);
		var cam_cit = hot.getDataAtCell( row, 20 );

		

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
		if( column == 12 ){
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
						var hor_cit = hot.getDataAtCell(row, 18);
						var data_json = { campo, valor, accion, id_eje, hor_cit };
						console.log('hor_cit :D');
					}

					// SELECCION DE INTERSECCION
					celdaSeleccionada = obtenerCeldaSeleccionada();
					// F SELECCION DE INTERSECCION	
					$.ajax({
						url: 'server/controlador_cita.php',
						type: 'POST',
						data: data_json,
						dataType: 'json',
						success: function( data ){
						//success

							console.log( 'response: '+data );

							// PASO 14
							// // CAMBIAR LOS ÍNDICES SEGÚN SEA EL CASO DE LA IMPRESIÓN DE DATOS Y TENER CUIDADO CON MOVER LOS ANTERIORES AL NUEVO
							hot.setDataAtCell(row, 16, data.id_cit); // Folio de la cita
							hot.setDataAtCell(row, 0, '--');
							hot.setDataAtCell(row, 1, data.hor_cit);
							hot.setDataAtCell(row, 2, data.cit_cit); // Fecha formateada de la cita

							hot.setDataAtCell(row, 3, data.nom_eje_agendo); // Estado de la cita
							hot.setDataAtCell(row, 4, data.nom_eje_cerrador); // Estado de la cita
							hot.setDataAtCell(row, 5, data.nom_eje); // Estado de la cita

							hot.setDataAtCell(row, 6, data.tip_cit); // Fecha formateada de la cita
							
							hot.setDataAtCell(row, 7, data.nom_cit); // Nombre de la cita
							hot.setDataAtCell(row, 8, data.eda_cit); // Nombre de la cita

							hot.setDataAtCell(row, 9, data.tel_cit); // Teléfono de la cita

							hot.setDataAtCell(row, 10, data.pro_cit); // Modalidad de interes
							hot.setDataAtCell(row, 11, data.can_cit); // Mercado de citas
							hot.setDataAtCell(row, 12, data.est_cit);

							hot.setDataAtCell(row, 13, data.efe_cit);
							hot.setDataAtCell(row, 14, data.url_cit); // URL de la cita
							hot.setDataAtCell(row, 15, data.obs_cit);

							hot.setDataAtCell(row, 20, data.cam_cit);

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
if (campo == 'est_cit' && valor == 'REGISTRO') {
    hot.deselectCell();
    
    // CONFETI INMEDIATO
    confetti({
        particleCount: 100,
        spread: 70,
        origin: { y: 0.6 }
    });
    
    $.ajax({
        url: 'server/controlador_cita.php',
        type: 'POST',
        data: { campo, valor, accion, id_cit },
        dataType: 'json',
        success: function(data) {
            console.log('edicion REGISTRO ---->' + data);
            
            // Validación de fecha de cita
            if (column == 2 && valor) {
                const partes = valor.split('/');
                const dia = parseInt(partes[0], 10);
                const mes = parseInt(partes[1], 10) - 1;
                const anio = parseInt(partes[2], 10);
                const fecha = new Date(anio, mes, dia);
                
                const hoy = new Date();
                if (fecha.getDate() !== hoy.getDate() || fecha.getMonth() !== hoy.getMonth() || fecha.getFullYear() !== hoy.getFullYear()) {
                    toastr.info('Cita para otro día');
                    obtener_datos();
                } else {
                    toastr.success('Cita para hoy');
                }
                toastr.success('FOLIO: ' + id_cit);
            }
            
            // Limpiar datos si la operación falló
            if (data.resultado == 'false') {
                console.log('Limpiando datos...');
                hot.setDataAtCell(row, 0, '');
                hot.setDataAtCell(row, 5, '');
                hot.setDataAtCell(row, 14, '');
            }
            
            obtenerConteosEstatus(hot);
        }
    });
    
    // Abrir modal de registro
    $('#modal_registro').modal('show');
    $('#id_cit').val(id);
    obtenerNombreDescompuesto(nombre);
    $('#tel_alu').val(telefono);
    obtenerCorreoCompuesto();
    obtener_colegiatura_grupo();
    
} else {
    // NO REGISTRO - Otros estatus
    $.ajax({
        url: 'server/controlador_cita.php',
        type: 'POST',
        data: { campo, valor, accion, id_cit },
        dataType: 'json',
        success: function(data) {
            console.log('edicion NO REGISTRO ---->' + data);
            
            // Manejo especial para columna de calidad de cita
            if (column == 13 && cam_cit === 0) {
                hot.getCellMeta(row, 13).readOnly = true;
                hot.render();
                toastr.info('RECUERDA QUE YA NO PODRÁS CAMBIAR LA CALIDAD DE LA CITA');
            }
            
            // Limpiar datos si la operación falló
            if (data.resultado == 'false') {
                console.log('Limpiando datos...');
                hot.setDataAtCell(row, 0, '');
                hot.setDataAtCell(row, 5, '');
                hot.setDataAtCell(row, 14, '');
            }
            
            obtenerConteosEstatus(hot);
        }
    });
}
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

		//toastr.success('Cambios guardados');

	}

	function activarDesactivarLigaPago(id_cit, nuevoValor, row) {
		$.ajax({
			type: "POST",
			url: "server/controlador_cita.php",
			data: { 
				id_cit: id_cit,
				lig_str_cit: nuevoValor,
				accion: 'actualizar_liga'
			},
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					hot.setDataAtCell(row, 20, nuevoValor);
					
					var mensaje = nuevoValor == 1 ? 'Liga de pago ACTIVADA' : 'Liga de pago DESACTIVADA';
					toastr.success(mensaje);
					
					hot.render();
				} else {
					toastr.error('Error al actualizar liga de pago: ' + response.message);
				}
			},
			error: function() {
				toastr.error('Error de conexión al actualizar liga de pago');
			}
		});
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


	// PASO 17
	// RECORRER SEGÚN EL CASO
	function obtenerCampoValor(column) {
		let columnName;
		if (column == 1) {
			columnName = "hor_cit";
		} else if (column == 2) {
			columnName = "cit_cit";
		} else if (column == 3) {
			columnName = "id_eje_agendo";
		} else if (column == 4) {
			columnName = "id_eje_cerrador";
		} else if (column == 5) {
			columnName = "id_eje_ejecutivo";
		} else if (column == 6) {
			columnName = "tip_cit";
		} else if (column == 7) {
			columnName = "nom_cit";
		} else if (column == 8) {
			columnName = "eda_cit";
		} else if (column == 9) {
			columnName = "tel_cit";
		} else if (column == 10) {
			columnName = "pro_cit";
		} else if (column == 11) {
			columnName = "can_cit";
		} else if (column == 12) {
			columnName = "est_cit";
		} else if (column == 13) {
			columnName = "efe_cit";
		} else if (column == 14) {
			columnName = "url_cit";
		} else if (column == 15) {
			columnName = "obs_cit";
		}
		return columnName;
	}


	function aplicarColorCelda(color) {
		console.log('=== APLICAR COLOR DEBUG ===');
		console.log('Color solicitado:', color);
		
		var selected = hot.getSelected();
		if (selected) {
			var row = selected[0][0];
			var col = selected[0][1];
			var id_cit = hot.getDataAtCell(row, 16);
			var campo = obtenerCampoValor(col);
			
			console.log('Celda:', row, col, 'ID:', id_cit, 'Campo:', campo);
			console.log('Estado ANTES - coloresCeldas[' + id_cit + ']:', coloresCeldas[id_cit]);
			
			if (!id_cit || !campo) {
				toastr.error('Error: Celda no válida para pintar');
				return;
			}
			
			// Preparar datos para enviar
			var accion = "color";
			var valor = color === 'quitar' ? '' : color;
			
			console.log('Enviando al servidor:', { accion, id_cit, campo, color: valor });
			
			$.ajax({
				url: 'server/controlador_cita.php',
				type: 'POST',
				data: {
					accion: accion,
					id_cit: id_cit,
					campo: campo,
					color: valor
				},
				dataType: 'json',
				success: function(response) {
					console.log('Respuesta del servidor:', response);
					
					if (response.resultado === 'exito') {
						console.log('=== PROCESANDO RESPUESTA EXITOSA ===');
						
						// Actualizar la variable local de colores
						if (!coloresCeldas[id_cit]) {
							coloresCeldas[id_cit] = {};
							console.log('Creando nuevo objeto para cita:', id_cit);
						}
						
						if (color === 'quitar') {
							console.log('=== QUITANDO COLOR ===');
							console.log('Antes de eliminar:', coloresCeldas[id_cit]);
							
							// Remover el color de la variable local
							delete coloresCeldas[id_cit][campo];
							console.log('Después de delete:', coloresCeldas[id_cit]);
							
							// Si no quedan más colores para esta cita, eliminar el objeto completo
							if (Object.keys(coloresCeldas[id_cit]).length === 0) {
								delete coloresCeldas[id_cit];
								console.log('Objeto eliminado completamente para cita:', id_cit);
							}
							
							console.log('Estado FINAL coloresCeldas:', coloresCeldas);
							
							toastr.success('Color removido');
						} else {
							console.log('=== APLICANDO COLOR ===');
							coloresCeldas[id_cit][campo] = color;
							console.log('Color aplicado:', color, 'Estado:', coloresCeldas[id_cit]);
							toastr.success('Color aplicado: ' + color.replace('_', ' '));
						}
						
						console.log('=== FORZANDO RE-RENDER ===');
						
						// Forzar re-renderizado inmediato
						hot.render();
						
					} else {
						console.log('ERROR en respuesta:', response);
						toastr.error('Error al aplicar color');
					}
				},
				error: function(xhr, status, error) {
					console.log('ERROR AJAX:', { xhr, status, error });
					toastr.error('Error de conexión al aplicar color');
				}
			});
		}
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