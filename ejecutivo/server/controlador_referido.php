<?php  
	// CONTROLADOR DE ALTA, BAJA Y CAMBIO DE CANDIDATO
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	// Añade el header para JSON
	header('Content-Type: application/json');

	// ========== LOG DEBUG ==========
	function logDebug($mensaje, $datos = null) {
		error_log("🔍 CONTROLADOR_REFERIDO: " . $mensaje);
		if ($datos !== null) {
			error_log("📋 DATOS: " . print_r($datos, true));
		}
	}

	logDebug("===== INICIO REQUEST =====");
	logDebug("POST recibido:", $_POST);
	logDebug("GET recibido:", $_GET);

	// ========== VALIDAR TELÉFONO INDIVIDUAL ==========
	if (isset($_POST['accion']) && $_POST['accion'] == "ValidarTelefono") {
		logDebug("Validando teléfono individual");
		
		$telefono = mysqli_real_escape_string($db, $_POST['telefono']);
		
		logDebug("Teléfono a validar: " . $telefono);
		
		$sql = "SELECT COUNT(*) as total FROM contacto WHERE tel_con = '$telefono'";
		logDebug("Query: " . $sql);
		
		$resultado = mysqli_query($db, $sql);
		$fila = mysqli_fetch_assoc($resultado);
		
		$respuesta = ['disponible' => $fila['total'] == 0];
		logDebug("Respuesta:", $respuesta);
		
		echo json_encode($respuesta);
		exit;
	}

	// ========== VALIDAR TELÉFONOS EN LOTE (CSV) ==========
	if (isset($_POST['accion']) && $_POST['accion'] == "ValidarTelefonosLote") {
		logDebug("Validando teléfonos en lote");
		
		$telefonos = json_decode($_POST['telefonos'], true);
		
		logDebug("Teléfonos recibidos:", $telefonos);
		
		if (!is_array($telefonos) || empty($telefonos)) {
			logDebug("Array vacío o inválido");
			echo json_encode(['disponibles' => [], 'existentes' => []]);
			exit;
		}
		
		// Escapar teléfonos
		$telefonosEscapados = array_map(function($tel) use ($db) {
			return "'" . mysqli_real_escape_string($db, $tel) . "'";
		}, $telefonos);
		
		$telefonosString = implode(',', $telefonosEscapados);
		
		$sql = "SELECT tel_con FROM contacto WHERE tel_con IN ($telefonosString)";
		logDebug("Query lote: " . $sql);
		
		$resultado = mysqli_query($db, $sql);
		
		$telefonosExistentes = [];
		while ($fila = mysqli_fetch_assoc($resultado)) {
			$telefonosExistentes[] = $fila['tel_con'];
		}
		
		logDebug("Existentes en BD:", $telefonosExistentes);
		
		$telefonosDisponibles = array_diff($telefonos, $telefonosExistentes);
		
		$respuesta = [
			'disponibles' => array_values($telefonosDisponibles),
			'existentes' => $telefonosExistentes
		];
		
		logDebug("Respuesta lote:", $respuesta);
		
		echo json_encode($respuesta);
		exit;
	}

	// ========== GUARDAR CONTACTOS CSV BATCH ==========
	if (isset($_GET['tipo']) && $_GET['tipo'] == 'csv_batch') {
		logDebug("===== GUARDANDO BATCH CSV =====");
		
		if (!isset($_POST['contactos'])) {
			logDebug("ERROR: No se recibió 'contactos' en POST");
			echo json_encode([
				'exitosos' => 0,
				'errores' => [['error' => 'No se recibieron contactos']]
			]);
			exit;
		}
		
		$contactosJSON = $_POST['contactos'];
		logDebug("JSON recibido:", $contactosJSON);
		
		$contactos = json_decode($contactosJSON, true);
		
		if (!is_array($contactos)) {
			logDebug("ERROR: JSON inválido");
			echo json_encode([
				'exitosos' => 0,
				'errores' => [['error' => 'JSON inválido']]
			]);
			exit;
		}
		
		logDebug("Total de contactos a insertar: " . count($contactos));
		
		$exitosos = 0;
		$errores = [];
		$cla_con = 'Referido';
		
		foreach ($contactos as $index => $contacto) {
			logDebug("Procesando contacto #" . ($index + 1));
			logDebug("Datos del contacto:", $contacto);
			
			// Escapar datos
			$nombre = mysqli_real_escape_string($db, $contacto['nombre']);
			$telefono = mysqli_real_escape_string($db, $contacto['telefono']);
			$mercado = mysqli_real_escape_string($db, $contacto['mercado']);
			$producto = mysqli_real_escape_string($db, $contacto['producto']);
			$observaciones = mysqli_real_escape_string($db, $contacto['observaciones']);
			
			// Validar duplicado (doble check)
			$sqlCheck = "SELECT COUNT(*) as total FROM contacto WHERE tel_con = '$telefono'";
			$resultadoCheck = mysqli_query($db, $sqlCheck);
			$filaCheck = mysqli_fetch_assoc($resultadoCheck);
			
			if ($filaCheck['total'] > 0) {
				logDebug("DUPLICADO: " . $telefono);
				$errores[] = [
					'fila' => $index + 1,
					'nombre' => $nombre,
					'error' => 'Teléfono duplicado'
				];
				continue;
			}
			
			// INSERT
			$sqlInsert = "
				INSERT INTO contacto (nom_con, tel_con, can_con, pro_con, obs_con, id_eje10, cla_con) 
				VALUES ('$nombre', '$telefono', '$mercado', '$producto', '$observaciones', '$id', '$cla_con')
			";
			
			logDebug("Query INSERT: " . $sqlInsert);
			
			$resultadoInsert = mysqli_query($db, $sqlInsert);
			
			if ($resultadoInsert) {
				$exitosos++;
				logDebug("✅ Insertado correctamente. ID: " . mysqli_insert_id($db));
			} else {
				logDebug("❌ Error al insertar: " . mysqli_error($db));
				$errores[] = [
					'fila' => $index + 1,
					'nombre' => $nombre,
					'error' => mysqli_error($db)
				];
			}
		}
		
		$respuesta = [
			'exitosos' => $exitosos,
			'errores' => $errores
		];
		
		logDebug("===== RESULTADO FINAL =====");
		logDebug("Respuesta:", $respuesta);
		
		echo json_encode($respuesta);
		exit;
	}

	// ========== CONTEOS (YA EXISTÍA) ==========
	if (isset($_POST['conteos']) && $_POST['conteos'] == 'true') {
		$id_eje = $_POST['id_eje'];
		
		// Total registros
		$sqlRegistros = "
			SELECT COUNT(*) as total 
			FROM alumno 
			WHERE id_eje = '$id_eje'
		";
		$resultadoRegistros = mysqli_query($db, $sqlRegistros);
		$filaRegistros = mysqli_fetch_assoc($resultadoRegistros);
		$totalRegistros = $filaRegistros['total'];
		
		// Total referidos
		$sqlReferidos = "
			SELECT COUNT(*) as total 
			FROM contacto 
			WHERE id_eje10 = '$id_eje' AND cla_con = 'Referido'
		";
		$resultadoReferidos = mysqli_query($db, $sqlReferidos);
		$filaReferidos = mysqli_fetch_assoc($resultadoReferidos);
		$totalReferidos = $filaReferidos['total'];
		
		echo json_encode([
			'total_registros' => $totalRegistros,
			'total_referidos' => $totalReferidos
		]);
		exit;
	}

	// ========== LÓGICA ORIGINAL (Alta y Cambio) ==========
	if (!isset($_POST['accion'])) {
		logDebug("ERROR: No se recibió 'accion'");
		echo json_encode(['error' => 'Falta parámetro accion']);
		exit;
	}

	$accion = $_POST['accion'];
	$campo = $_POST['campo'];
	$valor = $_POST['valor'];

	if ( isset( $_POST['id_eje'] )  ) {
		$id_eje = $_POST['id_eje'];

		if( $_POST['id_eje']  == 'Todos' ){
			$id_eje = $id;
		}
	} else {
		$id_eje = $id;
	}
	
	$cla_con = 'Referido';

	if ( $accion == "Alta" ) {
		///////////////////////////////////////////////
		$sql = "INSERT INTO contacto ( $campo, id_eje10, cla_con ) VALUES ( '$valor', '$id_eje', '$cla_con' )";
		
		logDebug("Alta: " . $sql);
		
		$resultado = mysqli_query( $db, $sql );
		if ( !$resultado ) {
			logDebug("Error Alta: " . mysqli_error($db));
			echo json_encode(['error' => $sql]);
		} else {
			//RETORNAR ULTIMO ID :D
			$id_con = mysqli_insert_id($db);

			// ESTATUS Registro
			if( $campo == 'est_con' && $valor == 'Registro' ){
				$cla_cit = 'Referido';
				$id_cit1 = 'nada';

				$sqlDelete = "DELETE FROM cita WHERE id_con2 = $id_con";
				$resultadoDelete = mysqli_query( $db, $sqlDelete );

				if( !$resultadoDelete ){
					echo $sqlDelete;
				}

				$sqlInsert = "
					INSERT INTO cita ( est_cit, id_eje3, cla_cit, id_con2 ) VALUES ( '$valor', '$id_eje', '$cla_cit', '$id_con' )
				";
				$resultadoInsert = mysqli_query( $db, $sqlInsert );
				if( !$resultadoInsert ){
					echo $sqlInsert;
				} else {
					$id_cit1 = mysqli_insert_id($db);
				}
			}
			// F ESTATUS Registro
			
			$sqlDatos = "
				SELECT contacto.*, ejecutivo.nom_eje,
				ejecutivo_asignado.nom_eje AS nom_ejecutivo_asignado
				FROM contacto
				INNER JOIN ejecutivo ON ejecutivo.id_eje = contacto.id_eje10 
				LEFT JOIN ejecutivo AS ejecutivo_asignado ON ejecutivo_asignado.id_eje = contacto.id_ejecutivo
				WHERE id_con = '$id_con'
			";

			$datos = obtener_datos_consulta( $db, $sqlDatos )['datos'];

			if( $campo == 'est_con' && $valor == 'Registro' ){
				echo json_encode( [
					'fec_con' => fechaFormateadaCompacta($datos['fec_con']),
					'nom_eje' => $datos['nom_eje'],
					'nom_ejecutivo_asignado' => isset($datos['nom_ejecutivo_asignado']) ? $datos['nom_ejecutivo_asignado'] : '',
					'tel_con' => $datos['tel_con'],
					'nom_con' => $datos['nom_con'],
					'can_con' => $datos['can_con'],
					'pro_con' => $datos['pro_con'],
					'est_con' => $datos['est_con'],
					'obs_con' => $datos['obs_con'],
					'id_con' => $datos['id_con'],
					'id_cit1' => $id_cit1,
				]);
			} else {
				echo json_encode( [
					'fec_con' => fechaFormateadaCompacta($datos['fec_con']),
					'nom_eje' => $datos['nom_eje'],
					'nom_ejecutivo_asignado' => isset($datos['nom_ejecutivo_asignado']) ? $datos['nom_ejecutivo_asignado'] : '',
					'tel_con' => $datos['tel_con'],
					'nom_con' => $datos['nom_con'],
					'can_con' => $datos['can_con'],
					'pro_con' => $datos['pro_con'],
					'est_con' => $datos['est_con'],
					'obs_con' => $datos['obs_con'],
					'id_con' => $datos['id_con'],
				]);
			}
		}
		//////////////////////////////

	} else if ( $accion == "Cambio" ) {
		//////////////////////////////
		$id_con = $_POST['id_con'];
		//////////////////////////////////// EDICION
		$sql = "
			UPDATE contacto
			SET
			$campo = '$valor'
			WHERE id_con = '$id_con'
		";
		
		logDebug("Cambio: " . $sql);
		
		$resultado = mysqli_query( $db, $sql );
		if ( !$resultado ) {
			logDebug("Error Cambio: " . mysqli_error($db));
			echo $sql;
			echo json_encode(['resultado' => 'error']);
		} else {

			// ESTATUS Registro
			if( $campo == 'est_con' && $valor == 'Registro' ){
				$cla_cit = 'Referido';
				$id_cit1 = 'nada';

				$sqlDelete = "DELETE FROM cita WHERE id_con2 = $id_con";
				$resultadoDelete = mysqli_query( $db, $sqlDelete );

				if( !$resultadoDelete ){
					echo $sqlDelete;
				}

				$sqlInsert = "
					INSERT INTO cita ( est_cit, id_eje3, cla_cit, id_con2 ) VALUES ( '$valor', '$id_eje', '$cla_cit', '$id_con' )
				";
				$resultadoInsert = mysqli_query( $db, $sqlInsert );
				if( !$resultadoInsert ){
					echo $sqlInsert;
				} else {
					$id_cit1 = mysqli_insert_id($db);
				}
			}
			// F ESTATUS Registro

			/////////////////// ELIMINACION
			$sqlDatos = "SELECT * FROM contacto WHERE id_con = $id_con";
			$datos = obtener_datos_consulta( $db, $sqlDatos )['datos'];

			if ( 
				$datos['nom_con'] == '' &&
				$datos['tel_con'] == '' &&
				$datos['can_con'] == '' &&
				$datos['pro_con'] == '' &&
				$datos['est_con'] == '' &&
				$datos['obs_con'] == ''
			) {
				///////////////////////////////////
				$sqlEliminar = "
					DELETE FROM contacto WHERE id_con = '$id_con'
				";
				$resultadoEliminar = mysqli_query( $db, $sqlEliminar );

				if ( !$resultadoEliminar ) {
					echo json_encode(['resultado' => 'error query']);
				} else {
					//////RETORNA 'false', IMPLICA BORRAR EN FRONTEND
					echo json_encode(['resultado' => 'false']);
				}
				////////////////////////////////////
			} else {

				if( $campo == 'est_con' && $valor == 'Registro' ){
					echo json_encode([
						'resultado' => 'exito',
						'id_cit1' => $id_cit1
					]);
				} else {
					echo json_encode(['resultado' => 'exito']);
				}
			}
			///////////////////FIN ELIMINACION	
		}
		/////////////////////////////////// FIN EDICION
	}

	logDebug("===== FIN REQUEST =====");
?>