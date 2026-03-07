<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR NUEVO ALUMNO
	//alumnos_carrera.php///alumnos.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	//enviarCorreoBienvenidaAlumno( $_POST['correo'], $_POST['cor1_alu'], $_POST['pas_alu'], $_POST['nom_alu'], $nombrePlantel, $correo2Plantel, $ligaPlantel, $fotoPlantel );
	if ( ( isset( $_POST['id_ram'] ) && ( isset( $_POST['id_gen'] ) ) ) ) {
	
		// ==========================================
		// 🔍 DEBUG INICIAL - VERIFICAR QUÉ LLEGA
		// ==========================================
		error_log("=================================================");
		error_log("🚀 INICIO REGISTRO ALUMNO");
		error_log("=================================================");
		error_log("📦 POST COMPLETO:");
		error_log(print_r($_POST, true));
		error_log("=================================================");

		$id_ram = array();
		$id_gen = array();
		
		$id_pla = $_POST['id_pla'];
		$id_ram[0] = $_POST['id_ram'];
		$id_gen[0] = $_POST['id_gen'];

		error_log("🎯 Datos básicos capturados:");
		error_log("  - id_pla: " . $id_pla);
		error_log("  - id_ram: " . $id_ram[0]);
		error_log("  - id_gen: " . $id_gen[0]);

		// Capturar forma de titulación si existe
		$forma_titulacion = '';
		if (isset($_POST['forma_titulacion'])) {
			$forma_titulacion = $_POST['forma_titulacion'];
		}

		// ==========================================
		// RECEPCIÓN DE VARIABLES DE PAGO DINÁMICAS
		// ==========================================
		$id_generacion_actual = $id_gen[0];

		error_log("💰 INICIO CAPTURA DE PAGOS");
		error_log("  - Generación actual: " . $id_generacion_actual);

		// MONTO DE INSCRIPCIÓN
		$monto_inscripcion = 1000; // Default
		if (isset($_POST['monto_inscripcion']) && !empty($_POST['monto_inscripcion'])) {
			$monto_inscripcion = $_POST['monto_inscripcion'];
			error_log("  ✅ Inscripción recibida: $" . $monto_inscripcion);
		} else {
			error_log("  ⚠️ Inscripción NO recibida - usando default: $1000");
		}

		// 🔥 TRÁMITES DINÁMICOS - NUEVA IMPLEMENTACIÓN
		$tramites_alumno = [];
		
		error_log("🔥 PROCESANDO TRÁMITES:");
		error_log("  - isset(tramites_json): " . (isset($_POST['tramites_json']) ? 'SÍ' : 'NO'));
		error_log("  - empty(tramites_json): " . (empty($_POST['tramites_json']) ? 'SÍ' : 'NO'));
		
		if (isset($_POST['tramites_json'])) {
			error_log("  - Valor RAW: " . $_POST['tramites_json']);
		}

		// Verificar si llegaron datos de trámites en JSON
		if (isset($_POST['tramites_json']) && !empty($_POST['tramites_json'])) {
			error_log("  🎯 Intentando decodificar JSON...");
			
			try {
				$tramites_data = json_decode($_POST['tramites_json'], true);
				
				error_log("  - JSON decodificado exitosamente");
				error_log("  - Es array: " . (is_array($tramites_data) ? 'SÍ' : 'NO'));
				error_log("  - Count: " . (is_array($tramites_data) ? count($tramites_data) : 'N/A'));
				error_log("  - Contenido: " . print_r($tramites_data, true));
				
				if (is_array($tramites_data) && count($tramites_data) > 0) {
					error_log("  ✅ Array válido, procesando trámites...");
					
					foreach ($tramites_data as $index => $tramite) {
						error_log("    - Trámite #" . ($index + 1) . ":");
						error_log("      * id_gru_pag: " . (isset($tramite['id_gru_pag']) ? $tramite['id_gru_pag'] : 'NO EXISTE'));
						error_log("      * monto: " . (isset($tramite['monto']) ? $tramite['monto'] : 'NO EXISTE'));
						error_log("      * fecha: " . (isset($tramite['fecha']) ? $tramite['fecha'] : 'NO EXISTE'));
						error_log("      * concepto: " . (isset($tramite['concepto']) ? $tramite['concepto'] : 'NO EXISTE'));
						
						if (isset($tramite['monto']) && floatval($tramite['monto']) > 0) {
							$tramites_alumno[] = [
								'id_gru_pag' => $tramite['id_gru_pag'],
								'monto' => floatval($tramite['monto']),
								'fecha' => !empty($tramite['fecha']) ? $tramite['fecha'] : date('Y-m-d'),
								'concepto' => !empty($tramite['concepto']) ? $tramite['concepto'] : 'TRÁMITE'
							];
							error_log("      ✅ Trámite agregado al array");
						} else {
							error_log("      ❌ Trámite ignorado (monto <= 0 o no existe)");
						}
					}
					
					error_log("  ✅ TOTAL TRÁMITES PROCESADOS: " . count($tramites_alumno));
				} else {
					error_log("  ⚠️ Array JSON vacío o no válido - esta generación no tiene trámites");
				}
				
			} catch (Exception $e) {
				error_log("  ❌ ERROR al decodificar JSON: " . $e->getMessage());
				$tramites_alumno = [];
			}
		} else {
			error_log("  ℹ️ No se recibió tramites_json - esta generación no tiene trámites");
		}

		// -------
		// REINSCRIPCIONES - OBTENER DINÁMICAMENTE DE grupo_pago
		error_log("🔄 PROCESANDO REINSCRIPCIONES:");
		$reinscripciones_grupo = [];
		$sqlReinscripcionesGrupo = "
			SELECT 
				id_gru_pag,
				con_gru_pag as concepto,
				mon_gru_pag as monto,
				ini_gru_pag as fecha_inicio
			FROM grupo_pago 
			WHERE id_gen15 = '$id_generacion_actual' 
			AND tip_gru_pag = 'Pago' 
			AND tip_pag_gru_pag = 'Reinscripción'
			ORDER BY ini_gru_pag ASC, id_gru_pag ASC
		";
		error_log("  - Query: " . $sqlReinscripcionesGrupo);
		
		$resultadoReinscripcionesGrupo = mysqli_query($db, $sqlReinscripcionesGrupo);
		
		if ($resultadoReinscripcionesGrupo) {
			$count_reinscripciones = 0;
			while ($filaReinscripcionGrupo = mysqli_fetch_assoc($resultadoReinscripcionesGrupo)) {
				$count_reinscripciones++;
				$reinscripciones_grupo[] = [
					'id_gru_pag' => $filaReinscripcionGrupo['id_gru_pag'],
					'concepto' => $filaReinscripcionGrupo['concepto'],
					'monto' => floatval($filaReinscripcionGrupo['monto']),
					'fecha_inicio' => $filaReinscripcionGrupo['fecha_inicio']
				];
				error_log("    - Reinscripción #" . $count_reinscripciones . ": " . $filaReinscripcionGrupo['concepto'] . " - $" . $filaReinscripcionGrupo['monto']);
			}
			error_log("  ✅ TOTAL REINSCRIPCIONES: " . count($reinscripciones_grupo));
		} else {
			error_log("  ❌ Error en query de reinscripciones: " . mysqli_error($db));
		}
		// -------

		error_log("=================================================");
		error_log("📊 RESUMEN DE PAGOS A CREAR:");
		error_log("  - Inscripción: $" . $monto_inscripcion);
		error_log("  - Trámites: " . count($tramites_alumno));
		error_log("  - Reinscripciones: " . count($reinscripciones_grupo));
		error_log("=================================================");

		// ==========================================
		// FIN RECEPCIÓN DE VARIABLES DE PAGO
		// ==========================================

		$cor1_alu = $_POST['cor1_alu'];

		$correo = $_POST['correo'];
		$pas_alu = $_POST['pas_alu'];
		$nom_alu = $_POST['nom_alu'];
		$app_alu = $_POST['app_alu'];
		$apm_alu = $_POST['apm_alu'];
		$bol_alu = 'PENDIENTE';
		$gen_alu = $_POST['gen_alu'];
		$tel_alu = $_POST['tel_alu'];
		$cur_alu = $_POST['cur_alu'];
		$nac_alu = $_POST['nac_alu'];

		// 🔥 RECEPCIÓN DE ent2_alu (Entidad de nacimiento)
		if (isset($_POST['ent2_alu']) && !empty($_POST['ent2_alu'])) {
			$ent2_alu = $_POST['ent2_alu'];
			error_log("✅ Estado de nacimiento recibido: " . $ent2_alu);
		} else {
			$ent2_alu = 'Pendiente';
			error_log("⚠️ Estado de nacimiento NO recibido - usando 'Pendiente'");
		}

		// 🔥 RECEPCIÓN DE val_cur_alu (Validación de CURP)
		if (isset($_POST['val_cur_alu']) && !empty($_POST['val_cur_alu'])) {
			$val_cur_alu = $_POST['val_cur_alu'];
			error_log("✅ Validación CURP recibida: " . $val_cur_alu);
		} else {
			$val_cur_alu = '';
			error_log("⚠️ Validación CURP NO recibida - usando campo vacío");
		}

		if( isset( $_POST['ing_alu'] ) ){
			$ing_alu = $_POST['ing_alu'];
		} else {
			$ing_alu = date('Y-m-d');
		}
		

		$alumno = $nom_alu.' '.$app_alu.' '.$apm_alu;

		$dir_alu = $_POST['direccion'];
		$cp_alu  = $_POST['cp_alu'];
		$col_alu = 'PENDIENTE';
		$del_alu = 'PENDIENTE';
		$ent_alu = 'PENDIENTE';
		$tut_alu = $_POST['tut_alu'];
		$tel2_alu = $_POST['tel2_alu'];
		$pro_alu = 'PENDIENTE';
		$qr_alu = sha1($correo);

		$fot_alu = 'PENDIENTE';
		/////
		$lug_alu = 'PENDIENTE';
		$civ_alu = 'PENDIENTE';
		$ocu_alu = $_POST['ocu_alu'];
		$lim_alu = 'PENDIENTE';

		$bec_alu_ram = 0;
		$bec2_alu_ram = 0;

		if( isset( $_POST['monto_colegiatura'] ) ){
			$mon_alu_ram = $_POST['monto_colegiatura'];
			error_log("✅ Colegiatura recibida: $" . $mon_alu_ram);
		} else {
			///// OBTENER MONTO PROGRAMA
			$sqlPrograma = "SELECT * FROM rama WHERE id_ram = $id_ram[0]";
			$cos_ram = obtener_datos_consulta( $db, $sqlPrograma )['datos']['cos_ram'];
			$mon_alu_ram = $cos_ram;
			error_log("⚠️ Colegiatura NO recibida - usando del programa: $" . $mon_alu_ram);
		}
		
		/// PERIODICIDAD DE PREPA EMPRENDE
		if( $_POST['tie_alu_ram'] != 0 ){
			$tie_alu_ram = $_POST['tie_alu_ram'];
		} else {
			$tie_alu_ram = NULL;
		}

		if( isset( $_POST['id_cit'] ) ){
			$id_cit = $_POST['id_cit'];
		} else {

			// echo 'entré aca';
			// ALTA PARA CUANDO ALUMNO SE GENERE DESDE ADMIN
			//dado q no existe cita asociada hay q generarla y extraer id_cit
			// DADO QUE DESASIGNAR id_eje_ejecutivo DESVINCULA LAS CITAS DEL LISTADO DE citas.php
			// SE INTEGRA UNA ASOCIACIÓN "PARCIAL" CON EJECUTIVOS PARCIALES CON NOMBRE "SIN ASIGNAR" 
			// QUE INTERNAMENTE ESTÁN "ELIMINADOS" PARA QUE NO SE LISTEN EN NINGÚN LADO
			// PERO QUE SÍ APAREZCA EL LISTADO RELACIONADO AL PLANTEL 
			// 2963 -- pachuca
			// 2962 -- queretaro
			// 2961 -- san luis potosi
			// 2960 -- cuautitlan
			// 2959 -- ecatepec
			// 2958 -- naucalpan
			if( $id_pla == 2 ){
				// NAU
				$id_eje_aux = 2958;
			} else if( $id_pla == 3 ){
				// ECA
				$id_eje_aux = 2959;
			} else if( $id_pla == 6 ){
				// CUA
				$id_eje_aux = 2960;
			} else if( $id_pla == 8 ){
				// QRO
				$id_eje_aux = 2962;
			} else if( $id_pla == 9 ){
				// PACH
				$id_eje_aux = 2963;
			} else if( $id_pla == 13 ){
				// SLP
				$id_eje_aux = 2961;
			}

			$id_eje = $_POST['id_eje'];
			$cit_cit = $ing_alu;

			$cla_cit = 'Cita';
			$sqlCita = "
				INSERT INTO cita ( nom_cit, tel_cit, id_eje3, cit_cit, est_cit, cla_cit, id_eje_cerrador, id_eje_agendo, efe_cit, obs_cit ) 
				VALUES ( '$alumno', '$tel_alu', '$id_eje', '$cit_cit', 'REGISTRO', '$cla_cit', '$id_eje_aux', '$id_eje_aux', '', 'REGISTRO ADMINISTRATIVO' )";
			$resultadoCita = mysqli_query( $db, $sqlCita );

			if(!$resultadoCita){
				echo $sqlCita;
			}else{
				$id_cit = mysqli_insert_id($db);
			}
		}

		// VALIDACION SI EXISTE ALUMNO ASOCIADO A CITA
		$sqlValidacion = "
			DELETE FROM alumno WHERE id_cit1 = $id_cit
		";
		$resultadoValidacion = mysqli_query( $db, $sqlValidacion );
		if( !$resultadoValidacion ){
			echo $sqlValidacion;
		}
		// F VALIDACION SI EXISTE ALUMNO ASOCIADO A CITA	

		// VALIDACION plantel_beneficiado
		// RECORDAR QUE SI UN ejecutivo REGISTRA UN alumno A UN plantel DIFERENTE AL SUYO, SETEAR plantel_beneficiado CON EL id_pla DEL ejecutivo
		// VALIDACION plantel_beneficiado
		$sqlValidacionPlantel = "
			SELECT *
			FROM cita
			INNER JOIN ejecutivo ON id_eje = id_eje3 
			WHERE id_cit = '$id_cit'
		";

		// echo "validacion: -->".$sqlValidacionPlantel."<---";

		$resultadoValidacionPlantel = mysqli_query($db, $sqlValidacionPlantel);
		$filaValidacionPlantel = mysqli_fetch_assoc($resultadoValidacionPlantel);
		$plantel_ejecutivo = $filaValidacionPlantel['id_pla'];

		// Si es del mismo plantel, plantel_beneficiado será NULL, si no, será el plantel del ejecutivo
		$plantel_beneficiado = ($plantel_ejecutivo == $id_pla) ? "NULL" : $id_pla;

		// echo "plantel_beneficiado: ".$plantel_beneficiado;

		// 🔥 INSERCIÓN CON NUEVOS CAMPOS: ent2_alu y val_cur_alu
		error_log("=================================================");
		error_log("👤 INSERTANDO ALUMNO CON DATOS CURP:");
		error_log("  - CURP: " . $cur_alu);
		error_log("  - Estado nacimiento (ent2_alu): " . $ent2_alu);
		error_log("  - Validación CURP (val_cur_alu): " . $val_cur_alu);
		error_log("=================================================");

		$sqlInsercionAlumno = "INSERT INTO alumno (cor_alu, pas_alu, nom_alu, app_alu, apm_alu, bol_alu, gen_alu, tel_alu, cur_alu, nac_alu, ing_alu, dir_alu, cp_alu, col_alu, del_alu, ent_alu, tut_alu, tel2_alu, pro_alu, tip_alu, qr_alu, id_pla8, est_alu, cor1_alu, id_cit1, lug_alu, civ_alu, ocu_alu, lim_alu, plantel_beneficiado, ent2_alu, val_cur_alu) VALUES ('$correo', '$pas_alu', '$nom_alu', '$app_alu', '$apm_alu', '$bol_alu', '$gen_alu', '$tel_alu', '$cur_alu', '$nac_alu', '$ing_alu', '$dir_alu', '$cp_alu', '$col_alu', '$del_alu', '$ent_alu', '$tut_alu', '$tel2_alu', '$pro_alu', 'Alumno', '$qr_alu', '$id_pla', 'Activo', '$cor1_alu', '$id_cit', '$lug_alu', '$civ_alu', '$ocu_alu', '$lim_alu', $plantel_beneficiado, '$ent2_alu', '$val_cur_alu')";
		// F VALIDACION plantel_beneficiado

		$resultadoInsercionAlumno = mysqli_query( $db, $sqlInsercionAlumno );

		if ( $resultadoInsercionAlumno ) {
			//CARGAR MATERIAS ACORDE A RAMA EN TABLA CALIFICACION

			$sql = "SELECT MAX(id_alu) AS ultimo FROM alumno";
			$resultado = mysqli_query( $db, $sql );

			$fila = mysqli_fetch_assoc( $resultado );
			$maxAlumno = $fila['ultimo'];

			error_log("👤 Alumno creado con ID: " . $maxAlumno);

			$sqlResponsable = "INSERT INTO alu_res (id_alu11, nom_res) VALUES ('$maxAlumno', '$nomResponsable')";

			mysqli_query($db, $sqlResponsable);

			for ( $contadorArreglo = 0 ;  $contadorArreglo < sizeof( $id_ram ) ;  $contadorArreglo++ ) { 
			/////
				$sqlRama = "
					SELECT *
					FROM rama
					WHERE id_ram = '$id_ram[$contadorArreglo]'
				";

				$resultadoRama = mysqli_query( $db, $sqlRama );

				$filaRama = mysqli_fetch_assoc( $resultadoRama );

				$car_alu_ram = $filaRama['car_reg_ram'];

				// Verificar si este programa es uno de los especiales que requieren forma de titulación
				$programas_especiales = [364, 363, 361, 360, 359, 357];
				$tit_alu_ram = '';
				
				if (in_array($id_ram[$contadorArreglo], $programas_especiales) && !empty($forma_titulacion)) {
					$tit_alu_ram = $forma_titulacion;
				}

				// Agregar el campo tit_alu_ram a la consulta si tiene valor
				if (!empty($tit_alu_ram)) {
					$sqlAlumnoRama = "INSERT INTO alu_ram ( mon_alu_ram, car_alu_ram, bec_alu_ram, bec2_alu_ram, id_gen1, id_alu1, id_ram3, tie_alu_ram, tit_alu_ram ) VALUES ( '$mon_alu_ram', '$car_alu_ram', '$bec_alu_ram', '$bec2_alu_ram', '$id_gen[$contadorArreglo]', '$maxAlumno', '$id_ram[$contadorArreglo]', '$tie_alu_ram', '$tit_alu_ram' )";
				} else {
					$sqlAlumnoRama = "INSERT INTO alu_ram ( mon_alu_ram, car_alu_ram, bec_alu_ram, bec2_alu_ram, id_gen1, id_alu1, id_ram3, tie_alu_ram ) VALUES ( '$mon_alu_ram', '$car_alu_ram', '$bec_alu_ram', '$bec2_alu_ram', '$id_gen[$contadorArreglo]', '$maxAlumno', '$id_ram[$contadorArreglo]', '$tie_alu_ram' )";
				}
				
				$resultadoAlumnoRama = mysqli_query( $db, $sqlAlumnoRama );

				if ($resultadoAlumnoRama) {

					// ADICION DE CALIFICACIONES Y PARCIALES

					$sqlAluRam = "SELECT MAX(id_alu_ram) AS ultimo FROM alu_ram";
					$resultadoAluRam = mysqli_query($db, $sqlAluRam);

					$filaAluRam = mysqli_fetch_assoc($resultadoAluRam);
					$maxAluRam = $filaAluRam['ultimo'];

					error_log("📚 alu_ram creado con ID: " . $maxAluRam);

					// ==========================================
					// ADICION DE PAGOS DINÁMICOS
					// ==========================================
					error_log("=================================================");
					error_log("💳 INICIANDO CREACIÓN DE PAGOS");
					error_log("  - id_alu_ram10: " . $maxAluRam);
					error_log("=================================================");

					$id_alu_ram10 = $maxAluRam;
					$fec_pag = date('Y-m-d');
					$est_pag = 'Pendiente';
					$res_pag = 'Sistema';
					$ini_pag = date('Y-m-d');
					$fin_pag = date('Y-m-d');
					$pro_pag = date('Y-m-d');
					$pri_pag = 1;
					$tip1_pag = 'NA';
					$tip2_pag = 'NA';
					$car_pag = 0;
					$des_pag = 0;

					// PAGO DE INSCRIPCIÓN (SIEMPRE SE GENERA)
					$mon_ori_pag = $monto_inscripcion;
					$mon_pag = $mon_ori_pag;
					$con_pag = 'INSCRIPCIÓN';
					$tip_pag = 'Inscripción';

					error_log("1️⃣ Creando INSCRIPCIÓN: $" . $mon_ori_pag);

					$sqlInscripcion = "
						INSERT INTO pago(fec_pag, mon_ori_pag, mon_pag, con_pag, est_pag, res_pag, ini_pag, fin_pag, pro_pag, pri_pag, tip1_pag, des_pag, tip2_pag, car_pag, id_alu_ram10, tip_pag ) 
						VALUES('$fec_pag', '$mon_ori_pag', '$mon_pag', '$con_pag', '$est_pag', '$res_pag', '$ini_pag', '$fin_pag', '$pro_pag', '$pri_pag', '$tip1_pag', '$des_pag', '$tip2_pag', '$car_pag', '$id_alu_ram10', '$tip_pag' )
					";

					$resultadoInscripcion = mysqli_query($db, $sqlInscripcion);

					if (!$resultadoInscripcion) {
						error_log("  ❌ ERROR en inscripción: " . mysqli_error($db));
						error_log("  Query: " . $sqlInscripcion);
					} else {
						error_log("  ✅ Inscripción creada exitosamente (ID: " . mysqli_insert_id($db) . ")");
					}

					// 🔥 PAGOS DE TRÁMITE DINÁMICOS - NUEVA IMPLEMENTACIÓN
					if (!empty($tramites_alumno)) {
						error_log("2️⃣ Creando " . count($tramites_alumno) . " TRÁMITE(S):");
						
						foreach ($tramites_alumno as $index => $tramite) {
							$mon_ori_pag = $tramite['monto'];
							$mon_pag = $mon_ori_pag;
							$con_pag = strtoupper($tramite['concepto']);
							$tip_pag = 'Otros';
							$ini_pag = $tramite['fecha'];
							$fin_pag = $tramite['fecha'];

							error_log("  - Trámite #" . ($index + 1) . ": " . $con_pag . " - $" . $mon_ori_pag);

							$sqlTramite = "
								INSERT INTO pago(fec_pag, mon_ori_pag, mon_pag, con_pag, est_pag, res_pag, ini_pag, fin_pag, pro_pag, pri_pag, tip1_pag, des_pag, tip2_pag, car_pag, id_alu_ram10, tip_pag ) 
								VALUES('$fec_pag', '$mon_ori_pag', '$mon_pag', '$con_pag', '$est_pag', '$res_pag', '$ini_pag', '$fin_pag', '$pro_pag', '$pri_pag', '$tip1_pag', '$des_pag', '$tip2_pag', '$car_pag', '$id_alu_ram10', '$tip_pag' )
							";

							$resultadoTramite = mysqli_query($db, $sqlTramite);
							if (!$resultadoTramite) {
								error_log("    ❌ ERROR: " . mysqli_error($db));
								error_log("    Query: " . $sqlTramite);
							} else {
								error_log("    ✅ Insertado (ID: " . mysqli_insert_id($db) . ")");
							}
						}
					} else {
						error_log("2️⃣ Sin trámites para crear");
					}

					// 🔥 PAGOS DE REINSCRIPCIÓN DINÁMICOS DESDE grupo_pago
					if (!empty($reinscripciones_grupo)) {
						error_log("3️⃣ Creando " . count($reinscripciones_grupo) . " REINSCRIPCIÓN(ES):");
						
						foreach ($reinscripciones_grupo as $index => $reinscripcion) {
							$mon_ori_pag = $reinscripcion['monto'];
							$mon_pag = $mon_ori_pag;
							$con_pag = $reinscripcion['concepto'];
							$tip_pag = 'Reinscripción';
							$ini_pag = $reinscripcion['fecha_inicio'];
							$fin_pag = $reinscripcion['fecha_inicio'];

							error_log("  - Reinscripción #" . ($index + 1) . ": " . $con_pag . " - $" . $mon_ori_pag);

							$sqlReinscripcion = "
								INSERT INTO pago(fec_pag, mon_ori_pag, mon_pag, con_pag, est_pag, res_pag, ini_pag, fin_pag, pro_pag, pri_pag, tip1_pag, des_pag, tip2_pag, car_pag, id_alu_ram10, tip_pag ) 
								VALUES('$fec_pag', '$mon_ori_pag', '$mon_pag', '$con_pag', '$est_pag', '$res_pag', '$ini_pag', '$fin_pag', '$pro_pag', '$pri_pag', '$tip1_pag', '$des_pag', '$tip2_pag', '$car_pag', '$id_alu_ram10', '$tip_pag' )
							";

							$resultadoReinscripcion = mysqli_query($db, $sqlReinscripcion);
							if (!$resultadoReinscripcion) {
								error_log("    ❌ ERROR: " . mysqli_error($db));
								error_log("    Query: " . $sqlReinscripcion);
							} else {
								error_log("    ✅ Insertada (ID: " . mysqli_insert_id($db) . ")");
							}
						}
					} else {
						error_log("3️⃣ Sin reinscripciones para crear");
					}

					// ==========================================
					// FIN ADICION DE PAGOS DINÁMICOS
					// ==========================================

					if ( !$resultadoInscripcion ) {
						echo $sqlInscripcion;
					} else {
						// CALENDARIO DE PAGOS
						error_log("4️⃣ Generando calendario de colegiaturas...");
						calendario_pagos( $id_alu_ram10, $db );
						//generar_primera_colegiatura( $db, $id_alu_ram10 );
						error_log("  ✅ Calendario generado");
					}

					error_log("=================================================");
					error_log("✅ PAGOS CREADOS EXITOSAMENTE");
					error_log("=================================================");
			        // FIN ADICION DE PAGOS

			        //TOTAL DE MATERIAS Y ADICION
					$sqlMateria = "
			            SELECT * 
						FROM materia
						WHERE id_ram2 = '$id_ram[$contadorArreglo]'
		            ";

					$resultadoMateria = mysqli_query($db, $sqlMateria);

			        // ADICION DE CALIFICACIONES Y PARCIALES ASOCIADOS A MATERIAS
			        $eva_ram = $filaRama['eva_ram'];

			        while ($filaMateriasAux = mysqli_fetch_array($resultadoMateria)) {
			            
			            $id_mat = $filaMateriasAux["id_mat"];
			            $sqlInsercionCalificacion = "INSERT INTO calificacion (id_alu_ram2, id_mat4) VALUES($maxAluRam, $id_mat)";
			        	//echo $sqlInsercionCalificacion;
			            mysqli_query($db, $sqlInsercionCalificacion);

			            for ($j = 0; $j < $eva_ram; $j++) {
			            	// ADICION DE REGISTROS NULOS PARA PARCIALES ACORDE A MATERIAS
			            	$sqlInsercionParcial = "INSERT INTO parcial (id_alu_ram9, id_mat3 ) VALUES( $maxAluRam, '$id_mat' )";
			            	mysqli_query($db, $sqlInsercionParcial);
			            }

			        }
			        // FIN ADICION DE CALIFICACIONES Y PARCIALES ASOCIADOS A MATERIAS

			        // ADICION DE CARGA DE DOCUMENTACION DE PROGRAMA
			        $sqlDocumentosRama = "
						SELECT *
						FROM documento_rama
						WHERE id_ram6 = '$id_ram[$contadorArreglo]'
					";

					$resultadoDocumentosRama = mysqli_query( $db, $sqlDocumentosRama );

					while( $filaDocumentosRama = mysqli_fetch_assoc( $resultadoDocumentosRama )){
			        	
			        	$id_doc_ram1 = $filaDocumentosRama['id_doc_ram'];

			        	if ( isset( $_POST['documentacion_alumno'] ) ) {
			        		//
			        		$documentacion_alumno = $_POST['documentacion_alumno'];

							// Supongamos que $id_doc_ram1 y $maxAluRam están definidos previamente

							// Inicializa $est_doc_alu_ram fuera del bucle
							$est_doc_alu_ram = 'Pendiente';
							$fec_doc_alu_ram = NULL;

							$j = 0;
							while ($j < sizeof($documentacion_alumno)) {
							    if ($documentacion_alumno[$j] == $id_doc_ram1) {
							        $est_doc_alu_ram = 'Entregado';
							        $fec_doc_alu_ram = date('Y-m-d');
							    }
							    $j++;
							}

							$sqlInsercionDocumento = "
						        INSERT INTO documento_alu_ram (est_doc_alu_ram, fec_doc_alu_ram, id_doc_ram1, id_alu_ram11) 
						        VALUES ('$est_doc_alu_ram', '$fec_doc_alu_ram', $id_doc_ram1, $maxAluRam)
						    ";

						    $resultadoInsercionDocumento = mysqli_query($db, $sqlInsercionDocumento);

						    if (!$resultadoInsercionDocumento) {
						        echo $sqlInsercionDocumento;
						    }

			        		//
			        	} else {

			        		$est_doc_alu_ram = 'Pendiente';
			        		$sqlInsercionDocumento = "
								INSERT INTO documento_alu_ram ( est_doc_alu_ram, id_doc_ram1, id_alu_ram11 ) VALUES ( '$est_doc_alu_ram', $id_doc_ram1, $maxAluRam )
							";

							$resultadoInsercionDocumento = mysqli_query($db, $sqlInsercionDocumento);

				        	if ( !$resultadoInsercionDocumento ) {
				        		
				        		echo $sqlInsercionDocumento;
				        	
				        	}
			        	}

			        }

			  //       $nombreAlumno = obtenerNombreAlumnoServer( $maxAluRam );
					// $nombrePrograma = obtenerNombreProgramaServer( $id_ram[$contadorArreglo] );

					// $des_log =  obtenerDescripcionAlumnoLogServer( $tipoUsuario, $nomResponsable, 'registró', $nombreAlumno, $nombrePrograma );
				   

					// logServer ( 'Alta', $tipoUsuario, $id, 'Alumno', $des_log, $plantel );

				}else{
					error_log("❌ ERROR al crear alu_ram");
					error_log("Query: " . $sqlAlumnoRama);
					echo "Error en insercion de alumno-rama";
					echo $sqlAlumnoRama;

				}
				//echo "Exito";
			///
			}

		}else{
			error_log("❌ ERROR al crear alumno");
			error_log("Query: " . $sqlInsercionAlumno);
			echo "Error en alta de alumno, verificar consulta";
			echo $sqlInsercionAlumno;
		}

		// UPDATE A CITA EFECTIVA
		$sqlUpdateCita = "
			UPDATE cita 
			SET 
			efe_cit = 'CITA EFECTIVA'
			WHERE id_cit = $id_cit
		";
		$resultadoUpdateCita = mysqli_query( $db, $sqlUpdateCita );
		if(!$resultadoUpdateCita){
			echo $sqlUpdateCita;
		}
		// F UPDATE A CITA EFECTIVA

		$sqlCita = "
			SELECT *
			FROM cita
			WHERE id_cit = '$id_cit'
		";

		$datos_cita = obtener_datos_consulta( $db, $sqlCita )['datos'];
		if( $datos_cita['id_con2'] != '' || $datos_cita['id_con2'] != null ){
			
			$id_con = $datos_cita['id_con2'];
			$sqlContacto = "
				UPDATE contacto SET est_con = 'Registro' WHERE id_con = '$id_con'
			";
			$resultadoContacto = mysqli_query( $db, $sqlContacto );
			if( !$resultadoContacto ){
				echo $sqlContacto;
			}

		}
		$id_eje = $datos_cita['id_eje3'];

		// agregar_referido_server( $_POST['nom_ref1'], $_POST['nom_ref1'], $id_eje, $maxAlumno );
		// agregar_referido_server( $_POST['nom_ref2'], $_POST['nom_ref2'], $id_eje, $maxAlumno );
		// agregar_referido_server( $_POST['nom_ref3'], $_POST['nom_ref3'], $id_eje, $maxAlumno );
		// agregar_referido_server( $_POST['nom_ref4'], $_POST['nom_ref4'], $id_eje, $maxAlumno );
		// agregar_referido_server( $_POST['nom_ref5'], $_POST['nom_ref5'], $id_eje, $maxAlumno );
		enviar_correo_alumno($id_alu_ram10, $db);
		
		// LOG FINAL DE ÉXITO
		error_log("=================================================");
		error_log("🎉 ALUMNO REGISTRADO EXITOSAMENTE");
		error_log("  - ID alu_ram: " . $id_alu_ram10);
		error_log("  - Nombre: " . $alumno);
		error_log("  - CURP: " . $cur_alu);
		error_log("  - Estado nacimiento: " . $ent2_alu);
		error_log("  - Validación CURP: " . $val_cur_alu);
		error_log("=================================================");
		echo $id_alu_ram10;
	
	}

?>