<?php  
	// CONTROLADOR DE ALTA, BAJA Y CAMBIO DE CITA
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	// Añade el header para JSON
	header('Content-Type: application/json');

	if ( isset( $_POST['conteos'] )  ) {
		
	   //// SE VACIO ESTE BLOQUE NO ES NECESARIO TENERLO, BORRAR DESPUÉS :D
	} else {
		//// EXCEL
		$campo = $_POST['campo'];
		
		$valor = isset($_POST['valor']) ? $_POST['valor'] : (isset($_POST['color']) ? $_POST['color'] : '');

		$accion = $_POST['accion'];

		if ( isset( $_POST['id_eje'] )  ) {
			$id_eje = $_POST['id_eje'];

			if( $_POST['id_eje']  == 'Todos' ){
				$id_eje = $id;
			}

		} else {
			$id_eje = $id;
		}

		if ( $accion == "Alta" ) {
			///////////////////////////////////////////////
			if ( $campo == 'id_eje_ejecutivo' ) {
				$cit_cit = date('Y-m-d', strtotime( date('Y-m-d'). ' +0 day'));
				$sql = "INSERT INTO cita ( id_eje3, cit_cit, id_eje_agendo ) VALUES ( '$valor', '$cit_cit', '$id' )";
			} else if( $campo == 'id_eje_agendo' ) {
				
				$cit_cit = date('Y-m-d', strtotime( date('Y-m-d'). ' +0 day'));
				$sql = "INSERT INTO cita ( id_eje3, cit_cit, id_eje_agendo ) VALUES ( '$valor', '$cit_cit', '$valor' )";


			}else if ( $campo == 'est_cit' ) {
				$cit_cit = date('Y-m-d', strtotime( date('Y-m-d'). ' +0 day'));
				$sql = "INSERT INTO cita ( $campo, id_eje3, cit_cit, id_eje_agendo ) VALUES ( '$valor', '$id_eje', '$cit_cit', '$id' )";

			} else if ( $campo == 'cit_cit' ) {

				$fecha = $valor;
				$partesFecha = explode('/', $fecha);
				$fechaFormatoMySQL = implode('-', array_reverse($partesFecha));

				$sql = "INSERT INTO cita ( $campo, id_eje3, id_eje_agendo ) VALUES ( '$fechaFormatoMySQL', '$id_eje', '$id' )";
			
			} else if ($campo == 'hor_cit') {
				

				$hor_cit = $valor;
			
				$hor_cit = date('H:i:s', strtotime($hor_cit));
				if (strtotime($hor_cit) < strtotime('09:00') || strtotime($hor_cit) > strtotime('20:00')) {
					$hor_cit = '13:00';
				}
				
				$fecha_actual = date('Y-m-d', strtotime( date('Y-m-d'). ' +0 day'));
				$cit_cit = $fecha_actual . ' ' . $hor_cit;

				

				// Insertar en la base de datos
				$sql = "INSERT INTO cita ($campo, id_eje3, cit_cit, id_eje_agendo) VALUES ('$hor_cit', '$id_eje', '$cit_cit', '$id')";
			} else {
				
				$cit_cit = date('Y-m-d', strtotime( date('Y-m-d'). ' +0 day'));
				$sql = "INSERT INTO cita ( $campo, id_eje3, cit_cit, id_eje_agendo ) VALUES ( '$valor', '$id_eje', '$cit_cit', '$id' )";
			}

			// echo "INSERt ----->".$sql;
			$resultado = mysqli_query( $db, $sql );

			if ( !$resultado ) {
				echo json_encode(['error' => $sql]);
			} else {
				//RETORNAR ULTIMO ID :D
				$id_cit = mysqli_insert_id($db);

				if( isset( $_POST['hor_cit'] ) ){
					$hor_cit = $_POST['hor_cit'];
					$hor_cit = date('H:i:s', strtotime($hor_cit));
					
					$sqlUpdate = "
						UPDATE cita
						SET
						hor_cit = '$hor_cit'
						WHERE id_cit = '$id_cit'
					";
					$resultadoUpdate = mysqli_query( $db, $sqlUpdate );

					if(!$resultadoUpdate){
						echo json_encode(['error' => $sqlUpdate]);
					}
				}

				$sqlDatos = "
					SELECT 
					cita.*,
					ejecutivo.nom_eje AS nom_eje,
					ejecutivo_cerrador.nom_eje AS ejecutivo_cerrador,
					ejecutivo_agendo.nom_eje AS nom_eje_agendo
					FROM cita
					INNER JOIN ejecutivo ON ejecutivo.id_eje = cita.id_eje3
					LEFT JOIN ejecutivo AS ejecutivo_cerrador ON ejecutivo_cerrador.id_eje = cita.id_eje_cerrador
					LEFT JOIN ejecutivo AS ejecutivo_agendo ON ejecutivo_agendo.id_eje = cita.id_eje_agendo
					WHERE cita.id_cit = '$id_cit'			
				";

				// echo 'SQL PRUEBA: '.$sqlDatos;

				$datos = obtener_datos_consulta( $db, $sqlDatos )['datos'];

				echo json_encode( [
					'fec_cit' => fechaFormateadaCompacta($datos['fec_cit']),
					'nom_eje' => $datos['nom_eje'],
					'can_cit' => $datos['can_cit'],
					'nom_eje_agendo' => $datos['nom_eje_agendo'],
					'nom_eje_cerrador' => $datos['ejecutivo_cerrador'],
					'tel_cit' => $datos['tel_cit'],
					'nom_cit' => $datos['nom_cit'],
					'eda_cit' => $datos['eda_cit'],
					'niv_cit' => $datos['niv_cit'],
					'obs_cit' => $datos['obs_cit'],
					'tip_cit' => $datos['tip_cit'],
					'cit_cit' => fechaFormateadaCompacta($datos['cit_cit']), 
					'id_cit' => $datos['id_cit'],
					'est_cit' => $datos['est_cit'],
					'efe_cit' => $datos['efe_cit'],
					'pro_cit' => $datos['pro_cit'],
					'cam_cit' => $datos['cam_cit'],
					'hor_cit' => horaFormateadaCompacta2($datos['hor_cit']),
					'sql' => $sql
				]);
				//echo json_encode(['ultimo_id' => $ultimo_id, 'mensaje' => $mensaje]);
				// MÁS DE 1 DATO
			}
			//////////////////////////////

		} else if ( $accion == "Cambio" ) {
			//////////////////////////////
			$bloque = 0;
			if ( isset( $_POST['id_alu_ram'] ) ) {
				$id_alu_ram = $_POST['id_alu_ram'];
				$sqlCita = "
					SELECT *
					FROM alumno
					INNER JOIN alu_ram ON alu_ram.id_alu1 = alumno.id_alu
					WHERE id_alu_ram = $id_alu_ram
				";
				$id_cit = obtener_datos_consulta( $db, $sqlCita )['datos']['id_cit1'];

			} else {
				$id_cit = $_POST['id_cit'];
			}
			

			//////////////////////////////////// EDICION
			if ( $campo == 'cit_cit' ) {

				if ($valor == '') {

					$sql = "
						UPDATE cita
						SET
						$campo = NULL
						WHERE id_cit = '$id_cit'
					";

					$bloque = 1;
				
				} else {

					$fecha = $valor;
					// Divide la fecha en un array usando '/' como delimitador
					$partesFecha = explode('/', $fecha);
					// Invierte el orden del array para tener el formato YYYY-MM-DD
					$fechaFormatoMySQL = implode('-', array_reverse($partesFecha));

					$sql = "
						UPDATE cita
						SET
						$campo = '$fechaFormatoMySQL'
						WHERE id_cit = '$id_cit'
					";

					$bloque = 2;

					// Esto te dará '2023-11-10'	
				}

			} else if( $campo == 'id_eje_cerrador' || $campo == 'id_eje_agendo' || $campo == 'id_eje_ejecutivo' ) {
				if ( $campo == 'id_eje_ejecutivo' ) {
					$campo = "id_eje3";
				}
				if ($valor == '') {
					if( $campo == 'id_eje3' ){
						
						// 
							//VALIDAR DE DÓNDE ES EL EJECUTIVO
							$sqlCita = "
								SELECT id_cit, id_eje3, id_pla
								FROM cita
								INNER JOIN ejecutivo ON ejecutivo.id_eje = cita.id_eje3
								WHERE id_cit = '$id_cit'
							";
							$datosCita = obtener_datos_consulta($db, $sqlCita)['datos'];

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
							if( $datosCita['id_pla'] == 2 ){
								// NAU
								$id_eje = 2958;
							} else if( $datosCita['id_pla'] == 3 ){
								// ECA
								$id_eje = 2959;
							} else if( $datosCita['id_pla'] == 6 ){
								// CUA
								$id_eje = 2960;
							} else if( $datosCita['id_pla'] == 8 ){
								// QRO
								$id_eje = 2962;
							} else if( $datosCita['id_pla'] == 9 ){
								// PACH
								$id_eje = 2963;
							} else if( $datosCita['id_pla'] == 13 ){
								// SLP
								$id_eje = 2961;
							}
							
						// 
						$sql = "
							UPDATE cita
							SET
							$campo = $id_eje
							WHERE id_cit = '$id_cit'
						";
					} else {
						$sql = "
							UPDATE cita
							SET
							$campo = NULL
							WHERE id_cit = '$id_cit'
						";
					}
					$bloque = 5;
				
				} else {
					$sql = "
						UPDATE cita
						SET
						$campo = '$valor'
						WHERE id_cit = '$id_cit'
					";
				}

			} else if ( $campo == 'hor_cit' ) {

				$hor_cit = $valor;

				$sqlCita = "SELECT * FROM cita WHERE id_cit = '$id_cit'";
				$datosCita = obtener_datos_consulta($db, $sqlCita)['datos'];

				$hor_cit = date('H:i:s', strtotime($hor_cit));

				$sql = "
					UPDATE cita
					SET
					$campo = '$hor_cit'
					WHERE id_cit = '$id_cit'
				";

				$bloque = 3;

		
				
			} else {

				if( $campo == 'efe_cit' ) {
					// EFECTIVIDAD DE CITA -> DECREMENTO EN cam_cit
					$sql = "
						UPDATE cita
						SET
						$campo = '$valor'
						WHERE id_cit = '$id_cit'
					";
					// F EFECTIVIDAD DE CITA -> DECREMENTO EN cam_cit
				} else {
					// Cualquier otro campo sin afectar cam_cit
					$sql = "
						UPDATE cita
						SET
						$campo = '$valor'
						WHERE id_cit = '$id_cit'
					";
				}				

				
				$bloque = 4;
			}

			// echo 'UPDATe: '.$sql;
			// echo 'BLOQUE: '.$bloque;

			$resultado = mysqli_query( $db, $sql );

			if ( !$resultado ) {

				// echo $sql;
				//echo 'no eliminacion';
				echo json_encode([
					'resultado' => $sql,
					'bloque' => $bloque
				]);
			} else {

				if( $campo == 'est_cit' && $valor == 'Registro' ){

					$sqlUpdate2 = "
						UPDATE cita
						SET
						efe_cit = 'CITA EFECTIVA'
						WHERE id_cit = '$id_cit'
					";
					
					$resultadoUpdate2 = mysqli_query( $db, $sqlUpdate2 );
					if( !$resultadoUpdate2 ){
						echo $sqlUpdate2;
					}
				}

				//echo 'eliminacion';
				/////////////////// ELIMINACION
				$sqlDatos = "SELECT * FROM cita WHERE id_cit = $id_cit";
				$datos = obtener_datos_consulta( $db, $sqlDatos )['datos'];

				// echo $sqlDatos;

				// echo "nom_cit: " . $datos['nom_cit'];
				// echo "tip_cit: " . $datos['tip_cit'];
				// echo "can_cit: " . $datos['can_cit'];
				// echo "cit_cit: " . $datos['cit_cit'];
				// echo "est_cit: " . $datos['est_cit'];
				// echo "tel_cit: " . $datos['tel_cit'];
				// echo "obs_cit: " . $datos['obs_cit'];

				if ( 
					$datos['nom_cit'] == '' &&
					// $datos['niv_cit'] == '' &&
					$datos['tip_cit'] == '' &&
					$datos['can_cit'] == '' &&
					// ( $datos['cit_cit'] == '' || $datos['cit_cit'] == null || $datos['cit_cit'] == '0000-00-00 00:00:00'  ) &&
					$datos['est_cit'] == '' &&
					$datos['tel_cit'] == '' &&
					$datos['obs_cit'] == ''
				) {

					// echo 'entré a condicion de eliminacion';
					///////////////////////////////////
					$sqlEliminar = "
						DELETE FROM cita WHERE id_cit = '$id_cit'
					";

					$resultadoEliminar = mysqli_query( $db, $sqlEliminar );

					if ( !$resultadoEliminar ) {
						//echo $sqlEliminar;
						echo json_encode(['resultado' => 'error query']);
					} else {
						//////RETORNA 'false', IMPLICA BORRAR EN FRONTEND
						echo json_encode(['resultado' => 'false']);
					}
					////////////////////////////////////
				} else {

					// echo 'no entro';
					echo json_encode([
						'resultado' => 'exito',
						'sql' => $sql,
					]);
				}
				///////////////////FIN ELIMINACION

				
			}
			/////////////////////////////////// FIN EDICION
			
			/////////////////////////////////
		} else if ( $accion == "color" ) {
			//////////////////////////////
			// NUEVA FUNCIONALIDAD: MANEJO DE COLORES DE CELDAS
			//////////////////////////////
			
			$id_cit = $_POST['id_cit'];
			$campo = $_POST['campo'];
			$color = $_POST['color'];
			
			// Validar que el id_cit sea válido
			if (!$id_cit || !is_numeric($id_cit)) {
				echo json_encode(['resultado' => 'error', 'mensaje' => 'ID de cita inválido']);
				exit;
			}
			
			// Validar que el campo sea válido (no est_cit ni efe_cit)
			$campos_prohibidos = ['est_cit', 'efe_cit'];
			if (in_array($campo, $campos_prohibidos)) {
				echo json_encode(['resultado' => 'error', 'mensaje' => 'Campo no permitido para colorear']);
				exit;
			}
			
			// Si el color está vacío, eliminar el registro
			if (empty($color)) {
				$sqlEliminarColor = "DELETE FROM color_cita WHERE id_cit5 = ? AND campo_cita = ?";
				$stmt = mysqli_prepare($db, $sqlEliminarColor);
				
				if ($stmt) {
					mysqli_stmt_bind_param($stmt, "is", $id_cit, $campo);
					$resultadoEliminar = mysqli_stmt_execute($stmt);
					mysqli_stmt_close($stmt);
					
					if ($resultadoEliminar) {
						echo json_encode(['resultado' => 'exito', 'mensaje' => 'Color removido']);
					} else {
						echo json_encode(['resultado' => 'error', 'mensaje' => 'Error al remover color']);
					}
				} else {
					echo json_encode(['resultado' => 'error', 'mensaje' => 'Error en la consulta']);
				}
			} else {
				// Validar que el color sea válido
				$colores_validos = ['verde_pastel', 'azul_pastel', 'naranja_pastel'];
				if (!in_array($color, $colores_validos)) {
					echo json_encode(['resultado' => 'error', 'mensaje' => 'Color no válido']);
					exit;
				}
				
				// Insertar o actualizar el color usando ON DUPLICATE KEY UPDATE
				$sqlColor = "
					INSERT INTO color_cita (id_cit5, campo_cita, color_celda, fec_col_cit) 
					VALUES (?, ?, ?, NOW()) 
					ON DUPLICATE KEY UPDATE 
					color_celda = VALUES(color_celda), 
					fec_col_cit = NOW()
				";
				
				$stmt = mysqli_prepare($db, $sqlColor);
				
				if ($stmt) {
					mysqli_stmt_bind_param($stmt, "iss", $id_cit, $campo, $color);
					$resultadoColor = mysqli_stmt_execute($stmt);
					mysqli_stmt_close($stmt);
					
					if ($resultadoColor) {
						echo json_encode(['resultado' => 'exito', 'mensaje' => 'Color aplicado correctamente']);
					} else {
						echo json_encode(['resultado' => 'error', 'mensaje' => 'Error al aplicar color']);
					}
				} else {
					echo json_encode(['resultado' => 'error', 'mensaje' => 'Error en la consulta preparada']);
				}
			}
			//////////////////////////////
			// FIN MANEJO DE COLORES
			//////////////////////////////
		}
		////
	}	
?>