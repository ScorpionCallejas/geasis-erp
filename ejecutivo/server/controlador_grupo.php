<?php  
    // CONTROLADOR DE ALTA, BAJA Y CAMBIO DE CANDIDATO
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

    header('Content-Type: application/json');
	if( isset( $_POST['id_gen'] ) ){
		
		$id_gen = $_POST['id_gen'];
		$sql = "SELECT * FROM generacion WHERE id_gen = $id_gen";
		$resultado = mysqli_query($db, $sql);

		if ($resultado) {
			$response = array();
			$datos = mysqli_fetch_assoc($resultado);
			
			$response['data'] = $datos;
			$response['status'] = 200;
			$response['message'] = "Respuesta exitosa";
		} else {
			$response['status'] = 500;
			$response['message'] = "Error en la consulta";
			$response['query'] = $sql;
		}

		echo json_encode($response);
	} else {
		//////////
		if( isset( $_POST['palabra'] ) || isset( $_POST['estatus'] )  ){
			// BUSQUEDA O ESTATUS
	
			$sql = "
				SELECT *, obtener_estatus_generacion( ini_gen, fin_gen ) AS estatus_generacion
				FROM generacion
				INNER JOIN rama ON rama.id_ram = generacion.id_ram5
				INNER JOIN plantel ON plantel.id_pla = rama.id_pla1
				WHERE id_pla1 = '$plantel'
			";
	
			if ( ( isset( $_POST['palabra'] ) ) && ( ( $_POST['palabra'] ) != '' ) ) {
	
				$palabra = $_POST['palabra'];
				$sqlAlumnos = " AND ";
				$sqlAlumnos .= " 
					( ( id_gen LIKE '%$palabra%' ) OR 
					( UPPER( nom_gen ) LIKE UPPER( _utf8 '%$palabra%') COLLATE utf8_general_ci )
				";
			} else {
				$estatus = $_POST['estatus'];
				$sqlAlumnos = " AND ( ";
	
				if( $estatus == 'En curso' ){
					$sqlAlumnos .= " 
						CURDATE() >= ini_gen AND CURDATE() <= fin_gen
					";
				} else if( $estatus == 'Fin curso' ){
					$sqlAlumnos .= " 
						CURDATE() > fin_gen
					";
				} else if( $estatus == 'Por comenzar' ){
					$sqlAlumnos .= " 
						CURDATE() < ini_gen
					";
				}
			}
	
			$sqlAlumnos .= ') ORDER BY id_gen DESC ';  
	
			$sql .= $sqlAlumnos;
	
			//echo $sql;
	
			$resultado = mysqli_query( $db, $sql );
	
			$alumnos = [];
	
			//echo $sql;
			while ($fila = mysqli_fetch_assoc($resultado)) {
				$id_gen = $fila['id_gen'];
				$sql = "
					SELECT obtener_total_alumnos_generacion( $id_gen ) AS total
				";
				$total_alumnos = obtener_datos_consulta( $db, $sql )['datos']['total'];
	
				$sql = "
					SELECT obtener_deudores_alumnos_generacion( $id_gen ) AS total
				";
				$total_deudores = obtener_datos_consulta( $db, $sql )['datos']['total'];
	
				////////////////////////////////////////////////
				$sqlTotal = "
					SELECT SUM( mon_ori_pag ) AS potencial 
					FROM vista_pagos
					WHERE 
					( id_gen1 = '$id_gen' ) AND
					( estatus_general != 'Baja definitiva' AND estatus_general !='Suspendido' )  
				";
	
				// echo $sqlTotal;
	
				$resultadoTotal = mysqli_query( $db, $sqlTotal );
	
				$filaTotal = mysqli_fetch_assoc( $resultadoTotal );
	
				$potencial = $filaTotal['potencial'];
	
				if ( $potencial == '' ) {
					$potencial = 0;
				}
	
				$sqlTotal = "
					SELECT SUM( mon_pag ) AS adeudo 
					FROM vista_pagos
					WHERE 
					( id_gen1 = '$id_gen' ) AND
					( estatus_general != 'Baja definitiva' AND estatus_general !='Suspendido' )  
				";
	
				// echo $sqlTotal;
	
				$resultadoTotal = mysqli_query( $db, $sqlTotal );
	
				$filaTotal = mysqli_fetch_assoc( $resultadoTotal );
	
				$adeudo = $filaTotal['adeudo'];
	
				if ( $adeudo == '' ) {
					$adeudo = 0;
				}
	
				$cobrado = round( $potencial - $adeudo, 2 );
	
				if ( $potencial == 0 ) {
					$porcentaje = "0%";
				} else {
					$porcentaje = round( ( $cobrado / $potencial ) * 100, 2 )."%";
				}
				////////////////////////////////////////////////
	
				$alumno = [
					"ID" => '<a class="btn-link text-primary"  target="_blank" href="alumnos.php?id_gen='.$fila['id_gen'].'">'.$fila['id_gen'].'</a>',
					"GPO" => $fila['nom_gen'],
					"INICIO" => fechaFormateadaCompacta($fila['ini_gen']),
					"FIN" => fechaFormateadaCompacta($fila['fin_gen']),
					"PROGRAMA" => $fila['nom_ram'],
					"T_ALUMNOS" => $total_alumnos,
					"DEUDORES" => $total_deudores,
					
					"COBRADO" => formatearDinero($cobrado),
					"POTENCIAL" => formatearDinero($potencial),
					"POR COBRAR" => formatearDinero($adeudo),
					"PORCENTAJE" => $porcentaje,
					"TRAMITE" => "######",
					"ESTADO" => $fila['estatus_generacion'],
					"ID." => $fila['id_gen'],
				];
	
				$alumnos[] = $alumno;
			}
			
			echo json_encode($alumnos);
		// FIN BUSQUEDA
		} else {
		// ALTA, BAJA Y CAMBIO
			//// EXCEL
			
			$accion = $_POST['accion'];
			if ( $accion == "Alta" ) {
				///////////////////////////////////////////////
				
				$nom_gen = $_POST['nom_gen'];
				$ini_gen = $_POST['ini_gen'];
				$fin_gen = $_POST['fin_gen'];
				$id_ram5 = $_POST['id_ram'];
	
				///// OBTENER MONTO PROGRAMA
				$sqlPrograma = "SELECT * FROM rama WHERE id_ram = $id_ram5";
				$cos_ram = obtener_datos_consulta( $db, $sqlPrograma )['datos']['cos_ram'];
				///// FIN OBTENER MONTO PROGRAMA
				$mon_gen = $cos_ram;
	
				$sql = "INSERT INTO generacion (nom_gen, ini_gen, fin_gen, id_ram5, mon_gen) VALUES ('$nom_gen', '$ini_gen', '$fin_gen', '$id_ram5', '$mon_gen')";
	
				$resultado = mysqli_query( $db, $sql );
	
				if ( !$resultado ) {
					echo json_encode(['error' => $sql]);
				} else {
					//RETORNAR ULTIMO ID :D
					$id_gen = mysqli_insert_id($db);
	
					$sqlDatos = "
						SELECT * 
						FROM generacion
						INNER JOIN rama ON rama.id_ram = generacion.id_ram5
						WHERE id_gen = '$id_gen'
					";
					$datos = obtener_datos_consulta( $db, $sqlDatos )['datos'];
	
					echo json_encode( [
						'id_gen' => $datos['id_gen'],
						'nom_gen' => $datos['nom_gen'],
						'ini_gen' => $datos['ini_gen'],
						'fin_gen' => $datos['fin_gen'],
						'nom_ram' => $datos['nom_ram'],
						'sql' => $sql
					]);
					//echo json_encode(['ultimo_id' => $ultimo_id, 'mensaje' => $mensaje]);
					// MÁS DE 1 DATO
				}
				//////////////////////////////
				//echo $sql;
				//////////////////////////////
	
			} else if ( $accion == "Cambio" ) {
				//////////////////////////////
				$campo = $_POST['campo'];
				$valor = $_POST['valor'];
				
				////
				$id_gen = $_POST['id_gen_aux'];
	
				//////////////////////////////////// EDICION
				if ( $campo == 'ini_gen' || $campo == 'fin_gen' ) {
	
					if ( $valor == '' ) {
	
						$sql = "
							UPDATE generacion
							SET
							$campo = NULL
							WHERE id_gen = '$id_gen'
						";
	
					} else {
	
						$fecha = $valor;
						// Divide la fecha en un array usando '/' como delimitador
						$partesFecha = explode('/', $fecha);
						// Invierte el orden del array para tener el formato YYYY-MM-DD
						$fechaFormatoMySQL = implode('-', array_reverse($partesFecha));
	
						$sql = "
							UPDATE generacion
							SET
							$campo = '$fechaFormatoMySQL'
							WHERE id_gen = '$id_gen'
						";
	
						// Esto te dará '2023-11-10'	
					}
					
	
				} else {
					$sql = "
						UPDATE generacion
						SET
						$campo = '$valor'
						WHERE id_gen = '$id_gen'
					";
				}
				////
	
				$resultado = mysqli_query( $db, $sql );
	
				if ( !$resultado ) {
	
					//echo $sql;
					echo json_encode(['resultado' => $sql]);
				} else {
	
					//echo 'eliminacion';
					/////////////////// ELIMINACION
					$sqlDatos = "SELECT * FROM generacion WHERE id_gen = $id_gen";
					$datos = obtener_datos_consulta( $db, $sqlDatos )['datos'];
	
					//echo $sqlDatos;
	
					if ( 
						$datos['nom_gen'] == '' &&
						$datos['ini_gen'] == '' &&
						$datos['fin_gen'] == '' 
					) {
	
						//echo 'entré a condicion de eliminacion';
						///////////////////////////////////
						$sqlEliminar = "
							DELETE FROM generacion WHERE id_gen = '$id_gen'
						";

						// echo $sqlEliminar;
	
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
						echo json_encode(['resultado' => 'exito']);
					}
					///////////////////FIN ELIMINACION
	
					
				}
				/////////////////////////////////// FIN EDICION
				
				/////////////////////////////////
			}
			////
		// FIN ALTA, BAJA Y CAMBIO
		}
		//////////
	}
	
?>