<?php  
	// CONTROLADOR DE ALTA, BAJA Y CAMBIO DE CANDIDATO
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	// Añade el header para JSON
	header('Content-Type: application/json');
	
	//// EXCEL
	$campo = $_POST['campo'];
	$valor = $_POST['valor'];
	$accion = $_POST['accion'];

	$id_mat6= $_POST['id_mat6'];


	if ( $accion == "Alta" ) {
		///////////////////////////////////////////////

	
		$sql = "INSERT INTO bloque ( $campo, id_mat6) VALUES ('$valor', '$id_mat6')";
		
		$resultado = mysqli_query( $db, $sql );

		if ( !$resultado ) {
			echo json_encode(['error' => $sql]);
		} else {
			//RETORNAR ULTIMO ID :D
			$id_pro = mysqli_insert_id($db);

			$sqlDatos = "SELECT * FROM bloque WHERE id_blo = $id_blo";
			$datos = obtener_datos_consulta( $db, $sqlDatos )['datos'];

			echo json_encode( [
				'id_blo' => $datos['id_blo'],
				'nom_blo' => $datos['nom_blo'], 
				'des_blo' => $datos['des_blo']

				//'sql' => $sql
			]);
			//echo json_encode(['ultimo_id' => $ultimo_id, 'mensaje' => $mensaje]);
			// MÁS DE 1 DATO
		}
		//////////////////////////////

	} else if ( $accion == "Cambio" ) {
		//////////////////////////////
		
		$id_mat = $_POST['id_blo'];

		//////////////////////////////////// EDICION 
			$sql = "
				UPDATE bloque
				SET
				$campo = '$valor'
				WHERE id_blo = '$id_blo'
			";

		$resultado = mysqli_query( $db, $sql );

		if ( !$resultado ) {
			echo json_encode(['resultado' => 'error']);
		} else {

			//echo 'eliminacion';
			/////////////////// ELIMINACION
			$sqlDatos = "SELECT * FROM bloque WHERE id_blo = $id_blo";
			$datos = obtener_datos_consulta( $db, $sqlDatos )['datos'];

			//echo $sqlDatos;

			if ( 
				$datos['nom_blo'] == '' &&
				$datos['des_blo'] == ''
			) {

				//echo 'entré a condicion de eliminacion';
				///////////////////////////////////
				$sqlEliminar = "
					DELETE FROM bloque WHERE id_blo = '$id_blo'
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
			/////////////////FIN ELIMINACION

			
		}
		/////////////////////////////////// FIN EDICION
		
		/////////////////////////////////
	}
	

	
?>