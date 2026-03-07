<?php  
	// CONTROLADOR DE ALTA, BAJA Y CAMBIO DE CANDIDATO
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	// Añade el header para JSON
	header('Content-Type: application/json');

	if ( isset( $_POST['conteos'] )  ) {
		//// CONTEOS
		$inicio = $_POST['inicio'];
		$fin = $_POST['fin'];

		//echo json_encode(['resultado' => 'conteo']);
		$total = 0;

		$sql = "SELECT obtener_total_informes_ejecutivo( '$inicio', '$fin', $id ) AS total";

	    //echo $sql;
	    $datos = obtener_datos_consulta($db, $sql);
	    $total_informes = $datos['datos']['total'];
	    

	    $sql2 = "SELECT obtener_total_citados_ejecutivo( '$inicio', '$fin', $id ) AS total";
	    //echo $sql;
	    $datos = obtener_datos_consulta($db, $sql2);
	    $total_citados = $datos['datos']['total'];

	    $sql3 = "SELECT obtener_total_entrevistados_ejecutivo( '$inicio', '$fin', $id ) AS total";
	    //echo $sql;
	    $datos = obtener_datos_consulta($db, $sql3);
	    $total_entrevistados = $datos['datos']['total'];

	    $sql4 = "SELECT obtener_total_procesos_ejecutivo( '$inicio', '$fin', $id ) AS total";
	    //echo $sql;
	    $datos = obtener_datos_consulta($db, $sql4);
	    $total_procesos = $datos['datos']['total'];


	    $sql5 = "SELECT obtener_total_regresos_ejecutivo( '$inicio', '$fin', $id ) AS total";
	    //echo $sql;
	    $datos = obtener_datos_consulta($db, $sql5);
	    $total_regresos = $datos['datos']['total'];

	    $total = $total_regresos + $total_procesos + $total_entrevistados + $total_citados + $total_informes;
	    
	    echo json_encode([
		    'total' => $total,
		    'total_informes' => $total_informes,
		    'total_regresos' => $total_regresos,
		    'total_procesos' => $total_procesos,
		    'total_entrevistados' => $total_entrevistados,
		    'total_citados' => $total_citados
		]);
		////////
	} else {
		//// EXCEL
		$campo = $_POST['campo'];
		$valor = $_POST['valor'];
		$accion = $_POST['accion'];
		

		if ( $accion == "Alta" ) {
			///////////////////////////////////////////////
			$id_eje = $_POST['id_eje'];
			if ( $campo == 'tel_can' || $campo == 'nom_can' || $campo == 'obs_can' ) {
				$fec_ent_can = date('Y-m-d', strtotime( date('Y-m-d'). ' +1 day'));
				$sql = "INSERT INTO candidato ( $campo, id_pla, id_eje, fec_ent_can ) VALUES ( '$valor', '$plantel', '$id_eje', '$fec_ent_can' )";
			
			} else if ( $campo == 'est_can' ) {
				$fec_ent_can = date('Y-m-d', strtotime( date('Y-m-d'). ' +1 day'));
				$sql = "INSERT INTO candidato ( $campo, id_pla, id_eje, fec_ent_can ) VALUES ( '$valor', '$plantel', '$id_eje', '$fec_ent_can' )";

			} else if ( $campo == 'fec_ent_can' ) {

				$fecha = $valor;

				// Divide la fecha en un array usando '/' como delimitador
				$partesFecha = explode('/', $fecha);

				// Invierte el orden del array para tener el formato YYYY-MM-DD
				$fechaFormatoMySQL = implode('-', array_reverse($partesFecha));

				// Esto te dará '2023-11-10'
				$sql = "INSERT INTO candidato ( $campo, id_pla, id_eje ) VALUES ( '$fechaFormatoMySQL', '$plantel', '$id_eje' )";
			}
			
			$resultado = mysqli_query( $db, $sql );

			if ( !$resultado ) {
				echo json_encode(['error' => $sql]);
			} else {
				//RETORNAR ULTIMO ID :D
				$id_can = mysqli_insert_id($db);

				$sqlDatos = "SELECT * FROM candidato WHERE id_can = $id_can";
				$datos = obtener_datos_consulta( $db, $sqlDatos )['datos'];

				echo json_encode( [
					'id_can' => $id_can, 
					'fec_reg_can' => fechaFormateadaCompacta($datos['fec_reg_can']), 
					'fec_ent_can' => fechaFormateadaCompacta($datos['fec_ent_can']), 
					'est_can' => $datos['est_can'],
					'nom_can' => $datos['nom_can'],
					'tel_can' => $datos['tel_can'],
					'obs_can' => $datos['obs_can']
					//'sql' => $sql
				]);
				//echo json_encode(['ultimo_id' => $ultimo_id, 'mensaje' => $mensaje]);
				// MÁS DE 1 DATO
			}
			//////////////////////////////

		} else if ( $accion == "Cambio" ) {
			//////////////////////////////
			
			$id_can = $_POST['id_can'];

			//////////////////////////////////// EDICION
			if ( $campo == 'tel_can' || $campo == 'nom_can' || $campo == 'obs_can' || $campo == 'est_can' ) {
				$sql = "
					UPDATE candidato
					SET
					$campo = '$valor'
					WHERE id_can = '$id_can'
				";
			
			} else if ( $campo == 'fec_ent_can' ) {

				$fecha = $valor;

				// Divide la fecha en un array usando '/' como delimitador
				$partesFecha = explode('/', $fecha);

				// Invierte el orden del array para tener el formato YYYY-MM-DD
				$fechaFormatoMySQL = implode('-', array_reverse($partesFecha));

				// Esto te dará '2023-11-10'
				$sql = "
					UPDATE candidato
					SET
					$campo = '$fechaFormatoMySQL'
					WHERE id_can = '$id_can'
				";
			}

			$resultado = mysqli_query( $db, $sql );

			if ( !$resultado ) {
				echo json_encode(['resultado' => 'error']);
			} else {

				//echo 'eliminacion';
				/////////////////// ELIMINACION
				$sqlDatos = "SELECT * FROM candidato WHERE id_can = $id_can";
				$datos = obtener_datos_consulta( $db, $sqlDatos )['datos'];

				//echo $sqlDatos;

				if ( 
					$datos['nom_can'] == '' &&
					( $datos['fec_ent_can'] == '' || $datos['fec_ent_can'] == null || $datos['fec_ent_can'] == '0000-00-00 00:00:00'  ) &&
					$datos['est_can'] == '' &&
					$datos['tel_can'] == '' &&
					$datos['obs_can'] == ''
				) {

					//echo 'entré a condicion de eliminacion';
					///////////////////////////////////
					$sqlEliminar = "
						DELETE FROM candidato WHERE id_can = '$id_can'
					";

					$resultadoEliminar = mysqli_query( $db, $sqlEliminar );

					if ( !$resultadoEliminar ) {
						echo $sqlEliminar;
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
	}

	

	
?>