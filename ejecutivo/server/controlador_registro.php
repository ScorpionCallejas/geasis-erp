<?php  
	// CONTROLADOR DE ALTA, BAJA Y CAMBIO DE CANDIDATO
	require('../inc/cabeceras.php');
	require('../inc/funciones.php');

	// Añade el header para JSON
	header('Content-Type: application/json');

	if ( $_POST['accion'] == 'Cambio' ) {
		/////////////////EDICION
		$id_alu_ram = $_POST['id_alu_ram'];
		
		$campo = $_POST['campo'];
		$valor = $_POST['valor'];
		
		$sql = "
			UPDATE alu_ram
			SET
			$campo = '$valor'
			WHERE id_alu_ram = '$id_alu_ram'
		";
		
		$resultado = mysqli_query( $db, $sql );

		if ( $resultado ) {
			echo json_encode(['success' => '200']);
		} else {
			echo json_encode(['error' => '500']);
		}
	}
	

	

	
?>