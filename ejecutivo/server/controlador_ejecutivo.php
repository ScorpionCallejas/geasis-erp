<?php  
	// ============================================================================
	// CONTROLADOR DE ALTA, BAJA Y CAMBIO DE EJECUTIVO
	// ============================================================================
	session_start();

	require_once(__DIR__."/../../includes/conexion.php");
	require('../inc/funciones.php');

	// Añade el header para JSON
	header('Content-Type: application/json');
	
	// ============================================================================
	// BLOQUE PRINCIPAL: CON PROC
	// ============================================================================
	if ( isset( $_POST['proc'] ) ) {

		// ========================================================================
		// ALTA DE EJECUTIVO
		// ========================================================================
		if ( $_POST['estatus'] == 'Alta' ) {
			
			$nom_eje = mysqli_real_escape_string($db, $_POST['nom_eje']);
			$ran_eje = mysqli_real_escape_string($db, $_POST['ran_eje']);
			$cor_eje = mysqli_real_escape_string($db, $_POST['cor_eje']);
			$pas_eje = mysqli_real_escape_string($db, $_POST['pas_eje']);
			$tel_eje = mysqli_real_escape_string($db, $_POST['tel_eje']);
			$obs_eje = mysqli_real_escape_string($db, $_POST['obs_eje']);
			$id_pla = mysqli_real_escape_string($db, $_POST['id_pla']);

			if ( $_POST['proc'] == 'estructuras_comerciales' ) {
				$sql = "
					INSERT INTO ejecutivo 
					( nom_eje, ran_eje, cor_eje, pas_eje, tel_eje, obs_eje, id_pla, id_can1, ult_cam_pas_eje, req_cam_pas_eje ) 
					VALUES 
					( '$nom_eje', '$ran_eje', '$cor_eje', '$pas_eje', '$tel_eje', '$obs_eje', '$id_pla', NULL, CURDATE(), 0 )
				";
			} else {
				$id_can = mysqli_real_escape_string($db, $_POST['id_can']);
				$sql = "
					INSERT INTO ejecutivo 
					( nom_eje, ran_eje, cor_eje, pas_eje, tel_eje, obs_eje, id_pla, id_can1, ult_cam_pas_eje, req_cam_pas_eje ) 
					VALUES 
					( '$nom_eje', '$ran_eje', '$cor_eje', '$pas_eje', '$tel_eje', '$obs_eje', '$id_pla', '$id_can', CURDATE(), 0 )
				";
			}

		// ========================================================================
		// CAMBIO/EDICIÓN DE EJECUTIVO
		// ========================================================================
		} else if ( $_POST['estatus'] == 'Cambio' ) {
			
			$id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
			$nom_eje = mysqli_real_escape_string($db, $_POST['nom_eje']);
			$ran_eje = mysqli_real_escape_string($db, $_POST['ran_eje']);
			$cor_eje = mysqli_real_escape_string($db, $_POST['cor_eje']);
			$pas_eje = mysqli_real_escape_string($db, $_POST['pas_eje']);
			$tel_eje = mysqli_real_escape_string($db, $_POST['tel_eje']);
			$obs_eje = mysqli_real_escape_string($db, $_POST['obs_eje']);
			$id_pla = mysqli_real_escape_string($db, $_POST['id_pla']);
			
			$sql = "
				UPDATE ejecutivo 
				SET 
					nom_eje = '$nom_eje', 
					ran_eje = '$ran_eje', 
					cor_eje = '$cor_eje', 
					pas_eje = '$pas_eje', 
					tel_eje = '$tel_eje', 
					obs_eje = '$obs_eje',
					id_pla = '$id_pla'
				WHERE id_eje = '$id_eje'
			";
			
		// ========================================================================
		// 📧 ENVIAR CÓDIGO DE VERIFICACIÓN AL CORREO PERSONAL
		// ========================================================================
		} else if ( $_POST['estatus'] == 'EnviarCodigoCorreo' ) {
			
			// Validar que existan los parámetros necesarios
			if( !isset($_POST['id_eje']) || !isset($_POST['cor2_eje']) ) {
				echo json_encode(array(
					'success' => false,
					'error' => 'Faltan parámetros requeridos'
				));
				exit();
			}
			
			$id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
			$cor2_eje = mysqli_real_escape_string($db, $_POST['cor2_eje']);
			
			// 🔥 LLAMAR A LA FUNCIÓN DE ENVÍO
			$resultado = enviar_codigo_validacion_ejecutivo($id_eje, $cor2_eje, $db);
			
			// Retornar respuesta
			echo json_encode($resultado);
			exit();
			
		// ========================================================================
		// ✅ VALIDAR CÓDIGO Y GUARDAR CORREO PERSONAL (cor2_eje)
		// ========================================================================
		} else if ( $_POST['estatus'] == 'ValidarCodigoYGuardarCorreo' ) {
			
			// Validar que existan los parámetros necesarios
			if( !isset($_POST['id_eje']) || !isset($_POST['cor2_eje']) ) {
				echo json_encode(array(
					'success' => false,
					'error' => 'Faltan parámetros requeridos'
				));
				exit();
			}
			
			$id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
			$cor2_eje = mysqli_real_escape_string($db, $_POST['cor2_eje']);
			
			// ====================================================================
			// ACTUALIZAR CORREO PERSONAL EN LA BASE DE DATOS
			// ====================================================================
			$sqlActualizarCorreo = "
				UPDATE ejecutivo 
				SET cor2_eje = '$cor2_eje'
				WHERE id_eje = '$id_eje'
			";
			
			$resultadoActualizar = mysqli_query($db, $sqlActualizarCorreo);
			
			if(!$resultadoActualizar) {
				echo json_encode(array(
					'success' => false,
					'error' => 'Error al guardar el correo personal',
					'debug' => mysqli_error($db)
				));
				exit();
			}
			
			// Respuesta exitosa
			echo json_encode(array(
				'success' => true,
				'mensaje' => 'Correo personal guardado correctamente',
				'cor2_eje' => $cor2_eje
			));
			exit();
			
		// ========================================================================
		// 🔐 VERIFICAR CONTRASEÑA ACTUAL (PASO 3 DEL STEPPER)
		// ========================================================================
		} else if ( $_POST['estatus'] == 'VerificarPassword' ) {
			
			// Validar que existan los parámetros necesarios
			if( !isset($_POST['id_eje']) || !isset($_POST['password_actual']) ) {
				echo json_encode(array(
					'success' => false,
					'error' => 'Faltan parámetros requeridos'
				));
				exit();
			}
			
			$id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
			$password_actual = mysqli_real_escape_string($db, $_POST['password_actual']);
			
			// Consultar contraseña actual en BD
			$sqlVerificar = "SELECT pas_eje FROM ejecutivo WHERE id_eje = '$id_eje'";
			$resultadoVerificar = mysqli_query($db, $sqlVerificar);
			
			if(!$resultadoVerificar) {
				echo json_encode(array(
					'success' => false,
					'error' => 'Error en la base de datos',
					'debug' => mysqli_error($db)
				));
				exit();
			}
			
			$filaVerificar = mysqli_fetch_assoc($resultadoVerificar);
			
			if(!$filaVerificar) {
				echo json_encode(array(
					'success' => false,
					'error' => 'Usuario no encontrado'
				));
				exit();
			}
			
			// Comparar contraseñas
			if($filaVerificar['pas_eje'] !== $password_actual) {
				echo json_encode(array(
					'success' => false,
					'error' => 'Contraseña incorrecta'
				));
				exit();
			}
			
			// Contraseña correcta
			echo json_encode(array(
				'success' => true,
				'mensaje' => 'Contraseña verificada correctamente'
			));
			exit();
			
		// ========================================================================
		// 🔐 CAMBIO DE CONTRASEÑA (PASO 4 DEL STEPPER)
		// ========================================================================
		} else if ( $_POST['estatus'] == 'CambioPassword' ) {
			
			// Validar que existan todos los parámetros necesarios
			if( !isset($_POST['id_eje']) || !isset($_POST['password_actual']) || 
			    !isset($_POST['password_nueva']) || !isset($_POST['password_confirmar']) ) {
				echo json_encode(array(
					'success' => false,
					'error' => 'Faltan parámetros requeridos'
				));
				exit();
			}
			
			$id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
			$password_actual = mysqli_real_escape_string($db, $_POST['password_actual']);
			$password_nueva = mysqli_real_escape_string($db, $_POST['password_nueva']);
			$password_confirmar = mysqli_real_escape_string($db, $_POST['password_confirmar']);
			
			// ====================================================================
			// VALIDACIÓN 1: Las nuevas coinciden
			// ====================================================================
			if($password_nueva !== $password_confirmar) {
				echo json_encode(array(
					'success' => false, 
					'error' => 'Las contraseñas nuevas no coinciden'
				));
				exit();
			}
			
			// ====================================================================
			// VALIDACIÓN 2: Verificar contraseña actual en BD
			// ====================================================================
			$sqlVerificar = "SELECT pas_eje FROM ejecutivo WHERE id_eje = '$id_eje'";
			$resultadoVerificar = mysqli_query($db, $sqlVerificar);
			
			if(!$resultadoVerificar) {
				echo json_encode(array(
					'success' => false, 
					'error' => 'Error al verificar contraseña actual',
					'debug' => mysqli_error($db)
				));
				exit();
			}
			
			$filaVerificar = mysqli_fetch_assoc($resultadoVerificar);
			
			if(!$filaVerificar) {
				echo json_encode(array(
					'success' => false, 
					'error' => 'Usuario no encontrado'
				));
				exit();
			}
			
			if($filaVerificar['pas_eje'] !== $password_actual) {
				echo json_encode(array(
					'success' => false, 
					'error' => 'La contraseña actual es incorrecta'
				));
				exit();
			}
			
			// ====================================================================
			// VALIDACIÓN 3: Nueva no es igual a la actual
			// ====================================================================
			if($password_actual === $password_nueva) {
				echo json_encode(array(
					'success' => false, 
					'error' => 'La nueva contraseña no puede ser igual a la actual'
				));
				exit();
			}
			
			// ====================================================================
			// VALIDACIÓN 4: Longitud mínima
			// ====================================================================
			if(strlen($password_nueva) < 8) {
				echo json_encode(array(
					'success' => false, 
					'error' => 'La contraseña debe tener al menos 8 caracteres'
				));
				exit();
			}
			
			// ====================================================================
			// VALIDACIÓN 5: Al menos 1 mayúscula
			// ====================================================================
			if(!preg_match('/[A-Z]/', $password_nueva)) {
				echo json_encode(array(
					'success' => false, 
					'error' => 'La contraseña debe contener al menos una mayúscula'
				));
				exit();
			}
			
			// ====================================================================
			// VALIDACIÓN 6: Al menos 1 número
			// ====================================================================
			if(!preg_match('/[0-9]/', $password_nueva)) {
				echo json_encode(array(
					'success' => false, 
					'error' => 'La contraseña debe contener al menos un número'
				));
				exit();
			}
			
			// ====================================================================
			// ACTUALIZAR CONTRASEÑA Y RESETEAR FLAGS
			// ====================================================================
			$sql = "
				UPDATE ejecutivo 
				SET 
					pas_eje = '$password_nueva',
					ult_cam_pas_eje = CURDATE(),
					req_cam_pas_eje = 0
				WHERE id_eje = '$id_eje'
			";
			
			$resultado = mysqli_query($db, $sql);
			
			if(!$resultado) {
				echo json_encode(array(
					'success' => false, 
					'error' => 'Error al actualizar la contraseña en la base de datos',
					'debug' => mysqli_error($db)
				));
				exit();
			}
			
			// Limpiar sesión
			if(isset($_SESSION['requiere_cambio'])) {
				unset($_SESSION['requiere_cambio']);
			}
			if(isset($_SESSION['motivo_cambio'])) {
				unset($_SESSION['motivo_cambio']);
			}
			
			echo json_encode(array(
				'success' => true, 
				'mensaje' => 'Contraseña actualizada correctamente'
			));
			exit();
			
		// ========================================================================
		// 📧 ENVIAR CORREO DE CONFIRMACIÓN DE CAMBIO DE CONTRASEÑA
		// ========================================================================
		} else if ( $_POST['estatus'] == 'EnviarCorreoConfirmacion' ) {
			
			// Validar que exista el parámetro necesario
			if( !isset($_POST['id_eje']) ) {
				echo json_encode(array(
					'success' => false,
					'error' => 'Falta el ID del ejecutivo'
				));
				exit();
			}
			
			$id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
			
			// 🔥 LLAMAR A LA FUNCIÓN DE ENVÍO
			$resultado = enviar_correo_cambio_password_ejecutivo($id_eje, $db);
			
			// Retornar respuesta
			echo json_encode($resultado);
			exit();
			
		// ========================================================================
		// DESPLIEGUE - OBTENER DATOS DE EJECUTIVO
		// ========================================================================
		} else if ( $_POST['estatus'] == 'Despliegue' ) {
			
			$id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
			$sql = "SELECT * FROM ejecutivo WHERE id_eje = '$id_eje'";
			
		// ========================================================================
		// SWITCH - CAMBIAR ESTATUS
		// ========================================================================
		} else if ( $_POST['estatus'] == 'Switch' ) {
			
			$id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
			$est_eje = mysqli_real_escape_string($db, $_POST['est_eje']);
			
			$sql = "
				UPDATE ejecutivo 
				SET est_eje = '$est_eje'
				WHERE id_eje = '$id_eje'
			";
			
		// ========================================================================
		// PERMISOS - CAMBIAR PERMISOS
		// ========================================================================
		} else if ( $_POST['estatus'] == 'Permisos' ) {
			
			$id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);
			$per_eje = mysqli_real_escape_string($db, $_POST['per_eje']);
			
			$sql = "
				UPDATE ejecutivo 
				SET per_eje = '$per_eje'
				WHERE id_eje = '$id_eje'
			";
		}

		// ========================================================================
		// EJECUTAR QUERY (EXCEPTO PARA CASOS ESPECIALES QUE YA SE EJECUTARON)
		// ========================================================================
		if($_POST['estatus'] != 'CambioPassword' && 
		   $_POST['estatus'] != 'VerificarPassword' && 
		   $_POST['estatus'] != 'EnviarCodigoCorreo' &&
		   $_POST['estatus'] != 'ValidarCodigoYGuardarCorreo' &&
		   $_POST['estatus'] != 'EnviarCorreoConfirmacion') {
			
			$resultado = mysqli_query( $db, $sql );

			// ====================================================================
			// REMOVER PERMISOS AHJ ENDE
			// ====================================================================
			if( isset( $_POST['per_eje'] ) && $_POST['per_eje'] == 0 ){
				
				$sqlEliminacionPlanteles = "
					DELETE FROM planteles_ejecutivo WHERE id_eje = '$id_eje'
				";
				
				$resultadoEliminacionPlanteles = mysqli_query( $db, $sqlEliminacionPlanteles );
				
				if( !$resultadoEliminacionPlanteles ){
					echo json_encode(array('error' => $sqlEliminacionPlanteles));
					exit();
				} else {
					$sqlEjecutivo = "
						SELECT * FROM ejecutivo WHERE id_eje = '$id_eje'
					";
					$datosEjecutivo = obtener_datos_consulta( $db, $sqlEjecutivo );
					$datosEjecutivo = $datosEjecutivo['datos'];
					$id_pla_aux = $datosEjecutivo['id_pla'];

					$sqlInsercionPlanteles = "
						INSERT INTO planteles_ejecutivo ( id_pla, id_eje ) VALUES ( $id_pla_aux, $id_eje )
					";
					$resultadoInsercionPlanteles = mysqli_query( $db, $sqlInsercionPlanteles );
					
					if( !$resultadoInsercionPlanteles ){
						echo json_encode(array('error' => $sqlInsercionPlanteles));
						exit();
					}
				}
			}

			// ====================================================================
			// ADICION DE CDEs A PERMISOS AHJ ENDE
			// ====================================================================
			if( isset( $_POST['per_eje'] ) && $_POST['per_eje'] == 2 ){
				
				$sqlEliminacionPlanteles = "
					DELETE FROM planteles_ejecutivo WHERE id_eje = '$id_eje' 
				";
				$resultadoEliminacionPlanteles = mysqli_query( $db, $sqlEliminacionPlanteles );

				if( !$resultadoEliminacionPlanteles ){
					echo json_encode(array('error' => $sqlEliminacionPlanteles));
					exit();
				} else {
					$sqlPlanteles = "
						SELECT * FROM plantel WHERE id_cad1 = 1
					";
					$resultadoPlanteles = mysqli_query( $db, $sqlPlanteles );

					if( !$resultadoPlanteles ){
						echo json_encode(array('error' => $sqlPlanteles));
						exit();
					} else {
						while( $filaPlanteles = mysqli_fetch_assoc( $resultadoPlanteles ) ){
							$id_pla = $filaPlanteles['id_pla'];
							$sqlInsercionPlanteles = "
								INSERT INTO planteles_ejecutivo ( id_pla, id_eje ) VALUES ( $id_pla, $id_eje )
							";
							$resultadoInsercionPlanteles = mysqli_query( $db, $sqlInsercionPlanteles );
							
							if( !$resultadoInsercionPlanteles ){
								echo json_encode(array('error' => $sqlInsercionPlanteles));
								exit();
							}
						}
					}
				}
			}

			// ====================================================================
			// VALIDAR RESULTADO
			// ====================================================================
			if ( !$resultado ) {
				echo json_encode(array('error' => $sql, 'mysql_error' => mysqli_error($db)));
			} else {
				$id_eje_insertado = mysqli_insert_id($db);
				
				if ( $_POST['estatus'] == 'Despliegue' ) {
					$datos = mysqli_fetch_assoc( $resultado );
					echo json_encode($datos);

				} else {
					// ============================================================
					// ASOCIAR/DISOCIAR id_pla EN planteles_ejecutivo
					// ============================================================
					if ( $_POST['estatus'] == 'Alta' && $_POST['ran_eje'] == 'GC' ){
						
						$sqlPlantel = "
							INSERT INTO planteles_ejecutivo ( id_eje, id_pla ) VALUES ( '$id_eje_insertado', '$id_pla' )
						";
						$resultadoPlantel = mysqli_query( $db, $sqlPlantel );
						
					} else if ( $_POST['estatus'] == 'Cambio' && $_POST['ran_eje'] != 'GC' ) {
						
						$sqlPlantelDelete = "
							DELETE FROM planteles_ejecutivo WHERE id_eje = '$id_eje'
						";
						$resultadoPlantelDelete = mysqli_query( $db, $sqlPlantelDelete );
						
					} else if ( $_POST['estatus'] == 'Cambio' && $_POST['ran_eje'] == 'GC' ) {
						
						$sqlPlantel = "
							INSERT INTO planteles_ejecutivo ( id_eje, id_pla ) VALUES ( '$id_eje', '$id_pla' )
						";
						$resultadoPlantel = mysqli_query( $db, $sqlPlantel );
						
					} else if( $_POST['estatus'] == 'Alta' && $_POST['ran_eje'] == 'GR' )  {
						
						$sqlPermisos = "
							UPDATE ejecutivo 
							SET per_eje = '1'
							WHERE id_eje = '$id_eje_insertado'
						";
						$resultadoPermisos = mysqli_query( $db, $sqlPermisos );

						if( !$resultadoPermisos ){
							echo json_encode(array('error' => $sqlPermisos));
							exit();
						}
					}
					
					echo json_encode(array('success' => 200));
				}
			}
		}
		
	// ============================================================================
	// BLOQUE SIN PROC: EXCEL, CONTEOS O REQUESTS INVÁLIDOS
	// ============================================================================
	} else {
		
		// ========================================================================
		// CONTEOS
		// ========================================================================
		if ( isset( $_POST['conteos'] )  ) {
			
			// Tu código de conteos aquí si lo necesitas
			echo json_encode(array('info' => 'Sección de conteos'));
			exit();
			
		// ========================================================================
		// EXCEL - EDICIÓN INLINE
		// ========================================================================
		} else if ( isset($_POST['campo']) && isset($_POST['accion']) ) {
			
			$campo = mysqli_real_escape_string($db, $_POST['campo']);
			$valor = mysqli_real_escape_string($db, $_POST['valor']);
			$accion = mysqli_real_escape_string($db, $_POST['accion']);
			$id_pla = mysqli_real_escape_string($db, $_POST['id_pla']);

			// ================================================================
			// ALTA DESDE EXCEL
			// ================================================================
			if ( $accion == "Alta" ) {
				
				if ( $campo == 'tel_eje' || $campo == 'nom_eje' || $campo == 'obs_eje' || $campo == 'cor_eje' || $campo == 'pas_eje' ) {
					$sql = "INSERT INTO ejecutivo ( $campo, id_pla, ult_cam_pas_eje, req_cam_pas_eje ) VALUES ( '$valor', '$id_pla', CURDATE(), 0 )";
				
				} else if ( $campo == 'ran_eje' ) {
					$sql = "INSERT INTO ejecutivo ( $campo, id_pla, ult_cam_pas_eje, req_cam_pas_eje ) VALUES ( '$valor', '$id_pla', CURDATE(), 0 )";
				}
				
				$resultado = mysqli_query( $db, $sql );

				if ( !$resultado ) {
					echo json_encode(array('error' => $sql, 'mysql_error' => mysqli_error($db)));
				} else {
					$id_eje = mysqli_insert_id($db);

					$sqlDatos = "SELECT * FROM ejecutivo WHERE id_eje = $id_eje";
					$datosConsulta = obtener_datos_consulta( $db, $sqlDatos );
					$datos = $datosConsulta['datos'];

					echo json_encode( array(
						'id_eje' => $id_eje, 
						'ing_eje' => fechaFormateadaCompacta($datos['ing_eje']), 
						'ran_eje' => $datos['ran_eje'],
						'nom_eje' => $datos['nom_eje'],
						'tel_eje' => $datos['tel_eje'],
						'cor_eje' => $datos['cor_eje'],
						'pas_eje' => $datos['pas_eje'],
						'obs_eje' => $datos['obs_eje']
					));
				}

			// ================================================================
			// CAMBIO DESDE EXCEL
			// ================================================================
			} else if ( $accion == "Cambio" ) {
				
				$id_eje = mysqli_real_escape_string($db, $_POST['id_eje']);

				if ( $campo == 'tel_eje' || $campo == 'nom_eje' || $campo == 'obs_eje' || $campo == 'ran_eje' || $campo == 'cor_eje' || $campo == 'pas_eje' ) {
					$sql = "
						UPDATE ejecutivo
						SET $campo = '$valor'
						WHERE id_eje = '$id_eje'
					";
				}

				$resultado = mysqli_query( $db, $sql );

				if ( !$resultado ) {
					echo json_encode(array('resultado' => 'error', 'mysql_error' => mysqli_error($db)));
				} else {
					// VERIFICAR SI DEBE ELIMINARSE
					$sqlDatos = "SELECT * FROM ejecutivo WHERE id_eje = $id_eje";
					$datosConsulta = obtener_datos_consulta( $db, $sqlDatos );
					$datos = $datosConsulta['datos'];

					if ( 
						$datos['nom_eje'] == '' &&
						$datos['ran_eje'] == '' &&
						$datos['tel_eje'] == '' &&
						$datos['cor_eje'] == '' &&
						$datos['pas_eje'] == '' &&
						$datos['obs_eje'] == ''
					) {
						$sqlEliminar = "
							UPDATE ejecutivo
							SET eli_eje = 'Inactivo'
							WHERE id_eje = '$id_eje'
						";

						$resultadoEliminar = mysqli_query( $db, $sqlEliminar );

						if ( !$resultadoEliminar ) {
							echo json_encode(array('resultado' => 'error query', 'error' => $sqlEliminar));
						} else {
							echo json_encode(array('resultado' => 'false'));
						}
					} else {
						echo json_encode(array('resultado' => 'exito'));
					}
				}
			}
			
		// ========================================================================
		// REQUEST INVÁLIDO
		// ========================================================================
		} else {
			echo json_encode(array(
				'error' => 'Parámetros inválidos o faltantes',
				'debug' => array(
					'tiene_proc' => isset($_POST['proc']),
					'tiene_conteos' => isset($_POST['conteos']),
					'tiene_campo' => isset($_POST['campo']),
					'tiene_accion' => isset($_POST['accion']),
					'post_recibido' => array_keys($_POST)
				)
			));
			exit();
		}
	}
?>