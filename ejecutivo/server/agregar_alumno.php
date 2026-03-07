<?php 
	//ARCHIVO VIA AJAX PARA AGREGAR NUEVO ALUMNO
	//alumnos_carrera.php///alumnos.php
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	//enviarCorreoBienvenidaAlumno( $_POST['correo'], $_POST['cor1_alu'], $_POST['pas_alu'], $_POST['nom_alu'], $nombrePlantel, $correo2Plantel, $ligaPlantel, $fotoPlantel );
	if ( ( isset( $_POST['id_ram'] ) && ( isset( $_POST['id_gen'] ) ) ) ) {
	
		$id_ram = array();
		$id_gen = array();
		
		$id_pla = $_POST['id_pla'];
		$id_ram[0] = $_POST['id_ram'];
		$id_gen[0] = $_POST['id_gen'];

		// Capturar forma de titulación si existe
		$forma_titulacion = '';
		if (isset($_POST['forma_titulacion'])) {
			$forma_titulacion = $_POST['forma_titulacion'];
		}

		// ==========================================
		// RECEPCIÓN DE VARIABLES DE PAGO DINÁMICAS
		// ==========================================
		$id_generacion_actual = $id_gen[0];

		// MONTO DE INSCRIPCIÓN
		$monto_inscripcion = 1000; // Default
		if (isset($_POST['monto_inscripcion']) && !empty($_POST['monto_inscripcion'])) {
			$monto_inscripcion = $_POST['monto_inscripcion'];
		}

		// 🔥 TRÁMITES DINÁMICOS - NUEVA IMPLEMENTACIÓN
		$tramites_alumno = [];
		
		// Verificar si llegaron datos de trámites en JSON
		if (isset($_POST['tramites_json']) && !empty($_POST['tramites_json'])) {
			try {
				$tramites_data = json_decode($_POST['tramites_json'], true);
				
				if (is_array($tramites_data) && count($tramites_data) > 0) {
					foreach ($tramites_data as $tramite) {
						if (isset($tramite['monto']) && floatval($tramite['monto']) > 0) {
							$tramites_alumno[] = [
								'id_gru_pag' => $tramite['id_gru_pag'],
								'monto' => floatval($tramite['monto']),
								'fecha' => !empty($tramite['fecha']) ? $tramite['fecha'] : date('Y-m-d'),
								'concepto' => !empty($tramite['concepto']) ? $tramite['concepto'] : 'TRÁMITE'
							];
						}
					}
					
					error_log("✅ TRÁMITES: Se procesaron " . count($tramites_alumno) . " trámite(s) desde JSON");
				} else {
					error_log("ℹ️ TRÁMITES: Array JSON vacío o no válido - esta generación no tiene trámites");
				}
				
			} catch (Exception $e) {
				error_log("❌ TRÁMITES: Error al decodificar JSON: " . $e->getMessage());
				$tramites_alumno = [];
			}
		} else {
			error_log("ℹ️ TRÁMITES: No se recibió campo tramites_json - esta generación no tiene trámites");
		}

		// -------
		// REINSCRIPCIONES - OBTENER DINÁMICAMENTE DE grupo_pago
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
		$resultadoReinscripcionesGrupo = mysqli_query($db, $sqlReinscripcionesGrupo);
		while ($filaReinscripcionGrupo = mysqli_fetch_assoc($resultadoReinscripcionesGrupo)) {
			$reinscripciones_grupo[] = [
				'id_gru_pag' => $filaReinscripcionGrupo['id_gru_pag'],
				'concepto' => $filaReinscripcionGrupo['concepto'],
				'monto' => floatval($filaReinscripcionGrupo['monto']),
				'fecha_inicio' => $filaReinscripcionGrupo['fecha_inicio']
			];
		}
		// -------

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
		} else {
			$ent2_alu = 'Pendiente';
		}

		// 🔥 RECEPCIÓN DE val_cur_alu (Validación de CURP)
		if (isset($_POST['val_cur_alu']) && !empty($_POST['val_cur_alu'])) {
			$val_cur_alu = $_POST['val_cur_alu'];
		} else {
			$val_cur_alu = 'Pendiente';
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
		} else {
			///// OBTENER MONTO PROGRAMA
			$sqlPrograma = "SELECT * FROM rama WHERE id_ram = $id_ram[0]";
			$cos_ram = obtener_datos_consulta( $db, $sqlPrograma )['datos']['cos_ram'];
			$mon_alu_ram = $cos_ram;
			///// FIN OBTENER MONTO PROGRAMA
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
		$sqlInsercionAlumno = "INSERT INTO alumno (cor_alu, pas_alu, nom_alu, app_alu, apm_alu, bol_alu, gen_alu, tel_alu, cur_alu, nac_alu, ing_alu, dir_alu, cp_alu, col_alu, del_alu, ent_alu, tut_alu, tel2_alu, pro_alu, tip_alu, qr_alu, id_pla8, est_alu, cor1_alu, id_cit1, lug_alu, civ_alu, ocu_alu, lim_alu, plantel_beneficiado, ent2_alu, val_cur_alu) VALUES ('$correo', '$pas_alu', '$nom_alu', '$app_alu', '$apm_alu', '$bol_alu', '$gen_alu', '$tel_alu', '$cur_alu', '$nac_alu', '$ing_alu', '$dir_alu', '$cp_alu', '$col_alu', '$del_alu', '$ent_alu', '$tut_alu', '$tel2_alu', '$pro_alu', 'Alumno', '$qr_alu', '$id_pla', 'Activo', '$cor1_alu', '$id_cit', '$lug_alu', '$civ_alu', '$ocu_alu', '$lim_alu', $plantel_beneficiado, '$ent2_alu', '$val_cur_alu')";
		// F VALIDACION plantel_beneficiado

		$resultadoInsercionAlumno = mysqli_query( $db, $sqlInsercionAlumno );

		if ( $resultadoInsercionAlumno ) {
			//CARGAR MATERIAS ACORDE A RAMA EN TABLA CALIFICACION

			$sql = "SELECT MAX(id_alu) AS ultimo FROM alumno";
			$resultado = mysqli_query( $db, $sql );

			$fila = mysqli_fetch_assoc( $resultado );
			$maxAlumno = $fila['ultimo'];

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

					// ==========================================
					// ADICION DE PAGOS DINÁMICOS
					// ==========================================
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

					$sqlInscripcion = "
						INSERT INTO pago(fec_pag, mon_ori_pag, mon_pag, con_pag, est_pag, res_pag, ini_pag, fin_pag, pro_pag, pri_pag, tip1_pag, des_pag, tip2_pag, car_pag, id_alu_ram10, tip_pag ) 
						VALUES('$fec_pag', '$mon_ori_pag', '$mon_pag', '$con_pag', '$est_pag', '$res_pag', '$ini_pag', '$fin_pag', '$pro_pag', '$pri_pag', '$tip1_pag', '$des_pag', '$tip2_pag', '$car_pag', '$id_alu_ram10', '$tip_pag' )
					";

					$resultadoInscripcion = mysqli_query($db, $sqlInscripcion);

					// if ($resultadoInscripcion) {
					// 	// Obtener el ID del último registro insertado
					// 	$id_pag = mysqli_insert_id($db);
						
					// 	// PAGO DE INSCRIPCION
					// 	$mon_abo_pag = $mon_ori_pag;
					// 	$tip_abo_pag = 'Depósito';
					// 	$mon_pag = $mon_abo_pag;

					// 	$sql = "
					// 		SELECT id_pag, tip_pag FROM pago WHERE id_pag = '$id_pag'
					// 	";

					// 	$resultado = mysqli_query($db, $sql);
					// 	if($row = mysqli_fetch_assoc($resultado)) {
					// 		$tip_pag = $row['tip_pag'];
					// 		agregar_abono_pago_server($id_pag, $mon_pag, $tip_abo_pag, $mon_abo_pag, $nomResponsable, $tip_pag);
					// 	}
					// }

					// 🔥 PAGOS DE TRÁMITE DINÁMICOS - NUEVA IMPLEMENTACIÓN
					if (!empty($tramites_alumno)) {
						error_log("🎯 PROCESANDO " . count($tramites_alumno) . " trámite(s) dinámico(s)");
						
						foreach ($tramites_alumno as $tramite) {
							$mon_ori_pag = $tramite['monto'];
							$mon_pag = $mon_ori_pag;
							$con_pag = strtoupper($tramite['concepto']);
							$tip_pag = 'Otros';
							$ini_pag = $tramite['fecha'];
							$fin_pag = $tramite['fecha'];

							$sqlTramite = "
								INSERT INTO pago(fec_pag, mon_ori_pag, mon_pag, con_pag, est_pag, res_pag, ini_pag, fin_pag, pro_pag, pri_pag, tip1_pag, des_pag, tip2_pag, car_pag, id_alu_ram10, tip_pag ) 
								VALUES('$fec_pag', '$mon_ori_pag', '$mon_pag', '$con_pag', '$est_pag', '$res_pag', '$ini_pag', '$fin_pag', '$pro_pag', '$pri_pag', '$tip1_pag', '$des_pag', '$tip2_pag', '$car_pag', '$id_alu_ram10', '$tip_pag' )
							";

							$resultadoTramite = mysqli_query($db, $sqlTramite);
							if (!$resultadoTramite) {
								error_log("❌ Error en trámite dinámico: " . $sqlTramite);
								echo "Error en trámite: " . $sqlTramite;
							} else {
								error_log("✅ Trámite insertado: " . $con_pag . " - $" . $mon_ori_pag);
							}
						}
					}

					// 🔥 PAGOS DE REINSCRIPCIÓN DINÁMICOS DESDE grupo_pago
					if (!empty($reinscripciones_grupo)) {
						error_log("🎯 PROCESANDO " . count($reinscripciones_grupo) . " reinscripción(es)");
						
						foreach ($reinscripciones_grupo as $reinscripcion) {
							$mon_ori_pag = $reinscripcion['monto'];
							$mon_pag = $mon_ori_pag;
							$con_pag = $reinscripcion['concepto'];
							$tip_pag = 'Reinscripción';
							$ini_pag = $reinscripcion['fecha_inicio'];
							$fin_pag = $reinscripcion['fecha_inicio'];

							$sqlReinscripcion = "
								INSERT INTO pago(fec_pag, mon_ori_pag, mon_pag, con_pag, est_pag, res_pag, ini_pag, fin_pag, pro_pag, pri_pag, tip1_pag, des_pag, tip2_pag, car_pag, id_alu_ram10, tip_pag ) 
								VALUES('$fec_pag', '$mon_ori_pag', '$mon_pag', '$con_pag', '$est_pag', '$res_pag', '$ini_pag', '$fin_pag', '$pro_pag', '$pri_pag', '$tip1_pag', '$des_pag', '$tip2_pag', '$car_pag', '$id_alu_ram10', '$tip_pag' )
							";

							$resultadoReinscripcion = mysqli_query($db, $sqlReinscripcion);
							if (!$resultadoReinscripcion) {
								error_log("❌ Error en reinscripción: " . $sqlReinscripcion);
								echo "Error en reinscripción: " . $sqlReinscripcion;
							} else {
								error_log("✅ Reinscripción insertada: " . $con_pag . " - $" . $mon_ori_pag);
							}
						}
					} else {
						error_log("ℹ️ Esta generación no tiene reinscripciones configuradas");
					}

					// ==========================================
					// FIN ADICION DE PAGOS DINÁMICOS
					// ==========================================

					if ( !$resultadoInscripcion ) {
						echo $sqlInscripcion;
					} else {
						// CALENDARIO DE PAGOS
						calendario_pagos( $id_alu_ram10, $db );
						//generar_primera_colegiatura( $db, $id_alu_ram10 );
					}
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
					echo "Error en insercion de alumno-rama";
					echo $sqlAlumnoRama;

				}
				//echo "Exito";
			///
			}

		}else{
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
		error_log("🎉 ALUMNO REGISTRADO EXITOSAMENTE - ID: $id_alu_ram10");
		echo $id_alu_ram10;
	
	}

?>