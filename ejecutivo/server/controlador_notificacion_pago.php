<?php  
    // CONTROLADOR DE ALTA, BAJA Y CAMBIO DE CANDIDATO
    require('../inc/cabeceras.php');
    require('../inc/funciones.php');

	$id_not_pag = $_POST['id_not_pag'];
	$est_not_pag = $_POST['est_not_pag'];
 	
	$sql = "
		UPDATE notificacion_pago
		SET
		est_not_pag = '$est_not_pag'
		WHERE id_not_pag = '$id_not_pag'
	";
	$resultado = mysqli_query( $db, $sql );
	if ( !$resultado ) {
		echo $sql;
	} else {
		
		$sqlPago = "SELECT * FROM notificacion_pago WHERE id_not_pag = '$id_not_pag'";
		$id_pag = obtener_datos_consulta($db, $sqlPago)['datos']['id_pag11'];

		if( $est_not_pag == 'Rechazar' ){
			$sqlUpdatePago = "DELETE FROM pago WHERE id_pag = $id_pag";
		} else {
			$sqlUpdatePago = "
				UPDATE pago
				SET
				not_pag = '$est_not_pag'
				WHERE id_pag = '$id_pag'
			";
		}

		$resultadoUpdatePago = mysqli_query( $db, $sqlUpdatePago );

		if( !$resultadoUpdatePago ){
			echo $sqlUpdatePago;
		}
	}
	
?>