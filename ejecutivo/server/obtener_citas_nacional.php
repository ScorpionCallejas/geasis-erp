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

	obtener_tarjeta_ejecutivo( '<?php echo $id_eje; ?>', '<?php echo $inicio; ?>', '<?php echo $fin; ?>' );
	
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
	var colHeaders = [ '<?php echo strtoupper(fechaFormateadaCompacta2($inicio)); ?>', 'HORARIO', 'FECHA', 'AGENDO', 'EJECUTIVO', 'CONSULTOR', 'TIPO DE CITA', 'NOMBRE', 'EDAD', 'NUMERO', 'MODALIDAD', 'MERCADO', 'ESTATUS', 'CALIDAD DE CITA', 'OBSERVACIONES', 'FOLIO', 'ID_ALU', 'BOOL1', 'BOOL2', 'BOOL3' ];

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
		$data[] = [horaFormateadaCompacta2($currentHour), "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", horaFormateadaCompacta2($currentHour), "--", "--"];
		
		// //////////////
		
		// PASO 4 
		// *ADICIÓN DE NUEVA COLUMNA EN SQL PERO ES OPCIONAL 

		if( $escala == 'plantel' ){

			$id_pla = $_POST['id_pla'];
	
			$sqlEjecutivosPlantel = "
				SELECT * FROM ejecutivo WHERE id_pla = '$id_pla' AND eli_eje = 'Activo' AND tip_eje = 'Ejecutivo'
			";
	
			$resultadoEjecutivosPlantel = mysqli_query( $db, $sqlEjecutivosPlantel );
		
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

			$sql .= "
				AND hor_cit >= '$currentHour' AND hor_cit < '$nextHour'
				ORDER BY hor_cit ASC
			";


		} else {
			
			// 
			if ( $id_eje == 'Todos' ) {
				//echo 'TOOODOS';
				$sqlPlanteles = "
					SELECT *
					FROM planteles_ejecutivo
					INNER JOIN plantel ON plantel.id_pla = planteles_ejecutivo.id_pla
					WHERE id_eje = '$id'
				";
		
				$totalValidacion = obtener_datos_consulta( $db, $sqlPlanteles )['total'];
		
				if( $totalValidacion == 0 ){
				//
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
						WHERE cla_cit = 'Cita' AND ejecutivo.id_pla = '$plantel' AND DATE(cit_cit) BETWEEN DATE('$inicio') AND DATE('$fin') AND hor_cit >= '$currentHour' AND hor_cit < '$nextHour'
						ORDER BY hor_cit ASC
					";
				//
				} else {
				//
					$resultadoPlanteles = mysqli_query( $db, $sqlPlanteles );
		
					$contador = 0;
					$sqlEjecutivos = '';
					while($filaPlanteles = mysqli_fetch_assoc($resultadoPlanteles)) {
						if ($contador > 0) {
							$sqlEjecutivos .= ' OR ';
						}
						$sqlEjecutivos .= '(ejecutivo.id_pla = '.$filaPlanteles['id_pla'].' OR (ejecutivo_agendo.id_pla = '.$filaPlanteles['id_pla'].' AND (ejecutivo_agendo.id_pla = ejecutivo.id_pla OR ejecutivo_agendo.per_eje = 2)))';


						//$sqlEjecutivos .= '(ejecutivo.id_pla = '.$filaPlanteles['id_pla'].' OR ejecutivo_agendo.id_pla = '.$filaPlanteles['id_pla'].')';
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
	
					$sql .= "
						AND hor_cit >= '$currentHour' AND hor_cit < '$nextHour'
						ORDER BY hor_cit ASC
					";
					
				//
				}
		
			} else {
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
					WHERE cla_cit = 'Cita' AND id_eje3 = '$id_eje' AND DATE(cit_cit) BETWEEN DATE('$inicio') AND DATE('$fin') AND hor_cit >= '$currentHour' AND hor_cit < '$nextHour'
					ORDER BY hor_cit ASC
				";
			}
			// 
		}
		
		
		$citas = [];
		
		if( $escala != '' && $escala == 'estructura' ){
			$citas = obtener_tabla_estructura_citas($id_eje, $inicio, $fin, $currentHour, $nextHour, $db, $citas);
		}

		// echo $sql;
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
					isset($fila['obs_cit']) ? $fila['obs_cit'] : null, // 14
					$fila['id_cit'], // 15
					$fila['res'], // 16
					$validadorRegistro, // 17
					$id_alu_ram_aux, // 18
					"--", // 19
					"--" // 20
				];
			}
			$data[] = ["--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", horaFormateadaCompacta2($currentHour), "--", "--"];
			$data[] = ["--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", horaFormateadaCompacta2($currentHour), "--", "--"];
		} else {
			// RELLENO DE FILAS VACÍAS
			for ($i = 0; $i < 4; $i++) {
				$data[] = ["--", "--", "--", "--", "--", "--",  "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", "--", horaFormateadaCompacta2($currentHour), "--", "--"];
			}
		}

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


	<?php 
		if( $permisos == 1 ){
	?>
			var dropdownEjecutivo = [
				<?php
					$sqlCerrador = "
						SELECT *
						FROM ejecutivo
						WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_pla = $plantel AND id_eje != 2311
						ORDER BY nom_eje ASC
					";

					$resultadoCerrador = mysqli_query( $db, $sqlCerrador );

					while( $filaCerrador = mysqli_fetch_assoc( $resultadoCerrador ) ){
						echo '{ label: "'.$filaCerrador['nom_eje'].'", value: '.$filaCerrador['id_eje'].' },';
					}
				?>
			];
	<?php
		} else if( $permisos == 2 ){
	?>
			var dropdownEjecutivo = [
				<?php
					$sqlCerrador = "
						SELECT *
						FROM ejecutivo
						INNER JOIN plantel ON plantel.id_pla = ejecutivo.id_pla
						WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_cad1 = 1 AND id_eje != 2311
						ORDER BY nom_eje ASC
					";

					$resultadoCerrador = mysqli_query( $db, $sqlCerrador );

					while( $filaCerrador = mysqli_fetch_assoc( $resultadoCerrador ) ){
						echo '{ label: "'.$filaCerrador['nom_eje'].'", value: '.$filaCerrador['id_eje'].' },';
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
						WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_pla = $plantel AND id_eje != 2311
						ORDER BY nom_eje ASC
					";

					$resultadoCerrador = mysqli_query( $db, $sqlCerrador );

					while( $filaCerrador = mysqli_fetch_assoc( $resultadoCerrador ) ){
						echo '{ label: "'.$filaCerrador['nom_eje'].'", value: '.$filaCerrador['id_eje'].' },';
					}
				?>
			];

	<?php
		}
	?>



	<?php 
		if( $permisos == 1 ){
	?>
			var dropdownCerrador = [
				<?php
					$sqlCerrador = "
						SELECT *
						FROM ejecutivo
						WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_pla = $plantel AND id_eje != 2311
						ORDER BY nom_eje ASC
					";

					$resultadoCerrador = mysqli_query( $db, $sqlCerrador );

					while( $filaCerrador = mysqli_fetch_assoc( $resultadoCerrador ) ){
						echo '{ label: "'.$filaCerrador['nom_eje'].'", value: '.$filaCerrador['id_eje'].' },';
					}
				?>
			];
	<?php
		} else if( $permisos == 2 ){
	?>
			var dropdownCerrador = [
				<?php
					$sqlCerrador = "
						SELECT *
						FROM ejecutivo
						INNER JOIN plantel ON plantel.id_pla = ejecutivo.id_pla
						WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_cad1 = 1 AND id_eje != 2311
						ORDER BY nom_eje ASC
					";

					$resultadoCerrador = mysqli_query( $db, $sqlCerrador );

					while( $filaCerrador = mysqli_fetch_assoc( $resultadoCerrador ) ){
						echo '{ label: "'.$filaCerrador['nom_eje'].'", value: '.$filaCerrador['id_eje'].' },';
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
						WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_pla = $plantel AND id_eje != 2311
						ORDER BY nom_eje ASC
					";

					$resultadoCerrador = mysqli_query( $db, $sqlCerrador );

					while( $filaCerrador = mysqli_fetch_assoc( $resultadoCerrador ) ){
						echo '{ label: "'.$filaCerrador['nom_eje'].'", value: '.$filaCerrador['id_eje'].' },';
					}
				?>
			];

	<?php
		}
	?>



	<?php 
		if( $permisos == 1 ){
	?>
			var dropdownAgendo = [
				<?php
					$sqlCerrador = "
						SELECT *
						FROM ejecutivo
						WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_pla = $plantel AND id_eje != 2311
						ORDER BY nom_eje ASC
					";

					$resultadoCerrador = mysqli_query( $db, $sqlCerrador );

					while( $filaCerrador = mysqli_fetch_assoc( $resultadoCerrador ) ){
						echo '{ label: "'.$filaCerrador['nom_eje'].'", value: '.$filaCerrador['id_eje'].' },';
					}
				?>
			];
	<?php
		} else if( $permisos == 2 ){
	?>
			var dropdownAgendo = [
				<?php
					$sqlCerrador = "
						SELECT *
						FROM ejecutivo
						INNER JOIN plantel ON plantel.id_pla = ejecutivo.id_pla
						WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_cad1 = 1 AND id_eje != 2311
						ORDER BY nom_eje ASC
					";

					$resultadoCerrador = mysqli_query( $db, $sqlCerrador );

					while( $filaCerrador = mysqli_fetch_assoc( $resultadoCerrador ) ){
						echo '{ label: "'.$filaCerrador['nom_eje'].'", value: '.$filaCerrador['id_eje'].' },';
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
						WHERE tip_eje = 'Ejecutivo' AND eli_eje = 'Activo' AND id_pla = $plantel AND id_eje != 2311
						ORDER BY nom_eje ASC
					";

					$resultadoCerrador = mysqli_query( $db, $sqlCerrador );

					while( $filaCerrador = mysqli_fetch_assoc( $resultadoCerrador ) ){
						echo '{ label: "'.$filaCerrador['nom_eje'].'", value: '.$filaCerrador['id_eje'].' },';
					}
				?>
			];

	<?php
		}
	?>




	//console.log( 'drop: '+dropdownEjecutivo.length );
	
	if (hot) {
	    hot.destroy();
	}

	function firstColumnRenderer2(instance, td, row, col, prop, value, cellProperties) {
		Handsontable.renderers.TextRenderer.apply(this, arguments);
	  	td.style.backgroundColor = '#CFE2F2'; // Cambia el color de fondo
	}

	function firstColumnRenderer(instance, td, row, col, prop, value, cellProperties) {
		Handsontable.renderers.TextRenderer.apply(this, arguments);
	  	td.style.backgroundColor = '#E3E6E7'; // Cambia el color de fondo
	}

	var colorRegistro = '#00FFFF';
	var errorNotificado = {}; // Objeto para rastrear errores notificados
	hot = new Handsontable(container, {
		language: 'es-MX',
		data,

		// PASO 18
		// PINTADO DE COLUMNAS Y VALIDACION DE EXISTENCI DE ALUMNO, RECORRER SEGÚN EL CASO
		cells: function deshabilitarFila(row, col) {
			var cellProperties = {};

			// Asumiendo que el estado está en la columna 'ESTATUS'
			// VALIDADOR REFORZADO CON id_alu_ram (id_alu_ram_aux) EN CASO DE NO EXISTIR SE PINTA LA FILA DE ROJO
			if (this.instance.getDataAtRowProp(row, 17) === 'false' && this.instance.getDataAtRowProp(row, 12) === 'Registro' && this.instance.getDataAtRowProp(row, 16) == 'true') {
				//cellProperties.readOnly = true;
				cellProperties.renderer = function(instance, td, row, col, prop, value, cellProperties) {
					Handsontable.renderers.TextRenderer.apply(this, arguments);
					td.style.color = '#ff0000';  // Color rojo
					td.style.backgroundColor = '#FFC0CB'; // Color rosa
				};

				// 7 nombre
				// 13 cita
				var nombre = this.instance.getDataAtRowProp(row, 7);
				var folio = this.instance.getDataAtRowProp(row, 15);
				var errorKey = nombre + '-' + folio;
				colorRegistro = '#00FFFF';

				if (!errorNotificado[errorKey]) {
					toastr.error('ERROR AL REGISTRAR CITA PARA NOMBRE: ' + nombre + ' --- FOLIO: ' + folio + '<br>¡VUELVE A REGISTRAR!');
					errorNotificado[errorKey] = true;
				}
				
			} else {
				// EN CASO DE EXISTIR, ES AZUL
				if (this.instance.getDataAtRowProp(row, 12) === 'Registro' && this.instance.getDataAtRowProp(row, 16) == 'true') {
					cellProperties.readOnly = true;
					cellProperties.renderer = function(instance, td, row, col, prop, value, cellProperties) {
						Handsontable.renderers.TextRenderer.apply(this, arguments);
						td.style.backgroundColor = '#00FFFF';  // Color cian
					};
				}
			}
			// VALIDADOR REFORZADO CON id_alu_ram EN CASO DE NO EXISTIR SE PINTA LA FILA DE ROJO
			

			if( col === 0 ){
				cellProperties.renderer = firstColumnRenderer2;
			} else if ( col === 15 <?php echo ( $rangoUsuario != 'GC' && $permisos != 1 && $permisos != 2  )? ' || col === 5': ''; ?> <?php echo ( $rangoUsuario != 'GC' || $rangoUsuario != 'DC'  )? ' || col === 12': ''; ?> <?php echo ( $rangoUsuario != 'GC' && $rangoUsuario != 'DC' ) ? ' || col === 3': ''; ?> ) { 
				cellProperties.renderer = firstColumnRenderer;
			}

			if (col === 12) {
				cellProperties.renderer = function (instance, td, row, col, prop, value, cellProperties) {
					Handsontable.renderers.TextRenderer.apply(this, arguments);

					// Establecer estilos en línea según el valor
					switch (value) {
						case 'Agendada':
							td.style.backgroundColor = '#FFFFFF'; // Verde brillante
							break;
						case 'Asesoria Realizada':
							td.style.backgroundColor = '#00FF00'; // Verde brillante
							break;
						case 'En Seguimiento':
							td.style.backgroundColor = '#FFFF00'; // Verde brillante
							break;
						case 'Dejo de Responder':
							td.style.backgroundColor = '#FF9800'; // Verde brillante
							break;
						case 'Cita No atendida':
							td.style.backgroundColor = '#FF6666'; // Rojo claro
							break;
						case 'Cita reagendada':
							td.style.backgroundColor = '#FF9900'; // Naranja
							break;
						case 'Perdido por Precio':
						case 'Perdido por horarios':
						case 'Perdido / Futuro Prospecto':
							td.style.backgroundColor = '#336699'; // Azul oscuro
							break;
						case 'Pago Esperado':
							td.style.backgroundColor = '#FF00FF'; // Magenta
							break;
						case 'Registro':
							td.style.backgroundColor = colorRegistro; // 
							break;
						case 'Cita confirmada':
							td.style.backgroundColor = '#FFFF00'; // Amarillo
							break;
						case 'No le interesa':
							td.style.backgroundColor = '#CC0000'; // Rojo oscuro
							break;
						default:
							td.style.backgroundColor = ''; // Resetear el estilo si es necesario
							break;
					}
				};
			}

			if (col === 13) {
				cellProperties.renderer = function (instance, td, row, col, prop, value, cellProperties) {
					Handsontable.renderers.TextRenderer.apply(this, arguments);

					// Establecer estilos en línea según el valor
					switch (value) {
						case 'CITA EFECTIVA':
							td.style.backgroundColor = '#FFC0CB'; // Rosa
							td.style.color = '#FF0000'; // Rojo para el texto
							break;
						case 'CITA NO EFECTIVA':
							td.style.backgroundColor = '#FF6666'; // Rojo claro
							td.style.color = '#FFFFFF'; // Blanco para el texto
							break;
						default:
							// Resetear estilos si es necesario
							td.style.backgroundColor = '';
							td.style.color = '';
							break;
					}
				};
			}

			return cellProperties;
		},

		
		height: 'auto',
		width: '100%',

		// PASO 17
		// OCULTAMIENTO DE COLUMNAS, RECORRER SEGÚN EL CASO
		hiddenColumns: {
	        columns: [ 16, 17, 18, 19 ], // Esconde la columna en el índice 0 (es decir, la primera columna, que sería el ID)
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
											var value = this.getDataAtCell(selectedRow, 15);

											var est_cit = this.getDataAtCell(selectedRow, 12);
											// console.log("Valor de la primera columna:", value);
											// Verificar si el valor es numérico
											if ( !isNaN(value) && est_cit == 'Registro' ) {
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
				if ( $rangoUsuario != 'GC' && $rangoUsuario != 'DC' ) {
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
				// Columna para id_eje_cerrador
				type: 'dropdown',
				source: dropdownCerrador.map(item => item.label)
			},


			<?php 
				if( $rangoUsuario == 'GC' || $permisos == 1 || $permisos == 2 ){
			?>
					{
						// 5
						// EJECUTIVO
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
				source: ['Videoconferencia', 'Presencial', 'Llamada']  //
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
				source: ['PE', 'EXAMEN ÚNICO', 'LICENCIATURA', 'BACH18']  //
			},
			{
				//11
				// MERCADO
				type: 'dropdown',
				source: ["Facebook", 'FANPAGE', "PAUTA ORGÁNICA", "PAUTA AHJ", "Mercado natural", "Mercado frío", "Referidos", "Rezagados", "Módulo", "Re matriculación", "Volantes", "Marketing", "PP"]
			},
			{
				//12
				// ESTATUS
				type: 'dropdown',
				source: ['Agendada', 'Asesoria Realizada', 'En Seguimiento', 'Dejo de Responder', 'Cita No atendida', 'Cita reagendada', 'Perdido por Precio', 'Perdido por horarios', 'Perdido / Futuro Prospecto', 'Registro', 'Pago Esperado', 'Cita confirmada', 'No le interesa']
			},

			{
				//13!!!!!!
				// ESTATUS
				type: 'dropdown',
				source: ['CITA EFECTIVA', 'CITA NO EFECTIVA']
			},

			{
				width: 500, wordWrap: false,
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
				//13
				// BOOL1
				readOnly: true,
			},
			{
				//14
				// BOOL2
				readOnly: true,
			},
			{
				//15
				// BOOL3
				readOnly: true,
			},
		],
		
	});

	

	


	obtenerConteosEstatus( hot );
	
	// PASO 9
	// RECORRER SEGÚN SEA EL CASO LAS COLUMNAS 
	function obtenerConteosEstatus(hot) {
		// Define los posibles estados.
		var posiblesEstados = {
			'Agendada': 0,
			'Asesoria Realizada': 0,
			'Cita No atendida': 0,
			'Cita reagendada': 0,
			'Perdido por Precio': 0,
			'Perdido por horarios': 0,
			'Perdido / Futuro Prospecto': 0,
			'Pago Esperado': 0,
			'Registro': 0,
			'Cita confirmada': 0,
			'No le interesa': 0,
			'En Seguimiento': 0,
			'Dejo de Responder': 0,
			'Cita Efectiva': 0 // Añadido para CITA EFECTIVA
		};

		// Obtén todos los datos de la columna con índice 12.
		var statusColumnData = hot.getDataAtCol(12);

		// Cuenta las ocurrencias de cada estado.
		statusColumnData.forEach(function(status) {
			if (status && posiblesEstados.hasOwnProperty(status)) {
				posiblesEstados[status]++;
			}
		});

		// Añade el conteo de "Cita Efectiva" desde la columna 13.
		var citaEfectivaColumnData = hot.getDataAtCol(13);
		var conteoCitaEfectiva = citaEfectivaColumnData.filter(function(status) {
			return status === 'CITA EFECTIVA';
		}).length;

		// Suma total
		var conteoTotal = Object.values(posiblesEstados).reduce(function(total, currentValue) {
			return total + currentValue;
		}, 0);

		// Actualiza los elementos HTML con los conteos.
		$('#conteo_total').text(conteoTotal);
		$('#conteo_agendadas').text(posiblesEstados['Agendada']);
		$('#conteo_asesoria_realizada').text(posiblesEstados['Asesoria Realizada']);
		$('#conteo_cita_no_atendida').text(posiblesEstados['Cita No atendida']);
		$('#conteo_cita_reagendada').text(posiblesEstados['Cita reagendada']);
		$('#conteo_perdido_por_precio').text(posiblesEstados['Perdido por Precio']);
		$('#conteo_perdido_por_horarios').text(posiblesEstados['Perdido por horarios']);
		$('#conteo_perdido_futuro_prospecto').text(posiblesEstados['Perdido / Futuro Prospecto']);
		$('#conteo_pago_esperado').text(posiblesEstados['Pago Esperado']);
		$('#conteo_registros').text(posiblesEstados['Registro']);
		$('#conteo_cita_confirmada').text(posiblesEstados['Cita confirmada']);
		$('#conteo_no_le_interesa').text(posiblesEstados['No le interesa']);
		$('#conteo_en_seguimiento').text(posiblesEstados['En Seguimiento']);
		$('#conteo_dejo_de_responder').text(posiblesEstados['Dejo de Responder']);
		
		// Actualiza el conteo específico para "Cita Efectiva"
		$('#total_cita_efectiva').text(conteoCitaEfectiva);
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
		var id = hot.getDataAtCell(row, 15);
    	var nombre = hot.getDataAtCell(row, 7); 
    	var telefono = hot.getDataAtCell(row, 9);

		

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
						var hor_cit = hot.getDataAtCell(row, 17);
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
							hot.setDataAtCell(row, 15, data.id_cit); // Folio de la cita
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

							hot.setDataAtCell(row, 14, data.obs_cit);
							
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
				if ( campo == 'est_cit' && valor == 'Registro' ) {
					hot.deselectCell();
					
					// CODE GOES HERE
					// 
					$.ajax({
						url: 'server/controlador_cita.php',
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
								hot.setDataAtCell(row, 5, '');
								hot.setDataAtCell(row, 14, '');

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
						url: 'server/controlador_cita.php',
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
								hot.setDataAtCell(row, 5, '');
								hot.setDataAtCell(row, 14, '');

							}	
							//console.log(data.resultado);
							obtenerConteosEstatus( hot );
							//obtener_conteos_citas();
							
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