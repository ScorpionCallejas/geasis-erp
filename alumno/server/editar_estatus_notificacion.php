<?php 
	//ARCHIVO VIA AJAX PARA EDITAR ESTATUS DE NOTIFICACION DE PAGO PARA cobranza
	//footer.php////obtener_notificaciones.php
	require('../inc/cabeceras.php');

	$tipo = $_POST['tipo'];
	$identificador = $_POST['identificador'];

	if ( ( $tipo == 'Ingreso' ) || ( $tipo == 'Egreso' ) ) {
		// SALDO DIGITAL
		
		$sql = "
			UPDATE historial_saldo
			SET
			est_his_sal = 'Enterado'
			WHERE
			id_his_sal = '$identificador'
		";

		$resultado = mysqli_query($db, $sql);

		if ($resultado) {

			echo 'Exito';
		
		} else {
		
			echo $sql;
		
		}

		// FIN SALDO DIGITAL
	} else {
		//  COBRANZA

		$sql = "
			UPDATE historial_pago
			SET
			est_his_pag = 'Enterado'
			WHERE
			id_his_pag = '$identificador'
		";

		$resultado = mysqli_query($db, $sql);

		if ($resultado) {

			echo 'Exito';
		
		} else {
		
			echo $sql;
		
		}

		// FIN COBRANZA
	}

	

	
?>